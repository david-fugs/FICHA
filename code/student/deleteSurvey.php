<?php
session_start();

include("../../conexion.php");

$id = $_GET['id'];
$campo = $_GET['campo'];

if($campo == "prepost"){
    $tabla = 'prePostnatales';
    $id_campo = 'id_prePostnatales';
    $ruta = 'viewSurveys.php?num_doc_est=' . $_GET['num_doc_est'];
}
else if($campo == "familiasalud"){
    $tabla = 'familiasalud';
    $id_campo = 'id_salud_familiaSalud';
    $ruta = 'viewHealthFamilySurvey.php?num_doc_est=' . $_GET['num_doc_est'];
    
}
else if($campo == "desempeno"){
    $tabla = 'desempeno';
    $id_campo = 'id_desempeno';
    $ruta = 'viewPerformanceSurvey.php?num_doc_est=' . $_GET['num_doc_est'];
}
else if($campo == "educacion"){
    $tabla = 'educacion';
    $id_campo = 'id_educacion';
    $ruta = 'viewEducationSurvey.php?num_doc_est=' . $_GET['num_doc_est'];
}
else if($campo == "entornohogar"){
    $tabla = 'entornohogar';
    $id_campo = 'id_hog';
    $ruta = 'viewEntornoHogar.php?num_doc_est=' . $_GET['num_doc_est'];
}
else if($campo == "preescolar"){
    $tabla = 'preescolar';
    $id_campo = 'id_preescolar';
    $ruta = 'viewPreescolarSurvey.php?num_doc_est=' . $_GET['num_doc_est'];
}
else if($campo == "personal"){
    $tabla = 'personal';
    $id_campo = 'id_personal';
    $ruta = 'viewPersonalSurvey.php?num_doc_est=' . $_GET['num_doc_est'];
}
else if($campo == "preguntas"){
    $tabla = 'preguntas';
    $id_campo = 'id_preguntas';
    $ruta = 'viewQuestionSurvey.php?num_doc_est=' . $_GET['num_doc_est'];
}


if ($stmt = $mysqli->prepare("DELETE FROM $tabla WHERE $id_campo = ?")) {
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    echo "<script>alert('Encuesta eliminada correctamente.'); window.location.href = ' 
    $ruta';</script>";

} else {
    echo "<script>alert('Error al eliminar la encuesta.'); window.location.href = '
    $ruta';</script>";
}


$mysqli->close();
?>
