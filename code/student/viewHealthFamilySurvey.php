<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../../index.php");
    exit();
}

$num_doc_est = $_GET['num_doc_est'];
include("../../conexion.php");
if ($mysqli->connect_error) {
    die("Conexión fallida: " . $mysqli->connect_error);
}

// Opcional: para evitar inyección SQL (aunque no uses prepare)
$num_doc_est = $mysqli->real_escape_string($num_doc_est);

$query = "SELECT * FROM familiasalud WHERE num_doc_est = '$num_doc_est'";
$result = $mysqli->query($query);

function Si1No2($value)
{
    if ($value == 1) {
        return "SI";
    } else {
        return "NO";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Detalles de Encuestas</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <style>
        .table th,
        .table td {
            white-space: nowrap;
        }

        .table-responsive {
            overflow-x: auto;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1>Detalles de Encuestas Aplicadas</h1>
        <div class="table-responsive mt-5">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>No.</th>
                        <th>ID</th>
                        <th>Fecha de Digitación</th>
                        <th>Nombre Estudiante</th>
                        <th>Municipio de Digitación</th>
                        <th>Nombre del Encuestador</th>
                        <th>Rol del Encuestador</th>
                        <th>Relacion con Madre</th>
                        <th>Relacion con Padre</th>
                        <th>Relacion con Hermanos</th>
                        <th>Relacion con Abuelos</th>
                        <th>Relacion con Tios</th>
                        <th>Relacion con Otros familiares</th>
                        <th>Situaciones que Afectan Aprendizaje</th>
                        <th>Presenta Discapacidad</th>
                        <th>Beneficiario PAE</th>
                        <th>Momentos de comida dia</th>
                        <th>Afiliado EPS </th>
                        <th>Nombre EPS</th>
                        <th>Otra Eps</th>
                        <th>Sistema Afiliado</th>
                        <th>Presenta Diagnóstico</th>
                        <th>Cual Diagnostico</th>
                        <th>Asiste Terapias</th>
                        <th>Frecuencia Terapias</th>
                        <th>Esta siendo atendido por alguna condicion?</th>
                        <th>Frecuencia que es atentido </th>
                        <th>Presenta alergia</th>
                        <th>Presenta alergia a que</th>
                        <th>Esquema vacunacion completo</th>
                        <th>Tipo de sangre</th>
                        <th>Fecha de Aplicación</th>
                        <th>Fecha de Modificación</th>
                        <th>EDITAR</th>
                        <th>Exportar Excel</th>
                        <th>ELIMINAR</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$i}</td>
                            <td>{$row['id_salud_familiaSalud']}</td>
                            <td>{$row['fechacreacion_familiaSalud']}</td>
                            <td>{$row['nom_ape_est']}</td>
                            <td>{$row['mun_dig_familiaSalud']}</td>
                            <td>{$row['nombre_encuestador_familiaSalud']}</td>
                            <td>{$row['rol_encuestador_familiaSalud']}</td>
                            <td>{$row['relacion_madre_familiaSalud']}</td>
                            <td>{$row['relacion_padre_familiaSalud']}</td>
                            <td>{$row['relacion_hermanos_familiaSalud']}</td>
                            <td>{$row['relacion_abuelos_familiaSalud']}</td>
                            <td>{$row['relacion_tios_familiaSalud']}</td>
                            <td>{$row['relacion_otros_familiaSalud']}</td>
                            <td>{$row['afecta_aprendizaje_familiaSalud']}</td>
                            ";
                        echo '<td>' . Si1No2($row['discapacidad_est_familiaSalud']) . '</td>';
                        echo '<td>' . Si1No2($row['beneficiario_pae_familiaSalud']) . '</td>';
                        echo "<td>{$row['comida_dia_familiaSalud']}</td>";
                        echo '<td>' . Si1No2($row['eps_estudiante_familiaSalud']) . '</td>';
                        echo "<td>{$row['nombre_eps_familiaSalud']}</td>
                        <td>{$row['cual_eps_familiaSalud']}</td>
                        <td>{$row['afiliado_eps_familiaSalud']}</td>";
                        echo '<td>' . Si1No2($row['presenta_diagnostico_familiaSalud']) . '</td>';
                        echo "<td>{$row['diagnostico_familiaSalud']}</td>";
                        echo '<td>' . Si1No2($row['terapia_familiaSalud']) . '</td>';
                        echo "<td>{$row['frecuencia_terapia_familiaSalud']}</td>";
                        echo '<td>' . Si1No2($row['condicion_particular_familiaSalud']) . '</td>';
                        echo "<td>{$row['frecuencia_atencion_familiaSalud']}</td>";
                        echo '<td>' . Si1No2($row['alergia_familiaSalud']) . '</td>';
                        echo "<td>{$row['tipo_alergia_familiaSalud']}</td>";
                        echo '<td>' . Si1No2($row['vacunacion_familiaSalud']) . '</td>';
                        echo "<td>{$row['sangre_familiaSalud']}</td>";
                        echo "<td>{$row['fechacreacion_familiaSalud']}</td>";
                        echo "<td>{$row['fechaedicion_familiaSalud']}</td>";
                        echo '<td><a href="editHealthFamily.php?num_doc_est=' . $row['num_doc_est'] . '&idSalud=' . $row['id_salud_familiaSalud'] . '"><img src="../../img/editar.png" width=28 height=28></a></td>';
                        echo "<td><a href='exportar/exportarIndivHealthFam.php?id_salud_familiaSalud={$row['id_salud_familiaSalud']}' class='btn btn-success'>Exportar</a></td>";
                        echo "<td><button class='btn btn-danger' onclick='confirmarEliminacion({$row['id_salud_familiaSalud']}, {$row['num_doc_est']})'><img src='../../img/delete1.png' width=28 height=28></button></td>
                         </tr>";
                        $i++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <a href="javascript:history.back()" class="btn btn-primary">Volver</a>
    </div>
</body>

</html>
<script>
    function confirmarEliminacion(id, num_doc) {
        if (confirm('¿Está seguro que desea eliminar esta encuesta? Esta acción no se puede deshacer.')) {
            // Realizar una solicitud AJAX para eliminar la encuesta
            var xhr = new XMLHttpRequest();
            xhr.open("GET", 'deleteSurvey.php?id=' + id + '&campo=familiasalud' + '&num_doc_est=' + num_doc, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Recargar la página una vez que la eliminación sea exitosa
                    window.location.reload();
                }
            };
            xhr.send();
        }
    }
</script>
<?php
$stmt->close();
$mysqli->close();
?>