<?php 
session_start();
if ($_SERVER["REQUEST_METHOD"] !== "GET" || !isset($_GET['id_product'])) {
    header('Location: index.php');
    exit();
}
include_once 'includes/Search.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="lang" content="es-ES">
    <meta name="author" content="Alvaro Mateo Polit Guartatanga">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Página de venta de productos de domótica.">
    <meta name="keywords" content="palabra clave 1, palabra clave 2, palabra clave 3">
    <link rel="stylesheet" href="styles/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="styles/styles.css">
    <style>
        .product-image {
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            margin-bottom: 1rem;
        }
        .product-info {
            padding: 1rem;
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
        }
        .product-description {
            background-color: #ffc107;
            padding: 1rem;
            margin-top: 1rem;
            margin-bottom: 1rem;
        }
        .btn-cart {
            background-color: #007bff;
            color: white;
            width: 100%;
            margin-bottom: 0.5rem;
        }
        .btn-buy {
            background-color: #ffc107;
            color: black;
            width: 100%;
        }


        .carousel-control-prev,
        .carousel-control-next {
            color: #000; /* Black color for text */
            background-color: #000; /* Black background color */
        }
        .carousel-control-prev .carousel-control-prev-icon,
        .carousel-control-next .carousel-control-next-icon {
            fill: #000; /* Black color for the icons */
        }
        .carousel-item{
            max-height: 375px;
            max-width: 630px;
        }
        .imagen{
            max-height: 375px;
            max-width: 630px;
        }
        @media (max-width: 768px) { /* Punto de corte para dispositivos móviles */
        .carousel-item {
            max-height: 330px; /* Ajustar altura para móviles */
            max-width: 310px; /* Ajustar ancho para móviles */
        }
        .imagen{
            max-height: 330px;
            max-width: 310px;
        }
        }
    </style>
    <title>Bienvenido!</title>
</head>
<body>
    <header class="py-3 mb-3 border-bottom">
        <?php include_once 'partials/header.php';?>
    </header>

    <main class="container-fluid">
        <div class="container">
            <?php
                if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id_product'])) {
                    $id = htmlspecialchars($_GET['id_product']);
                    $search = new Search();
                    $product_info = $search->product_inf($id);
                    ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="product-image">
                                    
                                    <div id="carouselExample" class="carousel slide">
                                        <div class="carousel-inner">
                                            <?php 
                                                define('PROJECT_ROOT', __DIR__ . '/');     
                                                $con =  new Search();
                                                $test = $con->show_product_photos(2);
                                                echo "$test";
                                            ?>
                                        </div>
                                        <button class="carousel-control-prev text-dark bg-dark" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Previous</span>
                                        </button>
                                        <button class="carousel-control-next text-dark bg-dark" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Next</span>
                                        </button>
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="product-info">
                                    <h5><?php echo $product_info['nombre']; ?></h5>
                                    <p>Stock: <?php echo $product_info['stock'] > 5 ? 'Disponible' : ($product_info['stock'] == 0 ? 'No queda stock' : 'Quedan ' . $product_info['stock']); ?></p>
                                    <p>Precio sin iva: <?php echo number_format($product_info['precio'],2); ?>€</p>
                                    <p>IVA: <?php $iva = $product_info['precio'] * $product_info['iva'] / 100;echo number_format($iva,2); ?>€</p>
                                    <p>Precio total: <?php $precio_con_iva = $product_info['precio'] + ($product_info['precio'] * $product_info['iva'] / 100);echo number_format($precio_con_iva,2); ?>€</p>
                                    
                                    <!-- Formulario para añadir al carrito -->
                                    <form action="partials/temp.php" method="post">
                                        <input type="hidden" name="accion" value="add">
                                        <input type="hidden" name="id_producto" value="<?php echo $id; ?>">
                                        <button type="submit" class="btn btn-cart">Añadir al carrito</button>
                                    </form>

                                    <!-- Formulario para comprar -->
                                    <form action="partials/temp.php" method="post">
                                        <input type="hidden" name="accion" value="buy">
                                        <input type="hidden" name="id_producto" value="<?php echo $id; ?>">
                                        <button type="submit" class="btn btn-buy">Comprar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="product-description">
                                    <p><?php echo $product_info['descripcion']; ?></p>
                                </div>
                            </div>
                        </div>
                    <?php  
                }
            ?>
        </div>
    </main>

    <footer class="footer mt-auto">
        <?php include_once 'partials/footer.php'; ?>
    </footer>

    <script src="scripts/bootstrap.bundle.min.js"></script>
</body>
</html>