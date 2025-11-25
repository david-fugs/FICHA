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
    <title>FICHA - Cantidad de Encuestas por Sede</title>
    <link rel="stylesheet" href="../student/css/styles.css">
    <link rel="stylesheet" type="text/css" href="../student/css/estilos2024.css">
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <style>
        .responsive {
            max-width: 100%;
            height: auto;
        }

        .tabla-encuestas {
            width: 95%;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .tabla-encuestas th {
            background-color: #412fd1;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
        }

        .tabla-encuestas td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            font-size: 12px;
        }

        .tabla-encuestas tr:hover {
            background-color: #f5f5f5;
        }

        .total-row {
            background-color: #e8e4f3 !important;
            font-weight: bold;
        }

        /* Estilos de paginación */
        .Zebra_Pagination {
            display: inline-block;
            margin: 20px 0;
        }

        .Zebra_Pagination li {
            display: inline-block;
            margin: 0 3px;
        }

        .Zebra_Pagination a, .Zebra_Pagination span {
            padding: 8px 12px;
            border: 1px solid #412fd1;
            background-color: white;
            color: #412fd1;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
        }

        .Zebra_Pagination a:hover {
            background-color: #412fd1;
            color: white;
        }

        .Zebra_Pagination .current {
            background-color: #412fd1;
            color: white;
            font-weight: bold;
        }

        .Zebra_Pagination .disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>
</head>

