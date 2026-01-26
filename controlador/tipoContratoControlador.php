<?php

include_once "../modelo/tipoContratoModelo.php";

class tipoContratoControlador {

    public $idTipoContrato;
    public $tipo_contrato; 

    public function ctrListarTipoContrato(){
        $objRespuesta = tipoContratoModelo::mdlListarTipoContrato();
        echo json_encode($objRespuesta);
    }
}


if(isset($_POST["listarTipoContrato"])){
    $objTipoContrato = new tipoContratoControlador();
    $objTipoContrato->ctrListarTipoContrato();
}