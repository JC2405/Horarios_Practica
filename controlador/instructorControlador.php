<?php

include_once "../modelo/instructorModelo.php";


class instructorControlador{
    public $idInstructor;
    public $nombre;
    public $correo;
    public $telefono;
    public $idTipoContrato;
    public $idArea;

    public function ctrListarInstructor(){
        $objRespuesta = instructorModelo::mdlListarInstructor();
        echo json_encode($objRespuesta);
    }

}


    if(isset($_POST["listarInstructor"])== "ok"){
        $objRespuesta = new instructorControlador();
        $objRespuesta ->ctrListarInstructor();
    }
