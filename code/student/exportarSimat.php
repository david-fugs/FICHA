<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../../index.php");
    exit();
}

require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

// Conectar a la base de datos
include("../../conexion.php");
date_default_timezone_set("America/Bogota");

$cod_dane_ie = $_SESSION['cod_dane_ie'];
$usuario = $_SESSION['usuario'];
$nombre = $_SESSION['nombre'];

// Obtener parámetros de filtro
$num_doc_est = $_GET['num_doc_est'] ?? '';
$nom_ape_est = $_GET['nom_ape_est'] ?? '';
$grado_est = $_GET['grado_est'] ?? '';
$estado_est = $_GET['estado_est'] ?? '';

// Construir la condición del filtro de estado
$filtro_estado = "";
if ($estado_est !== '' && $estado_est !== null) {
    $filtro_estado = " AND estudiantes.estado_est = '$estado_est'";
}

// Consulta SQL
$query = "SELECT estudiantes.*, usuarios.*, ie.*
  FROM estudiantes 
  INNER JOIN ieSede ON estudiantes.cod_dane_ieSede=ieSede.cod_dane_ieSede 
  INNER JOIN ie ON ieSede.cod_dane_ie=ie.cod_dane_ie 
  INNER JOIN usuarios ON estudiantes.id_usu = usuarios.id
  WHERE (estudiantes.num_doc_est LIKE '%$num_doc_est%') 
  AND (estudiantes.nom_ape_est LIKE '%$nom_ape_est%') 
  AND (estudiantes.grado_est = '$grado_est')
  AND ie.cod_dane_ie = $cod_dane_ie 
  $filtro_estado
  ORDER BY estudiantes.estado_est DESC, estudiantes.num_doc_est ASC";

$result = $mysqli->query($query);

if (!$result) {
    die("Error en la consulta: " . $mysqli->error);
}

// Crear una nueva hoja de cálculo
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Configurar el título
$sheet->setCellValue('A1', 'REPORTE DE ESTUDIANTES - SIMAT');
$sheet->mergeCells('A1:K1');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Información adicional
$institucion_nombre = '';
if ($result->num_rows > 0) {
    // Get first row to extract institution info
    $first_row = mysqli_fetch_array($result);
    // Try different possible column names for institution
    if (isset($first_row['nom_ie'])) {
        $institucion_nombre = $first_row['nom_ie'];
    } elseif (isset($first_row['nombre_ie'])) {
        $institucion_nombre = $first_row['nombre_ie'];
    } elseif (isset($first_row['institucion'])) {
        $institucion_nombre = $first_row['institucion'];
    } else {
        $institucion_nombre = 'Institución Educativa';
    }
}

$sheet->setCellValue('A2', 'Institución: ' . $institucion_nombre);
$sheet->setCellValue('A3', 'Fecha de exportación: ' . date('d/m/Y H:i:s'));
$sheet->setCellValue('A4', 'Exportado por: ' . $nombre);

// Resetear el resultado
$result = $mysqli->query($query);

// Configurar encabezados de columna
$headers = [
    'A6' => 'No.',
    'B6' => 'Tipo Documento',
    'C6' => 'Número Documento',
    'D6' => 'Nombres y Apellidos',
    'E6' => 'Fecha Nacimiento',
    'F6' => 'Grado',
    'G6' => 'Estado Estudiante',
    'H6' => 'Estado Actualización',
    'I6' => 'Fecha Última Edición',
    'J6' => 'Municipio Residencia',
    'K6' => 'Observaciones'
];

foreach ($headers as $cell => $header) {
    $sheet->setCellValue($cell, $header);
}

// Estilo para encabezados
$sheet->getStyle('A6:K6')->getFont()->setBold(true);
$sheet->getStyle('A6:K6')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('4F46E5');
$sheet->getStyle('A6:K6')->getFont()->getColor()->setRGB('FFFFFF');
$sheet->getStyle('A6:K6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Llenar datos
$row = 7;
$contador = 1;

while ($data = mysqli_fetch_array($result)) {
    // Determinar estado de actualización
    $fecha_edit = $data['fecha_edit_est'];
    $año_fecha_edit = date('Y', strtotime($fecha_edit));
    
    if ($año_fecha_edit > 2000) {
        $fecha_formateada = date('d/m/Y', strtotime($fecha_edit));
        $estado_actualizacion = "Actualizado $fecha_formateada";
    } else {
        $estado_actualizacion = "PENDIENTE";
    }

    // Determinar estado del estudiante
    $estado_estudiante = ($data['estado_est'] == 1) ? 'ACTIVO' : 'INACTIVO';    $sheet->setCellValue('A' . $row, $contador);
    $sheet->setCellValue('B' . $row, $data['tip_doc_est']);
    $sheet->setCellValue('C' . $row, $data['num_doc_est']);
    $sheet->setCellValue('D' . $row, $data['nom_ape_est']);
    $sheet->setCellValue('E' . $row, $data['fec_nac_est']);
    $sheet->setCellValue('F' . $row, $data['grado_est']);
    $sheet->setCellValue('G' . $row, $estado_estudiante);
    $sheet->setCellValue('H' . $row, $estado_actualizacion);
    $sheet->setCellValue('I' . $row, $data['fecha_edit_est']);
    $sheet->setCellValue('J' . $row, $data['mun_res_est']);
    $sheet->setCellValue('K' . $row, $data['obs_est']);

    // Colorear filas según estado del estudiante
    if ($data['estado_est'] == 1) {
        $sheet->getStyle('A' . $row . ':K' . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('DCFCE7');
    } else {
        $sheet->getStyle('A' . $row . ':K' . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F3F4F6');
    }

    $row++;
    $contador++;
}

// Aplicar bordes a toda la tabla
$lastRow = $row - 1;
$sheet->getStyle('A6:K' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

// Ajustar ancho de columnas
foreach (range('A', 'K') as $column) {
    $sheet->getColumnDimension($column)->setAutoSize(true);
}

// Configurar respuesta HTTP
$filename = 'Estudiantes_SIMAT_' . date('Y-m-d_H-i-s') . '.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

// Crear y enviar el archivo
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

// Limpiar memoria
$spreadsheet->disconnectWorksheets();
unset($spreadsheet);

exit();
?>
