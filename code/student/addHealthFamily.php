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

        <h1><b><img src="../../img/baby.png" width=35 height=35> ACTUALIZAR INFORMACIÓN FAMILIA DEL ESTUDIANTE <img src="../../img/baby.png" width=35 height=35></b></h1>
        <button onclick="window.print()" class="btn btn-primary mb-3">
            Imprimir formulario
        </button>
        <p><i><b>
                    <font size=3 color=#c68615>* Datos obligatorios</i></b></font>
        </p>

        <form action='processHealthFamily.php' method="POST">

            <hr style="border: 2px solid #16087B; border-radius: 2px;">
            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-sm-2">
                        <label for="fecha_dig_familiaSalud">FECHA DILIGENC.</label>
                        <input type='text' name='fecha_dig_familiaSalud' id="fecha_dig_familiaSalud" class='form-control' value='<?php echo date("d-m-Y h:i", $time); ?>' readonly />
                    </div>
                    <div class="col-12 col-sm-3">
                        <label for="mun_dig_familiaSalud">* MUNICIPIO DILIGENCIAMIENTO:</label>
                        <select name='mun_dig_familiaSalud' class='form-control' id='selectMunicipio' required />
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
                        <label for="nombre_encuestador_familiaSalud">NOMBRE DEL ENCUESTADOR:</label>
                        <input type='text' name='nombre_encuestador_familiaSalud' class='form-control' id="nombre_encuestador_familiaSalud" value='<?php echo $nombre; ?>' readonly />
                    </div>
                    <div class="col-12 col-sm-3">
                        <label for="rol_encuestador_familiaSalud">TIPO DE ACCESO:</label>
                        <select class="form-control" name="rol_encuestador_familiaSalud" readonly />
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
                    <div class="col-12">
                        <label for="relaciones">* COMO CALIFICA LAS RELACIONES ACTUALES CON SUS FAMILIARES:</label>
                        <table class="table table-bordered table-info">
                            <thead>
                                <tr>
                                    <th>Familiar</th>
                                    <th>Satisfactorias</th>
                                    <th>Llevaderas</th>
                                    <th>Tensionadas</th>
                                    <th>Hostiles</th>
                                    <th>Conflictivas</th>
                                    <th>Incomunicadas</th>
                                    <th>Disfuncionales</th>
                                    <th>No aplica</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Madre</td>
                                    <td><input type="radio" name="relacion_madre_familiaSalud" value="satisfactorias"></td>
                                    <td><input type="radio" name="relacion_madre_familiaSalud" value="llevaderas"></td>
                                    <td><input type="radio" name="relacion_madre_familiaSalud" value="tensionadas"></td>
                                    <td><input type="radio" name="relacion_madre_familiaSalud" value="hostiles"></td>
                                    <td><input type="radio" name="relacion_madre_familiaSalud" value="conflictivas"></td>
                                    <td><input type="radio" name="relacion_madre_familiaSalud" value="incomunicadas"></td>
                                    <td><input type="radio" name="relacion_madre_familiaSalud" value="disfuncionales"></td>
                                    <td><input type="radio" name="relacion_madre_familiaSalud" value="no aplica"></td>
                                </tr>
                                <tr>
                                    <td>Padre</td>
                                    <td><input type="radio" name="relacion_padre_familiaSalud" value="satisfactorias"></td>
                                    <td><input type="radio" name="relacion_padre_familiaSalud" value="llevaderas"></td>
                                    <td><input type="radio" name="relacion_padre_familiaSalud" value="tensionadas"></td>
                                    <td><input type="radio" name="relacion_padre_familiaSalud" value="hostiles"></td>
                                    <td><input type="radio" name="relacion_padre_familiaSalud" value="conflictivas"></td>
                                    <td><input type="radio" name="relacion_padre_familiaSalud" value="incomunicadas"></td>
                                    <td><input type="radio" name="relacion_padre_familiaSalud" value="disfuncionales"></td>
                                    <td><input type="radio" name="relacion_padre_familiaSalud" value="no aplica"></td>
                                </tr>

                                <tr>
                                    <td>Hermanos</td>
                                    <td><input type="radio" name="relacion_hermanos_familiaSalud" value="satisfactorias"></td>
                                    <td><input type="radio" name="relacion_hermanos_familiaSalud" value="llevaderas"></td>
                                    <td><input type="radio" name="relacion_hermanos_familiaSalud" value="tensionadas"></td>
                                    <td><input type="radio" name="relacion_hermanos_familiaSalud" value="hostiles"></td>
                                    <td><input type="radio" name="relacion_hermanos_familiaSalud" value="conflictivas"></td>
                                    <td><input type="radio" name="relacion_hermanos_familiaSalud" value="incomunicadas"></td>
                                    <td><input type="radio" name="relacion_hermanos_familiaSalud" value="disfuncionales"></td>
                                    <td><input type="radio" name="relacion_hermanos_familiaSalud" value="no aplica"></td>
                                </tr>
                                <tr>
                                    <td>Abuelos</td>
                                    <td><input type="radio" name="relacion_abuelos_familiaSalud" value="satisfactorias"></td>
                                    <td><input type="radio" name="relacion_abuelos_familiaSalud" value="llevaderas"></td>
                                    <td><input type="radio" name="relacion_abuelos_familiaSalud" value="tensionadas"></td>
                                    <td><input type="radio" name="relacion_abuelos_familiaSalud" value="hostiles"></td>
                                    <td><input type="radio" name="relacion_abuelos_familiaSalud" value="conflictivas"></td>
                                    <td><input type="radio" name="relacion_abuelos_familiaSalud" value="incomunicadas"></td>
                                    <td><input type="radio" name="relacion_abuelos_familiaSalud" value="disfuncionales"></td>
                                    <td><input type="radio" name="relacion_abuelos_familiaSalud" value="no aplica"></td>
                                </tr>
                                <tr>
                                    <td>Tíos</td>
                                    <td><input type="radio" name="relacion_tios_familiaSalud" value="satisfactorias"></td>
                                    <td><input type="radio" name="relacion_tios_familiaSalud" value="llevaderas"></td>
                                    <td><input type="radio" name="relacion_tios_familiaSalud" value="tensionadas"></td>
                                    <td><input type="radio" name="relacion_tios_familiaSalud" value="hostiles"></td>
                                    <td><input type="radio" name="relacion_tios_familiaSalud" value="conflictivas"></td>
                                    <td><input type="radio" name="relacion_tios_familiaSalud" value="incomunicadas"></td>
                                    <td><input type="radio" name="relacion_tios_familiaSalud" value="disfuncionales"></td>
                                    <td><input type="radio" name="relacion_tios_familiaSalud" value="no aplica"></td>
                                </tr>
                                <tr>
                                    <td>Otros familiares</td>
                                    <td><input type="radio" name="relacion_otros_familiaSalud" value="satisfactorias"></td>
                                    <td><input type="radio" name="relacion_otros_familiaSalud" value="llevaderas"></td>
                                    <td><input type="radio" name="relacion_otros_familiaSalud" value="tensionadas"></td>
                                    <td><input type="radio" name="relacion_otros_familiaSalud" value="hostiles"></td>
                                    <td><input type="radio" name="relacion_otros_familiaSalud" value="conflictivas"></td>
                                    <td><input type="radio" name="relacion_otros_familiaSalud" value="incomunicadas"></td>
                                    <td><input type="radio" name="relacion_otros_familiaSalud" value="disfuncionales"></td>
                                    <td><input type="radio" name="relacion_otros_familiaSalud" value="no aplica"></td>

                            </tbody>
                        </table>
                    </div>



                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-6 col-sm-4">
                        <label for="discapacidad_est_familiaSalud">* ESTUDIANTE PRESENTA DISCAPACIDAD: </label>
                        <select class="form-control" name="discapacidad_est_familiaSalud" id="discapacidad_est_familiaSalud">
                            <option value=""></option>
                            <option value="0">SI</option>
                            <option value="1">NO</option>
                        </select>

                    </div>
                    <div class="col-8">
                        <label for="Afecciones">SITUACIONES QUE AFECTAN APRENDIZAJE DEL ESTUDIANTE:</label>
                        <div class="form-control" id="Afecciones" style="height: auto;">
                            <div class="row">
                                <div class="col-8 col-sm-6 col-md-4">
                                    <input type="checkbox" name="afecta_aprendizaje_familiaSalud[]" value="ambiente familiar"> Ambiente familiar<br>
                                    <input type="checkbox" name="afecta_aprendizaje_familiaSalud[]" value="factor economico"> Factor economico<br>
                                    <input type="checkbox" name="afecta_aprendizaje_familiaSalud[]" value="calidad educacion"> Calidad Educacion<br>
                                    <input type="checkbox" name="afecta_aprendizaje_familiaSalud[]" value="salud mental"> Salud Mental<br>
                                </div>
                                <div class="col-8 col-sm-6 col-md-4">
                                    <input type="checkbox" name="afecta_aprendizaje_familiaSalud[]" value="traumas"> Experiencias traumaticas<br>
                                    <input type="checkbox" name="afecta_aprendizaje_familiaSalud[]" value="nutricion"> Nutricion<br>
                                    <input type="checkbox" name="afecta_aprendizaje_familiaSalud[]" value="dificultades aprendizaje"> Dificultades de aprendizaje<br>
                                    <input type="checkbox" name="afecta_aprendizaje_familiaSalud[]" value="habilidades sociales"> Habilidades Sociales<br>
                                </div>
                                <div class="col-8 col-sm-6 col-md-4">
                                    <input type="checkbox" name="afecta_aprendizaje_familiaSalud[]" value="acoso"> Discriminacion o acoso<br>
                                    <input type="checkbox" name="afecta_aprendizaje_familiaSalud[]" value="lenguaje"> Barreras de lenguaje<br>
                                    <input type="checkbox" name="afecta_aprendizaje_familiaSalud[]" value="motivacion"> Falta motivacion<br>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>





            <div class="form-group">
                <div class="row">
                    <div class="col-6 col-sm-3">
                        <label for="beneficiario_pae_familiaSalud">* EL ESTUDIANTE ES BENEFICIARIO DEL PROGRAMA PAE: </label>
                        <select class="form-control" name="beneficiario_pae_familiaSalud" id="beneficiario_pae_familiaSalud">
                            <option value=""></option>
                            <option value=1>SI</option>
                            <option value=0>NO</option>
                        </select>
                    </div>

                    <div class="col-6 col-sm-4">
                        <label for="comida_dia_familiaSalud">* CUANTOS MOMENTOS DE COMIDA TIENE AL DIA:</label>
                        <select class="form-control" name="comida_dia_familiaSalud" id="comida_dia_familiaSalud">
                            <option value=""></option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                        </select>
                    </div>
                    <div class="col-6 col-sm-4 mt-4  ">
                        <label for="eps_estudiante_familiaSalud">* SE ENCUENTRA AFILIADO A EPS:</label>
                        <select class="form-control" name="eps_estudiante_familiaSalud" id="eps_estudiante_familiaSalud">
                            <option value=""></option>
                            <option value="1">SI</option>
                            <option value="2">NO</option>
                        </select>
                    </div>
                </div>
            </div>


            <div class="form-group" id="eps-questions" style="display: none;">
                <div class="row">
                    <div class="col-6 col-sm-3">
                        <label for="nombre_eps_familiaSalud">* NOMBRE DE LA EPS AFILIADA: </label>
                        <select class="form-control" name="nombre_eps_familiaSalud" id="nombre_eps_familiaSalud">
                            <option value=""></option>
                            <option value="otro">Otra</option>
                            <option value="Nueva eps">Nueva EPS</option>
                            <option value="Salud Total">Salud Total</option>
                            <option value="Coosalud">Coosalud</option>
                            <option value="Sura">Sura</option>
                            <option value="Sanitas">Sanitas</option>
                            <option value="Wayu">Wayu</option>
                            <option value="Aliansalud">Aliansalud</option>
                            <option value="Compensar">Compensar</option>
                            <option value="Salud Bolívar">Salud Bolívar</option>
                            <option value="Cafesalud">Cafesalud</option>
                            <option value="Cruz Blanca">Cruz Blanca</option>
                            <option value="Famisanar">Famisanar</option>
                            <option value="Medimás">Medimás</option>
                            <option value="Mutual Ser">Mutual Ser</option>
                            <option value="SOS">SOS</option>
                        </select>
                    </div>
                    <div class="col-6 col-sm-4 ">
                        <label for="cual_eps_familiaSalud">* SI ES OTRA CUAL?:</label>
                        <input class="form-control" type="text" name="cual_eps_familiaSalud">
                    </div>

                    <div class="col-6 col-sm-4">
                        <label for="afiliado_eps_familiaSalud">SISTEMA DE SALUD AL CUAL ESTÁ AFILIADO:</label>
                        <select class="form-control" name="afiliado_eps_familiaSalud" id="afiliado_eps_familiaSalud">
                            <option value=""></option>
                            <option value="Contributivo">Contributivo</option>
                            <option value="Subsidiado">Subsidiado</option>
                            <option value="Especial">Especial</option>
                        </select>
                    </div>

                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-6 col-sm-3">
                        <label for="presenta_diagnostico_familiaSalud">* EL ESTUDIANTE TIENE ALGUN DIAGNOSTICO MEDICO: :</label>
                        <select class="form-control" name="presenta_diagnostico_familiaSalud" id="presenta_diagnostico_familiaSalud">
                            <option value=""></option>
                            <option value="1">SI</option>
                            <option value="2">NO</option>
                        </select>
                    </div>
                    <div class="col-6 col-sm-4 mt-4">
                        <label for="diagnostico_familiaSalud">* CUAL ES EL DIAGNOSTICO MEDICO:</label>
                        <input class="form-control" type="text" name="diagnostico_familiaSalud">
                    </div>
                    <div class="col-6 col-sm-4 mt-4">
                        <label for="terapia_familiaSalud">* EL ESTUDIANTE ASISTE A TERAPIA:</label>
                        <select class="form-control" name="terapia_familiaSalud" id="terapia_familiaSalud">
                            <option value=""></option>
                            <option value="1">SI</option>
                            <option value="2">NO</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-6 col-sm-3 mt-4">
                            <label for="frecuencia_terapia_familiaSalud">* CON QUE FRECUENCIA ASISTE A TERAPIA:</label>
                            <select class="form-control" name="frecuencia_terapia_familiaSalud" id="frecuencia_terapia_familiaSalud">
                                <option value=""></option>
                                <option value="Semanal">Semanal</option>
                                <option value="Quincenal">Quincenal</option>
                                <option value="Mensual">Mensual</option>
                                <option value="Bimestral">Bimestral</option>
                                <option value="Trimestral">Trimestral</option>
                                <option value="Semestral">Semestral</option>
                                <option value="Anual">Anual</option>
                            </select>
                        </div>

                        <div class="col-6 col-sm-4 mt-4">
                            <label for="condicion_particular_familiaSalud">* ACTUALMENTE ESTA SIENDO ATENDIDO POR EL SECTOR SALUD POR ALGUNA CONDICION:</label>
                            <select class="form-control" name="condicion_particular_familiaSalud" id="condicion_particular_familiaSalud">
                                <option value=""></option>
                                <option value="1">SI</option>
                                <option value="2">NO</option>
                            </select>
                        </div>
                        <div class="col-6 col-sm-4 mt-4">
                            <label for="frecuencia_atencion_familiaSalud">* CON QUE FRECUENCIA ES ATENDIDO POR EL SECTOR SALUD:</label>
                            <select class="form-control" name="frecuencia_atencion_familiaSalud" id="frecuencia_atencion_familiaSalud">
                                <option value=""></option>
                                <option value="Semanal">Semanal</option>
                                <option value="Quincenal">Quincenal</option>
                                <option value="Mensual">Mensual</option>
                                <option value="Bimestral">Bimestral</option>
                                <option value="Trimestral">Trimestral</option>
                                <option value="Semestral">Semestral</option>
                                <option value="Anual">Anual</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-6 col-sm-4 ">
                            <label for="alergia_familiaSalud">* PRESENTA ALGUN TIPO DE ALERGIA (alimentos, medicamentos, entorno):</label>
                            <select class="form-control" name="alergia_familiaSalud" id="alergia_familiaSalud">
                                <option value=""></option>
                                <option value="1">SI</option>
                                <option value="2">NO</option>
                            </select>
                        </div>

                        <div class="col-6 col-sm-4 ">
                            <label for="tipo_alergia_familiaSalud">* EN CASO DE TENER ALGUN TIPO ALERGIA, MENCIONELA:</label>
                            <input class="form-control" type="text" name="tipo_alergia_familiaSalud">
                        </div>

                        <div class="col-6 col-sm-4 ">
                            <label for="vacunacion_familiaSalud">* CUENTA CON EL ESQUEMA DE VACUNACION COMPLETO:</label>
                            <select class="form-control" name="vacunacion_familiaSalud" id="vacunacion_familiaSalud">
                                <option value=""></option>
                                <option value="1">SI</option>
                                <option value="2">NO</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-6 col-sm-4 mt-4">
                                <label for="sangre_familiaSalud">* TIPO Y FACTOR SANGUINEO:</label>
                                <select class="form-control" name="sangre_familiaSalud" id="sangre_familiaSalud">
                                    <option value=""></option>
                                    <option value="O+">O+</option>
                                    <option value="O-">O-</option>
                                    <option value="A+">A+</option>
                                    <option value="A-">A-</option>
                                    <option value="B+">B+</option>
                                    <option value="B-">B-</option>
                                    <option value="AB+">AB+</option>
                                    <option value="AB-">AB-</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary" name="btn-update">
                        <span class="spinner-border spinner-border-sm"></span>
                        ACTUALIZAR INFORMACIÓN FAMILIA
                    </button>
                    <button type="reset" class="btn btn-outline-dark" role='link' onclick="history.back();">
                        <img src='../../img/atras.png' width=27 height=27> REGRESAR
                    </button>
        </form>
    </div>
</body>
<script>
    // Mostrar u ocultar preguntas dependiendo de la respuesta de la pregunta eps
    document.addEventListener('DOMContentLoaded', function() {
        var epsSelect = document.getElementById('eps_estudiante_familiaSalud');
        var epsQuestions = document.getElementById('eps-questions');

        epsSelect.addEventListener('change', function() {
            if (epsSelect.value === '1') { // Si se selecciona "SI"
                epsQuestions.style.display = 'block';
            } else { // Si se selecciona "NO" o ninguna opción
                epsQuestions.style.display = 'none';
            }
        });
    });
</script>

</html>
