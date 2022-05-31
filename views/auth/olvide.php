<h1 class="nombre-pagina">Olvidaste Tu Password</h1>
<p class="descripcion-pagina">Llena los siguientes campos para reestablecer tu password</p>

<?php 
    include_once __DIR__ . "/../templates/alertas.php";
?>

<form class="formulario" method="POST" action="/olvide">    
    <div class="campo">
        <label for="email">Tu Email</label>
        <input type="email" name="email" id="email" placeholder="Escribe Tu email">

    </div>
    <input type="submit" value="Reestablece tu Password" class="boton">
</form>

<div class="acciones">
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crear una</a>
    <a href="/">¿Ya tienes una cuenta ? Inicia Sesión</a>
</div>
