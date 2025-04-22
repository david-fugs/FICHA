<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../../index.php");
    exit();
}

$num_doc_est = $_GET['num_doc_est'];

include("../../conexion.php");

include("../../conexion.php");
if ($mysqli->connect_error) {
    die("Conexión fallida: " . $mysqli->connect_error);
}

// Escapar el valor para mayor seguridad
$num_doc_est = $mysqli->real_escape_string($num_doc_est);

$query = "SELECT * FROM educacion WHERE num_doc_est = '$num_doc_est'";
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
    <script>
        function confirmarEliminacion(id_prePostnatales) {
            if (confirm('¿Está seguro que desea eliminar esta encuesta? Esta acción no se puede deshacer.')) {
                window.location.href = 'deleteSurvey.php?id_prePostnatales=' + id_prePostnatales;
            }
        }
    </script>
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

                        <th>Ha estado a otra institucion</th>
                        <th>Cual institucion</th>
                        <th>Cual fue la modalidad en institucion anterior</th>
                        <th>Asiste a programas complementarios</th>
                        <th>Cual programa complementario</th>
                        <th>Ha repetido años</th>
                        <th>Que años repitio</th>
                        <th>Reconocido algun talento</th>
                        <th>Cual talento</th>
                        <th>Vinculado a algun club o liga</th>
                        <th>Cual club o liga</th>


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
                            <td>{$row['id_educacion']}</td>
                            <td>{$row['fecha_dig_educacion']}</td>
                            <td>{$row['nom_ape_est']}</td>
                            <td>{$row['mun_dig_educacion']}</td>
                            <td>{$row['nombre_encuestador_educacion']}</td>
                            <td>{$row['rol_encuestador_educacion']}</td>";


                        echo '<td>' . Si1No2($row['vinculacion_inst_educacion']) . '</td>';
                        echo "<td>{$row['nom_inst_educacion']}</td>";
                        echo "<td>{$row['modalidad_inst_educacion']}</td>";
                        echo '<td>' . Si1No2($row['complementario_educacion']) . '</td>';
                        echo "<td>{$row['program_complement_educacion']}</td>";
                        echo '<td>' . Si1No2($row['repetir_year_educacion']) . '</td>';
                        echo "<td>{$row['anios_repet_educacion']}</td>";
                        echo '<td>' . Si1No2($row['talento_educacion']) . '</td>';
                        echo "<td>{$row['talento_descrip_educacion']}</td>";
                        echo '<td>' . Si1No2($row['vinculacion_club_educacion']) . '</td>';
                       echo '<td>' . Si1No2($row['club_descrip_educacion']) . '</td>';
                       echo '<td>' .$row['fechacreacion_educacion'] . '</td>';
                       echo '<td>' . $row['fechaedicion_educacion']. '</td>';
                        echo '<td><a href="editEducation.php?num_doc_est=' . $row['num_doc_est'] . '&id_educacion=' . $row['id_educacion'] . '"><img src="../../img/editar.png" width=28 height=28></a></td>';
                        echo "<td><a href='exportar/exportarIndivEducation.php?id_educacion={$row['id_educacion']}' class='btn btn-success'>Exportar</a></td>";
                        echo "<td><button class='btn btn-danger' onclick='confirmarEliminacion({$row['id_educacion']},{$row['num_doc_est']})'><img src='../../img/delete1.png' width=28 height=28></button></td>";
                        echo "</tr>";
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
    function confirmarEliminacion(id, num_doc_est) {
        if (confirm('¿Está seguro que desea eliminar esta encuesta? Esta acción no se puede deshacer.')) {
            // Realizar una solicitud AJAX para eliminar la encuesta
            var xhr = new XMLHttpRequest();
            xhr.open("GET", 'deleteSurvey.php?id=' + id + '&campo=educacion' + '&num_doc_est=' + num_doc_est, true);
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
