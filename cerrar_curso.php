<?php
require_once 'menu.php';
require_once 'conecta.php';

$rol = isset($_SESSION['tipoUsuario']) ? $_SESSION['tipoUsuario'] : '';

if ((!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true  && $rol !== 'administrador')) {
    // Si no está logado y no es admin
    header('Location: menu.php');
    exit(); 
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener el código del curso desde el formulario
    $codigocurso = isset($_POST['codigocurso']) ? $_POST['codigocurso'] : '';

    // Lógica para cerrar el curso
    $queryCerrarCurso = "UPDATE cursos SET abierto = 0 WHERE codigo = ?";
    $stmtCerrarCurso = mysqli_prepare($conexion, $queryCerrarCurso);
    mysqli_stmt_bind_param($stmtCerrarCurso, "s", $codigocurso);

    if (mysqli_stmt_execute($stmtCerrarCurso)) {
        
        // Obtener el número de plazas disponibles del curso cerrado
        $sqlPlazasDisponibles = "SELECT numeroplazas FROM cursos WHERE codigo = ?";
        $stmtPlazasDisponibles = mysqli_prepare($conexion, $sqlPlazasDisponibles);
        mysqli_stmt_bind_param($stmtPlazasDisponibles, "s", $codigocurso);
        mysqli_stmt_execute($stmtPlazasDisponibles);
        $resultPlazasDisponibles = mysqli_stmt_get_result($stmtPlazasDisponibles);
        $rowPlazasDisponibles = mysqli_fetch_assoc($resultPlazasDisponibles);
        $plazasDisponibles = $rowPlazasDisponibles['numeroplazas'];

        // Obtener lista de solicitudes pendientes (no admitidas) de cursos cerrados pasando el código del curso en el botón
        $sqlSolicitudes = "SELECT DISTINCT s.*, c.nombre as nombre_curso, c.numeroplazas,
                           r.puntos - COALESCE((SELECT COUNT(*) FROM solicitudes s2 WHERE s2.dni = s.dni AND s2.admitido = 1), 0) as prioridad,
                           sol.nombre as nombre_solicitante,
                           sol.apellidos as apellidos_solicitante
                           FROM solicitudes s
                           INNER JOIN cursos c ON s.codigocurso = c.codigo
                           LEFT JOIN resultados r ON s.dni = r.dni
                           INNER JOIN solicitantes sol ON s.dni = sol.dni
                           WHERE s.admitido = 0 AND c.abierto = 0 AND s.codigocurso = ?
                           ORDER BY prioridad DESC";

        
        $stmtSolicitudes = mysqli_prepare($conexion, $sqlSolicitudes);
        mysqli_stmt_bind_param($stmtSolicitudes, "s", $codigocurso);
        mysqli_stmt_execute($stmtSolicitudes);
        $resultSolicitudes = mysqli_stmt_get_result($stmtSolicitudes);

        // Almacenar las solicitudes en un array
        $solicitudesArray = array();
        while ($row = mysqli_fetch_assoc($resultSolicitudes)) {
            $solicitudesArray[] = $row;
        }

        // Arrays para almacenar solicitudes admitidas y no admitidas
        $solicitudesAdmitidas = array();
        $solicitudesNoAdmitidas = array();

        // Lógica para asignar cursos a solicitantes en función de su prioridad y plazas disponibles
        foreach ($solicitudesArray as $solicitud) {
            $dniSolicitante = $solicitud['dni'];
            $codigoCurso = $solicitud['codigocurso'];

            // Verificar si hay plazas disponibles
            if ($plazasDisponibles > 0) {
                // Ejemplo de lógica: asignar automáticamente y actualizar la base de datos
                $sqlAsignacion = "UPDATE solicitudes SET admitido = 1 WHERE dni = ? AND codigocurso = ? AND admitido = 0 LIMIT 1";
                $stmtAsignacion = mysqli_prepare($conexion, $sqlAsignacion);
                mysqli_stmt_bind_param($stmtAsignacion, "ss", $dniSolicitante, $codigoCurso);
                
                if (mysqli_stmt_execute($stmtAsignacion)) {
                    // Actualizar el número de plazas disponibles en el curso
                    $plazasDisponibles--;
                    $sqlActualizarPlazas = "UPDATE cursos SET numeroplazas = ? WHERE codigo = ?";
                    $stmtActualizarPlazas = mysqli_prepare($conexion, $sqlActualizarPlazas);
                    mysqli_stmt_bind_param($stmtActualizarPlazas, "ss", $plazasDisponibles, $codigoCurso);
                    mysqli_stmt_execute($stmtActualizarPlazas);

                    // Agregar a la lista de solicitudes admitidas
                    $solicitudesAdmitidas[] = $solicitud;
                } else {
                    // Agregar a la lista de solicitudes no admitidas
                    $solicitudesNoAdmitidas[] = $solicitud;
                }
            } else {
                // Agregar a la lista de solicitudes no admitidas
                $solicitudesNoAdmitidas[] = $solicitud;
            }
        }

        // Imprimir lista de solicitudes admitidas
        echo "<h2>Solicitudes Admitidas:</h2>";
        echo "<ul>";
        foreach ($solicitudesAdmitidas as $solicitudAdmitida) {
            echo "<li>{$solicitudAdmitida['nombre_solicitante']} {$solicitudAdmitida['apellidos_solicitante']} - {$solicitudAdmitida['nombre_curso']}</li>";
        }
        echo "</ul>";

        // Imprimir lista de solicitudes no admitidas
        echo "<h2>Solicitudes No Admitidas:</h2>";
        echo "<ul>";
        foreach ($solicitudesNoAdmitidas as $solicitudNoAdmitida) {
            echo "<li>{$solicitudNoAdmitida['nombre_solicitante']} {$solicitudNoAdmitida['apellidos_solicitante']} - {$solicitudNoAdmitida['nombre_curso']}</li>";
        }
        echo "</ul>";

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

// Cerrar la conexión a la base de datos
require_once 'desconecta.php';
?>
