<?php
/**
 * Descarga de plantilla Excel para Consentimiento Informado
 * 
 * Este archivo genera y descarga una plantilla Excel con los estudiantes
 */

session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../../index.php");
    exit();
}

require_once(__DIR__ . '/../../conexion.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

$usuario = $_SESSION['usuario'];
$nombre = $_SESSION['nombre'];
$tipo_usuario = $_SESSION['tipo_usuario'];
$cod_dane_ie = $_SESSION['cod_dane_ie'];

// Crear nuevo documento Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Consentimiento Informado');

// Configurar encabezados
$headers = [
    'A1' => 'No. DOCUMENTO ESTUDIANTE',
    'B1' => 'NOMBRE COMPLETO ESTUDIANTE',
    'C1' => 'GRADO'
];

foreach ($headers as $cell => $header) {
    $sheet->setCellValue($cell, $header);
    $sheet->getStyle($cell)->getFont()->setBold(true);
    $sheet->getStyle($cell)->getFill()
        ->setFillType(Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FF4472C4');
    $sheet->getStyle($cell)->getFont()->getColor()->setARGB('FFFFFFFF');
    $sheet->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle($cell)->getBorders()->getAllBorders()
        ->setBorderStyle(Border::BORDER_THIN);
}

// Ajustar anchos de columna
$sheet->getColumnDimension('A')->setWidth(25);
$sheet->getColumnDimension('B')->setWidth(50);
$sheet->getColumnDimension('C')->setWidth(15);

// Obtener estudiantes que no tienen consentimiento informado pendiente
$query = "SELECT estudiantes.num_doc_est, estudiantes.nom_ape_est, estudiantes.grado_est
          FROM estudiantes 
          INNER JOIN ieSede ON estudiantes.cod_dane_ieSede = ieSede.cod_dane_ieSede 
          INNER JOIN ie ON ieSede.cod_dane_ie = ie.cod_dane_ie 
          WHERE ie.cod_dane_ie = $cod_dane_ie 
          AND estudiantes.estado_consentimientoInformado = 0
          ORDER BY estudiantes.nom_ape_est ASC";

$result = mysqli_query($mysqli, $query);
$row_num = 2;

while ($row = mysqli_fetch_assoc($result)) {
    $sheet->setCellValue('A' . $row_num, $row['num_doc_est']);
    $sheet->setCellValue('B' . $row_num, utf8_encode($row['nom_ape_est']));
    $sheet->setCellValue('C' . $row_num, $row['grado_est']);
    
    // Estilo para las filas de datos
    $sheet->getStyle('A' . $row_num . ':C' . $row_num)->getBorders()->getAllBorders()
        ->setBorderStyle(Border::BORDER_THIN);
    
    $row_num++;
}

// Configurar respuesta HTTP
$fileName = 'Plantilla_ConsentimientoInformado_' . date('Y-m-d_His') . '.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $fileName . '"');
header('Cache-Control: max-age=0');

// Escribir archivo
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

exit;
?>
