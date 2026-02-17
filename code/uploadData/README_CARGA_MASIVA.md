# Sistema de Carga Masiva Excel - FICHA

## Descripción General

Este sistema permite descargar plantillas Excel con formato y validaciones para formularios, llenarlas con datos de múltiples estudiantes y subirlas para carga masiva en la base de datos.

## Características Principales

✅ **Plantillas con formato profesional**
- Headers destacados con colores
- Validaciones en celdas (listas desplegables, números, fechas)
- Instrucciones claras
- Campos obligatorios marcados

✅ **Datos precargados**
- Documento y nombre de estudiantes
- Municipios y otros datos del sistema
- Campos de solo lectura protegidos

✅ **Carga masiva inteligente**
- Validación de datos antes de insertar
- Reporte detallado de errores
- Campos automáticos (fecha, usuario, etc.)

✅ **Escalable y reutilizable**
- Una sola clase para todos los formularios
- Configuración simple mediante arrays
- Fácil de replicar

---

## Archivos del Sistema

### 1. Clase Principal
**`code/uploadData/ExcelFormHelper.php`**
- Clase reutilizable para generar y procesar Excel
- Métodos principales:
  - `generateTemplate()` - Genera plantilla Excel
  - `processUpload()` - Procesa archivo subido
  - `downloadFile()` - Descarga archivo

### 2. Archivos por Formulario (Ejemplo: prePostnatales)

**`code/student/downloadTemplatePrePostnatales.php`**
- Descarga la plantilla Excel
- Configura campos y opciones
- Precarga datos de estudiantes

**`code/student/uploadPrePostnatales.php`**
- Procesa archivo Excel subido
- Valida y carga datos a BD
- Muestra resultados y errores

---

## Cómo Replicar en Otros Formularios

### Paso 1: Crear archivo de descarga de plantilla

Crear archivo: `code/student/downloadTemplate[NombreFormulario].php`

```php
<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../../index.php");
    exit();
}

require_once(__DIR__ . '/../uploadData/ExcelFormHelper.php');
require_once(__DIR__ . '/../../conexion.php');

// Variables de sesión
$usuario = $_SESSION['usuario'];
$nombre = $_SESSION['nombre'];
$tipo_usuario = $_SESSION['tipo_usuario'];
$cod_dane_ie = $_SESSION['cod_dane_ie'];

// Crear instancia del helper
$excelHelper = new ExcelFormHelper($mysqli);

// ========== CONFIGURACIÓN DEL FORMULARIO ==========
$config = [
    'formName' => 'NOMBRE DEL FORMULARIO',
    'fileName' => 'Plantilla_NombreFormulario_' . date('Y-m-d_His') . '.xlsx',
    'tableName' => 'nombre_tabla_bd',
    
    // Definir campos del formulario
    'fields' => [
        // Campo 1: Documento (siempre primero)
        [
            'name' => 'num_doc_est',
            'label' => 'No. DOCUMENTO ESTUDIANTE',
            'type' => 'number',
            'required' => true,
            'preloadField' => 'num_doc_est'  // Campo de la query de precarga
        ],
        
        // Campo 2: Nombre (siempre segundo, solo lectura)
        [
            'name' => 'nom_ape_est',
            'label' => 'NOMBRE COMPLETO ESTUDIANTE',
            'type' => 'text',
            'required' => false,
            'readonly' => true,
            'preloadField' => 'nom_ape_est'
        ],
        
        // Campo 3: Campo de texto simple
        [
            'name' => 'nombre_campo',
            'label' => 'ETIQUETA DEL CAMPO',
            'type' => 'text',           // Tipos: text, number, select, date
            'required' => true,         // true o false
        ],
        
        // Campo 4: Select/Lista desplegable
        [
            'name' => 'nombre_campo_select',
            'label' => 'ETIQUETA CAMPO SELECT',
            'type' => 'select',
            'required' => true,
            'options' => ['OPCIÓN 1', 'OPCIÓN 2', 'OPCIÓN 3']  // Array de opciones
        ],
        
        // Campo 5: Campo numérico
        [
            'name' => 'nombre_campo_numero',
            'label' => 'ETIQUETA CAMPO NÚMERO',
            'type' => 'number',
            'required' => false
        ],
        
        // Agregar más campos según el formulario...
    ],
    
    // Query para precargar estudiantes (OPCIONAL)
    'preloadQuery' => "SELECT DISTINCT e.num_doc_est, e.nom_ape_est 
                       FROM estudiantes e
                       INNER JOIN ieSede ON e.cod_dane_ieSede = ieSede.cod_dane_ieSede
                       INNER JOIN ie ON ieSede.cod_dane_ie = ie.cod_dane_ie
                       WHERE ie.cod_dane_ie = '{$cod_dane_ie}' 
                       AND e.estado_estudiante = 1
                       ORDER BY e.nom_ape_est",
    
    // Campos que se llenan automáticamente al subir
    'autoFields' => [
        'fecha_dig_nombreTabla' => 'CURRENT_TIMESTAMP',
        'nombre_encuestador_nombreTabla' => 'SESSION_NOMBRE',
        'rol_encuestador_nombreTabla' => 'SESSION_TIPO_USUARIO',
        'fecha_alta_nombreTabla' => 'CURRENT_TIMESTAMP',
        'fecha_edit_nombreTabla' => '0000-00-00 00:00:00',
        'id_usu' => 'SESSION_ID'
    ]
];

// Generar y descargar plantilla
try {
    $filePath = $excelHelper->generateTemplate($config);
    $excelHelper->downloadFile($filePath, $config['fileName']);
} catch (Exception $e) {
    echo "Error al generar plantilla: " . $e->getMessage();
}
?>
```

