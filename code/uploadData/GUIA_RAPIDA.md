# GUÍA RÁPIDA DE IMPLEMENTACIÓN
## Sistema de Carga Masiva Excel

---

## ⚡ IMPLEMENTACIÓN EN 5 PASOS

### 1️⃣ COPIAR PLANTILLA DE DESCARGA
```bash
Copiar: code/uploadData/TEMPLATE_downloadTemplate.php
Como:   code/student/downloadTemplate[TuFormulario].php
```

**Editar:**
- Línea con `[NOMBRE DEL FORMULARIO]` → Nombre visible
- Línea con `[nombre_tabla_bd]` → Nombre de la tabla en BD
- Sección `'fields'` → Agregar campos del formulario
- Sección `'autoFields'` → Agregar campos automáticos

---

### 2️⃣ COPIAR PLANTILLA DE UPLOAD
```bash
Copiar: code/uploadData/TEMPLATE_upload.php
Como:   code/student/upload[TuFormulario].php
```

**Editar:**
- Copiar TODA la configuración de downloadTemplate[TuFormulario].php
- Cambiar enlace del botón "Ver Encuestas" (línea ~120)

---

### 3️⃣ AGREGAR BOTONES EN PÁGINA DE LISTADO
```bash
Editar: code/student/check[TuFormulario].php o show[TuFormulario].php
```

**Copiar desde:** `code/uploadData/TEMPLATE_botones_html.txt`

**Reemplazar:**
- `[NombreFormulario]` → Nombre de tu formulario
- `[NombreTabla]` → Nombre de tabla para exportar

**Pegar:** Donde están los botones de "Regresar" y "Exportar"

---

### 4️⃣ VERIFICAR FONTAWESOME
En el `<head>` del archivo de listado:
```html
<script src="https://kit.fontawesome.com/fed2435e21.js" crossorigin="anonymous"></script>
```

---

### 5️⃣ PROBAR
1. ✅ Ir a la página de listado
2. ✅ Click en "Descargar Plantilla Excel"
3. ✅ Llenar 2-3 registros en el Excel
4. ✅ Click en "Cargar Excel Masivo"
5. ✅ Subir el archivo
6. ✅ Verificar que se insertaron en la BD

---

## 📋 CONFIGURACIÓN DE CAMPOS

### Tipo: TEXT
```php
[
    'name' => 'nombre_campo',
    'label' => 'ETIQUETA',
    'type' => 'text',
    'required' => true
]
```

### Tipo: NUMBER
```php
[
    'name' => 'edad',
    'label' => 'EDAD',
    'type' => 'number',
    'required' => true
]
```

### Tipo: SELECT (Opciones fijas)
```php
[
    'name' => 'respuesta',
    'label' => '¿PREGUNTA?',
    'type' => 'select',
    'required' => true,
    'options' => ['SI', 'NO']
]
```

### Tipo: SELECT (De base de datos)
```php
// Antes de $config:
$opciones = [];
$query = "SELECT campo FROM tabla ORDER BY campo";
$res = mysqli_query($mysqli, $query);
while ($row = mysqli_fetch_assoc($res)) {
    $opciones[] = $row['campo'];
}

// En fields:
[
    'name' => 'campo_select',
    'label' => 'SELECCIONE',
    'type' => 'select',
    'required' => true,
    'options' => $opciones
]
```

### Tipo: SELECT (Rango numérico)
```php
[
    'name' => 'grado',
    'label' => 'GRADO',
    'type' => 'select',
    'required' => true,
    'options' => range(0, 11)  // 0 a 11
]
```

### Campo READONLY (precargado)
```php
[
    'name' => 'municipio',
    'label' => 'MUNICIPIO',
    'type' => 'text',
    'required' => false,
    'readonly' => true,
    'preloadField' => 'mun_dig_est'  // Campo de la BD
]
```

