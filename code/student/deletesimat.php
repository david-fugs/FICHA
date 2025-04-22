<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../../index.php");
    exit();
}

include("../../conexion.php");

if (isset($_GET['num_doc_est'])) {
    $num_doc_est = $_GET['num_doc_est'];

    // Escapar el valor para evitar inyección SQL
    $num_doc_est = $mysqli->real_escape_string($num_doc_est);

    $query = "UPDATE estudiantes SET estado_est = 0 WHERE num_doc_est = '$num_doc_est'";

    if ($mysqli->query($query)) {
        header("Location: showsimat.php");
        exit();
    } else {
        echo "Error al inactivar el estudiante: " . $mysqli->error;
    }
} else {
    echo "Número de documento no proporcionado.";
}
