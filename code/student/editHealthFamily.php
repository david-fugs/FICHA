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

    <script>
        
function Si1No2($value)
{
    if ($value == 1) {
        return "SI";
    } else {
        return "NO";
    }
}
    </script>

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
    $time = time();
    $num_doc_est  = $_GET['num_doc_est'];
    $id_registro        = $_GET['idSalud'];

    if (isset($_GET['num_doc_est'])) {
        $sql = mysqli_query($mysqli, "SELECT * FROM estudiantes INNER JOIN familiasalud ON estudiantes.num_doc_est=familiasalud.num_doc_est WHERE estudiantes.num_doc_est = '$num_doc_est'");
        $row = mysqli_fetch_array($sql);
        //$row = $result->fetch_assoc();
        $fec_nac_est = $row['fec_nac_est'];

        // Calcula la edad
        $fecha_actual = new DateTime();
        $fec_nac_est = new DateTime($fec_nac_est);
        $edad = $fecha_actual->diff($fec_nac_est)->y;


        //formulario
        $sql_formulario =  mysqli_query($mysqli, "SELECT * FROM familiasalud WHERE id_salud_familiaSalud = '$id_registro' ");
        $res_formulario =  mysqli_fetch_array($sql_formulario);

        //traer nombre del estudiante con el num_doc_est
        $sql_nombre = mysqli_query($mysqli, "SELECT nom_ape_est FROM estudiantes WHERE num_doc_est = '$num_doc_est' ");
        $res_nombre = mysqli_fetch_array($sql_nombre);
        
    }

    ?>

    <div class="container">
        <center>
            <img src='../../img/logo_educacion.png' width=600 height=121 class='responsive'>
        </center>

        <h1><b><img src="../../img/baby.png" width=35 height=35> ACTUALIZAR INFORMACIÓN PRE POSTNATAL DEL ESTUDIANTE <img src="../../img/baby.png" width=35 height=35></b></h1>
        <p><i><b>
                    <font size=3 color=#c68615>* Datos obligatorios</i></b></font>
        </p>

        <form action='editHealthFamily1.php' method="POST">

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
                            <option value='<?php echo $row1['nombre_mun']; ?>' <?php if ($row['mun_dig_familiaSalud'] == $row1['nombre_mun']) {
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
                        <input type='text' name='nom_ape_est' id="nom_ape_est" class='form-control' value='<?php echo $res_nombre['nom_ape_est'] ?>' readonly />
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
                                    <td><input type="radio" name="relacion_madre_familiaSalud" value="satisfactorias"<?php if ($res_formulario['relacion_madre_familiaSalud'] == 'satisfactorias') {echo 'checked';} ?>></td>
                                    <td><input type="radio" name="relacion_madre_familiaSalud" value="llevaderas"<?php if ($res_formulario['relacion_madre_familiaSalud'] == 'llevaderas') {echo 'checked';} ?>></td>
                                    <td><input type="radio" name="relacion_madre_familiaSalud" value="tensionadas"<?php if ($res_formulario['relacion_madre_familiaSalud'] == 'tensionadas') {echo 'checked';} ?>></td>
                                    <td><input type="radio" name="relacion_madre_familiaSalud" value="hostiles"<?php if ($res_formulario['relacion_madre_familiaSalud'] == 'hostiles') {echo 'checked';} ?>></td>
                                    <td><input type="radio" name="relacion_madre_familiaSalud" value="conflictivas"<?php if ($res_formulario['relacion_madre_familiaSalud'] == 'conflictivas') {echo 'checked';} ?>></td>
                                    <td><input type="radio" name="relacion_madre_familiaSalud" value="incomunicadas"<?php if ($res_formulario['relacion_madre_familiaSalud'] == 'incomunicadas') {echo 'checked';} ?>></td>
                                    <td><input type="radio" name="relacion_madre_familiaSalud" value="disfuncionales"<?php if ($res_formulario['relacion_madre_familiaSalud'] == 'disfuncionales') {echo 'checked';} ?>></td>
                                    <td><input type="radio" name="relacion_madre_familiaSalud" value="no aplica"<?php if ($res_formulario['relacion_madre_familiaSalud'] == 'no aplica') {echo 'checked';} ?>></td>  
                                </tr>
                                <tr>
                                    <td>Padre</td>
                                    <td><input type="radio" name="relacion_padre_familiaSalud" value="satisfactorias"<?php if ($res_formulario['relacion_padre_familiaSalud'] == 'satisfactorias') {echo 'checked';} ?>></td>
                                    <td><input type="radio" name="relacion_padre_familiaSalud" value="llevaderas"<?php if ($res_formulario['relacion_padre_familiaSalud'] == 'llevaderas') {echo 'checked';} ?>></td>
                                    <td><input type="radio" name="relacion_padre_familiaSalud" value="tensionadas"<?php if ($res_formulario['relacion_padre_familiaSalud'] == 'tensionadas') {echo 'checked';} ?>></td>
                                    <td><input type="radio" name="relacion_padre_familiaSalud" value="hostiles"<?php if ($res_formulario['relacion_padre_familiaSalud'] == 'hostiles') {echo 'checked';} ?>></td>
                                    <td><input type="radio" name="relacion_padre_familiaSalud" value="conflictivas"<?php if ($res_formulario['relacion_padre_familiaSalud'] == 'conflictivas') {echo 'checked';} ?>></td>
                                    <td><input type="radio" name="relacion_padre_familiaSalud" value="incomunicadas"<?php if ($res_formulario['relacion_padre_familiaSalud'] == 'incomunicadas') {echo 'checked';} ?>></td>
                                    <td><input type="radio" name="relacion_padre_familiaSalud" value="disfuncionales"<?php if ($res_formulario['relacion_padre_familiaSalud'] == 'disfuncionales') {echo 'checked';} ?>></td>
                                    <td><input type="radio" name="relacion_padre_familiaSalud" value="no aplica"<?php if ($res_formulario['relacion_padre_familiaSalud'] == 'no aplica') {echo 'checked';} ?>></td>
                                </tr>
                                <tr>
                                    <td>Hermanos</td>
                                    <td><input type="radio" name="relacion_hermanos_familiaSalud" value="satisfactorias"<?php if ($res_formulario['relacion_hermanos_familiaSalud'] == 'satisfactorias') {echo 'checked';} ?>></td>
                                    <td><input type="radio" name="relacion_hermanos_familiaSalud" value="llevaderas"<?php if ($res_formulario['relacion_hermanos_familiaSalud'] == 'llevaderas') {echo 'checked';} ?>></td>
                                    <td><input type="radio" name="relacion_hermanos_familiaSalud" value="tensionadas"<?php if ($res_formulario['relacion_hermanos_familiaSalud'] == 'tensionadas') {echo 'checked';} ?>></td>
                                    <td><input type="radio" name="relacion_hermanos_familiaSalud" value="hostiles"<?php if ($res_formulario['relacion_hermanos_familiaSalud'] == 'hostiles') {echo 'checked';} ?>></td>
                                    <td><input type="radio" name="relacion_hermanos_familiaSalud" value="conflictivas"<?php if ($res_formulario['relacion_hermanos_familiaSalud'] == 'conflictivas') {echo 'checked';} ?>></td>
                                    <td><input type="radio" name="relacion_hermanos_familiaSalud" value="incomunicadas"<?php if ($res_formulario['relacion_hermanos_familiaSalud'] == 'incomunicadas') {echo 'checked';} ?>></td>
                                    <td><input type="radio" name="relacion_hermanos_familiaSalud" value="disfuncionales"<?php if ($res_formulario['relacion_hermanos_familiaSalud'] == 'disfuncionales') {echo 'checked';} ?>></td>
                                    <td><input type="radio" name="relacion_hermanos_familiaSalud" value="no aplica"<?php if ($res_formulario['relacion_hermanos_familiaSalud'] == 'no aplica') {echo 'checked';} ?>></td>
                                </tr>
                                <tr>
                                    <td>Abuelos</td>
                                    <td><input type="radio" name="relacion_abuelos_familiaSalud" value="satisfactorias"<?php if ($res_formulario['relacion_abuelos_familiaSalud'] == 'satisfactorias') {echo 'checked';} ?>></td>
                                    <td><input type="radio" name="relacion_abuelos_familiaSalud" value="llevaderas"<?php if ($res_formulario['relacion_abuelos_familiaSalud'] == 'llevaderas') {echo 'checked';} ?>></td>
                                    <td><input type="radio" name="relacion_abuelos_familiaSalud" value="tensionadas"<?php if ($res_formulario['relacion_abuelos_familiaSalud'] == 'tensionadas') {echo 'checked';} ?>></td>
                                    <td><input type="radio" name="relacion_abuelos_familiaSalud" value="hostiles"<?php if ($res_formulario['relacion_abuelos_familiaSalud'] == 'hostiles') {echo 'checked';} ?>></td>
                                    <td><input type="radio" name="relacion_abuelos_familiaSalud" value="conflictivas"<?php if ($res_formulario['relacion_abuelos_familiaSalud'] == 'conflictivas') {echo 'checked';} ?>></td>
                                    <td><input type="radio" name="relacion_abuelos_familiaSalud" value="incomunicadas"<?php if ($res_formulario['relacion_abuelos_familiaSalud'] == 'incomunicadas') {echo 'checked';} ?>></td>
                                    <td><input type="radio" name="relacion_abuelos_familiaSalud" value="disfuncionales"<?php if ($res_formulario['relacion_abuelos_familiaSalud'] == 'disfuncionales') {echo 'checked';} ?>></td>
                                    <td><input type="radio" name="relacion_abuelos_familiaSalud" value="no aplica"<?php if ($res_formulario['relacion_abuelos_familiaSalud'] == 'no aplica') {echo 'checked';} ?>></td>
                                </tr>
                                <tr>
                                    <td>Tíos</td>
                                    <td><input type="radio" name="relacion_tios_familiaSalud" value="satisfactorias"<?php if ($res_formulario['relacion_tios_familiaSalud'] == 'satisfactorias') {echo 'checked';} ?>></td>
                                    <td><input type="radio" name="relacion_tios_familiaSalud" value="llevaderas"<?php if ($res_formulario['relacion_tios_familiaSalud'] == 'llevaderas') {echo 'checked';} ?>></td>
                                    <td><input type="radio" name="relacion_tios_familiaSalud" value="tensionadas"<?php if ($res_formulario['relacion_tios_familiaSalud'] == 'tensionadas') {echo 'checked';} ?>></td>
                                    <td><input type="radio" name="relacion_tios_familiaSalud" value="hostiles"<?php if ($res_formulario['relacion_tios_familiaSalud'] == 'hostiles') {echo 'checked';} ?>></td>
                                    <td><input type="radio" name="relacion_tios_familiaSalud" value="conflictivas"<?php if ($res_formulario['relacion_tios_familiaSalud'] == 'conflictivas') {echo 'checked';} ?>></td>
                                    <td><input type="radio" name="relacion_tios_familiaSalud" value="incomunicadas"<?php if ($res_formulario['relacion_tios_familiaSalud'] == 'incomunicadas') {echo 'checked';} ?>></td>
                                    <td><input type="radio" name="relacion_tios_familiaSalud" value="disfuncionales"<?php if ($res_formulario['relacion_tios_familiaSalud'] == 'disfuncionales') {echo 'checked';} ?>></td>
                                    <td><input type="radio" name="relacion_tios_familiaSalud" value="no aplica"<?php if ($res_formulario['relacion_tios_familiaSalud'] == 'no aplica') {echo 'checked';} ?>></td>
                                </tr>
                                <tr>
                                    <td>Otros familiares</td>
                                    <td><input type="radio" name="relacion_otros_familiaSalud" value="satisfactorias"<?php if ($res_formulario['relacion_otros_familiaSalud'] == 'satisfactorias') {echo 'checked';} ?>></td>
                                    <td><input type="radio" name="relacion_otros_familiaSalud" value="llevaderas"<?php if ($res_formulario['relacion_otros_familiaSalud'] == 'llevaderas') {echo 'checked';} ?>></td>
                                    <td><input type="radio" name="relacion_otros_familiaSalud" value="tensionadas"<?php if ($res_formulario['relacion_otros_familiaSalud'] == 'tensionadas') {echo 'checked';} ?>></td>
                                    <td><input type="radio" name="relacion_otros_familiaSalud" value="hostiles"<?php if ($res_formulario['relacion_otros_familiaSalud'] == 'hostiles') {echo 'checked';} ?>></td>
                                    <td><input type="radio" name="relacion_otros_familiaSalud" value="conflictivas"<?php if ($res_formulario['relacion_otros_familiaSalud'] == 'conflictivas') {echo 'checked';} ?>></td>
                                    <td><input type="radio" name="relacion_otros_familiaSalud" value="incomunicadas"<?php if ($res_formulario['relacion_otros_familiaSalud'] == 'incomunicadas') {echo 'checked';} ?>></td>
                                    <td><input type="radio" name="relacion_otros_familiaSalud" value="disfuncionales"<?php if ($res_formulario['relacion_otros_familiaSalud'] == 'disfuncionales') {echo 'checked';} ?>></td>
                                    <td><input type="radio" name="relacion_otros_familiaSalud" value="no aplica"<?php if ($res_formulario['relacion_otros_familiaSalud'] == 'no aplica') {echo 'checked';} ?>></td>
                                </tr>
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
                            <option value="" <?php if($res_formulario['discapacidad_est_familiaSalud'] == '') echo 'selected';  ?>></option>
                            <option value="0" <?php if($res_formulario['discapacidad_est_familiaSalud'] == 0) echo 'selected';  ?>>SI</option>
                            <option value="1" <?php if($res_formulario['discapacidad_est_familiaSalud'] == 1) echo 'selected';  ?>>NO</option>
                        </select>
                    </div>
                    <?php
                    // Convierte el string separado por comas en un array para revisar las situaciones
                    $afecta_aprendizaje_array = explode(',', $res_formulario['afecta_aprendizaje_familiaSalud']);
                    ?>
                    <div class="col-8">
                        <label for="Afecciones">SITUACIONES QUE AFECTAN APRENDIZAJE DEL ESTUDIANTE:</label>
                        <div class="form-control" id="Afecciones" style="height: auto;">
                            <div class="row">
                                <div class="col-8 col-sm-6 col-md-4">
                                    <input type="checkbox" name="afecta_aprendizaje_familiaSalud[]" value="ambiente familiar" 
                                    <?php if (in_array('ambiente familiar', $afecta_aprendizaje_array)) { echo 'checked'; } ?>> 
                                    Ambiente familiar<br>
                                    
                                    <input type="checkbox" name="afecta_aprendizaje_familiaSalud[]" value="factor economico" 
                                    <?php if (in_array('factor economico', $afecta_aprendizaje_array)) { echo 'checked'; } ?>> 
                                    Factor economico<br>
                                    
                                    <input type="checkbox" name="afecta_aprendizaje_familiaSalud[]" value="calidad educacion" 
                                    <?php if (in_array('calidad educacion', $afecta_aprendizaje_array)) { echo 'checked'; } ?>> 
                                    Calidad Educacion<br>
                                    
                                    <input type="checkbox" name="afecta_aprendizaje_familiaSalud[]" value="salud mental" 
                                    <?php if (in_array('salud mental', $afecta_aprendizaje_array)) { echo 'checked'; } ?>> 
                                    Salud Mental<br>
                                </div>
                                <div class="col-8 col-sm-6 col-md-4">
                                    <input type="checkbox" name="afecta_aprendizaje_familiaSalud[]" value="traumas" 
                                    <?php if (in_array('traumas', $afecta_aprendizaje_array)) { echo 'checked'; } ?>> 
                                    Experiencias traumaticas<br>
                                    
                                    <input type="checkbox" name="afecta_aprendizaje_familiaSalud[]" value="nutricion" 
                                    <?php if (in_array('nutricion', $afecta_aprendizaje_array)) { echo 'checked'; } ?>> 
                                    Nutricion<br>
                                    
                                    <input type="checkbox" name="afecta_aprendizaje_familiaSalud[]" value="dificultades aprendizaje" 
                                    <?php if (in_array('dificultades aprendizaje', $afecta_aprendizaje_array)) { echo 'checked'; } ?>> 
                                    Dificultades de aprendizaje<br>
                                    
                                    <input type="checkbox" name="afecta_aprendizaje_familiaSalud[]" value="habilidades sociales" 
                                    <?php if (in_array('habilidades sociales', $afecta_aprendizaje_array)) { echo 'checked'; } ?>> 
                                    Habilidades Sociales<br>
                                </div>
                                <div class="col-8 col-sm-6 col-md-4">
                                    <input type="checkbox" name="afecta_aprendizaje_familiaSalud[]" value="acoso" 
                                    <?php if (in_array('acoso', $afecta_aprendizaje_array)) { echo 'checked'; } ?>> 
                                    Discriminacion o acoso<br>
                                    
                                    <input type="checkbox" name="afecta_aprendizaje_familiaSalud[]" value="lenguaje" 
                                    <?php if (in_array('lenguaje', $afecta_aprendizaje_array)) { echo 'checked'; } ?>> 
                                    Barreras de lenguaje<br>
                                    
                                    <input type="checkbox" name="afecta_aprendizaje_familiaSalud[]" value="motivacion" 
                                    <?php if (in_array('motivacion', $afecta_aprendizaje_array)) { echo 'checked'; } ?>> 
                                    Falta motivacion<br>
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
                            <option value="" <?php if($res_formulario['beneficiario_pae_familiaSalud'] == '') echo 'selected';  ?> ></option>
                            <option value=1  <?php if($res_formulario['beneficiario_pae_familiaSalud'] == 1) echo 'selected';  ?> >SI</option>
                            <option value=0  <?php if($res_formulario['beneficiario_pae_familiaSalud'] == 0) echo 'selected';  ?> >NO</option>
                        </select>
                    </div>

                    <div class="col-6 col-sm-4">
                        <label for="comida_dia_familiaSalud">* CUANTOS MOMENTOS DE COMIDA TIENE AL DIA:</label>
                        <select class="form-control" name="comida_dia_familiaSalud" id="comida_dia_familiaSalud">
                            <option value=""  <?php if($res_formulario['comida_dia_familiaSalud'] == '') echo 'selected';  ?> ></option>
                            <option value="1" <?php if($res_formulario['comida_dia_familiaSalud'] == 1) echo 'selected';  ?>>1</option>
                            <option value="2" <?php if($res_formulario['comida_dia_familiaSalud'] == 2) echo 'selected';  ?>>2</option>
                            <option value="3" <?php if($res_formulario['comida_dia_familiaSalud'] == 3) echo 'selected';  ?>>3</option>
                            <option value="4" <?php if($res_formulario['comida_dia_familiaSalud'] == 4) echo 'selected';  ?>>4</option>
                            <option value="5" <?php if($res_formulario['comida_dia_familiaSalud'] == 5) echo 'selected';  ?>>5</option>
                            <option value="6" <?php if($res_formulario['comida_dia_familiaSalud'] == 6) echo 'selected';  ?>>6</option>
                        </select>
                    </div>
                    <div class="col-6 col-sm-4 mt-4  ">
                        <label for="eps_estudiante_familiaSalud">* SE ENCUENTRA AFILIADO A EPS:</label>
                        <select class="form-control" name="eps_estudiante_familiaSalud" id="eps_estudiante_familiaSalud">
                            <option value="" <?php if($res_formulario['eps_estudiante_familiaSalud'] == '') echo 'selected';  ?>></option>
                            <option value="1" <?php if($res_formulario['eps_estudiante_familiaSalud'] == 1) echo 'selected';  ?>>SI</option>
                            <option value="2" <?php if($res_formulario['eps_estudiante_familiaSalud'] == 2) echo 'selected';  ?>>NO</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group" id="eps-questions" style="display: none;">
                <div class="row">
                    <div class="col-6 col-sm-3">
                        <label for="nombre_eps_familiaSalud">* NOMBRE DE LA EPS AFILIADA: </label>
                        <select class="form-control" name="nombre_eps_familiaSalud" id="nombre_eps_familiaSalud">
                            <option value="" <?php if($res_formulario['nombre_eps_familiaSalud'] == '') echo 'selected';  ?> ></option>
                            <option value="Nueva eps"  <?php if($res_formulario['nombre_eps_familiaSalud'] == 'Nueva eps') echo 'selected';  ?>>Nueva EPS</option>
                            <option value="Salud Total"  <?php if($res_formulario['nombre_eps_familiaSalud'] == 'Salud Total') echo 'selected';  ?>>Salud Total</option>
                            <option value="Coosalud"  <?php if($res_formulario['nombre_eps_familiaSalud'] == 'Coosalud') echo 'selected';  ?>>Coosalud</option>
                            <option value="Sura"  <?php if($res_formulario['nombre_eps_familiaSalud'] == 'Sura') echo 'selected';  ?>>Sura</option>
                            <option value="Sanitas"  <?php if($res_formulario['nombre_eps_familiaSalud'] == 'Sanitas') echo 'selected';  ?>>Sanitas</option>
                            <option value="Wayu"  <?php if($res_formulario['nombre_eps_familiaSalud'] == 'Wayu') echo 'selected';  ?>>Wayu</option>
                            <option value="Aliansalud"  <?php if($res_formulario['nombre_eps_familiaSalud'] == 'Aliansalud') echo 'selected';  ?>>Aliansalud</option>
                            <option value="Compensar"  <?php if($res_formulario['nombre_eps_familiaSalud'] == 'Compensar') echo 'selected';  ?>>Compensar</option>
                            <option value="Salud Bolívar"  <?php if($res_formulario['nombre_eps_familiaSalud'] == 'Salud Bolívar') echo 'selected';  ?>>Salud Bolívar</option>
                            <option value="Cafesalud"  <?php if($res_formulario['nombre_eps_familiaSalud'] == 'Cafesalud') echo 'selected';  ?>>Cafesalud</option>
                            <option value="Cruz Blanca"  <?php if($res_formulario['nombre_eps_familiaSalud'] == 'Cruz Blanca') echo 'selected';  ?>>Cruz Blanca</option>
                            <option value="Famisanar"  <?php if($res_formulario['nombre_eps_familiaSalud'] == 'Famisanar') echo 'selected';  ?>>Famisanar</option>
                            <option value="Medimás"  <?php if($res_formulario['nombre_eps_familiaSalud'] == 'Medimás') echo 'selected';  ?>>Medimás</option>
                            <option value="Mutual Ser"  <?php if($res_formulario['nombre_eps_familiaSalud'] == 'Mutual Ser') echo 'selected';  ?>>Mutual Ser</option>
                            <option value="SOS"  <?php if($res_formulario['nombre_eps_familiaSalud'] == 'SOS') echo 'selected';  ?>>SOS</option>
                        </select>
                    </div>

                    <div class="col-6 col-sm-4">
                        <label for="afiliado_eps_familiaSalud">SISTEMA DE SALUD AL CUAL ESTÁ AFILIADO:</label>
                        <select class="form-control" name="afiliado_eps_familiaSalud" id="afiliado_eps_familiaSalud">
                            <option value=""  <?php if($res_formulario['afiliado_eps_familiaSalud'] == '') echo 'selected';  ?>></option>
                            <option value="Contributivo" <?php if($res_formulario['afiliado_eps_familiaSalud'] == 'Contributivo') echo 'selected';  ?>>Contributivo</option>
                            <option value="Subsidiado" <?php if($res_formulario['afiliado_eps_familiaSalud'] == 'Subsidiado') echo 'selected';  ?>>Subsidiado</option>
                            <option value="Especial" <?php if($res_formulario['afiliado_eps_familiaSalud'] == 'Especial') echo 'selected';  ?>>Especial</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-6 col-sm-3">
                        <label for="presenta_diagnostico_familiaSalud">* EL ESTUDIANTE TIENE ALGUN DIAGNOSTICO MEDICO: :</label>
                        <select class="form-control" name="presenta_diagnostico_familiaSalud" id="presenta_diagnostico_familiaSalud">
                            <option value="" <?php if($res_formulario['presenta_diagnostico_familiaSalud'] == '') echo 'selected';  ?>></option>
                            <option value="1" <?php if($res_formulario['presenta_diagnostico_familiaSalud'] == 1) echo 'selected';  ?>>SI</option>
                            <option value="2" <?php if($res_formulario['presenta_diagnostico_familiaSalud'] == 2) echo 'selected';  ?>>NO</option>
                        </select>
                    </div>
                    <div class="col-6 col-sm-4 mt-4">
                        <label for="diagnostico_familiaSalud">* CUAL ES EL DIAGNOSTICO MEDICO:</label>
                        <input class="form-control" type="text" name="diagnostico_familiaSalud" value="<?php echo htmlspecialchars($res_formulario['diagnostico_familiaSalud']); ?>">
                    </div>
                    <div class="col-6 col-sm-4 mt-4">
                        <label for="terapia_familiaSalud">* EL ESTUDIANTE ASISTE A TERAPIA:</label>
                        <select class="form-control" name="terapia_familiaSalud" id="terapia_familiaSalud">
                            <option value="" <?php if($res_formulario['terapia_familiaSalud'] == '') echo 'selected';  ?>></option>
                            <option value="1" <?php if($res_formulario['terapia_familiaSalud'] == 1) echo 'selected';  ?>>SI</option>
                            <option value="2" <?php if($res_formulario['terapia_familiaSalud'] == 2) echo 'selected';  ?>>NO</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-6 col-sm-3 mt-4">
                            <label for="frecuencia_terapia_familiaSalud">* CON QUE FRECUENCIA ASISTE A TERAPIA:</label>
                            <select class="form-control" name="frecuencia_terapia_familiaSalud" id="frecuencia_terapia_familiaSalud">
                                <option value="" <?php if($res_formulario['frecuencia_terapia_familiaSalud'] == '') echo 'selected';  ?>></option>
                                <option value="Semanal" <?php if($res_formulario['frecuencia_terapia_familiaSalud'] == 'Semanal') echo 'selected';  ?>>Semanal</option>
                                <option value="Quincenal" <?php if($res_formulario['frecuencia_terapia_familiaSalud'] == 'Quincenal') echo 'selected';  ?>>Quincenal</option>
                                <option value="Mensual" <?php if($res_formulario['frecuencia_terapia_familiaSalud'] == 'Mensual') echo 'selected';  ?>>Mensual</option>
                                <option value="Bimestral" <?php if($res_formulario['frecuencia_terapia_familiaSalud'] == 'Bimestral') echo 'selected';  ?>>Bimestral</option>
                                <option value="Trimestral" <?php if($res_formulario['frecuencia_terapia_familiaSalud'] == 'Trimestral') echo 'selected';  ?>>Trimestral</option>
                                <option value="Semestral" <?php if($res_formulario['frecuencia_terapia_familiaSalud'] == 'Semestral') echo 'selected';  ?>>Semestral</option>
                                <option value="Anual" <?php if($res_formulario['frecuencia_terapia_familiaSalud'] == 'Anual') echo 'selected';  ?>>Anual</option>
                            </select>
                        </div>

                        <div class="col-6 col-sm-4 mt-4">
                            <label for="condicion_particular_familiaSalud">* ACTUALMENTE ESTA SIENDO ATENDIDO POR EL SECTOR SALUD POR ALGUNA CONDICION:</label>
                            <select class="form-control" name="condicion_particular_familiaSalud" id="condicion_particular_familiaSalud">
                                <option value="" <?php if($res_formulario['condicion_particular_familiaSalud'] == '') echo 'selected';  ?>></option>
                                <option value="1" <?php if($res_formulario['condicion_particular_familiaSalud'] == 1) echo 'selected';  ?>>SI</option>
                                <option value="2" <?php if($res_formulario['condicion_particular_familiaSalud'] == 2) echo 'selected';  ?>>NO</option>
                            </select>
                        </div>
                        <div class="col-6 col-sm-4 mt-4">
                            <label for="frecuencia_atencion_familiaSalud">* CON QUE FRECUENCIA ES ATENDIDO POR EL SECTOR SALUD:</label>
                            <select class="form-control" name="frecuencia_atencion_familiaSalud" id="frecuencia_atencion_familiaSalud">
                                <option value="" <?php if($res_formulario['frecuencia_atencion_familiaSalud'] == '') echo 'selected';  ?>></option>
                                <option value="Semanal" <?php if($res_formulario['frecuencia_atencion_familiaSalud'] == 'Semanal') echo 'selected';  ?>>Semanal</option>
                                <option value="Quincenal" <?php if($res_formulario['frecuencia_atencion_familiaSalud'] == 'Quincenal') echo 'selected';  ?>>Quincenal</option>
                                <option value="Mensual" <?php if($res_formulario['frecuencia_atencion_familiaSalud'] == 'Mensual') echo 'selected';  ?>>Mensual</option>
                                <option value="Bimestral" <?php if($res_formulario['frecuencia_atencion_familiaSalud'] == 'Bimestral') echo 'selected';  ?>>Bimestral</option>
                                <option value="Trimestral" <?php if($res_formulario['frecuencia_atencion_familiaSalud'] == 'Trimestral') echo 'selected';  ?>>Trimestral</option>
                                <option value="Semestral" <?php if($res_formulario['frecuencia_atencion_familiaSalud'] == 'Semestral') echo 'selected';  ?>>Semestral</option>
                                <option value="Anual" <?php if($res_formulario['frecuencia_atencion_familiaSalud'] == 'Anual') echo 'selected';  ?>>Anual</option>                       
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-6 col-sm-4 ">
                            <label for="alergia_familiaSalud">* PRESENTA ALGUN TIPO DE ALERGIA (alimentos, medicamentos, entorno):</label>
                            <select class="form-control" name="alergia_familiaSalud" id="alergia_familiaSalud">
                                <option value="" <?php if($res_formulario['alergia_familiaSalud'] == '') echo 'selected';  ?>></option>
                                <option value="1" <?php if($res_formulario['alergia_familiaSalud'] == 1) echo 'selected';  ?>>SI</option>
                                <option value="2" <?php if($res_formulario['alergia_familiaSalud'] == 2) echo 'selected';  ?>>NO</option>
                            </select>
                        </div>
                        <div class="col-6 col-sm-4 ">
                            <label for="tipo_alergia_familiaSalud">* EN CASO DE TENER ALGUN TIPO ALERGIA, MENCIONELA:</label>
                            <input class="form-control" type="text" name="tipo_alergia_familiaSalud" value="<?php echo htmlspecialchars($res_formulario['tipo_alergia_familiaSalud']); ?>">
                        </div>
                        <div class="col-6 col-sm-4 ">
                            <label for="vacunacion_familiaSalud">* CUENTA CON EL ESQUEMA DE VACUNACION COMPLETO:</label>
                            <select class="form-control" name="vacunacion_familiaSalud" id="vacunacion_familiaSalud">
                                <option value="" <?php if($res_formulario['vacunacion_familiaSalud'] == '') echo 'selected';  ?>></option>
                                <option value="1" <?php if($res_formulario['vacunacion_familiaSalud'] == 1) echo 'selected';  ?>>SI</option>
                                <option value="2" <?php if($res_formulario['vacunacion_familiaSalud'] == 2) echo 'selected';  ?>>NO</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-6 col-sm-4 mt-4">
                                <label for="sangre_familiaSalud">* TIPO Y FACTOR SANGUINEO:</label>
                                <select class="form-control" name="sangre_familiaSalud" id="sangre_familiaSalud">
                                    <option value="" <?php if($res_formulario['sangre_familiaSalud'] == '') echo 'selected';  ?>></option>
                                    <option value="O+" <?php if($res_formulario['sangre_familiaSalud'] == 'O+') echo 'selected';  ?>>O+</option>
                                    <option value="O-" <?php if($res_formulario['sangre_familiaSalud'] == 'O-') echo 'selected';  ?>>O-</option>
                                    <option value="A+" <?php if($res_formulario['sangre_familiaSalud'] == 'A+') echo 'selected';  ?>>A+</option>
                                    <option value="A-" <?php if($res_formulario['sangre_familiaSalud'] == 'A-') echo 'selected';  ?>>A-</option>
                                    <option value="B+" <?php if($res_formulario['sangre_familiaSalud'] == 'B+') echo 'selected';  ?>>B+</option>
                                    <option value="B-" <?php if($res_formulario['sangre_familiaSalud'] == 'B-') echo 'selected';  ?>>B-</option>
                                    <option value="AB+" <?php if($res_formulario['sangre_familiaSalud'] == 'AB+') echo 'selected';  ?>>AB+</option>
                                    <option value="AB-" <?php if($res_formulario['sangre_familiaSalud'] == 'AB-') echo 'selected';  ?>>AB-</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                    <input type="hidden" name="id_registro" value="<?= $id_registro ?>">
                </div>


                    <button type="submit" class="btn btn-primary" name="btn-update">
                        <span class="spinner-border spinner-border-sm"></span>
                        ACTUALIZAR INFORMACIÓN FAMILIA Y SALUD
                    </button>
                    <button type="reset" class="btn btn-outline-dark" role='link' onclick="history.back();">
                        <img src='../../img/atras.png' width=27 height=27> REGRESAR
                    </button>
                </div>
            </div>

        
        </form>
    </div>

           
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