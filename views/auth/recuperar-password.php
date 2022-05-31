<h1 class="nombre-pagina">Reestablece Tu Password</h1>
<p class="descripcion-pagina"> Coloca tu nuevo Password a continuacion</p>

<?php 
    include_once __DIR__ . "/../templates/alertas.php";
?>

<?php if($error) return;?>
<form class="formulario" method="POST">

    <div class="campo">
        <label for="password">Password</label>
        <input type="password" placeholder="Escriba Su Password" id="password" name="password">
    </div>

<input type="submit" class="boton" value="Guardar Password">

</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta ? Inicia Sesión</a>
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crear una</a>
</div>