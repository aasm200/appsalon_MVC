<?php 
namespace Controllers;

use Model\Cita;
use Model\CitaServicios;
use Model\Servicio;

class APIController {
    public static function index() {
       $servicios = Servicio::all();
        
       echo json_encode($servicios);

    }

    public static function guardar() {
        
        //almacena la cita y devuelve la id
        $cita = new Cita($_POST);
        $resultado = $cita->guardar();

        $id = $resultado['id'];

        //almacena las citas y los servicios con el id de la cita 
        $idServicios = explode(",",$_POST['servicios']);
        foreach($idServicios as $idServicio) {
            $args = [
                'citaId' => $id,
                'servicioId' => $idServicio
            ];
            $citaServicio = new CitaServicios($args);
            $citaServicio->guardar();
        }

        //retornamos una respuesta
     
        echo json_encode(['resultado' => $resultado]);

        // $respuesta = [
        //     'cita'=> $cita
        // ];  codigo ayudo a conocer la respuesta y los datos que recibe la api

    }

    public static function eliminar () {
       if($_SERVER['REQUEST_METHOD']=== 'POST') {
           $id = $_POST['id'];
           $cita = Cita::find($id);
           $cita->eliminar();
           header('Location:'. $_SERVER['HTTP_REFERER']);
         
       }
    }
}