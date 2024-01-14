<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        nav {
            background-color: #342042;
            overflow: hidden;
            
        }

        nav a {
            display: block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            float: left;
            transition: background-color 0.3s;
        }

        nav a:hover {
            background-color: #714C8F;
        }

        h1 {
            color: #000;
            padding: 10px;
        }
    </style>
    <title></title>
</head>
<body>
<?php

session_start();



// Verifica el rol del usuario
$rol = isset($_SESSION['tipoUsuario']) ? $_SESSION['tipoUsuario'] : '';
$nombre = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'invitado';


?>

<nav>
    
    <a href="listar_cursos.php">Listar Cursos</a>
    

    <?php
    if ($rol === 'administrador') {
        
        echo '<a href="panel_administracion.php">Panel de Administración</a>';
        echo '<a href="adjudica_cursos.php">Adjudicaciones</a>';
    }

    if($rol === 'solicitante') {
        echo '<a href="solicitudes_realizadas.php">Solicitudes Realizadas</a>';
    }

    // Verifica si hay un rol para mostrar el enlace correcto
    if (empty($rol)) {
        echo '<a href="login.php" style="float: right;">Iniciar Sesión</a>';
    } else {
        echo '<a href="logout.php" style="float: right;">Cerrar Sesión</a>';
    }
    ?>
    
</nav>


</body>
</html>