### Paso 2: Crear archivo de carga/procesamiento

Crear archivo: `code/student/upload[NombreFormulario].php`

```php
<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../../index.php");
    exit();
}

require_once(__DIR__ . '/../uploadData/ExcelFormHelper.php');
require_once(__DIR__ . '/../../conexion.php');

// Variables de sesión
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
            
            // ========== MISMA CONFIGURACIÓN QUE EN downloadTemplate ==========
            $config = [
                'formName' => 'NOMBRE DEL FORMULARIO',
                'tableName' => 'nombre_tabla_bd',
                'fields' => [
                    // ... MISMOS CAMPOS QUE EN downloadTemplate ...
                ],
                'autoFields' => [
                    // ... MISMOS autoFields QUE EN downloadTemplate ...
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
            <i class="fas fa-file-upload"></i> Resultado de Carga Masiva - [Nombre Formulario]
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
```

### Paso 3: Agregar botones en página de listado

En el archivo `show[NombreFormulario].php` o `check[NombreFormulario].php`, agregar los botones:

```php
<!-- Reemplazar la sección de botones existente -->
<div class="d-flex justify-content-center align-items-center">
    <a href="../../access.php">
        <img src='../../img/atras.png' width="72" height="72" title="Regresar" />
    </a>
    <a class="ml-4" href="exportar/exportarAll[NombreFormulario].php">
        <img src='../../img/excel.png' width="75" height="80" title="Exportar Todos" />
    </a>
    
    <!-- Botón Descargar Plantilla -->
    <a class="ml-4" href="downloadTemplate[NombreFormulario].php" style="text-decoration: none;">
        <button class="btn btn-success" style="height: 80px; padding: 10px 20px;">
            <i class="fa fa-download"></i><br>
            <strong>Descargar<br>Plantilla Excel</strong>
        </button>
    </a>
    
    <!-- Botón Cargar Excel -->
    <a class="ml-2" href="#" onclick="document.getElementById('uploadForm').style.display='block'; return false;" 
       style="text-decoration: none;">
        <button class="btn btn-primary" style="height: 80px; padding: 10px 20px;">
            <i class="fa fa-upload"></i><br>
            <strong>Cargar<br>Excel Masivo</strong>
        </button>
    </a>
</div>

<!-- Formulario de carga oculto -->
<div id="uploadForm" style="display: none; margin-top: 20px; padding: 20px; background-color: #f8f9fa; 
     border-radius: 10px; max-width: 600px; margin-left: auto; margin-right: auto;">
    <h4 class="text-center"><i class="fa fa-upload"></i> Cargar Archivo Excel</h4>
    <form action="upload[NombreFormulario].php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="excel_file"><strong>Seleccione el archivo Excel (.xlsx):</strong></label>
            <input type="file" name="excel_file" id="excel_file" class="form-control" 
                   accept=".xlsx,.xls" required>
            <small class="form-text text-muted">
                El archivo debe seguir el formato de la plantilla descargada.
            </small>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-upload"></i> Subir y Procesar
            </button>
            <button type="button" class="btn btn-secondary" 
                    onclick="document.getElementById('uploadForm').style.display='none'">
                <i class="fa fa-times"></i> Cancelar
            </button>
        </div>
    </form>
</div>
```

