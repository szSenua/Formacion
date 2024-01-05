<?php
//Para que muestre los errores
ini_set("error_reporting", E_ALL);
ini_set("display_errors", "on");


//Conecta dedicado a crear la base de datos. Una vez creada, se utiliza conecta.php que ya tendrá la db_name incorporada


$db_user = "formacion";
$db_pass = "formacion";
$db_host = "localhost";

$conexion = mysqli_connect($db_host, $db_user, $db_pass);

if (mysqli_connect_errno()) {
    $mensaje = "Error al conectar";
    error_log($mensaje, 0);
} else {
    $mensaje = "Conectando a la base de datos <br>";
    error_log($mensaje, 0);
}



?>