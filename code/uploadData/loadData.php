<?php
session_start();

$nombre = $_SESSION['nombre'];
$tipo_usu = $_SESSION['tipo_usu'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>FICHA| SOFT</title>
    <script src="js/64d58efce2.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Lobster" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Orbitron" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../../css/estilos.css">
    <link rel="stylesheet" type="text/css" href="../../css/estilos2024.css">
    <link href="../../fontawesome/css/all.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/fed2435e21.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<style>
    /* Estilos generales del loader */
    #loader {
        display: none;
        /* 🔹 Oculto al cargar la página */
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        /* Fondo semitransparente */
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }

    /* Spinner animado */
    .spinner {
        width: 50px;
        height: 50px;
        border: 5px solid #ccc;
        border-top-color: #007bff;
        /* Color del borde superior */
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    /* Animación de giro */
    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>

<body>
    <center>
        <img src='../../img/logo.png' width=260 height=227>

    </center>

    <h1 style="color: #000000; text-shadow: #FFFFFF 0.1em 0.1em 0.2em; font-size: 40px; text-align: center;"><b><i class="fa-sharp-duotone fa-solid fa-file-excel"></i> SUBIR EXCEL ESTUDIANTES</b>
    </h1>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success text-white text-center">
                        <h4>Subir archivo Excel</h4>
                    </div>
                    <div class="card-body">
                        <form id="uploadForm" action="uploadStudents.php" enctype="multipart/form-data" method="POST">
                            <div class="mb-3">
                                <label for="fileInput" class="form-label">Selecciona un archivo Excel</label>
                                <input type="file" class="form-control" id="fileInput" name="excelFile" accept=".xlsx, .xls" required>
                                <small class="form-text text-muted">Acepta archivos con extensiones .xlsx o .xls.</small>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Subir</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Loader (inicialmente oculto) -->
    <div id="loader">
        <div class="spinner"></div>
    </div>
    <!-- Para mostrar mensajes de éxito o error -->
    <div id="message"></div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // $(document).ready(function() {
        //     $("#uploadForm").submit(function(event) {
        //         event.preventDefault(); // Evitar recarga de la página

        //         var formData = new FormData(this);

        //         $("#loader").css("display", "flex").hide().fadeIn(); // 🔹 Asegura que sea flex y haga fadeIn

        //         $.ajax({
        //             url: 'uploadStudents.php',
        //             type: 'POST',
        //             data: formData,
        //             contentType: false,
        //             processData: false,
        //             success: function(response) {
        //                 $("#loader").fadeOut(); // 🔹 Ocultar el loader cuando la petición termina
        //                 $("#message").html('<p style="color: green;">Archivo procesado correctamente</p>');
        //             },
        //             error: function(xhr, status, error) {
        //                 $("#loader").fadeOut();
        //                 $("#message").html(
        //                     '<p style="color: red;">Error al procesar el archivo: ' + error + '</p>' +
        //                     '<p style="color: red;">Detalles: ' + xhr.responseText + '</p>'
        //                 );
        //                 console.error("Error:", error);
        //                 console.error("Detalles:", xhr.responseText);
        //             }
        //         });
        //     });
        // });
    </script>