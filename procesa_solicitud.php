<?php
require_once 'menu.php';
require_once 'conecta.php';

//Aceptar la solicitud

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adjudicar'])) {
    $dni = isset($_POST['dni']) ? $_POST['dni'] : '';
    $codigoCurso = isset($_POST['codigo_curso']) ? $_POST['codigo_curso'] : '';

    // Verificar si hay plazas disponibles
    $sqlPlazasDisponibles = "SELECT numeroplazas FROM cursos WHERE codigo = $codigoCurso";
    $resultPlazasDisponibles = mysqli_query($conexion, $sqlPlazasDisponibles);

    if ($resultPlazasDisponibles && mysqli_num_rows($resultPlazasDisponibles) > 0) {
        $plazasDisponibles = mysqli_fetch_assoc($resultPlazasDisponibles)['numeroplazas'];

        if ($plazasDisponibles > 0) {
            // Adjudicar el curso al solicitante
            $sqlAdjudicar = "UPDATE solicitudes SET admitido = 1 WHERE dni = '$dni' AND codigocurso = $codigoCurso";
            $resultAdjudicar = mysqli_query($conexion, $sqlAdjudicar);

            if ($resultAdjudicar) {
                // Decrementar el número de plazas disponibles
                $sqlDecrementarPlazas = "UPDATE cursos SET numeroplazas = numeroplazas - 1 WHERE codigo = $codigoCurso";
                mysqli_query($conexion, $sqlDecrementarPlazas);

                header("Location: adjudica_cursos.php");
            } else {
                echo '<p style="color: red;">Error al adjudicar el curso.</p>';
            }
        } else {
            echo '<p style="color: red;">No hay plazas disponibles para este curso.</p>';
        }
    } else {
        echo '<p style="color: red;">Error al obtener el número de plazas disponibles.</p>';
    }
}

//Rechazar la solicitud

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rechazar'])) {
    $dni = isset($_POST['dni']) ? $_POST['dni'] : '';
    $codigoCurso = isset($_POST['codigo_curso']) ? $_POST['codigo_curso'] : '';

    // Rechazar la solicitud
    $sqlRechazar = "UPDATE solicitudes SET admitido = 0 WHERE dni = '$dni' AND codigocurso = $codigoCurso";
    $resultRechazar = mysqli_query($conexion, $sqlRechazar);

    if ($resultRechazar) {
        header("Location: adjudica_cursos.php");
    } else {
        echo '<p style="color: red;">Error al rechazar la solicitud.</p>';
    }
}

// Cerrar la conexión
require_once 'desconecta.php';
?>


