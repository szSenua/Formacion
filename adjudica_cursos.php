<?php
require_once 'menu.php';

$rol = isset($_SESSION['tipoUsuario']) ? $_SESSION['tipoUsuario'] : '';

if ((!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true  && $rol !== 'administrador')) {
    // Si no está logado y no es admin
    header('Location: menu.php');
    exit(); 
}



include('conecta.php'); 


/*
COALESCE((SELECT COUNT(*) FROM solicitudes s2 WHERE s2.dni = s.dni AND s2.admitido = 1), 0) as prioridad
Calcula la prioridad del solicitante restando el número total de solicitudes admitidas del total de puntos.*/

// Obtener lista de solicitudes pendientes (no admitidas) de cursos abiertos
$sqlSolicitudes = "SELECT DISTINCT s.*, c.nombre as nombre_curso, c.numeroplazas,
                          r.puntos - COALESCE((SELECT COUNT(*) FROM solicitudes s2 WHERE s2.dni = s.dni AND s2.admitido = 1), 0) as prioridad,
                          sol.nombre as nombre_solicitante,
                          sol.apellidos as apellidos_solicitante
                   FROM solicitudes s
                   INNER JOIN cursos c ON s.codigocurso = c.codigo
                   LEFT JOIN resultados r ON s.dni = r.dni
                   INNER JOIN solicitantes sol ON s.dni = sol.dni
                   WHERE s.admitido = 0 AND c.abierto = 1
                   ORDER BY prioridad DESC";




$resultSolicitudes = mysqli_query($conexion, $sqlSolicitudes);

// Obtener lista de solicitantes ordenados por puntos y solicitudes admitidas
$sqlSolicitantes = "SELECT s1.*, COUNT(s2.id) AS solicitudes_admitidas
                    FROM solicitantes s1
                    LEFT JOIN solicitudes s2 ON s1.dni = s2.dni AND s2.admitido = 1
                    GROUP BY s1.dni
                    ORDER BY s1.puntos DESC, solicitudes_admitidas ASC";

$resultSolicitantes = mysqli_query($conexion, $sqlSolicitantes);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adjudicación de Cursos</title>
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


        .btn-adjudicar {
            background-color: #357EDD;
            color: white;
        }

        .btn-rechazar {
            background-color: #FF3030;
            color: white;
        }
    </style>
</head>
<body>

    <h1>Listado de Solicitudes Pendientes de Cursos Abiertos</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>DNI Solicitante</th>
            <th>Nombre</th>
            <th>Código Curso</th>
            <th>Nombre Curso</th>
            <th>Plazas Disponibles</th>
            <th>Fecha Solicitud</th>
            <th>Admitido</th>
            <th>Acción</th>
        </tr>
        <?php
        while ($row = mysqli_fetch_assoc($resultSolicitudes)) {
            echo "<tr>";
            echo "<td>{$row['id']}</td>";
            echo "<td>{$row['dni']}</td>";
            echo "<td>{$row['nombre_solicitante']} {$row['apellidos_solicitante']}</td>";
            echo "<td>{$row['codigocurso']}</td>";
            echo "<td>{$row['nombre_curso']}</td>";
            echo "<td>{$row['numeroplazas']}</td>";
            echo "<td>{$row['fechasolicitud']}</td>";
            echo "<td>" . ($row['admitido'] == 1 ? 'Sí' : 'No') . "</td>";
            echo "<td>";
            echo "<form method='post' action='procesa_solicitud.php'>";
            echo "<input type='hidden' name='dni' value='{$row['dni']}'>";
            echo "<input type='hidden' name='codigo_curso' value='{$row['codigocurso']}'>"; 
            echo "<button class='btn-accion btn-adjudicar' type='submit' name='adjudicar'>Adjudicar</button>";
            echo "<button class='btn-accion btn-rechazar' type='submit' name='rechazar'>Rechazar</button>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }
        ?>
    </table>

    <h1>Listado de Solicitantes Ordenados</h1>
    <table>
        <tr>
            <th>DNI</th>
            <th>Apellidos</th>
            <th>Nombre</th>
            <th>Puntos</th>
            <th>Solicitudes Admitidas</th>
        </tr>
        <?php
        while ($row = mysqli_fetch_assoc($resultSolicitantes)) {
            echo "<tr>";
            echo "<td>{$row['dni']}</td>";
            echo "<td>{$row['apellidos']}</td>";
            echo "<td>{$row['nombre']}</td>";
            echo "<td>{$row['puntos']}</td>";
            echo "<td>{$row['solicitudes_admitidas']}</td>";
            echo "</tr>";
        }
        ?>
    </table>

</body>
</html>

<?php
// Cerrar la conexión
require_once 'desconecta.php';
?>
