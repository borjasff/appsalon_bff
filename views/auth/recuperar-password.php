<h1 class="nombre-pagina">Recuperar Password</h1>
<p class="descripcion-pagina">Restablece tu Password a continuación</p>


<?php 
//importamos los errores de alertas
 include_once __DIR__ . '/../templates/alertas.php'
?>
<?php if($error) return; ?>
<form class="formulario" method="POST">
    <div class="campo">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" placeholder="Tu nuevo password">
    </div>

    <input type="submit" class="boton" value="Guardar Nuevo Password">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Iniciar Sesión</a>
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crear una</a>
</div>