<?php
require_once 'menu.php';
include_once 'conecta.php';

// Obtener el código del curso a actualizar
$codigoCurso = isset($_GET['codigo']) ? $_GET['codigo'] : '';

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Recoger los datos del formulario
    $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
    $numeroplazas = isset($_POST['numeroplazas']) ? $_POST['numeroplazas'] : '';
    $plazoinscripcion = isset($_POST['plazoinscripcion']) ? date('Y-m-d', strtotime($_POST['plazoinscripcion'])) : '';
    $abierto = isset($_POST['abierto']) ? 1 : 0; // 1 si está marcado, 0 si no

    // Actualizar los datos en la base de datos
    $sql = "UPDATE cursos SET nombre = ?, numeroplazas = ?, plazoinscripcion = ?, abierto = ? WHERE codigo = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "sisii", $nombre, $numeroplazas, $plazoinscripcion, $abierto, $codigoCurso);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: actualizar_curso.php");
    } else {
        echo '<p style="color: red;">Error al actualizar el curso.</p>';
    }

    mysqli_stmt_close($stmt);
}

// Obtener los datos actuales del curso
$sqlSelect = "SELECT * FROM cursos WHERE codigo = ?";
$stmtSelect = mysqli_prepare($conexion, $sqlSelect);
mysqli_stmt_bind_param($stmtSelect, "i", $codigoCurso);
mysqli_stmt_execute($stmtSelect);
$resultado = mysqli_stmt_get_result($stmtSelect);
$curso = mysqli_fetch_assoc($resultado);

// Cerrar la conexión
require_once 'desconecta.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input,
        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
    <title>Actualizar Curso</title>
</head>

<body>
    <div class="container">
        <h2>Actualizar Curso</h2>
        <form action="actualizar_curso.php?codigo=<?php echo $codigoCurso; ?>" method="post">

            <label for="nombre">Nombre del Curso:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo isset($curso['nombre']) ? $curso['nombre'] : ''; ?>" required>

            <label for="numeroplazas">Número de Plazas:</label>
            <input type="number" id="numeroplazas" name="numeroplazas" value="<?php echo isset($curso['numeroplazas']) ? $curso['numeroplazas'] : ''; ?>" required>

            <label for="plazoinscripcion">Plazo de Inscripción:</label>
            <input type="date" id="plazoinscripcion" name="plazoinscripcion" value="<?php echo isset($curso['plazoinscripcion']) ? $curso['plazoinscripcion'] : ''; ?>" required>

            <label for="abierto">Estado del Curso:</label>
            <select id="abierto" name="abierto">
                <option value="1" <?php echo isset($curso['abierto']) && $curso['abierto'] == 1 ? 'selected' : ''; ?>>Abierto</option>
                <option value="0" <?php echo isset($curso['abierto']) && $curso['abierto'] == 0 ? 'selected' : ''; ?>>Cerrado</option>
            </select>

            <button type="submit" name="submit">Actualizar Curso</button>
        </form>
    </div>
</body>

</html>
