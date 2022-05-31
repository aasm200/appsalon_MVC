<h1 class="nombre-pagina"> Actualiza Servicio</h1>
<p class="descipcion-pagina"> Actualiza tu servicio</p>


<?php 
    include_once __DIR__.'/../templates/barra.php';
    include_once __DIR__.'/../templates/alertas.php';
?>

<form method="POST" class="formulario">
    <?php include_once __DIR__.'/formulario.php' ?>
    <input type="submit" class="boton" value="Actulizar Servicio">
</form>