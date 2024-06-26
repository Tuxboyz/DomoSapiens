<?php
    session_start();
    if(isset($_SESSION['nombre_admin'])){
        header("Location: panel.php");
        exit;
    }
    require_once('Admin.php');
    require_once('../includes/Config.php');

    $errores = [];

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
            $conn = new Admin();
            $test = $conn->validacion_admin($usuario, $password);
            if ($test == false) {
                $errores[] = '<div class="alert alert-danger d-flex align-items-center" role="alert">
                                <i class="m-1 bi bi-exclamation-triangle"></i>
                                <div>
                                    El email o contraseñas son incorrectos.
                                </div>
                            </div>';
            } else {
                $_SESSION['id'] = $test['id'];
                $_SESSION['nombre_admin'] = $test['nombre_admin'];
                $_SESSION['permisos'] = $test['administrador'];
                
                sleep(2);
                header('Location: panel.php');
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
    <link rel="stylesheet" href="../styles/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../styles/styles.css">
    <title>Bienvenido!</title>
    <style>
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
<body class="d-flex align-items-center justify-content-center vh-100">
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

            <button class="btn btn-primary w-100 py-2" type="submit">iniciar sesion</button>

        </form>
        <div class="text-center m-3">
            Si tienes algun problema, no dudes en contactar con tu superior.
        </div>
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
    <script src="../scripts/bootstrap.bundle.min.js"></script>
</body>
</html>
