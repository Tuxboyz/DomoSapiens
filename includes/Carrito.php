<?php

    require_once("Config.php");

    function validarTarjeta($num_tarjeta) {
        $num_tarjeta = preg_replace("/\D|\s/", "", $num_tarjeta);
        $length = strlen($num_tarjeta);
        
        if ($length < 16) {
            return false;
        } elseif ($length > 16) {
            return false;
        }
    
        $parity = $length % 2;
        $sum = 0;
        
        for ($i = 0; $i < $length; $i++) {
            $digit = $num_tarjeta[$i];
            if ($i % 2 == $parity) $digit = $digit * 2;
            if ($digit > 9) $digit = $digit - 9;
            $sum = $sum + $digit;
        }
        
        return ($sum % 10 == 0) ? true : false;
    }
    
    function getTipoTarjeta($cc) {
        $cc = str_replace(" ", "", $cc); // Quitar espacios
        
        // Validar longitud del número de tarjeta
        $length_error = validarTarjeta($cc);
        if ($length_error !== true) {
            return false;
        }
        
        $cards = array(
            'visa' => "(4\d{12}(?:\d{3})?)",
            'amex' => "(3[47]\d{13})",
            'jcb' => "(35[2-8]\d{12})",
            'maestro' => "((?:5020|5038|6304|6579|6761)\d{12}(?:\d{2})?)",
            'solo' => "((?:6334|6767)\d{12}(?:\d{2})?\d?)",
            'mastercard' => "(5[1-5]\d{14})",
            'switch' => "(?:(?:(?:4903|4905|4911|4936|6333|6759)\d{12})|(?:(?:564182|633110)\d{10})(\d{2})?\d?)"
        );
        
        $names = array('Visa', 'American Express', 'JCB', 'Maestro', 'Solo', 'Mastercard', 'Switch');
        
        $matches = array();
        
        $pattern = "#^(?:" . implode("|", $cards) . ")$#";
        
        $result = preg_match($pattern, $cc, $matches);
        
        if ($result > 0) {
            $result = (validarTarjeta($cc) === true) ? 1 : 0;
        }
        
        return ($result > 0) ? true : false;
    }

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

        public function mostrarCarrito($id_usuario) {
            try {
                $stmt = $this->db->prepare('SELECT p.id_producto, p.nombre, p.precio, p.iva, p.tipo_promo, c.cantidad
                                            FROM carrito c
                                            JOIN productos p ON c.id_producto = p.id_producto
                                            WHERE c.id_usuario = :id_usuario');
                $stmt->bindParam(':id_usuario', $id_usuario);
                $stmt->execute();
        
                $totalCarrito = 0;
                $costoEnvio = 10;
        
                if ($stmt->rowCount() > 0) {
                    echo '<div class="table-responsive">';
                    echo '<table class="table">';
                    echo '<thead>';
                    echo '<tr><th>Producto</th><th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cantidad&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th><th>Precio IVA/inc</th><th>Descuento</th><th>Precio Total</th><th></th></tr>';
                    echo '</thead>';
                    echo '<tbody>';
        
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $precio_unitario = $row['precio'] + ($row['precio'] * ($row['iva'] / 100));
                        $descuento = $row['tipo_promo'];
                        $precio_total_producto = $row['cantidad'] * ($precio_unitario - ($precio_unitario * ($descuento / 100)));
        
                        // Sumar al total del carrito sin formatear
                        $totalCarrito += $precio_total_producto;
        
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($row['nombre']) . '</td>';
                        echo '<td>';
                        echo '<div class="input-group">';
                        echo '<div class="input-group-prepend">';
                        echo '<button type="button" class="btn btn-warning" onclick="decrementQuantity(' . $row['id_producto'] . ')">-</button>';
                        echo '</div>';
                        echo '<input type="number" class="form-control text-center" name="cantidades[' . $row['id_producto'] . ']" value="' . htmlspecialchars($row['cantidad']) . '" min="1">';
                        echo '<div class="input-group-append">';
                        echo '<button type="button" class="btn btn-warning" onclick="incrementQuantity(' . $row['id_producto'] . ')">+</button>';
                        echo '</div>';
                        echo '</div>';
                        echo '</td>';
                        echo '<td id="pu' . $row['id_producto'] . '">' . number_format($precio_unitario, 2) . '€</td>';
                        echo '<td id="pd' . $row['id_producto'] . '">' . htmlspecialchars($descuento) . '%</td>';
                        echo '<td id="pt' . $row['id_producto'] . '">' . number_format($precio_total_producto, 2) . '€</td>';
                        echo '<td>';
                        echo '<a class="btn btn-sm btn-danger" href="partials/eliminar_producto.php?id_producto_elim='. $row['id_producto'] .'"><i class="bi bi-trash"></i></a>';
                        echo '</td>';
                        echo '</tr>';
                    }
        
                    $totalCarritoConEnvio = $totalCarrito + $costoEnvio;
        
                    echo '</tbody>';
                    echo '</table>';
                    echo '</div>'; // cierre de table-responsive
                    echo '<div class="p-5">';
                    echo '<p id="pe">Envío: ' . number_format($costoEnvio, 2) . '€</p>';
                    echo '<p id="pte">Total: ' . number_format($totalCarritoConEnvio, 2) . '€</p>';
                    echo '<button type="submit" form="comprar" class="btn btn-success">Comprar</button>';
                    echo '</div>';
                } else {
                    echo '<p class="fs-5">¿Qué quieres <a href="index.php">comprar</a>?</p>';
                }
        
            } catch (PDOException $e) {
                echo "¡Error!: " . $e->getMessage() . "<br/>";
                die();
            }
        }
        
        public function agregarAlCarrito($id_usu, $id_prod) {//funcion que se usa cuando se selecciona el boton de añadir al carrito o comprar
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

        public function elim_prod_carrito($id_usu, $id_prod){//funcion que elimina un producto del carrito
            try {
                $datos = array(':par1' => $id_usu, ':par2' => $id_prod);
                $borrado = ('DELETE FROM carrito WHERE id_usuario = :par1 AND id_producto = :par2');
                $stmt = $this->db->prepare($borrado);
                return $stmt->execute($datos);

            } catch (PDOException $e) {
                echo "¡Error!: " . $e->getMessage() . "<br/>";
                die();
            }
        }

        public function update_cart($cantidades, $id_usuario) {
            try {
                $this->db->beginTransaction();
                
                foreach ($cantidades as $id_producto => $cantidad) {
                    // Verificar que la cantidad sea un número positivo
                    if (is_numeric($cantidad) && $cantidad > 0) {
                        $datos = array(
                            ':id_usuario' => $id_usuario,
                            ':id_producto' => $id_producto,
                            ':cantidad' => $cantidad
                        );
        
                        // Actualizar la cantidad del producto en el carrito
                        $stmt = $this->db->prepare('UPDATE carrito SET cantidad = :cantidad WHERE id_usuario = :id_usuario AND id_producto = :id_producto');
                        $stmt->execute($datos);
                    }
                }
        
                $this->db->commit();
                
            } catch (PDOException $e) {
                $this->db->rollBack();
                echo "¡Error!: " . $e->getMessage() . "<br/>";
                die();
            }
        }

        public function buy_items($id_usuario){
            try {
                $stmt = $this->db->prepare('SELECT p.id_producto, p.nombre, p.precio, p.iva, p.tipo_promo, c.cantidad
                                            FROM carrito c
                                            JOIN productos p ON c.id_producto = p.id_producto
                                            WHERE c.id_usuario = :id_usuario');
                $stmt->bindParam(':id_usuario', $id_usuario);
                $stmt->execute();
        
                $totalCarrito = 0;
                $costoEnvio = 10;
        
                if ($stmt->rowCount() > 0) {
        
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $precio_unitario = $row['precio'] + ($row['precio'] * ($row['iva'] / 100));
                        $precio_total_producto = $row['cantidad'] * ($precio_unitario - ($precio_unitario * ($row['tipo_promo'] / 100)));
        
                        // Sumar al total del carrito sin formatear
                        $totalCarrito += $precio_total_producto;
        
                        echo '<li class="list-group-item d-flex justify-content-between lh-sm">';
                            echo '<div>';
                                echo '<h6 class="my-0">'.$row['nombre'].'</h6>';
                                echo '<small class="text-body-secondary">Cantidad: '.$row['cantidad'].'</small>';
                            echo '</div>';
                            echo '<span class="text-body-secondary">'.number_format($precio_total_producto,2).'€</span>';
                        echo '</li>';
                    }
        
                    $totalCarritoConEnvio = $totalCarrito + $costoEnvio;
        
                    echo '<li class="list-group-item d-flex justify-content-between"><span>Envio: </span><strong>'.number_format($costoEnvio, 2).'€</strong></li>';
                    echo '<li class="list-group-item d-flex justify-content-between"><span>Total: </span><strong>'.number_format($totalCarritoConEnvio, 2).'€</strong></li>';
        
                } else {
                    echo 'Esto no deveria mostrarse!!!';
                }
        
            } catch (PDOException $e) {
                echo "¡Error!: " . $e->getMessage() . "<br/>";
                die();
            }
        }

        public function item_count($id_usuario){
            try {
                $stmt = $this->db->prepare('SELECT COUNT(DISTINCT id_producto) as total_types FROM carrito WHERE id_usuario = :id_usuario');
                $stmt->bindParam(':id_usuario', $id_usuario);
                $stmt->execute();
                
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($row) {
                    return $row['total_types'];
                } else {
                    return false; // Si no hay productos en el carrito, devolver 0
                }
            } catch (PDOException $e) {
                echo "¡Error!: " . $e->getMessage() . "<br/>";
                die();
            }
        }

        public function obtener_resumen_compra($id_usuario) {
            try {
                $stmt = $this->db->prepare('SELECT p.id_producto, p.nombre, p.precio, p.iva, p.tipo_promo, c.cantidad
                                            FROM carrito c
                                            JOIN productos p ON c.id_producto = p.id_producto
                                            WHERE c.id_usuario = :id_usuario');
                $stmt->bindParam(':id_usuario', $id_usuario);
                $stmt->execute();
        
                $totalCarrito = 0;
                $costoEnvio = 10;
                $productos = [];
        
                if ($stmt->rowCount() > 0) {
        
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $precio_unitario = $row['precio'] + ($row['precio'] * ($row['iva'] / 100));
                        $precio_total_producto = $row['cantidad'] * ($precio_unitario - ($precio_unitario * ($row['tipo_promo'] / 100)));
        
                        // Sumar al total del carrito sin formatear
                        $totalCarrito += $precio_total_producto;
        
                        // Agregar al arreglo de productos
                        $productos[] = [
                            "producto_id" => $row['id_producto'],
                            "producto_nombre" => $row['nombre'],
                            "precio_unitario" => round($precio_unitario, 2),
                            "descuento" => $row['tipo_promo'],
                            "cantidad" => $row['cantidad'],
                            "precio_total" => round($precio_total_producto, 2)
                        ];
                    }
        
                    $totalCarritoConEnvio = $totalCarrito + $costoEnvio;
        
                    // Preparar la salida JSON
                    $result = [
                        "productos" => $productos,
                        "precio_envio" => round($costoEnvio, 2),
                        "precio_total" => round($totalCarritoConEnvio, 2)
                    ];
        
                    return $result;
        
                } else {
                    return false;
                }
        
            } catch (PDOException $e) {
                echo "¡Error!: " . $e->getMessage() . "<br/>";
                die();
            }
        }

        public function pago($id_usuario,$id_direccion,$metodo_pago,$productos,$precio_envio,$precio_total){
            try {
                $datos = array( ':par1' => $id_usuario,/*lo agarro por sesion*/
                                ':par2' => $id_direccion,/*viene del form*/
                                ':par3' => $metodo_pago,/*viene del form*/
                                ':par4' => $productos,/*es un JSON*/
                                ':par5' => $precio_envio,/*nose de donde sacarlo*/
                                ':par6' => $precio_total);/*no se de donde sacarlo*/
                $update = ('INSERT INTO tickets (id_usuario, id_direccion, metodo_pago, productos, precio_envio, precio_total)
                            VALUES(:par1, :par2, :par3, :par4, :par5, :par6 )');

                $stmt = $this->db->prepare($update);
                return $stmt->execute($datos);

            } catch (PDOException $e) {
                echo "¡Error!: " . $e->getMessage() . "<br/>";
                die();
            }
        }

        public function pago_post_borrado($id_usuario){
            try {
                $datos = array(':par1' => $id_usuario);
                $update = ('DELETE FROM carrito 
                            WHERE id_usuario = :par1');

                $stmt = $this->db->prepare($update);
                return $stmt->execute($datos);

            } catch (PDOException $e) {
                echo "¡Error!: " . $e->getMessage() . "<br/>";
                die();
            }
        }
          
    }

?>