<body>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>

    <center>
        <img src='../../img/logo_educacion.png' width=600 height=121 class='responsive'>
    </center>

    <section class="principal">
        <div style="border-radius: 9px; border: 4px solid #FFFFFF;" align="center">
            
            <div align="center">
                <h1 style="color: #412fd1; text-shadow: #FFFFFF 0.1em 0.1em 0.2em">
                    <b><i class="fa-solid fa-chart-bar"></i> CANTIDAD DE ENCUESTAS POR SEDE</b>
                </h1>
            </div>

            <div class="flex">
                <div class="box">
                    <form action="cantidadEncuestasPorSede.php" method="get">
                        <input name="buscar_ie" type="text" placeholder="Buscar Institución Educativa" size=35 value="<?php echo htmlspecialchars($_GET['buscar_ie'] ?? ''); ?>">
                        <input name="buscar_sede" type="text" placeholder="Buscar Sede" size=30 value="<?php echo htmlspecialchars($_GET['buscar_sede'] ?? ''); ?>">
                        <input value="Buscar" type="submit">
                        <a href="cantidadEncuestasPorSede.php"><input type="button" value="Ver Todas"></a>
                    </form>
                </div>
            </div>
            <br>

            <?php
            date_default_timezone_set("America/Bogota");
            include("../../conexion.php");

            // Obtener parámetros de búsqueda
            @$buscar_ie = $_GET['buscar_ie'] ?? '';
            @$buscar_sede = $_GET['buscar_sede'] ?? '';
            
            // Construir URL de exportación con parámetros
            $export_url = "exportarEncuestasPorSede.php?buscar_ie=" . urlencode($buscar_ie) . "&buscar_sede=" . urlencode($buscar_sede);
            ?>

            <div class="d-flex justify-content-center mb-3">
                <a href="../../access.php"><img src='../../img/atras.png' width="72" height="72" title="Regresar" /></a>
                <a class="ml-4" href="<?php echo $export_url; ?>"><img src='../../img/excel.png' width="75" height="80" title="Exportar a Excel" /></a>
            </div>

            <?php
            // Consulta para contar sedes (sin subconsultas)
            $query = "SELECT COUNT(*) as total
            FROM ieSede 
            INNER JOIN ie ON ieSede.cod_dane_ie = ie.cod_dane_ie 
            WHERE 1=1
            AND (ie.nombre_ie LIKE '%$buscar_ie%')
            AND (ieSede.nombre_ieSede LIKE '%$buscar_sede%')";

            $result = $mysqli->query($query);
            $row_count = mysqli_fetch_assoc($result);
            $num_registros = $row_count['total'];
            $resul_x_pagina = 10;

            // Requerir la librería de paginación
            require_once("../../zebra.php");

            if ($num_registros > 0) {
                echo "<div style='text-align:center; margin-bottom:15px; color:#412fd1; font-weight:bold;'>";
                echo "Se encontraron <span style='font-size:18px;'>{$num_registros}</span> sede(s)";
                if ($buscar_ie || $buscar_sede) {
                    echo " con los criterios de búsqueda";
                }
                echo "</div>";
                
                // Configurar paginación
                $paginacion = new Zebra_Pagination();
                $paginacion->records($num_registros);
                $paginacion->records_per_page($resul_x_pagina);

                // Consulta optimizada para obtener solo las sedes de la página actual
                $offset = ($paginacion->get_page() - 1) * $resul_x_pagina;
                
                $consulta_sedes = "SELECT 
                    ie.nombre_ie,
                    ie.cod_dane_ie,
                    ieSede.nombre_ieSede,
                    ieSede.cod_dane_ieSede
                FROM ieSede 
                INNER JOIN ie ON ieSede.cod_dane_ie = ie.cod_dane_ie 
                WHERE 1=1
                AND (ie.nombre_ie LIKE '%$buscar_ie%')
                AND (ieSede.nombre_ieSede LIKE '%$buscar_sede%')
                ORDER BY ie.nombre_ie ASC, ieSede.nombre_ieSede ASC
                LIMIT $offset, $resul_x_pagina";

                $result_sedes = $mysqli->query($consulta_sedes);
                
                // Almacenar sedes y construir lista de IDs
                $sedes = [];
                $sedes_ids = [];
                while ($sede = mysqli_fetch_array($result_sedes)) {
                    $sedes[] = $sede;
                    $sedes_ids[] = $mysqli->real_escape_string($sede['cod_dane_ieSede']);
                }

                // Si hay sedes, obtener los conteos
                $conteos = [];
                if (!empty($sedes_ids)) {
                    $ids_str = "'" . implode("','", $sedes_ids) . "'";
                    
                    // Obtener conteos de cada tipo de encuesta de forma optimizada
                    $queries_conteo = [
                        'pre_post' => "SELECT e.cod_dane_ieSede, COUNT(DISTINCT p.num_doc_est) as cnt 
                                      FROM estudiantes e 
                                      LEFT JOIN prePostnatales p ON e.num_doc_est = p.num_doc_est AND p.fecha_alta_prePostnatales >= '2023-10-01'
                                      WHERE e.cod_dane_ieSede IN ($ids_str) GROUP BY e.cod_dane_ieSede",
                        
                        'entorno' => "SELECT e.cod_dane_ieSede, COUNT(DISTINCT eh.num_doc_est) as cnt 
                                     FROM estudiantes e 
                                     LEFT JOIN entornohogar eh ON e.num_doc_est = eh.num_doc_est AND eh.fecha_alta_hog >= '2023-10-01'
                                     WHERE e.cod_dane_ieSede IN ($ids_str) GROUP BY e.cod_dane_ieSede",
                        
                        'salud' => "SELECT e.cod_dane_ieSede, COUNT(DISTINCT f.num_doc_est) as cnt 
                                   FROM estudiantes e 
                                   LEFT JOIN familiasalud f ON e.num_doc_est = f.num_doc_est AND f.fechacreacion_familiasalud >= '2023-10-01'
                                   WHERE e.cod_dane_ieSede IN ($ids_str) GROUP BY e.cod_dane_ieSede",
                        
                        'educacion' => "SELECT e.cod_dane_ieSede, COUNT(DISTINCT ed.num_doc_est) as cnt 
                                       FROM estudiantes e 
                                       LEFT JOIN educacion ed ON e.num_doc_est = ed.num_doc_est AND ed.fecha_dig_educacion >= '2023-10-01'
                                       WHERE e.cod_dane_ieSede IN ($ids_str) GROUP BY e.cod_dane_ieSede",
                        
                        'desempeno' => "SELECT e.cod_dane_ieSede, COUNT(DISTINCT d.num_doc_est) as cnt 
                                       FROM estudiantes e 
                                       LEFT JOIN desempeno d ON e.num_doc_est = d.num_doc_est AND d.fecha_dig_desempeno >= '2023-10-01'
                                       WHERE e.cod_dane_ieSede IN ($ids_str) GROUP BY e.cod_dane_ieSede",
                        
                        'preescolar' => "SELECT e.cod_dane_ieSede, COUNT(DISTINCT pr.num_doc_est) as cnt 
                                        FROM estudiantes e 
                                        LEFT JOIN preescolar pr ON e.num_doc_est = pr.num_doc_est AND pr.fecha_dig_preescolar >= '2023-10-01'
                                        WHERE e.cod_dane_ieSede IN ($ids_str) GROUP BY e.cod_dane_ieSede",
                        
                        'personal' => "SELECT e.cod_dane_ieSede, COUNT(DISTINCT pe.num_doc_est) as cnt 
                                      FROM estudiantes e 
                                      LEFT JOIN personal pe ON e.num_doc_est = pe.num_doc_est AND pe.fecha_dig_personal >= '2023-10-01'
                                      WHERE e.cod_dane_ieSede IN ($ids_str) GROUP BY e.cod_dane_ieSede",
                        
                        'preguntas' => "SELECT e.cod_dane_ieSede, COUNT(DISTINCT pg.num_doc_est) as cnt 
                                       FROM estudiantes e 
                                       LEFT JOIN preguntas pg ON e.num_doc_est = pg.num_doc_est AND pg.fecha_dig_preguntas >= '2023-10-01'
                                       WHERE e.cod_dane_ieSede IN ($ids_str) GROUP BY e.cod_dane_ieSede"
                    ];
                    
                    foreach ($queries_conteo as $tipo => $query_conteo) {
                        $res = $mysqli->query($query_conteo);
                        if ($res) {
                            while ($row = mysqli_fetch_assoc($res)) {
                                $conteos[$row['cod_dane_ieSede']][$tipo] = (int)$row['cnt'];
                            }
                        }
                    }
                }

                // Mostrar tabla
                echo "<table class='tabla-encuestas'>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>INSTITUCIÓN EDUCATIVA</th>
                            <th>COD DANE IE</th>
                            <th>SEDE</th>
                            <th>COD DANE SEDE</th>
                            <th>Pre Post-Natales</th>
                            <th>Entorno Hogar</th>
                            <th>Salud y Familia</th>
                            <th>Educación</th>
                            <th>Desempeño</th>
                            <th>Preescolar</th>
                            <th>Personal</th>
                            <th>Preguntas</th>
                            <th>TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>";

                $contador = $offset + 1;
                $totales = ['pre_post' => 0, 'entorno' => 0, 'salud' => 0, 'educacion' => 0, 'desempeno' => 0, 'preescolar' => 0, 'personal' => 0, 'preguntas' => 0, 'total' => 0];

                foreach ($sedes as $sede) {
                    $cod_sede = $sede['cod_dane_ieSede'];
                    $pre_post = $conteos[$cod_sede]['pre_post'] ?? 0;
                    $entorno = $conteos[$cod_sede]['entorno'] ?? 0;
                    $salud = $conteos[$cod_sede]['salud'] ?? 0;
                    $educacion = $conteos[$cod_sede]['educacion'] ?? 0;
                    $desempeno = $conteos[$cod_sede]['desempeno'] ?? 0;
                    $preescolar = $conteos[$cod_sede]['preescolar'] ?? 0;
                    $personal = $conteos[$cod_sede]['personal'] ?? 0;
                    $preguntas = $conteos[$cod_sede]['preguntas'] ?? 0;
                    
                    $total_sede = $pre_post + $entorno + $salud + $educacion + $desempeno + $preescolar + $personal + $preguntas;

                    // Acumular totales
                    $totales['pre_post'] += $pre_post;
                    $totales['entorno'] += $entorno;
                    $totales['salud'] += $salud;
                    $totales['educacion'] += $educacion;
                    $totales['desempeno'] += $desempeno;
                    $totales['preescolar'] += $preescolar;
                    $totales['personal'] += $personal;
                    $totales['preguntas'] += $preguntas;
                    $totales['total'] += $total_sede;

                    echo "<tr>
                        <td>{$contador}</td>
                        <td><strong>{$sede['nombre_ie']}</strong></td>
                        <td>{$sede['cod_dane_ie']}</td>
                        <td><strong>{$sede['nombre_ieSede']}</strong></td>
                        <td>{$cod_sede}</td>
                        <td style='text-align:center;'>{$pre_post}</td>
                        <td style='text-align:center;'>{$entorno}</td>
                        <td style='text-align:center;'>{$salud}</td>
                        <td style='text-align:center;'>{$educacion}</td>
                        <td style='text-align:center;'>{$desempeno}</td>
                        <td style='text-align:center;'>{$preescolar}</td>
                        <td style='text-align:center;'>{$personal}</td>
                        <td style='text-align:center;'>{$preguntas}</td>
                        <td style='text-align:center; font-weight:bold;'>{$total_sede}</td>
                    </tr>";
                    $contador++;
                }

                // Fila de totales
                echo "<tr class='total-row'>
                    <td colspan='5' style='text-align:right;'><strong>TOTALES (PÁGINA):</strong></td>
                    <td style='text-align:center;'><strong>{$totales['pre_post']}</strong></td>
                    <td style='text-align:center;'><strong>{$totales['entorno']}</strong></td>
                    <td style='text-align:center;'><strong>{$totales['salud']}</strong></td>
                    <td style='text-align:center;'><strong>{$totales['educacion']}</strong></td>
                    <td style='text-align:center;'><strong>{$totales['desempeno']}</strong></td>
                    <td style='text-align:center;'><strong>{$totales['preescolar']}</strong></td>
                    <td style='text-align:center;'><strong>{$totales['personal']}</strong></td>
                    <td style='text-align:center;'><strong>{$totales['preguntas']}</strong></td>
                    <td style='text-align:center;'><strong>{$totales['total']}</strong></td>
                </tr>";

                echo "</tbody></table>";
                
                // Mostrar controles de paginación
                echo "<div style='text-align:center; margin:20px 0;'>";
                echo "<p style='color:#412fd1; font-weight:bold;'>Mostrando página " . $paginacion->get_page() . " de " . ceil($num_registros / $resul_x_pagina) . "</p>";
                echo "</div>";
                
                echo "<div style='text-align:center; margin:20px 0;'>";
                $paginacion->render();
                echo "</div>";
                
            } else {
                echo "<div class='alert alert-warning' style='width:90%; margin:20px auto; text-align:center;'>";
                echo "<i class='fa-solid fa-exclamation-triangle'></i> No se encontraron sedes";
                if ($buscar_ie || $buscar_sede) {
                    echo " con los criterios de búsqueda especificados.";
                    echo "<br><br><a href='cantidadEncuestasPorSede.php' class='btn btn-primary'>Ver todas las sedes</a>";
                } else {
                    echo " en el sistema.";
                }
                echo "</div>";
            }

            $mysqli->close();
            ?>

        </div>
    </section>

</body>
</html>
