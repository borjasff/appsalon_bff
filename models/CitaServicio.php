<?php 
namespace Model;

class CitaServicio extends ActiveRecord{
        //Base de datos
        protected static $tabla = 'citasservicios';
        //es importante asegurar que estos datos estan sanitizados, vienen de la funcion sanitizarAtributo
        protected static $columnasDB = [ 'id', 'citaId','servicioId'];

        public $id;
        public $citaId;
        public $servicioId;

        public function __construct($args = [])
        {
            $this->id = $args['id'] ?? null;
            $this->citaId = $args['citaId'] ?? '';
            $this->servicioId = $args['servicioId'] ?? '';
        }
}