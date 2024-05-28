<?php

    require_once("Config.php");

    function barrer($data) {
        return trim(htmlspecialchars($data));
    }
    
    class Search{
        protected $db;

        function __construct(){
            try{
                $this->db = new PDO(BBDD_DSN, BBDD_USER, BBDD_PASSWORD);
            } catch(PDOException $e){
                die("¡Error  del php usuario!: ".$e->getMessage()." </br>");
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
                $consulta = 'SELECT nombre FROM categoria';
                $stmt = $this->db->prepare($consulta);
                $stmt->execute();
        
                if ($stmt->rowCount() > 0) {

                    $categorias_html = '<ul class="navbar-nav me-auto mb-2">';
        

                    while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $categorias_html .= '<li class="nav-item">';
                        $categorias_html .= '<a class="nav-link active" href="#">' . $fila["nombre"] . '</a>';
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
                $query = 'SELECT nombre, precio, iva, imagen_ruta
                          FROM productos 
                          WHERE id_producto = :id';
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                $product_info = $stmt->fetch(PDO::FETCH_ASSOC);
        
                if ($product_info) {
                    $precio_con_iva = $product_info['precio'] + ($product_info['precio'] * $product_info['iva'] / 100);
                    $texto = '<div style="background-color: #f7e6a7; width: 150px; height: 150px; display: flex; align-items: center; justify-content: center;">
                                <img src="' . $product_info['imagen_ruta'] . '" alt="Imagen del producto" style="max-width: 100%; max-height: 100%;">
                              </div>
                              <div style="margin-left: 20px;">
                                <div>' . $product_info['nombre'] . '</div>
                                <div>' . number_format($precio_con_iva, 2) . '$</div>
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
                                 precio, iva, imagen_ruta, 
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
        
    }

/*
// Crear una instancia de la clase que contiene la función product_inf
$search = new Search();

// Id del producto que deseas buscar
$product_id = 1; // Por ejemplo

// Obtener la información del producto
$product_info = $search->product_inf($product_id);

// Comprobar y mostrar la información del producto
if ($product_info) {
    echo "Nombre: " . $product_info['nombre'] . "<br>";
    echo "Descripción: " . $product_info['descripcion'] . "<br>";
    echo "Stock: " . $product_info['stock'] . "<br>";
    echo "Precio: " . $product_info['precio'] . "<br>";
    echo "IVA: " . $product_info['iva'] . "<br>";
    echo "Imagen Ruta: " . $product_info['imagen_ruta'] . "<br>";
    echo "Tipo de Promoción: " . $product_info['tipo_promo'] . "<br>";
    echo "Cantidad Vendida: " . $product_info['cantidad_vendida'] . "<br>";
} else {
    echo "No se encontró información del producto.<br>";
}
*/
?>