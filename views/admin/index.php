<h1 class="nombre-pagina"> Administrador</h1>
<p class="descripcion-pagina"> Revisa y chequea tus proximas citas</p>


<?php include_once __DIR__ . '/../templates/barra.php'; ?>


<h2>Buscar Citas</h2>

<div class="busqueda">
    <form class="formulario">
        <div class="campo">
            <label for="fecha">Fecha</label>
            <input type="date" id="fecha" name="fecha" value="<?php echo $fecha; ?>">
        </div>
    </form>
</div>

<?php 
    if(count($citas) === 0) {
        echo "<h2>No Hay citas para esta Fecha</h2>";
    }
?>

<div id="citas-admin">
    <ul class="citas">
    <?php 
     $idcita=0;
        foreach($citas as $key=> $cita):
            if($idcita !== $cita->id) {
                $total = 0;
                $idcita = $cita->id;
    ?> 
        <li>
                <p>ID: <span><?php echo $cita->id; ?></span></p>
                <p>Hora: <span><?php echo $cita->hora; ?></span></p>
                <p>Cliente: <span><?php echo $cita->cliente; ?></span></p>
                <p>Telefono: <span><?php echo $cita->telefono; ?></span></p>
                <p>Email: <span><?php echo $cita->email; ?></span></p>
                <h3>Servicios</h3>
    <?php }  //final del if
            $total += $cita->precio;
    ?> 
               
                <p class="servicio"><?php echo $cita->servicio . " " . $cita->precio; ?> </p>

    <?php 
        $actual = $cita->id;
        $proximo = $citas[$key + 1]->id ?? 0;

        if(esUltimo($actual,$proximo)) {?>
            <p class="total">Total a Pagar: <span><?php echo $total; ?></span></p>
            
            <form action="/api/eliminar" method="POST">
                 <input type="hidden" name="id" value="<?php echo $cita->id;?>">
                 <input type="submit" class="boton-eliminar" value="Eliminar">
            </form>
    <?php    
         } // fin del if
         endforeach; ?> <!-- cierre for each-->
    </ul>
</div>

<?php 

         $script ="<script src='build/js/buscador.js'></script>"
?>