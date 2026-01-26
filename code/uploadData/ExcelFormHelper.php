<?php
/**
 * ExcelFormHelper - Clase reutilizable para generar plantillas Excel y procesar cargas masivas
 * 
 * Esta clase permite de forma escalable:
 * 1. Generar plantillas Excel con formato y validaciones
 * 2. Procesar archivos Excel y cargarlos a la BD
 * 3. Reutilizar para múltiples formularios
 * 
 * @author Sistema FICHA
 * @version 1.0
 */

require_once(__DIR__ . '/../../vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ExcelFormHelper
{
    private $mysqli;
    private $config;

    /**
     * Constructor
     * @param mysqli $mysqli Conexión a la base de datos
     */
    public function __construct($mysqli)
    {
        $this->mysqli = $mysqli;
        $this->mysqli->set_charset('utf8');
    }

    /**
     * Genera una plantilla Excel para un formulario específico
     * 
     * @param array $config Configuración del formulario
     * Estructura del config:
     * [
     *   'formName' => 'Nombre del formulario',
     *   'fileName' => 'nombre_archivo.xlsx',
     *   'fields' => [
     *     ['name' => 'campo1', 'label' => 'Etiqueta 1', 'type' => 'text|select|number', 'required' => true|false, 'options' => [...], 'preload' => true|false],
     *     ...
     *   ],
     *   'preloadQuery' => 'SELECT ...' // Query para precargar datos de estudiantes
     * ]
     * 
     * @param array $filters Filtros opcionales para precargar datos
     * @return string Nombre del archivo generado
     */
    public function generateTemplate($config, $filters = [])
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Configurar título
        $sheet->setTitle(substr($config['formName'], 0, 31)); // Max 31 caracteres

        // FILA 1: Instrucciones
        $sheet->setCellValue('A1', 'INSTRUCCIONES: Complete los datos de los estudiantes. Los campos marcados con * son obligatorios. No modifique ni elimine las cabeceras.');
        $sheet->mergeCells('A1:' . $this->getColumnLetter(count($config['fields']) - 1) . '1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]
        ]);
        $sheet->getRowDimension(1)->setRowHeight(30);

        // FILA 2: Espaciador
        $sheet->getRowDimension(2)->setRowHeight(5);

        // FILA 3: Cabeceras
        $col = 0;
        foreach ($config['fields'] as $field) {
            $label = $field['label'];
            if (isset($field['required']) && $field['required']) {
                $label = '* ' . $label;
            }
            
            $cellRef = $this->getColumnLetter($col) . '3';
            $sheet->setCellValue($cellRef, $label);
            
            // Estilo de cabecera
            $sheet->getStyle($cellRef)->applyFromArray([
                'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '70AD47']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
            ]);
            
            // Ajustar ancho de columna
            $sheet->getColumnDimension($this->getColumnLetter($col))->setWidth(20);
            
            $col++;
        }
        $sheet->getRowDimension(3)->setRowHeight(40);

        // Crear hoja de datos para listas desplegables
        $dataSheet = $spreadsheet->createSheet();
        $dataSheet->setTitle('DATOS_SISTEMA');
        $spreadsheet->setActiveSheetIndex(0);

        // FILAS 4+: Datos precargados o filas vacías para llenar
        $startRow = 4;
        
        if (isset($config['preloadQuery']) && !empty($config['preloadQuery'])) {
            // Precargar datos de estudiantes
            $preloadData = $this->getPreloadData($config['preloadQuery'], $filters);
            
            if (!empty($preloadData)) {
                $row = $startRow;
                foreach ($preloadData as $data) {
                    $col = 0;
                    foreach ($config['fields'] as $field) {
                        $cellRef = $this->getColumnLetter($col) . $row;
                        
                        // Cargar dato precargado si existe
                        $value = '';
                        if (isset($field['preloadField']) && isset($data[$field['preloadField']])) {
                            $value = $data[$field['preloadField']];
                        }
                        
                        $sheet->setCellValue($cellRef, $value);
                        
                        // Aplicar validaciones y estilos
                        $this->applyCellRules($sheet, $cellRef, $field, $dataSheet);
                        
                        $col++;
                    }
                    $row++;
                }
            }
        }
        
        // Agregar al menos 100 filas vacías para llenar manualmente
        for ($row = $startRow; $row < $startRow + 100; $row++) {
            $col = 0;
            foreach ($config['fields'] as $field) {
                $cellRef = $this->getColumnLetter($col) . $row;
                $this->applyCellRules($sheet, $cellRef, $field, $dataSheet);
                $col++;
            }
        }

        // Ocultar hoja de datos
        $dataSheet->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_HIDDEN);

        // Proteger cabeceras (opcional)
        // $sheet->getProtection()->setSheet(true);

        // Guardar archivo
        $fileName = $config['fileName'];
        $filePath = __DIR__ . '/temp/' . $fileName;
        
        // Crear directorio temp si no existe
        if (!file_exists(__DIR__ . '/temp/')) {
            mkdir(__DIR__ . '/temp/', 0777, true);
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);

        return $filePath;
    }

    /**
     * Aplica reglas de validación y estilo a una celda
     */
    private function applyCellRules($sheet, $cellRef, $field, $dataSheet)
    {
        // Aplicar bordes
        $sheet->getStyle($cellRef)->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]]
        ]);

        // Campos de solo lectura
        if (isset($field['readonly']) && $field['readonly']) {
            $sheet->getStyle($cellRef)->applyFromArray([
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E7E6E6']],
                'font' => ['color' => ['rgb' => '7F7F7F']]
            ]);
        }

        // Aplicar validación según tipo de campo
        if ($field['type'] === 'select' && isset($field['options'])) {
            $this->addDropdownValidation($sheet, $cellRef, $field['options'], $dataSheet, $field['name']);
        } elseif ($field['type'] === 'multiselect' && isset($field['options'])) {
            $this->addMultiSelectValidation($sheet, $cellRef, $field['options'], $dataSheet, $field['name']);
        } elseif ($field['type'] === 'number') {
            $this->addNumberValidation($sheet, $cellRef);
        } elseif ($field['type'] === 'date') {
            $this->addDateValidation($sheet, $cellRef);
        }

        // Marcar campos obligatorios con color
        if (isset($field['required']) && $field['required'] && !isset($field['readonly'])) {
            $sheet->getStyle($cellRef)->applyFromArray([
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFF2CC']]
            ]);
        }
    }

    /**
     * Agrega validación de lista desplegable
     */
    private function addDropdownValidation($sheet, $cellRef, $options, $dataSheet, $fieldName)
    {
        // Guardar opciones en hoja oculta
        static $dataSheetRow = 1;
        $dataSheetCol = ord('A');
        
        $dataSheet->setCellValue(chr($dataSheetCol) . $dataSheetRow, strtoupper($fieldName));
        $dataSheetRow++;
        
        $startRow = $dataSheetRow;
        foreach ($options as $option) {
            $dataSheet->setCellValue(chr($dataSheetCol) . $dataSheetRow, $option);
            $dataSheetRow++;
        }
        $endRow = $dataSheetRow - 1;

        // Crear validación
        $validation = $sheet->getCell($cellRef)->getDataValidation();
        $validation->setType(DataValidation::TYPE_LIST);
        $validation->setErrorStyle(DataValidation::STYLE_STOP);
        $validation->setAllowBlank(true);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setShowDropDown(true);
        $validation->setErrorTitle('Valor inválido');
        $validation->setError('Por favor seleccione un valor de la lista');
        $validation->setPromptTitle('Seleccione');
        $validation->setPrompt('Seleccione un valor de la lista desplegable');
        $validation->setFormula1('DATOS_SISTEMA!$' . chr($dataSheetCol) . '$' . $startRow . ':$' . chr($dataSheetCol) . '$' . $endRow);
        
        $dataSheetRow++; // Espaciador
    }

    /**
     * Agrega validación para campos de selección múltiple
     * Los valores se separan por comas
     */
    private function addMultiSelectValidation($sheet, $cellRef, $options, $dataSheet, $fieldName)
    {
        // Agregar comentario con instrucciones
        $comment = $sheet->getComment($cellRef);
        $comment->setAuthor('Sistema');
        
        $optionsList = implode("\n", $options);
        $commentText = "SELECCIÓN MÚLTIPLE\n\n";
        $commentText .= "Opciones disponibles:\n";
        $commentText .= $optionsList . "\n\n";
        $commentText .= "IMPORTANTE:\n";
        $commentText .= "- Separe cada opción con coma (,)\n";
        $commentText .= "- Ejemplo: Opción1,Opción2,Opción3\n";
        $commentText .= "- Respete mayúsculas y tildes";
        
        $comment->getText()->createTextRun($commentText);
        $comment->setWidth('300pt');
        $comment->setHeight('200pt');
        
        // Aplicar estilo especial para multiselect
        $sheet->getStyle($cellRef)->applyFromArray([
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E7F3FF']],
            'font' => ['color' => ['rgb' => '0066CC']]
        ]);
    }

    /**
     * Agrega validación de número
     */
    private function addNumberValidation($sheet, $cellRef)
    {
        $validation = $sheet->getCell($cellRef)->getDataValidation();
        $validation->setType(DataValidation::TYPE_WHOLE);
        $validation->setErrorStyle(DataValidation::STYLE_STOP);
        $validation->setAllowBlank(true);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setErrorTitle('Valor inválido');
        $validation->setError('Por favor ingrese un número entero');
        $validation->setPromptTitle('Número');
        $validation->setPrompt('Ingrese un número entero');
        $validation->setOperator(DataValidation::OPERATOR_GREATERTHANOREQUAL);
        $validation->setFormula1('0');
    }

    /**
     * Agrega validación de fecha
     */
    private function addDateValidation($sheet, $cellRef)
    {
        $validation = $sheet->getCell($cellRef)->getDataValidation();
        $validation->setType(DataValidation::TYPE_DATE);
        $validation->setErrorStyle(DataValidation::STYLE_STOP);
        $validation->setAllowBlank(true);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setErrorTitle('Fecha inválida');
        $validation->setError('Por favor ingrese una fecha válida (dd-mm-yyyy)');
        $validation->setPromptTitle('Fecha');
        $validation->setPrompt('Ingrese una fecha en formato dd-mm-yyyy');
    }

    /**
     * Obtiene datos para precargar en la plantilla
     */
    private function getPreloadData($query, $filters = [])
    {
        $result = $this->mysqli->query($query);
        $data = [];
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        
        return $data;
    }

    /**
     * Procesa un archivo Excel y carga los datos a la BD
     * 
     * @param string $filePath Ruta del archivo Excel
     * @param array $config Configuración del formulario (misma estructura que generateTemplate)
     * @param array $sessionData Datos de sesión (id_usu, nombre, tipo_usuario, etc.)
     * @return array Resultado del procesamiento ['success' => bool, 'message' => string, 'inserted' => int, 'errors' => array]
     */
    public function processUpload($filePath, $config, $sessionData)
    {
        $result = [
            'success' => false,
            'message' => '',
            'inserted' => 0,
            'errors' => []
        ];

        try {
            // Cargar archivo Excel
            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            
            // Obtener datos (desde fila 4, fila 3 son cabeceras)
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();
            
            $inserted = 0;
            $errors = [];

            for ($row = 4; $row <= $highestRow; $row++) {
                // Verificar si la fila está vacía
                $isEmpty = true;
                foreach ($config['fields'] as $index => $field) {
                    $cellValue = $sheet->getCell($this->getColumnLetter($index) . $row)->getValue();
                    if (!empty($cellValue)) {
                        $isEmpty = false;
                        break;
                    }
                }

                if ($isEmpty) {
                    continue; // Saltar filas vacías
                }

                // Procesar fila
                $rowData = [];
                $validationErrors = [];

                foreach ($config['fields'] as $index => $field) {
                    $cellValue = $sheet->getCell($this->getColumnLetter($index) . $row)->getValue();
                    
                    // Validar campos obligatorios (solo si no es skipInsert)
                    if (isset($field['required']) && $field['required'] && empty($cellValue) && !isset($field['skipInsert'])) {
                        $validationErrors[] = "Campo '{$field['label']}' es obligatorio";
                    }

                    // Validar opciones de select
                    if ($field['type'] === 'select' && !empty($cellValue) && isset($field['options'])) {
                        if (!in_array($cellValue, $field['options'])) {
                            $validationErrors[] = "Valor inválido en '{$field['label']}': {$cellValue}";
                        }
                    }

                    // Validar opciones de multiselect
                    if ($field['type'] === 'multiselect' && !empty($cellValue) && isset($field['options'])) {
                        $selectedValues = array_map('trim', explode(',', $cellValue));
                        foreach ($selectedValues as $value) {
                            if (!in_array($value, $field['options'])) {
                                $validationErrors[] = "Valor inválido en '{$field['label']}': {$value}";
                            }
                        }
                    }

                    // Solo agregar a rowData si NO es skipInsert o readonly
                    if (!isset($field['skipInsert']) && !isset($field['readonly'])) {
                        $rowData[$field['name']] = $cellValue;
                    }
                }

                // Si hay errores de validación, registrar y continuar
                if (!empty($validationErrors)) {
                    $errors[] = "Fila {$row}: " . implode(', ', $validationErrors);
                    continue;
                }

                // Insertar en BD
                try {
                    $insertResult = $this->insertRecord($config['tableName'], $rowData, $config, $sessionData);
                    if ($insertResult) {
                        $inserted++;
                    } else {
                        $errors[] = "Fila {$row}: Error al insertar en BD - " . $this->mysqli->error;
                    }
                } catch (Exception $e) {
                    $errors[] = "Fila {$row}: " . $e->getMessage();
                }
            }

            $result['success'] = $inserted > 0;
            $result['inserted'] = $inserted;
            $result['errors'] = $errors;
            
            if ($inserted > 0) {
                $result['message'] = "Se insertaron {$inserted} registros correctamente.";
                if (!empty($errors)) {
                    $result['message'] .= " " . count($errors) . " registros con errores.";
                }
            } else {
                $result['message'] = "No se insertó ningún registro.";
            }

        } catch (Exception $e) {
            $result['message'] = "Error al procesar archivo: " . $e->getMessage();
        }

        return $result;
    }

    /**
     * Inserta un registro en la base de datos
     */
    private function insertRecord($tableName, $data, $config, $sessionData)
    {
        // Agregar campos automáticos
        if (isset($config['autoFields'])) {
            foreach ($config['autoFields'] as $fieldName => $value) {
                // Procesar valores especiales
                if ($value === 'CURRENT_TIMESTAMP') {
                    $data[$fieldName] = date('Y-m-d H:i:s');
                } elseif ($value === 'SESSION_ID') {
                    $data[$fieldName] = $sessionData['id'];
                } elseif ($value === 'SESSION_NOMBRE') {
                    $data[$fieldName] = $sessionData['nombre'];
                } elseif ($value === 'SESSION_TIPO_USUARIO') {
                    $data[$fieldName] = $this->getTipoUsuarioText($sessionData['tipo_usuario']);
                } else {
                    $data[$fieldName] = $value;
                }
            }
        }

        // Construir query
        $fields = [];
        $values = [];
        $types = '';
        $bindParams = [];

        foreach ($data as $field => $value) {
            $fields[] = $field;
            $values[] = '?';
            
            // Determinar tipo para bind_param
            if (is_int($value)) {
                $types .= 'i';
            } elseif (is_float($value)) {
                $types .= 'd';
            } else {
                $types .= 's';
                $value = mb_strtoupper($value); // Convertir a mayúsculas
            }
            
            $bindParams[] = $value;
        }

        $sql = "INSERT INTO {$tableName} (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $values) . ")";
        
        $stmt = $this->mysqli->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Error al preparar consulta: " . $this->mysqli->error);
        }

        // Bind parameters dinámicamente
        if (!empty($bindParams)) {
            $stmt->bind_param($types, ...$bindParams);
        }

        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    /**
     * Convierte código de tipo de usuario a texto
     */
    private function getTipoUsuarioText($tipo)
    {
        $tipos = [
            1 => 'RECTOR',
            2 => 'SIMAT',
            3 => 'DOCENTE',
            4 => 'DOCENTE DIRECTIVO',
            5 => 'DOCENTE ORIENTADOR',
            6 => 'ADMINISTRATIVO',
            7 => 'SIN ACCESO'
        ];
        
        return isset($tipos[$tipo]) ? $tipos[$tipo] : '';
    }

    /**
     * Obtiene la letra de columna desde un índice (0=A, 1=B, etc.)
     */
    private function getColumnLetter($index)
    {
        $letter = '';
        while ($index >= 0) {
            $letter = chr($index % 26 + 65) . $letter;
            $index = floor($index / 26) - 1;
        }
        return $letter;
    }

    /**
     * Descarga un archivo
     */
    public function downloadFile($filePath, $downloadName)
    {
        if (file_exists($filePath)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $downloadName . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filePath));
            readfile($filePath);
            
            // Eliminar archivo temporal
            unlink($filePath);
            exit;
        }
    }
}
