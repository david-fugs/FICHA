<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../../index.php");
    exit();
}

$num_doc_est = $_GET['num_doc_est'];

include("../../conexion.php");
// Escapar el valor para mayor seguridad
$num_doc_est = $mysqli->real_escape_string($num_doc_est);

$query = "SELECT * FROM prePostnatales WHERE num_doc_est = '$num_doc_est'";
$result = $mysqli->query($query);

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
        function confirmarEliminacion(id, num_doc_est) {
            if (confirm('¿Está seguro que desea eliminar esta encuesta? Esta acción no se puede deshacer.')) {
                window.location.href = 'deleteSurvey.php?id=' + id + '&campo=prepost&num_doc_est=' + num_doc_est;
            }
        }
    </script>
</head>

<body>
    <div class="container">
        <h1>Detalles de Encuestas Aplicadas</h1>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>No.</th>
                        <th>ID</th>
                        <th>Fecha de Digitación</th>
                        <th>Municipio de Digitación</th>
                        <th>Nombre del Encuestador</th>
                        <th>Rol del Encuestador</th>
                        <th>Edad de la Madre</th>
                        <th>Meses de Gestación</th>
                        <th>Embarazo de la Madre</th>
                        <th>Lactancia de la Madre</th>
                        <th>Gateo</th>
                        <th>Camino</th>
                        <th>Documento del Estudiante</th>
                        <th>Estado</th>
                        <th>Fecha de Aplicación</th>
                        <th>Fecha de Modificación</th>
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
                            <td>{$row['id_prePostnatales']}</td>
                            <td>{$row['fecha_dig_prePostnatales']}</td>
                            <td>{$row['mun_dig_prePostnatales']}</td>
                            <td>{$row['nombre_encuestador_prePostnatales']}</td>
                            <td>{$row['rol_encuestador_prePostnatales']}</td>
                            <td>{$row['edad_madre_prePostnatales']}</td>
                            <td>{$row['gestacion_meses_prePostnatales']}</td>
                            <td>{$row['embarazo_mama_prePostnatales']}</td>
                            <td>{$row['lactancia_mama_prePostnatales']}</td>
                            <td>{$row['gateo_prePostnatales']}</td>
                            <td>{$row['camino_prePostnatales']}</td>
                            <td>{$row['num_doc_est']}</td>
                            <td>{$row['estado_prePostnatales']}</td>
                            <td>{$row['fecha_alta_prePostnatales']}</td>
                            <td>{$row['fecha_edit_prePostnatales']}</td> ";
                        echo "<td><a href='exportar/exportarIndivPrePos.php?id_prePost={$row['id_prePostnatales']}' class='btn btn-success'>Exportar</a></td>";
                        echo "
                        <td>
                            <a href='#' onclick='confirmarEliminacion({$row['id_prePostnatales']}, {$row['num_doc_est']})'>
                                <img src='../../img/delete1.png' width='28' height='28'>
                            </a>
                        </td>
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

<?php
$stmt->close();
$mysqli->close();
?>