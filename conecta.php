<?php

//Para que muestre los errores
ini_set("error_reporting", E_ALL);
ini_set("display_errors", "on");

$db_name = "cursoscp";
$db_user = "formacion";
$db_pass = "formacion";
$db_host = "localhost";

$conexion = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (mysqli_connect_errno()) {
    echo "Error al conectar";
} else {
    $mensaje = "Conectando a la base de datos <br>";
    //error_log($mensaje, 0);

}

?>