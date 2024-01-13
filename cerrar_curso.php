<?php

require_once 'conecta.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener el código del curso desde el formulario
    $codigocurso = isset($_POST['codigocurso']) ? $_POST['codigocurso'] : '';

    // Actualizar el estado del curso a cerrado
    $queryCerrarCurso = "UPDATE cursos SET abierto = 0 WHERE codigo = ?";
    $stmtCerrarCurso = mysqli_prepare($conexion, $queryCerrarCurso);
    mysqli_stmt_bind_param($stmtCerrarCurso, "s", $codigocurso);
    
    if (mysqli_stmt_execute($stmtCerrarCurso)) {
        // Cierre del curso exitoso
        header("Location: listar_cursos.php");
        exit();
    } else {
        // Error al cerrar el curso
        header("Location: listar_cursos.php?error=cierre-curso-error");
        exit();
    }
} else {
    // Acceso no autorizado
    header("Location: listar_cursos.php?error=acceso-no-autorizado");
    exit();
}

?>
