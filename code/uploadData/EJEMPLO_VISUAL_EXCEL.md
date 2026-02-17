# EJEMPLO VISUAL DE PLANTILLA EXCEL GENERADA

## Cómo se ve la plantilla descargada

```
╔═══════════════════════════════════════════════════════════════════════════════════════════════╗
║ FILA 1 - INSTRUCCIONES (Azul, Fusionada)                                                    ║
║ INSTRUCCIONES: Complete los datos de los estudiantes. Los campos marcados con * son         ║
║ obligatorios. No modifique ni elimine las cabeceras.                                         ║
╠═══════════════════════════════════════════════════════════════════════════════════════════════╣
║ FILA 2 - ESPACIADOR (vacía)                                                                 ║
╠════════════════╦═══════════════════════╦═══════════════════╦══════════════╦═════════════════╣
║ FILA 3 - CABECERAS (Verde, Negrita)                                                          ║
╠════════════════╬═══════════════════════╬═══════════════════╬══════════════╬═════════════════╣
║ * No. DOCUMENTO║ NOMBRE COMPLETO       ║ * MUNICIPIO       ║ * EDAD MADRE ║ * GESTACIÓN     ║
║ ESTUDIANTE     ║ ESTUDIANTE            ║ DILIGENCIAMIENTO  ║              ║ EN MESES        ║
║ (Amarillo)     ║ (Gris - Solo lectura) ║ (Amarillo)        ║ (Amarillo)   ║ (Amarillo)      ║
╠════════════════╬═══════════════════════╬═══════════════════╬══════════════╬═════════════════╣
║ FILA 4 - DATOS PRECARGADOS (Si aplica)                                                       ║
╠════════════════╬═══════════════════════╬═══════════════════╬══════════════╬═════════════════╣
║ 1234567890     ║ JUAN PEREZ GOMEZ      ║ [Dropdown ▼]      ║ [Dropdown ▼] ║ [Dropdown ▼]    ║
╠════════════════╬═══════════════════════╬═══════════════════╬══════════════╬═════════════════╣
║ 9876543210     ║ MARIA LOPEZ RUIZ      ║ [Dropdown ▼]      ║ [Dropdown ▼] ║ [Dropdown ▼]    ║
╠════════════════╬═══════════════════════╬═══════════════════╬══════════════╬═════════════════╣
║ 5555555555     ║ CARLOS DIAZ TORRES    ║ [Dropdown ▼]      ║ [Dropdown ▼] ║ [Dropdown ▼]    ║
╠════════════════╬═══════════════════════╬═══════════════════╬══════════════╬═════════════════╣
║ ...            ║ ...                   ║ ...               ║ ...          ║ ...             ║
╠════════════════╬═══════════════════════╬═══════════════════╬══════════════╬═════════════════╣
║ FILA 104+ - FILAS VACÍAS PARA LLENAR MANUALMENTE                                             ║
╠════════════════╬═══════════════════════╬═══════════════════╬══════════════╬═════════════════╣
║                ║                       ║ [Dropdown ▼]      ║ [Dropdown ▼] ║ [Dropdown ▼]    ║
╠════════════════╬═══════════════════════╬═══════════════════╬══════════════╬═════════════════╣
║                ║                       ║ [Dropdown ▼]      ║ [Dropdown ▼] ║ [Dropdown ▼]    ║
╠════════════════╬═══════════════════════╬═══════════════════╬══════════════╬═════════════════╣
║ ... (hasta fila 104)                                                                         ║
╚════════════════╩═══════════════════════╩═══════════════════╩══════════════╩═════════════════╝
```

## Colores y Formato

