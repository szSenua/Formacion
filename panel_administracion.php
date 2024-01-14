<?php
include_once 'menu.php';

$rol = isset($_SESSION['tipoUsuario']) ? $_SESSION['tipoUsuario'] : '';

//var_dump($rol);

// Verifica si el usuario está logueado y no es un administrador
if ((!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true  && $rol !== 'administrador')) {
    // Si no está logado y no es administrador
    header('Location: menu.php');
    exit();
}


require_once 'conecta.php';




// Función para obtener la lista de cursos desde la base de datos
function obtenerCursos($conexion){
    $sql = "SELECT * FROM cursos ORDER BY codigo";
    $result = mysqli_query($conexion, $sql);

    $cursos = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $cursos[] = $row;
    }

    return $cursos;
}

// Obtener la lista de cursos
$cursos = obtenerCursos($conexion);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .btn-accion {
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-crear {
            background-color: #4CAF50;
            color: white;
        }

        .btn-actualizar {
            background-color: #357EDD;
            color: white;
        }

        .btn-eliminar {
            background-color: #FF3030;
            color: white;
        }
    </style>
    <title>Panel de Administración</title>
</head>

<body>

    <h2>Panel de Administración - Cursos</h2>

    <!-- Botón para crear un nuevo curso -->
    <form action="crear_curso.php" method="post">
        <a href="crear_curso.php"><button class="btn-accion btn-crear">Crear Nuevo Curso</button></a>
    </form>

    <!-- Tabla de Cursos -->
    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Número de Plazas</th>
                <th>Plazo de Inscripción</th>
                <th>Abierto</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cursos as $curso) : ?>
                <tr>
                    <td><?= $curso['codigo']; ?></td>
                    <td><?= $curso['nombre']; ?></td>
                    <td><?= $curso['numeroplazas']; ?></td>
                    <td><?= $curso['plazoinscripcion']; ?></td>
                    <td><?= $curso['abierto'] ? 'Sí' : 'No'; ?></td>
                    <td>
                        <!-- Botones de acciones (actualizar, eliminar) -->
                        <form action="actualizar_curso.php?codigo=<?php echo $curso['codigo']; ?>" method="post" style="display: inline;">
                            <input type="hidden" name="codigo" value="<?= $curso['codigo']; ?>">
                            <button type="submit" class="btn-accion btn-actualizar">Actualizar</button>
                        </form>
                        <form action="eliminar_curso.php" method="post" onsubmit="return confirm('¿Estás seguro de que deseas borrar este curso?');" style="display: inline;">
                            <input type="hidden" name="codigo" value="<?= $curso['codigo']; ?>">
                            <button type="submit" class="btn-accion btn-eliminar">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php
    // Cerrar la conexión
    require_once 'desconecta.php';
    ?>

</body>

</html>
