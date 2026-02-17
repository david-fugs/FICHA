<?php
/**
 * Descarga de plantilla Excel para Familia y Salud del Estudiante
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

// Obtener lista de municipios
$municipios = [];
$queryMunicipios = "SELECT nombre_mun FROM municipios ORDER BY nombre_mun";
$resMunicipios = mysqli_query($mysqli, $queryMunicipios);
while ($row = mysqli_fetch_assoc($resMunicipios)) {
    $municipios[] = $row['nombre_mun'];
}

// Configuración del formulario Familia y Salud
$config = [
    'formName' => 'FAMILIA Y SALUD DEL ESTUDIANTE',
    'fileName' => 'Plantilla_FamiliaSalud_' . date('Y-m-d_His') . '.xlsx',
    'tableName' => 'familiasalud',
    
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
            'preloadField' => 'nom_ape_est'
        ],
        [
            'name' => 'mun_dig_familiaSalud',
            'label' => 'MUNICIPIO DILIGENCIAMIENTO',
            'type' => 'select',
            'required' => true,
            'options' => $municipios,
            'preloadField' => 'mun_dig_est'
        ],
        
        // === RELACIONES FAMILIARES ===
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
        
        // === INFORMACIÓN GENERAL ===
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
            'options' => ['', 'ambiente familiar', 'factor economico', 'calidad educacion', 'salud mental', 'traumas', 'nutricion', 'dificultades aprendizaje', 'habilidades sociales', 'acoso', 'lenguaje', 'motivacion'],
            'help' => 'Seleccione la principal situación que afecta el aprendizaje',
            'normalize' => 'lowercase'
        ],
        
        // === NUTRICIÓN Y SALUD ===
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
        
        // === EPS Y SISTEMA DE SALUD ===
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
            'options' => ['Nueva eps', 'Salud Total', 'Coosalud', 'Sura', 'Sanitas', 'Wayu', 'Aliansalud', 'Compensar', 'Salud Bolívar', 'Cafesalud', 'Cruz Blanca', 'Famisanar', 'Medimás', 'Mutual Ser', 'SOS', 'otro'],
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
            'options' => ['Contributivo', 'Subsidiado', 'Especial'],
            'normalize' => 'titlecase'
        ],
        
        // === DIAGNÓSTICO MÉDICO ===
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
        
        // === TERAPIA ===
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
            'options' => ['Semanal', 'Quincenal', 'Mensual', 'Bimestral', 'Trimestral', 'Semestral', 'Anual'],
            'normalize' => 'titlecase'
        ],
        
        // === ATENCIÓN EN SALUD ===
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
            'options' => ['Semanal', 'Quincenal', 'Mensual', 'Bimestral', 'Trimestral', 'Semestral', 'Anual'],
            'normalize' => 'titlecase'
        ],
        
        // === ALERGIAS Y VACUNACIÓN ===
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
    
    // Query para precargar estudiantes
    'preloadQuery' => "SELECT DISTINCT e.num_doc_est, e.nom_ape_est, e.mun_dig_est 
                       FROM estudiantes e
                       INNER JOIN ieSede ON e.cod_dane_ieSede = ieSede.cod_dane_ieSede
                       INNER JOIN ie ON ieSede.cod_dane_ie = ie.cod_dane_ie
                       WHERE ie.cod_dane_ie = '{$cod_dane_ie}' 
                       AND e.estado_estudiante = 1
                       ORDER BY e.nom_ape_est",
    
    // Campos que se llenarán automáticamente al subir
    'autoFields' => [
        'nombre_encuestador_familiaSalud' => 'SESSION_NOMBRE',
        'rol_encuestador_familiaSalud' => 'SESSION_TIPO_USUARIO',
        'estado_familiasalud' => '1',
        'fechacreacion_familiasalud' => 'CURRENT_TIMESTAMP',
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