### Campo SOLO VISUAL (no se inserta en BD)
```php
[
    'name' => 'nom_ape_est',
    'label' => 'NOMBRE ESTUDIANTE',
    'type' => 'text',
    'required' => false,
    'readonly' => true,
    'skipInsert' => true,  // NO se inserta en BD - solo referencia
    'preloadField' => 'nom_ape_est'
]
```

---

## 🔧 CAMPOS AUTOMÁTICOS

Valores especiales que se pueden usar:
- `CURRENT_TIMESTAMP` → Fecha y hora actual
- `SESSION_ID` → ID del usuario en sesión
- `SESSION_NOMBRE` → Nombre del usuario
- `SESSION_TIPO_USUARIO` → Tipo de usuario (texto)
- Cualquier texto → Se inserta tal cual

```php
'autoFields' => [
    'fecha_dig_tabla' => 'CURRENT_TIMESTAMP',
    'nombre_encuestador_tabla' => 'SESSION_NOMBRE',
    'rol_encuestador_tabla' => 'SESSION_TIPO_USUARIO',
    'fecha_alta_tabla' => 'CURRENT_TIMESTAMP',
    'fecha_edit_tabla' => '0000-00-00 00:00:00',
    'id_usu' => 'SESSION_ID',
    'estado' => '1'  // Valor fijo
]
```

---

## 📁 ARCHIVOS CREADOS

### Clase Principal
✅ `code/uploadData/ExcelFormHelper.php` - Clase reutilizable

### Plantillas para Copiar
✅ `code/uploadData/TEMPLATE_downloadTemplate.php`  
✅ `code/uploadData/TEMPLATE_upload.php`  
✅ `code/uploadData/TEMPLATE_botones_html.txt`

### Ejemplo Implementado: PrePostnatales
✅ `code/student/downloadTemplatePrePostnatales.php`  
✅ `code/student/uploadPrePostnatales.php`  
✅ `code/student/checkprePostnatales.php` (modificado)

### Documentación
✅ `code/uploadData/README_CARGA_MASIVA.md` - Documentación completa  
✅ `code/uploadData/GUIA_RAPIDA.md` - Este archivo

---

## ⚠️ IMPORTANTE

### ✅ HACER
- Mantener configuración idéntica entre download y upload
- Probar con pocos registros primero
- Incluir siempre num_doc_est y nom_ape_est como primeros campos
- Verificar que todas las opciones de selects sean válidas

### ❌ NO HACER
- No cambiar el orden de campos entre download y upload
- No modificar la plantilla Excel manualmente (estructura)
- No olvidar agregar campos a autoFields si existen en la tabla
- No subir archivos muy grandes sin probar primero

---

## 🐛 SOLUCIÓN DE PROBLEMAS

### ❌ "Campo obligatorio vacío"
→ Llenar todos los campos con `*` en el Excel

### ❌ "Valor inválido en campo select"
→ Usar exactamente los valores de la lista desplegable

### ❌ No descarga la plantilla
→ Verificar permisos en `code/uploadData/temp/` (777)

### ❌ No se insertan registros
→ Revisar lista de errores en la página de resultado

---

## 📞 ARCHIVOS DE REFERENCIA

Para dudas, revisar la implementación completa en:
- `code/student/downloadTemplatePrePostnatales.php`
- `code/student/uploadPrePostnatales.php`
- `code/uploadData/README_CARGA_MASIVA.md`

---

## 🎯 CHECKLIST DE IMPLEMENTACIÓN

- [ ] Crear downloadTemplate[Formulario].php
- [ ] Configurar campos correctamente
- [ ] Configurar autoFields
- [ ] Crear upload[Formulario].php
- [ ] Copiar configuración exacta
- [ ] Agregar botones en página de listado
- [ ] Verificar FontAwesome
- [ ] Descargar plantilla de prueba
- [ ] Llenar 2-3 registros
- [ ] Subir y verificar en BD
- [ ] Probar carga masiva (50+ registros)

---

**¡Sistema listo para usar!** 🚀
