<?php

include_once "../modelo/tipoContratoModelo.php";

class tipoContratoControlador {

    public $idTipoContrato;
    public $tipoContrato; 

    public function ctrListarTipoContrato(){
        $objRespuesta = tipoContratoModelo::mdlListarTipoContrato();
        echo json_encode($objRespuesta);
    }


    public function ctrAgregarTipoContrato(){
        $objRespuesta = tipoContratoModelo::mdlAgregarTipoContrato($this->tipoContrato);
        echo json_encode($objRespuesta);
    }


    public function ctrEditarTipoContrato(){
        $objRespuesta = tipoContratoModelo::mdlEditarTipoContrato($this->idTipoContrato,$this->tipoContrato);
        echo json_encode($objRespuesta);
    }
}


if(isset($_POST["listarTipoContrato"])){
    $objTipoContrato = new tipoContratoControlador();
    $objTipoContrato->ctrListarTipoContrato();
}


if (isset($_POST["agregarTipoContrato"])) {
    $objRespuesta = new tipoContratoControlador();
    $objRespuesta ->tipoContrato = $_POST["tipoContrato"];
    $objRespuesta -> ctrAgregarTipoContrato();
}


if(isset($_POST["editarTipoContrato"])){
    $objRespuesta = new tipoContratoControlador();
    $objRespuesta -> idTipoContrato = $_POST["idTipoContrato"];
    $objRespuesta -> tipoContrato = $_POST["tipoContrato"];
    $objRespuesta -> ctrEditarTipoContrato();
}