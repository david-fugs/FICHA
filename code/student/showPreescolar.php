<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../../index.php");
    exit();
}

$usuario      = $_SESSION['usuario'];
$nombre       = $_SESSION['nombre'];
$tipo_usuario = $_SESSION['tipo_usuario'];
$cod_dane_ie  = $_SESSION['cod_dane_ie'];
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
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm">
    <script src="https://kit.fontawesome.com/fed2435e21.js" crossorigin="anonymous"></script>

    <style>
        .responsive {
            max-width: 100%;
            height: auto;
        }

        .selector-for-some-widget {
            box-sizing: content-box;
        }

        .ok {
            background-color: green;
            color: white;
        }

        .pendiente {
            background-color: orange;
            color: white;
        }
        .btn-verde {
            background-color: #4CAF50;
            color: white;
            padding: 14px 0px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
        }

        .btn-verde:hover {
            background-color: #90ee90;
            /* Cambiar a un tono más claro al pasar el cursor */
        }
    </style>
    <script>
          function cambiarEstado(num_doc_est) {
            if (confirm('¿Está seguro que desea cambiar el estado del estudiante?')) {
                window.location.href = 'cambiarEstado.php?num_doc_est=' + num_doc_est + '&campo=' + 'preescolar' + '&valor=' + 1;
            }
        }

        function cesados() {
            window.location.href = 'showPreescolarCesados.php';
        }
    </script>
</head>

