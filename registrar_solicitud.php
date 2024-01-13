<?php
session_start();

// Verifica si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once 'conecta.php';

    // Obtener dni
    $dni = $_SESSION['dni'];

    // Obtiene el código del curso desde el formulario
    $codigocurso = $_POST['codigocurso'];

    // Verifica si el curso está abierto
    $queryCurso = "SELECT abierto, plazoinscripcion FROM cursos WHERE codigo = ?";
    $stmtCurso = mysqli_prepare($conexion, $queryCurso);
    mysqli_stmt_bind_param($stmtCurso, "i", $codigocurso);
    mysqli_stmt_execute($stmtCurso);
    mysqli_stmt_bind_result($stmtCurso, $abierto, $plazoinscripcion);
    mysqli_stmt_fetch($stmtCurso);
    mysqli_stmt_close($stmtCurso);

    // Comprueba si el curso está abierto y dentro del plazo de inscripción
    if ($abierto == 1 && strtotime($plazoinscripcion) >= strtotime(date('Y-m-d'))) {

            $query = "INSERT INTO solicitudes (dni, codigocurso, fechasolicitud) VALUES (?, ?, NOW())";
            $stmt = mysqli_prepare($conexion, $query);
            mysqli_stmt_bind_param($stmt, "si", $dni, $codigocurso);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            // Cierra la conexión
            require_once 'desconecta.php';

            // Redirige a la página de cursos o muestra un mensaje de éxito
            header("Location: listar_cursos.php");
            exit();
    } else {
        // Curso no disponible, redirige con mensaje de error
        header("Location: listar_cursos.php?error=curso-no-disponible");
        exit();
    }
} else {
    // Si no viene por el formulario, redirige
    header("Location: listar_cursos.php");
    exit();
}
?>

