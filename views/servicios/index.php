<h1 class="nombre-pagina"> Servicios del Salon</h1>
<p class="descipcion-pagina"> Listado de servicios  para el Salon</p>

<?php 
    include_once __DIR__.'/../templates/barra.php';
?>

<ul class="servicios">
    <?php foreach($servicios as $servicio): ?>
        <li>
            <p>Nombre: <span><?php echo $servicio->nombre; ?></span></p>
            <p>Precio: <span>$<?php echo $servicio->precio; ?></span></p>
        
            <div class="acciones">
                <a class="boton" href="/servicios/actualizar?id=<?php echo $servicio->id;?>">Actualizar</a>

                <form action="/servicios/eliminar" method="POST">
                    <input type="hidden" name="id" value="<?php echo $servicio->id; ?>">
                    <input type="submit" value="borrar" class="boton-eliminar">
                </form>


            </div>

        </li>

    <?php endforeach; ?>
</ul>

