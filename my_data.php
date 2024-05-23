<?php
session_start();
require_once('includes/Config.php');
require_once('includes/Usuario.php');
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
        .user-data-list {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 10px;
            border-radius: 5px;
        }

        .user-data-list li {
            margin-bottom: 5px;
        }

        .data-label {
            font-weight: bold;
        }

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
        <?php
        include_once 'partials/header.php';
        include_once 'includes/Usuario.php';
        ?>
    </header>

    <main>
        <?php if (isset($_SESSION['nombre'])) { $conn = new Usuario();$data = $conn->get_data($_SESSION['id']);?>

        <div class='container'>
            <div class="row">
                <div class="col-3 text-center">
                    <i class="bi bi-person-fill" id="icono_data"></i>
                </div>

                <div class="col-9 d-flex align-items-center justify-content-center">
                    <h3>Bienvenido <?php echo $_SESSION['nombre']; ?> a tu panel de control.</h3>
                </div>

                <div class="col-12">
                    <nav>
                        <div class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-data-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-data" type="button" role="tab" aria-controls="nav-data"
                                aria-selected="true">Mis Datos</button>

                            <button class="nav-link" id="nav-address-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-address" type="button" role="tab" aria-controls="nav-address"
                                aria-selected="false" tabindex="-1">Mis direcciones</button>

                            <button class="nav-link" id="nav-orders-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-orders" type="button" role="tab" aria-controls="nav-orders"
                                aria-selected="false" tabindex="-1">Mis Pedidos</button>

                            <button class="nav-link" id="nav-leave-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-leave" type="button" role="tab" aria-controls="nav-leave"
                                aria-selected="false" tabindex="-1">Darse de baja</button>
                        </div>
                    </nav>

                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade active show" id="nav-data" role="tabpanel"
                            aria-labelledby="nav-data-tab" tabindex="0">
                            <!--Mis datos-->
                            <table class="table table-bordered table-striped">
                                <tbody>
                                    <tr>
                                      <?php include_once 'partials/new-name-md.php'; ?>
                                    </tr>

                                    <tr>
                                      <?php include_once 'partials/new-surname-md.php'; ?>
                                    </tr>

                                    <tr>
                                      <?php include_once 'partials/new-email-md.php'; ?>
                                    </tr>

                                    <tr>
                                      <?php include_once 'partials/new-password-md.php'; ?>
                                    </tr>

                                    <tr>
                                      <?php include_once 'partials/new-date-birth-md.php'; ?>
                                    </tr>

                                    <tr>
                                      <?php include_once 'partials/new-telef-md.php'; ?>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="nav-address" role="tabpanel" aria-labelledby="nav-address-tab"
                            tabindex="0">
                            <!--Mis direcciones-->
                            <?php include_once 'partials/address-md.php'; ?>
                        </div>
                        <div class="tab-pane fade" id="nav-orders" role="tabpanel" aria-labelledby="nav-orders-tab"
                            tabindex="0">
                            <!--Mis pedidos-->
                            <div class="container my-5">
                                <div class="bg-body-tertiary p-5 rounded">
                                    <div class="col-sm-8 py-5 mx-auto text-center">
                                        <p class="fs-5">Todavía no has realizado ning&uacute;n pedido...</p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-check text-center my-3">
                                ¿Tienes algún problema? Podemos ayudarte <a href="faq.php">aquí</a>!
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-leave" role="tabpanel" aria-labelledby="nav-leave-tab"
                            tabindex="0">
                            <!--Darse de baja-->
                            <div class="bg-body-tertiary p-5 rounded text-center">
                                <div class="col-sm-8 py-5 mx-auto">

                                    <p class="fs-5">¿Quieres darte de baja?</p>

                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#mod_eliminate">Darse de baja</button>

                                    <div class="modal fade" id="mod_eliminate" data-bs-backdrop="static"
                                        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                        aria-hidden="true">

                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">¿Estas seguro que
                                                        quieres darte de baja?</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="col-12 text-center mb-3">Se borraran los siguientes
                                                        datos</div>
                                                    <div class="col-12 text-center d-flex justify-content-center mb-3">
                                                        <ul class="text-left">
                                                            <li>Datos personales</li>
                                                            <li>Todas tus direcciones</li>
                                                            <li>Todos tus tickets</li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-12 text-center mb-3">Despues de un año seran borrados en
                                                        su totalidad, despues de esta fecha no podras recuperar los datos
                                                        borrados</div>
                                                    <form class="needs-validation was-validated" novalidate=""
                                                        id="leave_form">
                                                        <div class="mb-3">
                                                            <label for="valid" class="form-label">Escriba <b>Darse de
                                                                    baja</b> para confirmar:</label>
                                                            <input type="text" class="form-control" id="valid"
                                                                placeholder="" value="" required="">
                                                            <div class="invalid-feedback">Es necesario poner el texto de
                                                                confirmacion.</div>
                                                            <!--esto usa un display block "display: block;" para mostrarse-->
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cerrar</button>
                                                    <button type="submit" class="btn btn-primary"
                                                        form="leave_form">Aceptar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="form-check text-center my-3">
                                ¿Tienes alg&uacute;n problema? Podemos ayudarte <a href="faq.php">aqu&iacute;</a>!
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <?php } else { ?>
        <div class='container'>
            <div class="row">
                <div class="col-12 text-center">
                    <h2>No se puede acceder a esta pagina sin iniciar sesion como usuario.</h2>
                </div>
            </div>
        </div>

        <?php } ?>
    </main>

    <footer class="footer mt-auto">
        <?php include_once 'partials/footer.php'; ?>
    </footer>

    <script src="scripts/bootstrap.bundle.min.js"></script>
    <script src="scripts/validation-my_data.js"></script>
</body>

</html>