Asegurarse de incluir FontAwesome en el `<head>`:
```html
<script src="https://kit.fontawesome.com/fed2435e21.js" crossorigin="anonymous"></script>
```

---

## Tipos de Campos Soportados

### 1. **text** - Campo de texto libre
```php
[
    'name' => 'campo_texto',
    'label' => 'ETIQUETA',
    'type' => 'text',
    'required' => true
]
```

### 2. **number** - Campo numérico
```php
[
    'name' => 'campo_numero',
    'label' => 'EDAD',
    'type' => 'number',
    'required' => true
]
```

### 3. **select** - Lista desplegable
```php
[
    'name' => 'campo_select',
    'label' => 'SELECCIONE OPCIÓN',
    'type' => 'select',
    'required' => true,
    'options' => ['OPCIÓN 1', 'OPCIÓN 2', 'OPCIÓN 3']
]
```

Para obtener opciones de la BD:
```php
// Ejemplo: cargar municipios
$opciones = [];
$query = "SELECT nombre FROM tabla ORDER BY nombre";
$result = mysqli_query($mysqli, $query);
while ($row = mysqli_fetch_assoc($result)) {
    $opciones[] = $row['nombre'];
}

// Usar en el campo
[
    'name' => 'municipio',
    'label' => 'MUNICIPIO',
    'type' => 'select',
    'required' => true,
    'options' => $opciones
]
```

### 4. **date** - Campo de fecha
```php
[
    'name' => 'fecha',
    'label' => 'FECHA',
    'type' => 'date',
    'required' => false
]
```

### 5. **readonly** - Campo de solo lectura
```php
[
    'name' => 'campo_readonly',
    'label' => 'CAMPO BLOQUEADO',
    'type' => 'text',
    'required' => false,
    'readonly' => true,  // No se puede editar
    'preloadField' => 'campo_bd'  // Se precarga de la BD
]
```

---

## Campos Automáticos (autoFields)

Campos que se llenan automáticamente al procesar el Excel:

```php
'autoFields' => [
    // Fecha actual
    'fecha_creacion' => 'CURRENT_TIMESTAMP',
    
    // Datos de sesión
    'id_usuario' => 'SESSION_ID',
    'nombre_usuario' => 'SESSION_NOMBRE',
    'tipo_usuario' => 'SESSION_TIPO_USUARIO',
    
    // Valores fijos
    'estado' => '1',
    'campo_fijo' => 'VALOR FIJO',
    
    // Fecha vacía
    'fecha_edicion' => '0000-00-00 00:00:00'
]
```

Valores especiales:
- `CURRENT_TIMESTAMP` → Fecha y hora actual
- `SESSION_ID` → ID del usuario en sesión
- `SESSION_NOMBRE` → Nombre del usuario
- `SESSION_TIPO_USUARIO` → Tipo de usuario (convertido a texto)
- Cualquier otro valor se inserta tal cual

---

## Ejemplo Completo: Formulario de Educación

### downloadTemplateEducation.php
```php
<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../../index.php");
    exit();
}

require_once(__DIR__ . '/../uploadData/ExcelFormHelper.php');
require_once(__DIR__ . '/../../conexion.php');

$cod_dane_ie = $_SESSION['cod_dane_ie'];
$excelHelper = new ExcelFormHelper($mysqli);

// Obtener listas para selects
$sedes = [];
$querySedes = "SELECT nombre_sede FROM sedes WHERE cod_dane_ie = '{$cod_dane_ie}' ORDER BY nombre_sede";
$resSedes = mysqli_query($mysqli, $querySedes);
while ($row = mysqli_fetch_assoc($resSedes)) {
    $sedes[] = $row['nombre_sede'];
}

$config = [
    'formName' => 'EDUCACIÓN',
    'fileName' => 'Plantilla_Educacion_' . date('Y-m-d_His') . '.xlsx',
    'tableName' => 'educacion',
    
    'fields' => [
        ['name' => 'num_doc_est', 'label' => 'No. DOCUMENTO', 'type' => 'number', 
         'required' => true, 'preloadField' => 'num_doc_est'],
        ['name' => 'nom_ape_est', 'label' => 'NOMBRE ESTUDIANTE', 'type' => 'text', 
         'required' => false, 'readonly' => true, 'preloadField' => 'nom_ape_est'],
        ['name' => 'sede', 'label' => 'SEDE', 'type' => 'select', 
         'required' => true, 'options' => $sedes],
        ['name' => 'grado', 'label' => 'GRADO', 'type' => 'select', 
         'required' => true, 'options' => range(0, 11)],
        ['name' => 'promedio', 'label' => 'PROMEDIO', 'type' => 'number', 
         'required' => false],
    ],
    
    'preloadQuery' => "SELECT num_doc_est, nom_ape_est FROM estudiantes 
                       WHERE cod_dane_ie = '{$cod_dane_ie}' AND estado_estudiante = 1",
    
    'autoFields' => [
        'fecha_registro' => 'CURRENT_TIMESTAMP',
        'id_usu' => 'SESSION_ID'
    ]
];

$filePath = $excelHelper->generateTemplate($config);
$excelHelper->downloadFile($filePath, $config['fileName']);
?>
```

