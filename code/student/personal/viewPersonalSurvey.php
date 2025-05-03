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

$query = "SELECT * FROM personal WHERE num_doc_est = '$num_doc_est'";
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
            
            <!-- Proposito 1 -->
            <th>Sabe las normas Escolares</th>
            <th>Acata las normas y reglas</th>
            <th>Interactua con adultos correctamente</th>
            <th>Se ocupa de su cuidado personal</th>
            <th>IObservaciones</th>
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
                <td>{$row['id_personal']}</td>
                <td>{$row['fecha_dig_personal']}</td>
                <td>{$row['nom_ape_est']}</td>
                <td>{$row['mun_dig_personal']}</td>
                <td>{$row['nombre_encuestador_personal']}</td>
                <td>{$row['rol_encuestador_personal']}</td>
                <td> {$row['normas_personal'] }</td>
                <td> {$row['acata_personal'] }</td>
                <td> {$row['interactua_personal'] }</td>
                <td> {$row['cuidado_personal'] }</td>
                <td> {$row['observacion_personal'] }</td>
                <td><a href='editPersonal.php?num_doc_est={$row['num_doc_est']}&id_personal={$row['id_personal']}'><img src='../../../img/editar.png' width='28' height='28'></a></td>
                <td><a href='../exportar/exportarIndivPersonal.php?id_personal={$row['id_personal']}' class='btn btn-success'>Exportar</a></td>
                <td><button class='btn btn-danger' onclick='confirmarEliminacion({$row['id_personal']}.{$row['num_doc_est']})'><img src='../../../img/delete1.png' width='28' height='28'></button></td>

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
            xhr.open("GET", '../deleteSurvey.php?id=' + id+ '&campo=personal' + '&num_doc_est=' + num_doc, true);
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