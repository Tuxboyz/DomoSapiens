<?php session_start(); ?>
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
        <?php include_once 'partials/header.php';
              include_once 'includes/Usuario.php';?>
    </header>

    <main>
        <?php if (isset($_SESSION['nombre'])) { $conn = new Usuario();$data = $conn->get_data($_SESSION['id']);?>
            <div class='container'>
                <div class="row">
                    <div class="col-3 text-center">
                        <i class="bi bi-cart-fill" id="icono"></i>
                    </div>

                    <div class="col-9 d-flex align-items-center justify-content-center">
                        <h3>Bienvenido <?php echo $data['nombre']?> a tu Carrito de la compra.</h3>
                    </div>

                    <div class="col-12">
                        <div class="container my-5">
                            <div class="bg-body-tertiary p-5 rounded">
                                <div class="col-sm-8 py-5 mx-auto text-center">
                                    <p class="fs-5">¿Que quieres <a href="index.php">comprar</a>?</p>
                                </div>
                            </div>
                        </div>
                        <div class="form-check text-center my-5">
                            ¿Tienes algún problema? Podemos ayudarte <a href="faq.php">aquí</a>!
                        </div>
                    </div>

                </div>
            </div>
        <?php } else {?>
            <div class='container'>
                <div class="row">
                    <div class="col-3 text-center">
                        <i class="bi bi-cart-fill" id="icono"></i>
                    </div>

                    <div class="col-9 d-flex align-items-center justify-content-center">
                        <h3>Tienes que iniciar sesion para poder comprar.</h3>
                    </div>

                    <div class="col-12">
                        <div class="container my-5">
                            <div class="bg-body-tertiary p-5 rounded">
                                <div class="col-sm-8 py-5 mx-auto text-center">
                                    <p class="fs-5">Inicia sesion <a href="login.php">aqui</a>.</p>
                                </div>
                            </div>
                        </div>
                        <div class="form-check text-center my-5">
                            ¿Tienes algún problema? Podemos ayudarte <a href="faq.php">aquí</a>!
                        </div>
                    </div>

                </div>
            </div>
        <?php }?>
    </main>

    <footer class="footer mt-auto">
        <?php include_once 'partials/footer.php';?>
    </footer>

    <script src="scripts/bootstrap.bundle.min.js"></script>
</body>
</html>