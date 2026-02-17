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
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" type="text/css" href="css/estilos2024.css">
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm">
    <script src="https://kit.fontawesome.com/fed2435e21.js" crossorigin="anonymous"></script>    <style>
        :root {
            --primary-color: #4f46e5;
            --secondary-color: #7c3aed;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --light-bg: #f8fafc;
            --white: #ffffff;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --border-color: #e5e7eb;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #ffffff;
            min-height: 100vh;
            color: var(--text-dark);
        }

        .responsive {
            max-width: 100%;
            height: auto;
            border-radius: 12px;
            box-shadow: var(--shadow);
        }        .main-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
            background: #f8fafc;
            min-height: 100vh;
        }        .header-card {
            background: var(--white);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            text-align: center;
            border: 1px solid var(--border-color);
        }

        .header-title {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 10px;
            text-shadow: none;
        }        .search-card {
            background: var(--white);
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border: 1px solid var(--border-color);
        }

        .search-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            align-items: end;
        }

        .input-group {
            display: flex;
            flex-direction: column;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 8px;
            font-size: 0.9rem;
        }

        .form-input {
            padding: 12px 16px;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: var(--white);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .btn-search {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: var(--white);
            border: none;
            padding: 12px 32px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: var(--shadow);
        }

        .btn-search:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }        .table-card {
            background: var(--white);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            margin-bottom: 30px;
            border: 1px solid var(--border-color);
        }

        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.95rem;
        }

        thead {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        }

        th {
            padding: 20px 16px;
            text-align: left;
            font-weight: 600;
            color: var(--white);
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid var(--border-color);
        }

        tbody tr:hover {
            background: rgba(79, 70, 229, 0.05);
            transform: scale(1.005);
        }

        td {
            padding: 16px;
            vertical-align: middle;
        }

        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.85rem;
            text-align: center;
            min-width: 120px;
        }

        .status-updated {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            color: #065f46;
            border: 1px solid #10b981;
        }        .status-pending {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #991b1b;
            border: 1px solid #ef4444;
        }

        .status-active {
            background: linear-gradient(135deg, #dcfce7, #bbf7d0);
            color: #166534;
            border: 1px solid #22c55e;
        }

        .status-inactive {
            background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
            color: #374151;
            border: 1px solid #9ca3af;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            transition: all 0.3s ease;
            margin: 0 4px;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }

        .btn-edit {
            background: linear-gradient(135deg, var(--warning-color), #f97316);
        }

        .btn-delete {
            background: linear-gradient(135deg, var(--danger-color), #dc2626);
        }

        .btn-back {
            background: var(--white);
            border-radius: 16px;
            padding: 16px 24px;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            font-weight: 600;
            color: var(--text-dark);
        }        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            text-decoration: none;
            color: var(--primary-color);
        }

        .btn-excel {
            background: linear-gradient(135deg, #059669, #10b981);
            color: var(--white);
            border-radius: 16px;
            padding: 16px 24px;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            font-weight: 600;
        }

        .btn-excel:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            text-decoration: none;
            color: var(--white);
        }

        .pagination-container {
            display: flex;
            justify-content: center;
            margin: 30px 0;
        }        .share-container {
            background: var(--white);
            border-radius: 16px;
            padding: 20px;
            margin: 30px 0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            text-align: center;
            border: 1px solid var(--border-color);
        }

        @media (max-width: 768px) {
            .search-form {
                grid-template-columns: 1fr;
            }
            
            .header-title {
                font-size: 1.8rem;
            }
            
            table {
                font-size: 0.85rem;
            }
            
            th, td {
                padding: 12px 8px;
            }
        }

        /* Animaciones */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .header-card,
        .search-card,
        .table-card {
            animation: fadeInUp 0.6s ease-out;
        }

        .search-card {
            animation-delay: 0.1s;
        }

        .table-card {
            animation-delay: 0.2s;
        }
    </style>    <script>
        function confirmarEliminacion(num_doc_est) {
            // Crear modal personalizado
            const modal = document.createElement('div');
            modal.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 10000;
                backdrop-filter: blur(5px);
            `;

            const modalContent = document.createElement('div');
            modalContent.style.cssText = `
                background: white;
                padding: 30px;
                border-radius: 16px;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
                max-width: 400px;
                width: 90%;
                text-align: center;
                animation: modalSlideIn 0.3s ease-out;
            `;

            modalContent.innerHTML = `
                <style>
                    @keyframes modalSlideIn {
                        from { opacity: 0; transform: scale(0.8) translateY(-20px); }
                        to { opacity: 1; transform: scale(1) translateY(0); }
                    }
                </style>
                <div style="color: #ef4444; font-size: 3rem; margin-bottom: 20px;">
                    <i class="fa-solid fa-exclamation-triangle"></i>
                </div>
                <h3 style="color: #1f2937; margin-bottom: 15px; font-weight: 600;">¿Eliminar Estudiante?</h3>
                <p style="color: #6b7280; margin-bottom: 25px; line-height: 1.5;">
                    Esta acción eliminará permanentemente al estudiante con documento <strong>${num_doc_est}</strong> de la lista. 
                    <br><br>
                    <strong>Esta acción no se puede deshacer.</strong>
                </p>
                <div style="display: flex; gap: 10px; justify-content: center;">
                    <button onclick="eliminarEstudiante('${num_doc_est}')" style="
                        background: linear-gradient(135deg, #ef4444, #dc2626);
                        color: white;
                        border: none;
                        padding: 12px 24px;
                        border-radius: 8px;
                        font-weight: 600;
                        cursor: pointer;
                        transition: all 0.3s ease;
                    ">
                        <i class="fa-solid fa-trash"></i> Sí, Eliminar
                    </button>
                    <button onclick="cerrarModal()" style="
                        background: #f3f4f6;
                        color: #374151;
                        border: none;
                        padding: 12px 24px;
                        border-radius: 8px;
                        font-weight: 600;
                        cursor: pointer;
                        transition: all 0.3s ease;
                    ">
                        <i class="fa-solid fa-times"></i> Cancelar
                    </button>
                </div>
            `;

            modal.appendChild(modalContent);
            document.body.appendChild(modal);

            // Funciones para manejar el modal
            window.eliminarEstudiante = function(documento) {
                window.location.href = 'deletesimat.php?num_doc_est=' + documento;
            };

            window.cerrarModal = function() {
                modal.style.animation = 'modalSlideOut 0.3s ease-in';
                setTimeout(() => {
                    document.body.removeChild(modal);
                }, 300);
            };

            // Cerrar modal si se hace clic fuera
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    window.cerrarModal();
                }
            });

            // Agregar animación de salida
            const style = document.createElement('style');
            style.textContent = `
                @keyframes modalSlideOut {
                    from { opacity: 1; transform: scale(1) translateY(0); }
                    to { opacity: 0; transform: scale(0.8) translateY(-20px); }
                }
            `;
            document.head.appendChild(style);
        }
    </script>
</head>
<body>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"></script>

<div class="main-container">
    <center>
        <img src='../../img/logo_educacion.png' width=600 height=121 class='responsive'>
    </center>

    <div class="header-card">
        <h1 class="header-title">
            <i class="fa-solid fa-address-card"></i> INFORMACIÓN GENERAL DEL ESTUDIANTE - SIMAT
        </h1>
        <p style="color: #6b7280; margin-top: 10px;">Gestiona y consulta la información de los estudiantes</p>
    </div>    <div class="search-card">
        <form action="showsimat.php" method="get" class="search-form">
            <div class="input-group">
                <label class="form-label">Número de Documento</label>
                <input name="num_doc_est" type="text" class="form-input" placeholder="Ingrese el número de documento" value="<?php echo htmlspecialchars($num_doc_est ?? ''); ?>">
            </div>
            <div class="input-group">
                <label class="form-label">Nombre y Apellidos</label>
                <input name="nom_ape_est" type="text" class="form-input" placeholder="Ingrese nombres y/o apellidos" value="<?php echo htmlspecialchars($nom_ape_est ?? ''); ?>">
            </div>
            <div class="input-group">
                <label class="form-label">Grado</label>
                <input name="grado_est" type="text" class="form-input" placeholder="Ingrese el grado" value="<?php echo htmlspecialchars($grado_est ?? ''); ?>">
            </div>
            <div class="input-group">
                <label class="form-label">Estado del Estudiante</label>
                <select name="estado_est" class="form-input">
                    <option value="todos" <?php echo (isset($_GET['estado_est']) && $_GET['estado_est'] == 'todos') ? 'selected' : ''; ?>>Todos los estados</option>
                    <option value="1" <?php echo (!isset($_GET['estado_est']) || (isset($_GET['estado_est']) && $_GET['estado_est'] == '1')) ? 'selected' : ''; ?>>Activos</option>
                    <option value="0" <?php echo (isset($_GET['estado_est']) && $_GET['estado_est'] == '0') ? 'selected' : ''; ?>>Inactivos</option>
                </select>
            </div>
            <div class="input-group">
                <button type="submit" class="btn-search">
                    <i class="fa-solid fa-search"></i> Buscar Estudiantes
                </button>
            </div>
        </form>
    </div><div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
        <a href="../../access.php" class="btn-back">
            <img src='../../img/atras.png' width="24" height="24">
            Regresar al Menú
        </a>
        <a href="exportarSimat.php?num_doc_est=<?php echo urlencode($num_doc_est ?? ''); ?>&nom_ape_est=<?php echo urlencode($nom_ape_est ?? ''); ?>&grado_est=<?php echo urlencode($grado_est ?? ''); ?>&estado_est=<?php echo urlencode($estado_est ?? ''); ?>" class="btn-excel">
            <i class="fa-solid fa-file-excel"></i>
            Exportar a Excel
        </a>
    </div>

<?php

date_default_timezone_set("America/Bogota");
include("../../conexion.php");
require_once("../../zebra.php");

@$num_doc_est = ($_GET['num_doc_est']);
@$nom_ape_est = ($_GET['nom_ape_est']);
@$grado_est = ($_GET['grado_est']);
@$estado_est = isset($_GET['estado_est']) ? $_GET['estado_est'] : '1'; // Por defecto mostrar activos

// Construir la condición del filtro de estado
$filtro_estado = "";
if ($estado_est !== '' && $estado_est !== null && $estado_est !== 'todos') {
    $filtro_estado = " AND estudiantes.estado_est = '$estado_est'";
}

$query = "SELECT estudiantes.*, usuarios.*, ie.*
  FROM estudiantes 
  INNER JOIN ieSede ON estudiantes.cod_dane_ieSede=ieSede.cod_dane_ieSede 
  INNER JOIN ie ON ieSede.cod_dane_ie=ie.cod_dane_ie 
  INNER JOIN usuarios ON estudiantes.id_usu = usuarios.id
  WHERE (estudiantes.num_doc_est LIKE '%$num_doc_est%') 
  AND (estudiantes.nom_ape_est LIKE '%$nom_ape_est%') 
  AND (estudiantes.grado_est LIKE '%$grado_est%')
  AND ie.cod_dane_ie = $cod_dane_ie 
  $filtro_estado
  ORDER BY estudiantes.estado_est DESC, estudiantes.num_doc_est ASC";
$res = $mysqli->query($query);
$num_registros = mysqli_num_rows($res);
$resul_x_pagina = 50;

if ($res) {

    $paginacion = new Zebra_Pagination();
    $paginacion->records($num_registros);
    $paginacion->records_per_page($resul_x_pagina);    $consulta = "SELECT estudiantes.*, usuarios.*, ie.*
         FROM estudiantes 
         INNER JOIN ieSede ON estudiantes.cod_dane_ieSede=ieSede.cod_dane_ieSede 
         INNER JOIN ie ON ieSede.cod_dane_ie=ie.cod_dane_ie 
         INNER JOIN usuarios ON estudiantes.id_usu = usuarios.id
         WHERE (estudiantes.num_doc_est LIKE '%$num_doc_est%') 
         AND (estudiantes.nom_ape_est LIKE '%$nom_ape_est%') 
         AND (estudiantes.grado_est LIKE '%$grado_est%')
         AND ie.cod_dane_ie = $cod_dane_ie 
         $filtro_estado
         ORDER BY estudiantes.estado_est DESC, estudiantes.num_doc_est ASC 
         LIMIT " .(($paginacion->get_page() - 1) * $resul_x_pagina). "," .$resul_x_pagina;
    $result = $mysqli->query($consulta);    if ($result) {
        echo '<div class="pagination-container">';
        $paginacion->render();
        echo '</div>';
        
        echo "<div class='table-card'>
                <div class='table-responsive'>
                    <table>
                        <thead>
                            <tr>                                <th><i class='fa-solid fa-hashtag'></i> No.</th>
                                <th><i class='fa-solid fa-id-card'></i> Tipo</th>
                                <th><i class='fa-solid fa-fingerprint'></i> Documento</th>
                                <th><i class='fa-solid fa-user'></i> Estudiante</th>
                                <th><i class='fa-solid fa-graduation-cap'></i> Grado</th>
                                <th><i class='fa-solid fa-toggle-on'></i> Estado Estudiante</th>
                                <th><i class='fa-solid fa-chart-line'></i> Estado Actualización</th>
                                <th><i class='fa-solid fa-edit'></i> Editar</th>
                                <th><i class='fa-solid fa-trash'></i> Eliminar</th>
                            </tr>
                        </thead>
                        <tbody>";

        $i = 1;        while ($row = mysqli_fetch_array($result)) {
            // Lógica para determinar el estado basado en fecha_edit_est
            $fecha_edit = $row['fecha_edit_est'];
            $año_fecha_edit = date('Y', strtotime($fecha_edit));
            
            if ($año_fecha_edit > 2000) {
                $fecha_formateada = date('d/m/Y', strtotime($fecha_edit));
                $estado_actualizacion_html = "<span class='status-badge status-updated'><i class='fa-solid fa-check-circle'></i> Actualizado $fecha_formateada</span>";
            } else {
                $estado_actualizacion_html = "<span class='status-badge status-pending'><i class='fa-solid fa-clock'></i> PENDIENTE</span>";
            }

            // Lógica para el estado del estudiante (activo/inactivo)
            if ($row['estado_est'] == 1) {
                $estado_estudiante_html = "<span class='status-badge status-active'><i class='fa-solid fa-user-check'></i> ACTIVO</span>";
            } else {
                $estado_estudiante_html = "<span class='status-badge status-inactive'><i class='fa-solid fa-user-slash'></i> INACTIVO</span>";
            }            echo '
                <tr>
                    <td><strong>'.($i + (($paginacion->get_page() - 1) * $resul_x_pagina)).'</strong></td>
                    <td><span style="background: #e0e7ff; color: #3730a3; padding: 4px 8px; border-radius: 6px; font-size: 0.8rem; font-weight: 600;">'.$row['tip_doc_est'].'</span></td>
                    <td><strong style="color: #1f2937;">'.$row['num_doc_est'].'</strong></td>
                    <td><span style="font-weight: 500;">'.htmlspecialchars($row['nom_ape_est'], ENT_QUOTES, 'UTF-8').'</span></td>
                    <td><span style="background: #f3f4f6; color: #374151; padding: 4px 12px; border-radius: 12px; font-weight: 600;">'.$row['grado_est'].'</span></td>
                    <td>'.$estado_estudiante_html.'</td>
                    <td>'.$estado_actualizacion_html.'</td>
                    <td>
                        <a href="addsimat.php?num_doc_est='.$row['num_doc_est'].'" class="action-btn btn-edit" title="Editar estudiante">
                            <i class="fa-solid fa-edit" style="color: white;"></i>
                        </a>
                    </td>
                    <td>
                        <a href="#" onclick="confirmarEliminacion('.$row['num_doc_est'].')" class="action-btn btn-delete" title="Eliminar estudiante">
                            <i class="fa-solid fa-trash" style="color: white;"></i>
                        </a>
                    </td>
                </tr>';
            $i++;
        }

        echo '</tbody></table></div></div>';

        echo '<div class="pagination-container">';
        $paginacion->render();
        echo '</div>';

    } else {
        echo "<div style='background: #fee2e2; color: #991b1b; padding: 20px; border-radius: 12px; margin: 20px 0; text-align: center;'>
                <i class='fa-solid fa-exclamation-triangle'></i> Error en la consulta: " . $mysqli->error . "
              </div>";
    }
} else {
    echo "<div style='background: #fee2e2; color: #991b1b; padding: 20px; border-radius: 12px; margin: 20px 0; text-align: center;'>
            <i class='fa-solid fa-exclamation-triangle'></i> Error en la consulta: " . $mysqli->error . "
          </div>";
}
?>

    
    <div style="text-align: center; margin: 30px 0;">
        <a href="../../access.php" class="btn-back">
            <img src='../../img/atras.png' width="24" height="24">
            Regresar al Menú Principal
        </a>
    </div>

</div>

<script src="js/app.js"></script>
<script src="https://www.jose-aguilar.com/scripts/fontawesome/js/all.min.js" data-auto-replace-svg="nest"></script>

</body>
</html>
