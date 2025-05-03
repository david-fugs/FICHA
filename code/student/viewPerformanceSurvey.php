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

// Escapar el valor para mayor seguridad
$num_doc_est = $mysqli->real_escape_string($num_doc_est);

$query = "SELECT * FROM desempeno WHERE num_doc_est = '$num_doc_est'";
$result = $mysqli->query($query);

function Si1No2($value)
{
    if ($value == 1) {
        return "Si";
    } else {
        return "No";
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
        <h3 class="mt-4" > Las Materias se asignan (COMPRENSION,PARTICIPACION,APLICACION,CONSISTENCIA) </h3>
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

                        <th>Desempeño Ciencias naturales</th>
                        <th>Desempeño Ciencias sociales</th>
                        <th>Desempeño Educacion Fisica</th>
                        <th> Desempeño Etica y valores</th>
                        <th> Desempeño Religion</th>
                        <th>Desempeño Artes</th>
                        <th>Desempeño Humanidades</th>
                        <th>Desempeño Matematicas</th>
                        <th>Desempeño Fisica</th>
                        <th>Desempeño Algebra</th>
                        <th>Desempeño Calculo</th>
                        <th>Desempeño Ingles</th>
                        <th>Desempeño Tecnologia Informatica</th>
                        <th>Desempeño Emprendimiento</th>
                        <th>Desempeño Areas Tecnicas</th>
                        <th>Desempeño Filosofia</th>
                        <th>Desempeño Ciencias Economicas</th>
                        <th>Vinculado doble titulacion</th>
                        <th>Cual programa</th>
                        <th>Fecha de Aplicación</th>
                        <th>Fecha de Modificación</th>
                        <th>EDITAR</th>
                        <th>Exportar Excel</th>
                        <th>ELIMINAR</th>
                    </tr>
                    <tr>
           
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$i}</td>
                            <td>{$row['id_desempeno']}</td>
                            <td>{$row['fecha_dig_desempeno']}</td>
                            <td>{$row['nom_ape_est']}</td>
                            <td>{$row['mun_dig_desempeno']}</td>
                            <td>{$row['nombre_encuestador_desempeno']}</td>
                            <td>{$row['rol_encuestador_desempeno']}</td>
                             <td>{$row['comprension_ciencia_desempeno']}, {$row['participacion_ciencia_desempeno']}, {$row['aplicacion_ciencia_desempeno']}, {$row['consistencia_ciencia_desempeno']}</td>
                            <td>{$row['comprension_sociales_desempeno']}, {$row['participacion_sociales_desempeno']}, {$row['aplicacion_sociales_desempeno']}, {$row['consistencia_sociales_desempeno']}</td>
                            <td>{$row['comprension_edufisica_desempeno']}, {$row['participacion_edufisica_desempeno']}, {$row['aplicacion_edufisica_desempeno']}, {$row['consistencia_edufisica_desempeno']}</td>
                            <td>{$row['comprension_etica_desempeno']}, {$row['participacion_etica_desempeno']}, {$row['aplicacion_etica_desempeno']}, {$row['consistencia_etica_desempeno']}</td>

                            <td>{$row['comprension_religion_desempeno']}, {$row['participacion_religion_desempeno']}, {$row['aplicacion_religion_desempeno']}, {$row['consistencia_religion_desempeno']}</td>
                            <td>{$row['comprension_artistica_desempeno']}, {$row['participacion_artistica_desempeno']}, {$row['aplicacion_artistica_desempeno']}, {$row['consistencia_artistica_desempeno']}</td>
                            <td>{$row['comprension_humanidades_desempeno']}, {$row['participacion_humanidades_desempeno']}, {$row['aplicacion_humanidades_desempeno']}, {$row['consistencia_humanidades_desempeno']}</td>
                            <td>{$row['comprension_matematicas_desempeno']}, {$row['participacion_matematicas_desempeno']}, {$row['aplicacion_matematicas_desempeno']}, {$row['consistencia_matematicas_desempeno']}</td>
                            <td>{$row['comprension_fisica_desempeno']}, {$row['participacion_fisica_desempeno']}, {$row['aplicacion_fisica_desempeno']}, {$row['consistencia_fisica_desempeno']}</td>
                            <td>{$row['comprension_algebra_desempeno']}, {$row['participacion_algebra_desempeno']}, {$row['aplicacion_algebra_desempeno']}, {$row['consistencia_algebra_desempeno']}</td>
                            <td>{$row['comprension_calculo_desempeno']}, {$row['participacion_calculo_desempeno']}, {$row['aplicacion_calculo_desempeno']}, {$row['consistencia_calculo_desempeno']}</td>
                            <td>{$row['comprension_ingles_desempeno']}, {$row['participacion_ingles_desempeno']}, {$row['aplicacion_ingles_desempeno']}, {$row['consistencia_ingles_desempeno']}</td>
                            <td>{$row['comprension_tecno_desempeno']}, {$row['participacion_tecno_desempeno']}, {$row['aplicacion_tecno_desempeno']}, {$row['consistencia_tecno_desempeno']}</td>
                            <td>{$row['comprension_emprendimiento_desempeno']}, {$row['participacion_emprendimiento_desempeno']}, {$row['aplicacion_emprendimiento_desempeno']}, {$row['consistencia_emprendimiento_desempeno']}</td>
                            <td>{$row['comprension_areastec_desempeno']}, {$row['participacion_areastec_desempeno']}, {$row['aplicacion_areastec_desempeno']}, {$row['consistencia_areastec_desempeno']}</td>
                            <td>{$row['comprension_filosofia_desempeno']}, {$row['participacion_filosofia_desempeno']}, {$row['aplicacion_filosofia_desempeno']}, {$row['consistencia_filosofia_desempeno']}</td>
                            <td>{$row['comprension_cienciaseco_desempeno']}, {$row['participacion_cienciaseco_desempeno']}, {$row['aplicacion_cienciaseco_desempeno']}, {$row['consistencia_cienciaseco_desempeno']}</td>
                            ";
                        echo '<td>' . Si1No2($row['doble_titu_desempeno']) . '</td>';
                        echo "<td>{$row['nom_dobletitu_desempeno']}</td>";
                        echo '<td>' . $row['fechacreacion_desempeno'] . '</td>';
                        echo '<td>' . $row['fechaedicion_desempeno'] . '</td>';
                        echo '<td><a href="editPerformance.php?num_doc_est=' . $row['num_doc_est'] . '&id_desempeno=' . $row['id_desempeno'] . '"><img src="../../img/editar.png" width=28 height=28></a></td>';
                        echo "<td><a href='exportar/exportarIndivPerform.php?id_desempeno={$row['id_desempeno']}' class='btn btn-success'>Exportar</a></td>";
                        echo "<td><button class='btn btn-danger' onclick='confirmarEliminacion({$row['id_desempeno']}.{$row['num_doc_est']})'><img src='../../img/delete1.png' width=28 height=28></button></td>";
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


    function confirmarEliminacion(id, num_doc) {
        if (confirm('¿Está seguro que desea eliminar esta encuesta? Esta acción no se puede deshacer.')) {
            // Realizar una solicitud AJAX para eliminar la encuesta
            var xhr = new XMLHttpRequest();
            xhr.open("GET", 'deleteSurvey.php?id=' + id+ '&campo=desempeno' + '&num_doc_est=' + num_doc, true);
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