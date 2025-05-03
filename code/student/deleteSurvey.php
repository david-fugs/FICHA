<?php
session_start();

include("../../conexion.php");

$id = $_GET['id'];
$campo = $_GET['campo'];

if ($campo == "prepost") {
    $tabla = 'prePostnatales';
    $id_campo = 'id_prePostnatales';
    $ruta = 'viewSurveys.php?num_doc_est=' . $_GET['num_doc_est'];
} else if ($campo == "familiasalud") {
    $tabla = 'familiasalud';
    $id_campo = 'id_salud_familiaSalud';
    $ruta = 'viewHealthFamilySurvey.php?num_doc_est=' . $_GET['num_doc_est'];
} else if ($campo == "desempeno") {
    $tabla = 'desempeno';
    $id_campo = 'id_desempeno';
    $ruta = 'viewPerformanceSurvey.php?num_doc_est=' . $_GET['num_doc_est'];
} else if ($campo == "educacion") {
    $tabla = 'educacion';
    $id_campo = 'id_educacion';
    $ruta = 'viewEducationSurvey.php?num_doc_est=' . $_GET['num_doc_est'];
} else if ($campo == "entornohogar") {
    $tabla = 'entornohogar';
    $id_campo = 'id_hog';
    $ruta = 'viewEntornoHogar.php?num_doc_est=' . $_GET['num_doc_est'];
} else if ($campo == "preescolar") {
    $tabla = 'preescolar';
    $id_campo = 'id_preescolar';
    $ruta = 'viewPreescolarSurvey.php?num_doc_est=' . $_GET['num_doc_est'];
} else if ($campo == "personal") {
    $tabla = 'personal';
    $id_campo = 'id_personal';
    $ruta = 'viewPersonalSurvey.php?num_doc_est=' . $_GET['num_doc_est'];
} else if ($campo == "preguntas") {
    $tabla = 'preguntas';
    $id_campo = 'id_preguntas';
    $ruta = 'viewQuestionSurvey.php?num_doc_est=' . $_GET['num_doc_est'];
}


if (isset($id)) {
    // Escapar las variables para evitar inyección SQL
    $id = $mysqli->real_escape_string($id);
    $tabla = $mysqli->real_escape_string($tabla);  // Escapar el nombre de la tabla si es dinámico
    $id_campo = $mysqli->real_escape_string($id_campo); // Escapar el nombre del campo si es dinámico

    // Preparar la consulta SQL
    $query = "DELETE FROM $tabla WHERE $id_campo = '$id'";

    if ($mysqli->query($query)) {
        echo "<script>
            alert('Encuesta eliminada correctamente.');
            window.location.href = '$ruta';
        </script>";
    } else {
        echo "Error al eliminar la encuesta: " . $mysqli->error;
    }
}
 else {
    echo "<script>alert('Error al eliminar la encuesta.'); window.location.href = '$ruta';</script>";
}


$mysqli->close();
?>
<center>
    <a href="viewSurveys.php?num_doc_est=<?= $_GET['num_doc_est'] ?> "><img src='../../img/atras.png' width="72" height="72" title="Regresar" /></a>

</center>