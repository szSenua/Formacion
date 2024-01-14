<?php
require_once 'menu.php';
$rol = isset($_SESSION['tipoUsuario']) ? $_SESSION['tipoUsuario'] : '';

if ((!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true  && $rol !== 'administrador')) {
    // Si no está logado y no es admin
    header('Location: menu.php');
    exit(); 
}

include_once 'conecta.php';

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

    //var_dump($_POST); 
    // Recoger los datos del formulario
    $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
    $numeroplazas = isset($_POST['numeroplazas']) ? $_POST['numeroplazas'] : '';

    // Convertir la fecha del formato HTML al formato MySQL
 $fechaPlazoInscripcion = isset($_POST['plazoinscripcion']) ? $_POST['plazoinscripcion'] : '';
$dateTime = new DateTime($fechaPlazoInscripcion);
$plazoinscripcion = $dateTime->format('Y-m-d');

    //var_dump($plazoinscripcion);

    $abierto = isset($_POST['estadoCurso']) ? ($_POST['estadoCurso'] == '1' ? 1 : 0) : 0;

    // Insertar los datos en la base de datos
    $sql = "INSERT INTO cursos (nombre, abierto, numeroplazas, plazoinscripcion) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "siss", $nombre, $abierto, $numeroplazas, $plazoinscripcion);

    //echo "Consulta SQL antes de ejecutarla: " . vsprintf(str_replace('?', "'%s'", $sql), array($nombre, $abierto, $numeroplazas, $plazoinscripcion)) . "<br>";
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: panel_administracion.php");
    } else {
        echo '<p style="color: red;">Error al crear el curso.</p>';
        echo '<p style="color: red;">'.mysqli_error($conexion).'</p>';
    }

    mysqli_stmt_close($stmt);
}

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
    <title>Crear Curso</title>
</head>

<body>
    <div class="container">
        <h2>Crear Nuevo Curso</h2>
        <form action="crear_curso.php" method="post">

            <label for="nombre">Nombre del Curso:</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="numeroplazas">Número de Plazas:</label>
            <input type="number" id="numeroplazas" name="numeroplazas" required>

            <label for="plazoinscripcion">Plazo de Inscripción:</label>
            <input type="date" id="plazoinscripcion" name="plazoinscripcion" required>

            <label for="estadoCurso">Estado del Curso:</label>
            <select id="estadoCurso" name="estadoCurso">
                <option value="1">Abierto</option>
                <option value="0">Cerrado</option>
            </select>

            <button type="submit" name="submit">Crear Curso</button>
        </form>
    </div>
</body>

</html>
