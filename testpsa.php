<?php


//-----------------------SIN LA CANTIDAD-----------------------------
function agregarAlCarrito($id_usuario, $id_producto) {
    try {
        // Conexión a la base de datos
        $dbh = new PDO("mysql:host=db5015831002.hosting-data.io; dbname=dbs12907202;", 'dbu5123221', '26303114Mm');
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Comprobar si el producto ya está en el carrito
        $stmt = $dbh->prepare("SELECT cantidad FROM carrito WHERE id_usuario = :id_usuario AND id_producto = :id_producto");
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->bindParam(':id_producto', $id_producto);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Si el producto ya está en el carrito, incrementar la cantidad en 1
            $stmt = $dbh->prepare("UPDATE carrito SET cantidad = cantidad + 1 WHERE id_usuario = :id_usuario AND id_producto = :id_producto");
        } else {
            // Si el producto no está en el carrito, añadirlo con cantidad 1
            $stmt = $dbh->prepare("INSERT INTO carrito (id_usuario, id_producto, cantidad) VALUES (:id_usuario, :id_producto, 1)");
        }

        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->bindParam(':id_producto', $id_producto);
        $stmt->execute();

    } catch (PDOException $e) {
        echo "¡Error!: " . $e->getMessage() . "<br/>";
        die();
    }
}


//Función para mostrar el carrito y modificar cantidades
function mostrarCarrito($id_usuario) {
    try {
        // Conexión a la base de datos
        $dbh = new PDO("mysql:host=db5015831002.hosting-data.io; dbname=dbs12907202;", 'dbu5123221', '26303114Mm');
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Obtener los productos del carrito para el usuario
        $stmt = $dbh->prepare("SELECT p.id, p.nombre, p.precio, p.iva, p.tipo_promo, c.cantidad
                               FROM carrito c
                               JOIN productos p ON c.id_producto = p.id
                               WHERE c.id_usuario = :id_usuario");
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo '<form action="actualizar_carrito.php" method="post">';
            echo '<table>';
            echo '<tr><th>Producto</th><th>Cantidad</th><th>Precio</th><th>IVA</th><th>Descuento</th><th>Precio Total</th></tr>';
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $precio_unitario = $row['precio'];
                $iva = $precio_unitario * ($row['iva'] / 100);
                $descuento = $precio_unitario * ($row['tipo_promo'] / 100);
                $precio_total = $row['cantidad'] * ($precio_unitario + $iva - $descuento);
                echo '<tr>';
                echo '<td>' . $row['nombre'] . '</td>';
                echo '<td><input type="number" name="cantidades[' . $row['id'] . ']" value="' . $row['cantidad'] . '" min="1"></td>';
                echo '<td>' . $precio_unitario . '€</td>';
                echo '<td>' . $iva . '€</td>';
                echo '<td>' . $descuento . '%</td>';
                echo '<td>' . $precio_total . '€</td>';
                echo '</tr>';
            }
            echo '</table>';
            echo '<button type="submit">Actualizar carrito</button>';
            echo '</form>';
        } else {
            echo "El carrito está vacío.";
        }

    } catch (PDOException $e) {
        echo "¡Error!: " . $e->getMessage() . "<br/>";
        die();
    }
}


//Función para actualizar el carrito
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cantidades'])) {
    try {
        // Conexión a la base de datos
        $dbh = new PDO("mysql:host=db5015831002.hosting-data.io; dbname=dbs12907202;", 'dbu5123221', '26303114Mm');
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        foreach ($_POST['cantidades'] as $id_producto => $cantidad) {
            $stmt = $dbh->prepare("UPDATE carrito SET cantidad = :cantidad WHERE id_usuario = :id_usuario AND id_producto = :id_producto");
            $stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
            $stmt->bindParam(':id_usuario', $_SESSION['id_usuario'], PDO::PARAM_INT);
            $stmt->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
            $stmt->execute();
        }

        header('Location: carrito.php'); // Redirigir de nuevo a la página del carrito
        exit();

    } catch (PDOException $e) {
        echo "¡Error!: " . $e->getMessage() . "<br/>";
        die();
    }
} else {
    echo "No se recibieron datos para actualizar.";
}






//Agregar productos al carrito:

agregarAlCarrito($_SESSION['id_usuario'], $id_producto);


//Mostrar el carrito:
mostrarCarrito($_SESSION['id_usuario']);

?>
