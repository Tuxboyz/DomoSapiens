<?php
    // Iniciar la sesión
    session_start();

    // Borrar todos los datos de la sesión
    $_SESSION = array();



    // Destruir la sesión
    session_destroy();

    // Redirigir a la página de login
    header("Location: index.php");
    exit;
?>