<?php
    require_once("../includes/Config.php");
    function barrer($data) {
        return trim(htmlspecialchars($data));
    }
    class Admin{
        protected $db;

        function __construct(){
            try{
                $this->db = new PDO(BBDD_DSN, BBDD_USER, BBDD_PASSWORD);
            } catch(PDOException $e){
                die("¡Error del php Admin!: ".$e->getMessage()." </br>");
            }
        }

        public function getConBD(){
            return $this->db;
        }

        public function __destruct(){
            $this->db = NULL;
        }

        public function validacion_admin($user,$pass){
            try{
                $datos = array(':par1'=>$user,':par2'=>$pass);
                $consulta = 'SELECT id_usuario, nombre_admin, admin 
                            FROM admins 
                            WHERE    email = :par1
                            AND  password = :par2
                            AND admin = 1';
                $stmt = $this->db->prepare($consulta);
                $stmt->execute($datos);
                $stmt->setFetchMode(PDO::FETCH_ASSOC);

                if ($stmt->rowCount() == 1){
                    $fila = $stmt->fetch();

                    $id = "{$fila["id_usuario"]}";
                    $nombre_admin = "{$fila["nombre_admin"]}";
                    $admin = "{$fila["admin"]}";

                    $datos = ["id"=>$id, "nombre_admin"=>$nombre_admin, "administrador"=> $admin];
                    return $datos;

                } else {
                    return false;
                }
            } catch (PDOException $e){
                die("¡Error al validad el usuario!: ".$e->getMessage()." </br>");
            }
        }

        public function get_data($id){
            try{
                $dato = array(':par1'=>$id);
                $consulta = 'SELECT nombre_admin 
                            FROM admins
                            WHERE id_usuario = :par1
                            AND admin = 1';
                $stmt = $this->db->prepare($consulta);
                $stmt->execute($dato);
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
            
                if ($stmt->rowCount() == 1){
                    $fila = $stmt->fetch();
                    return $fila; // Devolver el arreglo completo
                } else {
                    return false;
                }
            } catch(PDOException $e){
                die("¡Error al obtener datos!: ".$e->getMessage()." </br>");
            }
        }

        public function show_products() {
            try {
                $consulta = 'SELECT 
                                id_producto, nombre, 
                                descripcion, stock, 
                                precio, iva, imagen_ruta, 
                                tipo_promo, cantidad_vendida
                            FROM productos
                            ORDER BY id_producto';
                $stmt = $this->db->prepare($consulta);
                $stmt->execute();
                
                if ($stmt->rowCount() > 0) {
                    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $html = '<table class="table table-bordered table-striped">';
                    $html .= '<thead><tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Stock</th>
                                <th>Precio</th>
                                <th>IVA</th>
                                <th>Imagen</th>
                                <th>Tipo de Promoción</th>
                                <th>Cantidad Vendida</th>
                                <th>Editar</th>
                                <th>Eliminar</th>
                              </tr></thead><tbody>';
                    foreach ($productos as $producto) {
                        $html .= '<tr>
                                    <td>' . $producto['id_producto'] . '</td>
                                    <td>' . $producto['nombre'] . '</td>
                                    <td>' . $producto['descripcion'] . '</td>
                                    <td>' . $producto['stock'] . '</td>
                                    <td>' . $producto['precio'] . '</td>
                                    <td>' . $producto['iva'] . '</td>
                                    <td><img src="' . $producto['imagen_ruta'] . '" alt="Imagen del producto" style="max-width: 100px; max-height: 100px;"></td>
                                    <td>' . $producto['tipo_promo'] . '</td>
                                    <td>' . $producto['cantidad_vendida'] . '</td>
                                    <td> 
                                        <form action="editar_producto.php" method="post">
                                            <input type="hidden" name="id_producto_update" value="'.$producto['id_producto'].'"></input>
                                            <button type="submit" class="btn btn-sm btn-warning">Editar</button>
                                        </form>
                                    </td>
                                    <td> 
                                        <form action="eliminar_producto.php" method="post">
                                            <input type="hidden" name="id_producto_elim" value="'.$producto['id_producto'].'"></input>
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'¿Estás seguro de que quieres eliminar este usuario?\');">Eliminar</button>
                                        </form>
                                    </td>
                                  </tr>';
                    }
                    $html .= '</tbody></table>';
                    return $html;
                } else {
                    return '<p>No se encontraron productos.</p>';
                }
            } catch(PDOException $e) {
                die("¡Error del php usuario!: " . $e->getMessage() . " </br>");
            }
        }
        
        public function new_product($nombre, $descripcion, $stock, $precio, $iva, $imagen_ruta, $cantidad_vendida, $tipo_promo = null) {
            try {
                $datos = array(
                    ':nombre' => $nombre,
                    ':descripcion' => $descripcion,
                    ':stock' => $stock,
                    ':precio' => $precio,
                    ':iva' => $iva,
                    ':imagen_ruta' => $imagen_ruta,
                    ':tipo_promo' => $tipo_promo,
                    ':cantidad_vendida' => $cantidad_vendida
                );
                $consulta = 'INSERT INTO productos 
                                (nombre, descripcion, stock, precio, iva, imagen_ruta, tipo_promo, cantidad_vendida) 
                             VALUES 
                                (:nombre, :descripcion, :stock, :precio, :iva, :imagen_ruta, :tipo_promo, :cantidad_vendida)';
                $stmt = $this->db->prepare($consulta);
                return $stmt->execute($datos);
        
            } catch(PDOException $e) {
                echo "¡Error!: " . $e->getMessage() . "</br>";
                echo "¡Error al insertar el producto!</br>";
            }
        }

        public function show_promo() {
            try {
                $consulta = 'SELECT tipo_promo, percent_promo FROM promociones';
                $stmt = $this->db->prepare($consulta);
                $stmt->execute();
        
                if ($stmt->rowCount() > 0) {
                    $promociones = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $html = '<option value="">Ninguna</option>';
        
                    foreach ($promociones as $promocion) {
                        $html .= '<option value="' . htmlspecialchars($promocion['tipo_promo']) . '">Tipo: ' . 
                                    htmlspecialchars($promocion['tipo_promo']) . ' (' . htmlspecialchars($promocion['percent_promo']) . '%)' . 
                                 '</option>';
                    }
                    return $html;
                } else {
                    return '<p>No se encontraron tipos de promociones.</p>';
                }
        
            } catch(PDOException $e) {
                echo "¡Error!: " . $e->getMessage() . "</br>";
                echo "¡Error al obtener los tipos de promociones!</br>";
            }
        }
        
        public function get_data_product($id) {
            try {
                $datos = array(':id_producto' => $id);
                $consulta = 'SELECT 
                                nombre, descripcion, 
                                stock, precio, 
                                iva, imagen_ruta, 
                                tipo_promo, cantidad_vendida
                             FROM productos
                             WHERE id_producto = :id_producto';
                $stmt = $this->db->prepare($consulta);
                $stmt->execute($datos);
        
                if ($stmt->rowCount() > 0) {
                    $productos = $stmt->fetch(PDO::FETCH_ASSOC);
                    return $productos;
                } else {
                    return null;
                }
        
            } catch(PDOException $e) {
                echo "¡Error!: " . $e->getMessage() . "</br>";
                echo "¡Error al obtener los detalles del producto!</br>";
            }
        }

        public function edit_product($id_producto, $nombre, $descripcion, $stock, $precio, $iva, $imagen_ruta, $tipo_promo = null, $cantidad_vendida) {
            try {
                $datos = array(
                    ':id_producto' => $id_producto,
                    ':nombre' => $nombre,
                    ':descripcion' => $descripcion,
                    ':stock' => $stock,
                    ':precio' => $precio,
                    ':iva' => $iva,
                    ':imagen_ruta' => $imagen_ruta,
                    ':tipo_promo' => $tipo_promo,
                    ':cantidad_vendida' => $cantidad_vendida
                );
                $consulta = 'UPDATE productos SET 
                                nombre = :nombre, 
                                descripcion = :descripcion,
                                stock = :stock, 
                                precio = :precio, 
                                iva = :iva, 
                                imagen_ruta = :imagen_ruta, 
                                tipo_promo = :tipo_promo, 
                                cantidad_vendida = :cantidad_vendida
                             WHERE id_producto = :id_producto';
                $stmt = $this->db->prepare($consulta);
                return $stmt->execute($datos);
        
            } catch(PDOException $e) {
                echo "¡Error!: " . $e->getMessage() . "</br>";
                echo "¡Error al actualizar el producto!</br>";
            }
        }

        public function elim_product($id_producto) {
            try {
                $datos = array(':id_producto' => $id_producto);
                $consulta = 'DELETE FROM productos WHERE id_producto = :id_producto';
                $stmt = $this->db->prepare($consulta);
                return $stmt->execute($datos);
        
            } catch(PDOException $e) {
                echo "¡Error!: " . $e->getMessage() . "</br>";
                echo "¡Error al eliminar el producto!</br>";
            }
        }
    }


?>