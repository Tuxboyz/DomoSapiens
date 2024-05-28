<?php
include_once 'Admin.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_producto_elim'])) {

    $id = barrer($_POST['id_producto_elim']);


    if(empty($id)){
        $errores[] = 'Ha ocurrido un error al borrar (ID no existe).';
    }
    if (!empty($errores)) {
        echo '<p style="color: red">';
        foreach ($errores as $e){
            echo $e;
        }
        echo "</p>";
    } else {

        $conn = new Admin();
        $test = $conn->elim_product($id);
        header("Location: panel.php");
        exit;
    }

}

?>