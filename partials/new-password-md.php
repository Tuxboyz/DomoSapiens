<th scope="row">Contraseña:</th>
<td>**********</td>
<td class="text-center">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#mod_pass">
        <i class="bi bi-pencil-square"></i>
    </button>
</td>

<div class="modal fade" id="mod_pass" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Editar Contraseña</h1>
                <a href="my_data.php" class="btn-close"></a>
            </div>
            <div class="modal-body">
                <form id="edit_pass_form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <div class="form-floating" id="log-block">
                        <input type="password" class="form-control" id="actual_pass" name="actual_pass" placeholder="Contraseña actual" required>
                        <label for="actual_pass">Contraseña actual</label>
                        <span class="error-message" style="display: none;"></span>
                    </div>
                    <div class="form-floating" id="log-block">
                        <input type="password" class="form-control" id="new_pass" name="new_pass" placeholder="Nueva contraseña" required>
                        <label for="new_pass">Nueva contraseña</label>
                        <span class="error-message" style="display: none;"></span>
                    </div>
                    <div class="form-floating" id="log-block">
                        <input type="password" class="form-control" id="confirm_new_pass" name="confirm_new_pass" placeholder="Confirmar nueva contraseña" required>
                        <label for="confirm_new_pass">Confirmar nueva contraseña</label>
                        <span class="error-message" style="display: none;"></span>
                    </div>
                    <div id="form_message_new_pass" class="text-center"></div>
                </form>
            </div>
            <div class="modal-footer">
                <a href="my_data.php"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button></a>
                <button type="submit" form="edit_pass_form" class="btn btn-primary">Aplicar</button>
            </div>
        </div>
    </div>
</div>
<?php
    require_once('includes/Config.php');
    require_once('includes/Usuario.php');

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['actual_pass'])) {
        $actual_pass = barrer($_POST['actual_pass']);
        $new_pass = barrer($_POST['new_pass']);
        $confirm_new_pass = barrer($_POST['confirm_new_pass']);
        $errores = [];

        // Check if actual password is correct
        $conn = new Usuario();
        $valid_pass = $conn->valid_pass($_SESSION['id'], MD5($actual_pass));

        if ($valid_pass == false) {
            $errores[] = 'Error: La contraseña actual no es correcta.';
        }
        if (empty($new_pass)) {
            $errores[] = 'Error: No has introducido la nueva contraseña.';
        }
        if (strlen($new_pass) < 8){
            $errores[] = 'Error: La contraseña es inferior a 8 caracteres.';
        }
        if ($new_pass !== $confirm_new_pass) {
            $errores[] = 'Error: La nueva contraseña y la confirmación no coinciden.';
        }

        if (!empty($errores)) {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    var modal = new bootstrap.Modal(document.getElementById('mod_pass'));
                    modal.show();
                    document.getElementById('form_message_new_pass').innerHTML = `<p style='color:red;'>".implode('<br>', $errores)."</p>`;
                });
            </script>";
        } else {
            $test = $conn->edit_data($_SESSION['id'], 'password', MD5($new_pass));
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    var modal = new bootstrap.Modal(document.getElementById('mod_pass'));
                    modal.show();
                    document.getElementById('form_message_new_pass').innerHTML = `<p style='color:green;'>Contraseña cambiada con éxito</p>`;
                });
            </script>";
        }
    }
?>
