<?php
/**
 * Descarga de plantilla Excel para Entorno del Hogar
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

// Configuración del formulario Entorno del Hogar
$config = [
    'formName' => 'ENTORNO DEL HOGAR',
    'fileName' => 'Plantilla_EntornoHogar_' . date('Y-m-d_His') . '.xlsx',
    'tableName' => 'entornohogar',
    
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
            'skipInsert' => true,
            'preloadField' => 'nom_ape_est'
        ],
        [
            'name' => 'mun_dig_hog',
            'label' => 'MUNICIPIO DILIGENCIAMIENTO',
            'type' => 'select',
            'required' => true,
            'options' => $municipios,
            'preloadField' => 'mun_dig_est'
        ],
        
        // === INFORMACIÓN DE LA MADRE ===
        [
            'name' => 'nombre_madre_hog',
            'label' => 'NOMBRES Y APELLIDOS DE LA MAMÁ',
            'type' => 'text',
            'required' => false
        ],
        [
            'name' => 'vive_madre_hog',
            'label' => '¿AÚN VIVE LA MADRE?',
            'type' => 'select',
            'required' => false,
            'options' => ['1' => 'SI', '0' => 'NO', '2' => 'N/A']
        ],
        [
            'name' => 'ocupacion_madre_hog',
            'label' => 'OCUPACIÓN MAMÁ',
            'type' => 'select',
            'required' => false,
            'options' => ['Ama de casa', 'Empleada', 'Trabajadora independiente', 'Desempleada', 'Jubilada', 'Estudiante', 'Pensionista']
        ],
        [
            'name' => 'educacion_madre_hog',
            'label' => 'NIVEL EDUCATIVO MAMÁ',
            'type' => 'select',
            'required' => false,
            'options' => ['Ninguno', 'Primaria', 'Bachillerato', 'Técnico', 'Tecnológico', 'Profesional', 'Postgrado']
        ],
        
        // === INFORMACIÓN DEL PADRE ===
        [
            'name' => 'nombre_padre_hog',
            'label' => 'NOMBRES Y APELLIDOS DEL PAPÁ',
            'type' => 'text',
            'required' => false
        ],
        [
            'name' => 'vive_padre_hog',
            'label' => '¿AÚN VIVE EL PADRE?',
            'type' => 'select',
            'required' => false,
            'options' => ['1' => 'SI', '0' => 'NO', '2' => 'N/A']
        ],
        [
            'name' => 'ocupacion_padre_hog',
            'label' => 'OCUPACIÓN PAPÁ',
            'type' => 'select',
            'required' => false,
            'options' => ['Amo de casa', 'Empleado', 'Trabajador independiente', 'Desempleado', 'Jubilado', 'Estudiante', 'Pensionista']
        ],
        [
            'name' => 'educacion_padre_hog',
            'label' => 'NIVEL EDUCATIVO PAPÁ',
            'type' => 'select',
            'required' => false,
            'options' => ['Ninguno', 'Primaria', 'Bachillerato', 'Técnico', 'Tecnológico', 'Profesional', 'Postgrado']
        ],
        
        // === CON QUIÉN VIVE ===
        [
            'name' => 'vive_estu_hog',
            'label' => 'ESTUDIANTE VIVE CON',
            'type' => 'select',
            'required' => true,
            'options' => ['Padres', 'Madre', 'Padre', 'Abuelos', 'Hermanos', 'Tíos', 'Otros familiares', 'Otro']
        ],
        
        // === INFORMACIÓN DEL CUIDADOR ===
        [
            'name' => 'cuidador_estu_hog',
            'label' => 'NOMBRE COMPLETO DEL CUIDADOR',
            'type' => 'text',
            'required' => true
        ],
        [
            'name' => 'parentesco_cuid_estu_hog',
            'label' => 'PARENTESCO CUIDADOR',
            'type' => 'select',
            'required' => true,
            'options' => ['Madre', 'Padre', 'Hermanos', 'Abuelos', 'Otro']
        ],
        [
            'name' => 'educacion_cuid_estu_hog',
            'label' => 'NIVEL EDUCATIVO CUIDADOR',
            'type' => 'select',
            'required' => true,
            'options' => ['Ninguno', 'Primaria', 'Bachillerato', 'Técnico', 'Tecnológico', 'Profesional', 'Postgrado']
        ],
        [
            'name' => 'ocupacion_cuid_estu_hog',
            'label' => 'OCUPACIÓN CUIDADOR',
            'type' => 'select',
            'required' => true,
            'options' => ['Ama(o) de casa', 'Empleada(o)', 'Trabajador(a) independiente', 'Desempleado(a)', 'Jubilado(a)', 'Estudiante', 'Pensionista', 'Otro']
        ],
        [
            'name' => 'tel_cuid_estu_hog',
            'label' => 'CONTACTO CUIDADOR',
            'type' => 'number',
            'required' => true
        ],
        [
            'name' => 'email_cuid_estu_hog',
            'label' => 'EMAIL CUIDADOR',
            'type' => 'text',
            'required' => false
        ],
        
        // === INFORMACIÓN DE HERMANOS ===
        [
            'name' => 'num_herm_estu_hog',
            'label' => 'No. HERMANOS',
            'type' => 'select',
            'required' => true,
            'options' => ['0', '1', '2', '3', '4', '5', '6']
        ],
        [
            'name' => 'lugar_ocupa_estu_hog',
            'label' => 'LUGAR QUE OCUPA ENTRE HERMANOS',
            'type' => 'select',
            'required' => true,
            'options' => ['0' => 'No aplica', '1', '2', '3', '4', '5', '6', '7']
        ],
        [
            'name' => 'tiene_herm_ie_estu_hog',
            'label' => '¿HERMANOS EN EL COLEGIO?',
            'type' => 'select',
            'required' => true,
            'options' => ['1' => 'SI', '0' => 'NO']
        ],
        
        // === CRIANZA Y FAMILIA ===
        [
            'name' => 'crianza_estu_hog',
            'label' => 'TIPO DE CRIANZA',
            'type' => 'select',
            'required' => true,
            'options' => ['Autoritaria', 'Permisiva', 'Democrática', 'Negligente', 'Otro']
        ],
        [
            'name' => 'prac_comu_estu_hog',
            'label' => 'PRACTICA COMUNIÓN',
            'type' => 'select',
            'required' => true,
            'options' => ['SI', 'NO']
        ],
        [
            'name' => 'fam_categ_estu_hog',
            'label' => 'CATEGORÍA FAMILIAR SISBEN',
            'type' => 'select',
            'required' => true,
            'options' => ['A', 'B', 'C', 'D', 'No tiene']
        ],
        [
            'name' => 'fam_subsidio_hog',
            'label' => '¿RECIBE SUBSIDIO?',
            'type' => 'select',
            'required' => true,
            'options' => ['1' => 'SI', '0' => 'NO']
        ],
        [
            'name' => 'tipo_subsidio_hog',
            'label' => 'NOMBRE DEL SUBSIDIO',
            'type' => 'text',
            'required' => false
        ],
        [
            'name' => 'mecanismos_conflictos_estu_hog',
            'label' => 'MECANISMOS SOLUCIÓN CONFLICTOS',
            'type' => 'select',
            'required' => false,
            'options' => ['', 'Comunicación abierta y sincera', 'Mediación', 'Terapia o asesoramiento', 'Compromiso mutuo', 'Cambio de perspectiva', 'Castigo físico', 'Amenaza verbal', 'Prohibiciones', 'Otros']
        ],
        [
            'name' => 'nom_mecanismos_conflictos_estu_hog',
            'label' => 'OTROS TIPOS DE MECANISMOS',
            'type' => 'text',
            'required' => false
        ],
        [
            'name' => 'inconvenientes_quien_hog',
            'label' => 'QUIÉNES SOLUCIONAN INCONVENIENTES',
            'type' => 'select',
            'required' => false,
            'options' => ['', 'Padre', 'Madre', 'Hermanos', 'Otros']
        ],
        [
            'name' => 'nom_quien_inconvenientes_hog',
            'label' => 'MENCIONE QUIÉNES SOLUCIONAN INCONVENIENTES',
            'type' => 'text',
            'required' => false
        ],
        [
            'name' => 'inconvenientes_como_hog',
            'label' => 'CÓMO SOLUCIONAN INCONVENIENTES',
            'type' => 'select',
            'required' => false,
            'options' => ['', 'Comunicación abierta y sincera', 'Terapia familiar', 'Mediación', 'Acciones legales', 'Consejería individual', 'Conversaciones difíciles', 'Disciplina física', 'Evitación', 'Otros']
        ],
        [
            'name' => 'nom_como_inconvenientes_hog',
            'label' => 'OTRA FORMA DE SOLUCIONAR INCONVENIENTES',
            'type' => 'text',
            'required' => false
        ],
        [
            'name' => 'responsabilidades_est_hog',
            'label' => 'RESPONSABILIDADES DEL ESTUDIANTE',
            'type' => 'select',
            'required' => false,
            'options' => ['', 'Mantener su habitación ordenada y limpia', 'Ayudar con las tareas domésticas como poner la mesa, lavar los platos, etc.', 'Cuidar de los animales de compañía', 'Realizar tareas de jardinería', 'Ayudar con la compra de alimentos y otros suministros', 'Ayudar a preparar comidas', 'Realizar tareas de limpieza y mantenimiento', 'Ayudar a cuidar a hermanos menores y familiares', 'Hacer la tarea y estudiar para mantener buenas calificaciones en el colegio', 'Participar en actividades familiares y comunitarias', 'Ninguna', 'Otros']
        ],
        [
            'name' => 'nom_responsabilidades_est_hog',
            'label' => 'CUÁLES RESPONSABILIDADES',
            'type' => 'text',
            'required' => false
        ],
        [
            'name' => 'afecto_est_hog',
            'label' => 'CÓMO EXPRESAN AFECTO',
            'type' => 'select',
            'required' => false,
            'options' => ['', 'Abrazos', 'Caricias', 'Juegos', 'Premios', 'Recreación', 'Camaradería', 'Ninguno', 'Otros']
        ],
        [
            'name' => 'nom_afecto_est_hog',
            'label' => 'DE QUÉ MANERA EXPRESAN AFECTO',
            'type' => 'text',
            'required' => false
        ],
        
        // === INFORMACIÓN DE VIVIENDA ===
        [
            'name' => 'tipo_vivienda_hog',
            'label' => 'TIPO DE VIVIENDA',
            'type' => 'select',
            'required' => true,
            'options' => ['Casa', 'Apartamento', 'Cuarto', 'Habitación', 'Otro']
        ],
        [
            'name' => 'tenencia_vivienda_hog',
            'label' => 'TENENCIA DE VIVIENDA',
            'type' => 'select',
            'required' => true,
            'options' => ['Propia', 'Arrendada', 'Familiar', 'Otra']
        ],
        [
            'name' => 'material_vivienda_hog',
            'label' => 'MATERIAL DE VIVIENDA',
            'type' => 'select',
            'required' => true,
            'options' => ['Ladrillo', 'Madera', 'Bahareque', 'Material reciclable', 'Otro']
        ],
        [
            'name' => 'num_personas_vivienda_hog',
            'label' => 'No. PERSONAS EN VIVIENDA',
            'type' => 'number',
            'required' => true
        ],
        [
            'name' => 'servicios_vivienda_hog',
            'label' => 'SERVICIOS DE LA VIVIENDA',
            'type' => 'select',
            'required' => false,
            'options' => ['', 'Aguas', 'Energía', 'Gas', 'Internet', 'Alcantarillado']
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
        'fecha_dig_hog' => 'CURRENT_TIMESTAMP',
        'nombre_encuestador_hog' => 'SESSION_NOMBRE',
        'rol_encuestador_hog' => 'SESSION_TIPO_USUARIO',
        'estado_hog' => '1',
        'fecha_alta_hog' => 'CURRENT_TIMESTAMP',
        'id_usu_alta_hog' => 'SESSION_ID',
        'fecha_edit_hog' => '0000-00-00 00:00:00',
        'id_usu' => 'SESSION_ID',
        // Campos opcionales que pueden estar vacíos
        'nom_vive_estu_hog' => '',
        'nom_parentesco_cuid_estu_hog' => '',
        'nom_ocupacion_cuid_estu_hog' => '',
        'niveles_educativos_herm_ie_estu_hog' => '',
        'nom_crianza_estu_hog' => '',
        'nom_tenencia_vivienda_hog' => ''
    ]
];

// Generar plantilla
try {
    $filePath = $excelHelper->generateTemplate($config);
    $excelHelper->downloadFile($filePath, $config['fileName']);
} catch (Exception $e) {
    echo "Error al generar plantilla: " . $e->getMessage();
}
