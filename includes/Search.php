<?php

    require_once("Config.php");
    
    class Search{
        protected $db;

        function __construct(){
            try{
                $this->db = new PDO(DNS, USER, PASS);
            } catch(PDOException $e){
                die("¡Error del php usuario!: ".$e->getMessage()." </br>");
            }
        }

        public function getConBD(){
            return $this->db;
        }

        public function __destruct(){
            $this->db = NULL;
        }
        
        public function show_categories(){
            try{
                $consulta = 'SELECT id_categoria, nombre FROM categoria';
                $stmt = $this->db->prepare($consulta);
                $stmt->execute();
        
                if ($stmt->rowCount() > 0) {

                    $categorias_html = '<ul class="navbar-nav me-auto mb-2">';
        

                    while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $categorias_html .= '<li class="nav-item">';
                        $categorias_html .= '<a class="nav-link active" href="result.php?categoria=' . $fila["id_categoria"] . '">' . $fila["nombre"] . '</a>';
                        $categorias_html .= '</li>';
                    }
        

                    $categorias_html .= '</ul>';
        

                    return $categorias_html;
                } else {

                    return '<p>No se encontraron categorías.</p>';
                }
            } catch(PDOExceptions $e){
                echo "¡Error!: ".$e->getMessage()."</br>";
                echo "¡Error al mostrar categorías!</br>";
            }
        }

        public function categories_searcher($id_categoria) {
            try {
                // Validar que el ID de la categoría es un número
                if (!is_numeric($id_categoria)) {
                    throw new Exception("El ID de la categoría debe ser un número.");
                }
        
                // Preparar y ejecutar la consulta
                $query = "
                    SELECT p.id_producto
                    FROM productos p
                    JOIN productos_categorias pc ON p.id_producto = pc.id_producto
                    WHERE pc.id_categoria = :id_categoria
                ";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':id_categoria', $id_categoria, PDO::PARAM_INT);
                $stmt->execute();
        
                $results = array();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $results[] = $row['id_producto'];
                }
        
                return array_unique($results);
        
            } catch (PDOException $e) {
                echo "¡Error!: " . $e->getMessage() . "</br>";
                echo "¡Error al realizar la búsqueda!</br>";
            } catch (Exception $e) {
                echo "¡Error!: " . $e->getMessage() . "</br>";
            }
        }

        public function searcher($keyword) {

            try{
                $keywords = explode(" ", $keyword);
                $results = array();
                foreach ($keywords as $key) {
                    $query = "SELECT id_producto FROM productos WHERE nombre LIKE :keyword OR descripcion LIKE :keyword";
                    $stmt = $this->db->prepare($query);
                    $keyParam = '%' . $key . '%';
                    $stmt->bindParam(':keyword', $keyParam, PDO::PARAM_STR);
                    $stmt->execute();
    
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $results[] = $row['id_producto'];
                    }
                }
                return array_unique($results);

            } catch(PDOExceptions $e){
                echo "¡Error!: ".$e->getMessage()."</br>";
                echo "¡Error al realizar busqueda!</br>";
            }

        }

        public function show_mini_prod($id) {
            try {
                $query = 'SELECT nombre, precio, iva, ruta, tipo_promo
                          FROM productos 
                          WHERE id_producto = :id';
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                $product_info = $stmt->fetch(PDO::FETCH_ASSOC);
        
                if ($product_info) {
                    $precio_con_iva = $product_info['precio'] + ($product_info['precio'] * $product_info['iva'] / 100);
                    $producto_con_descuento = $precio_con_iva - ($precio_con_iva * ($product_info['tipo_promo']/100));
                    $texto = '<div>
                                <img src="' . $product_info['ruta'] . '" alt="Imagen del producto" class="imagen">
                              </div>
                              <div>
                                <div><b>' . $product_info['nombre'] . '</b></div>
                                <div>' . number_format($producto_con_descuento, 2) . '€</div>
                              </div>';
                } else {
                    $texto = "Ha ocurrido un error.";
                }
                return $texto;
            } catch (PDOException $e) {
                echo "¡Error!: " . $e->getMessage() . "</br>";
                echo "¡Error al mostrar producto!</br>";
            }
        }
        
        public function product_inf($id) {
            try {
                $query = 'SELECT nombre, descripcion, stock, 
                                 precio, iva, 
                                 tipo_promo, cantidad_vendida 
                          FROM productos 
                          WHERE id_producto = :id';
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return $result;
                
            } catch (PDOException $e) {
                echo "¡Error!: " . $e->getMessage() . "</br>";
                echo "¡Error al mostrar producto!</br>";
            }
        }

        public function show_product_photos($id) {
            try {
                // Obtener la ruta de la imagen del producto desde la base de datos
                $query = 'SELECT imagen_ruta FROM productos WHERE id_producto = :id';
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
                if ($result) {
                    $image_path = $result['imagen_ruta'];
        
                    // Asegurarse de que la ruta sea absoluta usando la constante PROJECT_ROOT
                    $absolute_image_path = PROJECT_ROOT . $image_path;
        
                    // Verificar si el directorio existe y es accesible
                    if (is_dir($absolute_image_path)) {
                        // Leer todos los archivos en el directorio
                        $files = scandir($absolute_image_path);
                        $carousel_items = '';
                        $first_item = true;
        
                        // Filtrar solo archivos de imagen válidos (puedes ajustar las extensiones según tus necesidades)
                        $valid_extensions = ['jpg', 'jpeg', 'png', 'gif'];
                        foreach ($files as $file) {
                            $file_path = $image_path . $file;
                            $file_extension = pathinfo($file, PATHINFO_EXTENSION);
        
                            if (in_array(strtolower($file_extension), $valid_extensions)) {
                                $active_class = $first_item ? 'active' : '';
                                $carousel_items .= "
                                    <div class=\"carousel-item $active_class\">
                                        <img class=\"imagen\" src=\"$file_path\"/>
                                    </div>";
                                $first_item = false;
                            }
                        }
        
                        // Devolver el HTML generado
                        return $carousel_items;
                    } else {
                        return "El directorio de imágenes no existe o no es accesible. Ruta intentada: $absolute_image_path";
                    }
                } else {
                    return "No se encontró la ruta de la imagen para el producto con ID $id.";
                }
            } catch (PDOException $e) {
                echo "¡Error!: " . $e->getMessage() . "</br>";
                echo "¡Error al mostrar producto!</br>";
            }
        }
        
        public function show_mini_card_best_promo(){
            try {
                $query = 'SELECT id_producto, nombre, precio, iva, ruta, tipo_promo
                          FROM productos 
                          ORDER BY tipo_promo DESC
                          LIMIT 5';
                $stmt = $this->db->prepare($query);
                $stmt->execute();
                $products_info = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
                if ($products_info) {
                    $texto = '';
                    foreach ($products_info as $product_info) {
                        $precio_con_iva = $product_info['precio'] + ($product_info['precio'] * $product_info['iva'] / 100);
                        $producto_con_descuento = $precio_con_iva - ($precio_con_iva * ($product_info['tipo_promo']/100));
                        $texto .= '

                            <div class="col-sm-5 col-md-5 col-lg-2 mb-2">
                                <a href="product.php?id_product=' . $product_info['id_producto'] . '">
                                    <div class="card address-card h-100">
                                    <div class="card-body">
                                        <img src="' . $product_info['ruta'] . '" alt="Imagen del producto" class="imagen">
                                    </div>
                                    <div><b>' . $product_info['nombre'] . '</b></div>
                                    <div>' . number_format( $producto_con_descuento, 2) . '€</div>
                                    </div>
                                </a>
                            </div>';
                    }
                } else {
                    $texto = "No se encontraron productos.";
                }
                return $texto;
            } catch (PDOException $e) {
                echo "¡Error!: " . $e->getMessage() . "</br>";
                echo "¡Error al mostrar productos!</br>";
            }
        }

        public function show_mini_card_best_seller(){
            try {
                $query = 'SELECT id_producto, nombre, precio, iva, ruta, cantidad_vendida
                          FROM productos 
                          ORDER BY cantidad_vendida DESC
                          LIMIT 5';
                $stmt = $this->db->prepare($query);
                $stmt->execute();
                $products_info = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
                if ($products_info) {
                    $texto = '';
                    foreach ($products_info as $product_info) {
                        $precio_con_iva = $product_info['precio'] + ($product_info['precio'] * $product_info['iva'] / 100);
                        $texto .= '
                            <div class="col-sm-5 col-md-5 col-lg-2 mb-2">
                                <a href="product.php?id_product=' . $product_info['id_producto'] . '">
                                    <div class="card address-card h-100">
                                        <div class="card-body">
                                            <img src="' . $product_info['ruta'] . '" alt="Imagen del producto" class="imagen">
                                        </div>
                                        <div><b>' . $product_info['nombre'] . '</b></div>
                                        <div>' . number_format($precio_con_iva, 2) . '€</div>
                                    </div>
                                </a>
                            </div>';
                    }
                } else {
                    $texto = "No se encontraron productos.";
                }
                return $texto;
            } catch (PDOException $e) {
                echo "¡Error!: " . $e->getMessage() . "</br>";
                echo "¡Error al mostrar productos!</br>";
            }
        }
        
    }


?>