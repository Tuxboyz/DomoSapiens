<?php 
    session_start();
    if ($_SERVER["REQUEST_METHOD"] !== "GET" || !isset($_GET['id_product'])) {
        header('Location: index.php');
        exit();
    } 
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
                background-color: #343a40;
                width: 100%;
                height: 300px;
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
        </style>
        <title>Bienvenido!</title>
    </head>
    <body>
        <header class="py-3 mb-3 border-bottom">
            <?php include_once 'partials/header.php'; include_once 'includes/Search.php'; ?>
        </header>

        <main class="container-fluid">
            <?php
                if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id_product'])) {
                    $id = htmlspecialchars($_GET['id_product']);
                    $search = new Search();
                    $product_info = $search->product_inf($id);
                    ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="product-image">
                                    <p><?php echo htmlspecialchars($product_info['imagen_ruta']); ?></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="product-info">
                                    <h5><?php echo htmlspecialchars($product_info['nombre']); ?></h5>
                                    <p>Stock: <?php echo $product_info['stock'] > 5 ? 'Disponible' : ($product_info['stock'] == 0 ? 'No queda stock' : 'Quedan ' . htmlspecialchars($product_info['stock'])); ?></p>
                                    <p>Precio: <?php echo htmlspecialchars($product_info['precio']); ?>€</p>
                                    <p>IVA: <?php echo htmlspecialchars($product_info['iva']); ?>€</p>
                                    <p>Precio total: <?php echo htmlspecialchars($product_info['precio'] + $product_info['iva']); ?>€</p>
                                    <button class="btn btn-cart">Añadir al carrito</button>
                                    <button class="btn btn-buy">Comprar</button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="product-description">
                                    <p><?php echo htmlspecialchars($product_info['descripcion']); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php  
                }
            ?>
        </main>

        <footer class="footer mt-auto">
            <?php include_once 'partials/footer.php'; ?>
        </footer>

        <script src="scripts/bootstrap.bundle.min.js"></script>
    </body>
</html>
