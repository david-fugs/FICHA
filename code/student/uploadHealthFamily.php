<?php
/**
 * Procesador de carga masiva Excel para Familia y Salud del Estudiante
 * 
 * Este archivo procesa archivos Excel subidos y carga los datos a la BD
 */

session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../../index.php");
    exit();
}

require_once(__DIR__ . '/../uploadData/ExcelFormHelper.php');
require_once(__DIR__ . '/../../conexion.php');

$usuario = $_SESSION['usuario'];
$nombre = $_SESSION['nombre'];
$tipo_usuario = $_SESSION['tipo_usuario'];
$cod_dane_ie = $_SESSION['cod_dane_ie'];
$id_usu = $_SESSION['id'];

$response = [
    'success' => false,
    'message' => '',
    'inserted' => 0,
    'errors' => []
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excel_file'])) {
    
    $file = $_FILES['excel_file'];
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $response['message'] = 'Error al subir el archivo.';
    } elseif ($file['size'] === 0) {
        $response['message'] = 'El archivo está vacío.';
    } elseif (!in_array($file['type'], [
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-excel'
    ])) {
        $response['message'] = 'El archivo debe ser un archivo Excel (.xlsx o .xls).';
    } else {
        $uploadDir = __DIR__ . '/../uploadData/temp/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $tempFilePath = $uploadDir . 'upload_' . time() . '_' . basename($file['name']);
        
        if (move_uploaded_file($file['tmp_name'], $tempFilePath)) {
            
            $excelHelper = new ExcelFormHelper($mysqli);
            
            // Obtener lista de municipios
            $municipios = [];
            $queryMunicipios = "SELECT nombre_mun FROM municipios ORDER BY nombre_mun";
            $resMunicipios = mysqli_query($mysqli, $queryMunicipios);
            while ($row = mysqli_fetch_assoc($resMunicipios)) {
                $municipios[] = $row['nombre_mun'];
            }
            
            // Configuración (debe coincidir con downloadTemplateHealthFamily.php)
            $config = [
                'formName' => 'FAMILIA Y SALUD DEL ESTUDIANTE',
                'tableName' => 'familiasalud',
                
                'fields' => [
                    [
                        'name' => 'num_doc_est',
                        'label' => 'No. DOCUMENTO ESTUDIANTE',
                        'type' => 'number',
                        'required' => true
                    ],
                    [
                        'name' => 'nom_ape_est',
                        'label' => 'NOMBRE COMPLETO ESTUDIANTE',
                        'type' => 'text',
                        'required' => false,
                        'readonly' => true
                    ],
                    [
                        'name' => 'mun_dig_familiaSalud',
                        'label' => 'MUNICIPIO DILIGENCIAMIENTO',
                        'type' => 'select',
                        'required' => true,
                        'options' => $municipios
                    ],
                    [
                        'name' => 'relacion_madre_familiaSalud',
                        'label' => 'RELACIÓN CON MADRE',
                        'type' => 'select',
                        'required' => true,
                        'options' => ['satisfactorias' => 'satisfactorias', 'llevaderas' => 'llevaderas', 'tensionadas' => 'tensionadas', 'hostiles' => 'hostiles', 'conflictivas' => 'conflictivas', 'incomunicadas' => 'incomunicadas', 'disfuncionales' => 'disfuncionales', 'no aplica' => 'no aplica'],
                        'normalize' => 'lowercase'
                    ],
                    [
                        'name' => 'relacion_padre_familiaSalud',
                        'label' => 'RELACIÓN CON PADRE',
                        'type' => 'select',
                        'required' => true,
                        'options' => ['satisfactorias' => 'satisfactorias', 'llevaderas' => 'llevaderas', 'tensionadas' => 'tensionadas', 'hostiles' => 'hostiles', 'conflictivas' => 'conflictivas', 'incomunicadas' => 'incomunicadas', 'disfuncionales' => 'disfuncionales', 'no aplica' => 'no aplica'],
                        'normalize' => 'lowercase'
                    ],
                    [
                        'name' => 'relacion_hermanos_familiaSalud',
                        'label' => 'RELACIÓN CON HERMANOS',
                        'type' => 'select',
                        'required' => true,
                        'options' => ['satisfactorias' => 'satisfactorias', 'llevaderas' => 'llevaderas', 'tensionadas' => 'tensionadas', 'hostiles' => 'hostiles', 'conflictivas' => 'conflictivas', 'incomunicadas' => 'incomunicadas', 'disfuncionales' => 'disfuncionales', 'no aplica' => 'no aplica'],
                        'normalize' => 'lowercase'
                    ],
                    [
                        'name' => 'relacion_abuelos_familiaSalud',
                        'label' => 'RELACIÓN CON ABUELOS',
                        'type' => 'select',
                        'required' => true,
                        'options' => ['satisfactorias' => 'satisfactorias', 'llevaderas' => 'llevaderas', 'tensionadas' => 'tensionadas', 'hostiles' => 'hostiles', 'conflictivas' => 'conflictivas', 'incomunicadas' => 'incomunicadas', 'disfuncionales' => 'disfuncionales', 'no aplica' => 'no aplica'],
                        'normalize' => 'lowercase'
                    ],
                    [
                        'name' => 'relacion_tios_familiaSalud',
                        'label' => 'RELACIÓN CON TÍOS',
                        'type' => 'select',
                        'required' => true,
                        'options' => ['satisfactorias' => 'satisfactorias', 'llevaderas' => 'llevaderas', 'tensionadas' => 'tensionadas', 'hostiles' => 'hostiles', 'conflictivas' => 'conflictivas', 'incomunicadas' => 'incomunicadas', 'disfuncionales' => 'disfuncionales', 'no aplica' => 'no aplica'],
                        'normalize' => 'lowercase'
                    ],
                    [
                        'name' => 'relacion_otros_familiaSalud',
                        'label' => 'RELACIÓN CON OTROS FAMILIARES',
                        'type' => 'select',
                        'required' => true,
                        'options' => ['satisfactorias' => 'satisfactorias', 'llevaderas' => 'llevaderas', 'tensionadas' => 'tensionadas', 'hostiles' => 'hostiles', 'conflictivas' => 'conflictivas', 'incomunicadas' => 'incomunicadas', 'disfuncionales' => 'disfuncionales', 'no aplica' => 'no aplica'],
                        'normalize' => 'lowercase'
                    ],
                    [
                        'name' => 'discapacidad_est_familiaSalud',
                        'label' => 'ESTUDIANTE PRESENTA DISCAPACIDAD',
                        'type' => 'select',
                        'required' => true,
                        'options' => ['SI', 'NO'],
                        'valueMap' => ['SI' => '0', 'NO' => '1']
                    ],
                    [
                        'name' => 'afecta_aprendizaje_familiaSalud',
                        'label' => 'SITUACIONES QUE AFECTAN APRENDIZAJE',
                        'type' => 'select',
                        'required' => false,
                        'options' => ['' => '', 'ambiente familiar' => 'ambiente familiar', 'factor economico' => 'factor economico', 'calidad educacion' => 'calidad educacion', 'salud mental' => 'salud mental', 'traumas' => 'traumas', 'nutricion' => 'nutricion', 'dificultades aprendizaje' => 'dificultades aprendizaje', 'habilidades sociales' => 'habilidades sociales', 'acoso' => 'acoso', 'lenguaje' => 'lenguaje', 'motivacion' => 'motivacion'],
                        'normalize' => 'lowercase'
                    ],
                    [
                        'name' => 'beneficiario_pae_familiaSalud',
                        'label' => 'BENEFICIARIO DEL PROGRAMA PAE',
                        'type' => 'select',
                        'required' => true,
                        'options' => ['SI', 'NO'],
                        'valueMap' => ['SI' => '1', 'NO' => '0']
                    ],
                    [
                        'name' => 'comida_dia_familiaSalud',
                        'label' => 'MOMENTOS DE COMIDA AL DÍA',
                        'type' => 'select',
                        'required' => true,
                        'options' => ['1', '2', '3', '4', '5', '6']
                    ],
                    [
                        'name' => 'eps_estudiante_familiaSalud',
                        'label' => 'AFILIADO A EPS',
                        'type' => 'select',
                        'required' => true,
                        'options' => ['SI', 'NO'],
                        'valueMap' => ['SI' => '1', 'NO' => '2']
                    ],
                    [
                        'name' => 'nombre_eps_familiaSalud',
                        'label' => 'NOMBRE DE LA EPS',
                        'type' => 'select',
                        'required' => false,
                        'options' => ['Nueva eps', 'Salud Total', 'Coosalud', 'Sura', 'Sanitas', 'Wayu', 'Aliansalud', 'Compensar', 'Salud Bolívar', 'Cafesalud', 'Cruz Blanca', 'Famisanar', 'Medimás', 'Mutual Ser', 'SOS', 'otro', ''],
                        'normalize' => 'titlecase'
                    ],
                    [
                        'name' => 'cual_eps_familiaSalud',
                        'label' => 'SI ES OTRA EPS, CUÁL',
                        'type' => 'text',
                        'required' => false
                    ],
                    [
                        'name' => 'afiliado_eps_familiaSalud',
                        'label' => 'SISTEMA DE SALUD AL CUAL ESTÁ AFILIADO',
                        'type' => 'select',
                        'required' => false,
                        'options' => ['Contributivo', 'Subsidiado', 'Especial', ''],
                        'normalize' => 'titlecase'
                    ],
                    [
                        'name' => 'presenta_diagnostico_familiaSalud',
                        'label' => 'TIENE ALGÚN DIAGNÓSTICO MÉDICO',
                        'type' => 'select',
                        'required' => true,
                        'options' => ['SI', 'NO'],
                        'valueMap' => ['SI' => '1', 'NO' => '2']
                    ],
                    [
                        'name' => 'diagnostico_familiaSalud',
                        'label' => 'CUÁL ES EL DIAGNÓSTICO MÉDICO',
                        'type' => 'text',
                        'required' => false
                    ],
                    [
                        'name' => 'terapia_familiaSalud',
                        'label' => 'ESTUDIANTE ASISTE A TERAPIA',
                        'type' => 'select',
                        'required' => true,
                        'options' => ['SI', 'NO'],
                        'valueMap' => ['SI' => '1', 'NO' => '2']
                    ],
                    [
                        'name' => 'frecuencia_terapia_familiaSalud',
                        'label' => 'FRECUENCIA DE ASISTENCIA A TERAPIA',
                        'type' => 'select',
                        'required' => false,
                        'options' => ['Semanal', 'Quincenal', 'Mensual', 'Bimestral', 'Trimestral', 'Semestral', 'Anual', ''],
                        'normalize' => 'titlecase'
                    ],
                    [
                        'name' => 'condicion_particular_familiaSalud',
                        'label' => 'SIENDO ATENDIDO POR EL SECTOR SALUD POR ALGUNA CONDICIÓN',
                        'type' => 'select',
                        'required' => true,
                        'options' => ['SI', 'NO'],
                        'valueMap' => ['SI' => '1', 'NO' => '2']
                    ],
                    [
                        'name' => 'frecuencia_atencion_familiaSalud',
                        'label' => 'FRECUENCIA DE ATENCIÓN POR EL SECTOR SALUD',
                        'type' => 'select',
                        'required' => false,
                        'options' => ['Semanal', 'Quincenal', 'Mensual', 'Bimestral', 'Trimestral', 'Semestral', 'Anual', ''],
                        'normalize' => 'titlecase'
                    ],
                    [
                        'name' => 'alergia_familiaSalud',
                        'label' => 'PRESENTA ALGÚN TIPO DE ALERGIA',
                        'type' => 'select',
                        'required' => true,
                        'options' => ['SI', 'NO'],
                        'valueMap' => ['SI' => '1', 'NO' => '2']
                    ],
                    [
                        'name' => 'tipo_alergia_familiaSalud',
                        'label' => 'TIPO DE ALERGIA',
                        'type' => 'text',
                        'required' => false
                    ],
                    [
                        'name' => 'vacunacion_familiaSalud',
                        'label' => 'ESQUEMA DE VACUNACIÓN COMPLETO',
                        'type' => 'select',
                        'required' => true,
                        'options' => ['SI', 'NO'],
                        'valueMap' => ['SI' => '1', 'NO' => '2']
                    ],
                    [
                        'name' => 'sangre_familiaSalud',
                        'label' => 'TIPO Y FACTOR SANGUÍNEO',
                        'type' => 'select',
                        'required' => true,
                        'options' => ['O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-']
                    ]
                ],
                
                'autoFields' => [
                    'nombre_encuestador_familiaSalud' => 'SESSION_NOMBRE',
                    'rol_encuestador_familiaSalud' => 'SESSION_TIPO_USUARIO',
                    'estado_familiasalud' => '1',
                    'fechacreacion_familiasalud' => 'CURRENT_TIMESTAMP',
                    'id_usu' => 'SESSION_ID'
                ]
            ];
            
            $sessionData = [
                'id' => $id_usu,
                'nombre' => $nombre,
                'tipo_usuario' => $tipo_usuario,
                'cod_dane_ie' => $cod_dane_ie
            ];
            
            $response = $excelHelper->processUpload($tempFilePath, $config, $sessionData);
            
            if (file_exists($tempFilePath)) {
                unlink($tempFilePath);
            }
            
        } else {
            $response['message'] = 'Error al mover el archivo subido.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FICHA - Carga Masiva</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link href="../../fontawesome/css/all.css" rel="stylesheet">
    <style>
        .responsive { max-width: 100%; height: auto; }
        .alert { margin-top: 20px; }
        .error-list {
            max-height: 300px;
            overflow-y: auto;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <center>
            <img src='../../img/logo_educacion.png' width=600 height=121 class='responsive'>
        </center>

        <h2 class="text-center mt-4">
            <i class="fas fa-file-upload"></i> Resultado de Carga Masiva - Familia y Salud
        </h2>

        <?php if ($response['success']): ?>
            <div class="alert alert-success">
                <h4><i class="fas fa-check-circle"></i> <?php echo $response['message']; ?></h4>
                <p><strong>Registros insertados:</strong> <?php echo $response['inserted']; ?></p>
            </div>
        <?php else: ?>
            <div class="alert alert-danger">
                <h4><i class="fas fa-exclamation-triangle"></i> 
                    <?php echo !empty($response['message']) ? $response['message'] : 'No se pudo procesar el archivo.'; ?>
                </h4>
            </div>
        <?php endif; ?>

        <?php if (!empty($response['errors'])): ?>
            <div class="alert alert-warning">
                <h5>Errores encontrados (<?php echo count($response['errors']); ?>):</h5>
                <div class="error-list">
                    <ul>
                        <?php foreach ($response['errors'] as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>

        <div class="text-center mt-4">
            <a href="showHealthFamily.php" class="btn btn-primary">
                <i class="fas fa-list"></i> Ver Listado
            </a>
            <a href="../../access.php" class="btn btn-secondary">
                <i class="fas fa-home"></i> Ir al Inicio
            </a>
        </div>
    </div>
</body>
</html>
