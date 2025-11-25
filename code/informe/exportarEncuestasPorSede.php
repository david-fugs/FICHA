<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../../index.php");
    exit();
}

$usuario = $_SESSION['usuario'];
$nombre = $_SESSION['nombre'];
$tipo_usuario = $_SESSION['tipo_usuario'];
$cod_dane_ie = $_SESSION['cod_dane_ie'];

date_default_timezone_set("America/Bogota");
include("../../conexion.php");

// Obtener parámetros de búsqueda si existen
@$buscar_ie = $_GET['buscar_ie'] ?? '';
@$buscar_sede = $_GET['buscar_sede'] ?? '';

// Requerir la librería PhpSpreadsheet
require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

// Crear nuevo objeto Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Configurar el título
$sheet->setCellValue('A1', 'CANTIDAD DE ENCUESTAS POR SEDE');
$sheet->mergeCells('A1:N1');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Fecha de generación
$sheet->setCellValue('A2', 'Fecha de generación: ' . date('Y-m-d H:i:s'));
$sheet->mergeCells('A2:N2');
$sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Encabezados de columnas
$headers = [
    'A4' => 'No.',
    'B4' => 'INSTITUCIÓN EDUCATIVA',
    'C4' => 'COD DANE IE',
    'D4' => 'SEDE',
    'E4' => 'COD DANE SEDE',
    'F4' => 'Pre Post-Natales',
    'G4' => 'Entorno Hogar',
    'H4' => 'Salud y Familia',
    'I4' => 'Educación',
    'J4' => 'Desempeño',
    'K4' => 'Preescolar',
    'L4' => 'Personal',
    'M4' => 'Preguntas',
    'N4' => 'TOTAL'
];

foreach ($headers as $cell => $value) {
    $sheet->setCellValue($cell, $value);
}

// Estilo de encabezados
$headerStyle = [
    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '412fd1']],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
];
$sheet->getStyle('A4:N4')->applyFromArray($headerStyle);

// Consulta optimizada para obtener todas las sedes
$consulta_sedes = "SELECT 
    ie.nombre_ie,
    ie.cod_dane_ie,
    ieSede.nombre_ieSede,
    ieSede.cod_dane_ieSede
FROM ieSede 
INNER JOIN ie ON ieSede.cod_dane_ie = ie.cod_dane_ie 
WHERE 1=1
AND (ie.nombre_ie LIKE '%$buscar_ie%')
AND (ieSede.nombre_ieSede LIKE '%$buscar_sede%')
ORDER BY ie.nombre_ie ASC, ieSede.nombre_ieSede ASC";

$result_sedes = $mysqli->query($consulta_sedes);

// Almacenar sedes y construir lista de IDs
$sedes = [];
$sedes_ids = [];
while ($sede = mysqli_fetch_array($result_sedes)) {
    $sedes[] = $sede;
    $sedes_ids[] = $mysqli->real_escape_string($sede['cod_dane_ieSede']);
}

