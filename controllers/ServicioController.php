<?php

namespace Controllers;

use Model\Servicio;
use MVC\Router;

class ServicioController {
    //inicio de sesion
    public static function index(Router $router){

        session_start();

        $servicios = Servicio::all();

        $router->render('servicios/index', [
            'nombre' => $_SESSION['nombre'],
            'servicios' => $servicios
        ]);
    }

    public static function crear(Router $router){
        //INICIAMOS SESION COMO ADMIN
        session_start();
        isAdmin();

        //creamos un servicio vacio
        $servicio = new Servicio;
        //alertas de validacion
        $alertas = [];

        //leemos el formulario post
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            //sincronizamos con el metodo creado el post
            $servicio->sincronizar($_POST);

            //para retornar las alertas
            $alertas = $servicio->validar();

            if(empty($alertas)){
                $servicio->guardar();
                header('Location: /servicios');
            }
        }
        //devolvemos 
        $router->render('servicios/crear', [
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);
    }

    public static function actualizar(Router $router){
        session_start();
        isAdmin();

        //para pasar por cada valor que queremos actualizar
        if(!is_numeric($_GET['id'])) return;

        //creamos un servicio vacio
        $servicio = Servicio::find($_GET['id']);
        //alertas de validacion
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            //sincronizamos con el metodo creado el post
            $servicio->sincronizar($_POST);

            //para retornar las alertas
            $alertas = $servicio->validar();

            if(empty($alertas)){
                $servicio->guardar();
                header('Location: /servicios');
            }

        }
        $router->render('servicios/actualizar', [
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);
    }

    public static function eliminar(Router $router){
        session_start();
        isAdmin();
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $servicio =Servicio::find($id);
            $servicio->eliminar();
            header('Location: /servicios');
        }

    }

}