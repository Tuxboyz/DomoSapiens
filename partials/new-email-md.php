<th scope="row">Email:</th>
<td><?php echo htmlspecialchars($data['email']); ?></td>
<td class="text-center">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#mod_email">
        <i class="bi bi-pencil-square"></i>
    </button>
</td>

<div class="modal fade" id="mod_email" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Editar Email</h1>
                <a href="my_data.php" class="btn-close"></a>
            </div>
            <div class="modal-body">
                <form id="edit_email_form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <div class="form-floating" id="log-block">
                        <input type="email" class="form-control" id="new_email" name="new_email" placeholder="Nuevo email" required>
                        <label for="new_email">Nuevo email</label>
                        <span class="error-message" style="display: none;"></span>
                    </div>
                    <div id="form_message_email" class="text-center"></div>
                </form>
            </div>
            <div class="modal-footer">
                <a href="my_data.php"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button></a>
                <button type="submit" form="edit_email_form" class="btn btn-primary">Aplicar</button>
            </div>
        </div>
    </div>
</div>

<?php
    require_once('includes/Config.php');
    require_once('includes/Usuario.php');

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_email'])) {
        $new_email = barrer($_POST['new_email']);
        $errores = [];

        // Validar si el nuevo email está en uso
        $conn = new Usuario();
        if (!$conn->valid_email($new_email)) {
            $errores[] = 'Error: El correo electrónico ya está en uso.';
        }
        if (empty($new_email)){
            $errores[] = 'Error: No has introducido ningun correo electronico.';
        }
        if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)){
            $errores[] = 'Error: El correo electrónico no es valido.';
        }
        if (!preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.(com|es|org)$/", $new_email)){
            $errores[] = 'Error: El correo electrónico no contiene un dominio valido.';
        }

        // Mostrar errores si existen
        if (!empty($errores)) {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    var modal = new bootstrap.Modal(document.getElementById('mod_email'));
                    modal.show();
                    document.getElementById('form_message_email').innerHTML = `<p style='color:red;'>".implode('<br>', $errores)."</p>`;
                });
            </script>";
        } else {
            // Actualizar el correo electrónico en la base de datos
            $test = $conn->edit_data($_SESSION['id'], 'email', $new_email);
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    var modal = new bootstrap.Modal(document.getElementById('mod_email'));
                    modal.show();
                    document.getElementById('form_message_email').innerHTML = `<p style='color:green;'>Correo electrónico cambiado con éxito</p>`;
                });
            </script>";
        }
    }
?>
