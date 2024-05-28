<th scope="row">Apellidos:</th>
<td><?php echo $data['apellido']?></td>
<td class="text-center">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#mod_surname">
        <i class="bi bi-pencil-square"></i>
    </button>
</td>

<div class="modal fade" id="mod_surname" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Editar Apellidos</h1>
                <a href="my_data.php" class="btn-close"></a>
            </div>
            <div class="modal-body">
                <form id="edit_surname_form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <div class="form-floating" id="log-block">
                        <input type="text" class="form-control" id="new_surname" name="new_surname" placeholder="Nuevo apellidos" required>
                        <label for="new_surname">Nuevo Apellidos</label>
                        <span class="error-message" style="display: none;"></span>
                    </div>
                    <div id="form_message_surname" class="text-center"></div>
                </form>
            </div>
            <div class="modal-footer">
                <a href="my_data.php"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button></a>
                <button type="submit" form="edit_surname_form" class="btn btn-primary">Aplicar</button>
            </div>
        </div>
    </div>
</div>
<?php
require_once('includes/Config.php');
require_once('includes/Usuario.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_surname'])) {
    $new_surname = barrer($_POST['new_surname']);
    $errores = [];

    if (empty($new_surname)) {
        $errores[] = 'Error: No has introducido ningún apellido.';
    }
    if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $new_surname)){
        $errores[] = 'Error: Los apellidos contienen caracteres invalidos.';
    }
    if (strlen($new_surname) < 3){
        $errores[] = 'Error: Los apellidos contienen menos de 3 letras.';
    }

    if (!empty($errores)) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                var modal = new bootstrap.Modal(document.getElementById('mod_surname'));
                modal.show();
                document.getElementById('form_message_surname').innerHTML = `<p style='color:red;'>".implode('<br>', $errores)."</p>`;
            });
        </script>";
    } else {
        $conn = new Usuario();
        $test = $conn->edit_data($_SESSION['id'], 'apellidos', $new_surname);
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                var modal = new bootstrap.Modal(document.getElementById('mod_surname'));
                modal.show();
                document.getElementById('form_message_surname').innerHTML = `<p style='color:green;'>Apellidos cambiados con éxito</p>`;
            });
        </script>";
    }
}
?>