// Obtener conteos si hay sedes
$conteos = [];
if (!empty($sedes_ids)) {
    $ids_str = "'" . implode("','", $sedes_ids) . "'";
    
    // Obtener conteos de cada tipo de encuesta de forma optimizada
    $queries_conteo = [
        'pre_post' => "SELECT e.cod_dane_ieSede, COUNT(DISTINCT p.num_doc_est) as cnt 
                      FROM estudiantes e 
                      LEFT JOIN prePostnatales p ON e.num_doc_est = p.num_doc_est AND p.fecha_alta_prePostnatales >= '2023-10-01'
                      WHERE e.cod_dane_ieSede IN ($ids_str) GROUP BY e.cod_dane_ieSede",
        
        'entorno' => "SELECT e.cod_dane_ieSede, COUNT(DISTINCT eh.num_doc_est) as cnt 
                     FROM estudiantes e 
                     LEFT JOIN entornohogar eh ON e.num_doc_est = eh.num_doc_est AND eh.fecha_alta_hog >= '2023-10-01'
                     WHERE e.cod_dane_ieSede IN ($ids_str) GROUP BY e.cod_dane_ieSede",
        
        'salud' => "SELECT e.cod_dane_ieSede, COUNT(DISTINCT f.num_doc_est) as cnt 
                   FROM estudiantes e 
                   LEFT JOIN familiasalud f ON e.num_doc_est = f.num_doc_est AND f.fechacreacion_familiasalud >= '2023-10-01'
                   WHERE e.cod_dane_ieSede IN ($ids_str) GROUP BY e.cod_dane_ieSede",
        
        'educacion' => "SELECT e.cod_dane_ieSede, COUNT(DISTINCT ed.num_doc_est) as cnt 
                       FROM estudiantes e 
                       LEFT JOIN educacion ed ON e.num_doc_est = ed.num_doc_est AND ed.fecha_dig_educacion >= '2023-10-01'
                       WHERE e.cod_dane_ieSede IN ($ids_str) GROUP BY e.cod_dane_ieSede",
        
        'desempeno' => "SELECT e.cod_dane_ieSede, COUNT(DISTINCT d.num_doc_est) as cnt 
                       FROM estudiantes e 
                       LEFT JOIN desempeno d ON e.num_doc_est = d.num_doc_est AND d.fecha_dig_desempeno >= '2023-10-01'
                       WHERE e.cod_dane_ieSede IN ($ids_str) GROUP BY e.cod_dane_ieSede",
        
        'preescolar' => "SELECT e.cod_dane_ieSede, COUNT(DISTINCT pr.num_doc_est) as cnt 
                        FROM estudiantes e 
                        LEFT JOIN preescolar pr ON e.num_doc_est = pr.num_doc_est AND pr.fecha_dig_preescolar >= '2023-10-01'
                        WHERE e.cod_dane_ieSede IN ($ids_str) GROUP BY e.cod_dane_ieSede",
        
        'personal' => "SELECT e.cod_dane_ieSede, COUNT(DISTINCT pe.num_doc_est) as cnt 
                      FROM estudiantes e 
                      LEFT JOIN personal pe ON e.num_doc_est = pe.num_doc_est AND pe.fecha_dig_personal >= '2023-10-01'
                      WHERE e.cod_dane_ieSede IN ($ids_str) GROUP BY e.cod_dane_ieSede",
        
        'preguntas' => "SELECT e.cod_dane_ieSede, COUNT(DISTINCT pg.num_doc_est) as cnt 
                       FROM estudiantes e 
                       LEFT JOIN preguntas pg ON e.num_doc_est = pg.num_doc_est AND pg.fecha_dig_preguntas >= '2023-10-01'
                       WHERE e.cod_dane_ieSede IN ($ids_str) GROUP BY e.cod_dane_ieSede"
    ];
    
    foreach ($queries_conteo as $tipo => $query_conteo) {
        $res = $mysqli->query($query_conteo);
        if ($res) {
            while ($row = mysqli_fetch_assoc($res)) {
                $conteos[$row['cod_dane_ieSede']][$tipo] = (int)$row['cnt'];
            }
        }
    }
}

// Variables para totales
$totales = [
    'pre_post' => 0,
    'entorno' => 0,
    'salud' => 0,
    'educacion' => 0,
    'desempeno' => 0,
    'preescolar' => 0,
    'personal' => 0,
    'preguntas' => 0,
    'total' => 0
];

// Llenar datos
$row_num = 5;
$contador = 1;

