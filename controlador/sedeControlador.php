<?php

include_once "../modelo/sedeModelo.php";

class sedeControlador {
    public $idSede;
    public $nombre;
    public $direccion;
    public $municipio;


    public function ctrListarSede(){
        $objRespuesta = sedeModelo::mdlListarSedes();
        echo json_encode($objRespuesta);
    }

}


if(isset($_POST["listarSede"])){
    $objRespuesta = new sedeControlador();
    $objRespuesta ->ctrListarSede();
}