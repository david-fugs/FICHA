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
        $fecha_dig_familiaSalud               = $_POST['fecha_dig_familiaSalud'] ?? '';
        $mun_dig_familiaSalud                 = $_POST['mun_dig_familiaSalud'] ?? '';
        $nombre_encuestador_familiaSalud      = $_POST['nombre_encuestador_familiaSalud'] ?? '';
        $rol_encuestador_familiaSalud         = $_POST['rol_encuestador_familiaSalud'] ?? '';
        $num_doc_est                          = $_POST['num_doc_est'] ?? '';
        $nom_ape_est                          = $_POST['nom_ape_est'] ?? '';

        $relacion_madre_familiaSalud          = $_POST['relacion_madre_familiaSalud'] ?? '';
        $relacion_padre_familiaSalud          = $_POST['relacion_padre_familiaSalud'] ?? '';
        $relacion_hermanos_familiaSalud       = $_POST['relacion_hermanos_familiaSalud'] ?? '';
        $relacion_tios_familiaSalud           = $_POST['relacion_tios_familiaSalud'] ?? '';
        $relacion_abuelos_familiaSalud        = $_POST['relacion_abuelos_familiaSalud'] ?? '';
        $relacion_otros_familiaSalud          = $_POST['relacion_otros_familiaSalud'] ?? '';
        $discapacidad_est_familiaSalud        = $_POST['discapacidad_est_familiaSalud'] ?? '';
        //convertir array a string
        $afecta_aprendizaje_familiaSalud      = implode(',', $_POST['afecta_aprendizaje_familiaSalud']) ?? '';
        $beneficiario_pae_familiaSalud        = $_POST['beneficiario_pae_familiaSalud'] ?? '';
        $comida_dia_familiaSalud              = $_POST['comida_dia_familiaSalud'] ?? '';
        $eps_estudiante_familiaSalud          = $_POST['eps_estudiante_familiaSalud'] ?? '';
        $nombre_eps_familiaSalud              = $_POST['nombre_eps_familiaSalud'] ?? '';
        $cual_eps_familiaSalud                = $_POST['cual_eps_familiaSalud'] ?? '' ;
        $afiliado_eps_familiaSalud            = $_POST['afiliado_eps_familiaSalud'] ?? '';
        $presenta_diagnostico_familiaSalud    = $_POST['presenta_diagnostico_familiaSalud'] ?? '';
        $diagnostico_familiaSalud             = $_POST['diagnostico_familiaSalud'] ?? '';
        $terapia_familiaSalud                 = $_POST['terapia_familiaSalud'] ?? '';
        $frecuencia_terapia_familiaSalud      = $_POST['frecuencia_terapia_familiaSalud'] ?? '';
        $condicion_particular_familiaSalud     = $_POST['condicion_particular_familiaSalud'] ?? '';
        $frecuencia_atencion_familiaSalud      = $_POST['frecuencia_atencion_familiaSalud'] ?? '';
        $alergia_familiaSalud                 = $_POST['alergia_familiaSalud'] ?? '';
        $tipo_alergia_familiaSalud            = $_POST['tipo_alergia_familiaSalud'] ?? '';
        $vacunacion_familiaSalud              = $_POST['vacunacion_familiaSalud'] ?? '';
        $id_usu                             =   $_SESSION['id'];
        $fechacreacion_familiaSalud         =   date('Y-m-d H:i:s');
        $sangre_familiaSalud                 = $_POST['sangre_familiaSalud'];

        $sql = "INSERT INTO familiasalud
        ( mun_dig_familiaSalud, nombre_encuestador_familiaSalud, rol_encuestador_familiaSalud, num_doc_est, nom_ape_est, relacion_madre_familiaSalud, relacion_padre_familiaSalud, relacion_hermanos_familiaSalud, relacion_tios_familiaSalud, relacion_abuelos_familiaSalud,relacion_otros_familiaSalud,
         discapacidad_est_familiaSalud, afecta_aprendizaje_familiaSalud, beneficiario_pae_familiaSalud, comida_dia_familiaSalud, eps_estudiante_familiaSalud, nombre_eps_familiaSalud,cual_eps_familiaSalud, afiliado_eps_familiaSalud, presenta_diagnostico_familiaSalud, diagnostico_familiaSalud, terapia_familiaSalud, frecuencia_terapia_familiaSalud, condicion_particular_familiaSalud, frecuencia_atencion_familiaSalud, alergia_familiaSalud, tipo_alergia_familiaSalud, vacunacion_familiaSalud, id_usu ,
         sangre_familiaSalud, estado_familiasalud,fechacreacion_familiasalud)
        VALUES ( '$mun_dig_familiaSalud', '$nombre_encuestador_familiaSalud', '$rol_encuestador_familiaSalud', '$num_doc_est', '$nom_ape_est', '$relacion_madre_familiaSalud', '$relacion_padre_familiaSalud', '$relacion_hermanos_familiaSalud', '$relacion_tios_familiaSalud', '$relacion_abuelos_familiaSalud',
        '$relacion_otros_familiaSalud','$discapacidad_est_familiaSalud', '$afecta_aprendizaje_familiaSalud', '$beneficiario_pae_familiaSalud', '$comida_dia_familiaSalud', '$eps_estudiante_familiaSalud', '$nombre_eps_familiaSalud', '$cual_eps_familiaSalud'  ,'$afiliado_eps_familiaSalud', '$presenta_diagnostico_familiaSalud', '$diagnostico_familiaSalud', '$terapia_familiaSalud', '$frecuencia_terapia_familiaSalud', '$condicion_particular_familiaSalud', '$frecuencia_atencion_familiaSalud', '$alergia_familiaSalud', '$tipo_alergia_familiaSalud', '$vacunacion_familiaSalud', '$id_usu', '$sangre_familiaSalud'    ,'1', '$fechacreacion_familiaSalud')";
        $resultado = $mysqli->query($sql);

if (!$resultado) {
    die("Error en la consulta: " . mysqli_error($mysqli));
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
                                <br />
                           ";
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
