<?php
/**
 * Eliminar consentimiento informado
 */

session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../../index.php");
    exit();
}

require_once(__DIR__ . '/../../conexion.php');

$id_consentimiento = $_GET['id'] ?? 0;

if ($id_consentimiento > 0) {
    // Obtener información del archivo para eliminarlo
    $query = "SELECT num_doc_est, archivo_consentimientoInformado FROM consentimientoInformado WHERE id_consentimientoInformado = $id_consentimiento";
    $result = mysqli_query($mysqli, $query);
    $row = mysqli_fetch_assoc($result);
    
    if ($row) {
        // Eliminar archivo físico si existe
        if (!empty($row['archivo_consentimientoInformado'])) {
            $filePath = __DIR__ . '/../../uploads/consentimientos/' . $row['archivo_consentimientoInformado'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        
        // Eliminar registro de la base de datos
        $deleteQuery = "DELETE FROM consentimientoInformado WHERE id_consentimientoInformado = $id_consentimiento";
        
        if (mysqli_query($mysqli, $deleteQuery)) {
            // Actualizar estado del estudiante
            $num_doc_est = $row['num_doc_est'];
            $updateQuery = "UPDATE estudiantes SET estado_consentimientoInformado = 0 WHERE num_doc_est = '$num_doc_est'";
            mysqli_query($mysqli, $updateQuery);
            
            header("Location: checkConsentimientoInformado.php?msg=success");
        } else {
            header("Location: checkConsentimientoInformado.php?msg=error");
        }
    } else {
        header("Location: checkConsentimientoInformado.php?msg=notfound");
    }
} else {
    header("Location: checkConsentimientoInformado.php");
}

exit;
?>