### Fila 1 - Instrucciones
- 🔵 **Fondo:** Azul (#4472C4)
- ⚪ **Texto:** Blanco, Negrita
- 📏 **Alto:** 30px
- 🔗 **Fusionada:** Todas las columnas

### Fila 3 - Cabeceras
- 🟢 **Fondo:** Verde (#70AD47)
- ⚪ **Texto:** Blanco, Negrita, Tamaño 11
- 📏 **Alto:** 40px
- 🔲 **Bordes:** Todos los lados
- 📝 **Alineación:** Centro, Ajuste de texto

### Campos Obligatorios (*)
- 🟡 **Fondo:** Amarillo claro (#FFF2CC)
- 📝 **Indicador:** * antes del nombre

### Campos de Solo Lectura
- ⚫ **Fondo:** Gris (#E7E6E6)
- 🔒 **Texto:** Gris oscuro (#7F7F7F)
- ❌ **No editable**

### Campos con Dropdown
- 📋 **Flecha:** ▼ visible al hacer click
- ✅ **Validación:** Solo valores de la lista
- ❌ **Otros valores:** Rechazados

### Bordes de Datos
- 🔲 **Todas las celdas:** Borde delgado gris (#CCCCCC)

## Hojas del Archivo

### Hoja 1: "[NOMBRE FORMULARIO]"
- Visible
- Datos para llenar
- Validaciones activas

### Hoja 2: "DATOS_SISTEMA"
- Oculta
- Contiene listas para dropdowns
- No modificable por el usuario

## Ejemplo de Dropdown en Excel

Cuando el usuario hace click en una celda con dropdown:

```
╔═══════════════════════╗
║ * MUNICIPIO           ║
║ DILIGENCIAMIENTO      ║
╠═══════════════════════╣
║ BOGOTÁ            [▼] ║ ← Click aquí
╠═══════════════════════╣
║ ┌─────────────────┐   ║
║ │ ACACÍAS         │   ║
║ │ AGUAZUL         │   ║
║ │ ALBANIA         │   ║
║ │ BOGOTÁ          │◄── Selección actual
║ │ CALI            │   ║
║ │ MEDELLÍN        │   ║
║ │ ...             │   ║
║ └─────────────────┘   ║
╚═══════════════════════╝
```

## Validaciones Activas

### Campo Numérico (num_doc_est)
```
╔═══════════════════════╗
║ * No. DOCUMENTO       ║
║ ESTUDIANTE            ║
╠═══════════════════════╣
║ 1234567890        ✓   ║ ← Válido
╠═══════════════════════╣
║ ABC123            ✗   ║ ← Error: Solo números
╚═══════════════════════╝

Mensaje de error: "Por favor ingrese un número entero"
```

### Campo Select
```
╔═══════════════════════╗
║ * GESTACIÓN EN MESES  ║
╠═══════════════════════╣
║ 9 MESES           ✓   ║ ← Válido (de la lista)
╠═══════════════════════╣
║ 10 MESES          ✗   ║ ← Error: No está en lista
╚═══════════════════════╝

Mensaje de error: "Por favor seleccione un valor de la lista"
```

## Flujo de Usuario

### 1. Descargar Plantilla
```
Usuario hace click en "Descargar Plantilla Excel"
   ↓
Sistema genera archivo .xlsx
   ↓
Navegador descarga: Plantilla_PrePostnatales_2026-01-26_143022.xlsx
```

### 2. Llenar Datos
```
Abrir archivo en Excel
   ↓
Ver estudiantes precargados (documento + nombre)
   ↓
Completar campos obligatorios (marcados con *)
   ↓
Usar dropdowns para selects
   ↓
Agregar más filas si se necesita (filas vacías disponibles)
   ↓
Guardar archivo
```

### 3. Subir Archivo
```
Usuario hace click en "Cargar Excel Masivo"
   ↓
Aparece formulario de carga
   ↓
Seleccionar archivo .xlsx
   ↓
Click en "Subir y Procesar"
   ↓
Sistema valida cada fila
   ↓
Muestra resultado:
  - X registros insertados ✓
  - Y registros con errores ✗
  - Lista detallada de errores
```

## Ejemplo Real: PrePostnatales

### Datos en el Excel (Vista Usuario)

| * No. DOCUMENTO | NOMBRE ESTUDIANTE    | * MUNICIPIO | * EDAD MADRE | * GESTACIÓN | * EMBARAZO PRESENTÓ        | * LACTANCIA | * GATEÓ | * CAMINÓ |
|----------------|---------------------|-------------|-------------|------------|---------------------------|------------|---------|----------|
| 1234567890     | JUAN PEREZ GOMEZ    | BOGOTÁ      | 25          | 9 MESES    | PARTO A TIEMPO (NATURAL)  | 6          | SI      | 12       |
| 9876543210     | MARIA LOPEZ RUIZ    | MEDELLÍN    | 30          | 9 MESES    | PARTO ASISTIDO (CESÁREA)  | 12         | SI      | 11       |
| 5555555555     | CARLOS DIAZ TORRES  | CALI        | 22          | 8 MESES    | SIN ANTECEDENTE           | 0          | NO      | 0        |

### Datos Insertados en BD (Automático)

```sql
INSERT INTO prePostnatales (
    num_doc_est,
    mun_dig_prePostnatales,
    edad_madre_prePostnatales,
    gestacion_meses_prePostnatales,
    embarazo_mama_prePostnatales,
    lactancia_mama_prePostnatales,
    gateo_prePostnatales,
    camino_prePostnatales,
    -- CAMPOS AUTOMÁTICOS:
    fecha_dig_prePostnatales,           -- 2026-01-26 14:30:22
    nombre_encuestador_prePostnatales,  -- JUAN DOCENTE
    rol_encuestador_prePostnatales,     -- DOCENTE
    fecha_alta_prePostnatales,          -- 2026-01-26 14:30:22
    fecha_edit_prePostnatales,          -- 0000-00-00 00:00:00
    id_usu                              -- 123
) VALUES (
    '1234567890',
    'BOGOTÁ',
    '25',
    '9 MESES',
    'PARTO A TIEMPO (NATURAL)',
    '6',
    'SI',
    '12',
    '2026-01-26 14:30:22',
    'JUAN DOCENTE',
    'DOCENTE',
    '2026-01-26 14:30:22',
    '0000-00-00 00:00:00',
    123
);
```

## Ventajas del Sistema

✅ **Formato Profesional**
- Instrucciones claras
- Colores distintivos
- Bordes y alineación

✅ **Validaciones Integradas**
- Dropdowns con opciones válidas
- Validación de números
- Campos obligatorios marcados

✅ **Datos Precargados**
- Estudiantes del sistema
- Municipios de la BD
- Campos de solo lectura protegidos

✅ **Facilidad de Uso**
- No requiere conocimientos técnicos
- Interfaz familiar (Excel)
- Errores claros y descriptivos

✅ **Eficiencia**
- Carga masiva de 100+ registros
- Procesamiento rápido
- Reporte detallado de resultados

---

**Sistema de Carga Masiva Excel - FICHA 2026** 🚀
