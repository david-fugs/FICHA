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
    <link href="../../fontawesome/css/all.css" rel="stylesheet">
    <script type="text/javascript" src="../../js/jquery.min.js"></script>
    <!-- Using Select2 from a CDN-->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        .responsive {
            max-width: 100%;
            height: auto;
        }
    </style>
    <script>
        document.getElementById('eps_estudiante_familiaSalud').addEventListener('change', function() {
            var displayStyle = this.value === '1' ? 'block' : 'none';
            document.getElementById('eps-questions').style.display = displayStyle;
        });
    </script>
</head>

<body>
    <?php
    include("../../conexion.php");
    date_default_timezone_set("America/Bogota");
    $time = time();
    $num_doc_est  = $_GET['num_doc_est'];
    if (isset($_GET['num_doc_est'])) {
        $sql = mysqli_query($mysqli, "SELECT * FROM estudiantes WHERE num_doc_est = '$num_doc_est'");
        $row = mysqli_fetch_array($sql);

        //$row = $result->fetch_assoc();
        $fec_nac_est = $row['fec_nac_est'];

        // Calcula la edad
        $fecha_actual = new DateTime();
        $fec_nac_est = new DateTime($fec_nac_est);
        $edad = $fecha_actual->diff($fec_nac_est)->y;
    }
    ?>

    <div class="container">
        <center>
            <img src='../../img/logo_educacion.png' width=600 height=121 class='responsive'>
        </center>

        <h1><b><img src="../../img/baby.png" width=35 height=35> ACTUALIZAR INFORMACIÓN PRE POSTNATAL DEL ESTUDIANTE <img src="../../img/baby.png" width=35 height=35></b></h1>
        <button onclick="window.print()" class="btn btn-primary mb-3">
            Imprimir formulario
        </button>
        <p><i><b>
                    <font size=3 color=#c68615>* Datos obligatorios</i></b></font>
        </p>

        <form action='processEducation.php' method="POST">

            <hr style="border: 2px solid #16087B; border-radius: 2px;">
            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-sm-2">
                        <label for="fecha_dig_educacion">FECHA DILIGENC.</label>
                        <input type='text' name='fecha_dig_educacion' id="fecha_dig_educacion" class='form-control' value='<?php echo date("d-m-Y h:i", $time); ?>' readonly />
                    </div>
                    <div class="col-12 col-sm-3">
                        <label for="mun_dig_educacion">* MUNICIPIO DILIGENCIAMIENTO:</label>
                        <select name='mun_dig_educacion' class='form-control' id='selectMunicipio' required />
                        <option value=''></option>
                        <?php
                        header('Content-Type: text/html;charset=utf-8');
                        $consulta = 'SELECT * FROM municipios';
                        $res = mysqli_query($mysqli, $consulta);
                        $num_reg = mysqli_num_rows($res);
                        while ($row1 = $res->fetch_array()) {
                        ?>
                            <option value='<?php echo $row1['nombre_mun']; ?>' <?php if ($row['mun_dig_prePostnatales'] == $row1['nombre_mun']) {
                                                                                    echo 'selected';
                                                                                } ?>>
                                <?php echo $row1['nombre_mun']; ?>
                            </option>
                        <?php
                        }
                        ?>
                        </select>
                    </div>
                    <div class="col-12 col-sm-4">
                        <label for="nombre_encuestador_educacion">NOMBRE DEL ENCUESTADOR:</label>
                        <input type='text' name='nombre_encuestador_educacion' class='form-control' id="nombre_encuestador_educacion" value='<?php echo $nombre; ?>' readonly />
                    </div>
                    <div class="col-12 col-sm-3">
                        <label for="rol_encuestador_educacion">TIPO DE ACCESO:</label>
                        <select class="form-control" name="rol_encuestador_educacion" readonly />
                        <option value="">SELECCIONE:</option>
                        <option value="RECTOR" <?php if ($tipo_usuario == 1) {
                                                    echo 'selected';
                                                } ?>>RECTOR</option>
                        <option value="SIMAT" <?php if ($tipo_usuario == 2) {
                                                    echo 'selected';
                                                } ?>>SIMAT</option>
                        <option value="DOCENTE" <?php if ($tipo_usuario == 3) {
                                                    echo 'selected';
                                                } ?>>DOCENTE</option>
                        <option value="DOCENTE DIRECTIVO" <?php if ($tipo_usuario == 4) {
                                                                echo 'selected';
                                                            } ?>>DOCENTE DIRECTIVO</option>
                        <option value="DOCENTE ORIENTADOR" <?php if ($tipo_usuario == 5) {
                                                                echo 'selected';
                                                            } ?>>DOCENTE ORIENTADOR</option>
                        <option value="ADMINISTRATIVO" <?php if ($tipo_usuario == 6) {
                                                            echo 'selected';
                                                        } ?>>ADMINISTRATIVO</option>
                        <option value="SIN ACCESO" <?php if ($tipo_usuario == 7) {
                                                        echo 'selected';
                                                    } ?>>SIN ACCESO</option>
                        </select>
                    </div>
                </div>
            </div>
            <hr style="border: 2px solid #16087B; border-radius: 2px;">

            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-sm-3">
                        <label for="num_doc_est">No. DOCUMENTO ESTUDIANTE:</label>
                        <input type='number' name='num_doc_est' class='form-control' id="num_doc_est" value='<?php echo $row['num_doc_est']; ?>' readonly />
                    </div>
                    <div class="col-12 col-sm-9">
                        <label for="nom_ape_est">NOMBRES Y APELLIDOS COMPLETOS DEL ESTUDIANTE:</label>
                        <input type='text' name='nom_ape_est' id="nom_ape_est" class='form-control' value='<?php echo utf8_encode($row['nom_ape_est']); ?>' readonly />
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-6 col-sm-4">
                        <label for="vinculacion_inst_educacion">* ESTUDIANTE HA ESTADO VINCULADO A OTRA INSTUCION O FUNDACION: </label>
                        <select class="form-control" name="vinculacion_inst_educacion" id="vinculacion_inst_educacion">
                            <option value=""></option>
                            <option value="1">SI</option>
                            <option value="2">NO</option>
                        </select>
                    </div>
                    <div class="col-6 col-sm-4 mt-4">
                        <label for="nom_inst_educacion">* SI LA RESPUESTA FUE SI, ESPECIFIQUE CUAL: </label>
                        <input class="form-control" type="text" name="nom_inst_educacion">
                    </div>

                    <div class="col-6 col-sm-4 ">
                        <label for="modalidad_inst_educacion">* SI CURSO GRADO 10 o 11 EN LA ANTERIOR INSTITUCION, CUAL FUE LA MODALIDAD : </label>
                        <select class="form-control" name="modalidad_inst_educacion" id="modalidad_inst_educacion">
                            <option value=""></option>
                            <option value="tecnica">TECNICA</option>
                            <option value="academica">ACADEMICA</option>
                        </select>

                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-6 col-sm-4">
                        <label for="complementario_educacion">* ASISTE A PROGRAMAS COMPLEMENTARIO O EXTRACURRICULARES </label>
                        <select class="form-control" name="complementario_educacion" id="complementario_educacion">
                            <option value=""></option>
                            <option value="1">SI</option>
                            <option value="2">NO</option>
                        </select>
                    </div>
                    <div class="col-6 col-sm-4 mt-4">
                        <label for="program_complement_educacion">* SI LA RESPUESTA FUE SI, ESPECIFIQUE CUAL: </label>
                        <input class="form-control" type="text" name="program_complement_educacion">
                    </div>

                    <div class="col-6 col-sm-4 mt-4">
                        <label for="repetir_year_educacion">* EL ESTUDIANTE HA REPETIDO ALGUN AÑO: </label>
                        <select class="form-control" name="repetir_year_educacion" id="repetir_year_educacion">
                            <option value=""></option>
                            <option value="1">SI</option>
                            <option value="2">NO</option>
                        </select>
                        
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-6 col-sm-5">
                        <div class="col-12">
                            <label for="aniosrepetidos">SI HA REPETIDO ALGUN AÑO INDIQUE CUALES:</label>
                            <div class="form-control" id="aniosrepetidos" style="height: auto;">
                                <div class="row">
                                    <div class="col-8 col-sm-6 col-md-4">
                                        <input type="checkbox" name="anios_repet_educacion[]" value="11"> 11<br>
                                        <input type="checkbox" name="anios_repet_educacion[]" value="10"> 10<br>
                                        <input type="checkbox" name="anios_repet_educacion[]" value="9"> 9<br>
                                        <input type="checkbox" name="anios_repet_educacion[]" value="8"> 8<br>
                                    </div>
                                    <div class="col-8 col-sm-6 col-md-4">
                                        <input type="checkbox" name="anios_repet_educacion[]" value="7"> 7<br>
                                        <input type="checkbox" name="anios_repet_educacion[]" value="6"> 6<br>
                                        <input type="checkbox" name="anios_repet_educacion[]" value="5"> 5<br>
                                        <input type="checkbox" name="anios_repet_educacion[]" value="4"> 4<br>
                                    </div>
                                    <div class="col-8 col-sm-6 col-md-4">
                                        <input type="checkbox" name="anios_repet_educacion[]" value="3"> 3<br>
                                        <input type="checkbox" name="anios_repet_educacion[]" value="2"> 2<br>
                                        <input type="checkbox" name="anios_repet_educacion[]" value="1"> 1<br>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-sm-4 mt-5">
                        <label for="talento_educacion">* HA SIDO RECONOCIDO POR ALGUN TALENTO O CAPACIDAD: </label>
                        <select class="form-control" name="talento_educacion" id="talento_educacion">
                            <option value=""></option>
                            <option value="1">SI</option>
                            <option value="2">NO</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-6 col-sm-4 mt-4">
                        <label for="talento_descrip_educacion">* SI HA SIDO RECONOCIDO EL TALENTO, ESPECIFIQUE CUAL: </label>
                        <input class="form-control" type="text" name="talento_descrip_educacion" >
                    </div>
                    <div class="col-6 col-sm-4 mt-4">
                        <label for="vinculacion_club_educacion">* SE ENCUENTRA VINCULADO A LIGA O CLUB DEPORTIVO O CULTURAL: </label>
                        <select class="form-control" name="vinculacion_club_educacion" id="vinculacion_club_educacion">
                            <option value=""></option>
                            <option value="1">SI</option>
                            <option value="2">NO</option>
                        </select>
                    </div>

                    <div class="col-6 col-sm-4 mt-5">
                        <label for="club_descrip_educacion">* SI LA RESPUESTA FUE SI, ESPECIFIQUE CUAL: </label>
                        <input class="form-control" type="text" name="club_descrip_educacion">
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-5" name="btn-update">
                <span class="spinner-border spinner-border-sm"></span>
                ACTUALIZAR INFORMACIÓN EDUCACION
            </button>
            <button type="reset" class="btn btn-outline-dark mt-5" role='link' onclick="history.back();">
                <img src='../../img/atras.png' width=27 height=27> REGRESAR
            </button>
        </form>
    </div>
</body>


</html>