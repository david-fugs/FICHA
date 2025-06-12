<?php
$mysqli = new mysqli("localhost", "softepuc_fie", "~CY]&J9u#wxa", "softepuc_fie");

// Configurar charset UTF-8
$mysqli->set_charset("utf8");

// Verificar conexión
if ($mysqli->connect_error) {
    die("Error en la conexión: " . $mysqli->connect_error);
}
?>
