<?php

    require_once("Config.php");

    class Carrito{
        protected $db;

        function __construct(){
            try{
                $this->db = new PDO(DNS, USER, PASS);
            } catch(PDOException $e){
                die("¡Error  del php Carrito!: ".$e->getMessage()." </br>");
            }
        }

        public function getConBD(){
            return $this->db;
        }

        public function __destruct(){
            $this->db = NULL;
        }

        //Función para mostrar el carrito y modificar cantidades
        public function mostrarCarrito($id_usuario) {
            try {
                // Obtener los productos del carrito para el usuario
                $stmt = $this->db->prepare('SELECT p.id_producto, p.nombre, p.precio, p.iva, p.tipo_promo, c.cantidad
                                            FROM carrito c
                                            JOIN productos p ON c.id_producto = p.id_producto
                                            WHERE c.id_usuario = :id_usuario');
                $stmt->bindParam(':id_usuario', $id_usuario);
                $stmt->execute();
        
                if ($stmt->rowCount() > 0) {
                    echo '<form action="cart.php" method="post">';
                    echo '<table>';
                    echo '<tr><th>Producto</th><th>Cantidad</th><th>Precio IVA/inc</th><th>Descuento</th><th>Precio Total</th></tr>';
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                        $precio_unitario = $row['precio'] + ($row['precio']* ($row['iva'] / 100));
                        $descuento = $row['tipo_promo'];
                        $precio_total = $row['cantidad'] * ($precio_unitario - ($precio_unitario * ($descuento / 100)));

                        echo '<tr>';
                        echo '<td>' . $row['nombre'] . '</td>';
                        echo '<td><input type="number" name="cantidades[' . $row['id_producto'] . ']" value="' . $row['cantidad'] . '" min="1"></td>';
                        echo '<td>' . $precio_unitario . '€</td>';
                        echo '<td>' . $descuento . '%</td>';
                        echo '<td>' . $precio_total . '€</td>';
                        echo '</tr>';
                    }
                    echo '</table>';
                    echo '<button type="submit">Actualizar carrito</button>';
                    echo '</form>';

                } else {
                    echo '<p class="fs-5">¿Que quieres <a href="index.php">comprar</a>?</p>';
                }
        
            } catch (PDOException $e) {
                echo "¡Error!: " . $e->getMessage() . "<br/>";
                die();
            }
        }

        public function agregarAlCarrito($id_usu, $id_prod) {
            try {
                $datos = array(':par1' => $id_usu, ':par2' => $id_prod);
                $stmt = $this->db->prepare('SELECT cantidad FROM carrito WHERE id_usuario = :par1 AND id_producto = :par2');
                $stmt->execute($datos);
        
                if ($stmt->rowCount() > 0) {
                    // Si el producto ya está en el carrito, incrementar la cantidad en 1
                    $stmt = $this->db->prepare('UPDATE carrito SET cantidad = cantidad + 1 WHERE id_usuario = :par1 AND id_producto = :par2');
                } else {
                    // Si el producto no está en el carrito, añadirlo con cantidad 1
                    $stmt = $this->db->prepare('INSERT INTO carrito (id_usuario, id_producto, cantidad) VALUES (:par1, :par2, 1)');
                }
        
                // Volver a enlazar los parámetros
                $stmt->bindParam(':par1', $id_usu);
                $stmt->bindParam(':par2', $id_prod);
                $stmt->execute();
        
            } catch (PDOException $e) {
                echo "¡Error!: " . $e->getMessage() . "<br/>";
                die();
            }
        }

    }

?>