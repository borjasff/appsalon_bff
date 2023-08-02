<h1 class="nombre-pagina">Página de Administración</h1>

<?php
    include_once __DIR__ . '/../templates/barra.php';
?>

<h2>Buscar Citas</h2>
<div class="busqueda">
    <form class="formulario">
        <div class="campo">
            <label for="fecha">Fecha</label>
            <input  type="date"
                    id="fecha"
                    name="fecha"
                    value="<?php echo $fecha; ?>">
        </div>
    </form>
</div>

<?php
    if(count($citas) === 0) {
        echo "<h2>No hay Citas en esta fecha</h2>";
    }
?>

<!-- Mostramos toda la información de cada cita por persona y día-->
<div class="citas-admin">
    <ul class="citas">
        <?php
            $idCita = 0;
            //key lo usaremos para crear la suma
            foreach( $citas as $key => $cita ){
                if($idCita !== $cita->id){
                    //iniciar la sum en 0
                    $total = 0;



        ?>
        <li>
               <p>ID: <span><?php echo $cita->id; ?></span></p> 
               <p>Hora: <span><?php echo $cita->hora; ?></span></p>
               <p>Cliente: <span><?php echo $cita->cliente; ?></span></p>  
               <p>Email: <span><?php echo $cita->email; ?></span></p>  
               <p>Teléfono: <span><?php echo $cita->telefono; ?></span></p>  

               <h3>Servicios</h3>


        <?php 
            $idCita = $cita->id;
            } // fin de If 
            $total += $cita->precio;
            ?>
                <p class="servicio"><?php echo $cita->servicio . ': ' . $cita->precio; ?></p>
        
        <?php 
            $actual = $cita->id;
            $proximo = $citas[$key + 1]->id ?? 0;

            if(esUltimo($actual, $proximo)){ ?>
                <p class="total">Total: <span>$ <?php echo $total; ?></span></p>

                <!--Boton eliminar cita-->
                <form action="/api/eliminar" method="POST">
                <input type="hidden" name="id" value="<?php echo $cita->id; ?>">
                <input type="submit" class="boton-eliminar" value="Eliminar">
                </form>
        <?php 
            } //imprimimos el total después de buscar cual es el último
            } //fin de foreach
        ?>
    </ul>

</div>

<?php
    //Script para tener el buscador de citas por dias
    $script = "<script src='build/js/buscador.js'></script>" ; 
?>