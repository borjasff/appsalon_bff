<?php
namespace Controllers;

use Model\Cita;
use Model\CitaServicio;
use Model\Servicio;

class APIController {
    public static function index(){
        //consulta todos los registros
        $servicios = Servicio::all();
        echo json_encode($servicios);

    }
    public static function guardar(){
        //almacena la cita y devuelve el id
        //arreglo asociativo que hace al Json ser leido por js
        $cita = new Cita($_POST);
        //guardamos la informacion de cita en resultado
        $resultado = $cita->guardar();

        //viene de la bd
        $id = $resultado['id'];

        //almacena la cita y el servicio con el id
        $idServicios = explode(",", $_POST['servicios']);

        foreach($idServicios as $idServicio){
            $args = [
                'citaId' => $id,
                'servicioId' => $idServicio
            ];
            //guardamos los parametros en cita servicio
            $citaServicio = new CitaServicio($args);
            $citaServicio->guardar();
        }
        
        echo json_encode(['resultado' => $resultado]);
    }

    public static function eliminar(){
        //Si es un metodo post se extrae el id
        IF($_SERVER['REQUEST_METHOD'] === 'POST'){
            $id = $_POST['id'];
            //llamamos al metodo find en el que introducimos una cita, ese valor se le pasa a cita
            $cita = Cita::find($id);
            //se elimina cita
            $cita->eliminar();
            //redireccionamos de la pagina que venimos
            header('Location:' . $_SERVER['HTTP_REFERER']);
        }
    }
}
