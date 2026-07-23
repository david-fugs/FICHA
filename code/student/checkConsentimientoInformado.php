<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../../index.php");
    exit();
}

$usuario = $_SESSION['usuario'];
$nombre = $_SESSION['nombre'];
$tipo_usuario = $_SESSION['tipo_usuario'];
$cod_dane_ie = $_SESSION['cod_dane_ie'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>FICHA</title>
    <link rel="stylesheet" href="../student/css/styles.css">
    <link rel="stylesheet" type="text/css" href="../student/css/estilos2024.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm">
    <script src="https://kit.fontawesome.com/fed2435e21.js" crossorigin="anonymous"></script>

    <style>
        .responsive {
            max-width: 100%;
            height: auto;
        }

        .page-header {
            background: #2c3e50;
            color: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 25px;
        }

        .search-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-bottom: 20px;
            border: 1px solid #e0e0e0;
        }

        .search-card input[type="text"] {
            border-radius: 6px;
            border: 1px solid #d0d0d0;
            padding: 10px 12px;
            transition: border-color 0.2s;
        }

        .search-card input[type="text"]:focus {
            border-color: #5a6c7d;
            outline: none;
            box-shadow: 0 0 0 2px rgba(90, 108, 125, 0.1);
        }

        .search-card input[type="submit"] {
            background: #34495e;
            border: none;
            color: white;
            padding: 10px 25px;
            border-radius: 6px;
            font-weight: 500;
            transition: background 0.2s;
            cursor: pointer;
        }

        .search-card input[type="submit"]:hover {
            background: #2c3e50;
        }

        .search-card label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 5px;
            font-size: 0.9rem;
        }

        .badge-archivo-subido {
            background: #27ae60;
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 0.8rem;
        }

        .badge-archivo-pendiente {
            background: #e67e22;
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 0.8rem;
        }

        .action-btn {
            width: 36px;
            height: 36px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            margin: 0 3px;
            text-decoration: none;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .btn-view {
            background: #3498db;
            color: white;
        }

        .btn-upload {
            background: #9b59b6;
            color: white;
        }

        .btn-delete {
            background: #e74c3c;
            color: white;
        }

        .btn-back {
            background: #95a5a6;
            padding: 12px 25px;
            border-radius: 6px;
            border: none;
            color: white;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s;
            font-weight: 500;
        }

        .btn-back:hover {
            background: #7f8c8d;
            transform: translateY(-2px);
        }

        table {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border: 1px solid #e0e0e0;
        }

        table thead {
            background: #34495e;
            color: white;
        }

        table thead th {
            padding: 14px 12px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
        }

        table tbody tr {
            transition: background 0.2s;
            border-bottom: 1px solid #f0f0f0;
        }

        table tbody tr:hover {
            background-color: #f8f9fa;
        }

        table tbody td {
            padding: 12px;
            vertical-align: middle;
            color: #2c3e50;
        }

        .stats-card {
            background: white;
            border: 1px solid #e0e0e0;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-bottom: 20px;
        }

        .stats-card h3 {
            margin: 0;
            font-size: 2.2rem;
            font-weight: 700;
            color: #2c3e50;
        }

        .stats-card p {
            margin: 8px 0 0 0;
            font-size: 0.95rem;
            color: #7f8c8d;
            font-weight: 500;
        }

        .stats-card.success {
            border-left: 4px solid #27ae60;
        }

        .stats-card.warning {
            border-left: 4px solid #e67e22;
        }

        .stats-card.info {
            border-left: 4px solid #3498db;
        }
    </style>
</head>

<body>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"></script>

    <div class="container-fluid" style="padding: 20px; background: #f5f6fa;">
        <center>
            <img src='../../img/logo_educacion.png' width=600 height=121 class='responsive'>
        </center>

        <div class="page-header text-center">
            <h1><i class="fa-solid fa-file-signature"></i> Consentimientos Informados</h1>
            <p style="margin: 8px 0 0 0; font-size: 1rem; opacity: 0.9;">Gestión y seguimiento de documentos</p>
        </div>

        <div class="search-card">
            <form action="checkConsentimientoInformado.php" method="get" class="row align-items-end">
                <div class="col-md-3 mb-2">
                    <label for="num_doc_est">
                        <i class="fa fa-id-card"></i> Documento
                    </label>
                    <input name="num_doc_est" type="text" class="form-control" placeholder="Ingrese el documento">
                </div>
                <div class="col-md-4 mb-2">
                    <label for="nom_ape_est">
                        <i class="fa fa-user"></i> Nombre del Estudiante
                    </label>
                    <input name="nom_ape_est" type="text" class="form-control" placeholder="Nombre completo">
                </div>
                <div class="col-md-2 mb-2">
                    <label for="grado_est">
                        <i class="fa fa-graduation-cap"></i> Grado
                    </label>
                    <input name="grado_est" type="text" class="form-control" placeholder="Grado">
                </div>
                <div class="col-md-3 mb-2">
                    <input value="🔍 Buscar" type="submit" class="btn btn-block">
                </div>
            </form>
        </div>

        <div class="text-center mb-4">
            <a href="../../access.php" class="btn-back">
                <i class="fa fa-arrow-left"></i> Regresar al Menú
            </a>
        </div>
            <?php

            date_default_timezone_set("America/Bogota");
            include("../../conexion.php");
            require_once("../../zebra.php");

            @$num_doc_est = $_GET['num_doc_est'] ?? '';
            @$nom_ape_est = $_GET['nom_ape_est'] ?? '';
            @$grado_est = $_GET['grado_est'] ?? '';

            $query = "SELECT consentimientoInformado.*, estudiantes.*, ie.* 
          FROM consentimientoInformado 
          INNER JOIN estudiantes ON consentimientoInformado.num_doc_est = estudiantes.num_doc_est 
          INNER JOIN ieSede ON estudiantes.cod_dane_ieSede = ieSede.cod_dane_ieSede 
          INNER JOIN ie ON ieSede.cod_dane_ie = ie.cod_dane_ie 
          WHERE (estudiantes.num_doc_est LIKE '%$num_doc_est%') 
          AND (estudiantes.nom_ape_est LIKE '%$nom_ape_est%') 
          AND (estudiantes.grado_est = '$grado_est' OR '$grado_est' = '')
          AND ie.cod_dane_ie = $cod_dane_ie 
          ORDER BY consentimientoInformado.fecha_alta_consentimientoInformado DESC";
            $res = $mysqli->query($query);
            $num_registros = mysqli_num_rows($res);
            $resul_x_pagina = 200;

            // Contar archivos subidos vs pendientes
            $subidos = 0;
            $pendientes = 0;
            $res_temp = $mysqli->query($query);
            while ($row_temp = mysqli_fetch_array($res_temp)) {
                if (!empty($row_temp['archivo_consentimientoInformado'])) {
                    $subidos++;
                } else {
                    $pendientes++;
                }
            }
            ?>

            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="stats-card info">
                        <h3><?php echo $num_registros; ?></h3>
                        <p><i class="fa fa-file-alt"></i> Total de Registros</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card success">
                        <h3><?php echo $subidos; ?></h3>
                        <p><i class="fa fa-check-circle"></i> Archivos Subidos</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card warning">
                        <h3><?php echo $pendientes; ?></h3>
                        <p><i class="fa fa-clock"></i> Archivos Pendientes</p>
                    </div>
                </div>
            </div>

            <?php

            echo "<div class='table-responsive'>
                <table class='table table-hover'>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th><i class='fa fa-id-card'></i> Tipo</th>
                            <th><i class='fa fa-hashtag'></i> Documento</th>
                            <th><i class='fa fa-user'></i> Estudiante</th>
                            <th><i class='fa fa-graduation-cap'></i> Grado</th>
                            <th><i class='fa fa-calendar'></i> Subido El</th>
                            <th><i class='fa fa-user-tie'></i> Subió</th>
                            <th><i class='fa fa-edit'></i> Modificado</th>
                            <th><i class='fa fa-flag'></i> Estado</th>
                            <th style='text-align: center;'><i class='fa fa-cogs'></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody>";

            $paginacion = new Zebra_Pagination();
            $paginacion->records($num_registros);
            $paginacion->records_per_page($resul_x_pagina);

            $consulta = "SELECT consentimientoInformado.*, estudiantes.*, ie.* 
             FROM consentimientoInformado 
             INNER JOIN estudiantes ON consentimientoInformado.num_doc_est = estudiantes.num_doc_est 
             INNER JOIN ieSede ON estudiantes.cod_dane_ieSede = ieSede.cod_dane_ieSede 
             INNER JOIN ie ON ieSede.cod_dane_ie = ie.cod_dane_ie 
             WHERE (estudiantes.num_doc_est LIKE '%$num_doc_est%') 
             AND (estudiantes.nom_ape_est LIKE '%$nom_ape_est%') 
             AND (estudiantes.grado_est = '$grado_est' OR '$grado_est' = '')
             AND ie.cod_dane_ie = $cod_dane_ie 
             ORDER BY consentimientoInformado.fecha_alta_consentimientoInformado DESC 
             LIMIT " . (($paginacion->get_page() - 1) * $resul_x_pagina) . ", " . $resul_x_pagina;
            $result = $mysqli->query($consulta);

            $i = 1;
            while ($row = mysqli_fetch_array($result)) {
                $tiene_archivo = !empty($row['archivo_consentimientoInformado']);
                $badge_clase = $tiene_archivo ? 'badge-archivo-subido' : 'badge-archivo-pendiente';
                $estado_texto = $tiene_archivo ? '✓ SUBIDO' : '⏳ PENDIENTE';
                $fecha_modificado = $row['fecha_edit_consentimientoInformado'] ? $row['fecha_edit_consentimientoInformado'] : '-';

                echo '
            <tr>
                <td><strong>' . $i++ . '</strong></td>
                <td>' . $row['tip_doc_est'] . '</td>
                <td><strong>' . $row['num_doc_est'] . '</strong></td>
                <td>' . utf8_encode($row['nom_ape_est']) . '</td>
                <td><span class="badge badge-primary">' . $row['grado_est'] . '</span></td>
                <td>' . date('d/m/Y H:i', strtotime($row['fecha_alta_consentimientoInformado'])) . '</td>
                <td>' . utf8_encode($row['nombre_quien_sube_consentimientoInformado']) . '</td>
                <td>' . ($fecha_modificado != '-' ? date('d/m/Y H:i', strtotime($fecha_modificado)) : '-') . '</td>
                <td><span class="' . $badge_clase . '">' . $estado_texto . '</span></td>
                <td style="text-align: center;">';
                
                if ($tiene_archivo) {
                    echo '<a href="viewConsentimientoInformado.php?id=' . $row['id_consentimientoInformado'] . '" target="_blank" class="action-btn btn-view" title="Ver Archivo">
                        <i class="fa fa-eye" style="color: white;"></i>
                    </a>';
                } else {
                    echo '<span class="action-btn" style="background: #e0e0e0; cursor: not-allowed;" title="Sin archivo">
                        <i class="fa fa-eye-slash" style="color: #999;"></i>
                    </span>';
                }
                
                echo '<a href="uploadConsentimientoInformadoForm.php?id=' . $row['id_consentimientoInformado'] . '&num_doc_est=' . $row['num_doc_est'] . '" class="action-btn btn-upload" title="Subir/Actualizar Archivo">
                    <i class="fa fa-upload" style="color: white;"></i>
                </a>';
                
                echo '<a href="deleteConsentimientoInformado.php?id=' . $row['id_consentimientoInformado'] . '" class="action-btn btn-delete" onclick="return confirm(\'¿Está seguro de eliminar este registro y su archivo?\')" title="Eliminar">
                    <i class="fa fa-trash" style="color: white;"></i>
                </a>';
                
                echo '</td>
            </tr>';
            }

            echo '</tbody></table></div>';

            $paginacion->render();

            ?>

        <div class="text-center mt-4 mb-4">
            <a href="../../access.php" class="btn-back">
                <i class="fa fa-arrow-left"></i> Regresar al Menú
            </a>
        </div>

    </div>
    <script src="js/app.js"></script>
    <script src="https://www.jose-aguilar.com/scripts/fontawesome/js/all.min.js" data-auto-replace-svg="nest"></script>

</body>

</html>
