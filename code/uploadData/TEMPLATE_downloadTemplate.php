<?php
/**
 * PLANTILLA PARA REPLICAR EN OTROS FORMULARIOS
 * 
 * INSTRUCCIONES:
 * 1. Copiar este archivo
 * 2. Renombrar a: downloadTemplate[NombreFormulario].php
 * 3. Reemplazar [FORMULARIO] con el nombre real
 * 4. Configurar campos según el formulario
 * 5. Configurar autoFields según columnas de la tabla
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

$excelHelper = new ExcelFormHelper($mysqli);

// ==================== CARGAR OPCIONES PARA SELECTS ====================
// Ejemplo 1: Cargar municipios
$municipios = [];
$queryMunicipios = "SELECT nombre_mun FROM municipios ORDER BY nombre_mun";
$resMunicipios = mysqli_query($mysqli, $queryMunicipios);
while ($row = mysqli_fetch_assoc($resMunicipios)) {
    $municipios[] = $row['nombre_mun'];
}

// Ejemplo 2: Cargar sedes
// $sedes = [];
// $querySedes = "SELECT nombre_sede FROM sedes WHERE cod_dane_ie = '{$cod_dane_ie}'";
// $resSedes = mysqli_query($mysqli, $querySedes);
// while ($row = mysqli_fetch_assoc($resSedes)) {
//     $sedes[] = $row['nombre_sede'];
// }

// ==================== CONFIGURACIÓN ====================
$config = [
    // PASO 1: Configurar nombre y tabla
    'formName' => '[NOMBRE DEL FORMULARIO]',  // Ej: 'EDUCACIÓN', 'SALUD FAMILIAR'
    'fileName' => 'Plantilla_[Formulario]_' . date('Y-m-d_His') . '.xlsx',
    'tableName' => '[nombre_tabla_bd]',  // Ej: 'educacion', 'saludFamiliar'
    
    // PASO 2: Configurar campos del formulario
    'fields' => [
        // ===== SIEMPRE INCLUIR ESTOS DOS PRIMEROS CAMPOS =====
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
            'skipInsert' => true,  // NO insertar en BD - solo referencia visual
            'preloadField' => 'nom_ape_est'
        ],
        
        // ===== AGREGAR CAMPOS DEL FORMULARIO AQUÍ =====
        
        // Ejemplo: Campo de texto
        // [
        //     'name' => 'nombre_campo',
        //     'label' => 'ETIQUETA DEL CAMPO',
        //     'type' => 'text',
        //     'required' => true
        // ],
        
        // Ejemplo: Campo numérico
        // [
        //     'name' => 'edad',
        //     'label' => 'EDAD',
        //     'type' => 'number',
        //     'required' => true
        // ],
        
        // Ejemplo: Select con opciones fijas
        // [
        //     'name' => 'respuesta',
        //     'label' => '¿PREGUNTA?',
        //     'type' => 'select',
        //     'required' => true,
        //     'options' => ['SI', 'NO']
        // ],
        
        // Ejemplo: Select con opciones de BD
        // [
        //     'name' => 'municipio',
        //     'label' => 'MUNICIPIO',
        //     'type' => 'select',
        //     'required' => true,
        //     'options' => $municipios
        // ],
        
        // Ejemplo: Select con rango numérico
        // [
        //     'name' => 'grado',
        //     'label' => 'GRADO',
        //     'type' => 'select',
        //     'required' => true,
        //     'options' => range(0, 11)  // 0, 1, 2, ..., 11
        // ],
        
        // Ejemplo: Select con opciones mezcladas
        // [
        //     'name' => 'tiempo_meses',
        //     'label' => 'TIEMPO EN MESES',
        //     'type' => 'select',
        //     'required' => true,
        //     'options' => array_merge(range(0, 24), ['25' => 'MÁS DE 24'])
        // ],
        
        // Ejemplo: Campo de fecha
        // [
        //     'name' => 'fecha_evento',
        //     'label' => 'FECHA DEL EVENTO',
        //     'type' => 'date',
        //     'required' => false
        // ],
    ],
    
    // PASO 3: Query para precargar estudiantes (OPCIONAL - recomendado)
    'preloadQuery' => "SELECT DISTINCT e.num_doc_est, e.nom_ape_est 
                       FROM estudiantes e
                       INNER JOIN ieSede ON e.cod_dane_ieSede = ieSede.cod_dane_ieSede
                       INNER JOIN ie ON ieSede.cod_dane_ie = ie.cod_dane_ie
                       WHERE ie.cod_dane_ie = '{$cod_dane_ie}' 
                       AND e.estado_estudiante = 1
                       ORDER BY e.nom_ape_est",
    
    // PASO 4: Campos automáticos (revisar estructura de la tabla)
    'autoFields' => [
        // Fechas
        'fecha_dig_[tabla]' => 'CURRENT_TIMESTAMP',
        'fecha_alta_[tabla]' => 'CURRENT_TIMESTAMP',
        'fecha_edit_[tabla]' => '0000-00-00 00:00:00',
        
        // Usuario
        'nombre_encuestador_[tabla]' => 'SESSION_NOMBRE',
        'rol_encuestador_[tabla]' => 'SESSION_TIPO_USUARIO',
        'id_usu' => 'SESSION_ID',
        
        // Municipio (si aplica y se puede precargar)
        // 'mun_dig_[tabla]' => puede ir en fields si debe llenarse manualmente
        
        // Otros campos fijos
        // 'estado' => '1',
        // 'campo_fijo' => 'VALOR_FIJO'
    ]
];

// ==================== GENERAR Y DESCARGAR ====================
try {
    $filePath = $excelHelper->generateTemplate($config);
    $excelHelper->downloadFile($filePath, $config['fileName']);
} catch (Exception $e) {
    echo "Error al generar plantilla: " . $e->getMessage();
}
