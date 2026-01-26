<?php
/**
 * Descarga de plantilla Excel para Pre-Postnatales
 * 
 * Este archivo genera y descarga una plantilla Excel con:
 * - Formato predefinido
 * - Validaciones en campos
 * - Datos precargados de estudiantes
 * - Listas desplegables para selects
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

// Crear instancia del helper
$excelHelper = new ExcelFormHelper($mysqli);

// Obtener lista de municipios para el dropdown
$municipios = [];
$queryMunicipios = "SELECT nombre_mun FROM municipios ORDER BY nombre_mun";
$resMunicipios = mysqli_query($mysqli, $queryMunicipios);
while ($row = mysqli_fetch_assoc($resMunicipios)) {
    $municipios[] = $row['nombre_mun'];
}

// Configuración del formulario Pre-Postnatales
$config = [
    'formName' => 'PRE POSTNATALES',
    'fileName' => 'Plantilla_PrePostnatales_' . date('Y-m-d_His') . '.xlsx',
    'tableName' => 'prePostnatales',
    
    // Campos del formulario
    'fields' => [
        [
            'name' => 'num_doc_est',
            'label' => 'No. DOCUMENTO ESTUDIANTE',
            'type' => 'number',
            'required' => true,
            'preloadField' => 'num_doc_est'
        ],
        [
            'name' => 'nom_ape_est',
            'label' => 'NOMBRE COMPLETO ESTUDIANTE',
            'type' => 'text',
            'required' => false,
            'readonly' => true,
            'skipInsert' => true,  // No insertar en BD - solo referencia visual
            'preloadField' => 'nom_ape_est'
        ],
        [
            'name' => 'mun_dig_prePostnatales',
            'label' => 'MUNICIPIO DILIGENCIAMIENTO',
            'type' => 'select',
            'required' => true,
            'options' => $municipios,
            'preloadField' => 'mun_dig_est'
        ],
        [
            'name' => 'edad_madre_prePostnatales',
            'label' => 'EDAD DE LA MADRE (momento embarazo)',
            'type' => 'select',
            'required' => true,
            'options' => array_merge(range(10, 40), ['41' => 'MÁS DE 40'])
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
            'label' => 'LACTANCIA (tiempo en meses)',
            'type' => 'select',
            'required' => true,
            'options' => array_merge(range(0, 24), ['25' => 'MÁS DE 24'])
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
            'label' => 'EL ESTUDIANTE CAMINÓ (meses)',
            'type' => 'select',
            'required' => true,
            'options' => array_merge(range(8, 24), ['25' => 'MÁS DE 24', '0' => 'NO APLICA'])
        ]
    ],
    
    // Query para precargar estudiantes (solo documento y nombre)
    'preloadQuery' => "SELECT DISTINCT e.num_doc_est, e.nom_ape_est, e.mun_dig_est 
                       FROM estudiantes e
                       INNER JOIN ieSede ON e.cod_dane_ieSede = ieSede.cod_dane_ieSede
                       INNER JOIN ie ON ieSede.cod_dane_ie = ie.cod_dane_ie
                       WHERE ie.cod_dane_ie = '{$cod_dane_ie}' 
                       AND e.estado_estudiante = 1
                       ORDER BY e.nom_ape_est",
    
    // Campos que se llenarán automáticamente al subir
    'autoFields' => [
        'fecha_dig_prePostnatales' => 'CURRENT_TIMESTAMP',
        'nombre_encuestador_prePostnatales' => 'SESSION_NOMBRE',
        'rol_encuestador_prePostnatales' => 'SESSION_TIPO_USUARIO',
        'fecha_alta_prePostnatales' => 'CURRENT_TIMESTAMP',
        'fecha_edit_prePostnatales' => '0000-00-00 00:00:00',
        'id_usu' => 'SESSION_ID'
    ]
];

// Generar plantilla
try {
    $filePath = $excelHelper->generateTemplate($config);
    $excelHelper->downloadFile($filePath, $config['fileName']);
} catch (Exception $e) {
    echo "Error al generar plantilla: " . $e->getMessage();
}
