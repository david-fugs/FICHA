<?php
  
  require "conexion.php";
 
  session_start();

  if($_POST)
  {

    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM usuarios WHERE usuario='$usuario'";
    //echo $sql;
    $resultado = $mysqli->query($sql);
    $num = $resultado->num_rows;

      if($num>0)
      {
        $row = $resultado->fetch_assoc();
        $password_bd = $row['password'];

        $pass_c = sha1($password);

        if($password_bd == $pass_c)
        {
          $_SESSION['id'] = $row['id'];
          $_SESSION['nombre'] = $row['nombre'];
          $_SESSION['tipo_usuario'] = $row['tipo_usuario'];
          $_SESSION['cod_dane_ie'] = $row['cod_dane_ie'];
          $_SESSION['usuario'] = $row['usuario'];

          if($row['tipo_usuario']==1)
          {
            // header("Location: code/usuarios/adduser.php");
            header("Location: access.php");
          }
          elseif($row['tipo_usuario']==2)
          {
            header("Location: access.php");
          }
          elseif($row['tipo_usuario']==3)
          {
            header("Location: access.php");
          }
          elseif($row['tipo_usuario']==5)
          {
            header("Location: access.php");
          }
          elseif($row['tipo_usuario']==6)
          {
            header("Location: access.php");
          }
          else
          {
            
            header("Location: index.php");
          }
        }else
        {
          echo "La contraseña no coincide";
        }
      }else
      {
        echo "NO existe usuario";
      }
  }
?>


<!DOCTYPE html>
<html lang="es">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
  <script src="js/a81368914c.js"></script>
</head>
<body>

	<img class="wave" src="img/index1.jpg">
	<div class="container">

		<div class="img">
			<img src="img/kids.png">
		</div>
		<div class="login-content">
			<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
				<img src="img/avatar.svg">
				<h2 class="title">Bienvenid@</h2>
           		<div class="input-div one">
           		   <div class="i">
           		   		<i class="fas fa-user"></i>
           		   </div>
           		   <div class="div">
           		   		<h5>Usuario</h5>
           		   		<input type="text" class="input" name="usuario" type="text" />
           		   </div>
           		</div>
           		<div class="input-div pass">
           		   <div class="i"> 
           		    	<i class="fas fa-lock"></i>
           		   </div>
           		   <div class="div">
           		    	<h5>Password</h5>
           		    	<input type="password" class="input" name="password" type="text" />
            	   </div>
            	</div>
              <a href="register.php">Crear cuenta</a>
            	<input type="submit" class="btn" value="Accede">
            </form>
        </div>
    </div>
    <script type="text/javascript" src="js/main.js"></script>
</body>
</html>
