<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../../index.php");
    exit();
}

header("Content-Type: text/html; charset=utf-8");
$usuario = $_SESSION['usuario'];
$nombre = $_SESSION['nombre'];
$tipo_usuario = $_SESSION['tipo_usuario'];
$cod_dane_ie = $_SESSION['cod_dane_ie'];
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/fed2435e21.js" crossorigin="anonymous"></script>
    <style>
        .responsive {
            max-width: 100%;
            height: auto;
        }
    </style>
    <script>
        $(document).ready(function() {
            function toggleFieldsBasedOnInput(inputSelector, fieldSelectors) {
                $(inputSelector).on('input', function() {
                    if ($(this).val().trim() !== '') {
                        fieldSelectors.forEach(function(selector) {
                            $(selector).prop('disabled', false).filter('option[value="N/A"]').remove();
                        });
                    } else {
                        fieldSelectors.forEach(function(selector) {
                            $(selector).val('').prop('disabled', true);
                        });
                    }
                }).trigger('input');
            }

            function toggleFieldBasedOnSelect(selectSelector, value, fieldSelector) {
                $(selectSelector).on('change', function() {
                    if ($(this).val() === value) {
                        $(fieldSelector).prop('disabled', false);
                    } else {
                        $(fieldSelector).val('').prop('disabled', true);
                    }
                }).trigger('change');
            }

            function toggleFieldBasedOnCheckbox(checkboxSelector, value, fieldSelector) {
                $(checkboxSelector).on('change', function() {
                    if ($(this).is(':checked') && $(this).val() === value) {
                        $(fieldSelector).prop('disabled', false);
                    } else {
                        $(fieldSelector).val('').prop('disabled', true);
                    }
                }).trigger('change');
            }

            toggleFieldsBasedOnInput('#nombre_madre_hog', ['#vive_madre_hog', '#ocupacion_madre_hog', '#educacion_madre_hog']);
            toggleFieldsBasedOnInput('#nombre_padre_hog', ['#vive_padre_hog', '#ocupacion_padre_hog', '#educacion_padre_hog']);
            toggleFieldBasedOnSelect('#vive_estu_hog', 'Otro', '#nom_vive_estu_hog');
            toggleFieldBasedOnSelect('#parentesco_cuid_estu_hog', 'Otro', '#nom_parentesco_cuid_estu_hog');
            toggleFieldBasedOnSelect('#ocupacion_cuid_estu_hog', 'Otro', '#nom_ocupacion_cuid_estu_hog');
            toggleFieldBasedOnSelect('#crianza_estu_hog', 'Otro', '#nom_crianza_estu_hog');
            toggleFieldBasedOnSelect('#fam_subsidio_hog', '1', '#tipo_subsidio_hog');

            $('#tiene_herm_ie_estu_hog').on('change', function() {
                if ($(this).val() == '1') {
                    $('#niveles_educativos_herm_ie_estu_hog').find('input[type="checkbox"]').prop('disabled', false);
                } else {
                    $('#niveles_educativos_herm_ie_estu_hog').find('input[type="checkbox"]').prop('checked', false).prop('disabled', true);
                }
            }).trigger('change');

            toggleFieldBasedOnCheckbox('input[name="mecanismos_conflictos_estu_hog[]"]', 'Otros', '#nom_mecanismos_conflictos_estu_hog');
            toggleFieldBasedOnCheckbox('input[name="inconvenientes_quien_hog[]"]', 'Otros', '#nom_quien_inconvenientes_hog');
            toggleFieldBasedOnCheckbox('input[name="inconvenientes_como_hog[]"]', 'Otros', '#nom_como_inconvenientes_hog');
            toggleFieldBasedOnCheckbox('input[name="responsabilidades_est_hog[]"]', 'Otros', '#nom_responsabilidades_est_hog');
            toggleFieldBasedOnCheckbox('input[name="afecto_est_hog[]"]', 'Otros', '#nom_afecto_est_hog');
            toggleFieldBasedOnSelect('#tenencia_vivienda_hog', 'Otra', '#nom_tenencia_vivienda_hog');
        });
    </script>
