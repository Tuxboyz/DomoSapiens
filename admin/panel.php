<?php
session_start();
if(!isset($_SESSION['nombre_admin'])){
    header("Location: login.php");
    exit;
}
include_once('../includes/Config.php');
include_once('Admin.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="lang" content="es-ES">
    <meta name="author" content="Alvaro Mateo Polit Guartatanga">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Paguina de venta de productos de domotica.">
    <meta name="keywords" content="palabra clave 1, palabra clave 2, palabra clave 3">
    <link rel="stylesheet" href="../styles/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../styles/styles.css">
    <style>

    </style>
    <title>Bienvenido!</title>
</head>

<body>

    <main>
        <?php if ((isset($_SESSION['permisos'])) && ($_SESSION['permisos'] == 1)) { 
            $conn = new Admin();
            $data = $conn->get_data($_SESSION['id']);
        ?>

        <div class='container'>
            <div class="row">
                <div class="col-2 text-center">
                    <i class="bi bi-person-fill" id="icono_data"></i>
                </div>

                <div class="col-8 d-flex align-items-center justify-content-center">
                    <h3>Bienvenido <?php echo $data['nombre_admin']; ?> a tu panel de control.</h3>
                </div>

                <div class="col-2 d-flex align-items-center justify-content-center">
                    <a href="logout.php" ><i class="bi bi-box-arrow-right"></i></a>
                </div>

                <div class="col-12">
                    <nav>
                        <div class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-products-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-products" type="button" role="tab" aria-controls="nav-products"
                                aria-selected="true">Ver Productos
                            </button>

                            <button class="nav-link" id="nav-create-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-create" type="button" role="tab" aria-controls="nav-create"
                                aria-selected="false" tabindex="-1">Introducir Producto
                            </button>
                        </div>
                    </nav>

                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade active show" id="nav-products" role="tabpanel" aria-labelledby="nav-products-tab" tabindex="0">
                            <?php $products = $conn->show_products(); echo $products;?>
                        </div>
                        <div class="tab-pane fade" id="nav-create" role="tabpanel" aria-labelledby="nav-create-tab" tabindex="0">
                            <?php include_once 'nuevo_producto.php';?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <?php } ?>
    </main>

    <script src="../scripts/bootstrap.bundle.min.js"></script>
</body>
</html>