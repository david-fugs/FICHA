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

        <form action='addprePostnatales1.php' method="POST">

            <hr style="border: 2px solid #16087B; border-radius: 2px;">
            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-sm-2">
                        <label for="fecha_dig_prePostnatales">FECHA DILIGENC.</label>
                        <input type='text' name='fecha_dig_prePostnatales' id="fecha_dig_prePostnatales" class='form-control' value='<?php echo date("d-m-Y h:i", $time); ?>' readonly />
                    </div>
                    <div class="col-12 col-sm-3">
                        <label for="mun_dig_prePostnatales">* MUNICIPIO DILIGENCIAMIENTO:</label>
                        <select name='mun_dig_prePostnatales' class='form-control' id='selectMunicipio' required>
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
                        <label for="nombre_encuestador_prePostnatales">NOMBRE DEL ENCUESTADOR:</label>
                        <input type='text' name='nombre_encuestador_prePostnatales' class='form-control' id="nombre_encuestador_prePostnatales" value='<?php echo $nombre; ?>' readonly />
                    </div>
                    <div class="col-12 col-sm-3">
                        <label for="rol_encuestador_prePostnatales">TIPO DE ACCESO:</label>
                        <select class="form-control" name="rol_encuestador_prePostnatales" readonly />
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
                    <div class="col-12 col-sm-3">
                        <label for="edad_madre_prePostnatales">* EDAD DE LA MADRE:</label>
                        <select class="form-control" name="edad_madre_prePostnatales" required>
                            <option value=""></option>
                            <option value=10 <?php if ($row['edad_madre_prePostnatales'] == '10') {
                                                    echo 'selected';
                                                } ?>>10</option>
                            <option value=11 <?php if ($row['edad_madre_prePostnatales'] == '11') {
                                                    echo 'selected';
                                                } ?>>11</option>
                            <option value=12 <?php if ($row['edad_madre_prePostnatales'] == '12') {
                                                    echo 'selected';
                                                } ?>>12</option>
                            <option value=13 <?php if ($row['edad_madre_prePostnatales'] == '13') {
                                                    echo 'selected';
                                                } ?>>13</option>
                            <option value=14 <?php if ($row['edad_madre_prePostnatales'] == '14') {
                                                    echo 'selected';
                                                } ?>>14</option>
                            <option value=15 <?php if ($row['edad_madre_prePostnatales'] == '15') {
                                                    echo 'selected';
                                                } ?>>15</option>
                            <option value=16 <?php if ($row['edad_madre_prePostnatales'] == '16') {
                                                    echo 'selected';
                                                } ?>>16</option>
                            <option value=17 <?php if ($row['edad_madre_prePostnatales'] == '17') {
                                                    echo 'selected';
                                                } ?>>17</option>
                            <option value=18 <?php if ($row['edad_madre_prePostnatales'] == '18') {
                                                    echo 'selected';
                                                } ?>>18</option>
                            <option value=19 <?php if ($row['edad_madre_prePostnatales'] == '19') {
                                                    echo 'selected';
                                                } ?>>19</option>
                            <option value=20 <?php if ($row['edad_madre_prePostnatales'] == '20') {
                                                    echo 'selected';
                                                } ?>>20</option>
                            <option value=21 <?php if ($row['edad_madre_prePostnatales'] == '21') {
                                                    echo 'selected';
                                                } ?>>21</option>
                            <option value=22 <?php if ($row['edad_madre_prePostnatales'] == '22') {
                                                    echo 'selected';
                                                } ?>>22</option>
                            <option value=23 <?php if ($row['edad_madre_prePostnatales'] == '23') {
                                                    echo 'selected';
                                                } ?>>23</option>
                            <option value=24 <?php if ($row['edad_madre_prePostnatales'] == '24') {
                                                    echo 'selected';
                                                } ?>>24</option>
                            <option value=25 <?php if ($row['edad_madre_prePostnatales'] == '25') {
                                                    echo 'selected';
                                                } ?>>25</option>
                            <option value=26 <?php if ($row['edad_madre_prePostnatales'] == '26') {
                                                    echo 'selected';
                                                } ?>>26</option>
                            <option value=27 <?php if ($row['edad_madre_prePostnatales'] == '27') {
                                                    echo 'selected';
                                                } ?>>27</option>
                            <option value=28 <?php if ($row['edad_madre_prePostnatales'] == '28') {
                                                    echo 'selected';
                                                } ?>>28</option>
                            <option value=29 <?php if ($row['edad_madre_prePostnatales'] == '29') {
                                                    echo 'selected';
                                                } ?>>29</option>
                            <option value=30 <?php if ($row['edad_madre_prePostnatales'] == '30') {
                                                    echo 'selected';
                                                } ?>>30</option>
                            <option value=31 <?php if ($row['edad_madre_prePostnatales'] == '31') {
                                                    echo 'selected';
                                                } ?>>31</option>
                            <option value=32 <?php if ($row['edad_madre_prePostnatales'] == '32') {
                                                    echo 'selected';
                                                } ?>>32</option>
                            <option value=33 <?php if ($row['edad_madre_prePostnatales'] == '33') {
                                                    echo 'selected';
                                                } ?>>33</option>
                            <option value=34 <?php if ($row['edad_madre_prePostnatales'] == '34') {
                                                    echo 'selected';
                                                } ?>>34</option>
                            <option value=35 <?php if ($row['edad_madre_prePostnatales'] == '35') {
                                                    echo 'selected';
                                                } ?>>35</option>
                            <option value=36 <?php if ($row['edad_madre_prePostnatales'] == '36') {
                                                    echo 'selected';
                                                } ?>>36</option>
                            <option value=37 <?php if ($row['edad_madre_prePostnatales'] == '37') {
                                                    echo 'selected';
                                                } ?>>37</option>
                            <option value=38 <?php if ($row['edad_madre_prePostnatales'] == '38') {
                                                    echo 'selected';
                                                } ?>>38</option>
                            <option value=39 <?php if ($row['edad_madre_prePostnatales'] == '39') {
                                                    echo 'selected';
                                                } ?>>39</option>
                            <option value=40 <?php if ($row['edad_madre_prePostnatales'] == '40') {
                                                    echo 'selected';
                                                } ?>>40</option>
                            <option value=41 <?php if ($row['edad_madre_prePostnatales'] == '41') {
                                                    echo 'selected';
                                                } ?>>MÁS DE 40</option>
                        </select>
                        <label for="edad_madre_prePostnatales">(en el momento del embarazo)</label>
                    </div>
                    <div class="col-12 col-sm-3">
                        <label for="gestacion_meses_prePostnatales">* GESTACIÓN EN MESES:</label>
                        <select class="form-control" name="gestacion_meses_prePostnatales" required>
                            <option value=""></option>
                            <option value=5 <?php if ($row['gestacion_meses_prePostnatales'] == '5') {
                                                echo 'selected';
                                            } ?>>5 MESES</option>
                            <option value=6 <?php if ($row['gestacion_meses_prePostnatales'] == '6') {
                                                echo 'selected';
                                            } ?>>6 MESES</option>
                            <option value=7 <?php if ($row['gestacion_meses_prePostnatales'] == '7') {
                                                echo 'selected';
                                            } ?>>7 MESES</option>
                            <option value=8 <?php if ($row['gestacion_meses_prePostnatales'] == '8') {
                                                echo 'selected';
                                            } ?>>8 MESES</option>
                            <option value=9 <?php if ($row['gestacion_meses_prePostnatales'] == '9') {
                                                echo 'selected';
                                            } ?>>9 MESES</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-4">
                        <label for="embarazo_mama_prePostnatales">* ¿EL EMBARAZO PRESENTÓ?</label>
                        <select class="form-control" name="embarazo_mama_prePostnatales" required>
                            <option value=""></option>
                            <option value="PARTO A TIEMPO (NATURAL)" <?php if ($row['embarazo_mama_prePostnatales'] == 'PARTO A TIEMPO (NATURAL)') {
                                                                            echo 'selected';
                                                                        } ?>>PARTO A TIEMPO (NATURAL)</option>
                            <option value="PARTO ASISTIDO (CESÁREA)" <?php if ($row['embarazo_mama_prePostnatales'] == 'PARTO ASISTIDO (CESÁREA)') {
                                                                            echo 'selected';
                                                                        } ?>>PARTO ASISTIDO (CESÁREA)</option>
                            <option value="SIN ANTECEDENTE" <?php if ($row['embarazo_mama_prePostnatales'] == 'SIN ANTECEDENTE') {
                                                                echo 'selected';
                                                            } ?>>SIN ANTECEDENTE</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-2">
                        <label for="lactancia_mama_prePostnatales">* LACTANCIA:</label>
                        <select class="form-control" name="lactancia_mama_prePostnatales" required>
                            <option value=""></option>
                            <option value=0 <?php if ($row['lactancia_mama_prePostnatales'] == '0') {
                                                echo 'selected';
                                            } ?>>0</option>
                            <option value=1 <?php if ($row['lactancia_mama_prePostnatales'] == '1') {
                                                echo 'selected';
                                            } ?>>1</option>
                            <option value=2 <?php if ($row['lactancia_mama_prePostnatales'] == '2') {
                                                echo 'selected';
                                            } ?>>2</option>
                            <option value=3 <?php if ($row['lactancia_mama_prePostnatales'] == '3') {
                                                echo 'selected';
                                            } ?>>3</option>
                            <option value=4 <?php if ($row['lactancia_mama_prePostnatales'] == '4') {
                                                echo 'selected';
                                            } ?>>4</option>
                            <option value=5 <?php if ($row['lactancia_mama_prePostnatales'] == '5') {
                                                echo 'selected';
                                            } ?>>5</option>
                            <option value=6 <?php if ($row['lactancia_mama_prePostnatales'] == '6') {
                                                echo 'selected';
                                            } ?>>6</option>
                            <option value=7 <?php if ($row['lactancia_mama_prePostnatales'] == '7') {
                                                echo 'selected';
                                            } ?>>7</option>
                            <option value=8 <?php if ($row['lactancia_mama_prePostnatales'] == '8') {
                                                echo 'selected';
                                            } ?>>8</option>
                            <option value=9 <?php if ($row['lactancia_mama_prePostnatales'] == '9') {
                                                echo 'selected';
                                            } ?>>9</option>
                            <option value=10 <?php if ($row['lactancia_mama_prePostnatales'] == '10') {
                                                    echo 'selected';
                                                } ?>>10</option>
                            <option value=11 <?php if ($row['lactancia_mama_prePostnatales'] == '11') {
                                                    echo 'selected';
                                                } ?>>11</option>
                            <option value=12 <?php if ($row['lactancia_mama_prePostnatales'] == '12') {
                                                    echo 'selected';
                                                } ?>>12</option>
                            <option value=13 <?php if ($row['lactancia_mama_prePostnatales'] == '13') {
                                                    echo 'selected';
                                                } ?>>13</option>
                            <option value=14 <?php if ($row['lactancia_mama_prePostnatales'] == '14') {
                                                    echo 'selected';
                                                } ?>>14</option>
                            <option value=15 <?php if ($row['lactancia_mama_prePostnatales'] == '15') {
                                                    echo 'selected';
                                                } ?>>15</option>
                            <option value=16 <?php if ($row['lactancia_mama_prePostnatales'] == '16') {
                                                    echo 'selected';
                                                } ?>>16</option>
                            <option value=17 <?php if ($row['lactancia_mama_prePostnatales'] == '17') {
                                                    echo 'selected';
                                                } ?>>17</option>
                            <option value=18 <?php if ($row['lactancia_mama_prePostnatales'] == '18') {
                                                    echo 'selected';
                                                } ?>>18</option>
                            <option value=19 <?php if ($row['lactancia_mama_prePostnatales'] == '19') {
                                                    echo 'selected';
                                                } ?>>19</option>
                            <option value=20 <?php if ($row['lactancia_mama_prePostnatales'] == '20') {
                                                    echo 'selected';
                                                } ?>>20</option>
                            <option value=21 <?php if ($row['lactancia_mama_prePostnatales'] == '21') {
                                                    echo 'selected';
                                                } ?>>21</option>
                            <option value=22 <?php if ($row['lactancia_mama_prePostnatales'] == '22') {
                                                    echo 'selected';
                                                } ?>>22</option>
                            <option value=23 <?php if ($row['lactancia_mama_prePostnatales'] == '23') {
                                                    echo 'selected';
                                                } ?>>23</option>
                            <option value=24 <?php if ($row['lactancia_mama_prePostnatales'] == '24') {
                                                    echo 'selected';
                                                } ?>>24</option>
                            <option value=25 <?php if ($row['lactancia_mama_prePostnatales'] == '25') {
                                                    echo 'selected';
                                                } ?>>MÁS DE 24</option>
                        </select>
                        <label for="lactancia_mama_prePostnatales">(tiempo en meses)</label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-sm-3">
                        <label for="gateo_prePostnatales">* ¿EL ESTUDIANTE GATEÓ?</label>
                        <select class="form-control" name="gateo_prePostnatales" required>
                            <option value=""></option>
                            <option value="SI" <?php if ($row['gateo_prePostnatales'] == 'SI') {
                                                    echo 'selected';
                                                } ?>>SI</option>
                            <option value="NO" <?php if ($row['gateo_prePostnatales'] == 'NO') {
                                                    echo 'selected';
                                                } ?>>NO</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-4">
                        <label for="camino_prePostnatales">* ¿EL ESTUDIANTE CAMINÓ (tiempo meses)?</label>
                        <select class="form-control" name="camino_prePostnatales" required>
                            <option value=""></option>
                            <option value=8 <?php if ($row['camino_prePostnatales'] == '8') {
                                                echo 'selected';
                                            } ?>>8</option>
                            <option value=9 <?php if ($row['camino_prePostnatales'] == '9') {
                                                echo 'selected';
                                            } ?>>9</option>
                            <option value=10 <?php if ($row['camino_prePostnatales'] == '10') {
                                                    echo 'selected';
                                                } ?>>10</option>
                            <option value=11 <?php if ($row['camino_prePostnatales'] == '11') {
                                                    echo 'selected';
                                                } ?>>11</option>
                            <option value=12 <?php if ($row['camino_prePostnatales'] == '12') {
                                                    echo 'selected';
                                                } ?>>12</option>
                            <option value=13 <?php if ($row['camino_prePostnatales'] == '13') {
                                                    echo 'selected';
                                                } ?>>13</option>
                            <option value=14 <?php if ($row['camino_prePostnatales'] == '14') {
                                                    echo 'selected';
                                                } ?>>14</option>
                            <option value=15 <?php if ($row['camino_prePostnatales'] == '15') {
                                                    echo 'selected';
                                                } ?>>15</option>
                            <option value=16 <?php if ($row['camino_prePostnatales'] == '16') {
                                                    echo 'selected';
                                                } ?>>16</option>
                            <option value=17 <?php if ($row['camino_prePostnatales'] == '17') {
                                                    echo 'selected';
                                                } ?>>17</option>
                            <option value=18 <?php if ($row['camino_prePostnatales'] == '18') {
                                                    echo 'selected';
                                                } ?>>18</option>
                            <option value=19 <?php if ($row['camino_prePostnatales'] == '19') {
                                                    echo 'selected';
                                                } ?>>19</option>
                            <option value=20 <?php if ($row['camino_prePostnatales'] == '20') {
                                                    echo 'selected';
                                                } ?>>20</option>
                            <option value=21 <?php if ($row['camino_prePostnatales'] == '21') {
                                                    echo 'selected';
                                                } ?>>21</option>
                            <option value=22 <?php if ($row['camino_prePostnatales'] == '22') {
                                                    echo 'selected';
                                                } ?>>22</option>
                            <option value=23 <?php if ($row['camino_prePostnatales'] == '23') {
                                                    echo 'selected';
                                                } ?>>23</option>
                            <option value=24 <?php if ($row['camino_prePostnatales'] == '24') {
                                                    echo 'selected';
                                                } ?>>24</option>
                            <option value=25 <?php if ($row['camino_prePostnatales'] == '25') {
                                                    echo 'selected';
                                                } ?>>MÁS DE 24</option>
                            <option value=0 <?php if ($row['camino_prePostnatales'] == '0') {
                                                echo 'selected';
                                            } ?>>NO APLICA</option>
                        </select>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" name="btn-update">
                <span class="spinner-border spinner-border-sm"></span>
                ACTUALIZAR INFORMACIÓN PRE POSTNATAL
            </button>
            <button type="reset" class="btn btn-outline-dark" role='link' onclick="history.back();" type='reset'><img src='../../img/atras.png' width=27 height=27> REGRESAR
            </button>
        </form>
    </div>
</body>

</html>