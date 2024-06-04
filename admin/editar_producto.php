<?php
session_start();
    if(!isset($_SESSION['nombre_admin'])){
        header("Location: index.php");
        exit;
    }
    include_once 'Admin.php';
    $conn = new Admin();
    $errores = [];
    $mensaje = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_producto_update'])) {

        $id = barrer($_POST['id_producto_update']);
        
        if(empty($id)){
            $errores[] = 'Ha ocurrido un error al obtener el ID.';
        }

        if (empty($errores)) {
            $dato = $conn->get_data_product($id);

            if (isset($_POST['nombre'], $_POST['descripcion'], $_POST['stock'], $_POST['precio'], $_POST['iva'], $_POST['ruta'], $_POST['promo'], $_POST['vendidos'])) {
                $nombre = barrer($_POST['nombre']);
                $descripcion = barrer($_POST['descripcion']);
                $stock = barrer($_POST['stock']);
                $precio = barrer($_POST['precio']);
                $iva = barrer($_POST['iva']);
                $ruta1 = barrer($_POST['ruta1']);//nueva ruta
                $ruta = barrer($_POST['ruta']);
                $promos = barrer($_POST['promo']);
                $vendidos = barrer($_POST['vendidos']);

                if(empty($nombre)){
                    $errores[] = 'No has introducido el nombre del producto.';
                }
                if(empty($descripcion)){
                    $errores[] = 'No has añadido una descripcion del producto.';
                }
                if(empty($precio)){
                    $errores[] = 'No has seleccionado un precio para el producto.';
                }
                if(empty($iva)){
                    $errores[] = 'No has seleccionado una cantidad de IVA para el producto.';
                }
                if(empty($ruta1)){
                    $errores[] = 'No has añadido una ruta para la imagen principal.';
                }
                if(empty($ruta)){
                    $errores[] = 'No has añadido una ruta para las imagenes';
                }
                if(empty($vendidos)){
                    $errores[] = 'No has puesto una cantidad de productos vendidos.';
                }

                if (empty($errores)){
                    $update = $conn->edit_product($id, $nombre, $descripcion, $stock, $precio, $iva, $ruta1, $ruta, $promos, $vendidos);
                    if ($update) {
                        $mensaje = 'Se ha actualizado con exito.';
                    } else {
                        $errores[] = 'Error al actualizar el producto.';
                    }
                }
            } else {
                $errores[] = 'Tienes que rellenar todos los campos';
            }
        }

    }

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
        <link rel="stylesheet" href="../styles/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="../styles/styles.css">
        <style>
        </style>

        <title>Bienvenido!</title>
    </head>
    <body>
        <main>
            <div class="container mt-5">
                <form class="needs-validation was-validated" novalidate="" id="edit_form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <input type="hidden" name="id_producto_update" value="<?php echo htmlspecialchars($_POST['id_producto_update']); ?>">
                    
                    <?php if (!empty($errores)): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php foreach($errores as $error): ?>
                                    <li><?php echo htmlspecialchars($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php elseif ($mensaje): ?>
                        <div class="alert alert-success">
                            <?php echo htmlspecialchars($mensaje); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Introduce el nuevo nombre del producto." value="<?php echo htmlspecialchars($dato['nombre'] ?? ''); ?>" required="">
                        <div class="invalid-feedback">Es necesario poner el nombre del producto (evitar caracteres raros).</div>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripcion:</label>
                        <input type="text" class="form-control" id="descripcion" name="descripcion" placeholder="Introduce la nueva descripcion del producto." value="<?php echo htmlspecialchars($dato['descripcion'] ?? ''); ?>" required="">
                        <div class="invalid-feedback">Es necesaria la descripcion del producto (evitar caracteres raros).</div>
                    </div>
                    <div class="mb-3">
                        <label for="stock" class="form-label">Stock:</label>
                        <input type="number" class="form-control" id="stock" name="stock" min="0" max="9999" placeholder="Introduce la nueva cantidad de stock disponible." value="<?php echo htmlspecialchars($dato['stock'] ?? ''); ?>" required="">
                        <div class="invalid-feedback">Es necesario poner la cantidad de stock (usar solo valores numericos).</div>
                    </div>
                    <div class="mb-3">
                        <label for="precio" class="form-label">Precio:</label>
                        <input type="number" class="form-control" id="precio" name="precio" min="1" max="9999" placeholder="Introduce un nuevo precio para el poducto." value="<?php echo htmlspecialchars($dato['precio'] ?? ''); ?>" required="">
                        <div class="invalid-feedback">Es necesario poner un precio para el poducto (usar solo valores numericos).</div>
                    </div>
                    <div class="mb-3">
                        <label for="iva" class="form-label">IVA:</label>
                        <input type="number" class="form-control" id="iva" name="iva" min="0" max="100" placeholder="Introduce el nuevo IVA que va a tener el producto" value="<?php echo htmlspecialchars($dato['iva'] ?? ''); ?>" required="">
                        <div class="invalid-feedback">Es necesario poner el IVA que va a tener el producto (usar solo valores numericos).</div>
                    </div>
                    <div class="mb-3">
                        <label for="ruta1" class="form-label">Ruta de la imagen principal:</label>
                        <input type="text" class="form-control" id="ruta1" name="ruta1" placeholder="Introduce la nueva ruta que va a tener el producto" value="<?php echo htmlspecialchars($dato['ruta'] ?? ''); ?>" required="">
                        <div class="invalid-feedback">Es necesario el nombre de la Ruta de la imagen principal(escribela bien sino, no se mostraran).</div>
                    </div>
                    <div class="mb-3">
                        <label for="ruta" class="form-label">Ruta de la/s imagen/es:</label>
                        <input type="text" class="form-control" id="ruta" name="ruta" placeholder="Introduce la nueva ruta que va a tener el producto" value="<?php echo htmlspecialchars($dato['imagen_ruta'] ?? ''); ?>" required="">
                        <div class="invalid-feedback">Es necesario el nombre de la Ruta donde se ubican las imagenes del producto(escribela bien sino, no se mostraran).</div>
                    </div>
                    <div class="mb-3">
                        <label for="promo" class="form-label">Tipo de Promocion:</label>
                        <select class="form-control" id="promo" name="promo" placeholder="Selecciona la nueva promocion (es opcional)">
                            <option value="" selected>Selecciona una promoción (opcional)</option>
                            <option value="5">5% Descuento</option>
                            <option value="10">10% Descuento</option>
                            <option value="15">15% Descuento</option>
                            <option value="20">20% Descuento</option>
                        </select>
                        <div class="invalid-feedback">Es opcional.</div>
                    </div>
                    <div class="mb-3">
                        <label for="vendidos" class="form-label">Cantidad vendida:</label>
                        <input type="number" class="form-control" id="vendidos" name="vendidos" min="0" max="999999" placeholder="Introduce la nueva cantidad de productos vendidos" value="<?php echo htmlspecialchars($dato['cantidad_vendida'] ?? ''); ?>" required="">
                        <div class="invalid-feedback">Es necesaria la cantidad de productos vendidos(usar solo valores numericos).</div>
                    </div>

                    <div id="form_message_addprod" class="text-center"></div>
                    <button type="submit" form="edit_form" class="btn btn-primary">Aplicar</button>
                    <a href="panel.php" class="btn btn-secondary">Salir</a>
                </form>
            </div>
        </main>

        <script src="../scripts/bootstrap.bundle.min.js"></script>
    </body>
</html>
