<?php
/**
 * PLANTILLA PARA REPLICAR EN OTROS FORMULARIOS - UPLOAD
 * 
 * INSTRUCCIONES:
 * 1. Copiar este archivo
 * 2. Renombrar a: upload[NombreFormulario].php
 * 3. Copiar EXACTAMENTE la configuración de downloadTemplate[NombreFormulario].php
 * 4. Cambiar el enlace de retorno en el botón "Ver Encuestas"
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
            
            // ==================== CARGAR OPCIONES (IGUAL QUE downloadTemplate) ====================
            $municipios = [];
            $queryMunicipios = "SELECT nombre_mun FROM municipios ORDER BY nombre_mun";
            $resMunicipios = mysqli_query($mysqli, $queryMunicipios);
            while ($row = mysqli_fetch_assoc($resMunicipios)) {
                $municipios[] = $row['nombre_mun'];
            }
            
            // ==================== CONFIGURACIÓN (COPIAR DE downloadTemplate) ====================
            $config = [
                'formName' => '[NOMBRE DEL FORMULARIO]',
                'tableName' => '[nombre_tabla_bd]',
                
                'fields' => [
                    // ===== COPIAR EXACTAMENTE DE downloadTemplate[Formulario].php =====
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
                        'skipInsert' => true  // NO insertar en BD - solo referencia
                    ],
                    // ... resto de campos ...
                ],
                
                'autoFields' => [
                    // ===== COPIAR EXACTAMENTE DE downloadTemplate[Formulario].php =====
                    'fecha_dig_[tabla]' => 'CURRENT_TIMESTAMP',
                    'fecha_alta_[tabla]' => 'CURRENT_TIMESTAMP',
                    'fecha_edit_[tabla]' => '0000-00-00 00:00:00',
                    'nombre_encuestador_[tabla]' => 'SESSION_NOMBRE',
                    'rol_encuestador_[tabla]' => 'SESSION_TIPO_USUARIO',
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
            <i class="fas fa-file-upload"></i> Resultado de Carga Masiva - [NOMBRE FORMULARIO]
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
            <!-- CAMBIAR EL ENLACE SEGÚN EL FORMULARIO -->
            <a href="check[NombreFormulario].php" class="btn btn-primary">
                <i class="fas fa-list"></i> Ver Encuestas Aplicadas
            </a>
            <a href="../../access.php" class="btn btn-secondary">
                <i class="fas fa-home"></i> Ir al Inicio
            </a>
        </div>
    </div>
</body>
</html>
