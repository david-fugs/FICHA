<?php

//==================== ESTA ERA LA FORMA QUE HACIA YO COLUMNA POR COLUMNA ====================
//==================== AHORA SE HACE CON UN BUCLE QUE RECORRE una CELDA DE LA FILA ========

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
            $data = [];
            foreach ($row->getCells() as $cell) {
                $data[] = $cell->getValue();
            }

            if ($contadorRegistros == 0) {
                $contadorRegistros++;
                continue;
            }

            list(
                $num_doc_est, $tip_doc_est, $fecha_dig_est, $mun_dig_est, $nom_ape_est, $fec_nac_est, 
                $ciu_nac_est, $dir_est, $mun_res_est, $estrato_est, $zona_est, $tel1_est, $tel2_est, 
                $email_est, $est_civ_est, $gen_est, $eps_est, $med_trans_est, $sisben_est, 
                $cod_dane_ieSede, $obs_est, $poblacion_vulnerable_est, $discapacidad_est, 
                $capacidad_est, $trastorno_est, $etnia_est, $victima_est, $jornada_est, 
                $caracter_media_est, $especialidad_caracter_est, $grado_est, $nom_grado_est
            ) = $data;

            $fecha_alta_est = date('Y-m-d H:i:s');
            $id_usu = 1;
            
            if (is_numeric($fec_nac_est)) {
                $unixDate = ($fec_nac_est - 25569) * 86400;
                $fec_nac_est = date("Y-m-d", $unixDate);
            } else {
                $dateTime = DateTime::createFromFormat("m/d/Y", $fec_nac_est);
                if ($dateTime) {
                    $fec_nac_est = $dateTime->format("Y-m-d");
                } else {
                    $fec_nac_est = null;
                }
            }

            $loteDatos[] = "('$num_doc_est', '$tip_doc_est', '$fecha_dig_est', '$mun_dig_est', '$nom_ape_est', '$fec_nac_est',
                            '$ciu_nac_est', '$dir_est', '$mun_res_est', '$estrato_est', '$zona_est', '$tel1_est', '$tel2_est',
                            '$email_est', '$est_civ_est', '$gen_est', '$eps_est', '$med_trans_est', '$sisben_est',
                            '$cod_dane_ieSede', '$obs_est', '$poblacion_vulnerable_est', '$discapacidad_est',
                            '$capacidad_est', '$trastorno_est', '$etnia_est', '$victima_est', '$jornada_est',
                            '$caracter_media_est', '$especialidad_caracter_est', '$grado_est', '$nom_grado_est', '$id_usu')";

            $contadorRegistros++;

            if (count($loteDatos) >= $loteTamano) {
                procesarLote($loteDatos, $mysqli);
                echo json_encode(["progreso" => $contadorRegistros]);
                flush();
                ob_flush();
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

function procesarLote($loteDatos, $mysqli)
{
    $sql = "INSERT INTO estudiantes (
        num_doc_est, tip_doc_est, fecha_dig_est, mun_dig_est, nom_ape_est, fec_nac_est, ciu_nac_est,
        dir_est, mun_res_est, estrato_est, zona_est, tel1_est, tel2_est, email_est, est_civ_est,
        gen_est, eps_est, med_trans_est, sisben_est, cod_dane_ieSede, obs_est, poblacion_vulnerable_est,
        discapacidad_est, capacidad_est, trastorno_est, etnia_est, victima_est, jornada_est,
        caracter_media_est, especialidad_caracter_est, grado_est, nom_grado_est, id_usu
    ) VALUES " . implode(", ", $loteDatos) . " 
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
        id_usu = VALUES(id_usu)";

    if (!$mysqli->query($sql)) {
        die(json_encode(["error" => "Error al insertar datos: " . $mysqli->error]));
    }
}
