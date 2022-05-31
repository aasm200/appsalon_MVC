<?php 

namespace Model;

class Usuario extends ActiveRecord {
    // base de datos

    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id','nombre','apellido','email','password','telefono', 'admin','confirmado','token'];

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->admin = $args['admin'] ??  0 ;
        $this->confirmado = $args['confirmado'] ?? 0;
        $this->token = $args['token'] ?? '';


        
    }

    //mensajes de validacion para creacion de cuenta

    public function validarNuevaCuenta() {
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El Nombre del Cliente es Obligatorio';
        }
        if(!$this->apellido) {
            self::$alertas['error'][] = 'El Apellido del Cliente es Obligatorio';
        }
        if(!$this->email) {
            self::$alertas['error'][] = 'El email del Cliente es Obligatorio';
        }
        if(!$this->telefono) {
            self::$alertas['error'][] = 'El Telefono del Cliente es Obligatorio';
        }
        if(!$this->password) {
            self::$alertas['error'][] = 'El Password del Cliente es Obligatorio';
        }
        if(strlen($this->password) < 6 ) {
            self::$alertas['error'][] = 'El Password debe contener minimo 6 caracteres';
        }



        return self::$alertas;
    }

    public function validarLogin() {
       
        if(!$this->email) {
            self::$alertas['error'][] = 'Debes ingresar un Email para Iniciar Sesión';
        }
        if(!$this->password) {
            self::$alertas['error'][] = 'El Password es obligatorio para Iniciar Sesión';
        }

        return self::$alertas;
    }

    public function validarEmail() {
        if(!$this->email) {
            self::$alertas['error'][] = 'Debes ingresar un Email para Reestablecer tu password';
        }
        
        return self::$alertas;
    }

    public function validarPassword() {

        if(!$this->password) {
            self::$alertas['error'][] = 'Debes ingresar un Password para Reestablecer tu password';
        }
        if(strlen($this->password) < 6 ) {
            self::$alertas['error'][] = 'El Password debe contener minimo 6 caracteres';
        }

        return self::$alertas;
    }
    

    //revisa si el usuario ya existe
    public function existeUsuario() {
        $query = "SELECT * FROM " . self::$tabla . " WHERE email = '". $this->email. "' LIMIT 1";

        $resultado = self::$db->query($query);

        if($resultado->num_rows) {
            self::$alertas['error'][]='EL usuario ya esta Registrado';
        }
      return $resultado;
    }

    public function hashPassword() {
      
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function crearToken() {  // genera un token para ser enviado para confirmar correos

        $this->token = uniqid();
        
    }

    public function ComprobarPasswordAndVerificado($password) {
        $resultado = password_verify($password, $this->password);

        if(!$resultado || !$this->confirmado) {
            self::$alertas['error'][] ='El usuario no esta confirmado o Password incorrecto';
        } else {
           return true;
        }

    }




 
}