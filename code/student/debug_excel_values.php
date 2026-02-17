<?php
/**
 * Script de debug para ver qué valores guarda Excel en las celdas
 */

require_once(__DIR__ . '/../uploadData/ExcelFormHelper.php');

use PhpOffice\PhpSpreadsheet\IOFactory;

// Ruta del último Excel subido (ajustar según sea necesario)
echo "<h2>Debug Excel Values</h2>";
echo "<p>Sube un archivo Excel para ver qué valores exactos contiene:</p>";

if (isset($_FILES['excelFile'])) {
    try {
        $spreadsheet = IOFactory::load($_FILES['excelFile']['tmp_name']);
        $sheet = $spreadsheet->getActiveSheet();
        
        // Leer fila 4 (primera fila de datos)
        $row = 4;
        $col = 0;
        
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Columna</th><th>Valor Crudo</th><th>Tipo</th><th>Valor Procesado</th></tr>";
        
        $config = include(__DIR__ . '/uploadHealthFamily_config.php');
        
        foreach ($config['fields'] as $index => $field) {
            $colLetter = chr(65 + $index);
            $cellValue = $sheet->getCell($colLetter . $row)->getValue();
            $type = gettype($cellValue);
            
            // Simular procesamiento
            $processed = $cellValue;
            
            // Convertir índice a valor
            if ($field['type'] === 'select' && isset($field['options']) && !empty($processed)) {
                if (is_numeric($processed) && isset($field['options'][$processed])) {
                    $processed = $field['options'][$processed] . " (convertido desde índice $cellValue)";
                }
            }
            
            // Normalizar
            if (!empty($cellValue) && isset($field['normalize'])) {
                $original = $processed;
                switch ($field['normalize']) {
                    case 'lowercase':
                        $processed = strtolower(trim($processed));
                        $processed .= " (lowercase aplicado a: $original)";
                        break;
                    case 'titlecase':
                        $processed = ucwords(strtolower(trim($processed)));
                        $processed .= " (titlecase aplicado a: $original)";
                        break;
                }
            }
            
            echo "<tr>";
            echo "<td><strong>{$field['label']}</strong></td>";
            echo "<td>" . htmlspecialchars($cellValue) . "</td>";
            echo "<td>$type</td>";
            echo "<td>" . htmlspecialchars($processed) . "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
        
    } catch (Exception $e) {
        echo "<p style='color:red'>Error: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<form method='POST' enctype='multipart/form-data'>";
    echo "<input type='file' name='excelFile' required>";
    echo "<button type='submit'>Analizar Excel</button>";
    echo "</form>";
}
?>
