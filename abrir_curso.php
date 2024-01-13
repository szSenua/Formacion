<?php

require_once 'conecta.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener el código del curso desde el formulario
    $codigocurso = isset($_POST['codigocurso']) ? $_POST['codigocurso'] : '';

    // Actualizar el estado del curso a abierto
    $queryAbrirCurso = "UPDATE cursos SET abierto = 1 WHERE codigo = ?";
    $stmtAbrirCurso = mysqli_prepare($conexion, $queryAbrirCurso);
    mysqli_stmt_bind_param($stmtAbrirCurso, "s", $codigocurso);

    if (mysqli_stmt_execute($stmtAbrirCurso)) {
        // Apertura del curso exitosa
        header("Location: listar_cursos.php");
        exit();
    } else {
        // Error al abrir el curso
        header("Location: listar_cursos.php?error=apertura-curso-error");
        exit();
    }
} else {
    // Acceso no autorizado
    header("Location: listar_cursos.php?error=acceso-no-autorizado");
    exit();
}

?>
