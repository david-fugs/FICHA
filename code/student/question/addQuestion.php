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
    <link href="../../../css/bootstrap.min.css" rel="stylesheet">
    <link href="../../../fontawesome/css/all.css" rel="stylesheet">
    <script type="text/javascript" src="../../../js/jquery.min.js"></script>
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
            document.getElementById('eps-preguntass').style.display = displayStyle;
        });
    </script>
</head>

<body>
    <?php
    include("../../../conexion.php");
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
            <img src='../../../img/logo_educacion.png' width=600 height=121 class='responsive'>
        </center>

        <h1><b><img src="../../../img/baby.png" width=35 height=35> ACTUALIZAR INFORMACIÓN DEL ESTUDIANTE <img src="../../../img/baby.png" width=35 height=35></b></h1>
        <button onclick="window.print()" class="btn btn-primary mb-3">
            Imprimir formulario
        </button>
        <p><i><b>
                    <font size=3 color=#c68615>* Datos obligatorios</i></b></font>
        </p>

        <form action='processQuestion.php' method="POST">

            <hr style="border: 2px solid #16087B; border-radius: 2px;">
            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-sm-2">
                        <label for="fecha_dig_preguntas">FECHA DILIGENC.</label>
                        <input type='text' name='fecha_dig_preguntas' id="fecha_dig_preguntas" class='form-control' value='<?php echo date("d-m-Y h:i", $time); ?>' readonly />
                    </div>
                    <div class="col-12 col-sm-3">
                        <label for="mun_dig_preguntas">* MUNICIPIO DILIGENCIAMIENTO:</label>
                        <select name='mun_dig_preguntas' class='form-control' id='selectMunicipio' required />
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
                        <label for="nombre_encuestador_preguntas">NOMBRE DEL ENCUESTADOR:</label>
                        <input type='text' name='nombre_encuestador_preguntas' class='form-control' id="nombre_encuestador_preguntas" value='<?php echo $nombre; ?>' readonly />
                    </div>
                    <div class="col-12 col-sm-3">
                        <label for="rol_encuestador_preguntas">TIPO DE ACCESO:</label>
                        <select class="form-control" name="rol_encuestador_preguntas" readonly />
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
                    <div class="col-6 col-sm-4 mt-4">
                        <label for="bueno_preguntas">* PARA QUE ERES BUENO : </label>
                        <select class="form-control" name="bueno_preguntas" id="bueno_preguntas">
                            <option value=""></option>
                            <option value="deporte">DEPORTE</option>
                            <option value="artistica">ARTISTICA</option>
                            <option value="liderazgo">LIDERAZGO</option>
                            <option value="ciencia">CIENCIA</option>
                            <option value="tecnologia">TECNOLOGIA</option>
                            <option value="ocio">OCIO</option>
                            <option value="estudio">ESTUDIO</option>
                            <option value="video juegos">VIDEO JUEGOS</option>
                            <option value="otro">OTRO</option>
                        </select>
                    </div>
                    <div class="col-6 col-sm-4 mt-4">
                        <label for="grande_preguntas">* QUE QUIERES SER CUANDO SEAS GRANDE : </label>
                        <select class="form-control" name="grande_preguntas" id="grande_preguntas">
                            <option value=""></option>
                            <option value="profesional">PROFESIONAL</option>
                            <option value="empresario">EMPRESARIO</option>
                            <option value="madre o padre">MADRE O PADRE DE FAMILIA</option>
                            <option value="agricultor">AGRICULTOR</option>
                            <option value="migrar">MIGRAR</option>
                            <option value="militar">MILITAR</option>
                            <option value="independiente">INDEPENDIENTE</option>
                            <option value="ama de casa">AMA DE CASA</option>
                            <option value="influencer">INFLUENCER</option>
                            <option value="otro">OTRO</option>
                        </select>
                    </div>

                    <div class="col-6 col-sm-4 mt-4 ">
                        <label for="gustar_preguntas">* QUE TE GUSTA </label>
                        <select class="form-control" name="gustar_preguntas" id="gustar_preguntas">
                            <option value=""></option>
                            <option value="bailar">BAILAR</option>
                            <!-- LEER,COCINAR,PASEAR,VIDEO JUEGOS,REDES SOCIALES, estar con amigos,conducir,deportes,otro -->
                            <option value="leer">LEER</option>
                            <option value="cocinar">COCINAR</option>
                            <option value="pasear">PASEAR</option>
                            <option value="video juegos">VIDEO JUEGOS</option>
                            <option value="redes sociales">REDES SOCIALES</option>
                            <option value="estar con amigos">ESTAR CON AMIGOS</option>
                            <option value="conducir">CONDUCIR</option>
                            <option value="deportes">DEPORTES</option>
                            <option value="otro">OTRO</option>

                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-6 col-sm-12 mt-4">
                        <label for="recomendacion_preguntas">* RECOMENDACIONES DOCENTE : </label>
                        <input class="form-control" type="text" name="recomendacion_preguntas">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-12 mt-4">
                        <label for="recomendacion_preguntas"><strong>* CONSENTIMIENTO INFORMADO DE PADRES O ACUDIENTES:</strong></label>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <p>
                            Como padre, madre y/o acudiente mayor de edad del estudiante mencionado en el presente formulario identificado con el Documento de Identidad adjunto al presente, he sido informado acerca del diligenciamiento de la “Ficha de seguimiento integral al estudiante”, que realiza la Institución Educativa desde la acción pedagógica para identificar las fortalezas, dificultades, intereses, gustos y avances de los estudiantes de los niveles de Preescolar, Básica y Media, favoreciendo así el proceso de aprendizaje y de su formación integral.
                        </p>
                        <p>
                            Luego de haber sido informado(a) sobre las condiciones de mi participación, resuelto todas las inquietudes y comprendido en su totalidad la información sobre esta herramienta, entiendo que: Mi participación en el diligenciamiento de esta ficha o los resultados obtenidos por la persona evaluada no tendrán repercusiones o consecuencias en las actividades escolares, evaluaciones o calificaciones de mi hijo (o estudiante del que soy acudiente).
                        </p>
                        <p>
                            La persona que diligencia la “Ficha de seguimiento integral al estudiante” y la Institución Educativa garantizarán la protección de mi información suministrada y el uso de la misma, de acuerdo con la normatividad vigente sobre consentimientos informados (Ley 1581 de 2012 y Decreto 1377 de 2012), y de forma consciente y voluntaria.
                        </p>
                      
                    </div>
                </div>
                <label for="consentimiento_preguntas">*CONFIRMO QUE DOY EL CONSENTIMIENTO: </label>
                        <select class="form-control" name="consentimiento_preguntas" id="consentimiento_preguntas">
                            <option value=""></option>
                            <option value="si">SI</option>
                            <option value="no">NO</option>
                        </select>


            </div>





            <button type="submit" class="btn btn-primary mt-5 mb-4" name="btn-update">
                <span class="spinner-border spinner-border-sm"></span>
                ACTUALIZAR INFORMACIÓN PREGUNTAS ESTUDIANTE
            </button>
            <button type="reset" class="btn btn-outline-dark mt-5 mb-4" role='link' onclick="history.back();">
                <img src='../../../img/atras.png' width=27 height=27> REGRESAR
            </button>
        </form>
    </div>
</body>


</html>