<body>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"></script>

    <center>
        <img src='../../img/logo_educacion.png' width=600 height=121 class='responsive'>
    </center>

    <section class="principal">

        <div style="border-radius: 9px 9px 9px 9px; -moz-border-radius: 9px 9px 9px 9px; -webkit-border-radius: 9px 9px 9px 9px; border: 4px solid #FFFFFF;" align="center">

            <div align="center">
                <h1 style="color: #412fd1; text-shadow: #FFFFFF 0.1em 0.1em 0.2em"><b><i class="fa-solid fa-user-pen"></i> GESTIÓN Y ADMINISTRACIÓN DE USUARIOS</b></h1>
            </div>

        </div>

        <div class="flex">
            <div class="box">
                <form action="showPreescolar.php" method="get">
                    <input name="num_doc_est" type="text" placeholder="Ingrese el Documento" size=20>
                    <input name="nom_ape_est" type="text" placeholder="Escriba el nombre del estudiante" size=30>
                    <input name="grado_est" type="text" placeholder="Grado">
                    <input value="Buscar" type="submit">
                    <input class="ml-5 btn-verde" value="Ver cesados" onclick="cesados()">

                </form>
            </div>
        </div>
        <br /><a href="../../access.php"><img src='../../img/atras.png' width="72" height="72" title="Regresar" /></a>

        <?php

        date_default_timezone_set("America/Bogota");
        include("../../conexion.php");
        require_once("../../zebra.php");

        @$num_doc_est = $_GET['num_doc_est'] ?? '';
        @$nom_ape_est = $_GET['nom_ape_est'] ?? '';
        @$grado_est = $_GET['grado_est'] ?? '';


        $query = "SELECT estudiantes.*, usuarios.*, ie.*
          FROM estudiantes 
          INNER JOIN ieSede ON estudiantes.cod_dane_ieSede=ieSede.cod_dane_ieSede 
          INNER JOIN ie ON ieSede.cod_dane_ie=ie.cod_dane_ie 
          INNER JOIN usuarios ON estudiantes.id_usu = usuarios.id
          WHERE (estudiantes.num_doc_est LIKE '%$num_doc_est%') 
          AND (estudiantes.nom_ape_est LIKE '%$nom_ape_est%') 
          AND (estudiantes.grado_est LIKE '%$grado_est%')
          AND estudiantes.estado_preescolar = 0  
          AND ie.cod_dane_ie = $cod_dane_ie ";
        // AND (prePostnatales.num_doc_est IS NULL OR prePostnatales.estado_prePostnatales = 1)

        // ORDER BY ISNULL(prePostnatales.fecha_alta_prePostnatales) DESC, prePostnatales.fecha_alta_prePostnatales ASC, estudiantes.num_doc_est ASC";

        $res = $mysqli->query($query);
        $num_registros = mysqli_num_rows($res);
        $resul_x_pagina = 50;

        if ($res) {
            $paginacion = new Zebra_Pagination();
            $paginacion->records($num_registros);
            $paginacion->records_per_page($resul_x_pagina);

            $consulta = "SELECT estudiantes.*, usuarios.*, ie.*, preescolar.fecha_dig_preescolar, preescolar.estado_preescolar
            FROM estudiantes 
            INNER JOIN ieSede ON estudiantes.cod_dane_ieSede = ieSede.cod_dane_ieSede 
            INNER JOIN ie ON ieSede.cod_dane_ie = ie.cod_dane_ie 
            LEFT JOIN preescolar ON estudiantes.num_doc_est = preescolar.num_doc_est
            INNER JOIN usuarios ON estudiantes.id_usu = usuarios.id
            WHERE (estudiantes.num_doc_est LIKE '%$num_doc_est%') 
            AND (estudiantes.nom_ape_est LIKE '%$nom_ape_est%') 
            AND (estudiantes.grado_est LIKE '%$grado_est%')
            AND ie.cod_dane_ie = $cod_dane_ie 
            AND estudiantes.estado_preescolar = 0  
            GROUP BY estudiantes.num_doc_est
            ORDER BY ISNULL(preescolar.fecha_dig_preescolar) DESC, preescolar.fecha_dig_preescolar ASC, estudiantes.num_doc_est ASC

                
                 LIMIT " . (($paginacion->get_page() - 1) * $resul_x_pagina) . "," . $resul_x_pagina;
            $result = $mysqli->query($consulta);

            if ($result) {
                echo '<br>';
                $paginacion->render();

                echo "<section class='content'>
                <div class='card-body'>
                    <div class='table-responsive'>
                        <table>
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>DTO</th>
                                    <th>ESTUDIANTE</th>
                                    <th>GRADO</th>
                                    <th>APLICAR</th>
                                    <th>ELIMINAR</th>
                                    <th>REALIZADO</th>
                                </tr>
                            </thead>
                            <tbody>";

                $i = 1;
                while ($row = mysqli_fetch_array($result)) {
                    $estado_encuesta = $row['estado_preescolar'] == 1  ? 'REALIZADO' : 'PENDIENTE';
                    $clase_estado = $row['estado_preescolar'] == 1 ? 'ok' : 'pendiente';

                    echo '
                    <tr>
                        <td data-label="No.">' . ($i + (($paginacion->get_page() - 1) * $resul_x_pagina)) . '</td>
                        <td data-label="DTO">' . $row['num_doc_est'] . '</td>
                        <td data-label="ESTUDIANTE">' . utf8_encode($row['nom_ape_est']) . '</td>
                        <td data-label="GRADO">' . $row['grado_est'] . '</td>
                    ';
                    echo '
                        <td data-label="APLICAR"><a href="preescolar/addPreescolar.php?num_doc_est=' . $row['num_doc_est'] . '"><img src="../../img/aplicar.png" width=28 height=28></a></td>
                        ';
                    echo '
                        <td data-label="ELIMINAR"><a href="#" onclick="cambiarEstado(' . $row['num_doc_est'] . ')"><img src="../../img/delete1.png" width=28 height=28></a></td>
                        <td data-label="REALIZADO" class="' . $clase_estado . '">' . $estado_encuesta . '</td>
                    </tr>';
                    $i++;
                }

                echo '</tbody></table></div></div></section>';
            } else {
                echo "Error en la consulta: " . $mysqli->error;
            }
        } else {
            echo "Error en la consulta: " . $mysqli->error;
        }
        ?>

        <div class="share-container">
            <!-- Go to www.addthis.com/dashboard to customize your tools -->
            <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-4ecc1a47193e29e4" async="async"></script>
            <!-- Go to www.addthis.com/dashboard to customize your tools -->
            <div class="addthis_sharing_toolbox"></div>
        </div>
        <center>
            <br /><a href="../../access.php"><img src='../../img/atras.png' width="72" height="72" title="Regresar" /></a>
        </center>

        </div>
        </div>
    </section>
    <script src="../student/js/app.js"></script>
    <script src="https://www.jose-aguilar.com/scripts/fontawesome/js/all.min.js" data-auto-replace-svg="nest"></script>

</body>

</html>