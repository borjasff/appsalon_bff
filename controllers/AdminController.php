<?php

namespace Controllers;

use Model\AdminCita;
use MVC\Router;

class AdminController{
    public static function index(Router $router){
        //comprobamos el inicio de sesión
        session_start();
        //verificamos que es admin
        isAdmin();

        //recupera la fecha o de la selección o del servidor
        $fecha = $_GET['fecha'] ?? date('Y-m-d');
        //separa por el guión en un arreglo de 3 posiciones
        $fechas = explode('-', $fecha);
        //revisa una fecha y retorna true o false
        if(!checkdate($fechas[1], $fechas[2], $fechas[0])){
            header('Location: /404');
        }
        //para seleccionar la fecha de hoy

        //consultar la bd
        $consulta = "SELECT citas.id, citas.hora, CONCAT( usuarios.nombre, ' ', usuarios.apellido) as cliente, ";
        $consulta .= " usuarios.email, usuarios.telefono, servicios.nombre as servicio, servicios.precio  ";
        $consulta .= " FROM citas  ";
        $consulta .= " LEFT OUTER JOIN usuarios ";
        $consulta .= " ON citas.usuarioId=usuarios.id  ";
        $consulta .= " LEFT OUTER JOIN citasServicios ";
        $consulta .= " ON citasServicios.citaId=citas.id ";
        $consulta .= " LEFT OUTER JOIN servicios ";
        $consulta .= " ON servicios.id=citasServicios.servicioId ";
        $consulta .= " WHERE fecha =  '${fecha}' ";

        $citas = AdminCita::SQL($consulta);

        //devolvemos el nombre y citas
        $router->render('admin/index',[
            'nombre' => $_SESSION['nombre'],
            'citas' => $citas,
            'fecha' => $fecha
        ]);
    }
}