</head>
<body>
    <?php
    include("../../conexion.php");
    mysqli_set_charset($mysqli, "utf8"); // Asegura la conexión a la base de datos en UTF-8
    date_default_timezone_set("America/Bogota");
    $time = time();
    $num_doc_est = $_GET['num_doc_est'] ?? '';

    if ($num_doc_est) {
        $sql = mysqli_query($mysqli, "SELECT * FROM estudiantes WHERE num_doc_est = '$num_doc_est'");
        $row = mysqli_fetch_array($sql, MYSQLI_ASSOC);

        $fec_nac_est = $row['fec_nac_est'] ?? '';

        // Calcula la edad
        $fecha_actual = new DateTime();
        $fec_nac_est = new DateTime($fec_nac_est);
        $edad = $fecha_actual->diff($fec_nac_est)->y;

        // Convertir la cadena de niveles educativos en un array
        $niveles_educativos_seleccionados = explode(',', $row['niveles_educativos'] ?? '');
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $fecha_dig_hog = $_POST['fecha_dig_hog'];
        $mun_dig_hog = $_POST['mun_dig_hog'];
        $nombre_encuestador_hog = $_POST['nombre_encuestador_hog'];
        $rol_encuestador_hog = $_POST['rol_encuestador_hog'];
        $num_doc_est = $_POST['num_doc_est'];
        $nombre_madre_hog = $_POST['nombre_madre_hog'];
        $vive_madre_hog = $_POST['vive_madre_hog'];
        $ocupacion_madre_hog = $_POST['ocupacion_madre_hog'];
        $educacion_madre_hog = $_POST['educacion_madre_hog'];
        $nombre_padre_hog = $_POST['nombre_padre_hog'];
        $vive_padre_hog = $_POST['vive_padre_hog'];
        $ocupacion_padre_hog = $_POST['ocupacion_padre_hog'];
        $educacion_padre_hog = $_POST['educacion_padre_hog'];
        $vive_estu_hog = $_POST['vive_estu_hog'];
        $nom_vive_estu_hog = $_POST['nom_vive_estu_hog'];
        $cuidador_estu_hog = $_POST['cuidador_estu_hog'];
        $parentesco_cuid_estu_hog = $_POST['parentesco_cuid_estu_hog'];
        $nom_parentesco_cuid_estu_hog = $_POST['nom_parentesco_cuid_estu_hog'];
        $educacion_cuid_estu_hog = $_POST['educacion_cuid_estu_hog'];
        $ocupacion_cuid_estu_hog = $_POST['ocupacion_cuid_estu_hog'];
        $nom_ocupacion_cuid_estu_hog = $_POST['nom_ocupacion_cuid_estu_hog'];
        $tel_cuid_estu_hog = $_POST['tel_cuid_estu_hog'];
        $email_cuid_estu_hog = $_POST['email_cuid_estu_hog'];
        $num_herm_estu_hog = $_POST['num_herm_estu_hog'];
        $lugar_ocupa_estu_hog = $_POST['lugar_ocupa_estu_hog'];
        $tiene_herm_ie_estu_hog = $_POST['tiene_herm_ie_estu_hog'];
        $niveles_educativos_herm_ie_estu_hog = implode(',', $_POST['niveles_educativos_herm_ie_estu_hog'] ?? []);
        $crianza_estu_hog = $_POST['crianza_estu_hog'];
        $nom_crianza_estu_hog = $_POST['nom_crianza_estu_hog'];
        $prac_comu_estu_hog = $_POST['prac_comu_estu_hog'];
        $fam_categ_estu_hog = $_POST['fam_categ_estu_hog'];
        $fam_subsidio_hog = $_POST['fam_subsidio_hog'];
        $tipo_subsidio_hog = $_POST['tipo_subsidio_hog'];
        $mecanismos_conflictos_estu_hog = implode(',', $_POST['mecanismos_conflictos_estu_hog'] ?? []);
        $nom_mecanismos_conflictos_estu_hog = $_POST['nom_mecanismos_conflictos_estu_hog'];
        $inconvenientes_quien_hog = implode(',', $_POST['inconvenientes_quien_hog'] ?? []);
        $nom_quien_inconvenientes_hog = $_POST['nom_quien_inconvenientes_hog'];
        $inconvenientes_como_hog = implode(',', $_POST['inconvenientes_como_hog'] ?? []);
        $nom_como_inconvenientes_hog = $_POST['nom_como_inconvenientes_hog'];
        $responsabilidades_est_hog = implode(',', $_POST['responsabilidades_est_hog'] ?? []);
        $nom_responsabilidades_est_hog = $_POST['nom_responsabilidades_est_hog'];
        $afecto_est_hog = implode(',', $_POST['afecto_est_hog'] ?? []);
        $nom_afecto_est_hog = $_POST['nom_afecto_est_hog'];
        $tipo_vivienda_hog = $_POST['tipo_vivienda_hog'];
        $tenencia_vivienda_hog = $_POST['tenencia_vivienda_hog'];
        $nom_tenencia_vivienda_hog = $_POST['nom_tenencia_vivienda_hog'];
        $material_vivienda_hog = $_POST['material_vivienda_hog'];
        $servicios_vivienda_hog = implode(',', $_POST['servicios_vivienda_hog'] ?? []);
        $num_personas_vivienda_hog = $_POST['num_personas_vivienda_hog'];
        $estado_hog = 1;
        $fecha_alta_hog = date('Y-m-d h:i:s');
        $id_usu_alta_hog = $_SESSION['id'];
        $fecha_edit_hog = '0000-00-00 00:00:00';
        $id_usu = $_SESSION['id'];

        $sql_update = "INSERT INTO entornohogar (
                            fecha_dig_hog,
                            mun_dig_hog,
                            nombre_encuestador_hog,
                            rol_encuestador_hog,
                            num_doc_est,
                            nombre_madre_hog,
                            vive_madre_hog,
                            ocupacion_madre_hog,
                            educacion_madre_hog,
                            nombre_padre_hog,
                            vive_padre_hog,
                            ocupacion_padre_hog,
                            educacion_padre_hog,
                            vive_estu_hog,
                            nom_vive_estu_hog,
                            cuidador_estu_hog,
                            parentesco_cuid_estu_hog,
                            nom_parentesco_cuid_estu_hog,
                            educacion_cuid_estu_hog,
                            ocupacion_cuid_estu_hog,
                            nom_ocupacion_cuid_estu_hog,
                            tel_cuid_estu_hog,
                            email_cuid_estu_hog,
                            num_herm_estu_hog,
                            lugar_ocupa_estu_hog,
                            tiene_herm_ie_estu_hog,
                            niveles_educativos_herm_ie_estu_hog,
                            crianza_estu_hog,
                            nom_crianza_estu_hog,
                            prac_comu_estu_hog,
                            fam_categ_estu_hog,
                            fam_subsidio_hog,
                            tipo_subsidio_hog,
                            mecanismos_conflictos_estu_hog,
                            nom_mecanismos_conflictos_estu_hog,
                            inconvenientes_quien_hog,
                            nom_quien_inconvenientes_hog,
                            inconvenientes_como_hog,
                            nom_como_inconvenientes_hog,
                            responsabilidades_est_hog,
                            nom_responsabilidades_est_hog,
                            afecto_est_hog,
                            nom_afecto_est_hog,
                            tipo_vivienda_hog,
                            tenencia_vivienda_hog,
                            nom_tenencia_vivienda_hog,
                            material_vivienda_hog,
                            servicios_vivienda_hog,
                            num_personas_vivienda_hog,
                            estado_hog,
                            fecha_alta_hog,
                            id_usu_alta_hog,
                            fecha_edit_hog,
                            id_usu
                        ) VALUES (
                            '$fecha_dig_hog',
                            '$mun_dig_hog',
                            '$nombre_encuestador_hog',
                            '$rol_encuestador_hog',
                            '$num_doc_est',
                            '$nombre_madre_hog',
                            '$vive_madre_hog',
                            '$ocupacion_madre_hog',
                            '$educacion_madre_hog',
                            '$nombre_padre_hog',
                            '$vive_padre_hog',
                            '$ocupacion_padre_hog',
                            '$educacion_padre_hog',
                            '$vive_estu_hog',
                            '$nom_vive_estu_hog',
                            '$cuidador_estu_hog',
                            '$parentesco_cuid_estu_hog',
                            '$nom_parentesco_cuid_estu_hog',
                            '$educacion_cuid_estu_hog',
                            '$ocupacion_cuid_estu_hog',
                            '$nom_ocupacion_cuid_estu_hog',
                            '$tel_cuid_estu_hog',
                            '$email_cuid_estu_hog',
                            '$num_herm_estu_hog',
                            '$lugar_ocupa_estu_hog',
                            '$tiene_herm_ie_estu_hog',
                            '$niveles_educativos_herm_ie_estu_hog',
                            '$crianza_estu_hog',
                            '$nom_crianza_estu_hog',
                            '$prac_comu_estu_hog',
                            '$fam_categ_estu_hog',
                            '$fam_subsidio_hog',
                            '$tipo_subsidio_hog',
                            '$mecanismos_conflictos_estu_hog',
                            '$nom_mecanismos_conflictos_estu_hog',
                            '$inconvenientes_quien_hog',
                            '$nom_quien_inconvenientes_hog',
                            '$inconvenientes_como_hog',
                            '$nom_como_inconvenientes_hog',
                            '$responsabilidades_est_hog',
                            '$nom_responsabilidades_est_hog',
                            '$afecto_est_hog',
                            '$nom_afecto_est_hog',
                            '$tipo_vivienda_hog',
                            '$tenencia_vivienda_hog',
                            '$nom_tenencia_vivienda_hog',
                            '$material_vivienda_hog',
                            '$servicios_vivienda_hog',
                            '$num_personas_vivienda_hog',
                            '$estado_hog',
                            '$fecha_alta_hog',
                            '$id_usu_alta_hog',
                            '$fecha_edit_hog',
                            '$id_usu'
                        )";

        if (mysqli_query($mysqli, $sql_update)) {
            header('Location: registro_satisfactorio.htm');
            exit();
        } else {
            echo "<script>alert('Error al actualizar la información: " . mysqli_error($mysqli) . "');</script>";
        }
    }
    ?>

    <div class="container">
        <center>
            <img src='../../img/logo_educacion.png' width=600 height=121 class='responsive'>
        </center>

        <h1><b><i class="fa-solid fa-house-user"></i> APLICAR ENCUESTA ENTORNO DEL HOGAR</b></h1>
        <button onclick="window.print()" class="btn btn-primary mb-3">
            Imprimir formulario
        </button>
        <p><i><b><font size=3 color=#c68615>* Datos obligatorios</i></b></font></p>

        <form action='addentornoHogar1.php' method="POST">
            <hr style="border: 2px solid #16087B; border-radius: 2px;">
            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-sm-2">
                        <label for="fecha_dig_hog">FECHA DILIGENC.</label>
                        <input type='text' name='fecha_dig_hog' id="fecha_dig_hog" class='form-control' value='<?php echo date("Y-m-d h:i", $time); ?>' readonly />
                    </div>
                    <div class="col-12 col-sm-3">
                        <label for="mun_dig_hog">* MUNICIPIO DILIGENCIAMIENTO:</label>
                        <select name='mun_dig_hog' class='form-control' id='selectMunicipio' required>
                            <option value=''></option>
                            <?php
                            $consulta = 'SELECT * FROM municipios';
                            $res = mysqli_query($mysqli, $consulta);
                            $num_reg = mysqli_num_rows($res);
                            while ($row1 = $res->fetch_array(MYSQLI_ASSOC)) {
                            ?>
                                <option value='<?php echo $row1['nombre_mun']; ?>' <?php if ($row['mun_dig_hog'] == $row1['nombre_mun']) echo 'selected'; ?>>
                                    <?php echo $row1['nombre_mun']; ?>
                                </option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-12 col-sm-4">
                        <label for="nombre_encuestador_hog">NOMBRE DEL ENCUESTADOR:</label>
                        <input type='text' name='nombre_encuestador_hog' class='form-control' id="nombre_encuestador_hog" value='<?php echo $nombre; ?>' readonly />
                    </div>
                    <div class="col-12 col-sm-3">
                        <label for="rol_encuestador_hog">TIPO DE ACCESO:</label>
                        <select class="form-control" name="rol_encuestador_hog" readonly>
                            <option value="">SELECCIONE:</option>
                            <option value="RECTOR" <?php if ($tipo_usuario == 1) echo 'selected'; ?>>RECTOR</option>
                            <option value="SIMAT" <?php if ($tipo_usuario == 2) echo 'selected'; ?>>SIMAT</option>
                            <option value="DOCENTE" <?php if ($tipo_usuario == 3) echo 'selected'; ?>>DOCENTE</option>
                            <option value="DOCENTE DIRECTIVO" <?php if ($tipo_usuario == 4) echo 'selected'; ?>>DOCENTE DIRECTIVO</option>
                            <option value="DOCENTE ORIENTADOR" <?php if ($tipo_usuario == 5) echo 'selected'; ?>>DOCENTE ORIENTADOR</option>
                            <option value="ADMINISTRATIVO" <?php if ($tipo_usuario == 6) echo 'selected'; ?>>ADMINISTRATIVO</option>
                            <option value="SIN ACCESO" <?php if ($tipo_usuario == 7) echo 'selected'; ?>>SIN ACCESO</option>
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
                    <div class="col-12 col-sm-6">
                        <label for="nombre_madre_hog">NOMBRES Y APELLIDOS DE LA MAMÁ:</label>
                        <input type='text' name='nombre_madre_hog' id="nombre_madre_hog" class='form-control' value='<?php echo utf8_encode($row['nombre_madre_hog']); ?>' />
                    </div>
                    <div class="col-12 col-sm-2">
                        <label for="vive_madre_hog">¿AÚN VIVE?</label>
                        <select class="form-control" name="vive_madre_hog" id="vive_madre_hog">
                            <option value=""></option>
                            <option value=1 <?php if ($row['vive_madre_hog'] == 1) echo 'selected'; ?>>SI</option>
                            <option value=0 <?php if ($row['vive_madre_hog'] == 0) echo 'selected'; ?>>NO</option>
                            <option value=2 <?php if ($row['vive_madre_hog'] == 2) echo 'selected'; ?>>N/A</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-4">
                        <label for="ocupacion_madre_hog">OCUPACIÓN MAMÁ:</label>
                        <select class="form-control" name="ocupacion_madre_hog" id="ocupacion_madre_hog">
                            <option value=""></option>
                            <option value="Ama de casa" <?php if ($row['ocupacion_madre_hog'] == 'Ama de casa') echo 'selected'; ?>>Ama de casa</option>
                            <option value="Empleada" <?php if ($row['ocupacion_madre_hog'] == 'Empleada') echo 'selected'; ?>>Empleada</option>
                            <option value="Trabajadora independiente" <?php if ($row['ocupacion_madre_hog'] == 'Trabajadora independiente') echo 'selected'; ?>>Trabajadora independiente</option>
                            <option value="Desempleada" <?php if ($row['ocupacion_madre_hog'] == 'Desempleada') echo 'selected'; ?>>Desempleada</option>
                            <option value="Jubilada" <?php if ($row['ocupacion_madre_hog'] == 'Jubilada') echo 'selected'; ?>>Jubilada</option>
                            <option value="Estudiante" <?php if ($row['ocupacion_madre_hog'] == 'Estudiante') echo 'selected'; ?>>Estudiante</option>
                            <option value="Pensionista" <?php if ($row['ocupacion_madre_hog'] == 'Pensionista') echo 'selected'; ?>>Pensionista</option>
                            <option value="Otro" <?php if ($row['ocupacion_madre_hog'] == 'Otro') echo 'selected'; ?>>Otro</option>
                            <option value="N/A" <?php if ($row['ocupacion_madre_hog'] == 'N/A') echo 'selected'; ?>>N/A</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-sm-4">
                        <label for="educacion_madre_hog">NIVEL EDUCATIVO CULMINADO MAMÁ:</label>
                        <select class="form-control" name="educacion_madre_hog" id="educacion_madre_hog">
                            <option value=""></option>
                            <option value="Ninguno" <?php if ($row['educacion_madre_hog'] == 'Ninguno') echo 'selected'; ?>>Ninguno</option>
                            <option value="Primaria" <?php if ($row['educacion_madre_hog'] == 'Primaria') echo 'selected'; ?>>Primaria</option>
                            <option value="Bachillerato" <?php if ($row['educacion_madre_hog'] == 'Bachillerato') echo 'selected'; ?>>Bachillerato</option>
                            <option value="Técnico" <?php if ($row['educacion_madre_hog'] == 'Técnico') echo 'selected'; ?>>Técnico</option>
                            <option value="Tecnológico" <?php if ($row['educacion_madre_hog'] == 'Tecnológico') echo 'selected'; ?>>Tecnológico</option>
                            <option value="Profesional" <?php if ($row['educacion_madre_hog'] == 'Profesional') echo 'selected'; ?>>Profesional</option>
                            <option value="Postgrado" <?php if ($row['educacion_madre_hog'] == 'Postgrado') echo 'selected'; ?>>Postgrado</option>
                            <option value="N/A" <?php if ($row['educacion_madre_hog'] == 'N/A') echo 'selected'; ?>>N/A</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-6">
                        <label for="nombre_padre_hog">NOMBRES Y APELLIDOS DEL PAPÁ:</label>
                        <input type='text' name='nombre_padre_hog' id="nombre_padre_hog" class='form-control' value='<?php echo utf8_encode($row['nombre_padre_hog']); ?>' />
                    </div>
                    <div class="col-12 col-sm-2">
                        <label for="vive_padre_hog">¿AÚN VIVE?</label>
                        <select class="form-control" name="vive_padre_hog" id="vive_padre_hog">
                            <option value=""></option>
                            <option value=1 <?php if ($row['vive_padre_hog'] == 1) echo 'selected'; ?>>SI</option>
                            <option value=0 <?php if ($row['vive_padre_hog'] == 0) echo 'selected'; ?>>NO</option>
                            <option value=2 <?php if ($row['vive_padre_hog'] == 2) echo 'selected'; ?>>N/A</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-sm-3">
                        <label for="ocupacion_padre_hog">OCUPACIÓN PAPÁ:</label>
                        <select class="form-control" name="ocupacion_padre_hog" id="ocupacion_padre_hog">
                            <option value=""></option>
                            <option value="Amo de casa" <?php if ($row['ocupacion_padre_hog'] == 'Amo de casa') echo 'selected'; ?>>Amo de casa</option>
                            <option value="Empleado" <?php if ($row['ocupacion_padre_hog'] == 'Empleado') echo 'selected'; ?>>Empleado</option>
                            <option value="Trabajador independiente" <?php if ($row['ocupacion_padre_hog'] == 'Trabajador independiente') echo 'selected'; ?>>Trabajador independiente</option>
                            <option value="Desempleado" <?php if ($row['ocupacion_padre_hog'] == 'Desempleado') echo 'selected'; ?>>Desempleado</option>
                            <option value="Jubilado" <?php if ($row['ocupacion_padre_hog'] == 'Jubilado') echo 'selected'; ?>>Jubilado</option>
                            <option value="Estudiante" <?php if ($row['ocupacion_padre_hog'] == 'Estudiante') echo 'selected'; ?>>Estudiante</option>
                            <option value="Pensionista" <?php if ($row['ocupacion_padre_hog'] == 'Pensionista') echo 'selected'; ?>>Pensionista</option>
                            <option value="Otro" <?php if ($row['ocupacion_padre_hog'] == 'Otro') echo 'selected'; ?>>Otro</option>
                            <option value="N/A" <?php if ($row['ocupacion_padre_hog'] == 'N/A') echo 'selected'; ?>>N/A</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-3">
                        <label for="educacion_padre_hog">NIVEL EDUCATIVO CULMINADO PAPÁ:</label>
                        <select class="form-control" name="educacion_padre_hog" id="educacion_padre_hog">
                            <option value=""></option>
                            <option value="Ninguno" <?php if ($row['educacion_padre_hog'] == 'Ninguno') echo 'selected'; ?>>Ninguno</option>
                            <option value="Primaria" <?php if ($row['educacion_padre_hog'] == 'Primaria') echo 'selected'; ?>>Primaria</option>
                            <option value="Bachillerato" <?php if ($row['educacion_padre_hog'] == 'Bachillerato') echo 'selected'; ?>>Bachillerato</option>
                            <option value="Técnico" <?php if ($row['educacion_padre_hog'] == 'Técnico') echo 'selected'; ?>>Técnico</option>
                            <option value="Tecnológico" <?php if ($row['educacion_padre_hog'] == 'Tecnológico') echo 'selected'; ?>>Tecnológico</option>
                            <option value="Profesional" <?php if ($row['educacion_padre_hog'] == 'Profesional') echo 'selected'; ?>>Profesional</option>
                            <option value="Postgrado" <?php if ($row['educacion_padre_hog'] == 'Postgrado') echo 'selected'; ?>>Postgrado</option>
                            <option value="N/A" <?php if ($row['educacion_padre_hog'] == 'N/A') echo 'selected'; ?>>N/A</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-3">
                        <label for="vive_estu_hog">* ¿EL ESTUDIANTE VIVE CON?</label>
                        <select class="form-control" name="vive_estu_hog" id="vive_estu_hog" required>
                            <option value=""></option>
                            <option value="En familia" <?php if ($row['vive_estu_hog'] == 'En familia') echo 'selected'; ?>>En familia</option>
                            <option value="Madre" <?php if ($row['vive_estu_hog'] == 'Madre') echo 'selected'; ?>>Madre</option>
                            <option value="Padre" <?php if ($row['vive_estu_hog'] == 'Padre') echo 'selected'; ?>>Padre</option>
                            <option value="Hermanos" <?php if ($row['vive_estu_hog'] == 'Hermanos') echo 'selected'; ?>>Hermanos</option>
                            <option value="Abuelos" <?php if ($row['vive_estu_hog'] == 'Abuelos') echo 'selected'; ?>>Abuelos</option>
                            <option value="Otro" <?php if ($row['vive_estu_hog'] == 'Otro') echo 'selected'; ?>>Otro</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-3">
                        <label for="nom_vive_estu_hog">¿CON QUIÉN VIVE?</label>
                        <input type='text' name='nom_vive_estu_hog' id="nom_vive_estu_hog" class='form-control' value='<?php echo utf8_encode($row['nom_vive_estu_hog']); ?>' />
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-sm-4">
                        <label for="cuidador_estu_hog">* NOMBRES Y APELLIDOS CUIDADOR:</label>
                        <input type='text' name='cuidador_estu_hog' id="cuidador_estu_hog" class='form-control' value='<?php echo utf8_encode($row['cuidador_estu_hog']); ?>' required/>
                    </div>
                    <div class="col-12 col-sm-2">
                        <label for="parentesco_cuid_estu_hog">* PARENTESCO CUIDADOR:</label>
                        <select class="form-control" name="parentesco_cuid_estu_hog" id="parentesco_cuid_estu_hog" required>
                            <option value=""></option>
                            <option value="Madre" <?php if ($row['parentesco_cuid_estu_hog'] == 'Madre') echo 'selected'; ?>>Madre</option>
                            <option value="Padre" <?php if ($row['parentesco_cuid_estu_hog'] == 'Padre') echo 'selected'; ?>>Padre</option>
                            <option value="Hermanos" <?php if ($row['parentesco_cuid_estu_hog'] == 'Hermanos') echo 'selected'; ?>>Hermanos</option>
                            <option value="Abuelos" <?php if ($row['parentesco_cuid_estu_hog'] == 'Abuelos') echo 'selected'; ?>>Abuelos</option>
                            <option value="Otro" <?php if ($row['parentesco_cuid_estu_hog'] == 'Otro') echo 'selected'; ?>>Otro</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-3">
                        <label for="nom_parentesco_cuid_estu_hog">¿A QUIÉN SE REFIERE CON OTRO?</label>
                        <input type='text' name='nom_parentesco_cuid_estu_hog' id="nom_parentesco_cuid_estu_hog" class='form-control' value='<?php echo utf8_encode($row['nom_parentesco_cuid_estu_hog']); ?>' />
                    </div>
                    <div class="col-12 col-sm-3">
                        <label for="educacion_cuid_estu_hog">* NIVEL EDUCATIVO CULMINADO CUIDADOR:</label>
                        <select class="form-control" name="educacion_cuid_estu_hog" id="educacion_cuid_estu_hog" required>
                            <option value=""></option>
                            <option value="Ninguno" <?php if ($row['educacion_cuid_estu_hog'] == 'Ninguno') echo 'selected'; ?>>Ninguno</option>
                            <option value="Primaria" <?php if ($row['educacion_cuid_estu_hog'] == 'Primaria') echo 'selected'; ?>>Primaria</option>
                            <option value="Bachillerato" <?php if ($row['educacion_cuid_estu_hog'] == 'Bachillerato') echo 'selected'; ?>>Bachillerato</option>
                            <option value="Técnico" <?php if ($row['educacion_cuid_estu_hog'] == 'Técnico') echo 'selected'; ?>>Técnico</option>
                            <option value="Tecnológico" <?php if ($row['educacion_cuid_estu_hog'] == 'Tecnológico') echo 'selected'; ?>>Tecnológico</option>
                            <option value="Profesional" <?php if ($row['educacion_cuid_estu_hog'] == 'Profesional') echo 'selected'; ?>>Profesional</option>
                            <option value="Postgrado" <?php if ($row['educacion_cuid_estu_hog'] == 'Postgrado') echo 'selected'; ?>>Postgrado</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-sm-4">
                        <label for="ocupacion_cuid_estu_hog">* OCUPACIÓN CUIDADOR:</label>
                        <select class="form-control" name="ocupacion_cuid_estu_hog" id="ocupacion_cuid_estu_hog" required>
                            <option value=""></option>
                            <option value="Ama(o) de casa" <?php if ($row['ocupacion_cuid_estu_hog'] == 'Ama(o) de casa') echo 'selected'; ?>>Ama(o) de casa</option>
                            <option value="Empleada(o)" <?php if ($row['ocupacion_cuid_estu_hog'] == 'Empleada(o)') echo 'selected'; ?>>Empleada(o)</option>
                            <option value="Trabajador(a) independiente" <?php if ($row['ocupacion_cuid_estu_hog'] == 'Trabajador(a) independiente') echo 'selected'; ?>>Trabajador(a) independiente</option>
                            <option value="Desempleado(a)" <?php if ($row['ocupacion_cuid_estu_hog'] == 'Desempleado(a)') echo 'selected'; ?>>Desempleado(a)</option>
                            <option value="Jubilado(a)" <?php if ($row['ocupacion_cuid_estu_hog'] == 'Jubilado(a)') echo 'selected'; ?>>Jubilado(a)</option>
                            <option value="Estudiante" <?php if ($row['ocupacion_cuid_estu_hog'] == 'Estudiante') echo 'selected'; ?>>Estudiante</option>
                            <option value="Pensionista" <?php if ($row['ocupacion_cuid_estu_hog'] == 'Pensionista') echo 'selected'; ?>>Pensionista</option>
                            <option value="Otro" <?php if ($row['ocupacion_cuid_estu_hog'] == 'Otro') echo 'selected'; ?>>Otro</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-3">
                        <label for="nom_ocupacion_cuid_estu_hog">OCUPACIÓN A LA QUE SE REFIERE:</label>
                        <input type='text' name='nom_ocupacion_cuid_estu_hog' id="nom_ocupacion_cuid_estu_hog" class='form-control' value='<?php echo utf8_encode($row['nom_ocupacion_cuid_estu_hog']); ?>' />
                    </div>
                    <div class="col-12 col-sm-2">
                        <label for="tel_cuid_estu_hog">* CONTACTO CUIDADOR:</label>
                        <input type='number' name='tel_cuid_estu_hog' id="tel_cuid_estu_hog" class='form-control' value='<?php echo utf8_encode($row['tel_cuid_estu_hog']); ?>' required/>
                    </div>
                    <div class="col-12 col-sm-3">
                        <label for="email_cuid_estu_hog">EMAIL CUIDADOR:</label>
                        <input type='email' name='email_cuid_estu_hog' id="email_cuid_estu_hog" class='form-control' value='<?php echo utf8_encode($row['email_cuid_estu_hog']); ?>' />
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-sm-2">
                        <label for="num_herm_estu_hog">* No. HERMANOS:</label>
                        <select class="form-control" name="num_herm_estu_hog" id="num_herm_estu_hog" required>
                            <option value=""></option>
                            <option value=0 <?php if ($row['num_herm_estu_hog'] == 0) echo 'selected'; ?>>0</option>
                            <option value=1 <?php if ($row['num_herm_estu_hog'] == 1) echo 'selected'; ?>>1</option>
                            <option value=2 <?php if ($row['num_herm_estu_hog'] == 2) echo 'selected'; ?>>2</option>
                            <option value=3 <?php if ($row['num_herm_estu_hog'] == 3) echo 'selected'; ?>>3</option>
                            <option value=4 <?php if ($row['num_herm_estu_hog'] == 4) echo 'selected'; ?>>4</option>
                            <option value=5 <?php if ($row['num_herm_estu_hog'] == 5) echo 'selected'; ?>>5</option>
                            <option value=6 <?php if ($row['num_herm_estu_hog'] == 6) echo 'selected'; ?>>Más de 6</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-3">
                        <label for="lugar_ocupa_estu_hog">* LUGAR QUE OCUPA ENTRE SUS HERMANOS:</label>
                        <select class="form-control" name="lugar_ocupa_estu_hog" required>
                            <option value=""></option>
                            <option value=1 <?php if ($row['lugar_ocupa_estu_hog'] == 1) echo 'selected'; ?>>1</option>
                            <option value=2 <?php if ($row['lugar_ocupa_estu_hog'] == 2) echo 'selected'; ?>>2</option>
                            <option value=3 <?php if ($row['lugar_ocupa_estu_hog'] == 3) echo 'selected'; ?>>3</option>
                            <option value=4 <?php if ($row['lugar_ocupa_estu_hog'] == 4) echo 'selected'; ?>>4</option>
                            <option value=5 <?php if ($row['lugar_ocupa_estu_hog'] == 5) echo 'selected'; ?>>5</option>
                            <option value=6 <?php if ($row['lugar_ocupa_estu_hog'] == 6) echo 'selected'; ?>>6</option>
                            <option value=7 <?php if ($row['lugar_ocupa_estu_hog'] == 7) echo 'selected'; ?>>Más de 7</option>
                            <option value=0 <?php if ($row['lugar_ocupa_estu_hog'] == 0) echo 'selected'; ?>>No aplica</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-3">
                        <label for="tiene_herm_ie_estu_hog">* ¿TIENE HERMANOS QUE ESTUDIAN EN EL COLEGIO?</label>
                        <select class="form-control" name="tiene_herm_ie_estu_hog" id="tiene_herm_ie_estu_hog" required>
                            <option value=""></option>
                            <option value=1 <?php if ($row['tiene_herm_ie_estu_hog'] == 1) echo 'selected'; ?>>Si</option>
                            <option value=0 <?php if ($row['tiene_herm_ie_estu_hog'] == 0) echo 'selected'; ?>>No</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-4">
                        <label for="niveles_educativos_herm_ie_estu_hog">NIVELES EDUCATIVOS DE LOS HERMANOS:</label>
                        <div class="form-control" id="niveles_educativos_herm_ie_estu_hog" style="height: auto;">
                            <div class="row">
                                <div class="col-6 col-sm-4">
                                    <input type="checkbox" name="niveles_educativos_herm_ie_estu_hog[]" value="-1" <?php echo in_array('-1', $niveles_educativos_seleccionados) ? 'checked' : ''; ?>> Jardín<br>
                                    <input type="checkbox" name="niveles_educativos_herm_ie_estu_hog[]" value="0" <?php echo in_array('0', $niveles_educativos_seleccionados) ? 'checked' : ''; ?>> Transición<br>
                                    <input type="checkbox" name="niveles_educativos_herm_ie_estu_hog[]" value="1" <?php echo in_array('1', $niveles_educativos_seleccionados) ? 'checked' : ''; ?>> 1<br>
                                    <input type="checkbox" name="niveles_educativos_herm_ie_estu_hog[]" value="2" <?php echo in_array('2', $niveles_educativos_seleccionados) ? 'checked' : ''; ?>> 2<br>
                                    <input type="checkbox" name="niveles_educativos_herm_ie_estu_hog[]" value="3" <?php echo in_array('3', $niveles_educativos_seleccionados) ? 'checked' : ''; ?>> 3<br>
                                </div>
                                <div class="col-6 col-sm-4">
                                    <input type="checkbox" name="niveles_educativos_herm_ie_estu_hog[]" value="4" <?php echo in_array('4', $niveles_educativos_seleccionados) ? 'checked' : ''; ?>> 4<br>
                                    <input type="checkbox" name="niveles_educativos_herm_ie_estu_hog[]" value="5" <?php echo in_array('5', $niveles_educativos_seleccionados) ? 'checked' : ''; ?>> 5<br>
                                    <input type="checkbox" name="niveles_educativos_herm_ie_estu_hog[]" value="6" <?php echo in_array('6', $niveles_educativos_seleccionados) ? 'checked' : ''; ?>> 6<br>
                                    <input type="checkbox" name="niveles_educativos_herm_ie_estu_hog[]" value="7" <?php echo in_array('7', $niveles_educativos_seleccionados) ? 'checked' : ''; ?>> 7<br>
                                    <input type="checkbox" name="niveles_educativos_herm_ie_estu_hog[]" value="8" <?php echo in_array('8', $niveles_educativos_seleccionados) ? 'checked' : ''; ?>> 8<br>
                                </div>
                                <div class="col-6 col-sm-4">
                                    <input type="checkbox" name="niveles_educativos_herm_ie_estu_hog[]" value="9" <?php echo in_array('9', $niveles_educativos_seleccionados) ? 'checked' : ''; ?>> 9<br>
                                    <input type="checkbox" name="niveles_educativos_herm_ie_estu_hog[]" value="10" <?php echo in_array('10', $niveles_educativos_seleccionados) ? 'checked' : ''; ?>> 10<br>
                                    <input type="checkbox" name="niveles_educativos_herm_ie_estu_hog[]" value="11" <?php echo in_array('11', $niveles_educativos_seleccionados) ? 'checked' : ''; ?>> 11<br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-sm-3">
                        <label for="crianza_estu_hog">* ¿QUIÉN(ES) APOYAN PROCESO CRIANZA?</label>
                        <select class="form-control" name="crianza_estu_hog" id="crianza_estu_hog" required>
                            <option value=""></option>
                            <option value="Madre" <?php if ($row['crianza_estu_hog'] == 'Madre') echo 'selected'; ?>>Madre</option>
                            <option value="Padre" <?php if ($row['crianza_estu_hog'] == 'Padre') echo 'selected'; ?>>Padre</option>
                            <option value="Hermano" <?php if ($row['crianza_estu_hog'] == 'Hermano') echo 'selected'; ?>>Hermano</option>
                            <option value="Hermana" <?php if ($row['crianza_estu_hog'] == 'Hermana') echo 'selected'; ?>>Hermana</option>
                            <option value="Abuelo" <?php if ($row['crianza_estu_hog'] == 'Abuelo') echo 'selected'; ?>>Abuelo</option>
                            <option value="Abuela" <?php if ($row['crianza_estu_hog'] == 'Abuela') echo 'selected'; ?>>Abuela</option>
                            <option value="Tío" <?php if ($row['crianza_estu_hog'] == 'Tío') echo 'selected'; ?>>Tío</option>
                            <option value="Tía" <?php if ($row['crianza_estu_hog'] == 'Tía') echo 'selected'; ?>>Tía</option>
                            <option value="Primos" <?php if ($row['crianza_estu_hog'] == 'Primos') echo 'selected'; ?>>Primos</option>
                            <option value="Otro" <?php if ($row['crianza_estu_hog'] == 'Otro') echo 'selected'; ?>>Otro</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-4">
                        <label for="nom_crianza_estu_hog">¿QUIÉN APOYA LA CRIANZA?:</label>
                        <input type='text' name='nom_crianza_estu_hog' id="nom_crianza_estu_hog" class='form-control' value='<?php echo utf8_encode($row['nom_crianza_estu_hog']); ?>' />
                    </div>
                    <div class="col-12 col-sm-5">
                        <label for="prac_comu_estu_hog">* ¿CUÁL ES LA PRÁCTICA COMUNICATIVA MÁS FRECUENTE ENTRE EL ESTUDIANTE Y LA FAMILIA y/o CUIDADOR? </label>
                        <select class="form-control" name="prac_comu_estu_hog" id="prac_comu_estu_hog" required>
                            <option value=""></option>
                            <option value="Diálogo continuo" <?php if ($row['prac_comu_estu_hog'] == 'Diálogo continuo') echo 'selected'; ?>>Diálogo continuo</option>
                            <option value="Diálogo en horas especíﬁcas" <?php if ($row['prac_comu_estu_hog'] == 'Diálogo en horas especíﬁcas') echo 'selected'; ?>>Diálogo en horas especíﬁcas</option>
                            <option value="Llamadas o mensajes de texto" <?php if ($row['prac_comu_estu_hog'] == 'Llamadas o mensajes de texto') echo 'selected'; ?>>Llamadas o mensajes de texto</option>
                            <option value="Encuentros eventuales" <?php if ($row['prac_comu_estu_hog'] == 'Encuentros eventuales') echo 'selected'; ?>>Encuentros eventuales</option>
                            <option value="Ninguno" <?php if ($row['prac_comu_estu_hog'] == 'Ninguno') echo 'selected'; ?>>Ninguno</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-sm-4">
                        <label for="fam_categ_estu_hog">* LA FAMILIA DEL O LA ESTUDIANTE SE AJUSTA A LA CATEGORÍA DE: </label>
                        <select class="form-control" name="fam_categ_estu_hog" required>
                            <option value=""></option>
                            <option value="Familia nuclear (biparental)" <?php if ($row['fam_categ_estu_hog'] == 'Familia nuclear (biparental)') echo 'selected'; ?>>Familia nuclear (biparental)</option>
                            <option value="Familia monoparental" <?php if ($row['fam_categ_estu_hog'] == 'Familia monoparental') echo 'selected'; ?>>Familia monoparental</option>
                            <option value="Familia adoptiva" <?php if ($row['fam_categ_estu_hog'] == 'Familia adoptiva') echo 'selected'; ?>>Familia adoptiva</option>
                            <option value="Familia de padres separados" <?php if ($row['fam_categ_estu_hog'] == 'Familia de padres separados') echo 'selected'; ?>>Familia de padres separados</option>
                            <option value="Familia compuesta" <?php if ($row['fam_categ_estu_hog'] == 'Familia compuesta') echo 'selected'; ?>>Familia compuesta</option>
                            <option value="Familia homoparental" <?php if ($row['fam_categ_estu_hog'] == 'Familia homoparental') echo 'selected'; ?>>Familia homoparental</option>
                            <option value="Familia extensa" <?php if ($row['fam_categ_estu_hog'] == 'Familia extensa') echo 'selected'; ?>>Familia extensa</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-3">
                        <label for="fam_subsidio_hog">* ¿LA FAMILIA RECIBE ALGÚN SUBSIDIO? </label>
                        <select class="form-control" name="fam_subsidio_hog" id="fam_subsidio_hog" required>
                            <option value=""></option>
                            <option value=1 <?php if ($row['fam_subsidio_hog'] == 1) echo 'selected'; ?>>Si</option>
                            <option value=0 <?php if ($row['fam_subsidio_hog'] == 0) echo 'selected'; ?>>No</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-5">
                        <label for="tipo_subsidio_hog"> NOMBRE DEL SUBSIDIO:</label>
                        <input type='text' name='tipo_subsidio_hog' id="tipo_subsidio_hog" class='form-control' value='<?php echo utf8_encode($row['tipo_subsidio_hog']); ?>' />
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-sm-6">
                        <label for="mecanismos_conflictos_estu_hog">* MECANISMOS PARA LA SOLUCIÓN DE CONFLICTOS:</label>
                        <div class="form-control" id="mecanismos_conflictos_estu_hog" style="height: auto;">
                            <div class="row">
                                <div class="col-6 col-sm-6">
                                    <input type="checkbox" name="mecanismos_conflictos_estu_hog[]" value="Comunicación abierta y sincera"> Comunicación abierta y sincera<br>
                                    <input type="checkbox" name="mecanismos_conflictos_estu_hog[]" value="Mediación"> Mediación<br>
                                    <input type="checkbox" name="mecanismos_conflictos_estu_hog[]" value="Terapia o asesoramiento"> Terapia o asesoramiento<br>
                                    <input type="checkbox" name="mecanismos_conflictos_estu_hog[]" value="Compromiso mutuo"> Compromiso mutuo<br>
                                    <input type="checkbox" name="mecanismos_conflictos_estu_hog[]" value="Cambio de perspectiva"> Cambio de perspectiva<br>
                                </div>
                                <div class="col-6 col-sm-5">
                                    <input type="checkbox" name="mecanismos_conflictos_estu_hog[]" value="Castigo físico"> Castigo físico<br>
                                    <input type="checkbox" name="mecanismos_conflictos_estu_hog[]" value="Amenaza verbal"> Amenaza verbal<br>
                                    <input type="checkbox" name="mecanismos_conflictos_estu_hog[]" value="Prohibiciones"> Prohibiciones<br>
                                    <input type="checkbox" name="mecanismos_conflictos_estu_hog[]" value="Otros"> Otros<br>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <label for="nom_mecanismos_conflictos_estu_hog"> MENCIONE LOS OTROS TIPOS DE MECANISMOS PARA LA SOLUCIÓN DE CONFLICTOS:</label>
                        <input type='text' name='nom_mecanismos_conflictos_estu_hog' id="nom_mecanismos_conflictos_estu_hog" class='form-control' value='<?php echo utf8_encode($row['nom_mecanismos_conflictos_estu_hog']); ?>' />
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-sm-5">
                        <label for="inconvenientes_quien_hog">* ¿QUIÉN o QUIÉNES SOLUCIONAN LOS INCONVENIENTES QUE SE PRESENTAN EN LA FAMILIA?</label>
                        <div class="form-control" id="inconvenientes_quien_hog" style="height: auto;">
                            <div class="row">
                                <div class="col-6 col-sm-4">
                                    <input type="checkbox" name="inconvenientes_quien_hog[]" value="Padre"> Padre<br>
                                    <input type="checkbox" name="inconvenientes_quien_hog[]" value="Madre"> Madre<br>
                                    <input type="checkbox" name="inconvenientes_quien_hog[]" value="Hermanos"> Hermanos<br>
                                    <input type="checkbox" name="inconvenientes_quien_hog[]" value="Otros"> Otros<br>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-7">
                        <label for="nom_quien_inconvenientes_hog"> MENCIONE QUIÉN o QUIÉNES SOLUCIONAN LOS INCONVENIENTES QUE SE PRESENTAN EN LA FAMILIA:</label>
                        <input type='text' name='nom_quien_inconvenientes_hog' id="nom_quien_inconvenientes_hog" class='form-control' value='<?php echo utf8_encode($row['nom_quien_inconvenientes_hog']); ?>' />
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-sm-6">
                        <label for="inconvenientes_como_hog">* ¿CÓMO SOLUCIONAN LOS INCONVENIENTES QUE SE PRESENTAN EN LA FAMILIA?</label>
                        <div class="form-control" id="inconvenientes_como_hog" style="height: auto;">
                            <div class="row">
                                <div class="col-6 col-sm-6">
                                    <input type="checkbox" name="inconvenientes_como_hog[]" value="Comunicación abierta y sincera"> Comunicación abierta y sincera<br>
                                    <input type="checkbox" name="inconvenientes_como_hog[]" value="Terapia familiar"> Terapia familiar<br>
                                    <input type="checkbox" name="inconvenientes_como_hog[]" value="Mediación"> Mediación<br>
                                    <input type="checkbox" name="inconvenientes_como_hog[]" value="Acciones legales"> Acciones legales<br>
                                </div>
                                <div class="col-6 col-sm-6">
                                    <input type="checkbox" name="inconvenientes_como_hog[]" value="Consejería individual"> Consejería individual<br>
                                    <input type="checkbox" name="inconvenientes_como_hog[]" value="Conversaciones difíciles"> Conversaciones difíciles<br>
                                    <input type="checkbox" name="inconvenientes_como_hog[]" value="Disciplina física"> Disciplina física<br>
                                    <input type="checkbox" name="inconvenientes_como_hog[]" value="Evitación"> Evitación<br>
                                    <input type="checkbox" name="inconvenientes_como_hog[]" value="Otros"> Otros<br>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <label for="nom_como_inconvenientes_hog"> MENCIONE A QUÉ SE REFIERE CON OTRO FRENTE A LA SOLUCIÓN DEL INCONVENIENTE PRESENTADO:</label>
                        <input type='text' name='nom_como_inconvenientes_hog' id="nom_como_inconvenientes_hog" class='form-control' value='<?php echo utf8_encode($row['nom_como_inconvenientes_hog']); ?>' />
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-sm-12">
                        <label for="responsabilidades_est_hog">* ¿QUÉ RESPONSABILIDADES SON ASIGNADAS AL ESTUDIANTE EN EL HOGAR?</label>
                        <div class="form-control" id="responsabilidades_est_hog" style="height: auto;">
                            <div class="row">
                                <div class="col-6 col-sm-12">
                                    <input type="checkbox" name="responsabilidades_est_hog[]" value="Mantener su habitación ordenada y limpia"> Mantener su habitación ordenada y limpia<br>
                                    <input type="checkbox" name="responsabilidades_est_hog[]" value="Ayudar con las tareas domésticas como poner la mesa, lavar los platos, etc."> Ayudar con las tareas domésticas como poner la mesa, lavar los platos, etc.<br>
                                    <input type="checkbox" name="responsabilidades_est_hog[]" value="Cuidar de los animales de compañía"> Cuidar de los animales de compañía<br>
                                    <input type="checkbox" name="responsabilidades_est_hog[]" value="Realizar tareas de jardinería"> Realizar tareas de jardinería<br>
                                    <input type="checkbox" name="responsabilidades_est_hog[]" value="Ayudar con la compra de alimentos y otros suministros"> Ayudar con la compra de alimentos y otros suministros<br>
                                    <input type="checkbox" name="responsabilidades_est_hog[]" value="Ayudar a preparar comidas"> Ayudar a preparar comidas<br>
                                    <input type="checkbox" name="responsabilidades_est_hog[]" value="Realizar tareas de limpieza y mantenimiento"> Realizar tareas de limpieza y mantenimiento<br>
                                    <input type="checkbox" name="responsabilidades_est_hog[]" value="Ayudar a cuidar a hermanos menores y familiares"> Ayudar a cuidar a hermanos menores y familiares<br>
                                    <input type="checkbox" name="responsabilidades_est_hog[]" value="Hacer la tarea y estudiar para mantener buenas caliﬁcaciones en el colegio"> Hacer la tarea y estudiar para mantener buenas caliﬁcaciones en el colegio<br>
                                    <input type="checkbox" name="responsabilidades_est_hog[]" value="Participar en actividades familiares y comunitarias"> Participar en actividades familiares y comunitarias<br>
                                    <input type="checkbox" name="responsabilidades_est_hog[]" value="Ninguna"> Ninguna<br>
                                    <input type="checkbox" name="responsabilidades_est_hog[]" value="Otros"> Otros<br>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-sm-12">
                        <label for="nom_responsabilidades_est_hog"> MENCIONE LAS RESPONSABILIDADES ASIGNADAS AL ESTUDIANTE:</label>
                        <input type='text' name='nom_responsabilidades_est_hog' id="nom_responsabilidades_est_hog" class='form-control' value='<?php echo utf8_encode($row['nom_responsabilidades_est_hog']); ?>' />
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-sm-6">
                        <label for="afecto_est_hog">* ¿CÓMO EXPRESAN EL AFECTO ENTRE LOS MIEMBROS DE LA FAMILIA? </label>
                        <div class="form-control" id="afecto_est_hog" style="height: auto;">
                            <div class="row">
                                <div class="col-6 col-sm-6">
                                    <input type="checkbox" name="afecto_est_hog[]" value="Abrazos"> Abrazos<br>
                                    <input type="checkbox" name="afecto_est_hog[]" value="Caricias"> Caricias<br>
                                    <input type="checkbox" name="afecto_est_hog[]" value="Juegos"> Juegos<br>
                                    <input type="checkbox" name="afecto_est_hog[]" value="Premios"> Premios<br>
                                </div>
                                <div class="col-6 col-sm-6">
                                    <input type="checkbox" name="afecto_est_hog[]" value="Recreación"> Recreación<br>
                                    <input type="checkbox" name="afecto_est_hog[]" value="Camaradería"> Camaradería<br>
                                    <input type="checkbox" name="afecto_est_hog[]" value="Ninguno"> Ninguno<br>
                                    <input type="checkbox" name="afecto_est_hog[]" value="Otros"> Otros<br>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <label for="nom_afecto_est_hog"> MENCIONE LA FORMA EN QUE SE EXPRESA EL AFECTO:</label>
                        <input type='text' name='nom_afecto_est_hog' id="nom_afecto_est_hog" class='form-control' value='<?php echo utf8_encode($row['nom_afecto_est_hog']); ?>' />
                    </div>
                </div>
            </div>

            <hr>
            <h2><b><i class="fa-solid fa-house-user"></i> INFORMACIÓN SOBRE LA VIVIENDA</b></h2>
            <hr>

            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-sm-3">
                        <label for="tipo_vivienda_hog">* TIPO DE VIVIENDA: </label>
                        <select class="form-control" name="tipo_vivienda_hog" required>
                            <option value=""></option>
                            <option value="Casa unifamiliar" <?php if ($row['tipo_vivienda_hog'] == 'Casa unifamiliar') echo 'selected'; ?>>Casa unifamiliar</option>
                            <option value="Casa multifamiliar" <?php if ($row['tipo_vivienda_hog'] == 'Casa multifamiliar') echo 'selected'; ?>>Casa multifamiliar</option>
                            <option value="Apartamento" <?php if ($row['tipo_vivienda_hog'] == 'Apartamento') echo 'selected'; ?>>Apartamento</option>
                            <option value="Condominio" <?php if ($row['tipo_vivienda_hog'] == 'Condominio') echo 'selected'; ?>>Condominio</option>
                            <option value="Casa compartida" <?php if ($row['tipo_vivienda_hog'] == 'Casa compartida') echo 'selected'; ?>>Casa compartida</option>
                            <option value="Chalet" <?php if ($row['tipo_vivienda_hog'] == 'Chalet') echo 'selected'; ?>>Chalet</option>
                            <option value="Cabaña" <?php if ($row['tipo_vivienda_hog'] == 'Cabaña') echo 'selected'; ?>>Cabaña</option>
                            <option value="Casa prefabricada" <?php if ($row['tipo_vivienda_hog'] == 'Casa prefabricada') echo 'selected'; ?>>Casa prefabricada</option>
                            <option value="Casa de campo" <?php if ($row['tipo_vivienda_hog'] == 'Casa de campo') echo 'selected'; ?>>Casa de campo</option>
                            <option value="Casa de huéspedes" <?php if ($row['tipo_vivienda_hog'] == 'Casa de huéspedes') echo 'selected'; ?>>Casa de huéspedes</option>
                            <option value="Inquilinato" <?php if ($row['tipo_vivienda_hog'] == 'Inquilinato') echo 'selected'; ?>>Inquilinato</option>
                            <option value="Casa móvil" <?php if ($row['tipo_vivienda_hog'] == 'Casa móvil') echo 'selected'; ?>>Casa móvil</option>
                            <option value="Tráiler" <?php if ($row['tipo_vivienda_hog'] == 'Tráiler') echo 'selected'; ?>>Tráiler</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-3">
                        <label for="tenencia_vivienda_hog">* TENENCIA DE LA VIVIENDA: </label>
                        <select class="form-control" name="tenencia_vivienda_hog" id="tenencia_vivienda_hog" required>
                            <option value=""></option>
                            <option value="Propia" <?php if ($row['tenencia_vivienda_hog'] == 'Propia') echo 'selected'; ?>>Propia</option>
                            <option value="Familiar" <?php if ($row['tenencia_vivienda_hog'] == 'Familiar') echo 'selected'; ?>>Familiar</option>
                            <option value="Alquilada" <?php if ($row['tenencia_vivienda_hog'] == 'Alquilada') echo 'selected'; ?>>Alquilada</option>
                            <option value="Otra" <?php if ($row['tenencia_vivienda_hog'] == 'Otra') echo 'selected'; ?>>Otra</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-3">
                        <label for="nom_tenencia_vivienda_hog"> OTRO TIPO TENENCIA:</label>
                        <input type='text' name='nom_tenencia_vivienda_hog' id="nom_tenencia_vivienda_hog" class='form-control' value='<?php echo utf8_encode($row['nom_tenencia_vivienda_hog']); ?>' />
                    </div>
                    <div class="col-12 col-sm-3">
                        <label for="material_vivienda_hog">* MATERIAL DE LA VIVIENDA: </label>
                        <select class="form-control" name="material_vivienda_hog" id="material_vivienda_hog" required>
                            <option value=""></option>
                            <optgroup label="Material">
                                <option value="Ladrillo" <?php if ($row['material_vivienda_hog'] == 'Ladrillo') echo 'selected'; ?>>Ladrillo</option>
                                <option value="Cemento" <?php if ($row['material_vivienda_hog'] == 'Cemento') echo 'selected'; ?>>Cemento</option>
                            </optgroup>
                            <optgroup label="Material de desecho">
                                <option value="Plástico" <?php if ($row['material_vivienda_hog'] == 'Plástico') echo 'selected'; ?>>Plástico</option>
                                <option value="Otros materiales" <?php if ($row['material_vivienda_hog'] == 'Otros materiales') echo 'selected'; ?>>Otros materiales</option>
                            </optgroup>
                            <option value="Bareque" <?php if ($row['material_vivienda_hog'] == 'Bareque') echo 'selected'; ?>>Bareque</option>
                            <optgroup label="Madera liviana">
                                <option value="Metal" <?php if ($row['material_vivienda_hog'] == 'Metal') echo 'selected'; ?>>Metal</option>
                                <option value="Placas sólidas" <?php if ($row['material_vivienda_hog'] == 'Placas sólidas') echo 'selected'; ?>>Placas sólidas</option>
                            </optgroup>
                            <option value="Tablas de corteza" <?php if ($row['material_vivienda_hog'] == 'Tablas de corteza') echo 'selected'; ?>>Tablas de corteza</option>
                            <option value="Bambú" <?php if ($row['material_vivienda_hog'] == 'Bambú') echo 'selected'; ?>>Bambú</option>
                            <option value="Caña" <?php if ($row['material_vivienda_hog'] == 'Caña') echo 'selected'; ?>>Caña</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-12 col-sm-5">
                        <label for="servicios_vivienda_hog">* SERVICIOS CON LOS CUALES CUENTA LA VIVIENDA </label>
                        <div class="form-control" id="servicios_vivienda_hog" style="height: auto;">
                            <div class="row">
                                <div class="col-6 col-sm-6">
                                    <input type="checkbox" name="servicios_vivienda_hog[]" value="Aguas"> Aguas<br>
                                    <input type="checkbox" name="servicios_vivienda_hog[]" value="Energía"> Energía<br>
                                    <input type="checkbox" name="servicios_vivienda_hog[]" value="Gas"> Gas<br>
                                    <input type="checkbox" name="servicios_vivienda_hog[]" value="Internet"> Internet<br>
                                    <input type="checkbox" name="servicios_vivienda_hog[]" value="Alcantarillado"> Alcantarillado<br>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-5">
                        <label for="num_personas_vivienda_hog">* ¿CUÁNTAS PERSONAS VIVEN NORMALMENTE EN ESTA VIVIENDA, CONTANDO NIÑOS Y ADULTOS MAYORES?</label>
                        <select class="form-control" name="num_personas_vivienda_hog" required>
                            <option value=""></option>
                            <option value=1 <?php if ($row['num_personas_vivienda_hog'] == 1) echo 'selected'; ?>>1</option>
                            <option value=2 <?php if ($row['num_personas_vivienda_hog'] == 2) echo 'selected'; ?>>2</option>
                            <option value=3 <?php if ($row['num_personas_vivienda_hog'] == 3) echo 'selected'; ?>>3</option>
                            <option value=4 <?php if ($row['num_personas_vivienda_hog'] == 4) echo 'selected'; ?>>4</option>
                            <option value=5 <?php if ($row['num_personas_vivienda_hog'] == 5) echo 'selected'; ?>>5</option>
                            <option value=6 <?php if ($row['num_personas_vivienda_hog'] == 6) echo 'selected'; ?>>6</option>
                            <option value=7 <?php if ($row['num_personas_vivienda_hog'] == 7) echo 'selected'; ?>>7</option>
                            <option value=8 <?php if ($row['num_personas_vivienda_hog'] == 8) echo 'selected'; ?>>8</option>
                            <option value=9 <?php if ($row['num_personas_vivienda_hog'] == 9) echo 'selected'; ?>>9</option>
                            <option value=10 <?php if ($row['num_personas_vivienda_hog'] == 10) echo 'selected'; ?>>10</option>
                            <option value=11 <?php if ($row['num_personas_vivienda_hog'] == 11) echo 'selected'; ?>>Más de 10 personas</option>
                        </select>
                    </div>
                </div>
            </div>


            <button type="submit" class="btn btn-primary" name="btn-update">
                <span class="spinner-border spinner-border-sm"></span>
                ACTUALIZAR INFORMACIÓN ENTORNO HOGAR - VIVIENDA
            </button>
            <button type="reset" class="btn btn-outline-dark" role='link' onclick="history.back();" type='reset'><img src='../../img/atras.png' width=27 height=27> REGRESAR
            </button>
        </form>
    </div>
</body>
</html>

<?php
if (isset($_POST['btn-update'])) {
    $fecha_dig_hog = mysqli_real_escape_string($mysqli, $_POST['fecha_dig_hog']);
    $mun_dig_hog = mysqli_real_escape_string($mysqli, $_POST['mun_dig_hog']);
    $nombre_encuestador_hog = mysqli_real_escape_string($mysqli, $_POST['nombre_encuestador_hog']);
    $rol_encuestador_hog = mysqli_real_escape_string($mysqli, $_POST['rol_encuestador_hog']);
    $num_doc_est = mysqli_real_escape_string($mysqli, $_POST['num_doc_est']);
    $nombre_madre_hog = mysqli_real_escape_string($mysqli, $_POST['nombre_madre_hog']);
    $vive_madre_hog = 2;
    $ocupacion_madre_hog = mysqli_real_escape_string($mysqli, $_POST['ocupacion_madre_hog']);
    $educacion_madre_hog = mysqli_real_escape_string($mysqli, $_POST['educacion_madre_hog']);
    $nombre_padre_hog = mysqli_real_escape_string($mysqli, $_POST['nombre_padre_hog']);
    $vive_padre_hog = 2;
    $ocupacion_padre_hog = mysqli_real_escape_string($mysqli, $_POST['ocupacion_padre_hog']);
    $educacion_padre_hog = mysqli_real_escape_string($mysqli, $_POST['educacion_padre_hog']);
    $vive_estu_hog = mysqli_real_escape_string($mysqli, $_POST['vive_estu_hog']);
    $nom_vive_estu_hog = mysqli_real_escape_string($mysqli, $_POST['nom_vive_estu_hog']);
    $cuidador_estu_hog = mysqli_real_escape_string($mysqli, $_POST['cuidador_estu_hog']);
    $parentesco_cuid_estu_hog = mysqli_real_escape_string($mysqli, $_POST['parentesco_cuid_estu_hog']);
    $nom_parentesco_cuid_estu_hog = mysqli_real_escape_string($mysqli, $_POST['nom_parentesco_cuid_estu_hog']);
    $educacion_cuid_estu_hog = mysqli_real_escape_string($mysqli, $_POST['educacion_cuid_estu_hog']);
    $ocupacion_cuid_estu_hog = mysqli_real_escape_string($mysqli, $_POST['ocupacion_cuid_estu_hog']);
    $nom_ocupacion_cuid_estu_hog = mysqli_real_escape_string($mysqli, $_POST['nom_ocupacion_cuid_estu_hog']);
    $tel_cuid_estu_hog = mysqli_real_escape_string($mysqli, $_POST['tel_cuid_estu_hog']);
    $email_cuid_estu_hog = mysqli_real_escape_string($mysqli, $_POST['email_cuid_estu_hog']);
    $num_herm_estu_hog = mysqli_real_escape_string($mysqli, $_POST['num_herm_estu_hog']);
    $lugar_ocupa_estu_hog = mysqli_real_escape_string($mysqli, $_POST['lugar_ocupa_estu_hog']);
    $tiene_herm_ie_estu_hog = mysqli_real_escape_string($mysqli, $_POST['tiene_herm_ie_estu_hog']);
    $niveles_educativos_herm_ie_estu_hog = isset($_POST['niveles_educativos_herm_ie_estu_hog']) ? mysqli_real_escape_string($mysqli, implode(',', $_POST['niveles_educativos_herm_ie_estu_hog'])) : '';
    $crianza_estu_hog = mysqli_real_escape_string($mysqli, $_POST['crianza_estu_hog']);
    $nom_crianza_estu_hog = mysqli_real_escape_string($mysqli, $_POST['nom_crianza_estu_hog']);
    $prac_comu_estu_hog = mysqli_real_escape_string($mysqli, $_POST['prac_comu_estu_hog']);
    $fam_categ_estu_hog = mysqli_real_escape_string($mysqli, $_POST['fam_categ_estu_hog']);
    $fam_subsidio_hog = mysqli_real_escape_string($mysqli, $_POST['fam_subsidio_hog']);
    $tipo_subsidio_hog = mysqli_real_escape_string($mysqli, $_POST['tipo_subsidio_hog']);
    $mecanismos_conflictos_estu_hog = isset($_POST['mecanismos_conflictos_estu_hog']) ? mysqli_real_escape_string($mysqli, implode(',', $_POST['mecanismos_conflictos_estu_hog'])) : '';
    $nom_mecanismos_conflictos_estu_hog = mysqli_real_escape_string($mysqli, $_POST['nom_mecanismos_conflictos_estu_hog']);
    $inconvenientes_quien_hog = isset($_POST['inconvenientes_quien_hog']) ? mysqli_real_escape_string($mysqli, implode(',', $_POST['inconvenientes_quien_hog'])) : '';
    $nom_quien_inconvenientes_hog = mysqli_real_escape_string($mysqli, $_POST['nom_quien_inconvenientes_hog']);
    $inconvenientes_como_hog = isset($_POST['inconvenientes_como_hog']) ? mysqli_real_escape_string($mysqli, implode(',', $_POST['inconvenientes_como_hog'])) : '';
    $nom_como_inconvenientes_hog = mysqli_real_escape_string($mysqli, $_POST['nom_como_inconvenientes_hog']);
    $responsabilidades_est_hog = isset($_POST['responsabilidades_est_hog']) ? mysqli_real_escape_string($mysqli, implode(',', $_POST['responsabilidades_est_hog'])) : '';
    $nom_responsabilidades_est_hog = mysqli_real_escape_string($mysqli, $_POST['nom_responsabilidades_est_hog']);
    $afecto_est_hog = isset($_POST['afecto_est_hog']) ? mysqli_real_escape_string($mysqli, implode(',', $_POST['afecto_est_hog'])) : '';
    $nom_afecto_est_hog = mysqli_real_escape_string($mysqli, $_POST['nom_afecto_est_hog']);
    $tipo_vivienda_hog = mysqli_real_escape_string($mysqli, $_POST['tipo_vivienda_hog']);
    $tenencia_vivienda_hog = mysqli_real_escape_string($mysqli, $_POST['tenencia_vivienda_hog']);
    $nom_tenencia_vivienda_hog = mysqli_real_escape_string($mysqli, $_POST['nom_tenencia_vivienda_hog']);
    $material_vivienda_hog = mysqli_real_escape_string($mysqli, $_POST['material_vivienda_hog']);
    $servicios_vivienda_hog = isset($_POST['servicios_vivienda_hog']) ? mysqli_real_escape_string($mysqli, implode(',', $_POST['servicios_vivienda_hog'])) : '';
    $num_personas_vivienda_hog = mysqli_real_escape_string($mysqli, $_POST['lugar_ocupa_estu_hog']);
    $estado_hog = 1;
    $fecha_alta_hog = date('Y-m-d h:i:s');
    $id_usu_alta_hog = $_SESSION['id'];
    $fecha_edit_hog = '0000-00-00 00:00:00';
    $id_usu = $_SESSION['id'];

    $sql_update = "INSERT INTO entornoHogar (
                        fecha_dig_hog,
                        mun_dig_hog,
                        nombre_encuestador_hog,
                        rol_encuestador_hog,
                        num_doc_est,
                        nombre_madre_hog,
                        vive_madre_hog,
                        ocupacion_madre_hog,
                        educacion_madre_hog,
                        nombre_padre_hog,
                        vive_padre_hog,
                        ocupacion_padre_hog,
                        educacion_padre_hog,
                        vive_estu_hog,
                        nom_vive_estu_hog,
                        cuidador_estu_hog,
                        parentesco_cuid_estu_hog,
                        nom_parentesco_cuid_estu_hog,
                        educacion_cuid_estu_hog,
                        ocupacion_cuid_estu_hog,
                        nom_ocupacion_cuid_estu_hog,
                        tel_cuid_estu_hog,
                        email_cuid_estu_hog,
                        num_herm_estu_hog,
                        lugar_ocupa_estu_hog,
                        tiene_herm_ie_estu_hog,
                        niveles_educativos_herm_ie_estu_hog,
                        crianza_estu_hog,
                        nom_crianza_estu_hog,
                        prac_comu_estu_hog,
                        fam_categ_estu_hog,
                        fam_subsidio_hog,
                        tipo_subsidio_hog,
                        mecanismos_conflictos_estu_hog,
                        nom_mecanismos_conflictos_estu_hog,
                        inconvenientes_quien_hog,
                        nom_quien_inconvenientes_hog,
                        inconvenientes_como_hog,
                        nom_como_inconvenientes_hog,
                        responsabilidades_est_hog,
                        nom_responsabilidades_est_hog,
                        afecto_est_hog,
                        nom_afecto_est_hog,
                        tipo_vivienda_hog,
                        tenencia_vivienda_hog,
                        nom_tenencia_vivienda_hog,
                        material_vivienda_hog,
                        servicios_vivienda_hog,
                        num_personas_vivienda_hog,
                        estado_hog,
                        fecha_alta_hog,
                        id_usu_alta_hog,
                        fecha_edit_hog,
                        id_usu
                    ) VALUES (
                        '$fecha_dig_hog',
                        '$mun_dig_hog',
                        '$nombre_encuestador_hog',
                        '$rol_encuestador_hog',
                        '$num_doc_est',
                        '$nombre_madre_hog',
                        '$vive_madre_hog',
                        '$ocupacion_madre_hog',
                        '$educacion_madre_hog',
                        '$nombre_padre_hog',
                        '$vive_padre_hog',
                        '$ocupacion_padre_hog',
                        '$educacion_padre_hog',
                        '$vive_estu_hog',
                        '$nom_vive_estu_hog',
                        '$cuidador_estu_hog',
                        '$parentesco_cuid_estu_hog',
                        '$nom_parentesco_cuid_estu_hog',
                        '$educacion_cuid_estu_hog',
                        '$ocupacion_cuid_estu_hog',
                        '$nom_ocupacion_cuid_estu_hog',
                        '$tel_cuid_estu_hog',
                        '$email_cuid_estu_hog',
                        '$num_herm_estu_hog',
                        '$lugar_ocupa_estu_hog',
                        '$tiene_herm_ie_estu_hog',
                        '$niveles_educativos_herm_ie_estu_hog',
                        '$crianza_estu_hog',
                        '$nom_crianza_estu_hog',
                        '$prac_comu_estu_hog',
                        '$fam_categ_estu_hog',
                        '$fam_subsidio_hog',
                        '$tipo_subsidio_hog',
                        '$mecanismos_conflictos_estu_hog',
                        '$nom_mecanismos_conflictos_estu_hog',
                        '$inconvenientes_quien_hog',
                        '$nom_quien_inconvenientes_hog',
                        '$inconvenientes_como_hog',
                        '$nom_como_inconvenientes_hog',
                        '$responsabilidades_est_hog',
                        '$nom_responsabilidades_est_hog',
                        '$afecto_est_hog',
                        '$nom_afecto_est_hog',
                        '$tipo_vivienda_hog',
                        '$tenencia_vivienda_hog',
                        '$nom_tenencia_vivienda_hog',
                        '$material_vivienda_hog',
                        '$servicios_vivienda_hog',
                        '$num_personas_vivienda_hog',
                        '$estado_hog',
                        '$fecha_alta_hog',
                        '$id_usu_alta_hog',
                        '$fecha_edit_hog',
                        '$id_usu'
                    )";

    if (mysqli_query($mysqli, $sql_update)) {
        header('Location: registro_satisfactorio.htm');
        exit();
    } else {
        echo "Error: " . mysqli_error($mysqli);
    }
}
?>
