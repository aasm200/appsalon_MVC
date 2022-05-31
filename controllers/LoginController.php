<?php 

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController {
    public static function login(Router $router) {
        $alertas = [];

        if($_SERVER['REQUEST_METHOD']=== 'POST'){
            $auth = new Usuario($_POST);

            $alertas = $auth->validarlogin();

            if(empty($alertas)) {
                //comprobar que exista usuario
              

                $usuario = Usuario::where('email',$auth->email);


                if($usuario) {
                    //verificar el password
                  
                  if ($usuario->ComprobarPasswordAndVerificado($auth->password)) {
                      //autentica el usurio
                    session_start();

                    $_SESSION['id'] = $usuario->id;
                    $_SESSION['nombre'] = $usuario->nombre ."  ".$usuario->apellido;
                    $_SESSION['email'] = $usuario->email;
                    $_SESSION['login'] = true;

                    //redirecionamiento

                    if($usuario->admin === "1") {
                        $_SESSION['admin'] = $usuario->admin ?? null;
                       
                        header('Location:/admin');
                    } else {
                        header('Location:/cita');
                    }
                  }
                } else {
                    Usuario::setAlerta('error','Usuario no registrado');
                }
            }

            //obtener alertas

            $alertas = Usuario::getAlertas();

        }
       
        $router->render('auth/login', [
            'alertas'=>$alertas
        ]);
    }

    public static function logout() {
        session_start();

        $_SESSION = [];

        header('Location:/');
    }

    public static function olvide(Router $router) {

        $alertas =[];

        if($_SERVER['REQUEST_METHOD']==='POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();

          if(empty($alertas)) {
              $usuario = Usuario::where('email',$auth->email);

              if($usuario && $usuario->confirmado === "1") {

                //generar un token

                $usuario->crearToken();
                $usuario->guardar();

                //enviar email
                $email = new Email($usuario->nombre,$usuario->email,$usuario->token);
                $email->enviarInstruciones();

                //alerta de exito 
                Usuario::setAlerta('exito', 'Se han enviado las intruciones para Reestablecer tu password');
              
              } else {
                    Usuario::setAlerta('error',"El usuario no existe o no esta confirmado");
              }
            //   debuguear($usuario);
          }

        }

        $alertas= Usuario::getAlertas();

        $router->render('auth/olvide', [
            'alertas'=>$alertas
        ]);
    }

    public static function recuperar(Router $router) {

        $alertas= [];
        $error= false;

        $token = s($_GET['token']);

        //busca usuario por su token
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)) {
            Usuario::setAlerta('error','token no valido');
            $error=true;
        }

       if($_SERVER['REQUEST_METHOD'] === "POST") {
           //leer el nuevo password y guardarlo

           $password = new Usuario($_POST);

           $alertas = $password->validarPassword();

        if(empty($alertas)) {
            $usuario->password = null;
            
            $usuario->password =  $password->password;
            $usuario->hashPassword();
            $usuario->token = null;

            $resultado =$usuario->guardar();

            if($resultado) {
                header('Location:/');
            }
            debuguear($usuario);

        }

       }

        // debuguear($usuario);
        $alertas= Usuario::getAlertas();
        $router->render('auth/recuperar-password', [
            'alertas'=>$alertas,
            'error'=>$error
        ]);
    }

    


    public static function crear(Router $router) {
        
        $usuario = new Usuario();

        //alertas vacias
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();
        
            //revisar que alerta este vacio

            if(empty($alertas)) {
               $resultado = $usuario->existeUsuario();

               if($resultado->num_rows) {
                   $alertas = Usuario::getAlertas();
               } else {  
                // hashear pasword

                $usuario->hashPassword();

                //generar un token unico para hacer confirmacion por email

                $usuario->crearToken();

                //enviar el email para que el usuario confirme

                $email = new Email($usuario->nombre,$usuario->email,$usuario->token);

                $email->enviarConfirmacion();

                //crea el usuario

                $resultado = $usuario->guardar();

                if($resultado) { 
                   header('location:/mensaje');
                }

               }
            }

        }
        $router->render('auth/crear-cuenta', [
            'usuario'=> $usuario,
            'alertas'=> $alertas
        ]);
    }
    public static function mensaje (Router $router) {
        $router->render('auth/mensaje');
    }

    public static function confirmar(Router $router) {
        $alertas = [];
        $token = s($_GET['token']);
        $usuario = Usuario::where('token', $token);
      
        if(empty($usuario)) {
            //mostrar mensaje de error
            Usuario::setAlerta('error','Token No Válido');
        } else {
            //usuario confirmado
            $usuario->confirmado = "1";
            $usuario->token = "";
            $usuario->guardar();
            Usuario::setAlerta('exito','Te has registrado Correctamente');
        }
        //obtener alertas
        $alertas = Usuario::getAlertas();

        //mostrar la vista
        $router->render('auth/confirmar-cuenta', [
            'alertas'=> $alertas,
        ]);
    }
}