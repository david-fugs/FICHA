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
$query = "SELECT *
FROM entornohogar 
JOIN estudiantes ON entornohogar.num_doc_est = estudiantes.num_doc_est
WHERE entornohogar.num_doc_est = '$num_doc_est'";

$result = $mysqli->query($query);
function Si1No2($value)
{
    if ($value == 1) {
        return "SI";
    } 
    if ($value == 0) {
        return "NO";
    } 
    if($value == 2) {
        return "No Aplica";
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
                        <th>Rol encuestador</th>

                        <th>Nombre Madre</th>
                        <th>Aun vive?</th>
                        <th>Ocupacion madre</th>
                        <th>Nivel educativo madre</th>
                        <th>Nombre Padre</th>
                        <th>Aun Vive</th>
                        <th>Ocupacion Padre</th>
                        <th>Nivel educativo Padre</th>
                        <th>Estudiante vive con</th>
                        <th>Con quien vive</th>
                        <th>Nombre Cuidador</th>
                        <th>Parentesco Cuidador</th>
                        <th>Espeficique otro</th>

                        <th>Nivel Educativo Cuidador</th>
                        <th>Ocupacion Cuidador</th>
                        <th>Cual ocupacion</th>

                        <th>Contacto cuidador</th>

                        <th>Email Cuidador</th>
                        <th>Numero Hermanos</th>

                        <th>Lugar que ocupa entre Hermanos</th>

                        <th>Hermano que estudian en el colegio</th>
                        <th>Niveles educativos de los hermanos</th>
                        <th>Quienes apoyan proceso crianza</th>
                        <th>Quien apoya proceso </th>
                        <th>Cual es la Practica comunicativa mas frecuente</th>
                        <th>Categoria Familia</th>
                        <th>Familia recibe subsidio</th>
                        <th>Nombre Subsidio</th>
                        <th>Mecanismos solucion conflictos</th>
                        <th>Otros tipos de mecanismos de solucion conflictos</th>
                        <th>Quienes solucionan los inconvenientes</th>
                        <th>Mencione los que solucionan inconvenientes</th>
                        <th>Como soluciona los inconvenientes</th>
                        <th>Que otra forma de solucionar invonvenientes</th>
                        <th>Resposnabilidades del estudiante en el hogar</th>
                        <th>Cuales responsabilidades</th>
                        <th>Como expresan el afecto entre la familia</th>
                        <th>De que manera expresan afecto</th>
                        <th>Tipo Vivienda</th>
                        <th>Tenencia de vivienda</th>
                        <th>Otro tipo tenencia</th>
                        <th>Material de construccion</th>
                        <th>Servicios del hogar</th>
                        <th>Cuantas personas viven en vivienda</th>
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
                        echo "<tr>";
                        echo "<td>{$i}</td>";
                        echo "<td>{$row['id_hog']}</td>";
                        echo "<td>{$row['fecha_dig_hog']}</td>";
                        echo "<td>{$row['nom_ape_est']}</td>";
                        echo "<td>{$row['mun_dig_hog']}</td>";
                        echo "<td>{$row['nombre_encuestador_hog']}</td>";
                        echo "<td>{$row['rol_encuestador_hog']}</td>";
                        
                        echo "<td>{$row['nombre_madre_hog']}</td>";
                        echo '<td>' . Si1No2($row['vive_madre_hog']) . '</td>';
                        echo "<td>{$row['ocupacion_madre_hog']}</td>";
                        echo "<td>{$row['educacion_madre_hog']}</td>";
                        echo "<td>{$row['nombre_padre_hog']}</td>";
                        echo '<td>' . Si1No2($row['vive_padre_hog']) . '</td>';
                        echo "<td>{$row['ocupacion_padre_hog']}</td>";
                        echo "<td>{$row['educacion_padre_hog']}</td>";
                        echo "<td>{$row['vive_estu_hog']}</td>";
                        echo "<td>{$row['nom_vive_estu_hog']}</td>";
                        echo "<td>{$row['cuidador_estu_hog']}</td>";
                        echo "<td>{$row['parentesco_cuid_estu_hog']}</td>";
                        echo "<td>{$row['nom_parentesco_cuid_estu_hog']}</td>";
                        echo "<td>{$row['educacion_cuid_estu_hog']}</td>";
                        echo "<td>{$row['ocupacion_cuid_estu_hog']}</td>";
                        echo "<td>{$row['nom_ocupacion_cuid_estu_hog']}</td>";
                        echo "<td>{$row['tel_cuid_estu_hog']}</td>";
                        echo "<td>{$row['email_cuid_estu_hog']}</td>";
                        echo "<td>{$row['num_herm_estu_hog']}</td>";
                        echo "<td>{$row['lugar_ocupa_estu_hog']}</td>";
                        echo '<td>' . Si1No2($row['tiene_herm_ie_estu_hog']) . '</td>';
                        echo "<td>{$row['niveles_educativos_herm_ie_estu_hog']}</td>";
                        echo "<td>{$row['crianza_estu_hog']}</td>";
                        echo "<td>{$row['nom_crianza_estu_hog']}</td>";
                        echo "<td>{$row['prac_comu_estu_hog']}</td>";
                        echo "<td>{$row['fam_categ_estu_hog']}</td>";
                        echo '<td>' . Si1No2($row['fam_subsidio_hog']) . '</td>';
                        echo "<td>{$row['tipo_subsidio_hog']}</td>";
                        echo "<td>{$row['mecanismos_conflictos_estu_hog']}</td>";
                        echo "<td>{$row['nom_mecanismos_conflictos_estu_hog']}</td>";
                        echo "<td>{$row['inconvenientes_quien_hog']}</td>";
                        echo "<td>{$row['nom_quien_inconvenientes_hog']}</td>";
                        echo "<td>{$row['inconvenientes_como_hog']}</td>";
                        echo "<td>{$row['nom_como_inconvenientes_hog']}</td>";
                        echo "<td>{$row['responsabilidades_est_hog']}</td>";
                        echo "<td>{$row['nom_responsabilidades_est_hog']}</td>";
                        echo "<td>{$row['afecto_est_hog']}</td>";
                        echo "<td>{$row['nom_afecto_est_hog']}</td>";
                        echo "<td>{$row['tipo_vivienda_hog']}</td>";
                        echo "<td>{$row['tenencia_vivienda_hog']}</td>";
                        echo "<td>{$row['nom_tenencia_vivienda_hog']}</td>";
                        echo "<td>{$row['material_vivienda_hog']}</td>";
                        echo "<td>{$row['servicios_vivienda_hog']}</td>";
                        echo "<td>{$row['num_personas_vivienda_hog']}</td>";
                        echo "<td>{$row['fecha_alta_hog']}</td>";
                        echo "<td>{$row['fecha_edit_hog']}</td>";
                        echo '<td><a href="editEntornoHogar.php?num_doc_est=' . $row['num_doc_est'] . '&idHogar=' . $row['id_hog'] . '"><img src="../../img/editar.png" width=28 height=28></a></td>';
                        echo "<td><a href='exportar/exportarIndivHogar.php?id_hog={$row['id_hog']}' class='btn btn-success'>Exportar</a></td>";
                        echo "<td><button class='btn btn-danger' onclick='confirmarEliminacion({$row['id_hog']}, {$row['num_doc_est']})'><img src='../../img/delete1.png' width=28 height=28></button></td>";
                        echo "</tr>";
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
            xhr.open("GET", '../student/deleteSurvey.php?id=' + id + '&campo=entornohogar' + '&num_doc_est=' + num_doc, true);
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