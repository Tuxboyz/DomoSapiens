<?php 
    session_start();
    if(!isset($_SESSION['nombre'])){
        header("Location: login.php");
        exit;
    } else {
        include_once 'includes/Usuario.php';
        include_once 'includes/Carrito.php';
        $conn = new Usuario();
        $data = $conn->get_data($_SESSION['id']);
        $conn2 = new Carrito();
    }
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
    <link rel="stylesheet" href="styles/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="styles/styles.css">
    <style>

    </style>

    <title>Bienvenido!</title>
</head>
<body>
    <header class="py-3 mb-3 border-bottom">
        <?php include_once 'partials/header.php';?>
    </header>

    <main>
        <div class='container'>
            <div class="row">
                <div class="col-3 text-center">
                    <i class="bi bi-cart-fill" id="icono"></i>
                </div>

                <div class="col-9 d-flex align-items-center justify-content-center">
                    <h3>Bienvenido/a <?php echo $data['nombre']?> a tu Carrito de la compra.</h3>
                </div>
                
                <div class="col-12">
                    <div class="container my-5">
                        <div class="bg-body-tertiary p-5 rounded">
                            <div class="col-sm-8 py-5 mx-auto text-center">
                                    <?php 
                                        echo'<form action="buy.php" method="post" id="comprar">';
                                        $items = $conn2->mostrarCarrito($_SESSION['id']);
                                        echo'</form>';
                                    ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-check text-center my-5">
                        ¿Tienes algún problema? Podemos ayudarte <a href="faq.php">aquí</a>!
                    </div>
                </div>

            </div>
        </div>
    </main>

    <footer class="footer mt-auto">
        <?php include_once 'partials/footer.php';?>
    </footer>

    <script>
        function incrementQuantity(productId) {
            var input = document.querySelector(`input[name="cantidades[${productId}]"]`);
            input.value = parseInt(input.value) + 1;
        }

        function decrementQuantity(productId) {
            var input = document.querySelector(`input[name="cantidades[${productId}]"]`);
            if (input.value > 1) {
                input.value = parseInt(input.value) - 1;
            }
        }
    </script>
    
    <script src="scripts/bootstrap.bundle.min.js"></script>
</body>
</html>