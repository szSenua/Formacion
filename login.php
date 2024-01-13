<?php
require_once 'conecta.php';
require_once 'funciones.php';

$dni = '';
$contrasena = '';
$errores = array();

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    // Si no es una solicitud POST, mostrar el formulario
    pintaLoginconParam($dni, $contrasena, $errores);
} else {
    // Verificar si el usuario y contraseña son válidos
    $dni = $_POST['dni'];
    $contrasena = $_POST['contrasena'];

    // Obtener información del usuario
    $usuarioData = obtenerInfoUsuario($dni, $contrasena);

    // Verificar si el usuario es válido
    if ($usuarioData) {
        session_start();
        $_SESSION['dni'] = $dni;
        $_SESSION['tipoUsuario'] = $usuarioData['tipo'];
        $_SESSION['nombreUsuario'] = $usuarioData['nombre'];
        $_SESSION['logged_in'] = true;

        header("Location: menu.php");
        exit();
    } else {
        // Si el usuario no es válido, mostrar el formulario de login con errores
        $errores[] = 'Usuario o contraseña incorrectos';
        pintaLoginconParam($dni, $contrasena, $errores);
    }
}
?>


