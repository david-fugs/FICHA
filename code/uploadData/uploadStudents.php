<?php

require '../../vendor/autoload.php';
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 300);

use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['excelFile']) || $_FILES['excelFile']['error'] !== UPLOAD_ERR_OK) {
        die(json_encode(['error' => 'Error al subir el archivo']));
    }

    $tempFile = $_FILES['excelFile']['tmp_name'];
    $reader = ReaderEntityFactory::createXLSXReader();
    $reader->open($tempFile);

    include("../../conexion.php");

    $loteDatos = [];
    $contadorRegistros = 0;
    $loteTamano = 1000;

    foreach ($reader->getSheetIterator() as $sheet) {
        foreach ($sheet->getRowIterator() as $row) {
            if ($contadorRegistros == 0) {
                $contadorRegistros++;
                continue; // saltamos la fila de encabezados
            }

            $cell = $row->getCellAtIndex(0); // Columna CH
            $cellValue = $cell ? $cell->getValue() : null;

            $data = str_getcsv($cellValue);
            // Limpiar paréntesis y comillas innecesarias
            $data = array_map(function ($val) {
                $val = trim($val);                // eliminar espacios
                $val = trim($val, "'");           // eliminar comillas simples
                $val = trim($val, "()");          // eliminar paréntesis
                return $val;
            }, $data);

            // Asegurarte de que solo tomas los primeros 33 valores necesarios
            $data = array_slice($data, 0, 33);
            if (count($data) < 33) {
                continue; // evita errores si hay datos incompletos
            }


            list(
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
                $nom_grado_est
            ) = $data;

            $fecha_alta_est = date('Y-m-d H:i:s');
            $id_usu = 1;

            // Convertir fecha numérica o formato string
            if (is_numeric($fec_nac_est)) {
                $unixDate = ($fec_nac_est - 25569) * 86400;
                $fec_nac_est = date("Y-m-d", $unixDate);
            } else {
                $dateTime = DateTime::createFromFormat("Y-m-d", $fec_nac_est) ?: DateTime::createFromFormat("m/d/Y", $fec_nac_est);
                $fec_nac_est = $dateTime ? $dateTime->format("Y-m-d") : null;
            }

            // Aquí guardamos un array con datos crudos, sin comillas ni paréntesis
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
                $id_usu
            ];

            $contadorRegistros++;
            if ($contadorRegistros % 1000 == 0) {
                echo "Procesados $contadorRegistros registros...<br>";
                flush();
                ob_flush();
            }


            if (count($loteDatos) >= $loteTamano) {
                procesarLote($loteDatos, $mysqli);
                $loteDatos = [];
            }
        }
    }
    $reader->close();

    if (!empty($loteDatos)) {
        procesarLote($loteDatos, $mysqli);
    }

    echo json_encode(["finalizado" => "Carga completada"]);
    flush();
    ob_flush();
}
function procesarLote(array $loteDatos, mysqli $mysqli)
{
    if (empty($loteDatos)) return;

    $valuesList = [];

    foreach ($loteDatos as $fila) {
        $escaped = array_map(function ($valor) use ($mysqli) {
            return "'" . $mysqli->real_escape_string($valor) . "'";
        }, $fila);
        $valuesList[] = "(" . implode(", ", $escaped) . ")";
    }

    $valuesString = implode(", ", $valuesList);

    $sql = "INSERT INTO estudiantes (
        num_doc_est, tip_doc_est, fecha_dig_est, mun_dig_est, nom_ape_est, fec_nac_est, ciu_nac_est,
        dir_est, mun_res_est, estrato_est, zona_est, tel1_est, tel2_est, email_est, est_civ_est,
        gen_est, eps_est, med_trans_est, sisben_est, cod_dane_ieSede, obs_est, poblacion_vulnerable_est,
        discapacidad_est, capacidad_est, trastorno_est, etnia_est, victima_est, jornada_est,
        caracter_media_est, especialidad_caracter_est, grado_est, nom_grado_est, id_usu
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
        id_usu = VALUES(id_usu)
    ";

    if (!$mysqli->query($sql)) {
        echo "Error en inserción de lote: " . $mysqli->error . "<br>";
    } else {
        echo "Insertado lote de " . count($loteDatos) . " registros<br>";
    }

    flush();
    ob_flush();
}
