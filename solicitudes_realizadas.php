<?php
require_once 'menu.php';
$rol = isset($_SESSION['tipoUsuario']) ? $_SESSION['tipoUsuario'] : '';


// Verifica si el usuario está logueado y no es un administrador
if ((!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true  && $rol !== 'solicitante')) {
    // Si no está logado y no es solicitante
    header('Location: menu.php');
    exit();
}


include('conecta.php');


// Obtener DNI del usuario logueado
$dniUsuario = isset($_SESSION['dni']) ? $_SESSION['dni'] : '';

// Obtener solicitudes realizadas por el usuario logueado
$sqlSolicitudesRealizadas = "SELECT s.*, c.nombre as nombre_curso, c.numeroplazas
                              FROM solicitudes s
                              INNER JOIN cursos c ON s.codigocurso = c.codigo
                              WHERE s.dni = '$dniUsuario'
                              ORDER BY s.fechasolicitud DESC";

$resultSolicitudesRealizadas = mysqli_query($conexion, $sqlSolicitudesRealizadas);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitudes Realizadas</title>
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
    </style>
</head>
<body>

    <h1>Solicitudes Realizadas</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Código Curso</th>
            <th>Nombre Curso</th>
            <th>Plazas Disponibles</th>
            <th>Fecha Solicitud</th>
            <th>Admitido</th>
        </tr>
        <?php
        while ($row = mysqli_fetch_assoc($resultSolicitudesRealizadas)) {
            echo "<tr>";
            echo "<td>{$row['id']}</td>";
            echo "<td>{$row['codigocurso']}</td>";
            echo "<td>{$row['nombre_curso']}</td>";
            echo "<td>" . (isset($row['numeroplazas']) ? $row['numeroplazas'] : 'N/A') . "</td>";
            echo "<td>{$row['fechasolicitud']}</td>";
            echo "<td>" . ($row['admitido'] == 1 ? 'Sí' : 'No') . "</td>";
            echo "</tr>";
        }
        ?>
    </table>

</body>
</html>

<?php
// Cerrar la conexión a la base de datos al finalizar
require_once 'desconecta.php';
?>
