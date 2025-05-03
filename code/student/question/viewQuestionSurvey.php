<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../../../index.php");
    exit();
}

$num_doc_est = $_GET['num_doc_est'];

include("../../../conexion.php");

// Escapar el valor para mayor seguridad
$num_doc_est = $mysqli->real_escape_string($num_doc_est);

$query = "SELECT * FROM preguntas WHERE num_doc_est = '$num_doc_est'";
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
    
    <link rel="stylesheet" href="../../../css/bootstrap.min.css">
    <style>
        .table th,
        .table td {
            white-space: nowrap;
        }

        .table-responsive {
            overflow-x: auto;
        }
        .propositos{
            background-color: #f0ad4e;
            color: white;
            font-family: sans-serif;

        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1>Detalles de Encuestas Aplicadas Preescolar</h1>
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
            
        
            <th>Para que eres bueno</th>
            <th>Que quieres ser cuando seas grande</th>
            <th>Que te gusta</th>
            <th>Recomendaciones Docente</th>
            <th>Consentimiento de Padres o Acudiente</th>
            <th>Editar</th>
            <th>Exportar</th>
            <th>Eliminar</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 1;
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$i}</td>
                <td>{$row['id_preguntas']}</td>
                <td>{$row['fecha_dig_preguntas']}</td>
                <td>{$row['nom_ape_est']}</td>
                <td>{$row['mun_dig_preguntas']}</td>
                <td>{$row['nombre_encuestador_preguntas']}</td>
                <td>{$row['rol_encuestador_preguntas']}</td>
                <td>{$row['bueno_preguntas']}</td>
                <td>{$row['grande_preguntas']}</td>
                <td>{$row['gustar_preguntas']}</td>
                <td>{$row['recomendacion_preguntas']}</td>
                <td>{$row['consentimiento_preguntas']}</td>

                <td><a href='editQuestion.php?num_doc_est={$row['num_doc_est']}&id_preguntas={$row['id_preguntas']}'><img src='../../../img/editar.png' width='28' height='28'></a></td>
                <td><a href='../exportar/exportarIndivpreguntas.php?id_preguntas={$row['id_preguntas']}' class='btn btn-success'>Exportar</a></td>
                <td><button class='btn btn-danger' onclick='confirmarEliminacion({$row['id_preguntas']}.{$row['num_doc_est']})'><img src='../../../img/delete1.png' width='28' height='28'></button></td>

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
            xhr.open("GET", '../deleteSurvey.php?id=' + id+ '&campo=preguntas' + '&num_doc_est=' + num_doc, true);
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