---

## Consejos y Buenas Prácticas

### ✅ DO (Hacer)

1. **Siempre incluir estos dos campos primero:**
   - `num_doc_est` (documento del estudiante)
   - `nom_ape_est` (nombre del estudiante, readonly)

2. **Mantener consistencia entre descarga y upload:**
   - La configuración debe ser IDÉNTICA en ambos archivos
   - Copiar y pegar el array `$config` completo

3. **Validar opciones de selects:**
   - Cargar opciones desde la BD para asegurar valores válidos
   - Usar las mismas opciones en descarga y upload

4. **Nombrar archivos consistentemente:**
   - `downloadTemplate[Formulario].php`
   - `upload[Formulario].php`
   - `check[Formulario].php` (para los botones)

5. **Probar con pocos registros primero:**
   - Subir 2-3 registros para validar
   - Verificar errores antes de subir masivamente

### ❌ DON'T (No hacer)

1. ❌ No cambiar el orden de campos entre descarga y upload
2. ❌ No modificar la estructura de la plantilla Excel manualmente
3. ❌ No eliminar las cabeceras del Excel
4. ❌ No mezclar configuraciones de diferentes formularios
5. ❌ No olvidar agregar campos a `autoFields` si existen en la tabla

---

## Solución de Problemas

### Error: "Campo obligatorio vacío"
- **Causa:** Fila con datos incompletos
- **Solución:** Verificar que todos los campos marcados con `*` estén llenos

### Error: "Valor inválido en campo select"
- **Causa:** Valor no está en la lista de opciones
- **Solución:** Usar exactamente los valores de la lista desplegable

### Error: "No se insertó ningún registro"
- **Causa:** Todas las filas tienen errores o están vacías
- **Solución:** Revisar la lista de errores detallada

### La plantilla no descarga
- **Causa:** Error en la configuración o permisos
- **Solución:** 
  - Verificar que existe `code/uploadData/temp/`
  - Dar permisos 777 a la carpeta temp

### El upload no funciona
- **Causa:** Archivo muy grande o formato incorrecto
- **Solución:**
  - Verificar que el archivo sea .xlsx
  - Dividir en archivos más pequeños si es muy grande

---

## Checklist de Implementación

Al implementar en un nuevo formulario, verificar:

- [ ] Crear `downloadTemplate[Formulario].php`
- [ ] Crear `upload[Formulario].php`
- [ ] Agregar botones en página de listado
- [ ] Incluir FontAwesome en el head
- [ ] Configurar todos los campos del formulario
- [ ] Agregar opciones para todos los selects
- [ ] Configurar `autoFields` correctamente
- [ ] Probar descarga de plantilla
- [ ] Probar carga con 2-3 registros
- [ ] Verificar que los datos se insertan correctamente en BD
- [ ] Probar carga masiva (50+ registros)
- [ ] Documentar campos específicos del formulario

---

## Soporte y Contacto

Para dudas o mejoras al sistema, consultar:
- Clase principal: `code/uploadData/ExcelFormHelper.php`
- Ejemplo completo: Pre-Postnatales
- Archivos de referencia:
  - `downloadTemplatePrePostnatales.php`
  - `uploadPrePostnatales.php`
  - `checkprePostnatales.php`

---

**Versión:** 1.0  
**Fecha:** Enero 2026  
**Sistema:** FICHA - Secretaría de Educación
