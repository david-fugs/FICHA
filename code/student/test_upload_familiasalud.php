<?php
/**
 * Script de prueba para simular subida de Excel de Familia y Salud
 * Genera un archivo Excel con datos de prueba y lo procesa
 */

require_once(__DIR__ . '/../uploadData/ExcelFormHelper.php');
require_once(__DIR__ . '/../../conexion.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Crear un Excel de prueba
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// ENCABEZADOS (fila 3)
$headers = [
    'Código estudiante',
    'Tipo documento',
    'Número documento estudiante',
    'Nombres estudiante',
    'Apellidos estudiante',
    'Grupo',
    'Jornada',
    'Con quien vive',
    'Parentesco',
    'Tipo documento acudiente',
    'Documento acudiente',
    'Nombres acudiente',
    'Apellidos acudiente',
    'Teléfono',
    'Relaciones familiares',
    'Afecta aprendizaje',
    'Descripción afectación',
    'EPS',
    'Frecuencia uso medicamentos',
    'Medicamentos uso frecuente',
    'Frecuencia sistema salud',
    'Motivo',
    'Antecedentes médicos',
    'Descripción antecedentes',
    'Valoraciones medicas',
    'Fecha valoraciones',
    'Diagnóstico',
    'Recomendaciones médicas',
    'Beneficiario PAE',
    'Alergias',
    'Descripción alergias'
];

// Fila 3: encabezados
$col = 0;
foreach ($headers as $header) {
    $sheet->setCellValueByColumnAndRow($col + 1, 3, $header);
    $col++;
}

// Datos de prueba (fila 4)
$testData = [
    '2023001',  // código
    'RC',       // tipo doc
    '1234567890',  // num doc
    'JUAN',     // nombres
    'PEREZ',    // apellidos
    '5-A',      // grupo
    'MAÑANA',   // jornada
    'padres',   // con quien vive
    'madre',    // parentesco
    'CC',       // tipo doc acudiente
    '987654321', // doc acudiente
    'MARIA',    // nombres acudiente
    'LOPEZ',    // apellidos acudiente
    '3001234567', // teléfono
    'satisfactorias',  // relaciones familiares
    'NO',       // afecta aprendizaje
    '',         // descripción afectación
    'Sura',     // EPS
    'Ocasionalmente',  // frecuencia medicamentos
    '',         // medicamentos
    'Ocasionalmente',  // frecuencia sistema salud
    '',         // motivo
    'NO',       // antecedentes
    '',         // descripción antecedentes
    'NO',       // valoraciones
    '',         // fecha valoraciones
    '',         // diagnóstico
    '',         // recomendaciones
    'SI',       // beneficiario PAE
    'NO',       // alergias
    ''          // descripción alergias
];

// Fila 4: datos de prueba
$col = 0;
foreach ($testData as $value) {
    $sheet->setCellValueByColumnAndRow($col + 1, 4, $value);
    $col++;
}

// Guardar archivo temporal
$tempFile = sys_get_temp_dir() . '/test_familiasalud_' . time() . '.xlsx';
$writer = new Xlsx($spreadsheet);
$writer->save($tempFile);

echo "<h2>Archivo de prueba generado: " . basename($tempFile) . "</h2>";
echo "<p>Ubicación: $tempFile</p>";

// Ahora simular la subida procesándolo
$_FILES['excelFile'] = [
    'name' => basename($tempFile),
    'tmp_name' => $tempFile,
    'error' => 0,
    'size' => filesize($tempFile)
];

echo "<h3>Procesando archivo...</h3>";

// Configuración (copiada de uploadHealthFamily.php)
$config = [
    'table' => 'familiasalud',
    'idField' => 'id_salud_familiaSalud',
    'fields' => [
        ['name' => 'codigo_estudiante', 'label' => 'Código estudiante', 'type' => 'text', 'readonly' => true, 'preloadField' => 'codigo_estudiante'],
        ['name' => 'id_tipodoc_estudiante', 'label' => 'Tipo documento', 'type' => 'select', 'options' => ['TI' => 'TI', 'CC' => 'CC', 'RC' => 'RC', 'CE' => 'CE'], 'readonly' => true, 'preloadField' => 'TipDoc'],
        ['name' => 'numdoc_estudiante', 'label' => 'Número documento estudiante', 'type' => 'text', 'readonly' => true, 'preloadField' => 'numdoc_estudiante'],
        ['name' => 'nombre_estudiante', 'label' => 'Nombres estudiante', 'type' => 'text', 'readonly' => true, 'preloadField' => 'nombre_estudiante'],
        ['name' => 'apellido_estudiante', 'label' => 'Apellidos estudiante', 'type' => 'text', 'readonly' => true, 'preloadField' => 'apellido_estudiante'],
        ['name' => 'grupo_estudiante', 'label' => 'Grupo', 'type' => 'text', 'readonly' => true, 'preloadField' => 'Grupo'],
        ['name' => 'jornada_estudiante', 'label' => 'Jornada', 'type' => 'text', 'readonly' => true, 'preloadField' => 'Jornada'],
        ['name' => 'con_quien_vive_familiaSalud', 'label' => 'Con quien vive', 'type' => 'select', 'required' => true, 'options' => ['1' => 'padres', '2' => 'solo madre', '3' => 'solo padre', '4' => 'abuelos', '5' => 'tios', '6' => 'hermanos', '7' => 'otros familiares', '8' => 'familia adoptiva', '9' => 'familia de acogida', '10' => 'hogar sustituto', '11' => 'solo'], 'normalize' => 'lowercase'],
        ['name' => 'parentezco_familiaSalud', 'label' => 'Parentesco', 'type' => 'select', 'required' => true, 'options' => ['1' => 'madre', '2' => 'padre', '3' => 'abuelo', '4' => 'abuela', '5' => 'tio', '6' => 'tia', '7' => 'hermano', '8' => 'hermana', '9' => 'primo', '10' => 'prima', '11' => 'otro'], 'normalize' => 'lowercase'],
        ['name' => 'id_tipodoc_acudiente', 'label' => 'Tipo documento acudiente', 'type' => 'select', 'required' => true, 'options' => ['TI' => 'TI', 'CC' => 'CC', 'CE' => 'CE']],
        ['name' => 'numdoc_acudiente_familiaSalud', 'label' => 'Documento acudiente', 'type' => 'text', 'required' => true],
        ['name' => 'nombre_acudiente_familiaSalud', 'label' => 'Nombres acudiente', 'type' => 'text', 'required' => true],
        ['name' => 'apellido_acudiente_familiaSalud', 'label' => 'Apellidos acudiente', 'type' => 'text', 'required' => true],
        ['name' => 'telefono_familiaSalud', 'label' => 'Teléfono', 'type' => 'text', 'required' => true],
        ['name' => 'relaciones_familiares_familiaSalud', 'label' => 'Relaciones familiares', 'type' => 'select', 'required' => true, 'options' => ['1' => 'satisfactorias', '2' => 'llevaderas', '3' => 'conflictivas', '4' => 'Violencia Intrafamiliar'], 'normalize' => 'lowercase'],
        ['name' => 'afecta_aprendizaje_familiaSalud', 'label' => 'Afecta aprendizaje', 'type' => 'select', 'required' => true, 'options' => ['1' => 'SI', '0' => 'NO']],
        ['name' => 'descripcion_afectacion_familiaSalud', 'label' => 'Descripción afectación', 'type' => 'text'],
        ['name' => 'eps_familiaSalud', 'label' => 'EPS', 'type' => 'select', 'required' => true, 'options' => ['1' => 'Sura', '2' => 'Nueva Eps', '3' => 'Sanitas', '4' => 'Salud Total', '5' => 'Coomeva', '6' => 'Compensar', '7' => 'Cafesalud', '8' => 'Famisanar', '9' => 'Aliansalud', '10' => 'Saludcoop', '11' => 'Cruz Blanca', '12' => 'Colsanitas', '13' => 'Otra'], 'normalize' => 'titlecase'],
        ['name' => 'frecuencia_medicamento_familiaSalud', 'label' => 'Frecuencia uso medicamentos', 'type' => 'select', 'required' => true, 'options' => ['1' => 'Nunca', '2' => 'Ocasionalmente', '3' => 'Frecuentemente', '4' => 'Permanentemente'], 'normalize' => 'titlecase'],
        ['name' => 'medicamento_familiaSalud', 'label' => 'Medicamentos uso frecuente', 'type' => 'text'],
        ['name' => 'frecuencia_sist_salud_familiaSalud', 'label' => 'Frecuencia sistema salud', 'type' => 'select', 'required' => true, 'options' => ['1' => 'Nunca', '2' => 'Ocasionalmente', '3' => 'Frecuentemente', '4' => 'Permanentemente'], 'normalize' => 'titlecase'],
        ['name' => 'motivo_familiaSalud', 'label' => 'Motivo', 'type' => 'text'],
        ['name' => 'antecedentes_familiaSalud', 'label' => 'Antecedentes médicos', 'type' => 'select', 'required' => true, 'options' => ['1' => 'SI', '0' => 'NO']],
        ['name' => 'descripcion_antecedentes_familiaSalud', 'label' => 'Descripción antecedentes', 'type' => 'text'],
        ['name' => 'valoracion_familiaSalud', 'label' => 'Valoraciones medicas', 'type' => 'select', 'required' => true, 'options' => ['1' => 'SI', '0' => 'NO']],
        ['name' => 'fecha_valoracion_familiaSalud', 'label' => 'Fecha valoraciones', 'type' => 'date'],
        ['name' => 'diagnostico_familiaSalud', 'label' => 'Diagnóstico', 'type' => 'text'],
        ['name' => 'recomendaciones_familiaSalud', 'label' => 'Recomendaciones médicas', 'type' => 'text'],
        ['name' => 'beneficiario_pae', 'label' => 'Beneficiario PAE', 'type' => 'select', 'required' => true, 'options' => ['1' => 'SI', '0' => 'NO']],
        ['name' => 'alergia_familiaSalud', 'label' => 'Alergias', 'type' => 'select', 'required' => true, 'options' => ['1' => 'SI', '0' => 'NO']],
        ['name' => 'descripcion_alergia_familiaSalud', 'label' => 'Descripción alergias', 'type' => 'text']
    ],
    'autoFields' => [
        'sede_estudiante' => 1,
        'usuario_dig_familiaSalud' => 1
    ]
];

try {
    $helper = new ExcelFormHelper($mysqli);
    $result = $helper->processUpload($config, $_FILES['excelFile']);
    
    echo "<div style='margin: 20px; padding: 15px; background: #d4edda; border: 1px solid #c3e6cb; border-radius: 5px;'>";
    echo "<h3 style='color: #155724;'>✓ Resultado del procesamiento:</h3>";
    echo "<p><strong>Registros insertados:</strong> " . $result['inserted'] . "</p>";
    
    if (!empty($result['errors'])) {
        echo "<h4 style='color: #721c24;'>Errores encontrados (" . count($result['errors']) . "):</h4>";
        echo "<ul>";
        foreach ($result['errors'] as $error) {
            echo "<li style='color: #721c24;'>$error</li>";
        }
        echo "</ul>";
    }
    echo "</div>";
    
    // Mostrar datos insertados
    if ($result['inserted'] > 0) {
        $query = "SELECT * FROM softepuc_fie.familiasalud ORDER BY id_salud_familiaSalud DESC LIMIT 1";
        $res = $mysqli->query($query);
        if ($row = $res->fetch_assoc()) {
            echo "<div style='margin: 20px; padding: 15px; background: #d1ecf1; border: 1px solid #bee5eb; border-radius: 5px;'>";
            echo "<h3>Datos insertados en la BD:</h3>";
            echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
            foreach ($row as $key => $value) {
                echo "<tr><td><strong>$key</strong></td><td>$value</td></tr>";
            }
            echo "</table>";
            echo "</div>";
        }
    }
    
} catch (Exception $e) {
    echo "<div style='margin: 20px; padding: 15px; background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 5px;'>";
    echo "<h3 style='color: #721c24;'>✗ Error:</h3>";
    echo "<p style='color: #721c24;'>" . $e->getMessage() . "</p>";
    echo "</div>";
}

// Limpiar archivo temporal
if (file_exists($tempFile)) {
    unlink($tempFile);
    echo "<p style='color: #666;'><em>Archivo temporal eliminado.</em></p>";
}
?>
