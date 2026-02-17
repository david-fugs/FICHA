<?php
include 'conexion.php';

echo "====== DIAGNÓSTICO COMPLETO DEL ESTUDIANTE 1088031555 ======\n\n";

// 1. Verificar si existe
$result = $mysqli->query("SELECT * FROM estudiantes WHERE num_doc_est = '1088031555'");
if ($result && $row = $result->fetch_assoc()) {
    echo "✓ El estudiante EXISTE en la tabla estudiantes\n";
    echo "  - Nombre: " . $row['nom_ape_est'] . "\n";
    echo "  - Grado: " . $row['grado_est'] . "\n";
    echo "  - Nombre Grado: " . $row['nom_grado_est'] . "\n";
    echo "  - cod_dane_ieSede: " . $row['cod_dane_ieSede'] . "\n";
    echo "  - estado_est: " . $row['estado_est'] . " (1=activo, 0=inactivo)\n";
    echo "  - estado_entornohogar: " . $row['estado_entornohogar'] . " (0=pendiente, 1=realizada)\n";
    echo "  - estado_educacion: " . $row['estado_educacion'] . "\n";
    echo "  - estado_prepostnatales: " . $row['estado_prepostnatales'] . "\n";
    echo "\n";
    
    $cod_dane_ieSede = $row['cod_dane_ieSede'];
    
    // 2. Verificar si existe en ieSede
    $result2 = $mysqli->query("SELECT * FROM ieSede WHERE cod_dane_ieSede = '$cod_dane_ieSede'");
    if ($result2 && $row2 = $result2->fetch_assoc()) {
        echo "✓ El cod_dane_ieSede EXISTE en la tabla ieSede\n";
        echo "  - cod_dane_ie: " . $row2['cod_dane_ie'] . "\n";
        echo "\n";
        
        $cod_dane_ie = $row2['cod_dane_ie'];
        
        // 3. Verificar si existe en ie
        $result3 = $mysqli->query("SELECT * FROM ie WHERE cod_dane_ie = '$cod_dane_ie'");
        if ($result3 && $row3 = $result3->fetch_assoc()) {
            echo "✓ El cod_dane_ie EXISTE en la tabla ie\n";
            echo "  - nombre_ie: " . $row3['nombre_ie'] . "\n";
            echo "\n";
        } else {
            echo "✗ ERROR: El cod_dane_ie '$cod_dane_ie' NO EXISTE en la tabla 'ie'\n";
            echo "  (El JOIN con ie fallará)\n\n";
        }
    } else {
        echo "✗ ERROR: El cod_dane_ieSede '$cod_dane_ieSede' NO EXISTE en la tabla 'ieSede'\n";
        echo "  (Los JOINs con ieSede e ie fallarán)\n\n";
    }
    
} else {
    echo "✗ ERROR: El estudiante NO EXISTE en la tabla estudiantes\n\n";
}

// 4. Probar la consulta exacta de showentornoHogar.php
echo "====== PRUEBA DE CONSULTA showentornoHogar.php ======\n";
$queryHogar = "SELECT estudiantes.num_doc_est, estudiantes.nom_ape_est, estudiantes.grado_est, 
                estudiantes.estado_entornohogar, estudiantes.estado_est, ie.cod_dane_ie
          FROM estudiantes 
          INNER JOIN ieSede ON estudiantes.cod_dane_ieSede=ieSede.cod_dane_ieSede 
          INNER JOIN ie ON ieSede.cod_dane_ie=ie.cod_dane_ie 
          WHERE estudiantes.num_doc_est = '1088031555'";
$resHogar = $mysqli->query($queryHogar);
if ($resHogar && $resHogar->num_rows > 0) {
    $row = $resHogar->fetch_assoc();
    echo "✓ Los JOINs funcionan correctamente\n";
    echo "  - cod_dane_ie desde sesión necesario: " . $row['cod_dane_ie'] . "\n";
    echo "  - estado_entornohogar: " . $row['estado_entornohogar'] . "\n";
    if ($row['estado_entornohogar'] == 0) {
        echo "  ✓ estado_entornohogar = 0 (debería aparecer en el listado)\n";
    } else {
        echo "  ✗ estado_entornohogar != 0 (NO aparecerá en el listado)\n";
    }
} else {
    echo "✗ Los JOINs FALLAN - el estudiante no puede aparecer\n";
    echo "  Error: " . $mysqli->error . "\n";
}
echo "\n";

// 5. Probar la consulta exacta de showsimat.php
echo "====== PRUEBA DE CONSULTA showsimat.php ======\n";
$querySimat = "SELECT estudiantes.num_doc_est, estudiantes.nom_ape_est, estudiantes.estado_est, ie.cod_dane_ie
         FROM estudiantes 
         INNER JOIN ieSede ON estudiantes.cod_dane_ieSede=ieSede.cod_dane_ieSede 
         INNER JOIN ie ON ieSede.cod_dane_ie=ie.cod_dane_ie 
         WHERE estudiantes.num_doc_est = '1088031555'";
$resSimat = $mysqli->query($querySimat);
if ($resSimat && $resSimat->num_rows > 0) {
    $row = $resSimat->fetch_assoc();
    echo "✓ Los JOINs funcionan correctamente\n";
    echo "  - cod_dane_ie desde sesión necesario: " . $row['cod_dane_ie'] . "\n";
    echo "  - estado_est: " . $row['estado_est'] . "\n";
    if ($row['estado_est'] == 1) {
        echo "  ✓ estado_est = 1 (debería aparecer en el listado de activos)\n";
    } else {
        echo "  ✗ estado_est != 1 (NO aparecerá en el listado de activos)\n";
    }
} else {
    echo "✗ Los JOINs FALLAN - el estudiante no puede aparecer\n";
    echo "  Error: " . $mysqli->error . "\n";
}

// 6. Mostrar todos los registros de ieSede para chequear
echo "\n====== TODOS LOS REGISTROS EN ieSede ======\n";
$allSedes = $mysqli->query("SELECT cod_dane_ieSede, cod_dane_ie, nombre_sede FROM ieSede");
if ($allSedes) {
    while ($sede = $allSedes->fetch_assoc()) {
        echo "  - cod_dane_ieSede: " . $sede['cod_dane_ieSede'] . " -> cod_dane_ie: " . $sede['cod_dane_ie'] . " (" . $sede['nombre_sede'] . ")\n";
    }
}

$mysqli->close();
