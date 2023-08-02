<h1 class="nombre-pagina">Crear Nueva Cita</h1>
<p class="descripcion-pagina">Elige tus servicios e introduce tus datos</p>

<?php
    include_once __DIR__ . '/../templates/barra.php';
?>


<div class="app">

    <nav class="tabs">
        <button class="actual" type="button" data-paso="1">Servicios</button>
        <button type="button" data-paso="2">Información Cita</button>
        <button type="button" data-paso="3">Resumen</button>
    </nav>

    <div class="seccion" id="paso-1">
        <h2>Servicios</h2>
        <p class="text-center">Elige tus servicios a continuación</p>
        <div class="listado-servicios" id="servicios"></div>
    </div>
    <div class="seccion" id="paso-2">
        <h2>Tus Datos y Cita</h2>
        <p class="text-center">Introduce tus datos y fecha de  tu cita</p>
        <form class="formulario">
            <div class="campo">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" placeholder="Tu nombre" value="<?php echo $nombre;  ?>" disabled>
            </div>
            <div class="campo">
                <label for="fecha">Fecha</label>
                <!---Para seleccionar a partir de que día se puede reservar, indicamos que a partir del día siguiente-->
                <input type="date" id="fecha" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
            </div>
            <div class="campo">
                <label for="hora">Hora</label>
                <input type="time" id="hora">

                <input type="hidden" id="id" value="<?php echo $id; ?>">
            </div>
        </form>
    </div>
    <div class="seccion contenido-resumen" id="paso-3" >
        <h2>Resumen</h2>
        <p>Verifica que la información sea correcta</p>
    </div>
    <div class="paginacion">
        <button id="anterior" class="boton">
            &laquo; Anterior</button>

        <button id="siguiente" class="boton">
            Siguiente &raquo; </button>
    </div>
</div>

<?php 
    $script = "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script src='build/js/app.js'></script>
    ";

?>