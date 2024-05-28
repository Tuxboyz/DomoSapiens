<th scope="row">Tel&eacute;fono:</th>
<td><?php echo $data['telefono']?></td>
<td class="text-center">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#mod_phone">
        <i class="bi bi-pencil-square"></i>
    </button>
</td>

<div class="modal fade" id="mod_phone" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Editar N&uacute;mero de tel&eacute;fono</h1>
                <a href="my_data.php" class="btn-close"></a>
            </div>
            <div class="modal-body">
                <form id="edit_tlf_form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <div class="form-floating" id="log-block">
                        <input type="tel" class="form-control" id="new_tel" name="new_tel" placeholder="Nuevo telefono" required>
                        <label for="new_tel">Nuevo tel&eacute;fono</label>
                        <span class="error-message" style="display: none;"></span>
                    </div>
                    <div id="form_message_telef" class="text-center"></div>
                </form>
            </div>
            <div class="modal-footer">
                <a href="my_data.php"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button></a>
                <button type="submit" form="edit_tlf_form" class="btn btn-primary" >Aplicar</button>
            </div>
        </div>
    </div>
</div>
<?php
    require_once('includes/Config.php');
    require_once('includes/Usuario.php');

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_tel'])) {
        $new_tel = barrer($_POST['new_tel']);
        $errores = [];

        if (empty($new_tel)) {
            $errores[] = 'Error: No has introducido ningún número de teléfono.';
        }
        if (!preg_match("/^\d{9}$/", $new_tel)){
            $errores[] = 'Error: El telefono solo puede tener 9 <b>digitos</b>.';
        }

        if (!empty($errores)) {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    var modal = new bootstrap.Modal(document.getElementById('mod_phone'));
                    modal.show();
                    document.getElementById('form_message_telef').innerHTML = `<p style='color:red;'>".implode('<br>', $errores)."</p>`;
                });
            </script>";
        } else {
            $conn = new Usuario();
            $test = $conn->edit_data($_SESSION['id'], 'telefono', $new_tel);
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    var modal = new bootstrap.Modal(document.getElementById('mod_phone'));
                    modal.show();
                    document.getElementById('form_message_telef').innerHTML = `<p style='color:green;'>Teléfono cambiado con éxito</p>`;
                });
            </script>";
        }
    }
?>