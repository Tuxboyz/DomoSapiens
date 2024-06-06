<?php

    require_once("Config.php");

    // Funciones de validación
        function validateNombre($nombre) {
            return !empty($nombre) && preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $nombre) && strlen($nombre) >= 3;
        }
        
        function validateApellido($apellido) {
            return !empty($apellido) && preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $apellido) && strlen($apellido) >= 3;
        }
        
        function validateEmail($email) {
            return !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.(com|es|org)$/", $email);
        }
        
        function validatePassword($password) {
            return !empty($password) && strlen($password) >= 8;
        }
        
        function validateFechaNacimiento($fecha_nacimiento) {
            if (empty($fecha_nacimiento)) return false;
            $fecha = new DateTime($fecha_nacimiento);
            $hoy = new DateTime();
            $edad = $hoy->diff($fecha)->y;
            return $edad >= 18;
        }
        
        function validateTelefono($telefono) {
            return !empty($telefono) && preg_match("/^\d{9}$/", $telefono);
        }


    class Usuario{
        protected $db;

        function __construct(){
            try{
                $this->db = new PDO(DNS, USER, PASS);
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

        public function crearUser($nombre,$apellido,$email,$password,$fecha_nacimiento,$telefono){
            try{

                $datos = array(':par1'=> $nombre,
                ':par2'=> $apellido,
                ':par3'=> $email,
                ':par4'=> $password,
                ':par5'=> $fecha_nacimiento,
                ':par6'=> $telefono);
                $creacion = 'INSERT INTO usuarios(nombre,apellido,email,password,fecha_nac,telefono)
                                VALUES(:par1, :par2, :par3, :par4, :par5, :par6 )';
                $inicio = $this->db->prepare($creacion);
                return $inicio->execute($datos);

            } catch(PDOExceptions $e){

                echo "¡Error!: ".$e->getMessage()."</br>";
                echo "¡Error al crear el usuario!</br>";

            }
        }

        public function crearAddress($id,$direccion,$ciudad,$cp){
            try{

                $datos = array(':par1'=> $id,
                ':par2'=> $direccion,
                ':par3'=> $ciudad,
                ':par4'=> $cp);

                $creacion = 'INSERT INTO direcciones(id_usu,direccion,ciudad,cod_post)
                                VALUES(:par1, :par2, :par3, :par4)';
                $inicio = $this->db->prepare($creacion);
                return $inicio->execute($datos);

            } catch(PDOExceptions $e){

                echo "¡Error!: ".$e->getMessage()."</br>";
                echo "¡Error al añadir direccion!</br>";

            }
        }

        public function validacion($user,$pass){
            $datos = array(':par1'=>$user,':par2'=>$pass);

            $consulta = 'SELECT id_usuario, nombre
                        FROM usuarios 
                        WHERE    email = :par1
                        AND  password = :par2';
            $stmt = $this->db->prepare($consulta);
            $stmt->execute($datos);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);

            if ($stmt->rowCount() == 1){
                $fila = $stmt->fetch();

                $id = "{$fila["id_usuario"]}";
                $nombreCompleto = "{$fila["nombre"]}";

                $datos = ["id"=>$id, "nombre"=>$nombreCompleto];
                return $datos;

            } else {

                return false;

            }
        }

        public function get_data($id){

            $dato = array(':par1'=>$id);

            $consulta ='SELECT nombre, apellido, email, password, fecha_nac, telefono 
                        FROM usuarios
                        WHERE id_usuario = :par1';
            $stmt = $this->db->prepare($consulta);
            $stmt->execute($dato);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);

            if ($stmt->rowCount() == 1){
                $fila = $stmt->fetch();

                $datos = [  
                    "nombre"    => $fila["nombre"], 
                    "apellido" => $fila["apellido"],
                    "email"     => $fila["email"],
                    "password"  => $fila["password"],
                    "fecha_nac" => $fila["fecha_nac"],
                    "telefono"  => $fila["telefono"]
                ];
                return $datos;
            } else {
                return false;
            }
        } 

        public function valid_pass($id, $pass) {
            try {
                $datos = array(':par1' => $id, ':par2' => $pass);
        
                $consulta = 'SELECT nombre
                             FROM usuarios
                             WHERE id_usuario = :par1
                             AND password = :par2';
                $stmt = $this->db->prepare($consulta);
                $stmt->execute($datos);
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
        
                if ($stmt->rowCount() == 1) {
                    return true;
                } else {
                    return false;
                }
            } catch (PDOException $e) {
                echo "¡Error!: " . $e->getMessage() . "</br>";
                echo "¡Error al validar la contraseña!</br>";
            }
        }

        public function valid_email($email) {
            try {

                $datos = array(':par1' => $email);
        
                $consulta = 'SELECT nombre FROM usuarios WHERE email = :par1';
                $stmt = $this->db->prepare($consulta);
                $stmt->execute($datos);
                
                if ($stmt->rowCount() == 1) {
                    return false;
                } else {
                    return true;
                }
            } catch (PDOException $e) {
                echo "¡Error!: " . $e->getMessage() . "<br>";
                echo "¡Error al validar la existencia de uso del nuevo correo!<br>";
                return false;
            }
        }
        
        public function edit_data($id,$tipo,$new_dato){
            if($tipo == "nombre"){
                try{
                    $datos = array(':par1' => $new_dato,':par2' => $id);
    
                    $update = ' UPDATE usuarios 
                                SET nombre = :par1
                                WHERE id_usuario = :par2';
    
                    $stmt = $this->db->prepare($update);
                    return $stmt->execute($datos);
    
                } catch(PDOExceptions $e){
                    echo "¡Error!: ".$e->getMessage()."</br>";
                    echo "¡Error al actualizar el nombre!</br>";
                }
            }
            if($tipo == "apellidos"){
                try{
                    $datos = array(':par1' => $new_dato,':par2' => $id);
    
                    $update = ' UPDATE usuarios 
                                SET apellido = :par1
                                WHERE id_usuario = :par2';
    
                    $stmt = $this->db->prepare($update);
                    return $stmt->execute($datos);
    
                } catch(PDOExceptions $e){
                    echo "¡Error!: ".$e->getMessage()."</br>";
                    echo "¡Error al actualizar los apellidos!</br>";
                }
            }
            if($tipo == "email"){
                try{
                    $datos = array(':par1' => $new_dato,':par2' => $id);
    
                    $update = ' UPDATE usuarios 
                                SET email = :par1
                                WHERE id_usuario = :par2';
    
                    $stmt = $this->db->prepare($update);
                    return $stmt->execute($datos);
    
                } catch(PDOExceptions $e){
                    echo "¡Error!: ".$e->getMessage()."</br>";
                    echo "¡Error al actualizar el email!</br>";
                }
            }
            if($tipo == "password"){
                try{
                    $datos = array(':par1' => $new_dato,':par2' => $id);
    
                    $update = ' UPDATE usuarios 
                                SET password = :par1
                                WHERE id_usuario = :par2';
    
                    $stmt = $this->db->prepare($update);
                    return $stmt->execute($datos);
    
                } catch(PDOExceptions $e){
                    echo "¡Error!: ".$e->getMessage()."</br>";
                    echo "¡Error al actualizar la contraseña!</br>";
                }
            }
            if($tipo == "fecha_nac"){
                try{
                    $datos = array(':par1' => $new_dato,':par2' => $id);
    
                    $update = ' UPDATE usuarios 
                                SET fecha_nac = :par1
                                WHERE id_usuario = :par2';
    
                    $stmt = $this->db->prepare($update);
                    return $stmt->execute($datos);
    
                } catch(PDOExceptions $e){
                    echo "¡Error!: ".$e->getMessage()."</br>";
                    echo "¡Error al actualizar la fecha de nacimiento!</br>";
                }
            }
            if($tipo == "telefono"){
                try{
                    $datos = array(':par1' => $new_dato,':par2' => $id);
    
                    $update = ' UPDATE usuarios 
                                SET telefono = :par1
                                WHERE id_usuario = :par2';
    
                    $stmt = $this->db->prepare($update);
                    return $stmt->execute($datos);
                    
                } catch(PDOExceptions $e){
                    echo "¡Error!: ".$e->getMessage()."</br>";
                    echo "¡Error al actualizar el telefono!</br>";
                }
            }
        }

        public function show_address($id) {
            try{
                $dato = array(':par1' => $id);
            
                $consulta = 'SELECT direccion, ciudad, cod_post, id_direccion 
                            FROM direcciones
                            WHERE id_usu = :par1';
            
                $stmt = $this->db->prepare($consulta);
                $stmt->execute($dato);
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
            
                $addresses = '';
            
                if ($stmt->rowCount() >= 1) {
                    while ($fila = $stmt->fetch()) {
                        $addresses .= '<div class="col-md-6 col-lg-4 mb-4">
                                            <div class="card address-card h-100">
                                                <div class="card-body" id="'.$fila["id_direccion"].'">
                                                    <h5 class="card-title">Dirección de Entrega</h5>
                                                    <p class="card-text">'.$fila["direccion"].'</p>
                                                    <p class="card-text">Ciudad: '.$fila["ciudad"].'</p>
                                                    <p class="card-text">Código Postal: '.$fila["cod_post"].'</p>
                                                </div>
                                                <button type="button" 
                                                        class="m-1 btn btn-danger rounded-pill"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#mod_'.$fila['id_direccion'].'">
                                                    Eliminar - <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="modal fade" id="mod_'.$fila['id_direccion'].'" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                                            aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Eliminar Dirección</h1>
                                                        <a href="my_data.php" class="btn-close"></a>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form id="delete_'.$fila['id_direccion'].'_form" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'" method="POST">
                                                            <div class="form-floating" id="log-block">
                                                                <p>¿Estás seguro de borrar esta dirección?</p>
                                                                <input type="hidden" name="id_direccion" value="'.$fila['id_direccion'].'">
                                                                <div class="d-flex justify-content-center align-items-center">
                                                                    <label class="form-check-label mb-0" for="id_address_'.$fila['id_direccion'].'"><input type="checkbox" class="form-check-input me-2" id="id_address_'.$fila['id_direccion'].'" name="confirm_delete" value="1">Estoy seguro</label>
                                                                </div>
                                                            </div>
                                                            <div id="form_message_'.$fila['id_direccion'].'" class="text-center"></div>
                                                        </form>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <a href="my_data.php"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button></a>
                                                        <button type="submit" form="delete_'.$fila['id_direccion'].'_form" class="btn btn-primary">Borrar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>';
                    }
            
                    return $addresses;
                } else {
                    return false;
                }
            } catch (PDOExceptions $e){
                echo "¡Error!: ".$e->getMessage()."</br>";
                echo "¡Error al actualizar el nombre!</br>";
            }
        }
        
        public function elim_address($id_address){

            try{
                $datos = array(':par1' => $id_address);

                $update = ' DELETE FROM direcciones
                            WHERE id_direccion = :par1';

                $stmt = $this->db->prepare($update);
                return $stmt->execute($datos);

            } catch(PDOExceptions $e){
                echo "¡Error!: ".$e->getMessage()."</br>";
                echo "¡Error al actualizar borrar la direccion!</br>";
            }
        }

        public function dar_baja($id){
            try{
                $datos = array(':par1' => $id);

                $update = ' DELETE FROM usuarios 
                            WHERE id_usuario = :par1';

                $stmt = $this->db->prepare($update);
                return $stmt->execute($datos);

            } catch(PDOExceptions $e){
                echo "¡Error!: ".$e->getMessage()."</br>";
                echo "¡Error al actualizar el nombre!</br>";
            }
        }

        public function select_address($id) {
            try{
                $dato = array(':par1' => $id);
            
                $consulta = 'SELECT direccion, ciudad, cod_post, id_direccion 
                            FROM direcciones
                            WHERE id_usu = :par1';
            
                $stmt = $this->db->prepare($consulta);
                $stmt->execute($dato);
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
            
                $addresses = '<select class="form-select" id="ciudad" name="ciudad" required><option value="">Seleccione una opción</option>';
            
                if ($stmt->rowCount() >= 1) {
                    while ($fila = $stmt->fetch()) {
                        $addresses .= '<option value="' . $fila["direccion"] . ', ' . $fila["ciudad"] . ', ' . $fila["cod_post"] . '">' . $fila["direccion"] . ', ' . $fila["ciudad"] . ', ' . $fila["cod_post"] . '</option>';
                    }
                    $addresses .= '</select>';
                } else {
                    $addresses = false;
                }
            
                return $addresses;
            } catch(PDOExceptions $e){
                echo "¡Error!: ".$e->getMessage()."</br>";
                echo "¡Error al actualizar el nombre!</br>";
            }
        }

        public function show_tickets($id_usuario) {
            try {
                $datos = array(':par1' => $id_usuario);
        
                // Consulta para obtener los tickets con la dirección asociada
                $consulta = 'SELECT 
                                t.id_ticket, t.id_direccion, t.metodo_pago, t.productos, 
                                t.precio_envio, t.precio_total, t.fecha_compra, 
                                d.direccion, d.ciudad, d.cod_post 
                            FROM 
                                tickets t
                            JOIN 
                                direcciones d ON t.id_direccion = d.id_direccion
                            WHERE 
                                t.id_usuario = :par1';
        
                $stmt = $this->db->prepare($consulta);
                $stmt->execute($datos);
        
                // Fetch all results
                $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
                if (empty($tickets)) {
                    return false;
                }
        
                // Decode the JSON products for each ticket
                foreach ($tickets as &$ticket) {
                    $ticket['productos'] = json_decode($ticket['productos'], true);
                }
        
                // Initialize HTML output
                $html = '<div class="row row-cols-1 row-cols-md-2 g-4">';
        
                foreach ($tickets as $ticket) {
                    $fecha_objeto = new DateTime($ticket['fecha_compra']);
                    $fecha_sin_hora = $fecha_objeto->format('d-m-Y');
        
                    $html .= '<div class="col">';
                    $html .= '    <div class="card h-100">';
                    $html .= '        <div class="card-body">';
                    $html .= '            <h5 class="card-title">Ticket ID: ' . $ticket['id_ticket'] . '</h5>';
                    $html .= '            <p class="card-text"><strong>Productos del ' . $fecha_sin_hora . '</strong></p>';
                    $html .= '            <ul class="list-group">';
                    foreach ($ticket['productos'] as $producto) {
                        $html .= '                <li class="list-group-item m-1">';
                        $html .= '                    <strong>Producto ID: </strong> ' . $producto['producto_id'] . '<br>';
                        $html .=                      $producto['producto_nombre'] . ' - <strong>x</strong>' . $producto['cantidad'] .' - '. $producto['precio_unitario'] .'€/<strong>ud</strong> <br>';
                        $html .= '                    <strong>Descuento: </strong> ' . $producto['descuento'] . '%<br>';
                        $html .= '                    <strong>Precio Total: </strong>' . $producto['precio_total']. '€';
                        $html .= '                </li>';
                    }
                    $html .= '            </ul>';
                    $html .= '            <hr>';
                    $html .= '            <p class="card-text"><strong>Envío: </strong>' . $ticket['precio_envio'] . '€</p>';
                    $html .= '            <p class="card-text"><strong>Precio Total: </strong>' . $ticket['precio_total'] . '€</p>';
                    $html .= '        </div>';
                    $html .= '        <div class="card-footer">';
                    $html .= '            <a href="PDF.php?id=' . $ticket['id_ticket'] . '"><button type="button" class="btn btn-danger rounded-pill w-100" data-bs-toggle="modal" data-bs-target="#mod_' . $ticket['id_ticket'] . '">';
                    $html .= '                PDF - <i class="bi bi-filetype-pdf"></i>';
                    $html .= '            </button></a>';
                    $html .= '        </div>';
                    $html .= '    </div>';
                    $html .= '</div>';
                }
        
                $html .= '</div>';
        
                return $html;
        
            } catch (PDOException $e) {
                echo "¡Error!: " . $e->getMessage() . "<br/>";
                echo "¡Error al obtener los tickets!</br>";
            }
        }
           
        public function ticket_pdf($id_usuario, $id_ticket) {
            try {
                $datos = array(':par1' => $id_usuario, ':par2' => $id_ticket);
        
                // Consulta para obtener los tickets con la dirección asociada
                $consulta = 'SELECT 
                                t.id_direccion, t.metodo_pago, t.productos, 
                                t.precio_envio, t.precio_total, t.fecha_compra
                            FROM 
                                tickets t
                            WHERE 
                                t.id_usuario = :par1
                            AND t.id_ticket = :par2';
        
                $stmt = $this->db->prepare($consulta);
                $stmt->execute($datos);
        
                // Fetch the result
                $ticket = $stmt->fetch(PDO::FETCH_ASSOC);
        
                if (empty($ticket)) {
                    return false;
                }
        
                // Decode the JSON products
                $ticket['productos'] = json_decode($ticket['productos'], true);
        
                // Obtener el nombre completo del usuario
                $consulta_usuario = 'SELECT nombre, apellido FROM usuarios WHERE id_usuario = :par1';
                $stmt_usuario = $this->db->prepare($consulta_usuario);
                $stmt_usuario->execute(array(':par1' => $id_usuario));
                $usuario = $stmt_usuario->fetch(PDO::FETCH_ASSOC);
        
                if (empty($usuario)) {
                    return false;
                }
        
                $nombre_usu = htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido']);
                
                // Cambiar el formato de la fecha de compra a DD/MM/AAAA
                $fecha_compra = (new DateTime($ticket['fecha_compra']))->format('d/m/Y');
                
                $direccion_ciudad_cp = $ticket['id_direccion'];
                
                // Initialize the table HTML
                $tabla_productos = '<table border="1" cellpadding="5" cellspacing="0">';
                $tabla_productos .= '<tr>';
                $tabla_productos .= '<th>Producto ID</th>';
                $tabla_productos .= '<th>Nombre Producto</th>';
                $tabla_productos .= '<th>Precio Unitario</th>';
                $tabla_productos .= '<th>Descuento</th>';
                $tabla_productos .= '<th>Cantidad</th>';
                $tabla_productos .= '<th>Precio Total</th>';
                $tabla_productos .= '</tr>';
        
                foreach ($ticket['productos'] as $producto) {
                    $tabla_productos .= '<tr>';
                    $tabla_productos .= '<td>' . htmlspecialchars($producto['producto_id']) . '</td>';
                    $tabla_productos .= '<td>' . htmlspecialchars($producto['producto_nombre']) . '</td>';
                    $tabla_productos .= '<td>' . htmlspecialchars($producto['precio_unitario']) . '€</td>';
                    $tabla_productos .= '<td>' . htmlspecialchars($producto['descuento']) . '%</td>';
                    $tabla_productos .= '<td>' . htmlspecialchars($producto['cantidad']) . '</td>';
                    $tabla_productos .= '<td>' . htmlspecialchars($producto['precio_total']) . '€</td>';
                    $tabla_productos .= '</tr>';
                }
        
                $tabla_productos .= '</table>';
        
                $precio_envio = htmlspecialchars($ticket['precio_envio']).'€';
                $precio_total = htmlspecialchars($ticket['precio_total']).'€';
                $metodo_pago = htmlspecialchars($ticket['metodo_pago']);
        
                return array(
                    'nombre_usu' => $nombre_usu,
                    'fecha_compra' => $fecha_compra,
                    'direccion_ciudad_cp' => $direccion_ciudad_cp,
                    'tabla_productos' => $tabla_productos,
                    'precio_envio' => $precio_envio,
                    'precio_total' => $precio_total,
                    'metodo_pago' => $metodo_pago
                );
        
            } catch (PDOException $e) {
                echo "¡Error!: " . $e->getMessage() . "<br/>";
                echo "¡Error al obtener los tickets estoy en Usuario.php!</br>";
            }
        }
        
        public function get_tickets($id_usuario) {
            try {
                $datos = array(':par1' => $id_usuario);
        
                // Consulta para obtener los tickets con la dirección asociada
                $consulta = 'SELECT 
                                t.id_ticket, t.id_direccion, t.metodo_pago, t.productos, 
                                t.precio_envio, t.precio_total, t.fecha_compra
                            FROM 
                                tickets t
                            WHERE 
                                t.id_usuario = :par1';
        
                $stmt = $this->db->prepare($consulta);
                $stmt->execute($datos);
        
                // Fetch all results
                $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
                if (empty($tickets)) {
                    return [];
                }
        
                // Decode the JSON products for each ticket
                foreach ($tickets as &$ticket) {
                    $ticket['productos'] = json_decode($ticket['productos'], true);
                }
        
                return $tickets;
        
            } catch (PDOException $e) {
                echo "¡Error!: " . $e->getMessage() . "<br/>";
                echo "¡Error al obtener los tickets!</br>";
                return [];
            }
        }
        
    }
?>