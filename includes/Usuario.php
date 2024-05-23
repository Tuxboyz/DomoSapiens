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

    }
    /*
    public function edit_data($id, $tipo, $new_dato) {
        try {
            // Validar tipo de dato y aplicar validación correspondiente
            switch ($tipo) {
                case 'nombre':
                    if (!validateNombre($new_dato)) {
                        throw new Exception('Error al actualizar el nombre: Nombre inválido.');
                    }
                    $campo = 'nombre';
                    break;
    
                case 'apellidos':
                    if (!validateApellido($new_dato)) {
                        throw new Exception('Error al actualizar los apellidos: Apellidos inválidos.');
                    }
                    $campo = 'apellido';
                    break;
    
                case 'email':
                    if (!validateEmail($new_dato)) {
                        throw new Exception('Error al actualizar el email: Email inválido.');
                    }
                    $campo = 'email';
                    break;
    
                case 'password':
                    if (!validatePassword($new_dato)) {
                        throw new Exception('Error al actualizar la contraseña: Contraseña inválida.');
                    }
                    $campo = 'password';
                    break;
    
                case 'fecha_nac':
                    if (!validateFechaNacimiento($new_dato)) {
                        throw new Exception('Error al actualizar la fecha de nacimiento: Fecha de nacimiento inválida.');
                    }
                    $campo = 'fecha_nac';
                    break;
    
                case 'telefono':
                    if (!validateTelefono($new_dato)) {
                        throw new Exception('Error al actualizar el teléfono: Teléfono inválido.');
                    }
                    $campo = 'telefono';
                    break;
    
                default:
                    throw new Exception('Tipo de dato inválido.');
            }
    
            // Preparar y ejecutar la consulta de actualización
            $datos = array(':par1' => $new_dato, ':par2' => $id);
            $update = "UPDATE usuarios SET $campo = :par1 WHERE id_usuario = :par2";
            $stmt = $this->db->prepare($update);
            return $stmt->execute($datos);
    
        } catch (Exception $e) {
            echo "¡Error!: " . $e->getMessage() . "<br>";
        }
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
    }*/

?>