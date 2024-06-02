<?php
    session_start();
    require_once '../includes/Carrito.php';

    echo "<pre> estoy en la funcion de temp.php";
    var_dump($_POST);
    echo "</pre>";

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
            echo "Estoy dentro del segundo IF";
            header('Location: ../index.php');
            exit();
        }
    } else {
        echo "estoy dentro del primer IF";
        header('Location: ../index.php');
        exit();
    }
?>

