<?php
/**
 * Script para verificar qué valores tiene la hoja DATOS_SISTEMA en el Excel generado
 */

require_once(__DIR__ . '/../uploadData/ExcelFormHelper.php');
require_once(__DIR__ . '/../../conexion.php');

use PhpOffice\PhpSpreadsheet\IOFactory;

// Generar un Excel de prueba
$config = [
    'tableName' => 'familiasalud',
    'fields' => [
        [
            'name' => 'relacion_madre_familiaSalud',
            'label' => 'RELACIÓN CON MADRE',
            'type' => 'select',
            'required' => true,
            'options' => ['satisfactorias', 'llevaderas', 'tensionadas', 'hostiles', 'conflictivas'],
            'normalize' => 'lowercase'
        ],
        [
            'name' => 'nombre_eps_familiaSalud',
            'label' => 'NOMBRE DE LA EPS',
            'type' => 'select',
            'required' => false,
            'options' => ['Nueva eps', 'Salud Total', 'Coosalud', 'Sura', 'Sanitas'],
            'normalize' => 'titlecase'
        ],
    ]
];

$helper = new ExcelFormHelper($mysqli);
$filePath = $helper->generateTemplate($config, [], 'test_verificacion.xlsx');

echo "<h2>Verificando Excel generado</h2>";
echo "<p>Archivo: $filePath</p>";

// Leer el archivo generado
$spreadsheet = IOFactory::load($filePath);

// Verificar hoja DATOS_SISTEMA
$dataSheet = $spreadsheet->getSheetByName('DATOS_SISTEMA');

if ($dataSheet) {
    echo "<h3>Contenido de hoja DATOS_SISTEMA:</h3>";
    echo "<table border='1' cellpadding='5'>";
    
    // Leer columna A (relacion_madre)
    echo "<tr><th colspan='2'>Columna A - RELACIÓN CON MADRE</th></tr>";
    for ($row = 1; $row <= 10; $row++) {
        $value = $dataSheet->getCell('A' . $row)->getValue();
        if (!empty($value)) {
            echo "<tr><td>A$row</td><td>" . htmlspecialchars($value) . "</td></tr>";
        }
    }
    
    // Leer columna B (nombre_eps)
    echo "<tr><th colspan='2'>Columna B - NOMBRE DE LA EPS</th></tr>";
    for ($row = 1; $row <= 10; $row++) {
        $value = $dataSheet->getCell('B' . $row)->getValue();
        if (!empty($value)) {
            echo "<tr><td>B$row</td><td>" . htmlspecialchars($value) . "</td></tr>";
        }
    }
    
    echo "</table>";
} else {
    echo "<p style='color:red'>No se encontró la hoja DATOS_SISTEMA</p>";
}

// Verificar la hoja principal
$sheet = $spreadsheet->getSheet(0);
echo "<h3>Validación en hoja principal:</h3>";
echo "<p>Celda A4: ";
$validation = $sheet->getCell('A4')->getDataValidation();
if ($validation && $validation->getType() != 'none') {
    echo "Formula: " . $validation->getFormula1();
} else {
    echo "Sin validación";
}
echo "</p>";

echo "<p>Celda B4: ";
$validation = $sheet->getCell('B4')->getDataValidation();
if ($validation && $validation->getType() != 'none') {
    echo "Formula: " . $validation->getFormula1();
} else {
    echo "Sin validación";
}
echo "</p>";

// Limpiar
unlink($filePath);
?>
