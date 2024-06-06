<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    session_start();
    require_once '../includes/Carrito.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['accion']) && isset($_POST['id_producto']) && isset($_SESSION['id'])) {
            $accion = $_POST['accion'];
            $id_producto = $_POST['id_producto'];
            $id_usuario = $_SESSION['id'];
            $carrito = new Carrito();

            if ($accion == 'add') {
                $carrito->agregarAlCarrito($id_usuario, $id_producto);
                // Redirige a la página anterior
                $previousPage = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '../index.php';
                header("Location: $previousPage");
                exit();
            } elseif ($accion == 'buy') {
                $carrito->agregarAlCarrito($id_usuario, $id_producto);
                header('Location: ../buy.php');
                exit();
            }
        } else {
            header('Location: ../index.php');
            exit();
        }
    } else {
        header('Location: ../index.php');
        exit();
    }
?>

