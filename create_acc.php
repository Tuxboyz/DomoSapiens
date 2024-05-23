<?php session_start();?>
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
</head>
<body>
    <header class="py-3 mb-3 border-bottom"><?php include_once 'partials/header.php';?></header>
    <?php if (!isset($_SESSION['id'])){ ?>
        <main class="form-signin w-100 m-auto" id="form">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" >
                <div class="text-center" id="log-block">
                    <i class="bi bi-person-fill" id="icono" style="font-size: 80px"></i>
                </div>
                <h1 class="text-center h3 mb-3 fw-normal">Crear cuenta</h1>

                <div class="form-floating" id="log-block">
                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" required value="<?php echo isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : ''; ?>">
                    <label for="nombre">Nombre</label>
                    <span class="error-message" style="display: none;"></span>
                </div>

                <div class="form-floating" id="log-block">
                    <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Apellido" required value="<?php echo isset($_POST['apellido']) ? htmlspecialchars($_POST['apellido']) : ''; ?>">
                    <label for="apellido">Apellidos</label>
                    <span class="error-message" style="display: none;"></span>
                </div>

                <div class="form-floating" id="log-block">
                    <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    <label for="email">Correo electrónico</label>
                    <span class="error-message" style="display: none;"></span>
                </div>

                <div class="form-floating" id="log-block">
                    <input type="email" class="form-control" id="confirm_email" name="confirm_email" placeholder="name@example.com" required value="<?php echo isset($_POST['confirm_email']) ? htmlspecialchars($_POST['confirm_email']) : ''; ?>">
                    <label for="confirm_email">Confirmar correo electrónico</label>
                    <span class="error-message" style="display: none;"></span>
                </div>

                <div class="form-floating" id="log-block">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    <label for="password">Contraseña</label>
                    <span class="error-message" style="display: none;"></span>
                </div>

                <div class="form-floating" id="log-block">
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Password" required>
                    <label for="confirm_password">Confirmar contraseña</label>
                    <span class="error-message" style="display: none;"></span>
                </div>

                <div class="form-floating" id="log-block">
                    <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" min="1950-01-01" max="<?php $fechaHoy = date("Y-m-d"); echo $fechaHoy; ?>" required value="<?php echo isset($_POST['fecha_nacimiento']) ? htmlspecialchars($_POST['fecha_nacimiento']) : ''; ?>">
                    <label for="fecha_nacimiento">Fecha de nacimiento</label>
                    <span class="error-message" style="display: none;"></span>
                </div>

                <div class="form-floating" id="log-block">
                    <input type="tel" class="form-control" id="telefono" name="telefono" placeholder="123456789" required value="<?php echo isset($_POST['telefono']) ? htmlspecialchars($_POST['telefono']) : ''; ?>">
                    <label for="telefono">Número de teléfono</label>
                    <span class="error-message" style="display: none;"></span>
                </div>

                <button class="btn btn-primary w-100 py-2" type="submit">Crear cuenta</button>

                <div class="text-center m-3">
                    ¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a>!
                </div>
            </form>
            <div class="text-center m-3">
                <?php
                    require_once('includes/Config.php');
                    require_once('includes/Usuario.php');

                    function validateNombre($nombre) {
                        return !empty($nombre) && preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $nombre) && strlen($nombre) >= 3;
                    }

                    function validateApellido($apellido) {
                        return !empty($apellido) && preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $apellido) && strlen($apellido) >= 3;
                    }

                    function validateEmail($email) {
                        return !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.(com|es|org)$/", $email);
                    }

                    function validatePassword($password) {
                        return !empty($password) && strlen($password) >= 8;
                    }

                    function validateTelefono($telefono) {
                        return !empty($telefono) && preg_match("/^\d{9}$/", $telefono);
                    }

                    function validateFechaNacimiento($fecha_nacimiento) {
                        if (empty($fecha_nacimiento)) return false;
                        $fecha = new DateTime($fecha_nacimiento);
                        $hoy = new DateTime();
                        $edad = $hoy->diff($fecha)->y;
                        return $edad >= 18;
                    }

                    $errores = [];

                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        $nombre = isset($_POST['nombre']) ? barrer($_POST['nombre']) : '';
                        $apellido = isset($_POST['apellido']) ? barrer($_POST['apellido']) : '';
                        $email = isset($_POST['email']) ? barrer($_POST['email']) : '';
                        $password = isset($_POST['password']) ? barrer($_POST['password']) : '';
                        $fecha_nacimiento = isset($_POST['fecha_nacimiento']) ? barrer($_POST['fecha_nacimiento']) : '';
                        $telefono = isset($_POST['telefono']) ? barrer($_POST['telefono']) : '';

                        if (!validateNombre($nombre)) $errores[] = 'Error: No has introducido un nombre válido.';
                        if (!validateApellido($apellido)) $errores[] = 'Error: No has introducido un apellido válido.';
                        if (!validateEmail($email)) $errores[] = 'Error: No has introducido un email válido.';
                        if (!validatePassword($password)) $errores[] = 'Error: No has introducido una contraseña válida.';
                        if (!validateFechaNacimiento($fecha_nacimiento)) $errores[] = 'Error: No has introducido una fecha de nacimiento válida.';
                        if (!validateTelefono($telefono)) $errores[] = 'Error: No has introducido un número de teléfono válido.';

                        if (!empty($errores)) {
                            echo "<p style='color:red;'>";
                            foreach ($errores as $e) {
                                echo $e . '</br>';
                            }
                            echo "</p>";
                        } else {
                            $usuario = new Usuario();
                            $usuario->crearUser($nombre, $apellido, $email, $password, $fecha_nacimiento, $telefono);
                            echo "<p style='color:green;'> Registrado exitosamente.</p>";
                            echo '<meta http-equiv="refresh" content="2;url=login.php">';
                            echo "<a href='login.php'>Haz clic aquí</a> si no eres redirigido automáticamente.</p>";
                        }
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
    <script src="scripts/bootstrap.bundle.min.js"></script>
    <script src="scripts/validation-create.js"></script>

    <footer class="footer mt-auto"><?php include_once 'partials/footer.php';?></footer>
</body>
</html>