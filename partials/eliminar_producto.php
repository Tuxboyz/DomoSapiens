<?php 
    session_start();

    if(!isset($_SESSION['id'])){
        header("Location: index.php");
        exit;
    }
    include_once '../includes/Carrito.php';

    echo'<pre>';
    var_dump($_GET);
    echo'</pre>';

    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id_producto_elim'])) {
        $conn = new Carrito();
        $eliminado = $conn->elim_prod_carrito($_SESSION['id'],$_GET['id_producto_elim']);
        header("Location: ../cart.php");
        exit;
    }

?>