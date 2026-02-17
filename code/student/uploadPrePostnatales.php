<?php
/**
 * Procesador de carga masiva Excel para Pre-Postnatales
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
    
    // Validar archivo
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
        // Mover archivo a ubicación temporal
        $uploadDir = __DIR__ . '/../uploadData/temp/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $tempFilePath = $uploadDir . 'upload_' . time() . '_' . basename($file['name']);
        
        if (move_uploaded_file($file['tmp_name'], $tempFilePath)) {
            
            // Crear instancia del helper
            $excelHelper = new ExcelFormHelper($mysqli);
            
            // Obtener lista de municipios para validar
            $municipios = [];
            $queryMunicipios = "SELECT nombre_mun FROM municipios ORDER BY nombre_mun";
            $resMunicipios = mysqli_query($mysqli, $queryMunicipios);
            while ($row = mysqli_fetch_assoc($resMunicipios)) {
                $municipios[] = $row['nombre_mun'];
            }
            
            // Configuración (debe coincidir con downloadTemplatePrePostnatales.php)
            $config = [
                'formName' => 'PRE POSTNATALES',
                'tableName' => 'prePostnatales',
                
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
                        'readonly' => true,
                        'skipInsert' => true  // No insertar en BD - solo referencia visual
                    ],
                    [
                        'name' => 'mun_dig_prePostnatales',
                        'label' => 'MUNICIPIO DILIGENCIAMIENTO',
                        'type' => 'select',
                        'required' => true,
                        'options' => $municipios
                    ],
                    [
                        'name' => 'edad_madre_prePostnatales',
                        'label' => 'EDAD DE LA MADRE',
                        'type' => 'select',
                        'required' => true,
                        'options' => array_merge(range(10, 40), ['41', 'MÁS DE 40'])
                    ],
                    [
                        'name' => 'gestacion_meses_prePostnatales',
                        'label' => 'GESTACIÓN EN MESES',
                        'type' => 'select',
                        'required' => true,
                        'options' => ['5 MESES', '6 MESES', '7 MESES', '8 MESES', '9 MESES']
                    ],
                    [
                        'name' => 'embarazo_mama_prePostnatales',
                        'label' => 'EL EMBARAZO PRESENTÓ',
                        'type' => 'select',
                        'required' => true,
                        'options' => ['PARTO A TIEMPO (NATURAL)', 'PARTO ASISTIDO (CESÁREA)', 'SIN ANTECEDENTE']
                    ],
                    [
                        'name' => 'lactancia_mama_prePostnatales',
                        'label' => 'LACTANCIA',
                        'type' => 'select',
                        'required' => true,
                        'options' => array_merge(range(0, 24), ['25', 'MÁS DE 24'])
                    ],
                    [
                        'name' => 'gateo_prePostnatales',
                        'label' => 'EL ESTUDIANTE GATEÓ',
                        'type' => 'select',
                        'required' => true,
                        'options' => ['SI', 'NO']
                    ],
                    [
                        'name' => 'camino_prePostnatales',
                        'label' => 'EL ESTUDIANTE CAMINÓ',
                        'type' => 'select',
                        'required' => true,
                        'options' => array_merge(range(8, 24), ['25', 'MÁS DE 24', '0', 'NO APLICA'])
                    ]
                ],
                
                'autoFields' => [
                    'fecha_dig_prePostnatales' => 'CURRENT_TIMESTAMP',
                    'nombre_encuestador_prePostnatales' => 'SESSION_NOMBRE',
                    'rol_encuestador_prePostnatales' => 'SESSION_TIPO_USUARIO',
                    'fecha_alta_prePostnatales' => 'CURRENT_TIMESTAMP',
                    'fecha_edit_prePostnatales' => '0000-00-00 00:00:00',
                    'id_usu' => 'SESSION_ID'
                ]
            ];
            
            // Datos de sesión
            $sessionData = [
                'id' => $id_usu,
                'nombre' => $nombre,
                'tipo_usuario' => $tipo_usuario,
                'cod_dane_ie' => $cod_dane_ie
            ];
            
            // Procesar archivo
            $response = $excelHelper->processUpload($tempFilePath, $config, $sessionData);
            
            // Eliminar archivo temporal
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
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>FICHA - Carga Masiva</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link href="../../fontawesome/css/all.css" rel="stylesheet">
    <style>
        .responsive {
            max-width: 100%;
            height: auto;
        }
        .alert {
            margin-top: 20px;
        }
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

        <h2 class="text-center mt-4"><i class="fas fa-file-upload"></i> Resultado de Carga Masiva - Pre Postnatales</h2>

        <?php if ($response['success']): ?>
            <div class="alert alert-success">
                <h4><i class="fas fa-check-circle"></i> <?php echo $response['message']; ?></h4>
                <p><strong>Registros insertados:</strong> <?php echo $response['inserted']; ?></p>
            </div>
        <?php else: ?>
            <div class="alert alert-danger">
                <h4><i class="fas fa-exclamation-triangle"></i> <?php echo !empty($response['message']) ? $response['message'] : 'No se pudo procesar el archivo.'; ?></h4>
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
            <a href="checkprePostnatales.php" class="btn btn-primary">
                <i class="fas fa-list"></i> Ver Encuestas Aplicadas
            </a>
            <a href="../../access.php" class="btn btn-secondary">
                <i class="fas fa-home"></i> Ir al Inicio
            </a>
        </div>
    </div>

    <script src="../../js/jquery.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
</body>
</html>
