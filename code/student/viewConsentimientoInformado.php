<?php
/**
 * Visualizar archivo de consentimiento informado (PDF, DOC, DOCX)
 */

session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../../index.php");
    exit();
}

require_once(__DIR__ . '/../../conexion.php');

$id_consentimiento = $_GET['id'] ?? 0;

// Obtener información del archivo
$query = "SELECT archivo_consentimientoInformado FROM consentimientoInformado WHERE id_consentimientoInformado = $id_consentimiento";
$result = mysqli_query($mysqli, $query);
$row = mysqli_fetch_assoc($result);

if (!$row || empty($row['archivo_consentimientoInformado'])) {
    die("Archivo no encontrado");
}

$fileName = $row['archivo_consentimientoInformado'];
$filePath = __DIR__ . '/../../uploads/consentimientos/' . $fileName;

if (!file_exists($filePath)) {
    die("El archivo no existe en el servidor");
}

// Determinar el tipo de archivo por extensión
$extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

// Configurar el tipo MIME según la extensión
switch ($extension) {
    case 'pdf':
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . $fileName . '"');
        break;
    case 'doc':
        header('Content-Type: application/msword');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        break;
    case 'docx':
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        break;
    default:
        // Por defecto, forzar descarga
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        break;
}

header('Content-Length: ' . filesize($filePath));
readfile($filePath);
exit;
?>
