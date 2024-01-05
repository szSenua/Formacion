

    <?php
require_once 'conecta.php';
require_once 'funciones.php';

$dni = '';
$contrasena = '';
$errores = array();


    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        //Si es que no, entonces muestro el formulario
        pintaLoginconParam($dni, $contrasena, $errores);
    } else {


    if (isset($_POST['submit'])) {
        // Verificar si el usuario y contraseña son válidos
        $dni = $_POST['dni'];
        $contrasena = $_POST['contrasena'];

        // Conectar a la base de datos 
        

        
        $tipoUsuario = obtenerTipoUsuario($dni); 

        //Cerrar base de datos
        require_once 'desconecta.php';

        // Tanto si el usuario es administrador como usuario se redirigirán al menú.
        if ($tipoUsuario === 'administrador' || $tipoUsuario === 'usuario') {
            session_start();
            $_SESSION['dni'] = $dni;
            $_SESSION['tipoUsuario'] = $tipoUsuario;
            $_SESSION['logged_in'] = true;

            header("Location: menu.php");
            exit();


        } else {
            pintaLoginconParam($dni, $contrasena, $errores);
        }
    }
       
    }
    ?>

