<?php

//leo la sesión
session_start();

//Destruyo la sesión y redirigo al login
session_destroy();

header('Location: menu.php');



?>