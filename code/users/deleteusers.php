<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../../index.php");
    exit();
}

include("../../conexion.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Escapar el valor para evitar inyección SQL
    $id = $mysqli->real_escape_string($id);

    // Preparar la consulta SQL
    $query = "UPDATE usuarios SET estado_usu = 0 WHERE id = '$id'";

    if ($mysqli->query($query)) {
        header("Location: showusers.php");
        exit();
    } else {
        echo "Error al inactivar el usuario: " . $mysqli->error;
    }
} else {
    echo "Usuario no proporcionado.";
}

?>
