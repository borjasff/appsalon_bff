<?php
namespace Controllers;

use Classes\Email;
use MVC\Router;
use Model\Usuario;

class LoginController {
    //inicio de sesion
    public static function login(Router $router){
        //alertas vacias para registrar la validación
        $alertas = [];

        //para cuando pulsamos el iniciar sesión
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            //recogemos los datos introducidos por el usuario
            $auth = new Usuario($_POST);
            //validamos el login
            $alertas = $auth->validarLogin();
            //verificar si las alertas están vacias
            if(empty($alertas)){
                //comprobar que existe el usuario
                $usuario = Usuario::where('email', $auth->email);

                if($usuario){
                    //verificar el password
                    if($usuario->comprobarPasswordAndVerificado($auth->password)){
                        //autentificar el usuario
                        //iniciar sesion
                        session_start();
                        //credenciales que queremos reflejar
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = True;

                        //redireccionamientoo
                        if($usuario->admin === "1"){
                            //si es admin
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header('Location: /admin');
                        }else{
                            //si es cliente
                            header('Location: /cita');
                        }

                    }
                } else {
                    //no verificado
                    Usuario::setAlerta('error', 'Usuario no encontrado');
                }
            }
                    
        }
        $alertas = Usuario::getAlertas();
        //Renderizar la vista
        $router->render('auth/login', [
            'alertas' => $alertas
        ]);
        
    }
    //cerrar sesion
    public static function logout(){
        session_start();
        $_SESSION = [];

        header('Location: /');
    }
        //recuperar password
    public static function olvide(Router $router){
        //alertas vacias para registrar la validación
        $alertas = [];

        //para cuando pulsamos el recuperar contraseña
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();

            if(empty($alertas)){
                $usuario = Usuario::where('email', $auth->email);
                if($usuario && $usuario->confirmado === "1" ){
                    //generar un token
                    $usuario->crearToken();
                    $usuario->guardar();
                    
                    //Enviar el email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token );
                    $email->enviarInstrucciones();

                    //alerta de éxito
                    Usuario::setAlerta('exito', 'Revisa tu email');

                } else{
                    //alerta de error
                    //generamos un error con setAlertas, con getAlertas mostramos el cuadro de texto del error
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                }
            }
        }
        //obtenemos la alerta
        $alertas = Usuario::getAlertas();

        //Renderizar la vista
        $router->render('auth/olvide-password', [
            'alertas' => $alertas
        ]);
    }
    public static function recuperar(Router $router){
        //alertas vacias para registrar la validación
        $alertas = [];
        $error = false;
        //obtenemos el token del usuario
        $token = s($_GET['token']);

        //buscamos al usuario por su token
        $usuario = Usuario::where('token', $token);

        //si no tiene ningun usuario el arreglo
        if(empty($usuario)){
            Usuario::setAlerta('error', 'token no valido');
            $error = true;
        } 

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            //LEER EL NUEVO PASSWORD Y GUARDARLO
            $password = new Usuario($_POST);
            $alertas = $password->validarPassword();
            
            if(empty($alertas)){
                //vaciamos el anterior password
                $usuario->password = null;

                //tomamos de la instancia de password el valor y se lo asignamos al password del usuario
                $usuario->password = $password->password;
                $usuario->hashPassword();
                $usuario->token = null;
                $resultado = $usuario->guardar();
                if($resultado){
                    header('Location: /');
                }
                
            } 
        }

        //obtenemos la alerta
        $alertas = Usuario::getAlertas();
        //Renderizar la vista
        $router->render('auth/recuperar-password', [
            'alertas' => $alertas,
            'error' => $error
        ]);
    }
    public static function crear(Router $router){
        //instanciar un usuario
        $usuario = new Usuario;

        //alertas vacias
        $alertas = [];

        //si enviamos un metodo post
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            //sincronizamos y validamos
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            //Revisar que la cadena está vacía
            if(empty($alertas)){
                //verificar que el usuario no está registrado
                $resultado = $usuario->existeUsuario();

                //si existe un usuario creamos de nuevo la alerta, ya que ha pasado previamente la validación
                if($resultado->num_rows){
                    $alertas = Usuario::getAlertas();
                } else{
                    //no esta registrado
                    //hashear el password
                    $usuario->hashPassword();

                    //generar un token unico
                    $usuario->crearToken();

                    //Enviar el email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();

                    //crear el usuario
                    $resultado = $usuario->guardar();

                    if($resultado){
                        //redireccionar a mensaje
                        header('Location: /mensaje');
                    }

                    //debuguear($usuario);
                }
            }

        }
        //Renderizar la vista
        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function mensaje(Router $router) {
        //para redireccionar a mensaje
        $router->render('auth/mensaje');
    }

    public static function confirmar(Router $router) {
        $alertas = [];
        //confirmar la cuenta y sanitizamos para evitar hackeos
        $token = s($_GET['token']);

        $usuario = Usuario::where('token', $token);

        if(empty($usuario)){
            //mostrar mensaje de error
            Usuario::setAlerta('error', 'Token no válido');
        }else{
            //usuario confirmado
            $usuario->confirmado = "1";
            $usuario->token = null;
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Cuenta verificada correctamente');
        }

        //para redireccionar a confirma-cuenta y las alertas confirman la verificación de la cuenta o si el token no es valido
        //obtener alertas
        $alertas = Usuario::getAlertas();

        //renderizar la vista
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas 
        ]);
    }
}