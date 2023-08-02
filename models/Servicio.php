<?php
namespace Model;
//el modelo interactua directamente con la bd
class Servicio extends ActiveRecord {
    //bd configuración y creamos un objeto igual al de la bd
    protected static $tabla = 'servicios';
    protected static $columnasDB = ['id', 'nombre', 'precio'];

    //registramos los atributos
    public $id;
    public $nombre;
    public $precio;

    public function __construct($args = []){

        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->precio = $args['precio'] ?? '';
        
    }

    public function validar(){
        if(!$this->nombre){
            self::$alertas['error'][] = 'El Nombre para el Servicio es Obligatorio';
        }
        if(!$this->precio){
            self::$alertas['error'][] = 'El Precio para el Servicio es Obligatorio';
        }
        if(!is_numeric($this->precio)){
            self::$alertas['error'][] = 'El Precio no es válido';
        }
        return self::$alertas;
    }
}