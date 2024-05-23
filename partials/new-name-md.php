<th scope="row">Nombre:</th>
<td><?php echo $data['nombre']?></td>
<td class="text-center">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#mod_name">
        <i class="bi bi-pencil-square"></i>
    </button>
</td>

<div class="modal fade" id="mod_name" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Editar Nombre</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="edit_name_form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <!--FORMULARIO-->
                    <div class="form-floating" id="log-block">
                        <input type="text" class="form-control" id="new_name" name="new_name" placeholder="Nuevo nombre" required>
                        <label for="new_name">Nuevo nombre</label>
                        <span class="error-message" style="display: none;"></span>
                    </div>
                    <div id="form_message_name" class="text-center"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" form="edit_name_form" class="btn btn-primary" id="apply_button">Aplicar</button>
            </div>
        </div>
    </div>
</div>

<?php
require_once('includes/Config.php');
require_once('includes/Usuario.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_name'])) {
    $new_name = barrer($_POST['new_name']);
    $errores = [];

    if (empty($new_name)) {
        $errores[] = 'Error: No has introducido ningún nombre.';
    }
    if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $new_name)){
        $errores[] = 'Error: El nombre contiene caracteres invalidos.';
    }
    if (strlen($new_name) < 3){
        $errores[] = 'Error: El nombre contiene menos de 3 letras.';
    }

    if (!empty($errores)) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                var modal = new bootstrap.Modal(document.getElementById('mod_name'));
                modal.show();
                document.getElementById('form_message_name').innerHTML = `<p style='color:red;'>".implode('<br>', $errores)."</p>`;
            });
        </script>";
    } else {
        $conn = new Usuario();
        $test = $conn->edit_data($_SESSION['id'], 'nombre', $new_name);
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                var modal = new bootstrap.Modal(document.getElementById('mod_name'));
                modal.show();
                document.getElementById('form_message_name').innerHTML = `<p style='color:green;'>Nombre cambiado con éxito</p>`;
            });
        </script>";
    }
}
?>