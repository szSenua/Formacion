<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title></title>
</head>
<body>

<?php

//Función para obtener el tipo de usuario

function obtenerTipoUsuario($dni) {
    // Conectar a la base de datos 
    require_once 'conecta.php';

    // Consultar el tipo de usuario en la tabla administradores
    $queryAdmin = "SELECT dni FROM administradores WHERE dni='$dni'";
    $resultAdmin = $conexion->query($queryAdmin);

    // Si es un administrador, devolver 'administrador'
    if ($resultAdmin->num_rows > 0) {
        return 'administrador';
    }

    // Consultar el tipo de usuario en la tabla solicitantes
    $querySolicitante = "SELECT dni FROM solicitantes WHERE dni='$dni'";
    $resultSolicitante = $conexion->query($querySolicitante);

    // Si es un solicitante, devolver 'solicitante'
    if ($resultSolicitante->num_rows > 0) {
        return 'solicitante';
    }

    // Si no se encuentra en ninguna tabla, devolver null o un valor predeterminado
    return null;

    // Cerrar conexión
   require_once 'desconecta.php';
}


function pintaRegistroSolicitanteConParam($dni, $apellidos, $nombre, $contrasena, $telefono, $correo, $codigocentro, $coordinadortic, $grupotic, $nombregrupo, $pbilin, $cargo, 
$nombrecargo, $situacion, $especialidad, $errores) {
    echo '<div class="registro"><form action="registro.php" method="post" class="form">';
    
    // Mostrar errores solo si la variable $errores no está vacía
    if (!empty($errores)) {
        echo '<div class="alert alert-danger" role="alert">
            <ul>';
        foreach ($errores as $error) {
            echo '<li>' . $error . '</li>';
        }
        echo '</ul></div>';
    }

    echo '
        <h2>Registro de Solicitante</h2>
        
            <label for="dni">DNI:</label>
            <input type="text" name="dni" value="' . htmlspecialchars($dni) . '" >

            <label for="apellidos">Apellidos:</label>
            <input type="text" name="apellidos" value="' . htmlspecialchars($apellidos) . '" >

            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" value="' . htmlspecialchars($nombre) . '" >

            <label for="contrasena">Contraseña:</label>
            <input type="password" name="contrasena" value="' . htmlspecialchars($contrasena) . '" >

            <label for="telefono">Teléfono:</label>
            <input type="text" name="telefono" value="' . htmlspecialchars($telefono) . '" >

            <label for="correo">Correo:</label>
            <input type="email" name="correo" value="' . htmlspecialchars($correo) . '" >

            <label for="codigocentro">Código Centro:</label>
            <input type="text" name="codigocentro" value="' . htmlspecialchars($codigocentro) . '" >

            <label for="coordinadortic">Coordinador TC:</label>
            <select name="coordinadortic" >
                <option value="1" ' . ($coordinadortic == 1 ? 'selected' : '') . '>Sí</option>
                <option value="0" ' . ($coordinadortic == 0 ? 'selected' : '') . '>No</option>
            </select>

        

        
            
            <label for="coordinadortic">Coordinador TC:</label>
            <select name="coordinadortic" >
                <option value="1" ' . ($coordinadortic == 1 ? 'selected' : '') . '>Sí</option>
                <option value="0" ' . ($coordinadortic == 0 ? 'selected' : '') . '>No</option>
            </select>

            <label for="grupotc">Grupo TC:</label>
            <select name="grupotic" >
                <option value="1" ' . ($grupotic == 1 ? 'selected' : '') . '>Sí</option>
                <option value="0" ' . ($grupotic == 0 ? 'selected' : '') . '>No</option>
            </select>

            <label for="nombregrupo">Nombre Grupo:</label>
            <input type="text" name="nombregrupo" value="' . htmlspecialchars($nombregrupo) . '" >

            <label for="pbilin">Plaza Bilingüe:</label>
            <select name="pbilin" >
                <option value="1" ' . ($pbilin == 1 ? 'selected' : '') . '>Sí</option>
                <option value="0" ' . ($pbilin == 0 ? 'selected' : '') . '>No</option>
            </select>

            <label for="cargo">Cargo:</label>
            <select name="cargo" >
                <option value="1" ' . ($cargo == 1 ? 'selected' : '') . '>Sí</option>
                <option value="0" ' . ($cargo == 0 ? 'selected' : '') . '>No</option>
            </select>

            <label for="nombrecargo">Nombre Cargo:</label>
            <input type="text" name="nombrecargo" value="' . htmlspecialchars($nombrecargo) . '" >

            <label for="situacion">Situación:</label>
            <select name="situacion" >
                <option value="1" ' . ($situacion == 1 ? 'selected' : '') . '>Activo</option>
                <option value="0" ' . ($situacion == 0 ? 'selected' : '') . '>Inactivo</option>
            </select>

            <label for="especialidad">Especialidad:</label>
            <input type="text" name="especialidad" value="' . htmlspecialchars($especialidad) . '">
        

        
        <input type="submit" value="Registrar" class="submit">
   
    
      </form>
    </div>';
}


//Función para validar un dni español
function validarDNI($dni) {
    $dni = strtoupper($dni);
    $letra = substr($dni, -1);
    $numeros = substr($dni, 0, -1);

    // Verificar que el formato del DNI es correcto
    if (!preg_match('/^[0-9]{8}$/', $numeros) || !preg_match('/^[A-Z]$/', $letra)) {
        return false;
    }

    // Calcular la letra esperada
    $letraEsperada = substr("TRWAGMYFPDXBNJZSQVHLCKE", $numeros % 23, 1);

    // Comparar la letra esperada con la letra del DNI
    return ($letra == $letraEsperada);
}


// Función para validar el formato de un correo electrónico
function validarCorreo($correo) {
    return filter_var($correo, FILTER_VALIDATE_EMAIL) !== false;
}

//Función para validar un teléfono fijo o móvil
function validarTelefono($telefono) {
    // Eliminar cualquier caracter que no sea dígito
    $numero = preg_replace("/[^0-9]/", "", $telefono);

    // Comprobar si el número tiene un formato válido para teléfonos españoles
    if (preg_match("/^(34)?[6789]\d{8}$/", $numero)) {
        return true;
    }

    return false;
}

//Función para validar el código de un centro (CIF)
function validarCodigoCentro($codigo) {
    // Eliminar cualquier caracter que no sea alfanumérico
    $codigoLimpio = preg_replace("/[^a-zA-Z0-9]/", "", $codigo);

    // Comprobar si el código tiene un formato válido para centros o CIF en España
    if (preg_match("/^[a-zA-Z0-9]{9,12}$/", $codigoLimpio)) {
        return true;
    }

    return false;
}


function pintaLoginconParam($dni, $contrasena, $errores) {
    echo '<div class="login"><form action="login.php" method="post" class="form">';
    
    // Mostrar errores solo si la variable $errores no está vacía
    if (!empty($errores)) {
        echo '<div class="alert alert-danger" role="alert">
            <ul>';
        foreach ($errores as $error) {
            echo '<li>' . $error . '</li>';
        }
        echo '</ul></div>';
    }

    echo '
        <h2>Bienvenid@ al login</h2>
        <input type="text" name="dni" value="' . htmlspecialchars($dni) . '" placeholder="dni">
        <input type="password" name="contrasena" value="' . htmlspecialchars($contrasena) . '" placeholder="Contraseña">
        <input type="submit" value="Enviar" class="submit">
        <a href="registro.php">Registrarse</a>
      </form>
    </div>';
}



?>
</body>
</html>