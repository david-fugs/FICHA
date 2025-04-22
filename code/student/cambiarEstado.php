<?php
session_start();
include("../../conexion.php");

if (!isset($_SESSION['id'])) {
    header("Location: ../../index.php");
    exit();
}

$num_doc_est = $_GET['num_doc_est'] ?? '';

if ($_GET['campo'] == 'prepostnatales') {
    $estado = 'estado_prepostnatales';
    $valor = $_GET['valor'];
    $url = 'showprePostnatales.php';
} else if ($_GET['campo'] == 'familiasalud') {
    $estado = 'estado_familiasalud';
    $valor = $_GET['valor'];
    $url = 'showHealthFamily.php';
} else if ($_GET['campo'] == 'desempeno') {
    $estado = 'estado_desempeno';
    $valor = $_GET['valor'];
    $url = 'showPerformance.php';
} else if ($_GET['campo'] == 'educacion') {
    $estado = 'estado_educacion';
    $valor = $_GET['valor'];
    $url = 'showEducation.php';
} else if ($_GET['campo'] == 'entornohogar') {
    $estado = 'estado_entornohogar';
    $valor = $_GET['valor'];
    $url = '../home/showentornoHogar.php';
} else if ($_GET['campo'] == 'preescolar') {
    $estado = 'estado_preescolar';
    $valor = $_GET['valor'];
    $url = 'showPreescolar.php';
} else if ($_GET['campo'] == 'personal') {
    $estado = 'estado_personal';
    $valor = $_GET['valor'];
    $url = 'showPersonal.php';
} else if ($_GET['campo'] == 'preguntas') {
    $estado = 'estado_preguntas';
    $valor = $_GET['valor'];
    $url = 'showQuestions.php';
}



if ($num_doc_est) {
    $num_doc_est = $mysqli->real_escape_string($num_doc_est);
    $estado = $mysqli->real_escape_string($estado);
    $valor = $mysqli->real_escape_string($valor);

    $query = "UPDATE estudiantes SET $estado = '$valor' WHERE num_doc_est = '$num_doc_est'";
    if ($mysqli->query($query)) {
        echo "<script>
            alert('El estado del estudiante ha sido actualizado.');
            window.location.href = '$url';
        </script>";
    } else {
        echo "<script>
            alert('Error al actualizar el estado del estudiante: " . $mysqli->error . "');
            window.location.href = '$url';
        </script>";
    }
} else {
    // Alerta cuando no se proporciona un documento válido
    echo "<script>alert('No se ha proporcionado un documento válido.');
        window.location.href = '$url';
    </script>";
}

$mysqli->close();
//puse la redireccion mejor en el js para que poder usar la funcion en los demas archivos
// header("Location: showprePostnatales.php");
