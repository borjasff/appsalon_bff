<?php

namespace Controllers;

use MVC\Router;

class CitaController{
    public static function index ( Router $router){
        //iniciamos sesion 
        session_start();

        //comprobamos si se ha verificado
        isAuth();
        
        //lo pasamos a la vista
        $router->render('cita/index',[
            'nombre' => $_SESSION['nombre'],
            'id' => $_SESSION['id']
        ]);
    }
}