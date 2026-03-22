<?php

/**
 * Formulario para subir archivo PDF de consentimiento informado
 */

session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../../index.php");
    exit();
}

require_once(__DIR__ . '/../../conexion.php');

$usuario = $_SESSION['usuario'];
$nombre = $_SESSION['nombre'];
$tipo_usuario = $_SESSION['tipo_usuario'];
$cod_dane_ie = $_SESSION['cod_dane_ie'];
$id_usu = $_SESSION['id'];

$id_consentimiento = $_GET['id'] ?? 0;
$num_doc_est = $_GET['num_doc_est'] ?? 0;

$nuevo_registro = ($id_consentimiento == 0);

// Obtener información del estudiante
if ($nuevo_registro) {
    // Crear nuevo registro
    $query = "SELECT * FROM estudiantes WHERE num_doc_est = '$num_doc_est'";
    $result = mysqli_query($mysqli, $query);
    $row = mysqli_fetch_assoc($result);

    if (!$row) {
        die("Estudiante no encontrado");
    }
} else {
    // Editar registro existente
    $query = "SELECT e.*, c.* 
              FROM consentimientoInformado c
              INNER JOIN estudiantes e ON c.num_doc_est = e.num_doc_est
              WHERE c.id_consentimientoInformado = $id_consentimiento";
    $result = mysqli_query($mysqli, $query);
    $row = mysqli_fetch_assoc($result);

    if (!$row) {
        die("Registro no encontrado");
    }
}

