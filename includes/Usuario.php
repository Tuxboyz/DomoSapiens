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
                    $addresses .= '
                    <div class="col-md-6 col-lg-4 mb-4">
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
                                                <p>
                                                <input type="checkbox" class="form-check-input" id="id_address_'.$fila['id_direccion'].'" name="confirm_delete" value="1">
                                                <label class="form-check-label" for="id_address_'.$fila['id_direccion'].'">Estoy seguro</label></p>
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
                    $addresses .= '<option value="' . htmlspecialchars($fila["id_direccion"]) . '">' . 
                                  htmlspecialchars($fila["direccion"]) . ', ' . 
                                  htmlspecialchars($fila["ciudad"]) . ' ' . 
                                  htmlspecialchars($fila["cod_post"]) . '</option>';
                }
                $addresses .= '</select>';
            } else {
                $addresses = false;
            }
        
            return $addresses;
        }
    }
?>




