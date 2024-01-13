<?php
include_once 'conecta.php';

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['codigo'])) {
    // Obtener el código del curso a eliminar
    $codigoCurso = $_POST['codigo'];

    // Eliminar el curso de la base de datos
    $sql = "DELETE FROM cursos WHERE codigo = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "s", $codigoCurso);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: panel_administracion.php");
    } else {
        echo '<p style="color: red;">Error al eliminar el curso.</p>';
    }

    mysqli_stmt_close($stmt);
}

// Cerrar la conexión
require_once 'desconecta.php';
?>
