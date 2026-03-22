<?php
/**
 * Descargar plantilla Word de Consentimiento Informado
 */

session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../../index.php");
    exit();
}

$filePath = __DIR__ . '/../../Consentimiento_Informado.docx';

if (!file_exists($filePath)) {
    die("El archivo no existe");
}

// Enviar archivo al navegador
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Content-Disposition: attachment; filename="Consentimiento_Informado.docx"');
header('Content-Length: ' . filesize($filePath));
readfile($filePath);
exit;
?>
