<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        h2 {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px;
            margin: 0;
        }

        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            background-color: #eee;
            text-align: center;
        }

        nav li {
            display: inline-block;
            margin: 10px;
        }

        nav a {
            text-decoration: none;
            color: #333;
            padding: 8px 16px;
            border-radius: 4px;
            background-color: #ddd;
        }

        nav a:hover {
            background-color: #bbb;
        }
    </style>
    <title>Menú</title>
</head>
<body>
    <h2>Menú</h2>
    <nav>
        <ul>

<?php

//Propago sesión y reviso que el usuario esté autenticado, si no redirijo al login
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// incluir funciones
require_once 'funciones.php';

// Obtener el tipo de usuario (administrador o solicitante) desde la base de datos
$usuario = $_SESSION['usuario'];
$tipoUsuario = obtenerTipoUsuario($usuario); 

// Mostrar opciones según el tipo de usuario
if ($tipoUsuario === 'administrador') {
    echo '<li><a href="#">Listar todos los cursos</a></li>';
    echo '<li><a href="#">Abrir y Cerrar cursos mediante checkbox</a></li>';
    echo '<li><a href="#">Asignar vacantes a los solicitantes</a></li>';
    echo '<li><a href="#">Eliminar cursos</a></li>';
    echo '<li><a href="#">Agregar cursos</a></li>';
} elseif ($tipoUsuario === 'usuario') {
    echo '<li><a href="#">Visualizar cursos abiertos (o todos y filtrar por abiertos)</a></li>';
    echo '<li><a href="#">Suscribirse a cursos abiertos</a></li>';
} else {
    echo '<li><a href="#">Ver listado de cursos</a></li>';
}
?>

</ul>
    </nav>
</body>
</html>








