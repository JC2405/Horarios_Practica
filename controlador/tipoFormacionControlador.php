<?php

include_once "../modelo/tipoFormacionModelo";

class tipoFormacionControlador {

    public $idTipoFormacion;
    public $tipo_formacion;


    public function ctrListarTipoFormacion(){
        $objRespuesta = tipoFormacionModelo::mdlListarTipoFormacion();
        echo json_encode($objRespuesta);
    }
}

if (isset($_POST["listarTipoFormacion"])) {
    $objRespuesta = new tipoContratoControlador();
    $objRespuesta->ctrListarTipoContrato();
}