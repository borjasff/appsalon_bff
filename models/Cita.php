<?php

namespace Model;

class Cita extends ActiveRecord {
    //Base de datos
    protected static $tabla = 'citas';
    //es importante asegurar que estos datos estan sanitizados, vienen de la funcion sanitizarAtributo
    protected static $columnasDB = [ 'id', 'fecha', 'hora' ,'usuarioId'];

    //cuando instanciamos lo que el usuario nos proporciona
    public $id;
    public $fecha;
    public $hora;
    public $usuarioId;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->fecha = $args['fecha'] ?? '';
        $this->hora = $args['hora'] ?? '';
        $this->usuarioId = $args['usuarioId'] ?? '';
    }
}