<?php
    session_start();
    include_once 'includes/Usuario.php';
        $con = new Usuario(); 
        $test = $con->select_address($_SESSION['id']);
    require_once 'includes/Carrito.php';
        $con2 = new Carrito();
        $count = $con2->item_count($_SESSION['id']);

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cantidades']) && isset($_SESSION['id'])) {
        $cantidades = $_POST['cantidades'];
        $id_usuario = $_SESSION['id'];
        
        $carrito = new Carrito();
        $carrito->update_cart($cantidades, $id_usuario);
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
            .error-message {
                color: red;
                font-size: 0.9em;
            }
            .valid {
                border-color: #198754;
            }
            .invalid {
                border-color: #dc3545;
            }
            .form-floating label {
                transition: all 0.2s;
            }
            .form-floating input:not(:placeholder-shown) + label,
            .form-floating input:focus + label {
                opacity: 1;
                transform: scale(0.85) translateY(-1.5rem) translateX(0.15rem);
            }
        </style>
        <title>Bienvenido!</title>
    </head>
    <body>
        <header class="py-3 mb-3 border-bottom">
            <?php include_once 'partials/header.php';?>
        </header>
        <div class='container'>
            <div class="row g-5">
                <div class="col-md-5 col-lg-4 order-md-last">
                    <h4 class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-primary">Tu carrito</span>
                        <span class="badge bg-primary rounded-pill"><?php echo $count;?></span><!--cantidad de articulos-->
                    </h4>
                    <ul class="list-group mb-3">
                        <?php $items = $con2->buy_items($_SESSION['id']);?><!--articulos a comprar-->
                    </ul>
                </div>
                <div class="col-md-7 col-lg-8">
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <h4 class="mb-3">Selecciona dirección de envío.</h4>
                        <div class="row g-3"><!--mostramos el select de las direcciones-->
                            <div class="container">
                                <div class="my-3">
                                    <?php if (!$test) {
                                        echo '<div class="alert alert-danger d-flex align-items-center" role="alert"><i class="bi bi-exclamation-triangle me-2"></i><p class="mb-0">Es necesaria una dirección de envío, créate una en el <a href="my_data.php">panel de Control</a>!</p></div>';
                                    } else {
                                        echo $test;
                                    }?>
                                </div>
                            </div>
                        </div>
                        <hr class="my-4">
                        <h4 class="mb3">Selecciona un método de pago.</h4>
                        <div class="my-3">
                            <div class="form-check">
                                <input id="credit" name="metodopago" type="radio" class="form-check-input" value="credit" checked required>
                                <label class="form-check-label" for="credit">Credit card</label>
                                <div class="invalid-feedback">Credit card is required</div>
                            </div>
                            <div class="form-check">
                                <input id="debit" name="metodopago" type="radio" class="form-check-input" value="debit" required>
                                <label class="form-check-label" for="debit">Debit card</label>
                                <div class="invalid-feedback">Debit card is required</div>
                            </div>
                            <div class="form-check">
                                <input id="paypal" name="metodopago" type="radio" class="form-check-input" value="paypal" disabled>
                                <label class="form-check-label" for="paypal">PayPal (Próximamente...)</label>
                                <div class="invalid-feedback">PayPal is required</div>
                            </div>
                        </div>
                        <div class="row gy-3">
                            <div class="col-md-6">
                                <div class="form-floating" id="log-block">
                                    <input type="text" class="form-control" id="tarjeta-nombre" name="tarjeta-nombre" placeholder="" required>
                                    <label for="tarjeta-nombre">Nombre en la tarjeta</label>
                                    <span class="error-message" style="display: none;"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating" id="log-block">
                                    <input type="text" class="form-control" id="tarjeta-numero" name="tarjeta-numero" placeholder="" required>
                                    <label for="tarjeta-numero">Numero de la tarjeta</label>
                                    <span id="tarjeta-numero-feedback" class="error-message" style="display: none;"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating" id="log-block">
                                    <input type="number" class="form-control" id="tarjeta-caducidad1" name="tarjeta-caducidad1" min="1" max="12" placeholder="MM" placeholder="" required>
                                    <label for="tarjeta-caducidad1">Mes de Vencimiento:</label>
                                    <span id="caducidad1-feedback" class="error-message" style="display: none;"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating" id="log-block">
                                    <input type="number" class="form-control" id="tarjeta-caducidad2" name="tarjeta-caducidad2" min="<?php echo date('Y'); ?>" max="<?php echo date('Y') + 10; ?>" placeholder="YYYY" required>
                                    <label for="tarjeta-caducidad2">Año de Vencimiento:</label>
                                    <span id="caducidad2-feedback" class="error-message" style="display: none;"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating" id="log-block">
                                    <input type="text" class="form-control" id="tarjeta-cvv" name="tarjeta-cvv" placeholder="" required>
                                    <label for="tarjeta-cvv">CVV</label>
                                    <span class="error-message" style="display: none;"></span>
                                </div>
                            </div>
                        </div>
                        <hr class="my-4">
                        <button class="w-100 btn btn-primary btn-lg m-3" type="submit">Finalizar pedido</button>
                    </form>
                    <div>
                        <?php
                            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['id']) && isset($_POST['ciudad'])) {

                                echo '<pre>';
                                var_dump($_POST);
                                echo '</pre>';
                                

                                $datos = $con2->obtener_resumen_compra($_SESSION['id']);

                                $productos = json_encode($datos["productos"]);
                                $precio_envio = $datos["precio_envio"];
                                $precio_total = $datos["precio_total"];

                                $ciudad = isset($_POST['ciudad']) ? barrer($_POST['ciudad']) : '';
                                $metodopago = barrer($_POST['metodopago']);
                                $tarjetaNombre = barrer($_POST['tarjeta-nombre']);
                                $tarjetaNumero = barrer($_POST['tarjeta-numero']);
                                $tarjetaCVV = barrer($_POST['tarjeta-cvv']);

                                if (!$datos){
                                    $errores[] = 'Error: A ocurrido un error con los productos, intentelo mas tarde.';
                                }

                                if (empty($productos)) {
                                    $errores[] = 'Error: No hay nigun producto.';
                                }

                                if (empty($precio_envio)) {
                                    $errores[] = 'Error: A ocurrido un error con el precio del envio, intentelo mas tarde.';
                                }

                                if (empty($precio_total)) {
                                    $errores[] = 'Error: A ocurrido un erro con el precio.';
                                }

                                if (empty($ciudad)) {
                                    $errores[] = 'Error: No has seleccionado ninguna ciudad.';
                                }

                                if (empty($metodopago)) {
                                    $errores[] = 'Error: No has seleccionado ningún método de pago.';
                                }
                                if (empty($tarjetaNombre)) {
                                    $errores[] = 'Error: No has introducido ningún nombre de titular de tarjeta.';
                                }
                                if (empty($tarjetaNumero)) {
                                    $errores[] = 'Error: No has introducido ningún número de tarjeta.';
                                }
                                if (getTipoTarjeta($tarjetaNumero) == false){
                                    $errores[] = 'Error: Numero de tarjeta invalido.';
                                }
                                if (empty($tarjetaCVV)) {
                                    $errores[] = 'Error: No has introducido el código de seguridad CVV.';
                                }

                                if (!empty($errores)) {
                                    echo "<p style='color:red;'>";
                                    foreach ($errores as $e) {
                                        echo $e . '</br>';
                                    }
                                    echo "</p>";
                                } else {
                                    $pago = $con2->pago($_SESSION['id'],$ciudad,$metodopago,$productos,$precio_envio,$precio_total);
                                    if($pago == true){
                                        $con2->pago_post_borrado($_SESSION['id']);
                                    } else {
                                        $errores[] = 'Error: Ha ocurrido un error inesperado con el pago.';
                                    }
                                    echo "<p style='color:green;'> Pago efectuado exitosamente.</p>";
                                    echo '<meta http-equiv="refresh" content="2;url=my_data.php">';
                                    echo "<a href='my_data.php'>Haz clic aquí</a> si no eres redirigido automáticamente.</p>";
                                    
                                }
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <footer class="footer mt-auto">
            <?php include_once 'partials/footer.php';?>
        </footer>
        
        <script src="scripts/bootstrap.bundle.min.js"></script>
        <script src="scripts/validacion-buy.js"></script>
    </body>
</html>

<!--4111 1111 1111 1111 test de tarjeta-->

