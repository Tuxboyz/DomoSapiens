<?php
session_start();

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

function agregarAlCarrito($id_producto, $cantidad) {
    // Supongamos que esta función obtiene detalles del producto desde la base de datos
    $producto = obtenerProductoPorId($id_producto);

    // Estructura del producto en el carrito
    $item_carrito = [
        'id_producto' => $id_producto,
        'nombre' => $producto['nombre'],
        'precio' => $producto['precio'],
        'iva' => $producto['iva'],
        'tipo_promo' => $producto['tipo_promo'],
        'cantidad' => $cantidad
    ];

    // Si el producto ya está en el carrito, actualizar la cantidad
    if (isset($_SESSION['carrito'][$id_producto])) {
        $_SESSION['carrito'][$id_producto]['cantidad'] += $cantidad;
    } else {
        // Si el producto no está en el carrito, añadirlo
        $_SESSION['carrito'][$id_producto] = $item_carrito;
    }
}

function mostrarCarrito() {
    if (empty($_SESSION['carrito'])) {
        echo "El carrito está vacío.";
    } else {
        foreach ($_SESSION['carrito'] as $item) {
            echo "Producto: " . $item['nombre'] . "<br>";
            echo "Cantidad: " . $item['cantidad'] . "<br>";
            echo "Precio: " . $item['precio'] . "€<br>";
            echo "IVA: " . $item['iva'] . "€<br>";
            echo "Descuento: " . $item['tipo_promo'] . "%<br>";
            $precio_total = $item['cantidad'] * ($item['precio'] + ($item['precio'] * $item['iva'] / 100) - ($item['precio'] * $item['tipo_promo'] / 100));
            echo "Precio total: " . $precio_total . "€<br><br>";
        }
    }
}

// Ejemplo de función que simula la obtención de un producto desde la base de datos
function obtenerProductoPorId($id_producto) {
    // Esta es una simulación. En un caso real, harías una consulta a la base de datos.
    $productos = [
        1 => ['nombre' => 'Producto 1', 'precio' => 10.00, 'iva' => 21, 'tipo_promo' => 0],
        2 => ['nombre' => 'Producto 2', 'precio' => 20.00, 'iva' => 21, 'tipo_promo' => 10],
        // Añadir más productos según sea necesario
    ];

    return $productos[$id_producto];
}

// Ejemplo de uso:
agregarAlCarrito(1, 2); // Agrega 2 unidades del producto con id 1
agregarAlCarrito(2, 1); // Agrega 1 unidad del producto con id 2
mostrarCarrito(); // Muestra los productos en el carrito
