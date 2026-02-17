<?php
session_start();

if (!isset($_SESSION['id'])) {
    echo "Error: Debes iniciar sesión primero\n";
    exit;
}

$cod_dane_ie = $_SESSION['cod_dane_ie'];

include("conexion.php");

echo "====== ESTUDIANTES QUE DEBERÍAN APARECER EN LOS MENÚS DE ENCUESTAS ======\n\n";
echo "Tu institución (cod_dane_ie): $cod_dane_ie\n\n";

// 1. Estudiantes para entornoHogar (estado_entornohogar = 0)
echo "=== 1. ENTORNO DEL HOGAR (estado_entornohogar = 0) ===\n";
$queryHogar = "SELECT estudiantes.num_doc_est, estudiantes.nom_ape_est, estudiantes.grado_est, 
               estudiantes.estado_entornohogar, estudiantes.estado_est
          FROM estudiantes 
          INNER JOIN ieSede ON estudiantes.cod_dane_ieSede=ieSede.cod_dane_ieSede 
          INNER JOIN ie ON ieSede.cod_dane_ie=ie.cod_dane_ie 
          WHERE ie.cod_dane_ie = $cod_dane_ie 
          AND estudiantes.estado_entornohogar = 0
          LIMIT 20";
$resHogar = $mysqli->query($queryHogar);
if ($resHogar && $resHogar->num_rows > 0) {
    echo "Total encontrados: " . $resHogar->num_rows . " (mostrando max 20)\n";
    while ($row = $resHogar->fetch_assoc()) {
        $marca = ($row['num_doc_est'] == '1088031555') ? " <<<< ESTE ES" : "";
        echo "  - " . $row['num_doc_est'] . " | " . $row['nom_ape_est'] . " | Grado: " . $row['grado_est'] . 
             " | estado_est: " . $row['estado_est'] . $marca . "\n";
    }
} else {
    echo "✗ NO hay estudiantes para esta encuesta\n";
}
echo "\n";

// 2. Estudiantes para showEducation (estado_educacion = 0)
echo "=== 2. EDUCACIÓN (estado_educacion = 0) ===\n";
$queryEdu = "SELECT estudiantes.num_doc_est, estudiantes.nom_ape_est, estudiantes.grado_est, 
             estudiantes.estado_educacion, estudiantes.estado_est
          FROM estudiantes 
          INNER JOIN ieSede ON estudiantes.cod_dane_ieSede=ieSede.cod_dane_ieSede 
          INNER JOIN ie ON ieSede.cod_dane_ie=ie.cod_dane_ie 
          WHERE ie.cod_dane_ie = $cod_dane_ie 
          AND estudiantes.estado_educacion = 0
          LIMIT 20";
$resEdu = $mysqli->query($queryEdu);
if ($resEdu && $resEdu->num_rows > 0) {
    echo "Total encontrados: " . $resEdu->num_rows . " (mostrando max 20)\n";
    while ($row = $resEdu->fetch_assoc()) {
        $marca = ($row['num_doc_est'] == '1088031555') ? " <<<< ESTE ES" : "";
        echo "  - " . $row['num_doc_est'] . " | " . $row['nom_ape_est'] . " | Grado: " . $row['grado_est'] . 
             " | estado_est: " . $row['estado_est'] . $marca . "\n";
    }
} else {
    echo "✗ NO hay estudiantes para esta encuesta\n";
}
echo "\n";

// 3. Estudiantes activos en showsimat (estado_est = 1)
echo "=== 3. SIMAT - ACTIVOS (estado_est = 1) ===\n";
$querySimat = "SELECT estudiantes.num_doc_est, estudiantes.nom_ape_est, estudiantes.grado_est, 
              estudiantes.estado_est
          FROM estudiantes 
          INNER JOIN ieSede ON estudiantes.cod_dane_ieSede=ieSede.cod_dane_ieSede 
          INNER JOIN ie ON ieSede.cod_dane_ie=ie.cod_dane_ie 
          WHERE ie.cod_dane_ie = $cod_dane_ie 
          AND estudiantes.estado_est = 1
          LIMIT 20";
$resSimat = $mysqli->query($querySimat);
if ($resSimat && $resSimat->num_rows > 0) {
    echo "Total encontrados: " . $resSimat->num_rows . " (mostrando max 20)\n";
    while ($row = $resSimat->fetch_assoc()) {
        $marca = ($row['num_doc_est'] == '1088031555') ? " <<<< ESTE ES" : "";
        echo "  - " . $row['num_doc_est'] . " | " . $row['nom_ape_est'] . " | Grado: " . $row['grado_est'] . $marca . "\n";
    }
} else {
    echo "✗ NO hay estudiantes activos\n";
}
echo "\n";

// 4. Buscar específicamente el 1088031555 sin filtro de institución
echo "=== 4. BÚSQUEDA ESPECÍFICA 1088031555 (sin filtro de institución) ===\n";
$query1055_direct = "SELECT estudiantes.*, ieSede.cod_dane_ie as ie_del_estudiante
                     FROM estudiantes 
                     LEFT JOIN ieSede ON estudiantes.cod_dane_ieSede=ieSede.cod_dane_ieSede 
                     WHERE estudiantes.num_doc_est = '1088031555'";
$res1055_direct = $mysqli->query($query1055_direct);
if ($res1055_direct && $row = $res1055_direct->fetch_assoc()) {
    echo "Encontrado:\n";
    echo "  - Documento: " . $row['num_doc_est'] . "\n";
    echo "  - Nombre: " . $row['nom_ape_est'] . "\n";
    echo "  - Grado: " . $row['grado_est'] . "\n";
    echo "  - cod_dane_ieSede: " . $row['cod_dane_ieSede'] . "\n";
    echo "  - cod_dane_ie de su institución: " . $row['ie_del_estudiante'] . "\n";
    echo "  - Tu cod_dane_ie de sesión: $cod_dane_ie\n";
    echo "  - estado_est: " . $row['estado_est'] . "\n";
    echo "  - estado_entornohogar: " . $row['estado_entornohogar'] . "\n";
    echo "  - estado_educacion: " . $row['estado_educacion'] . "\n";
    
    if ($row['ie_del_estudiante'] == $cod_dane_ie) {
        echo "\n✓ El estudiante PERTENECE a tu institución\n";
    } else {
        echo "\n✗ PROBLEMA: El estudiante NO pertenece a tu institución\n";
        echo "  Necesitas cambiar de usuario o actualizar el cod_dane_ieSede del estudiante\n";
    }
} else {
    echo "✗ Estudiante 1088031555 NO encontrado en la BD\n";
}

$mysqli->close();
