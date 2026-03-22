<?php
/**
 * Procesador de carga masiva Excel para Consentimiento Informado
 * 
 * Este archivo procesa archivos Excel subidos y crea registros en la BD
 */

session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../../index.php");
    exit();
}

require_once(__DIR__ . '/../../conexion.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\IOFactory;

$usuario = $_SESSION['usuario'];
$nombre = $_SESSION['nombre'];
$tipo_usuario = $_SESSION['tipo_usuario'];
$cod_dane_ie = $_SESSION['cod_dane_ie'];
$id_usu = $_SESSION['id'];

$response = [
    'success' => false,
    'message' => '',
    'inserted' => 0,
    'errors' => []
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excel_file'])) {
    
    // Validar archivo
    $file = $_FILES['excel_file'];
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $response['message'] = 'Error al subir el archivo.';
    } elseif ($file['size'] === 0) {
        $response['message'] = 'El archivo está vacío.';
    } elseif (!in_array($file['type'], [
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-excel'
    ])) {
        $response['message'] = 'El archivo debe ser un archivo Excel (.xlsx o .xls).';
    } else {
        // Mover archivo a ubicación temporal
        $uploadDir = __DIR__ . '/../uploadData/temp/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $tempFilePath = $uploadDir . 'upload_' . time() . '_' . basename($file['name']);
        
        if (move_uploaded_file($file['tmp_name'], $tempFilePath)) {
            
            try {
                // Cargar archivo Excel
                $spreadsheet = IOFactory::load($tempFilePath);
                $sheet = $spreadsheet->getActiveSheet();
                $highestRow = $sheet->getHighestRow();
                
                $insertados = 0;
                $errores = [];
                
                // Procesar filas (empezando desde la fila 2, después del encabezado)
                for ($row = 2; $row <= $highestRow; $row++) {
                    $num_doc_est = $sheet->getCell('A' . $row)->getValue();
                    
                    // Saltar filas vacías
                    if (empty($num_doc_est)) {
                        continue;
                    }
                    
                    // Verificar que el estudiante existe
                    $checkQuery = "SELECT num_doc_est FROM estudiantes WHERE num_doc_est = '$num_doc_est'";
                    $checkResult = mysqli_query($mysqli, $checkQuery);
                    
                    if (mysqli_num_rows($checkResult) == 0) {
                        $errores[] = "Fila $row: El estudiante con documento $num_doc_est no existe.";
                        continue;
                    }
                    
                    // Preparar datos para inserción
                    $fecha_actual = date('Y-m-d H:i:s');
                    
                    // Insertar registro
                    $insertQuery = "INSERT INTO consentimientoInformado 
                                   (num_doc_est, 
                                    nombre_quien_sube_consentimientoInformado, 
                                    rol_quien_sube_consentimientoInformado,
                                    archivo_consentimientoInformado,
                                    estado_consentimientoInformado, 
                                    fecha_alta_consentimientoInformado, 
                                    id_usu) 
                                   VALUES 
                                   ('$num_doc_est', 
                                    '$nombre', 
                                    '$tipo_usuario',
                                    '',
                                    1, 
                                    '$fecha_actual', 
                                    $id_usu)";
                    
                    if (mysqli_query($mysqli, $insertQuery)) {
                        $insertados++;
                    } else {
                        $errores[] = "Fila $row: Error al insertar - " . mysqli_error($mysqli);
                    }
                }
                
                // Limpiar archivo temporal
                unlink($tempFilePath);
                
                // Preparar respuesta
                $response['success'] = true;
                $response['inserted'] = $insertados;
                $response['errors'] = $errores;
                
                if ($insertados > 0) {
                    $response['message'] = "Se insertaron $insertados registros correctamente.";
                    if (count($errores) > 0) {
                        $response['message'] .= " Se encontraron " . count($errores) . " errores.";
                    }
                } else {
                    $response['message'] = "No se insertó ningún registro.";
                }
                
            } catch (Exception $e) {
                $response['message'] = 'Error al procesar el archivo: ' . $e->getMessage();
            }
            
        } else {
            $response['message'] = 'Error al mover el archivo subido.';
        }
    }
    
} else {
    $response['message'] = 'No se recibió ningún archivo.';
}

// Mostrar resultado
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Resultado de Carga</title>
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            margin-top: 50px;
        }
        .alert {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3>Resultado de Carga Masiva - Consentimiento Informado</h3>
            </div>
            <div class="card-body">
                <?php if ($response['success']): ?>
                    <div class="alert alert-success">
                        <strong>¡Éxito!</strong> <?php echo $response['message']; ?>
                    </div>
                    <p><strong>Registros insertados:</strong> <?php echo $response['inserted']; ?></p>
                <?php else: ?>
                    <div class="alert alert-danger">
                        <strong>Error:</strong> <?php echo $response['message']; ?>
                    </div>
                <?php endif; ?>
                
                <?php if (count($response['errors']) > 0): ?>
                    <div class="alert alert-warning">
                        <strong>Errores encontrados:</strong>
                        <ul>
                            <?php foreach ($response['errors'] as $error): ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <div class="mt-4">
                    <a href="showConsentimientoInformado.php" class="btn btn-primary">
                        <i class="fa fa-arrow-left"></i> Volver a Aplicar Encuesta
                    </a>
                    <a href="checkConsentimientoInformado.php" class="btn btn-success">
                        <i class="fa fa-list"></i> Ver Encuestas Realizadas
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
