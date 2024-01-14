<?php

include_once 'menu.php';


// Errores
$error = isset($_GET['error']) ? $_GET['error'] : '';

// Mostrar el mensaje de error si existe
if ($error == 'curso-no-disponible') {
    echo '<p style="color: red;">Este curso no está disponible actualmente.</p>';
} elseif ($error == 'curso-ya-solicitado') {
    echo '<p style="color: red;">Ya has solicitado este curso anteriormente.</p>';
}

require_once 'conecta.php';

// Consulta para obtener todos los cursos
$sql = "SELECT * FROM cursos ORDER BY nombre";
$result = mysqli_query($conexion, $sql);

// Variable del rol del usuario
$rol = isset($_SESSION['tipoUsuario']) ? $_SESSION['tipoUsuario'] : '';

// Variable del DNI del usuario
$dni = isset($_SESSION['dni']) ? $_SESSION['dni'] : '';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        #cursos-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 2em;
        }

        .card {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 15px;
            width: 300px;
            background-color: #fff;
        }

        .btn-solicitar,
        .btn-cerrado,
        .btn-abrir,
        .btn-ya-solicitado {
            font-weight: bolder;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-solicitar {
            background-color: #4CAF50;
            color: white;
        }

        .btn-cerrado {
            background-color: red;
            color: white;
        }

        .btn-abrir {
            background-color: green;
            color: white;
        }

        .btn-ya-solicitado {
            background-color: gray;
            color: white;
        }
    </style>
    <title>Listar Cursos</title>
</head>

<body>

    <div id="cursos-container">

        <?php

        // Mostrar los cursos en tarjetas
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="card">';
                echo '<h2>' . $row['nombre'] . '</h2>';
                echo '<p>Código: ' . $row['codigo'] . '</p>';
                echo '<p>Plazas Disponibles: ' . $row['numeroplazas'] . '</p>';
                echo '<p>Plazo de Inscripción: ' . $row['plazoinscripcion'] . '</p>';

                // Verificar si el usuario es administrador
                if ($rol === 'administrador') {
                    // Mostrar botón "Cerrar" si el curso está abierto y "Abrir" si está cerrado
                    if ($row['abierto'] == 1) {
                        echo '<button class="btn-cerrado">Cerrar</button>';
                    } else {
                        echo '<button class="btn-abrir">Abrir</button>';
                    }
                } else if ($rol === 'solicitante') {
                    // Verificar si el curso está cerrado o el plazo de inscripción ha expirado
                    if ($row['abierto'] == 0 || strtotime($row['plazoinscripcion']) < strtotime(date('Y-m-d'))) {
                        echo '<button class="btn-cerrado" disabled>Cerrado</button>';
                    } else {
                        // Verificar si el solicitante ya solicitó o fue admitido en este curso
                        $queryVerificar = "SELECT COUNT(*) FROM solicitudes WHERE dni = ? AND codigocurso = ?";
                        $stmtVerificar = mysqli_prepare($conexion, $queryVerificar);
                        mysqli_stmt_bind_param($stmtVerificar, "si", $dni, $row['codigo']);
                        mysqli_stmt_execute($stmtVerificar);
                        mysqli_stmt_bind_result($stmtVerificar, $count);
                        mysqli_stmt_fetch($stmtVerificar);
                        mysqli_stmt_close($stmtVerificar);

                        // Verificar si el solicitante ya fue admitido en este curso
                        $queryAdmitido = "SELECT COUNT(*) FROM solicitudes WHERE dni = ? AND codigocurso = ? AND admitido IS NOT NULL";
                        $stmtAdmitido = mysqli_prepare($conexion, $queryAdmitido);
                        mysqli_stmt_bind_param($stmtAdmitido, "si", $dni, $row['codigo']);
                        mysqli_stmt_execute($stmtAdmitido);
                        mysqli_stmt_bind_result($stmtAdmitido, $countAdmitido);
                        mysqli_stmt_fetch($stmtAdmitido);
                        mysqli_stmt_close($stmtAdmitido);

                        if ($countAdmitido > 0) {
                            // El solicitante ya fue admitido en este curso
                            echo '<button class="btn-ya-solicitado" disabled>Ya solicitado</button>';
                        } else {
                            // Mostrar botón "Solicitar" si no ha solicitado o fue admitido en este curso antes
                            echo '<form action="registrar_solicitud.php" method="post">';
                            echo '<input type="hidden" name="codigocurso" value="' . $row['codigo'] . '">';
                            echo '<button class="btn-solicitar">Solicitar</button>';
                            echo '</form>';
                        }
                    }
                }

                echo '</div>';
            }
        } else {
            echo "No hay cursos disponibles.";
        }

        // Cerrar la conexión
        require_once 'desconecta.php';
        ?>

    </div>

</body>

</html>

