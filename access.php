<?php
session_start();

if (!isset($_SESSION['id'])) {
  header("Location: index.php");
}

$usuario      = $_SESSION['usuario'];
$nombre       = $_SESSION['nombre'];
$tipo_usuario = $_SESSION['tipo_usuario'];
$cod_dane_ie  = $_SESSION['cod_dane_ie'];
?>

<!DOCTYPE html>
<!-- Coding by CodingNepal || www.codingnepalweb.com -->
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <!-- Boxicons CSS -->
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js" defer></script>

  <script src="https://kit.fontawesome.com/fed2435e21.js" crossorigin="anonymous"></script>
  <title>FICHA</title>
  <link rel="stylesheet" href="menu/style.css" />
</head>

<body>
  <!-- navbar -->
  <nav class="navbar">
    <div class="logo_item">
      <i class="bx bx-menu" id="sidebarOpen"></i>
      <img src="img/logo.png" alt=""></i>FICHA INTEGRAL DE CARACTERIZACIÓN
    </div>

    <div class="search_bar">
      <input type="text" placeholder="Buscar..." />
    </div>

    <div class="navbar_content">
      <i class="bi bi-grid"></i>
      <i class="fa-solid fa-sun" id="darkLight"></i><!--<i class='bx bx-sun' id="darkLight"></i>-->
      <a href="logout.php"> <i class="fa-solid fa-door-open"></i></a>
      <img src="img/logo.png" alt="" class="profile" />
    </div>
  </nav>

  <!--********************************INICIA MENÚ RECTOR********************************-->

  <?php if ($tipo_usuario == 1) { ?>
    <!-- sidebar -->
    <nav class="sidebar">
      <div class="menu_content">
        <ul class="menu_items">
          <div class="menu_title menu_dahsboard"></div>
          <!-- duplicate or remove this li tag if you want to add or remove navlink with submenu -->
          <!-- start -->
          <li class="item">
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="fa-solid fa-user-pen"></i>
                <!--<i class="bx bx-home-alt"></i>-->
              </span>

              <span class="navlink">Usuarios</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>

            <ul class="menu_items submenu">
              <a href="code/users/showusers.php" class="nav_link sublink">Permisos</a>
              <a href="code/users/register.php" class="nav_link sublink">Crear Nuevo</a>
            </ul>
          </li>

          <li class="item">
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="fa-solid fa-address-card"></i>
              </span>

              <span class="navlink">SIMAT Info. General</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>

            <ul class="menu_items submenu">
              <a href="code/student/showsimat.php" class="nav_link sublink">Actualizar Datos</a></a>
              <a href="code/student/checksimat.php" class="nav_link sublink">Estudiantes Actualizados</a>
            </ul>
          </li>

          <li class="item">
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="fa-solid fa-baby-carriage"></i>
              </span>

              <span class="navlink">Pre Post-Natales</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>

            <ul class="menu_items submenu">
              <a href="code/student/showprePostnatales.php" class="nav_link sublink">Aplicar Encuesta</a>
              <a href="code/student/checkprePostnatales.php" class="nav_link sublink">Encuestas Realizadas</a>
            </ul>
          </li>


          <li class="item">
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="fa-solid fa-house-user"></i>
              </span>

              <span class="navlink">Entorno Hogar - Vivienda</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>

            <ul class="menu_items submenu">
              <a href="code/home/showentornoHogar.php" class="nav_link sublink">Aplicar Encuesta</a>
              <a href="code/home/checkentornoHogar.php" class="nav_link sublink">Encuestas Realizadas</a>
            </ul>
          </li>
          <li class="item">
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="fa-solid fa-people-roof"></i>
              </span>

              <span class="navlink">Salud y Familia</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>

            <ul class="menu_items submenu">
              <a href="code/student/showHealthFamily.php" class="nav_link sublink">Aplicar Encuesta</a>
              <a href="code/student/checkHealthFamily.php" class="nav_link sublink">Encuestas Realizadas</a>
            </ul>
          </li>
          <li class="item">
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="fa-solid fa-school"></i>
              </span>

              <span class="navlink">Educación y Formación</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>

            <ul class="menu_items submenu">
              <a href="code/student/showEducation.php" class="nav_link sublink">Aplicar Encuesta</a>
              <a href="code/student/checkEducation.php" class="nav_link sublink">Encuestas Realizadas</a>
            </ul>
          </li>
          <li class="item">
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="fa-solid fa-book-bookmark"></i>
              </span>

              <span class="navlink">Desempeño Académico</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>

            <ul class="menu_items submenu">
              <a href="code/student/showPerformance.php" class="nav_link sublink">Aplicar Encuesta</a>
              <a href="code/student/checkPerformance.php" class="nav_link sublink">Encuestas Realizadas</a>
            </ul>
          </li>
          <li class="item">
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="fa-solid fa-baby"></i>
              </span>

              <span class="navlink">Desarrollo Preescolar</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>

            <ul class="menu_items submenu">
              <a href="code/student/showPreescolar.php" class="nav_link sublink">Aplicar Encuesta</a>
              <a href="code/student/preescolar/checkPreescolar.php" class="nav_link sublink">Encuestas Realizadas</a>
            </ul>
          </li>

          <li class="item">
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="fa-solid fa-person"></i>
              </span>

              <span class="navlink">Desarrollo Personal</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>

            <ul class="menu_items submenu">
              <a href="code/student/showPersonal.php" class="nav_link sublink">Aplicar Encuesta</a>
              <a href="code/student/personal/checkPersonal.php" class="nav_link sublink">Encuestas Realizadas</a>
            </ul>
          </li>
          <li class="item">
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="fa-sharp fa-solid fa-book"></i>
              </span>

              <span class="navlink">Preguntas al Estudiante</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>

            <ul class="menu_items submenu">
              <a href="code/student/showQuestions.php" class="nav_link sublink">Aplicar Encuesta</a>
              <a href="code/student/question/checkQuestion.php" class="nav_link sublink">Encuestas Realizadas</a>
            </ul>
          </li>
          <li class="item">
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="fa-solid fa-chart-pie"></i>
              </span>
              <span class="navlink">Cargar Estudiantes</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>

            <ul class="menu_items submenu">
              <a href="code/uploadData/loadData.php" class="nav_link sublink">Subir Excel</a>
            </ul>
          </li>

          <li class="item">
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="fa-solid fa-file"></i>
              </span>
              <span class="navlink">Informe Encuestas</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>

            <ul class="menu_items submenu">
              <a href="code/informe/informeEncuestas.php" class="nav_link sublink">Ver Informes</a>
            </ul>
          </li>

          <li class="item">
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="fa-solid fa-screwdriver-wrench"></i>
              </span>
              <span class="navlink">Mi Cuenta</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>

            <ul class="menu_items submenu">
              <a href="reset-password.php" class="nav_link sublink">Cambiar Contraseña</a>
            </ul>
          </li>

          <!-- Sidebar Open / Close -->
          <div class="bottom_content">
            <div class="bottom expand_sidebar">
              <span> Expand</span>
              <i class='bx bx-log-in'></i>
            </div>
            <div class="bottom collapse_sidebar">
              <span> Collapse</span>
              <i class='bx bx-log-out'></i>
            </div>
          </div>
      </div>
    </nav>
  <?php } ?>


  <!--********************************INICIA MENÚ SIMAT********************************-->

  <?php if ($tipo_usuario == 2) { ?>
    <!-- sidebar -->
    <nav class="sidebar">
      <div class="menu_content">
        <ul class="menu_items">
          <div class="menu_title menu_dahsboard"></div>
          <!-- duplicate or remove this li tag if you want to add or remove navlink with submenu -->
          <!-- start -->
          <li class="item">
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="fa-solid fa-address-card"></i>
              </span>

              <span class="navlink">SIMAT Info. General</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>
            <ul class="menu_items submenu">
              <a href="code/student/showsimat.php" class="nav_link sublink">Actualizar Datos</a>
              <a href="code/student/checksimat.php" class="nav_link sublink">Verificar Realizadas</a>

            </ul>
          </li>

          <!-- end -->

          <!-- duplicate this li tag if you want to add or remove  navlink with submenu -->
          <!-- start -->
          <li class="item">
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="fa-solid fa-screwdriver-wrench"></i>
              </span>
              <span class="navlink">Mi Cuenta</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>

            <ul class="menu_items submenu">
              <a href="reset-password.php" class="nav_link sublink">Cambiar Contraseña</a>
              <!--<a href="#" class="nav_link sublink">Nav Sub Link</a>
              <a href="#" class="nav_link sublink">Nav Sub Link</a>
              <a href="#" class="nav_link sublink">Nav Sub Link</a>-->
            </ul>
          </li>
          <!-- end -->
        </ul>

        <!-- Sidebar Open / Close -->
        <div class="bottom_content">
          <div class="bottom expand_sidebar">
            <span> Expand</span>
            <i class='bx bx-log-in'></i>
          </div>
          <div class="bottom collapse_sidebar">
            <span> Collapse</span>
            <i class='bx bx-log-out'></i>
          </div>
        </div>
      </div>
    </nav>
  <?php } ?>

  <!--********************************INICIA MENÚ DOCENTE********************************-->

  <?php if ($tipo_usuario == 3) { ?>
    <!-- sidebar -->
    <nav class="sidebar">
      <div class="menu_content">
        <ul class="menu_items">
          <div class="menu_title menu_dahsboard"></div>
          <!-- duplicate or remove this li tag if you want to add or remove navlink with submenu -->
          <!-- start -->
         

        


       
          <li class="item">
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="fa-solid fa-school"></i>
              </span>

              <span class="navlink">Educación y Formación</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>

            <ul class="menu_items submenu">
              <a href="code/student/showEducation.php" class="nav_link sublink">Aplicar Encuesta</a>
              <a href="code/student/checkEducation.php" class="nav_link sublink">Encuestas Realizadas</a>
            </ul>
          </li>
          <li class="item">
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="fa-solid fa-book-bookmark"></i>
              </span>

              <span class="navlink">Desempeño Académico</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>

            <ul class="menu_items submenu">
              <a href="code/student/showPerformance.php" class="nav_link sublink">Aplicar Encuesta</a>
              <a href="code/student/checkPerformance.php" class="nav_link sublink">Encuestas Realizadas</a>
            </ul>
          </li>
          <li class="item">
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="fa-solid fa-baby"></i>
              </span>

              <span class="navlink">Desarrollo Preescolar</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>

            <ul class="menu_items submenu">
              <a href="code/student/showPreescolar.php" class="nav_link sublink">Aplicar Encuesta</a>
              <a href="code/student/preescolar/checkPreescolar.php" class="nav_link sublink">Encuestas Realizadas</a>
            </ul>
          </li>

          <li class="item">
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="fa-solid fa-person"></i>
              </span>

              <span class="navlink">Desarrollo Personal</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>

            <ul class="menu_items submenu">
              <a href="code/student/showPersonal.php" class="nav_link sublink">Aplicar Encuesta</a>
              <a href="code/student/personal/checkPersonal.php" class="nav_link sublink">Encuestas Realizadas</a>
            </ul>
          </li>
          <li class="item">
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="fa-sharp fa-solid fa-book"></i>
              </span>

              <span class="navlink">Preguntas al Estudiante</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>

            <ul class="menu_items submenu">
              <a href="code/student/showQuestions.php" class="nav_link sublink">Aplicar Encuesta</a>
              <a href="code/student/question/checkQuestion.php" class="nav_link sublink">Encuestas Realizadas</a>
            </ul>
          </li>
          <li class="item">
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="fa-solid fa-chart-pie"></i>
              </span>
              <span class="navlink">Cargar Estudiantes</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>

            <ul class="menu_items submenu">
              <a href="code/uploadData/loadData.php" class="nav_link sublink">Subir Excel</a>
            </ul>
          </li>

          <li class="item">
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="fa-solid fa-file"></i>
              </span>
              <span class="navlink">Informe Encuestas</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>

            <ul class="menu_items submenu">
              <a href="code/informe/informeEncuestas.php" class="nav_link sublink">Ver Informes</a>
            </ul>
          </li>

          <li class="item">
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="fa-solid fa-screwdriver-wrench"></i>
              </span>
              <span class="navlink">Mi Cuenta</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>

            <ul class="menu_items submenu">
              <a href="reset-password.php" class="nav_link sublink">Cambiar Contraseña</a>
            </ul>
          </li>

          <!-- Sidebar Open / Close -->
          <div class="bottom_content">
            <div class="bottom expand_sidebar">
              <span> Expand</span>
              <i class='bx bx-log-in'></i>
            </div>
            <div class="bottom collapse_sidebar">
              <span> Collapse</span>
              <i class='bx bx-log-out'></i>
            </div>
          </div>
      </div>
    </nav>
  <?php } ?>

  <!--********************************INICIA MENÚ DIRECTIVO DOCENTE********************************-->

  <?php if ($tipo_usuario == 4) { ?>
    <!-- sidebar -->
    <nav class="sidebar">
      <div class="menu_content">
        <ul class="menu_items">
          <div class="menu_title menu_dahsboard"></div>

          <li class="item">
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="fa-solid fa-address-card"></i>
              </span>

              <span class="navlink">SIMAT Info. General</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>

            <ul class="menu_items submenu">
              <a href="code/student/showsimat.php" class="nav_link sublink">Actualizar Datos</a></a>
              <a href="code/student/checksimat.php" class="nav_link sublink">Estudiantes Actualizados</a>
            </ul>
          </li>

          <li class="item">
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="fa-solid fa-baby-carriage"></i>
              </span>

              <span class="navlink">Pre Post-Natales</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>

            <ul class="menu_items submenu">
              <a href="code/student/showprePostnatales.php" class="nav_link sublink">Aplicar Encuesta</a>
              <a href="code/student/checkprePostnatales.php" class="nav_link sublink">Encuestas Realizadas</a>
            </ul>
          </li>


          <li class="item">
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="fa-solid fa-house-user"></i>
              </span>

              <span class="navlink">Entorno Hogar - Vivienda</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>

            <ul class="menu_items submenu">
              <a href="code/home/showentornoHogar.php" class="nav_link sublink">Aplicar Encuesta</a>
              <a href="code/home/checkentornoHogar.php" class="nav_link sublink">Encuestas Realizadas</a>
            </ul>
          </li>
          <li class="item">
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="fa-solid fa-people-roof"></i>
              </span>

              <span class="navlink">Salud y Familia</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>

            <ul class="menu_items submenu">
              <a href="code/student/showHealthFamily.php" class="nav_link sublink">Aplicar Encuesta</a>
              <a href="code/student/checkHealthFamily.php" class="nav_link sublink">Encuestas Realizadas</a>
            </ul>
          </li>
          <li class="item">
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="fa-solid fa-school"></i>
              </span>

              <span class="navlink">Educación y Formación</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>

            <ul class="menu_items submenu">
              <a href="code/student/showEducation.php" class="nav_link sublink">Aplicar Encuesta</a>
              <a href="code/student/checkEducation.php" class="nav_link sublink">Encuestas Realizadas</a>
            </ul>
          </li>
          <li class="item">
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="fa-solid fa-book-bookmark"></i>
              </span>

              <span class="navlink">Desempeño Académico</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>

            <ul class="menu_items submenu">
              <a href="code/student/showPerformance.php" class="nav_link sublink">Aplicar Encuesta</a>
              <a href="code/student/checkPerformance.php" class="nav_link sublink">Encuestas Realizadas</a>
            </ul>
          </li>
          <li class="item">
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="fa-solid fa-baby"></i>
              </span>

              <span class="navlink">Desarrollo Preescolar</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>

            <ul class="menu_items submenu">
              <a href="code/student/showPreescolar.php" class="nav_link sublink">Aplicar Encuesta</a>
              <a href="code/student/preescolar/checkPreescolar.php" class="nav_link sublink">Encuestas Realizadas</a>
            </ul>
          </li>

          <li class="item">
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="fa-solid fa-person"></i>
              </span>

              <span class="navlink">Desarrollo Personal</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>

            <ul class="menu_items submenu">
              <a href="code/student/showPersonal.php" class="nav_link sublink">Aplicar Encuesta</a>
              <a href="code/student/personal/checkPersonal.php" class="nav_link sublink">Encuestas Realizadas</a>
            </ul>
          </li>
          <li class="item">
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="fa-sharp fa-solid fa-book"></i>
              </span>

              <span class="navlink">Preguntas al Estudiante</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>

            <ul class="menu_items submenu">
              <a href="code/student/showQuestions.php" class="nav_link sublink">Aplicar Encuesta</a>
              <a href="code/student/question/checkQuestion.php" class="nav_link sublink">Encuestas Realizadas</a>
            </ul>
          </li>

          <li class="item">
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="fa-solid fa-screwdriver-wrench"></i>
              </span>
              <span class="navlink">Mi Cuenta</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>

            <ul class="menu_items submenu">
              <a href="reset-password.php" class="nav_link sublink">Cambiar Contraseña</a>
            </ul>
          </li>

          <!-- Sidebar Open / Close -->
          <div class="bottom_content">
            <div class="bottom expand_sidebar">
              <span> Expand</span>
              <i class='bx bx-log-in'></i>
            </div>
            <div class="bottom collapse_sidebar">
              <span> Collapse</span>
              <i class='bx bx-log-out'></i>
            </div>
          </div>
      </div>
    </nav>
  <?php } ?>

  <!--********************************INICIA DOCENTE ORIENTADOR********************************-->

  <?php if ($tipo_usuario == 5) { ?>
    <!-- sidebar -->
    <nav class="sidebar">
      <div class="menu_content">
        <ul class="menu_items">
          <div class="menu_title menu_dahsboard"></div>
          <li class="item">
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="fa-solid fa-baby-carriage"></i>
              </span>
              <span class="navlink">Pre Post-Natales</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>

            <ul class="menu_items submenu">
              <a href="code/student/showprePostnatales.php" class="nav_link sublink">Aplicar Encuesta</a>
              <a href="code/student/checkprePostnatales.php" class="nav_link sublink">Encuestas Realizadas</a>
            </ul>
          </li>


          <li class="item">
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="fa-solid fa-house-user"></i>
              </span>

              <span class="navlink">Entorno Hogar - Vivienda</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>

            <ul class="menu_items submenu">
              <a href="code/home/showentornoHogar.php" class="nav_link sublink">Aplicar Encuesta</a>
              <a href="code/home/checkentornoHogar.php" class="nav_link sublink">Encuestas Realizadas</a>
            </ul>
          </li>
          <li class="item">
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="fa-solid fa-people-roof"></i>
              </span>

              <span class="navlink">Salud y Familia</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>

            <ul class="menu_items submenu">
              <a href="code/student/showHealthFamily.php" class="nav_link sublink">Aplicar Encuesta</a>
              <a href="code/student/checkHealthFamily.php" class="nav_link sublink">Encuestas Realizadas</a>
            </ul>
          </li>
          <li class="item">
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="fa-solid fa-school"></i>
              </span>

              <span class="navlink">Educación y Formación</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>

            <ul class="menu_items submenu">
              <a href="code/student/showEducation.php" class="nav_link sublink">Aplicar Encuesta</a>
              <a href="code/student/checkEducation.php" class="nav_link sublink">Encuestas Realizadas</a>
            </ul>
          </li>
          <li class="item">
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="fa-solid fa-book-bookmark"></i>
              </span>

              <span class="navlink">Desempeño Académico</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>

            <ul class="menu_items submenu">
              <a href="code/student/showPerformance.php" class="nav_link sublink">Aplicar Encuesta</a>
              <a href="code/student/checkPerformance.php" class="nav_link sublink">Encuestas Realizadas</a>
            </ul>
          </li>
          <li class="item">
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="fa-solid fa-baby"></i>
              </span>

              <span class="navlink">Desarrollo Preescolar</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>

            <ul class="menu_items submenu">
              <a href="code/student/showPreescolar.php" class="nav_link sublink">Aplicar Encuesta</a>
              <a href="code/student/preescolar/checkPreescolar.php" class="nav_link sublink">Encuestas Realizadas</a>
            </ul>
          </li>

          <li class="item">
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="fa-solid fa-person"></i>
              </span>

              <span class="navlink">Desarrollo Personal</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>

            <ul class="menu_items submenu">
              <a href="code/student/showPersonal.php" class="nav_link sublink">Aplicar Encuesta</a>
              <a href="code/student/personal/checkPersonal.php" class="nav_link sublink">Encuestas Realizadas</a>
            </ul>
          </li>
          <li class="item">
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="fa-sharp fa-solid fa-book"></i>
              </span>

              <span class="navlink">Preguntas al Estudiante</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>

            <ul class="menu_items submenu">
              <a href="code/student/showQuestions.php" class="nav_link sublink">Aplicar Encuesta</a>
              <a href="code/student/question/checkQuestion.php" class="nav_link sublink">Encuestas Realizadas</a>
            </ul>
          </li>


          <li class="item">
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="fa-solid fa-chart-pie"></i>
              </span>
              <span class="navlink">Cargar Estudiantes</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>

            <ul class="menu_items submenu">
              <a href="code/uploadData/loadData.php" class="nav_link sublink">Subir Excel</a>
            </ul>
          </li>

          <li class="item">
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="fa-solid fa-file"></i>
              </span>
              <span class="navlink">Informe Encuestas</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>

            <ul class="menu_items submenu">
              <a href="code/informe/informeEncuestas.php" class="nav_link sublink">Ver Informes</a>
            </ul>
          </li>
          
          <li class="item">
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="fa-solid fa-screwdriver-wrench"></i>
              </span>
              <span class="navlink">Mi Cuenta</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>

            <ul class="menu_items submenu">
              <a href="reset-password.php" class="nav_link sublink">Cambiar Contraseña</a>
            </ul>
          </li>

          <!-- Sidebar Open / Close -->
          <div class="bottom_content">
            <div class="bottom expand_sidebar">
              <span> Expand</span>
              <i class='bx bx-log-in'></i>
            </div>
            <div class="bottom collapse_sidebar">
              <span> Collapse</span>
              <i class='bx bx-log-out'></i>
            </div>
          </div>
      </div>
    </nav>
  <?php } ?>

  <!--********************************INICIA MENÚ ADMINISTRATIVO********************************-->

  <?php if ($tipo_usuario == 6) { ?>
    <!-- sidebar -->
    <nav class="sidebar">
      <div class="menu_content">
        <ul class="menu_items">
          <div class="menu_title menu_dahsboard"></div>

          <li class="item">
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="fa-solid fa-address-card"></i>
              </span>

              <span class="navlink">SIMAT Info. General</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>

            <ul class="menu_items submenu">
              <a href="code/student/showsimat.php" class="nav_link sublink">Actualizar Datos</a></a>
              <a href="code/student/checksimat.php" class="nav_link sublink">Estudiantes Actualizados</a>
            </ul>
          </li>

          <li class="item">
            <div href="#" class="nav_link submenu_item">
              <span class="navlink_icon">
                <i class="fa-solid fa-screwdriver-wrench"></i>
              </span>
              <span class="navlink">Mi Cuenta</span>
              <i class="bx bx-chevron-right arrow-left"></i>
            </div>

            <ul class="menu_items submenu">
              <a href="reset-password.php" class="nav_link sublink">Cambiar Contraseña</a>
            </ul>
          </li>

          <!-- Sidebar Open / Close -->
          <div class="bottom_content">
            <div class="bottom expand_sidebar">
              <span> Expand</span>
              <i class='bx bx-log-in'></i>
            </div>
            <div class="bottom collapse_sidebar">
              <span> Collapse</span>
              <i class='bx bx-log-out'></i>
            </div>
          </div>
      </div>
    </nav>
  <?php } ?>

  <!-- JavaScript -->
  <script src="menu/script.js"></script>
</body>

</html>