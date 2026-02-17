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
            document.getElementById('eps-questions').style.display = displayStyle;
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

        <h1><b><img src="../../../img/baby.png" width=35 height=35> ACTUALIZAR INFORMACIÓN PREESCOLAR DEL ESTUDIANTE <img src="../../../img/baby.png" width=35 height=35></b></h1>
        <button onclick="window.print()" class="btn btn-primary mb-3">
            Imprimir formulario
        </button>
        <p><i><b>
                    <font size=3 color=#c68615>* Datos obligatorios</i></b></font>
        </p>

        <form action='processPreescolar.php' method="POST">

            <hr style="border: 2px solid #16087B; border-radius: 2px;">
            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-sm-2">
                        <label for="fecha_dig_preescolar">FECHA DILIGENC.</label>
                        <input type='text' name='fecha_dig_preescolar' id="fecha_dig_preescolar" class='form-control' value='<?php echo date("d-m-Y h:i", $time); ?>' readonly />
                    </div>
                    <div class="col-12 col-sm-3">
                        <label for="mun_dig_preescolar">* MUNICIPIO DILIGENCIAMIENTO:</label>
                        <select name='mun_dig_preescolar' class='form-control' id='selectMunicipio' required />
                        <option value=''></option>
                             <option value=''></option>
                            <?php
                            header('Content-Type: text/html;charset=utf-8');
                            $consulta = 'SELECT * FROM municipios';
                            $res = mysqli_query($mysqli, $consulta);
                            $num_reg = mysqli_num_rows($res);
                            $selected_mun = isset($row['mun_dig_est']) ? $row['mun_dig_est'] : '';
                            while ($row1 = $res->fetch_array()) {
                                $is_selected = ($selected_mun == $row1['nombre_mun']) ? 'selected' : '';
                            ?>
                                <option value='<?php echo $row1['nombre_mun']; ?>' <?php echo $is_selected; ?>>
                                    <?php echo $row1['nombre_mun']; ?>
                                </option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-12 col-sm-4">
                        <label for="nombre_encuestador_preescolar">NOMBRE DEL ENCUESTADOR:</label>
                        <input type='text' name='nombre_encuestador_preescolar' class='form-control' id="nombre_encuestador_preescolar" value='<?php echo $nombre; ?>' readonly />
                    </div>
                    <div class="col-12 col-sm-3">
                        <label for="rol_encuestador_preescolar">TIPO DE ACCESO:</label>
                        <select class="form-control" name="rol_encuestador_preescolar" readonly />
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

            <div class="form-group mt-4">
                <div class="row">
                    <div class="col-12">
                        <label for="relaciones">
                            <h4> Proposito 1: "Los niños y las niñas construyen su identidad en relación con los otros; se sienten queridos, y valoran positivamente pertenecer a una familia, cultura y mundo."
                        </label></h4>
                        <table class="table table-bordered table-info mt-4">
                            <thead>
                                <tr class="table-header">
                                    <th style="width: 70%;">Conceptos</th>
                                    <th style="width:30%;">Avances</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Explica las razones por las que hace una elección.</td>
                                    <td><select class="form-control" name="p1_eleccion_preescolar">
                                            <option value="">Seleccionar</option>
                                            <option value="sobresaliente">Avance sobresaliente</option>
                                            <option value="promedio">Avance promedio</option>
                                            <option value="minimo">Avance mínimo</option>
                                            <option value="sin_avance">Sin avance</option>
                                        </select></td>

                                </tr>
                                <tr>
                                    <td>Anticipa algunas consecuencias de las decisiones que toma.</td>
                                    <td><select class="form-control" name="p1_consecuen_preescolar">
                                            <option value="">Seleccionar</option>
                                            <option value="sobresaliente">Avance sobresaliente</option>
                                            <option value="promedio">Avance promedio</option>
                                            <option value="minimo">Avance mínimo</option>
                                            <option value="sin_avance">Sin avance</option>
                                        </select></td>

                                </tr>
                                <tr>
                                    <td>Muestra independencia en la realización de prácticas de higiene y alimentación saludables.</td>
                                    <td><select class="form-control" name="p1_independencia_preescolar">
                                            <option value="">Seleccionar</option>
                                            <option value="sobresaliente">Avance sobresaliente</option>
                                            <option value="promedio">Avance promedio</option>
                                            <option value="minimo">Avance mínimo</option>
                                            <option value="sin_avance">Sin avance</option>
                                        </select></td>

                                </tr>
                                <tr>
                                    <td>Representa su cuerpo a través de diferentes leguajes artísticos (dibujo, danza, escultura, rondas, entre otros).</td>
                                    <td><select class="form-control" name="p1_artistico_preescolar">
                                            <option value="">Seleccionar</option>
                                            <option value="sobresaliente">Avance sobresaliente</option>
                                            <option value="promedio">Avance promedio</option>
                                            <option value="minimo">Avance mínimo</option>
                                            <option value="sin_avance">Sin avance</option>
                                        </select></td>

                                </tr>
                                <tr>
                                    <td>Identifica características del lugar donde vive.</td>
                                    <td><select class="form-control" name="p1_lugarvive_preescolar">
                                            <option value="">Seleccionar</option>
                                            <option value="sobresaliente">Avance sobresaliente</option>
                                            <option value="promedio">Avance promedio</option>
                                            <option value="minimo">Avance mínimo</option>
                                            <option value="sin_avance">Sin avance</option>
                                        </select></td>

                                </tr>
                                <tr>
                                    <td>Describe roles de personas de su familia y entorno cercano.</td>
                                    <td><select class="form-control" name="p1_rolfamilia_preescolar">
                                            <option value="">Seleccionar</option>
                                            <option value="sobresaliente">Avance sobresaliente</option>
                                            <option value="promedio">Avance promedio</option>
                                            <option value="minimo">Avance mínimo</option>
                                            <option value="sin_avance">Sin avance</option>
                                        </select></td>

                                </tr>
                                <tr>
                                    <td>Reconoce que los demás pueden tener un punto de vista diferente al suyo y los escucha.</td>
                                    <td><select class="form-control" name="p1_escucha_preescolar">
                                            <option value="">Seleccionar</option>
                                            <option value="sobresaliente">Avance sobresaliente</option>
                                            <option value="promedio">Avance promedio</option>
                                            <option value="minimo">Avance mínimo</option>
                                            <option value="sin_avance">Sin avance</option>
                                        </select></td>

                                </tr>


                            </tbody>
                        </table>

                        <!-- Propósito 2 -->
                        <h4>Propósito 2: "Los niños y las niñas son comunicadores activos de sus ideas, sentimientos y emociones; expresan, imaginan y representan su realidad"</h4>
                        <table class="table table-bordered table-info mt-4">
                            <thead>
                                <tr class="table-header">
                                    <th style="width: 70%;">Conceptos</th>
                                    <th style="width:30%;">Avances</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Participa en canciones, rondas y juegos tradicionales haciendo aportes personales de manera espontánea.</td>
                                    <td><select class="form-control" name="p2_juegos_preescolar">
                                            <option value="">Seleccionar</option>
                                            <option value="sobresaliente">Avance sobresaliente</option>
                                            <option value="promedio">Avance promedio</option>
                                            <option value="minimo">Avance mínimo</option>
                                            <option value="sin_avance">Sin avance</option>
                                        </select></td>

                                </tr>
                                <tr>
                                    <td>dentifica palabras que riman en juegos con la música, las rondas, la poesía, juegos corporales, entre otros.</td>
                                    <td><select class="form-control" name="p2_palabras_preescolar">
                                            <option value="">Seleccionar</option>
                                            <option value="sobresaliente">Avance sobresaliente</option>
                                            <option value="promedio">Avance promedio</option>
                                            <option value="minimo">Avance mínimo</option>
                                            <option value="sin_avance">Sin avance</option>
                                        </select></td>

                                </tr>
                                <tr>
                                    <td>Sigue y construye juegos de segmentación de palabras orales a través de las palmas, el zapateo, y otras estrategias.</td>
                                    <td><select class="form-control" name="p2_segmenoral_preescolar">
                                            <option value="">Seleccionar</option>
                                            <option value="sobresaliente">Avance sobresaliente</option>
                                            <option value="promedio">Avance promedio</option>
                                            <option value="minimo">Avance mínimo</option>
                                            <option value="sin_avance">Sin avance</option>
                                        </select></td>

                                </tr>
                                <tr>
                                    <td>Lee imágenes, hace preguntas, formula ideas y crea historias a propósito de lo que percibe en diferentes registros (textos escritos, pinturas, aplicaciones, páginas web, entre otros).</td>
                                    <td><select class="form-control" name="p2_crea_preescolar">
                                            <option value="">Seleccionar</option>
                                            <option value="sobresaliente">Avance sobresaliente</option>
                                            <option value="promedio">Avance promedio</option>
                                            <option value="minimo">Avance mínimo</option>
                                            <option value="sin_avance">Sin avance</option>
                                        </select></td>

                                </tr>
                                <tr>
                                    <td>Explora diferentes tipos de texto y reconoce su propósito (recetarios, libro álbum, cuento, diccionarios ilustrados, enciclopedias infantiles, cancioneros, entre otros).</td>
                                    <td><select class="form-control" name="p2_leer_preescolar">
                                            <option value="">Seleccionar</option>
                                            <option value="sobresaliente">Avance sobresaliente</option>
                                            <option value="promedio">Avance promedio</option>
                                            <option value="minimo">Avance mínimo</option>
                                            <option value="sin_avance">Sin avance</option>
                                        </select></td>

                                </tr>
                                <tr>
                                    <td> Se interesa por saber cómo se escriben las palabras que escucha.</td>
                                    <td><select class="form-control" name="p2_escribir_preescolar">
                                            <option value="">Seleccionar</option>
                                            <option value="sobresaliente">Avance sobresaliente</option>
                                            <option value="promedio">Avance promedio</option>
                                            <option value="minimo">Avance mínimo</option>
                                            <option value="sin_avance">Sin avance</option>
                                        </select></td>

                                </tr>

                            </tbody>
                        </table>

                        <!-- Propósito 3 -->
                        <h4> "Los niños y las niñas disfrutan aprender; exploran y se relacionan con el mundo para comprenderlo y construirlo". </h4>
                        <table class="table table-bordered table-info mt-4">
                            <thead>
                                <tr class="table-header">
                                    <th style="width: 70%;">Conceptos</th>
                                    <th style="width:30%;">Avances</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Muestra atención y concentración en las actividades que desarrolla.</td>
                                    <td><select class="form-control" name="p3_concentracion_preescolar">
                                            <option value="">Seleccionar</option>
                                            <option value="sobresaliente">Avance sobresaliente</option>
                                            <option value="promedio">Avance promedio</option>
                                            <option value="minimo">Avance mínimo</option>
                                            <option value="sin_avance">Sin avance</option>
                                        </select></td>

                                </tr>
                                <tr>
                                    <td>Formula explicaciones para aquello que sucede a su alrededor.</td>
                                    <td><select class="form-control" name="p3_explica_preescolar">
                                            <option value="">Seleccionar</option>
                                            <option value="sobresaliente">Avance sobresaliente</option>
                                            <option value="promedio">Avance promedio</option>
                                            <option value="minimo">Avance mínimo</option>
                                            <option value="sin_avance">Sin avance</option>
                                        </select></td>

                                </tr>
                                <tr>
                                    <td>Participa en juegos de transformaciones y construcción de juguetes con materiales cotidianos y bloques de construcción.</td>
                                    <td><select class="form-control" name="p3_participa_preescolar">
                                            <option value="">Seleccionar</option>
                                            <option value="sobresaliente">Avance sobresaliente</option>
                                            <option value="promedio">Avance promedio</option>
                                            <option value="minimo">Avance mínimo</option>
                                            <option value="sin_avance">Sin avance</option>
                                        </select></td>

                                </tr>
                                <tr>
                                    <td>Identifica características de las cosas que encuentra a su alrededor y se pegunta sobre cómo funcionan.</td>
                                    <td><select class="form-control" name="p3_identifica_preescolar">
                                            <option value="">Seleccionar</option>
                                            <option value="sobresaliente">Avance sobresaliente</option>
                                            <option value="promedio">Avance promedio</option>
                                            <option value="minimo">Avance mínimo</option>
                                            <option value="sin_avance">Sin avance</option>
                                        </select></td>

                                </tr>
                                <tr>
                                    <td>Sitúa acontecimientos relevantes en el tiempo.</td>
                                    <td><select class="form-control" name="p3_acontecimientos_preescolar">
                                            <option value="">Seleccionar</option>
                                            <option value="sobresaliente">Avance sobresaliente</option>
                                            <option value="promedio">Avance promedio</option>
                                            <option value="minimo">Avance mínimo</option>
                                            <option value="sin_avance">Sin avance</option>
                                        </select></td>

                                </tr>
                                <tr>
                                    <td>Reconoce y establece relaciones espaciales a partir de su cuerpo y objetos (izquierda-derecha, arriba abajo, delante-detrás, cerca-lejos, dentro- fuera) al participar en actividades grupales como juegos, danzas y rondas.</td>
                                    <td><select class="form-control" name="p3_cuerpo_preescolar">
                                            <option value="">Seleccionar</option>
                                            <option value="sobresaliente">Avance sobresaliente</option>
                                            <option value="promedio">Avance promedio</option>
                                            <option value="minimo">Avance mínimo</option>
                                            <option value="sin_avance">Sin avance</option>
                                        </select></td>

                                </tr>
                                <tr>
                                    <td>Identifica el patrón que conforma una secuencia (pollo- gato-pollo) y puede continuarla (pollo-gato-pollo-gato).</td>
                                    <td><select class="form-control" name="p3_secuencia_preescolar">
                                            <option value="">Seleccionar</option>
                                            <option value="sobresaliente">Avance sobresaliente</option>
                                            <option value="promedio">Avance promedio</option>
                                            <option value="minimo">Avance mínimo</option>
                                            <option value="sin_avance">Sin avance</option>
                                        </select></td>

                                </tr>
                                <tr>
                                    <td>Crea series de acuerdo a un atributo (del más largo al más corto, del más pesado al más liviano, etc).</td>
                                    <td><select class="form-control" name="p3_atributo_preescolar">
                                            <option value="">Seleccionar</option>
                                            <option value="sobresaliente">Avance sobresaliente</option>
                                            <option value="promedio">Avance promedio</option>
                                            <option value="minimo">Avance mínimo</option>
                                            <option value="sin_avance">Sin avance</option>
                                        </select></td>

                                </tr>
                                <tr>
                                    <td>Determina cuántos objetos conforman una colección a partir de: la percepción global, la enumeración y la correspondencia uno a uno.</td>
                                    <td><select class="form-control" name="p3_enumeracion_preescolar">
                                            <option value="">Seleccionar</option>
                                            <option value="sobresaliente">Avance sobresaliente</option>
                                            <option value="promedio">Avance promedio</option>
                                            <option value="minimo">Avance mínimo</option>
                                            <option value="sin_avance">Sin avance</option>
                                        </select></td>

                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <button type="submit" class="btn btn-primary mt-5 mb-4" name="btn-update">
                <span class="spinner-border spinner-border-sm"></span>
                ACTUALIZAR INFORMACIÓN PREESCOLAR
            </button>
            <a href="../showPreescolar.php" class="btn btn-outline-dark mt-5 mb-4" role='link'>
                <img src='../../../img/atras.png' width=27 height=27> REGRESAR
            </a>
        </form>
    </div>
</body>


</html>