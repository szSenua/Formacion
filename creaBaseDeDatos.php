<?php
// Crear la base de datos cursoscp

require_once 'conexionCrearBD.php';

// Nombre de la base de datos
$db_name = 'cursoscp';

// Nombre del archivo de log
$logFile = 'creacion_bd.log';

// Verificar si la base de datos ya existe
$sqlCheckDB = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$db_name'";

$result = mysqli_query($conexion, $sqlCheckDB);

if (mysqli_num_rows($result) > 0) {
    $mensaje = "La base de datos ya existe.";
    error_log($mensaje, 0);
} else {
    // Crear la base de datos
    $sqlCrearDB = "CREATE DATABASE $db_name";

    if (mysqli_query($conexion, $sqlCrearDB)) {
        $mensaje = "Base de datos creada con éxito.";
        error_log($mensaje, 0);

        // Seleccionar la base de datos después de crearla
        mysqli_select_db($conexion, $db_name);

        // Abrir el archivo de log en modo de escritura (creará el archivo si no existe)
        $logHandle = fopen($logFile, 'a+');

        if ($logHandle) {
            // Escribir en el log
            $dateTime = new DateTime();
            fwrite($logHandle, $dateTime->format('Y-m-d H:i:s') . " - Base de datos creada con éxito.\n");

            // Declarar el archivo de la base de datos
            $sqliFile = 'inmobiliaria.sql';

            // Si existe el archivo de la base de datos
            if (file_exists($sqliFile)) {
                // Abrir el archivo y ejecutar las consultas una por una
                $file = fopen($sqliFile, "r");

                if ($file) {
                    $query = '';
                    while (!feof($file)) {
                        $line = fgets($file);
                        
                        // Ignorar líneas en blanco y comentarios
                        if (!empty($line) && strpos($line, '--') === false) {
                            $query .= $line;

                            // Si la línea termina con un punto y coma, ejecutar la consulta
                            if (substr(trim($line), -1) == ';') {
                                if (mysqli_query($conexion, $query)) {
                                    // Escribir en el log
                                    fwrite($logHandle, $dateTime->format('Y-m-d H:i:s') . " - Consulta ejecutada con éxito: $query\n");
                                } else {
                                    // Escribir en el log
                                    fwrite($logHandle, $dateTime->format('Y-m-d H:i:s') . " - Error al ejecutar la consulta: $query\n");
                                    fwrite($logHandle, $dateTime->format('Y-m-d H:i:s') . " - Error: " . mysqli_error($conexion) . "\n");
                                    exit();
                                }
                                //Reseteo la variable query
                                $query = '';
                            }
                        }
                    }

                    fclose($file);
                    fwrite($logHandle, $dateTime->format('Y-m-d H:i:s') . " - Tablas creadas con éxito\n\n" . "---------------------------------" . "\n\n");
                } else {
                    fwrite($logHandle, $dateTime->format('Y-m-d H:i:s') . " - Error al abrir el archivo $sqliFile\n");
                    exit();
                }
            } else {
                fwrite($logHandle, $dateTime->format('Y-m-d H:i:s') . " - No se encontró el archivo $sqliFile\n");
                exit();
            }

            // Cerrar el archivo de log
            fclose($logHandle);
        } else {
            $mensaje = "Error al abrir el archivo de log " . $logFile;
            error_log($mensaje, 0);
            exit();
        }
    } else {
        $mensaje = "Error al crear la base de datos: " . mysqli_error($conexion);
        error_log($mensaje, 0);
        exit();
    }
}

// Cerrar la conexión
require_once 'desconecta.php';
?>