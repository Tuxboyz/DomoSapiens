<?php
    session_start();

    require_once('includes/Config.php');
    require_once('includes/Usuario.php');

    $errores = [];
    $mensajeExito = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['usuario'])) {
            $usuario = barrer($_POST['usuario']);
        }
        if (empty($usuario)) {
            $errores[] = 'Error: No has introducido ningún usuario.';
        }

        if (isset($_POST['password'])) {
            $password = barrer($_POST['password']);
        }
        if (empty($password)) {
            $errores[] = 'Error: No has introducido ninguna contraseña.';
        }

        if (empty($errores)) {
            $conn = new Usuario();
            $test = $conn->validacion($usuario, $password);
            if ($test == false) {
                $errores[] = '<div class="alert alert-danger d-flex align-items-center" role="alert">
                                <i class="m-1 bi bi-exclamation-triangle"></i>
                                <div>
                                    El email o contraseñas son incorrectos.
                                </div>
                            </div>';
            } else {
                $_SESSION['id'] = $test['id'];
                $_SESSION['nombre'] = $test['nombre'];
                
                sleep(2);
                header('Location: index.php');
                exit();
            }
        }
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
    <title>Bienvenido!</title>
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
    <script src="scripts/bootstrap.bundle.min.js"></script>
    <script src="scripts/validation-log.js"></script>
</head>
<body>
    <header class="py-3 mb-3 border-bottom">
        <?php require_once 'partials/header.php'; ?>
    </header>
<?php if (!isset($_SESSION['id'])){ ?>
    <main class="form-signin w-100 m-auto" id="form">
        <form action="login.php" method="post">
            <div class="text-center" id="log-block">
                <i class="bi bi-person-fill" id="icono" style="font-size: 80px"></i>
            </div>
            <h1 class="text-center h3 mb-3 fw-normal">Inicia sesi&oacute;n</h1>

            <div class="form-floating" id="log-block">
                <input type="email" class="form-control" id="usuario" name="usuario" placeholder="name@example.com" value="" required>
                <label for="usuario">Correo</label>
                <span class="text-danger"></span>
            </div>

            <div class="form-floating" id="log-block">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                <label for="password">Contraseña</label>
                <span class="text-danger"></span>
            </div>

            <button class="btn btn-primary w-100 py-2" type="submit">Sign in</button>

            <div class="text-center m-3">
                No tienes cuenta, créate una <a href="create_acc.php">aquí</a>!
            </div>
        </form>
        <div class="text-center m-3">
            <?php
                if (!empty($errores)) {
                    echo "<p style='color:red;'>";
                    foreach ($errores as $e) {
                        echo $e . '</br>';
                    }
                    echo "</p>";
                }
            ?>
        </div>
    </main>
<?php } else { ?>
    <div class="container my-5">
        <div class="my-5 bg-body-tertiary p-5 rounded">
            <div class="col-sm-8 py-5 mx-auto text-center">
                <h2>Ya has inciado sesion.</h2>
            </div>
        </div>

        <div class="text-center"><p>Si quieres volver a la paguina de incicio puedes darle <a href="index.php">aquí</a></p></div>
        <div class="text-center"><p>Si quieres acceder a tu panel de control dale <a href="my_data.php">aquí</a></p></div>
        <div class="text-center"><p>Si necesitas ayuda puedes darle <a href="faq.php">aquí</a></p></div>
        
    </div>
<?php } ?>

    <footer class="footer mt-auto">
        <?php require_once 'partials/footer.php'; ?>
    </footer>
</body>
</html>