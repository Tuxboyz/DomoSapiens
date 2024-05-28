<th scope="row">Fecha de Nacimiento:</th>
<td>
    <?php
    $date = new DateTime($data['fecha_nac']);
    $formatted_date = $date->format('d/m/Y');
    echo $formatted_date;
    ?>
</td>
<td class="text-center">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#mod_birthdate">
        <i class="bi bi-pencil-square"></i>
    </button>
</td>

<div class="modal fade" id="mod_birthdate" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Editar Fecha de Nacimiento</h1>
                <a href="my_data.php" class="btn-close"></a>
            </div>
            <div class="modal-body">
                <form id="edit_birthdate_form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <div class="form-floating" id="log-block">
                        <input type="date" class="form-control" id="new_birth" name="new_birth" placeholder="Nueva Fecha de Nacimiento" min="1950-01-01" max="<?php $fechaHoy = date("Y-m-d"); echo $fechaHoy; ?>" required>
                        <label for="new_birth">Nueva Fecha de Nacimiento</label>
                        <span class="error-message" style="display: none;"></span>
                    </div>
                    <div id="form_message_birthdate" class="text-center"></div>
                </form>
            </div>
            <div class="modal-footer">
                <a href="my_data.php"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button></a>
                <button type="submit" form="edit_birthdate_form" class="btn btn-primary">Aplicar</button>
            </div>
        </div>
    </div>
</div>

<?php
    require_once('includes/Config.php');
    require_once('includes/Usuario.php');

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_birth'])) {
        $new_birth = barrer($_POST['new_birth']);
        $errores = [];

        $testeo = validateFechaNacimiento($new_birth);
        if (!$testeo){
            $errores[] = 'Error: Tienes que ser  mayor de edad.';
        }
        if (empty($new_birth)) {
            $errores[] = 'Error: No has introducido ninguna fecha de nacimiento.';
        }

        if (!empty($errores)) {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    var modal = new bootstrap.Modal(document.getElementById('mod_birthdate'));
                    modal.show();
                    document.getElementById('form_message_birthdate').innerHTML = `<p style='color:red;'>".implode('<br>', $errores)."</p>`;
                });
            </script>";
        } else {
            $conn = new Usuario();
            $test = $conn->edit_data($_SESSION['id'], 'fecha_nac', $new_birth);
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    var modal = new bootstrap.Modal(document.getElementById('mod_birthdate'));
                    modal.show();
                    document.getElementById('form_message_birthdate').innerHTML = `<p style='color:green;'>Fecha de nacimiento cambiada con éxito</p>`;
                });
            </script>";
        }
    }
?>