if (!empty($sedes)) {
    foreach ($sedes as $sede) {
        $cod_sede = $sede['cod_dane_ieSede'];
        $pre_post = $conteos[$cod_sede]['pre_post'] ?? 0;
        $entorno = $conteos[$cod_sede]['entorno'] ?? 0;
        $salud = $conteos[$cod_sede]['salud'] ?? 0;
        $educacion = $conteos[$cod_sede]['educacion'] ?? 0;
        $desempeno = $conteos[$cod_sede]['desempeno'] ?? 0;
        $preescolar = $conteos[$cod_sede]['preescolar'] ?? 0;
        $personal = $conteos[$cod_sede]['personal'] ?? 0;
        $preguntas = $conteos[$cod_sede]['preguntas'] ?? 0;
        
        $total_sede = $pre_post + $entorno + $salud + $educacion + $desempeno + $preescolar + $personal + $preguntas;

        // Datos de la fila
        $sheet->setCellValue('A' . $row_num, $contador);
        $sheet->setCellValue('B' . $row_num, $sede['nombre_ie']);
        $sheet->setCellValue('C' . $row_num, $sede['cod_dane_ie']);
        $sheet->setCellValue('D' . $row_num, $sede['nombre_ieSede']);
        $sheet->setCellValue('E' . $row_num, $cod_sede);
        $sheet->setCellValue('F' . $row_num, $pre_post);
        $sheet->setCellValue('G' . $row_num, $entorno);
        $sheet->setCellValue('H' . $row_num, $salud);
        $sheet->setCellValue('I' . $row_num, $educacion);
        $sheet->setCellValue('J' . $row_num, $desempeno);
        $sheet->setCellValue('K' . $row_num, $preescolar);
        $sheet->setCellValue('L' . $row_num, $personal);
        $sheet->setCellValue('M' . $row_num, $preguntas);
        $sheet->setCellValue('N' . $row_num, $total_sede);

        // Acumular totales
        $totales['pre_post'] += $pre_post;
        $totales['entorno'] += $entorno;
        $totales['salud'] += $salud;
        $totales['educacion'] += $educacion;
        $totales['desempeno'] += $desempeno;
        $totales['preescolar'] += $preescolar;
        $totales['personal'] += $personal;
        $totales['preguntas'] += $preguntas;
        $totales['total'] += $total_sede;

        // Estilo de datos
        $sheet->getStyle('A' . $row_num . ':N' . $row_num)->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);

        $row_num++;
        $contador++;
    }

    // Fila de totales
    $sheet->setCellValue('A' . $row_num, 'TOTALES:');
    $sheet->mergeCells('A' . $row_num . ':E' . $row_num);
    $sheet->setCellValue('F' . $row_num, $totales['pre_post']);
    $sheet->setCellValue('G' . $row_num, $totales['entorno']);
    $sheet->setCellValue('H' . $row_num, $totales['salud']);
    $sheet->setCellValue('I' . $row_num, $totales['educacion']);
    $sheet->setCellValue('J' . $row_num, $totales['desempeno']);
    $sheet->setCellValue('K' . $row_num, $totales['preescolar']);
    $sheet->setCellValue('L' . $row_num, $totales['personal']);
    $sheet->setCellValue('M' . $row_num, $totales['preguntas']);
    $sheet->setCellValue('N' . $row_num, $totales['total']);

    // Estilo de fila de totales
    $totalStyle = [
        'font' => ['bold' => true],
        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'e8e4f3']],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
    ];
    $sheet->getStyle('A' . $row_num . ':N' . $row_num)->applyFromArray($totalStyle);
}

// Ajustar ancho de columnas
$sheet->getColumnDimension('A')->setWidth(8);
$sheet->getColumnDimension('B')->setWidth(40);
$sheet->getColumnDimension('C')->setWidth(18);
$sheet->getColumnDimension('D')->setWidth(35);
$sheet->getColumnDimension('E')->setWidth(18);
$sheet->getColumnDimension('F')->setWidth(18);
$sheet->getColumnDimension('G')->setWidth(18);
$sheet->getColumnDimension('H')->setWidth(18);
$sheet->getColumnDimension('I')->setWidth(15);
$sheet->getColumnDimension('J')->setWidth(15);
$sheet->getColumnDimension('K')->setWidth(15);
$sheet->getColumnDimension('L')->setWidth(15);
$sheet->getColumnDimension('M')->setWidth(15);
$sheet->getColumnDimension('N')->setWidth(12);

$mysqli->close();

// Configurar headers para descarga
$filename = 'Encuestas_Por_Sede_' . date('Y-m-d_His') . '.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

// Crear el writer y generar el archivo
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
