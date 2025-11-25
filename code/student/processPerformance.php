<?php

session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../../index.php");
}

header("Content-Type: text/html;charset=utf-8");
$usuario      = $_SESSION['usuario'];
$nombre       = $_SESSION['nombre'];
$tipo_usuario = $_SESSION['tipo_usuario'];
$cod_dane_ie  = $_SESSION['cod_dane_ie'];

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FICHA</title>
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <style>
        .responsive {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>

<body>
    <?php
    include("../../conexion.php");
    date_default_timezone_set("America/Bogota");
    $mysqli->set_charset('utf8');
    if (isset($_POST)) {
        $fecha_dig_desempeno                      = $_POST['fecha_dig_desempeno'];
        $mun_dig_desempeno                        = $_POST['mun_dig_desempeno'];
        $nombre_encuestador_desempeno           = $_POST['nombre_encuestador_desempeno'];
        $rol_encuestador_desempeno               = $_POST['rol_encuestador_desempeno'];
        $num_doc_est                             = $_POST['num_doc_est'];
        $nom_ape_est                             = $_POST['nom_ape_est'];
        $comprension_ciencia_desempeno           = $_POST['comprension_ciencia_desempeno'];
        $participacion_ciencia_desempeno         = $_POST['participacion_ciencia_desempeno'];
        $aplicacion_ciencia_desempeno            = $_POST['aplicacion_ciencia_desempeno'];
        $consistencia_ciencia_desempeno         = $_POST['consistencia_ciencia_desempeno'];
        $comprension_sociales_desempeno         = $_POST['comprension_sociales_desempeno'];
        $participacion_sociales_desempeno       = $_POST['participacion_sociales_desempeno'];
        $aplicacion_sociales_desempeno          = $_POST['aplicacion_sociales_desempeno'];
        $consistencia_sociales_desempeno        = $_POST['consistencia_sociales_desempeno'];
        $comprension_edufisica_desempeno         = $_POST['comprension_edufisica_desempeno'];
        $participacion_edufisica_desempeno       = $_POST['participacion_edufisica_desempeno'];
        $aplicacion_edufisica_desempeno          = $_POST['aplicacion_edufisica_desempeno'];
        $consistencia_edufisica_desempeno        = $_POST['consistencia_edufisica_desempeno'];
        $comprension_etica_desempeno              = $_POST['comprension_etica_desempeno'];
        $participacion_etica_desempeno            = $_POST['participacion_etica_desempeno'];
        $aplicacion_etica_desempeno               = $_POST['aplicacion_etica_desempeno'];
        $consistencia_etica_desempeno             = $_POST['consistencia_etica_desempeno'];
        $comprension_religion_desempeno           = $_POST['comprension_religion_desempeno'];
        $participacion_religion_desempeno         = $_POST['participacion_religion_desempeno'];
        $aplicacion_religion_desempeno            = $_POST['aplicacion_religion_desempeno'];
        $consistencia_religion_desempeno          = $_POST['consistencia_religion_desempeno'];
        $comprension_humanidades_desempeno       = $_POST['comprension_humanidades_desempeno'];
        $participacion_humanidades_desempeno     = $_POST['participacion_humanidades_desempeno'];
        $aplicacion_humanidades_desempeno        = $_POST['aplicacion_humanidades_desempeno'];
        $consistencia_humanidades_desempeno      = $_POST['consistencia_humanidades_desempeno'];
        $comprension_matematicas_desempeno       = $_POST['comprension_matematicas_desempeno'];
        $participacion_matematicas_desempeno     = $_POST['participacion_matematicas_desempeno'];
        $aplicacion_matematicas_desempeno        = $_POST['aplicacion_matematicas_desempeno'];
        $consistencia_matematicas_desempeno      = $_POST['consistencia_matematicas_desempeno'];
        $comprension_fisica_desempeno            = $_POST['comprension_fisica_desempeno'];
        $participacion_fisica_desempeno          = $_POST['participacion_fisica_desempeno'];
        $aplicacion_fisica_desempeno             = $_POST['aplicacion_fisica_desempeno'];
        $consistencia_fisica_desempeno           = $_POST['consistencia_fisica_desempeno'];
        $comprension_algebra_desempeno          = $_POST['comprension_algebra_desempeno'] ??'';
        $participacion_algebra_desempeno        = $_POST['participacion_algebra_desempeno'] ??'';
        $aplicacion_algebra_desempeno           = $_POST['aplicacion_algebra_desempeno'] ??'';
        $consistencia_algebra_desempeno         = $_POST['consistencia_algebra_desempeno'] ??'';
        $comprension_calculo_desempeno          = $_POST['comprension_calculo_desempeno'] ??'';
        $participacion_calculo_desempeno        = $_POST['participacion_calculo_desempeno'] ??'';
        $aplicacion_calculo_desempeno           = $_POST['aplicacion_calculo_desempeno'] ??'';
        $consistencia_calculo_desempeno         = $_POST['consistencia_calculo_desempeno'] ??'';
        $comprension_ingles_desempeno            = $_POST['comprension_ingles_desempeno'];
        $participacion_ingles_desempeno          = $_POST['participacion_ingles_desempeno'];
        $aplicacion_ingles_desempeno             = $_POST['aplicacion_ingles_desempeno'];
        $consistencia_ingles_desempeno           = $_POST['consistencia_ingles_desempeno'];
        $comprension_tecno_desempeno             = $_POST['comprension_tecno_desempeno'];
        $participacion_tecno_desempeno           = $_POST['participacion_tecno_desempeno'];
        $aplicacion_tecno_desempeno              = $_POST['aplicacion_tecno_desempeno'];
        $consistencia_tecno_desempeno            = $_POST['consistencia_tecno_desempeno'];
        $comprension_emprendimiento_desempeno    = $_POST['comprension_emprendimiento_desempeno'];
        $participacion_emprendimiento_desempeno  = $_POST['participacion_emprendimiento_desempeno'];
        $aplicacion_emprendimiento_desempeno     = $_POST['aplicacion_emprendimiento_desempeno'];
        $consistencia_emprendimiento_desempeno   = $_POST['consistencia_emprendimiento_desempeno'];
        $comprension_areastec_desempeno          = $_POST['comprension_areastec_desempeno'];
        $participacion_areastec_desempeno        = $_POST['participacion_areastec_desempeno'];
        $aplicacion_areastec_desempeno           = $_POST['aplicacion_areastec_desempeno'];
        $consistencia_areastec_desempeno         = $_POST['consistencia_areastec_desempeno'];
        $comprension_filosofia_desempeno          = $_POST['comprension_filosofia_desempeno'];
        $participacion_filosofia_desempeno        = $_POST['participacion_filosofia_desempeno'];
        $aplicacion_filosofia_desempeno           = $_POST['aplicacion_filosofia_desempeno'];
        $consistencia_filosofia_desempeno         = $_POST['consistencia_filosofia_desempeno'];
        $comprension_cienciaseco_desempeno       = $_POST['comprension_cienciaseco_desempeno'];
        $participacion_cienciaseco_desempeno     = $_POST['participacion_cienciaseco_desempeno'];
        $aplicacion_cienciaseco_desempeno        = $_POST['aplicacion_cienciaseco_desempeno'];
        $consistencia_cienciaseco_desempeno      = $_POST['consistencia_cienciaseco_desempeno'];
        $doble_titu_desempeno                    = $_POST['doble_titu_desempeno'];
        $nom_dobletitu_desempeno                 = $_POST['nom_dobletitu_desempeno'];
        $comprension_artistica_desempeno         = $_POST['comprension_artistica_desempeno'];
        $participacion_artistica_desempeno       = $_POST['participacion_artistica_desempeno']; 
        $aplicacion_artistica_desempeno          = $_POST['aplicacion_artistica_desempeno'];
        $consistencia_artistica_desempeno        = $_POST['consistencia_artistica_desempeno'];
        $id_usu                                  =   $_SESSION['id'];
        $fechacreacion_desempeno                  =   date('Y-m-d H:i:s');

    $sql = "INSERT INTO desempeno
        (fecha_dig_desempeno, mun_dig_desempeno, nombre_encuestador_desempeno, rol_encuestador_desempeno, num_doc_est, nom_ape_est, comprension_ciencia_desempeno, participacion_ciencia_desempeno, aplicacion_ciencia_desempeno, consistencia_ciencia_desempeno, comprension_sociales_desempeno, participacion_sociales_desempeno, aplicacion_sociales_desempeno, consistencia_sociales_desempeno, comprension_edufisica_desempeno, participacion_edufisica_desempeno, aplicacion_edufisica_desempeno, consistencia_edufisica_desempeno, comprension_etica_desempeno, participacion_etica_desempeno, aplicacion_etica_desempeno, consistencia_etica_desempeno, comprension_religion_desempeno, participacion_religion_desempeno, aplicacion_religion_desempeno, consistencia_religion_desempeno, comprension_humanidades_desempeno, participacion_humanidades_desempeno, aplicacion_humanidades_desempeno, consistencia_humanidades_desempeno, comprension_matematicas_desempeno, participacion_matematicas_desempeno, aplicacion_matematicas_desempeno, consistencia_matematicas_desempeno, comprension_fisica_desempeno, participacion_fisica_desempeno, aplicacion_fisica_desempeno, consistencia_fisica_desempeno, comprension_algebra_desempeno, participacion_algebra_desempeno, aplicacion_algebra_desempeno, consistencia_algebra_desempeno, comprension_calculo_desempeno, participacion_calculo_desempeno, aplicacion_calculo_desempeno, consistencia_calculo_desempeno, comprension_ingles_desempeno, participacion_ingles_desempeno, aplicacion_ingles_desempeno,consistencia_ingles_desempeno,  comprension_tecno_desempeno, participacion_tecno_desempeno, aplicacion_tecno_desempeno, consistencia_tecno_desempeno, comprension_emprendimiento_desempeno, participacion_emprendimiento_desempeno, aplicacion_emprendimiento_desempeno, consistencia_emprendimiento_desempeno, comprension_areastec_desempeno, participacion_areastec_desempeno, aplicacion_areastec_desempeno, consistencia_areastec_desempeno, comprension_filosofia_desempeno, participacion_filosofia_desempeno, aplicacion_filosofia_desempeno, consistencia_filosofia_desempeno, comprension_cienciaseco_desempeno, participacion_cienciaseco_desempeno, aplicacion_cienciaseco_desempeno, consistencia_cienciaseco_desempeno, doble_titu_desempeno, nom_dobletitu_desempeno, comprension_artistica_desempeno,participacion_artistica_desempeno, aplicacion_artistica_desempeno, consistencia_artistica_desempeno,id_usu, fechacreacion_desempeno,estado_desempeno)
        VALUES ('$fecha_dig_desempeno', '$mun_dig_desempeno', '$nombre_encuestador_desempeno', '$rol_encuestador_desempeno', '$num_doc_est', '$nom_ape_est', '$comprension_ciencia_desempeno', '$participacion_ciencia_desempeno', '$aplicacion_ciencia_desempeno', '$consistencia_ciencia_desempeno', '$comprension_sociales_desempeno', '$participacion_sociales_desempeno', '$aplicacion_sociales_desempeno', '$consistencia_sociales_desempeno', '$comprension_edufisica_desempeno', '$participacion_edufisica_desempeno', '$aplicacion_edufisica_desempeno', '$consistencia_edufisica_desempeno', '$comprension_etica_desempeno', '$participacion_etica_desempeno', '$aplicacion_etica_desempeno', '$consistencia_etica_desempeno', '$comprension_religion_desempeno', '$participacion_religion_desempeno', '$aplicacion_religion_desempeno', '$consistencia_religion_desempeno', '$comprension_humanidades_desempeno', '$participacion_humanidades_desempeno', '$aplicacion_humanidades_desempeno', '$consistencia_humanidades_desempeno', '$comprension_matematicas_desempeno', '$participacion_matematicas_desempeno', '$aplicacion_matematicas_desempeno', '$consistencia_matematicas_desempeno', '$comprension_fisica_desempeno', '$participacion_fisica_desempeno', '$aplicacion_fisica_desempeno', '$consistencia_fisica_desempeno', '$comprension_algebra_desempeno', '$participacion_algebra_desempeno', '$aplicacion_algebra_desempeno', '$consistencia_algebra_desempeno', '$comprension_calculo_desempeno', '$participacion_calculo_desempeno', '$aplicacion_calculo_desempeno', '$consistencia_calculo_desempeno', '$comprension_ingles_desempeno', '$participacion_ingles_desempeno', '$aplicacion_ingles_desempeno', '$consistencia_ingles_desempeno',  '$comprension_tecno_desempeno', '$participacion_tecno_desempeno', '$aplicacion_tecno_desempeno', '$consistencia_tecno_desempeno', '$comprension_emprendimiento_desempeno', '$participacion_emprendimiento_desempeno', '$aplicacion_emprendimiento_desempeno', '$consistencia_emprendimiento_desempeno', '$comprension_areastec_desempeno', '$participacion_areastec_desempeno', '$aplicacion_areastec_desempeno', '$consistencia_areastec_desempeno', '$comprension_filosofia_desempeno', '$participacion_filosofia_desempeno', '$aplicacion_filosofia_desempeno', '$consistencia_filosofia_desempeno', '$comprension_cienciaseco_desempeno', '$participacion_cienciaseco_desempeno', '$aplicacion_cienciaseco_desempeno', '$consistencia_cienciaseco_desempeno', '$doble_titu_desempeno', '$nom_dobletitu_desempeno', '$comprension_artistica_desempeno',
        '$participacion_artistica_desempeno', '$aplicacion_artistica_desempeno', '$consistencia_artistica_desempeno','$id_usu', '$fechacreacion_desempeno', 1)";
    
            $resultado = $mysqli->query($sql);

        if ($resultado) {
            echo "Datos insertados correctamente.";
        } else {
            echo "Error: " . $mysqli->error;
        }
        echo "
            <!DOCTYPE html>
                    <html lang='es'>
                        <head>
                            <meta charset='utf-8' />
                            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                            <meta http-equiv='X-UA-Compatible' content='ie=edge'>
                            <link href='https://fonts.googleapis.com/css?family=Lobster' rel='stylesheet'>
                            <link href='https://fonts.googleapis.com/css?family=Orbitron' rel='stylesheet'>
                            <link rel='stylesheet' href='../../css/bootstrap.min.css'>
                            <link href='../../fontawesome/css/all.css' rel='stylesheet'>
                            <title>FICHA</title>
                            <style>
                                .responsive {
                                    max-width: 100%;
                                    height: auto;
                                }
                            </style>
                        </head>
                        <body>
                            <center>
                               <img src='../../img/logo_educacion.png' width=600 height=121 class='responsive'>
                            <div class='container'>
                                <br />";
                                echo "  <h3><b><i class='fas fa-users'></i> SE CREO DE FORMA EXITOSA EL REGISTRO</b></h3><br />";
                            
                            
                            echo "    
                            <p align='center'><a href='../../access.php'><img src='../../img/atras.png' width=96 height=96></a></p>
                            </div>
                            </center>
                        </body>
                    </html>
            ";
    }
    ?>

 </body>

 </html>