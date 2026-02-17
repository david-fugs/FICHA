<?php
session_start();

echo "====== VERIFICACIÓN DE SESIÓN DEL USUARIO ======\n\n";

if (!isset($_SESSION['id'])) {
    echo "✗ NO HAY SESIÓN ACTIVA\n";
    echo "  Debes iniciar sesión primero\n";
    exit;
}

echo "✓ Sesión activa detectada\n\n";
echo "Datos de la sesión:\n";
echo "  - Usuario ID: " . $_SESSION['id'] . "\n";
echo "  - Usuario: " . $_SESSION['usuario'] . "\n";
echo "  - Nombre: " . $_SESSION['nombre'] . "\n";
echo "  - Tipo: " . $_SESSION['tipo_usuario'] . "\n";
echo "  - cod_dane_ie: " . $_SESSION['cod_dane_ie'] . "\n\n";

include("conexion.php");

// Verificar qué institución corresponde a ese código DANE
$cod_dane_ie = $_SESSION['cod_dane_ie'];
$result = $mysqli->query("SELECT * FROM ie WHERE cod_dane_ie = '$cod_dane_ie'");
if ($result && $row = $result->fetch_assoc()) {
    echo "✓ Tu institución:\n";
    echo "  - Código DANE: " . $row['cod_dane_ie'] . "\n";
    echo "  - Nombre: " . $row['nombre_ie'] . "\n\n";
} else {
    echo "✗ ERROR: Tu cod_dane_ie no corresponde a ninguna institución en la BD\n\n";
}

// Contar estudiantes de tu institución
$query = "SELECT COUNT(*) as total FROM estudiantes 
          INNER JOIN ieSede ON estudiantes.cod_dane_ieSede=ieSede.cod_dane_ieSede 
          INNER JOIN ie ON ieSede.cod_dane_ie=ie.cod_dane_ie 
          WHERE ie.cod_dane_ie = $cod_dane_ie";
$result = $mysqli->query($query);
if ($result && $row = $result->fetch_assoc()) {
    echo "Total estudiantes en tu institución: " . $row['total'] . "\n\n";
}

// Verificar si el estudiante 1088031555 pertenece a tu institución
$query1055 = "SELECT estudiantes.num_doc_est, estudiantes.nom_ape_est, ie.cod_dane_ie, ie.nombre_ie
              FROM estudiantes 
              INNER JOIN ieSede ON estudiantes.cod_dane_ieSede=ieSede.cod_dane_ieSede 
              INNER JOIN ie ON ieSede.cod_dane_ie=ie.cod_dane_ie 
              WHERE estudiantes.num_doc_est = '1088031555'";
$res1055 = $mysqli->query($query1055);
if ($res1055 && $row1055 = $res1055->fetch_assoc()) {
    echo "====== ESTUDIANTE 1088031555 ======\n";
    echo "  - Nombre: " . $row1055['nom_ape_est'] . "\n";
    echo "  - Institución: " . $row1055['nombre_ie'] . "\n";
    echo "  - cod_dane_ie: " . $row1055['cod_dane_ie'] . "\n\n";
    
    if ($row1055['cod_dane_ie'] == $cod_dane_ie) {
        echo "✓ El estudiante 1088031555 PERTENECE a tu institución\n";
        echo "  Debería aparecer en los listados\n\n";
    } else {
        echo "✗ El estudiante 1088031555 NO PERTENECE a tu institución\n";
        echo "  Tu cod_dane_ie: " . $cod_dane_ie . "\n";
        echo "  Su cod_dane_ie: " . $row1055['cod_dane_ie'] . "\n";
        echo "  Por eso NO aparece en los listados\n\n";
    }
}

echo "\n====== SOLUCIÓN ======\n";
echo "Si el estudiante no aparece:\n";
echo "1. Verifica que hayas iniciado sesión con el usuario correcto\n";
echo "2. El usuario debe estar asociado a la institución correcta\n";
echo "3. O actualiza el cod_dane_ieSede del estudiante para que coincida con tu institución\n";

$mysqli->close();
