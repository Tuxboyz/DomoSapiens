<?php
if(!isset($_SESSION['nombre_admin'])){
    header("Location: login.php");
    exit;
}
    $conn = new Admin();
    $promos = $conn->show_promo();
?>
<form class="needs-validation was-validated" novalidate="" id="address_form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre:</label>
        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Introduce el nombre del producto." value="" required="">
        <div class="invalid-feedback">Es necesario poner el nombre del producto (evitar caracteres raros).</div>
    </div>
    <div class="mb-3">
        <label for="descripcion" class="form-label">Descripcion:</label>
        <input type="text" class="form-control" id="descripcion" name="descripcion" placeholder="Introduce la descripcion del producto." value="" required="">
        <div class="invalid-feedback">Es necesaria la descripcion del producto (evitar caracteres raros).</div>
    </div>
    <div class="mb-3">
        <label for="stock" class="form-label">Stock:</label>
        <input type="number" class="form-control" id="stock" name="stock" min="1" max="9999" placeholder="Introduce la cantidad de stock disponible." value="" required="">
        <div class="invalid-feedback">Es necesario poner la cantidad de stock (usar solo valores numericos).</div>
    </div>
    <div class="mb-3">
        <label for="precio" class="form-label">Precio:</label>
        <input type="number" class="form-control" id="precio" name="precio" min="1" max="9999" placeholder="Introduce un precio para el poducto." value="" required="">
        <div class="invalid-feedback">Es necesario poner un precio para el poducto (usar solo valores numericos).</div>
    </div>
    <div class="mb-3">
        <label for="iva" class="form-label">IVA:</label>
        <input type="number" class="form-control" id="iva" name="iva" min="1" max="100" placeholder="Introduce el IVA que va a tener el producto" value="" required="">
        <div class="invalid-feedback">Es necesario poner el IVA que va a tener el producto (usar solo valores numericos).</div>
    </div>
    <div class="mb-3">
        <label for="ruta" class="form-label">Ruta de la/s imagen/es:</label>
        <input type="ruta" class="form-control" id="ruta" name="ruta" placeholder="Introduce la ruta que va a tener el producto" value="" required="">
        <div class="invalid-feedback">Es necesario el nombre de la Ruta donde se ubican las imagenes del producto(escribela bien sino, no se mostraran).</div>
    </div>
    <div class="mb-3">
        <label for="promo" class="form-label">Tipo de Promocion:</label>
        <select class="form-control" id="promo" name="promo" placeholder="Selecciona la nueva promocion (es opcional)">
            <?php echo $promos;?>
        </select>
        <div class="invalid-feedback">Es opcional.</div>
    </div>
    <div class="mb-3">
        <label for="vendidos" class="form-label">Cantidad vendida:</label>
        <input type="number" class="form-control" id="vendidos" name="vendidos" min="1" max="999999" placeholder="introduce la cantidad de productos vendidos" value="" required="">
        <div class="invalid-feedback">Es necesaria la cantidad de productos vendidos(usar solo valores numericos).</div>
    </div>
    <div id="form_message_addprod" class="text-center"></div>
    <button type="submit" form="address_form" class="btn btn-primary">Aplicar</button>
</form>
<?php

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nombre'])) {

    $nombre = barrer($_POST['nombre']);
    $descripcion = barrer($_POST['descripcion']);
    $stock = barrer($_POST['stock']);
    $precio = barrer($_POST['precio']);
    $iva = barrer($_POST['iva']);
    $ruta = barrer($_POST['ruta']);
    $tipo_promo = barrer($_POST['promo']);
    $vendidos = barrer($_POST['vendidos']);


    if(empty($nombre)){
        $errores[] = 'No has introducido el nombre del producto.';
    }
    if(empty($descripcion)){
        $errores[] = 'No has añadido una descripcion del producto.';
    }
    if(empty($stock)){
        $errores[] = 'No has añadido una stock para producto.';
    }
    if(empty($precio)){
        $errores[] = 'No has seleccionado un precio para el producto.';
    }
    if(empty($iva)){
        $errores[] = 'No has seleccionado una cantidad de IVA para el producto.';
    }
    if(empty($ruta)){
        $errores[] = 'No has añadido una ruta para las imagenes';
    }
    if(empty($vendidos)){
        $errores[] = 'No has puesto una cantidad de productos vendidos.';
    }
    if (!empty($errores)) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {

                document.getElementById('form_message_addprod').innerHTML = `<p style='color:red;'>".implode('<br>', $errores)."</p>`;
                // Update tab classes
                document.getElementById('nav-products-tab').className = 'nav-link';
                document.getElementById('nav-products-tab').setAttribute('aria-selected', 'false');
                document.getElementById('nav-products-tab').setAttribute('tabindex', '-1');

                document.getElementById('nav-products').className = 'tab-pane fade';

                document.getElementById('nav-create-tab').className = 'nav-link active';
                document.getElementById('nav-create-tab').setAttribute('aria-selected', 'true');
                document.getElementById('nav-create-tab').removeAttribute('tabindex');

                document.getElementById('nav-create').className = 'tab-pane fade active show';
            });
        </script>";
    } else {

        $conn = new Admin();
        $test = $conn->new_product($nombre, $descripcion, $stock ,$precio, $iva, $ruta, $vendidos, $tipo_promo);
        
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {

                    document.getElementById('form_message_addprod').innerHTML = `<p style='color:green;'>Producto añadido con éxito</p>`;

                    // Update tab classes
                    document.getElementById('nav-products-tab').className = 'nav-link';
                    document.getElementById('nav-products-tab').setAttribute('aria-selected', 'false');
                    document.getElementById('nav-products-tab').setAttribute('tabindex', '-1');

                    document.getElementById('nav-products').className = 'tab-pane fade';

                    document.getElementById('nav-create-tab').className = 'nav-link active';
                    document.getElementById('nav-create-tab').setAttribute('aria-selected', 'true');
                    document.getElementById('nav-create-tab').removeAttribute('tabindex');

                    document.getElementById('nav-create').className = 'tab-pane fade active show';
                });
            </script>";
    }

}

?>