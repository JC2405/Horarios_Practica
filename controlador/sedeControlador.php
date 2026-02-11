<?php

include_once "../modelo/sedeModelo.php";

class sedeControlador {
   public $idSede;
    public $nombre;
    public $direccion;
    public $descripcion;
    public $estado;
    public $idMunicipio;


    public function ctrListarSede(){
        $objRespuesta = sedeModelo::mdlListarSedes();
        echo json_encode($objRespuesta);
    }
    
     public function ctrAgregarSede(){
        $objRespuesta = sedeModelo::mdlRegistrarSede($this->nombre,$this->direccion,$this->descripcion,$this->estado,$this->idMunicipio);
        echo json_encode($objRespuesta);
    }
}


if(isset($_POST["listarSede"])){
    $objRespuesta = new sedeControlador();
    $objRespuesta ->ctrListarSede();
}

if(isset($_POST["agregarSede"])){
    $objRespuesta = new sedeControlador();
    $objRespuesta->nombre = $_POST["nombre"];
    $objRespuesta->direccion = $_POST["direccion"];
    $objRespuesta->descripcion = $_POST["descripcion"];
    $objRespuesta->estado = $_POST["estado"];
    $objRespuesta->idMunicipio = $_POST["idMunicipio"];
    $objRespuesta->ctrAgregarSede();
}