// Procesar subida de archivo
$mensaje = '';
$tipo_mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['archivo_pdf'])) {
    $file = $_FILES['archivo_pdf'];

    if ($file['error'] === UPLOAD_ERR_OK) {
        // Validar tipo de archivo - Se acepta PDF (recomendado), DOC y DOCX
        $allowed_types = [
            'application/pdf',
            'application/msword', // .doc
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' // .docx
        ];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (in_array($mime_type, $allowed_types)) {
            // Crear directorio si no existe
            $uploadDir = __DIR__ . '/../../uploads/consentimientos/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Generar nombre único para el archivo
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $fileName = 'consentimiento_' . $num_doc_est . '_' . time() . '.' . $extension;
            $filePath = $uploadDir . $fileName;

            // Si es un registro existente, eliminar archivo anterior si existe
            if (!$nuevo_registro && !empty($row['archivo_consentimientoInformado'])) {
                $oldFile = $uploadDir . $row['archivo_consentimientoInformado'];
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }

            // Mover archivo subido
            if (move_uploaded_file($file['tmp_name'], $filePath)) {
                $fecha_actual = date('Y-m-d H:i:s');

                if ($nuevo_registro) {
                    // Crear nuevo registro
                    $insertQuery = "INSERT INTO consentimientoInformado 
                                   (num_doc_est, nombre_quien_sube_consentimientoInformado, 
                                    rol_quien_sube_consentimientoInformado, archivo_consentimientoInformado,
                                    estado_consentimientoInformado, fecha_alta_consentimientoInformado, id_usu) 
                                   VALUES 
                                   ('$num_doc_est', '$nombre', '$tipo_usuario', '$fileName', 1, '$fecha_actual', $id_usu)";

                    if (mysqli_query($mysqli, $insertQuery)) {
                        // Actualizar estado del estudiante
                        $updateEstQuery = "UPDATE estudiantes SET estado_consentimientoInformado = 1 WHERE num_doc_est = '$num_doc_est'";
                        mysqli_query($mysqli, $updateEstQuery);

                        $tipo_mensaje = 'success';
                        $mensaje = 'Archivo subido correctamente.';

                        // Obtener el ID del nuevo registro
                        $id_consentimiento = mysqli_insert_id($mysqli);

                        // Recargar datos con el nuevo ID
                        $query = "SELECT e.*, c.* 
                                  FROM consentimientoInformado c
                                  INNER JOIN estudiantes e ON c.num_doc_est = e.num_doc_est
                                  WHERE c.id_consentimientoInformado = $id_consentimiento";
                        $result = mysqli_query($mysqli, $query);
                        if ($result) {
                            $row = mysqli_fetch_assoc($result);
                            $nuevo_registro = false;
                        }
                        // Asegurar que $row tenga datos
                        if (!$row) {
                            $query = "SELECT * FROM estudiantes WHERE num_doc_est = '$num_doc_est'";
                            $result = mysqli_query($mysqli, $query);
                            $row = mysqli_fetch_assoc($result);
                        }
                    } else {
                        $tipo_mensaje = 'danger';
                        $mensaje = 'Error al guardar en la base de datos: ' . mysqli_error($mysqli);
                    }
                } else {
                    // Actualizar registro existente
                    $updateQuery = "UPDATE consentimientoInformado 
                                   SET archivo_consentimientoInformado = '$fileName',
                                       fecha_edit_consentimientoInformado = '$fecha_actual'
                                   WHERE id_consentimientoInformado = $id_consentimiento";

                    if (mysqli_query($mysqli, $updateQuery)) {
                        $tipo_mensaje = 'success';
                        $mensaje = 'Archivo actualizado correctamente.';
                        // Recargar datos
                        $query = "SELECT e.*, c.* 
                                  FROM consentimientoInformado c
                                  INNER JOIN estudiantes e ON c.num_doc_est = e.num_doc_est
                                  WHERE c.id_consentimientoInformado = $id_consentimiento";
                        $result = mysqli_query($mysqli, $query);
                        if ($result) {
                            $row = mysqli_fetch_assoc($result);
                        }
                    } else {
                        $tipo_mensaje = 'danger';
                        $mensaje = 'Error al actualizar la base de datos: ' . mysqli_error($mysqli);
                    }
                }
            } else {
                $tipo_mensaje = 'danger';
                $mensaje = 'Error al mover el archivo.';
            }
        } else {
            $tipo_mensaje = 'danger';
            $mensaje = 'Tipo de archivo no permitido. Solo se aceptan archivos PDF (recomendado), DOC o DOCX.';
        }
    } else {
        $tipo_mensaje = 'danger';
        $mensaje = 'Error al subir el archivo.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Subir Consentimiento Informado</title>
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <link href="../../fontawesome/css/all.css" rel="stylesheet">
    <style>
        .responsive {
            max-width: 100%;
            height: auto;
        }

        .container {
            margin-top: 30px;
        }

        .info-box {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <center>
        <img src='../../img/logo_educacion.png' width=600 height=121 class='responsive'>
    </center>

    <div class="container">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3><i class="fa fa-file-signature"></i> Subir Consentimiento Informado</h3>
            </div>
            <div class="card-body">

                <?php if ($mensaje): ?>
                    <div class="alert alert-<?php echo $tipo_mensaje; ?> alert-dismissible fade show" role="alert">
                        <?php echo $mensaje; ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <div class="info-box">
                    <h5>Información del Estudiante</h5>
                    <p><strong>Documento:</strong> <?php echo isset($row['num_doc_est']) ? $row['num_doc_est'] : 'N/A'; ?></p>
                    <p><strong>Nombre:</strong> <?php echo isset($row['nom_ape_est']) ? utf8_encode($row['nom_ape_est']) : 'N/A'; ?></p>
                    <p><strong>Grado:</strong> <?php echo isset($row['grado_est']) ? $row['grado_est'] : 'N/A'; ?></p>

                    <?php if (!$nuevo_registro && isset($row['archivo_consentimientoInformado']) && !empty($row['archivo_consentimientoInformado'])): ?>
                        <p><strong>Archivo Actual:</strong>
                            <a href="viewConsentimientoInformado.php?id=<?php echo $id_consentimiento; ?>" target="_blank">
                                <i class="fa fa-file-pdf"></i> Ver archivo actual
                            </a>
                        </p>
                    <?php else: ?>
                        <p><strong>Estado:</strong> <span class="badge badge-warning">Sin archivo</span></p>
                    <?php endif; ?>
                </div>

                <div class="text-center mb-3">
                    <a href="downloadConsentimientoInformadoWord.php" class="btn btn-success btn-lg">
                        <i class="fa fa-download"></i> Descargar Plantilla Word (Consentimiento_Informado.docx)
                    </a>
                </div>

                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="archivo_pdf"><strong>Seleccione el archivo del Consentimiento Informado:</strong></label>
                        <input type="file" name="archivo_pdf" id="archivo_pdf" class="form-control" accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" required>
                        <small class="form-text text-muted">
                            <strong>Se recomienda PDF.</strong> También se aceptan archivos DOC y DOCX. Tamaño máximo: 10 MB.
                        </small>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fa fa-upload"></i> Subir Archivo
                        </button>
                        <a href="showConsentimientoInformado.php" class="btn btn-secondary btn-lg">
                            <i class="fa fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script src="../../js/jquery.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
</body>

</html>