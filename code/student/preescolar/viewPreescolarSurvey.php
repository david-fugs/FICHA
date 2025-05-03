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

$query = "SELECT * FROM preescolar WHERE num_doc_est = '$num_doc_est'";
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
        <h1>Detalles de Encuestas Aplicadas Preescolar</h1>
        <div class="table-responsive mt-5">
           <table class="table table-bordered table-striped">
    <thead class="thead-dark">
        <tr>
            <td colspan="7" class="propositos"  ></td>
            <td colspan="7" class="propositos" >PROPOSITO 1</td>
            <td colspan="6"  class="propositos" >PROPOSITO 2</td>
            <td colspan="9" class="propositos">PROPOSITO 3</td>
            <td colspan="3" class="propositos"  ></td>

        </tr>
        <tr>
            <th>No.</th>
            <th>ID</th>
            <th>Fecha de Digitación</th>
            <th>Nombre Estudiante</th>
            <th>Municipio de Digitación</th>
            <th>Nombre del Encuestador</th>
            <th>Rol del Encuestador</th>
            
            <!-- Proposito 1 -->
            <th>Explica elección</th>
            <th>Anticipa consecuencias</th>
            <th>Independencia</th>
            <th>Representación artística</th>
            <th>Identifica lugar</th>
            <th>Describe roles familia</th>
            <th>Escucha puntos de vista</th>
            
            <!-- Proposito 2 -->
            <th>Participa en canciones</th>
            <th>Identifica palabras</th>
            <th>Sigue juegos de palabras</th>
            <th>Lee imágenes</th>
            <th>Explora tipos de texto</th>
            <th>Interés en escritura</th>
            
            <!-- Proposito 3 -->
            <th>Concentración</th>
            <th>Explica razones</th>
            <th>Participa en prácticas</th>
            <th>Identifica características</th>
            <th>Anticipa consecuencias</th>
            <th>Representa cuerpo</th>
            <th>Secuencia</th>
            <th>Atributo</th>
            <th>Enumeración</th>
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
                <td>{$row['id_preescolar']}</td>
                <td>{$row['fecha_dig_preescolar']}</td>
                <td>{$row['nom_ape_est']}</td>
                <td>{$row['mun_dig_preescolar']}</td>
                <td>{$row['nombre_encuestador_preescolar']}</td>
                <td>{$row['rol_encuestador_preescolar']}</td>
                
                <!-- Proposito 1 -->
                <td>{$row['p1_eleccion_preescolar']}</td>
                <td>{$row['p1_consecuen_preescolar']}</td>
                <td>{$row['p1_independencia_preescolar']}</td>
                <td>{$row['p1_artistico_preescolar']}</td>
                <td>{$row['p1_lugarvive_preescolar']}</td>
                <td>{$row['p1_rolfamilia_preescolar']}</td>
                <td>{$row['p1_escucha_preescolar']}</td>
                
                <!-- Proposito 2 -->
                <td>{$row['p2_juegos_preescolar']}</td>
                <td>{$row['p2_palabras_preescolar']}</td>
                <td>{$row['p2_segmenoral_preescolar']}</td>
                <td>{$row['p2_crea_preescolar']}</td>
                <td>{$row['p2_leer_preescolar']}</td>
                <td>{$row['p2_escribir_preescolar']}</td>
                
                <!-- Proposito 3 -->
                <td>{$row['p3_concentracion_preescolar']}</td>
                <td>{$row['p3_explica_preescolar']}</td>
                <td>{$row['p3_participa_preescolar']}</td>
                <td>{$row['p3_identifica_preescolar']}</td>
                <td>{$row['p3_acontecimientos_preescolar']}</td>
                <td>{$row['p3_cuerpo_preescolar']}</td>
                <td>{$row['p3_secuencia_preescolar']}</td>
                <td>{$row['p3_atributo_preescolar']}</td>
                <td>{$row['p3_enumeracion_preescolar']}</td>
                
                <td><a href='editPreescolar.php?num_doc_est={$row['num_doc_est']}&id_preescolar={$row['id_preescolar']}'><img src='../../../img/editar.png' width='28' height='28'></a></td>
                <td><a href='../exportar/exportarIndivPreescolar.php?id_preescolar={$row['id_preescolar']}' class='btn btn-success'>Exportar</a></td>
                <td><button class='btn btn-danger' onclick='confirmarEliminacion({$row['id_preescolar']}.{$row['num_doc_est']})'><img src='../../../img/delete1.png' width='28' height='28'></button></td>
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
            xhr.open("GET", '../deleteSurvey.php?id=' + id+ '&campo=preescolar' + '&num_doc_est=' + num_doc, true);
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