<?php

require '../../vendor/autoload.php';
ini_set('memory_limit', '-1');
ini_set('max_execution_time', -1);

use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['excelFile']) || $_FILES['excelFile']['error'] !== UPLOAD_ERR_OK) {
        die(json_encode(['error' => 'Error al subir el archivo']));
    }

    $tempFile = $_FILES['excelFile']['tmp_name'];
    $reader = ReaderEntityFactory::createXLSXReader();
    $reader->open($tempFile);

    include("../../conexion.php");    $loteDatos = [];
    $contadorRegistros = 0;
    $loteTamano = 1000;
    $numerosDocumentoEnArchivo = []; // Array para almacenar los números de documento del archivo
    $totalFilasProcesadas = 0;
    $filasOmitidas = 0;
    $mapeoColumnas = []; // Mapeo de nombres de campos a índices de columnas

    echo "Iniciando lectura del archivo...<br>";
    flush();
    ob_flush();

    foreach ($reader->getSheetIterator() as $sheet) {
        echo "Procesando hoja: " . $sheet->getName() . "<br>";
        flush();
        ob_flush();
        
        foreach ($sheet->getRowIterator() as $row) {
            if ($contadorRegistros == 0) {
                // Leer los encabezados para mostrarlos
                $cells = $row->getCells();
                $encabezados = [];
                foreach ($cells as $cell) {
                    $encabezados[] = $cell->getValue();
                }
                
                // Crear mapeo automático de columnas
                // Mapeo EXACTO basado en los nombres de columnas del archivo SIMAT
                $mapeoExacto = [
                    'ANO_INF' => 0,
                    'MUN_CODIGO' => 1,
                    'MUNICIPIO' => 2,
                    'CODIGO_DANE' => 3,
                    'INSTITUCION' => 4,
                    'DANE_ANTERIOR' => 5,
                    'SEDE' => 6,
                    'CONS_SEDE' => 7,
                    'NOMBRE CONS_SEDE2' => 8,
                    'ZONA' => 9,
                    'TIPO_DOCUMENTO' => 10,
                    'NOMBRE TIPO_DOCUMENTO2' => 11,
                    'NRO_DOCUMENTO' => 12,
                    'EXP_DEPTO' => 13,
                    'EXP_MUN' => 14,
                    'NUM. EXP_MUN2' => 15,
                    'NUM. EXP_MUN3' => 16,
                    'NOMBRE EXP_MUN3' => 17,
                    'APELLIDO1' => 18,
                    'APELLIDO2' => 19,
                    'NOMBRE1' => 20,
                    'NOMBRE2' => 21,
                    'DIRECCION_RESIDENCIA' => 22,
                    'TEL' => 23,
                    'RES_DEPTO' => 24,
                    'RES_MUN' => 25,
                    'NUM. RES_MUN2' => 26,
                    'NUM. RES_MUN3' => 27,
                    'NOMBRE RES_MUN3' => 28,
                    'ESTRATO' => 29,
                    'SISBEN iv' => 30,
                    'FECHA_NACIMIENTO' => 31,
                    'EDAD EN AÑOS' => 32,
                    'NAC_DEPTO' => 33,
                    'UM. EXP_DEPTO22' => 34,
                    'NUM.NAC_MUN3' => 35,
                    'NAC_MUN' => 36,
                    'GENERO' => 37,
                    'NOMBRE GENERO2' => 38,
                    'POB_VICT_CONF' => 39,
                    'PROVIENE_SECTOR_PRIV' => 40,
                    'PROVIENE_OTR_MUN' => 41,
                    'TIPO_DISCAPACIDAD' => 42,
                    'NOMBRE TIPO_DISCAPACIDAD2' => 43,
                    'CAP_EXC' => 44,
                    'NOMBRE CAP_EXC2' => 45,
                    'ETNIA' => 46,
                    'NOMBRE ETNIA2' => 47,
                    'RES' => 48,
                    'NOMBRE_RESGUARDO' => 49,
                    'INS_FAMILIAR' => 50,
                    'TIPO_JORNADA' => 51,
                    'NOMBRE TIPO_JORNADA2' => 52,
                    'CARACTER' => 53,
                    'NOMBRE CARACTER2' => 54,
                    'ESPECIALIDAD' => 55,
                    'NOMBRE CARACTER22' => 56,
                    'GRADO' => 57,
                    'NOMBRE GRADO2' => 58
                ];
                
                // Buscar columnas por sus nombres exactos
                for ($i = 0; $i < count($encabezados); $i++) {
                    $encabezado_limpio = trim($encabezados[$i]);
                    if (isset($mapeoExacto[$encabezado_limpio])) {
                        // Verificar coincidencia
                        continue;
                    }
                }
                
                // Mapeo de campos de la BD a columnas del Excel
                $mapeoColumnas = [
                    'num_doc_est' => 12,  // NRO_DOCUMENTO
                    'tip_doc_est' => 10,  // TIPO_DOCUMENTO
                    'fecha_dig_est' => 0,  // ANO_INF
                    'mun_dig_est' => 2,   // MUNICIPIO
                    'nom_ape_est' => 18,  // APELLIDO1 (concatenaremos después)
                    'fec_nac_est' => 31,  // FECHA_NACIMIENTO
                    'ciu_nac_est' => 36,  // NAC_MUN
                    'dir_est' => 22,      // DIRECCION_RESIDENCIA
                    'mun_res_est' => 28,  // NOMBRE RES_MUN3
                    'estrato_est' => 29,  // ESTRATO
                    'zona_est' => 9,      // ZONA
                    'tel1_est' => 23,     // TEL
                    'tel2_est' => 23,     // TEL (mismo campo)
                    'email_est' => null,  // No existe en el archivo
                    'est_civ_est' => null, // No existe
                    'gen_est' => 37,      // GENERO
                    'eps_est' => null,    // No existe
                    'med_trans_est' => null, // No existe
                    'sisben_est' => 30,   // SISBEN iv
                    'cod_dane_ieSede' => 3, // CODIGO_DANE
                    'obs_est' => null,    // No existe
                    'poblacion_vulnerable_est' => 39, // POB_VICT_CONF
                    'discapacidad_est' => 42, // TIPO_DISCAPACIDAD
                    'capacidad_est' => 44,    // CAP_EXC
                    'trastorno_est' => 85,    // NOMBRE TRASTORNOS (si existe)
                    'etnia_est' => 46,        // ETNIA
                    'victima_est' => 39,      // POB_VICT_CONF
                    'jornada_est' => 51,      // TIPO_JORNADA
                    'caracter_media_est' => 53, // CARACTER
                    'especialidad_caracter_est' => 55, // ESPECIALIDAD
                    'grado_est' => 57,        // GRADO
                    'nom_grado_est' => 58     // NOMBRE GRADO2
                ];
                
                // Función para convertir índice a letra de columna Excel
                function getExcelColumn($num) {
                    $letter = '';
                    while ($num >= 0) {
                        $letter = chr($num % 26 + 65) . $letter;
                        $num = floor($num / 26) - 1;
                    }
                    return $letter;
                }
                
                echo "<div style='background: lightblue; padding: 15px; margin: 10px; border: 2px solid darkblue;'>";
                echo "<strong>MAPEO DE COLUMNAS CORREGIDO (MANUAL):</strong><br><br>";
                echo "<table border='1' style='border-collapse: collapse; font-size: 11px;'>";
                echo "<tr><th>Campo BD</th><th>Col Excel</th><th>Índice</th><th>Nombre en Excel</th></tr>";
                foreach ($mapeoColumnas as $campo => $indice) {
                    if ($indice !== null) {
                        $colExcel = getExcelColumn($indice);
                        $nombreCol = $encabezados[$indice] ?? 'N/A';
                        $highlight = ($campo == 'num_doc_est' || $campo == 'grado_est') ? "background: yellow;" : "";
                        echo "<tr style='$highlight'><td><strong>$campo</strong></td><td>$colExcel</td><td>[$indice]</td><td>" . htmlspecialchars($nombreCol) . "</td></tr>";
                    } else {
                        echo "<tr style='background: #ffeeee;'><td><strong>$campo</strong></td><td>-</td><td>-</td><td>NO EXISTE EN EXCEL</td></tr>";
                    }
                }
                echo "</table>";
                echo "</div>";
                flush();
                ob_flush();
                
                $contadorRegistros++;
                continue; // saltamos la fila de encabezados
            }

            // Leer todas las celdas de la fila
            $cells = $row->getCells();
            $data = [];
            
            // Extraer valores de cada celda
            foreach ($cells as $cell) {
                $cellValue = $cell->getValue();
                // Limpiar el valor
                if ($cellValue !== null) {
                    $cellValue = trim($cellValue);
                    $cellValue = trim($cellValue, "'");
                    $cellValue = trim($cellValue, "()");
                }
                $data[] = $cellValue;
            }

            // Debug: mostrar primera fila de datos
            if ($contadorRegistros == 1) {
                echo "Primera fila tiene " . count($data) . " columnas<br>";
                echo "Primeras 5 columnas: " . implode(" | ", array_slice($data, 0, 5)) . "<br>";
                flush();
                ob_flush();
            }

            // Verificar que tengamos el mapeo de columnas
            if (empty($mapeoColumnas)) {
                echo "ERROR: No se pudo crear el mapeo de columnas<br>";
                break;
            }
            
            $totalFilasProcesadas++;


            // Asignar valores usando el mapeo dinámico
            $num_doc_est = $data[$mapeoColumnas['num_doc_est']] ?? '';
            $tip_doc_est = $data[$mapeoColumnas['tip_doc_est']] ?? '';
            $fecha_dig_est = $data[$mapeoColumnas['fecha_dig_est']] ?? '';
            $mun_dig_est = $data[$mapeoColumnas['mun_dig_est']] ?? '';
            
            // Construir nombre completo concatenando apellidos y nombres
            $apellido1 = $data[18] ?? ''; // APELLIDO1
            $apellido2 = $data[19] ?? ''; // APELLIDO2
            $nombre1 = $data[20] ?? '';   // NOMBRE1
            $nombre2 = $data[21] ?? '';   // NOMBRE2
            $nom_ape_est = trim("$apellido1 $apellido2 $nombre1 $nombre2");
            
            $fec_nac_est = $data[$mapeoColumnas['fec_nac_est']] ?? '';
            $ciu_nac_est = $data[$mapeoColumnas['ciu_nac_est']] ?? '';
            $dir_est = $data[$mapeoColumnas['dir_est']] ?? '';
            $mun_res_est = $data[$mapeoColumnas['mun_res_est']] ?? '';
            $estrato_est = $data[$mapeoColumnas['estrato_est']] ?? '';
            $zona_est = $data[$mapeoColumnas['zona_est']] ?? '';
            $tel1_est = $data[$mapeoColumnas['tel1_est']] ?? '';
            $tel2_est = $data[$mapeoColumnas['tel2_est']] ?? '';
            $email_est = $mapeoColumnas['email_est'] !== null ? ($data[$mapeoColumnas['email_est']] ?? '') : '';
            $est_civ_est = $mapeoColumnas['est_civ_est'] !== null ? ($data[$mapeoColumnas['est_civ_est']] ?? '') : '';
            $gen_est = $data[$mapeoColumnas['gen_est']] ?? '';
            $eps_est = $mapeoColumnas['eps_est'] !== null ? ($data[$mapeoColumnas['eps_est']] ?? '') : '';
            $med_trans_est = $mapeoColumnas['med_trans_est'] !== null ? ($data[$mapeoColumnas['med_trans_est']] ?? '') : '';
            $sisben_est = $data[$mapeoColumnas['sisben_est']] ?? '';
            $cod_dane_ieSede = $data[$mapeoColumnas['cod_dane_ieSede']] ?? '';
            $obs_est = $mapeoColumnas['obs_est'] !== null ? ($data[$mapeoColumnas['obs_est']] ?? '') : '';
            $poblacion_vulnerable_est = $data[$mapeoColumnas['poblacion_vulnerable_est']] ?? '';
            $discapacidad_est = $data[$mapeoColumnas['discapacidad_est']] ?? '';
            $capacidad_est = $data[$mapeoColumnas['capacidad_est']] ?? '';
            $trastorno_est = $mapeoColumnas['trastorno_est'] !== null && isset($data[$mapeoColumnas['trastorno_est']]) ? $data[$mapeoColumnas['trastorno_est']] : '';
            $etnia_est = $data[$mapeoColumnas['etnia_est']] ?? '';
            $victima_est = $data[$mapeoColumnas['victima_est']] ?? '';
            $jornada_est = $data[$mapeoColumnas['jornada_est']] ?? '';
            $caracter_media_est = $data[$mapeoColumnas['caracter_media_est']] ?? '';
            $especialidad_caracter_est = $data[$mapeoColumnas['especialidad_caracter_est']] ?? '';
            $grado_est = $data[$mapeoColumnas['grado_est']] ?? '';
            $nom_grado_est = $data[$mapeoColumnas['nom_grado_est']] ?? '';
            
            // Debug específico para documento 1088031555
            if ($num_doc_est == '1088031555') {
                echo "<div style='background: yellow; padding: 10px; margin: 10px; border: 2px solid red;'>";
                echo "<strong>DEBUG - Documento 1088031555 encontrado en fila " . $contadorRegistros . "</strong><br>";
                echo "Total columnas en data: " . count($data) . "<br>";
                echo "Índice usado para num_doc_est: " . $mapeoColumnas['num_doc_est'] . " (Valor: [$num_doc_est])<br>";
                echo "Índice usado para grado_est: " . $mapeoColumnas['grado_est'] . " (Valor: [$grado_est])<br>";
                echo "Índice usado para nom_grado_est: " . $mapeoColumnas['nom_grado_est'] . " (Valor: [$nom_grado_est])<br>";
                echo "Nombre completo construido: [$nom_ape_est]<br>";
                echo "</div>";
                flush();
                ob_flush();
            }
            
            // Validar que el número de documento no esté vacío
            if (empty($num_doc_est)) {
                $filasOmitidas++;
                if ($filasOmitidas <= 5) {
                    echo "Fila omitida: número de documento vacío<br>";
                    flush();
                    ob_flush();
                }
                continue;
            }            $fecha_alta_est = date('Y-m-d H:i:s');
            $id_usu = 1;

            // Agregar el número de documento al array de seguimiento
            $numerosDocumentoEnArchivo[] = $num_doc_est;

            // Convertir fecha numérica o formato string
            if (is_numeric($fec_nac_est)) {
                $unixDate = ($fec_nac_est - 25569) * 86400;
                $fec_nac_est = date("Y-m-d", $unixDate);
            } else {
                $dateTime = DateTime::createFromFormat("Y-m-d", $fec_nac_est) ?: DateTime::createFromFormat("m/d/Y", $fec_nac_est);
                $fec_nac_est = $dateTime ? $dateTime->format("Y-m-d") : null;
            }            // Aquí guardamos un array con datos crudos, sin comillas ni paréntesis
            $loteDatos[] = [
                $num_doc_est,
                $tip_doc_est,
                $fecha_dig_est,
                $mun_dig_est,
                $nom_ape_est,
                $fec_nac_est,
                $ciu_nac_est,
                $dir_est,
                $mun_res_est,
                $estrato_est,
                $zona_est,
                $tel1_est,
                $tel2_est,
                $email_est,
                $est_civ_est,
                $gen_est,
                $eps_est,
                $med_trans_est,
                $sisben_est,
                $cod_dane_ieSede,
                $obs_est,
                $poblacion_vulnerable_est,
                $discapacidad_est,
                $capacidad_est,
                $trastorno_est,
                $etnia_est,
                $victima_est,
                $jornada_est,
                $caracter_media_est,
                $especialidad_caracter_est,
                $grado_est,
                $nom_grado_est,
                $id_usu,
                1, // estado_est = 1 (activo)
                0, // estado_prepostnatales = 0 (encuesta pendiente)
                0, // estado_entornohogar = 0 (encuesta pendiente)
                0, // estado_familiasalud = 0 (encuesta pendiente)
                0, // estado_educacion = 0 (encuesta pendiente)
                0, // estado_desempeno = 0 (encuesta pendiente)
                0, // estado_preescolar = 0 (encuesta pendiente)
                0, // estado_personal = 0 (encuesta pendiente)
                0  // estado_preguntas = 0 (encuesta pendiente)
            ];

            $contadorRegistros++;
            if ($contadorRegistros % 1000 == 0) {
                echo "Procesadas $contadorRegistros filas totales ($totalFilasProcesadas registros válidos, $filasOmitidas omitidas)...<br>";
                flush();
                ob_flush();
            }            if (count($loteDatos) >= $loteTamano) {
                procesarLote($loteDatos, $mysqli);
                $loteDatos = [];
            }
        }
    }
    $reader->close();

    if (!empty($loteDatos)) {
        procesarLote($loteDatos, $mysqli);
    }

    echo "<br>Total de filas procesadas: $totalFilasProcesadas<br>";
    echo "Total de documentos en archivo: " . count($numerosDocumentoEnArchivo) . "<br>";
    echo "Filas omitidas: $filasOmitidas<br>";
    flush();
    ob_flush();

    // Actualizar los estudiantes que NO están en el archivo Excel
    actualizarEstudiantesNoEnArchivo($numerosDocumentoEnArchivo, $mysqli);

    echo json_encode(["finalizado" => "Carga completada"]);
    flush();
    ob_flush();
}
function procesarLote(array $loteDatos, mysqli $mysqli)
{
    if (empty($loteDatos)) return;

    $valuesList = [];
    $debug_doc = false;

    foreach ($loteDatos as $fila) {
        // Verificar si este lote contiene el documento de debug
        if ($fila[0] == '1088031555') {
            $debug_doc = true;
            echo "<div style='background: cyan; padding: 10px; margin: 10px; border: 2px solid blue;'>";
            echo "<strong>DEBUG - Procesando lote que contiene documento 1088031555</strong><br>";
            echo "Valor num_doc_est (índice 0): [" . $fila[0] . "]<br>";
            echo "Valor grado_est (índice 30): [" . $fila[30] . "]<br>";
            echo "Valor nom_grado_est (índice 31): [" . $fila[31] . "]<br>";
            echo "Valor id_usu (índice 32): [" . $fila[32] . "]<br>";
            echo "Valor estado_est (índice 33): [" . $fila[33] . "]<br>";
            echo "Valor estado_educacion (índice 37): [" . $fila[37] . "]<br>";
            echo "Total de valores en el array: " . count($fila) . "<br>";
            echo "</div>";
            flush();
            ob_flush();
        }
        
        $escaped = array_map(function ($valor) use ($mysqli) {
            return "'" . $mysqli->real_escape_string($valor) . "'";
        }, $fila);
        $valuesList[] = "(" . implode(", ", $escaped) . ")";
    }

    $valuesString = implode(", ", $valuesList);    $sql = "INSERT INTO estudiantes (
        num_doc_est, tip_doc_est, fecha_dig_est, mun_dig_est, nom_ape_est, fec_nac_est, ciu_nac_est,
        dir_est, mun_res_est, estrato_est, zona_est, tel1_est, tel2_est, email_est, est_civ_est,
        gen_est, eps_est, med_trans_est, sisben_est, cod_dane_ieSede, obs_est, poblacion_vulnerable_est,
        discapacidad_est, capacidad_est, trastorno_est, etnia_est, victima_est, jornada_est,
        caracter_media_est, especialidad_caracter_est, grado_est, nom_grado_est, id_usu, estado_est,
        estado_prepostnatales, estado_entornohogar, estado_familiasalud, estado_educacion, 
        estado_desempeno, estado_preescolar, estado_personal, estado_preguntas
    ) VALUES $valuesString
    ON DUPLICATE KEY UPDATE 
        tip_doc_est = VALUES(tip_doc_est),
        fecha_dig_est = VALUES(fecha_dig_est),
        mun_dig_est = VALUES(mun_dig_est),
        nom_ape_est = VALUES(nom_ape_est),
        fec_nac_est = VALUES(fec_nac_est),
        ciu_nac_est = VALUES(ciu_nac_est),
        dir_est = VALUES(dir_est),
        mun_res_est = VALUES(mun_res_est),
        estrato_est = VALUES(estrato_est),
        zona_est = VALUES(zona_est),
        tel1_est = VALUES(tel1_est),
        tel2_est = VALUES(tel2_est),
        email_est = VALUES(email_est),
        est_civ_est = VALUES(est_civ_est),
        gen_est = VALUES(gen_est),
        eps_est = VALUES(eps_est),
        med_trans_est = VALUES(med_trans_est),
        sisben_est = VALUES(sisben_est),
        cod_dane_ieSede = VALUES(cod_dane_ieSede),
        obs_est = VALUES(obs_est),
        poblacion_vulnerable_est = VALUES(poblacion_vulnerable_est),
        discapacidad_est = VALUES(discapacidad_est),
        capacidad_est = VALUES(capacidad_est),
        trastorno_est = VALUES(trastorno_est),
        etnia_est = VALUES(etnia_est),
        victima_est = VALUES(victima_est),
        jornada_est = VALUES(jornada_est),
        caracter_media_est = VALUES(caracter_media_est),
        especialidad_caracter_est = VALUES(especialidad_caracter_est),
        grado_est = VALUES(grado_est),
        nom_grado_est = VALUES(nom_grado_est),
        id_usu = VALUES(id_usu),
        estado_est = VALUES(estado_est),
        estado_prepostnatales = VALUES(estado_prepostnatales),
        estado_entornohogar = VALUES(estado_entornohogar),
        estado_familiasalud = VALUES(estado_familiasalud),
        estado_educacion = VALUES(estado_educacion),
        estado_desempeno = VALUES(estado_desempeno),
        estado_preescolar = VALUES(estado_preescolar),
        estado_personal = VALUES(estado_personal),
        estado_preguntas = VALUES(estado_preguntas),
        fecha_edit_est = NOW()
    ";

    if (!$mysqli->query($sql)) {
        echo "Error en inserción de lote: " . $mysqli->error . "<br>";
    } else {
        echo "Insertado lote de " . count($loteDatos) . " registros<br>";
        
        if ($debug_doc) {
            echo "<div style='background: lightgreen; padding: 10px; margin: 10px; border: 2px solid green;'>";
            echo "<strong>DEBUG - Lote con documento 1088031555 procesado exitosamente</strong><br>";
            echo "Filas afectadas: " . $mysqli->affected_rows . "<br>";
            
            // Verificar el valor en la BD
            $checkQuery = "SELECT num_doc_est, grado_est, nom_grado_est, estado_est, estado_educacion, 
                          estado_prepostnatales, cod_dane_ieSede, fecha_edit_est 
                          FROM estudiantes WHERE num_doc_est = '1088031555'";
            $checkResult = $mysqli->query($checkQuery);
            if ($checkResult && $row = $checkResult->fetch_assoc()) {
                echo "<br><strong>Valores actuales en la BD:</strong><br>";
                echo "num_doc_est: " . $row['num_doc_est'] . "<br>";
                echo "grado_est: " . $row['grado_est'] . "<br>";
                echo "nom_grado_est: " . $row['nom_grado_est'] . "<br>";
                echo "estado_est: " . $row['estado_est'] . " (1=activo, 0=inactivo)<br>";
                echo "estado_educacion: " . $row['estado_educacion'] . " (0=pendiente, 1=realizada)<br>";
                echo "estado_prepostnatales: " . $row['estado_prepostnatales'] . " (0=pendiente, 1=realizada)<br>";
                echo "cod_dane_ieSede: " . $row['cod_dane_ieSede'] . "<br>";
                echo "fecha_edit_est: " . $row['fecha_edit_est'] . "<br>";
            }
            echo "</div>";
            flush();
            ob_flush();
        }
    }

    flush();
    ob_flush();
}
function actualizarEstudiantesNoEnArchivo(array $numerosDocumentoEnArchivo, mysqli $mysqli)
{
    if (empty($numerosDocumentoEnArchivo)) {
        echo "No hay documentos en el archivo para comparar<br>";
        return;
    }

    // Escapar los números de documento para la consulta
    $numerosEscapados = array_map(function($num) use ($mysqli) {
        return "'" . $mysqli->real_escape_string($num) . "'";
    }, $numerosDocumentoEnArchivo);

    $numerosString = implode(", ", $numerosEscapados);
    
    // Obtener los códigos DANE únicos del archivo actual
    $codigosDane = [];
    $queryDane = "SELECT DISTINCT cod_dane_ieSede FROM estudiantes WHERE num_doc_est IN ($numerosString)";
    $resultDane = $mysqli->query($queryDane);
    if ($resultDane) {
        while ($row = $resultDane->fetch_assoc()) {
            $codigosDane[] = "'" . $mysqli->real_escape_string($row['cod_dane_ieSede']) . "'";
        }
    }
    
    if (empty($codigosDane)) {
        echo "No se pudieron obtener los códigos DANE del archivo<br>";
        return;
    }
    
    $codigosDaneString = implode(", ", $codigosDane);

    // Actualizar SOLO los estudiantes que:
    // 1. NO están en la lista del archivo (num_doc_est NOT IN)
    // 2. Y pertenecen a las mismas sedes que los estudiantes del archivo (cod_dane_ieSede IN)
    // IMPORTANTE: NO modificamos los estados de encuestas, solo marcamos como inactivo
    $sqlUpdate = "UPDATE estudiantes SET 
        estado_est = 0
        WHERE num_doc_est NOT IN ($numerosString)
        AND cod_dane_ieSede IN ($codigosDaneString)
        AND estado_est = 1";  // Solo actualizar los que actualmente están activos

    if (!$mysqli->query($sqlUpdate)) {
        echo "Error al actualizar estudiantes no en archivo: " . $mysqli->error . "<br>";
    } else {
        $registrosAfectados = $mysqli->affected_rows;
        echo "Marcados como inactivos $registrosAfectados estudiantes que no estaban en el archivo (de las mismas sedes)<br>";
    }

    flush();
    ob_flush();
}
