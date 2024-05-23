<?php

    $provincias = [
        "A Coruña", "Álava", "Albacete", "Alicante", "Almería", "Asturias", "Ávila",
        "Badajoz", "Barcelona", "Burgos", "Cáceres", "Cádiz", "Cantabria", "Castellón",
        "Ceuta", "Ciudad Real", "Córdoba", "Cuenca", "Formentera", "Girona", "Granada",
        "Guadalajara", "Guipúzcoa", "Huelva", "Huesca", "Ibiza", "Jaén", "La Rioja",
        "Las Palmas de Gran Canaria (Gran Canaria)", "Las Palmas de Gran Canaria (Fuerteventura)", 
        "Las Palmas de Gran Canaria (Lanzarote)", "León", "Lérida", "Lugo", "Madrid", 
        "Málaga", "Mallorca", "Menorca", "Murcia", "Navarra", "Orense", "Palencia", 
        "Pontevedra", "Salamanca", "Santa Cruz de Tenerife (Tenerife)", 
        "Santa Cruz de Tenerife (La Gomera)", "Santa Cruz de Tenerife (La Palma)", 
        "Santa Cruz de Tenerife (El Hierro)", "Segovia", "Sevilla", "Soria", 
        "Tarragona", "Teruel", "Toledo", "Valencia", "Valladolid", "Vizcaya", "Zamora", "Zaragoza"
    ];

    function imprimirSelect($provincias) {
        echo '<select class="form-select" id="ciudad" name="ciudad" required>';
        echo '<option value="">Seleccione una opción</option>';
        foreach ($provincias as $provincia) {
            echo '<option value="' . htmlspecialchars($provincia) . '">' . htmlspecialchars($provincia) . '</option>';
        }
        echo '</select>';
    }
?>

<div class="container my-5">
    <div class="bg-body-tertiary p-5 rounded">
        <div class="col-sm-8 py-5 mx-auto text-center">
            <p class="fs-5">Aún no hay ninguna dirección...</p>
        </div>
    </div>

    <div class="text-center mt-3">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#mod_address">Añadir dirección</button>
    </div>

    <div class="modal fade" id="mod_address" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Añadir Dirección</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="needs-validation was-validated" novalidate="" id="address_form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                        <div class="mb-3">
                            <label for="calle" class="form-label">Calle:</label>
                            <input type="text" class="form-control" id="calle" name="calle" placeholder="Introduce la calle" value="" required="">
                            <div class="invalid-feedback">Es necesario poner el nombre de la calle.</div>
                        </div>
                        <div class="mb-3">
                            <label for="numero" class="form-label">Número:</label>
                            <input type="text" class="form-control" id="numero" name="numero" placeholder="Introduce el número" value="" required="">
                            <div class="invalid-feedback">Es necesario poner el número del piso/vivienda.</div>
                        </div>
                        <div class="mb-3">
                            <label for="portal" class="form-label">Portal:<span class="text-body-secondary">(Opcional si es una casa)</span></label>
                            <input type="text" class="form-control" id="portal" name="portal" placeholder="Introduce el portal">
                        </div>
                        <div class="mb-3">
                            <label for="planta" class="form-label">Planta:<span class="text-body-secondary">(Opcional si es una casa)</span></label>
                            <input type="text" class="form-control" id="planta" name="planta" placeholder="Introduce la planta">
                        </div>
                        <div class="mb-3">
                            <label for="puerta" class="form-label">Puerta:<span class="text-body-secondary">(Opcional si es una casa)</span></label>
                            <input type="text" class="form-control" id="puerta" name="puerta" placeholder="Introduce la puerta">
                        </div>
                        <div class="mb-3">
                            <label for="ciudad" class="form-label">Ciudad:</label>
                                <?php imprimirSelect($provincias); ?>
                            <div class="invalid-feedback">Es necesario el nombre de la ciudad.</div>
                        </div>
                        <div class="mb-3">
                            <label for="codigo_postal" class="form-label">Código Postal:</label>
                            <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" placeholder="Introduce el código postal" value="" required="">
                            <div class="invalid-feedback">Es necesario el código postal.</div>
                        </div>
                        <div id="form_message_address" class="text-center"></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" form="address_form" class="btn btn-primary">Aplicar</button>
                </div>
                <?php
                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['calle'])) {
                        
                        $calle = barrer($_POST['calle']);
                        $numero = barrer($_POST['numero']);
                        $portal = barrer($_POST['portal']); // opcional
                        $planta = barrer($_POST['planta']); // opcional
                        $puerta = barrer($_POST['puerta']); // opcional
                        $ciudad = isset($_POST['ciudad']) ? barrer($_POST['ciudad']) : '';  // Asegurarse de que está definido
                        $cp = barrer($_POST['codigo_postal']);

                        $errores = [];

                        if (empty($calle)) {
                            $errores[] = 'Error: No has introducido ninguna calle.';
                        }
                        if (empty($numero)) {
                            $errores[] = 'Error: No has introducido ningún número.';
                        }
                        if (empty($ciudad)) {
                            $errores[] = 'Error: No has seleccionado ninguna ciudad.';
                        }
                        if (empty($cp)) {
                            $errores[] = 'Error: No has introducido ningún código postal.';
                        }

                        if (!empty($errores)) {
                            echo "<script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    var modal = new bootstrap.Modal(document.getElementById('mod_address'));
                                    modal.show();
                                    document.getElementById('form_message_address').innerHTML = `<p style='color:red;'>".implode('<br>', $errores)."</p>`;
                                    // Update tab classes
                                    document.getElementById('nav-data-tab').className = 'nav-link';
                                    document.getElementById('nav-data-tab').setAttribute('aria-selected', 'false');
                                    document.getElementById('nav-data-tab').setAttribute('tabindex', '-1');

                                    document.getElementById('nav-data').className = 'tab-pane fade';

                                    document.getElementById('nav-address-tab').className = 'nav-link active';
                                    document.getElementById('nav-address-tab').setAttribute('aria-selected', 'true');
                                    document.getElementById('nav-address-tab').removeAttribute('tabindex');

                                    document.getElementById('nav-address').className = 'tab-pane fade active show';
                                });
                            </script>";
                        } else {
                            $id = $_SESSION['id'];
                            $direccion = "$calle $numero $portal $planta $puerta";

                            $conn = new Usuario();
                            $test = $conn->crearAddress($id, $direccion, $ciudad, $cp);
                            echo "<script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        var modal = new bootstrap.Modal(document.getElementById('mod_address'));
                                        modal.show();
                                        document.getElementById('form_message_address').innerHTML = `<p style='color:green;'>Dirección añadida con éxito</p>`;

                                        // Update tab classes
                                        document.getElementById('nav-data-tab').className = 'nav-link';
                                        document.getElementById('nav-data-tab').setAttribute('aria-selected', 'false');
                                        document.getElementById('nav-data-tab').setAttribute('tabindex', '-1');

                                        document.getElementById('nav-data').className = 'tab-pane fade';

                                        document.getElementById('nav-address-tab').className = 'nav-link active';
                                        document.getElementById('nav-address-tab').setAttribute('aria-selected', 'true');
                                        document.getElementById('nav-address-tab').removeAttribute('tabindex');

                                        document.getElementById('nav-address').className = 'tab-pane fade active show';
                                    });
                                </script>";
                        }
                    }
                ?>
            </div>
        </div>
    </div>
</div>