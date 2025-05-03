<?php
    session_start();
    date_default_timezone_set("America/Bogota");
    header("Content-Type: text/html;charset=utf-8");
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>FICHA</title>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <script type="text/javascript" src="js/jquery.min.js"></script>
        <script type="text/javascript" src="js/popper.min.js"></script>
        <script type="text/javascript" src="js/bootstrap.min.js"></script>
        <link href="fontawesome/css/all.css" rel="stylesheet"> <!--load all styles -->
        <style>
            .responsive {
                max-width: 100%;
                height: auto;
            }
        </style>
        <script>
            function ordenarSelect(id_componente) {
                var selectToSort = jQuery('#' + id_componente);
                var optionActual = selectToSort.val();
                selectToSort.html(selectToSort.children('option').sort(function (a, b) {
                    return a.text === b.text ? 0 : a.text < b.text ? -1 : 1;
                })).val(optionActual);
            }
            $(document).ready(function () {
                ordenarSelect('selectIE');
            });

            function evitarEspacios(e) {
                if (e.which === 32) {
                    return false;
                }
            }

            function togglePasswordVisibility() {
                var passwordField = document.getElementById("password");
                var passwordToggle = document.getElementById("password-toggle");
                if (passwordField.type === "password") {
                    passwordField.type = "text";
                    passwordToggle.classList.remove("fa-eye");
                    passwordToggle.classList.add("fa-eye-slash");
                } else {
                    passwordField.type = "password";
                    passwordToggle.classList.remove("fa-eye-slash");
                    passwordToggle.classList.add("fa-eye");
                }
            }
        </script>
    </head>
    <body>
  
        <center>
            <img src="img/logo_educacion.png" class="responsive">
        </center>
        <br />

<?php
    require('conexion.php');
    $mysqli->set_charset('utf8');
    if (isset($_REQUEST['usuario'])){
        $usuario = stripslashes($_REQUEST['usuario']); // removes backslashes
        $usuario = mysqli_real_escape_string($mysqli, $usuario); //escapes special characters in a string
        $query = "SELECT * FROM usuarios WHERE usuario='$usuario'";
        $result = mysqli_query($mysqli, $query);
        $rows = mysqli_num_rows($result);
        if ($rows > 0) {
            echo "<center><p style='border-radius: 20px;box-shadow: 10px 10px 5px #c68615; font-size: 23px; font-weight: bold;' >EL USUARIO YA EXISTE<br><br></p></center>
                <div class='form' align='center'><h3>Regresar para intentar nuevamente... <br/><br/><center><a href='index.php'>Regresar</a></center></h3></div>";
            exit;
        }
        $password = stripslashes($_REQUEST['password']);
        $password = mysqli_real_escape_string($mysqli, $password);
        $nombre = stripslashes($_REQUEST['nombre']);
        $tipo_usuario = 7;
        $cod_dane_ie = stripslashes($_REQUEST['cod_dane_ie']);
        
        $query = "INSERT INTO `usuarios` (usuario, password, tipo_usuario, nombre, cod_dane_ie) VALUES ('$usuario', '".sha1($password)."', '$tipo_usuario', '$nombre', '$cod_dane_ie')";
        $result = mysqli_query($mysqli, $query);
        if ($result) {
            echo "<center><p style='border-radius: 20px;box-shadow: 10px 10px 5px #c68615; font-size: 23px; font-weight: bold;' >REGISTRO CREADO SATISFACTORIAMENTE<br><br></p></center>
                <div class='form' align='center'><h3>Pidele al rector de tu institucion para que te de Acceso... <br/><br/><center><a href='index.php'>Regresar</a></center></h3></div>";
        }
    } else {
?>
        
        <div class="container">
            <h1><b><i class="fas fa-users"></i> REGISTRO DE UN NUEVO USUARIO</b></h1>
            <p><i><b><font size=3 color=#c68615>*Datos obligatorios</i></b></font></p>
            <form action='' method="POST">
                
                <div class="form-group">
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <label for="nombre">* NOMBRES COMPLETOS (persona que se registra):</label>
                            <input type='text' name='nombre' class='form-control' id="nombre" required autofocus style="text-transform:uppercase;" />
                        </div>
                        <div class="col-12 col-sm-6">
                            <label for="cod_dane_ie">* ESTABLECIMIENTO EDUCATIVO:</label>
                            <select name='cod_dane_ie' class='form-control' required id='selectIE' />
                                <option value=''></option>
                                    <?php
                                        header('Content-Type: text/html;charset=utf-8');
                                        $consulta = 'SELECT * FROM ie';
                                        $res = mysqli_query($mysqli, $consulta);
                                        $num_reg = mysqli_num_rows($res);
                                        while ($row = $res->fetch_array()) {
                                    ?>
                                <option value='<?php echo $row['cod_dane_ie']; ?>'>
                                        <?php echo $row['nombre_ie']; ?>
                                </option>
                                    <?php
                                        }
                                    ?>    
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-12 col-sm-3">
                            <label for="usuario">* USUARIO:</label>
                            <input type='text' name='usuario' id="usuario" class='form-control' required onkeypress="return evitarEspacios(event)" />
                            <label for="usuario">(minúsculas, sin espacios, ni caracteres especiales)</label>
                        </div>
                        <div class="col-12 col-sm-3">
                            <label for="password">* PASSWORD:</label>
                            <div class="input-group">
                                <input type='password' name='password' id="password" class='form-control' required style="text-transform:uppercase;" />
                                <div class="input-group-append">
                                    <span class="input-group-text" onclick="togglePasswordVisibility();">
                                        <i class="fas fa-eye" id="password-toggle"></i>
                                    </span>
                                </div>
                            </div>
                            <label for="password"> (no tiene restricción)</label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <span class="spinner-border spinner-border-sm"></span>
                    REGISTRAR USUARIO
                </button>
                <button type="reset" class="btn btn-outline-dark" role='link' onclick="history.back();" type='reset'><img src='img/atras.png' width=27 height=27> REGRESAR
                </button>
            </form>
        </div>

    </body>
</html>

<?php } ?>
