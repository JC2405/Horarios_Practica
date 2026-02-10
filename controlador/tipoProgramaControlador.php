<?php

include_once "../modelo/tipoProgramaModelo.php";

class tipoFormacionControlador {

    public $idTipoFormacion;
    public $tipo_formacion;
    public $duracion;

    public function ctrListarTipoPrograma(){
        $objRespuesta = tipoProgramaModelo::mdlListarTipoPrograma();
        echo json_encode($objRespuesta);
    }
}

if (isset($_POST["listarTipoPrograma"])) {
    $objRespuesta = new tipoFormacionControlador();
    $objRespuesta->ctrListarTipoPrograma();
}