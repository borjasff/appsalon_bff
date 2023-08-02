<?php
    //doble foreach para identificar la llave del arreglo buscando el error y luego accedemos con el segundo a el
    foreach($alertas as $key => $mensajes):
        foreach($mensajes as $mensaje):
    
    

?>
    <div class="alerta <?php echo $key; ?>">
            <?php echo $mensaje; ?>
    </div>
<?php

        endforeach;
    endforeach;

?>