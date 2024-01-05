<?php

// Crea la base de datos y las tablas, incorpora la conexión ya en el script y la desconexión
require_once 'creaBaseDeDatos.php';

// Incluye el fichero con las funciones
require_once 'funciones.php';

$dni = '';
$apellidos = ''; 
$nombre = '';
$contrasena = '';
$telefono = '';
$correo = '';
$codigocentro = ''; 
$coordinadortic = '';
$grupotic = '';
$nombregrupo = '';
$pbilin = '';
$cargo = '';
$nombrecargo = ''; 
$situacion = '';
$fechaAlta = (new DateTime())->format('Y-m-d');
$especialidad = '';
$errores = array();

// Comprueba si han enviado el formulario o es la primera vez
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    // Si no, muestra el formulario de registro
    pintaRegistroSolicitanteConParam($dni, $apellidos, $nombre, $contrasena, $telefono, $correo, $codigocentro, $coordinadortic, $grupotic, $nombregrupo, $pbilin, $cargo, 
    $nombrecargo, $situacion, $especialidad, $errores);
} else {
    // Si sí, comienza las validaciones

    // Comprueba que los campos no estén vacíos

    //Valido dni
    if (!empty($_POST['dni'])) {
        $dni = $_POST['dni'];
        // Validar el formato del DNI
        if (!validarDNI($dni)) {
            $errores[] = 'El formato del DNI no es válido.';
        }
    } else {
        $errores[] = 'El campo DNI no puede estar vacío.';
    }

    //Valido apellidos

    if (!empty($_POST['apellidos'])) {
        $apellidos = $_POST['apellidos'];
    } else {
        $errores[] = 'El campo apellidos no puede estar vacío.';
    }

    //Valido nombre

    if (!empty($_POST['nombre'])) {
        $nombre = $_POST['nombre'];
    } else {
        $errores[] = 'El campo nombre no puede estar vacío.';
    }
    
    //Valido contraseña
    
    if (!empty($_POST['contrasena'])) {
        $contrasena = $_POST['contrasena'];
    } else {
        $errores[] = 'El campo contraseña no puede estar vacío.';
    }

    //Valido teléfono

    if (!empty($_POST['telefono'])) {
        $telefono = $_POST['telefono'];
        // Validar el formato del DNI
        if (!validarTelefono($telefono)) {
            $errores[] = 'El formato del teléfono no es válido.';
        }
    } else {
        $errores[] = 'El campo teléfono no puede estar vacío.';
    }

    //Valido correo

    if (!empty($_POST['correo'])) {
        $correo = $_POST['correo'];
        // Validar el formato del correo electrónico
        if (!validarCorreo($correo)) {
            $errores[] = 'El formato del correo electrónico no es válido.';
        }
    } else {
        $errores[] = 'El campo correo electrónico no puede estar vacío.';
    }

    //Valido el código del centro

    if (!empty($_POST['codigocentro'])) {
        $codigocentro = $_POST['codigocentro'];
        // Validar el formato del código del centro
        if (!validarCodigoCentro($codigocentro)) {
            $errores[] = 'El formato del Código del centro no es válido.';
        }
    } else {
        $errores[] = 'El campo Código del centro no puede estar vacío.';
    }

    //Valido coordinador tic

    if (!empty($_POST['coordinadortic'])) {
        $coordinadortic = $_POST['coordinadortic'];
    } else {
        $errores[] = 'El campo Coordinadortic no puede estar vacío.';
    }

    //Valido grupo tic

    if (!empty($_POST['grupotic'])) {
        $grupotic = $_POST['grupotic'];
    } else {
        $errores[] = 'El campo grupotic no puede estar vacío.';
    }

    //Valido nombre grupo

    if (!empty($_POST['nombregrupo'])) {
        $nombregrupo = $_POST['nombregrupo'];
    } else {
        $errores[] = 'El campo nombre grupo no puede estar vacío.';
    }

    //Valido plaza bilingüe

    if (!empty($_POST['pbilin'])) {
        $pbilin = $_POST['pbilin'];
    } else {
        $errores[] = 'El campo n plaza bilingüe no puede estar vacío.';
    }

    //Valido cargo

    if (!empty($_POST['cargo'])) {
        $cargo = $_POST['cargo'];
    } else {
        $errores[] = 'El campo cargo no puede estar vacío.';
    }
    
    //Valido nombre cargo

    if (!empty($_POST['nombrecargo'])) {
        $nombrecargo = $_POST['nombrecargo'];
    } else {
        $errores[] = 'El campo nombre cargo no puede estar vacío.';
    }

    //Valido situación

    if (!empty($_POST['situacion'])) {
        $situacion = $_POST['situacion'];
    } else {
        $errores[] = 'El campo situación no puede estar vacío.';
    }


    //Valido especialidad

    if (!empty($_POST['especialidad'])) {
        $especialidad = $_POST['especialidad'];
    } else {
        $errores[] = 'El campo especialidad no puede estar vacío.';
    }


    // Entonces, si los errores no están vacíos, repinta el formulario
    if (count($errores) > 0) {
        pintaRegistroSolicitanteConParam($dni, $apellidos, $nombre, $contrasena, $telefono, $correo, $codigocentro, $coordinadortic, $grupotic, $nombregrupo, $pbilin, $cargo, 
        $nombrecargo, $situacion, $especialidad, $errores);
    } else {
        // Si no hay errores, conecta con la base de datos e inserta el nuevo usuario
        require_once 'conecta.php';

        // Comprueba si el usuario ya existe en la base de datos
        $sql_check_user = "SELECT * FROM solicitantes WHERE dni = '$dni'";
        $result_check_user = mysqli_query($conexion, $sql_check_user);
        $num_filas_check_user = mysqli_num_rows($result_check_user);

        if ($num_filas_check_user > 0) {
            // El usuario ya existe, muestra un error
            $errores[] = 'El nombre de usuario ya está en uso. Por favor, elige otro.';
            pintaRegistroSolicitanteConParam($dni, $apellidos, $nombre, $contrasena, $telefono, $correo, $codigocentro, $coordinadortic, $grupotic, $nombregrupo, $pbilin, $cargo, 
            $nombrecargo, $situacion, $especialidad, $errores);
        } else {
            // Inserta el nuevo usuario en la base de datos
            $sql_insert_applicant = "INSERT INTO solicitantes (dni, apellidos, nombre, contrasena, telefono, correo, codigocentro, coordinadortic, grupotic, nombregrupo, pbilin, cargo,
            nombrecargo, situacion, fechaAlta, especialidad) VALUES ('$dni', '$apellidos', '$nombre', '$contrasena', '$telefono', '$correo', '$codigocentro', '$coordinadortic', '$grupotic',
            '$nombregrupo', '$pbilin', '$cargo', '$nombrecargo', '$situacion', '$fechaAlta', '$especialidad')";
            $result_insert_applicant = mysqli_query($conexion, $sql_insert_applicant);

            require_once 'desconecta.php';

            // Redirige a la página de inicio de sesión o a la página principal
            if ($result_insert_applicant) {
                header('Location: login.php'); // Página de inicio de sesión
            } else {
                // Si hay un error en la inserción, muestra un mensaje de error
                $errores[] = 'Error en el registro. Por favor, inténtalo de nuevo.';
                pintaRegistroSolicitanteConParam($dni, $apellidos, $nombre, $contrasena, $telefono, $correo, $codigocentro, $coordinadortic, $grupotic, $nombregrupo, $pbilin, $cargo, 
                $nombrecargo, $situacion, $especialidad, $errores);
            }
        }
    }
}
?>