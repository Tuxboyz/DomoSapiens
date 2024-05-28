<div class="bg-body-tertiary p-5 rounded text-center">
    <div class="col-sm-8 py-5 mx-auto">
        <p class="fs-5">¿Quieres darte de baja?</p>
        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#mod_eliminate">Darse de baja</button>
        <div class="modal fade" id="mod_eliminate" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">¿Estas seguro que quieres darte de baja?</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="col-12 text-center mb-3">Se borraran los siguientes datos</div>
                        <div class="col-12 text-center d-flex justify-content-center mb-3">
                            <ul class="text-left">
                                <li>Datos personales</li>
                                <li>Todas tus direcciones</li>
                                <li>Todos tus tickets</li>
                            </ul>
                        </div>
                        <div class="col-12 text-center mb-3">Despues de un año seran borrados en su totalidad, despues de esta fecha no podras recuperar los datos borrados</div>
                        <form class="needs-validation was-validated" novalidate="" id="leave_form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                            <div class="mb-3">
                                <label for="valid" class="form-label">Escriba <b>Darse de baja</b> para confirmar:</label>
                                <input type="text" class="form-control" id="valid" name="valid" required>
                                <div class="invalid-feedback">Es necesario poner el texto de confirmacion.</div>
                            </div>
                            <div id="form_message_leave" class="text-center"></div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" form="leave_form">Aceptar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="form-check text-center my-3">
    ¿Tienes algún problema? Podemos ayudarte <a href="faq.php">aquí</a>!
</div>
<?php
// Incluye archivos de configuración y clases necesarias
require_once('includes/Config.php');
require_once('includes/Usuario.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['valid'])) {
    // Filtra y valida el campo de confirmación
    $valid = isset($_POST['valid']) ? htmlspecialchars($_POST['valid']) : '';
    $errores = [];

    if (empty($valid)) {
        $errores[] = 'No has escrito la confirmacion.';
    }

    if ($valid != 'Darse de baja') {
        $errores[] = 'No has escrito <b>Darse de baja</b>.';
    }

    if (!empty($errores)) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                var modal = new bootstrap.Modal(document.getElementById('mod_eliminate'));
                modal.show();
                document.getElementById('form_message_leave').innerHTML = `<p style='color:red;'>".implode('<br>', $errores)."</p>`;

                // Update tab classes
                document.getElementById('nav-data-tab').className = 'nav-link';
                document.getElementById('nav-data-tab').setAttribute('aria-selected', 'false');
                document.getElementById('nav-data-tab').setAttribute('tabindex', '-1');

                document.getElementById('nav-data').className = 'tab-pane fade';

                document.getElementById('nav-leave-tab').className = 'nav-link active';
                document.getElementById('nav-leave-tab').setAttribute('aria-selected', 'true');
                document.getElementById('nav-leave-tab').removeAttribute('tabindex');

                document.getElementById('nav-leave').className = 'tab-pane fade active show';
            });
        </script>";
    } else {
        $conn = new Usuario();
        if ($conn->dar_baja($_SESSION['id'])) {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    var modal = new bootstrap.Modal(document.getElementById('mod_eliminate'));
                    modal.show();
                    document.getElementById('form_message_leave').innerHTML = `<p style='color:green;'>Se ha dado de baja correctamente, será redirigido a la página principal.</p>`;

                    // Update tab classes
                    document.getElementById('nav-leave-tab').className = 'nav-link active';
                    document.getElementById('nav-leave-tab').setAttribute('aria-selected', 'true');
                    document.getElementById('nav-leave-tab').removeAttribute('tabindex');

                    document.getElementById('nav-leave').className = 'tab-pane fade active show';
                });

                setTimeout(function() {
                    window.location.href = 'logout.php';
                }, 3000); // Redirigir después de 3 segundos
            </script>";
        } else {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    var modal = new bootstrap.Modal(document.getElementById('mod_eliminate'));
                    modal.show();
                    document.getElementById('form_message_leave').innerHTML = `<p style='color:red;'>Error al intentar dar de baja.</p>`;
                });
            </script>";
        }
    }
}
?>

