<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../../index.php");
    exit();
}

$usuario = $_SESSION['usuario'];
$nombre = $_SESSION['nombre'];
$tipo_usuario = $_SESSION['tipo_usuario'];
$cod_dane_ie = $_SESSION['cod_dane_ie'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>FICHA</title>
    <link rel="stylesheet" href="../student/css/styles.css">
    <link rel="stylesheet" type="text/css" href="../student/css/estilos2024.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm">

    <style>
        .custom-table {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .custom-table thead {
            background-color: #007bff;
            color: white;
            text-align: center;
        }

        .custom-table tbody tr:hover {
            background-color: #f1f1f1;
        }

        .custom-table td,
        .custom-table th {
            text-align: center;
            vertical-align: middle;
            padding: 10px;
        }

        .total-row {
            font-weight: bold;
            background-color: #e0e0e0;
        }

        .responsive {
            max-width: 100%;
            height: auto;
        }

        .selector-for-some-widget {
            box-sizing: content-box;
        }

        .veces-aplicada-verde {
            background-color: green;
            color: white;
        }

        .veces-aplicada-rojo {
            background-color: red;
            color: white;
        }
    </style>
</head>

<body>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"></script>

    <center>
        <img src='../../img/logo_educacion.png' width=600 height=121 class='responsive'>
    </center>

    <section class="principal">
        <div style="border-radius: 9px 9px 9px 9px; -moz-border-radius: 9px 9px 9px 9px; -webkit-border-radius: 9px 9px 9px 9px; border: 4px solid #FFFFFF;" align="center">
            <div align="center">
                <h1 style="color: #412fd1; text-shadow: #FFFFFF 0.1em 0.1em 0.2em"><b><i class="fa-solid fa-baby"></i> ENCUESTAS APLICADAS POR INSTITUCION</b></h1>
            </div>
            <a href="../../access.php"><img src='../../img/atras.png' width="72" height="72" title="Regresar" /></a>
        </div>
        <?php
        include("../../conexion.php");
        date_default_timezone_set("America/Bogota");
        $mysqli->set_charset('utf8');
        $sql = "SELECT 
                    ie.nombre_ie, 
                    COUNT(DISTINCT d.num_doc_est) AS total_desempeno,
                    COUNT(DISTINCT eh.num_doc_est) AS total_entornohogar,
                    COUNT(DISTINCT fs.num_doc_est) AS total_familiasalud,
                    COUNT(DISTINCT p.num_doc_est) AS total_personal,
                    COUNT(DISTINCT pr.num_doc_est) AS total_preescolar,
                    COUNT(DISTINCT pg.num_doc_est) AS total_preguntas,
                    COUNT(DISTINCT pp.num_doc_est) AS total_prepostnatales,
                    (COUNT(DISTINCT d.num_doc_est) + 
                    COUNT(DISTINCT eh.num_doc_est) + 
                    COUNT(DISTINCT fs.num_doc_est) + 
                    COUNT(DISTINCT p.num_doc_est) + 
                    COUNT(DISTINCT pr.num_doc_est) + 
                    COUNT(DISTINCT pg.num_doc_est) + 
                    COUNT(DISTINCT pp.num_doc_est)) AS total_registros
                FROM estudiantes e
                JOIN ie ON e.cod_dane_ieSede = ie.cod_dane_ie
                LEFT JOIN desempeno d ON e.num_doc_est = d.num_doc_est
                LEFT JOIN educacion eh ON e.num_doc_est = eh.num_doc_est
                LEFT JOIN familiasalud fs ON e.num_doc_est = fs.num_doc_est
                LEFT JOIN personal p ON e.num_doc_est = p.num_doc_est
                LEFT JOIN preescolar pr ON e.num_doc_est = pr.num_doc_est
                LEFT JOIN preguntas pg ON e.num_doc_est = pg.num_doc_est
                LEFT JOIN prePostnatales pp ON e.num_doc_est = pp.num_doc_est
                GROUP BY ie.nombre_ie
                HAVING total_registros > 0;
        ";
        $res = $mysqli->query($sql);
        ?>

        <table class="table table-hover  custom-table mt-5">
            <tr>
                <th>Institucion</th>
                <th>Desempeño</th>
                <th>Entorno Hogar</th>
                <th>Familia Salud</th>
                <th>Personal</th>
                <th>Preescolar</th>
                <th>Preguntas</th>
                <th>PrePostnatales</th>
                <th>Total</th>
            </tr>

            <tbody>
                <?php
                $totalGeneral = 0; // Para sumar todos los totales

                while ($row = $res->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['nombre_ie'] . "</td>";
                    echo "<td>" . $row['total_desempeno'] . "</td>";
                    echo "<td>" . $row['total_entornohogar'] . "</td>";
                    echo "<td>" . $row['total_familiasalud'] . "</td>";
                    echo "<td>" . $row['total_personal'] . "</td>";
                    echo "<td>" . $row['total_preescolar'] . "</td>";
                    echo "<td>" . $row['total_preguntas'] . "</td>";
                    echo "<td>" . $row['total_prepostnatales'] . "</td>";
                    echo "<td><strong>" . $row['total_registros'] . "</strong></td>";
                    echo "</tr>";

                    $totalGeneral += $row['total_registros'];
                }

                // Agregar una fila con el total general de todas las instituciones
                echo "<tr class='total-row'>";
                echo "<td><strong>Total General</strong></td>";
                echo "<td colspan='7'></td>"; // Celdas vacías para alineación
                echo "<td><strong>$totalGeneral</strong></td>";
                echo "</tr>";
                ?>
            </tbody>
        </table>

        <a href="../../access.php"><img src='../../img/atras.png' width="72" height="72" title="Regresar" /></a>

    </section>
</body>

</html>