<?php

include_once "../modelo/tipoProgramaModelo.php";

class tipoFormacionControlador {

    public $idTipoPrograma;
    public $tipoFormacion;
    public $duracion;

    public function ctrListarTipoPrograma(){
        $objRespuesta = tipoProgramaModelo::mdlListarTipoPrograma();
        echo json_encode($objRespuesta);
    }


    public function ctrAgrarTipoPrograma(){
        $objRespuesta = tipoProgramaModelo::mdlRegistrarTipoPrograma($this->tipoFormacion,$this->duracion);
        echo json_encode($objRespuesta);
    }


    public function ctrEditarTipoFormacion(){
        $objRespuesta = tipoProgramaModelo::mdlEditarTipoPrograma($this->idTipoPrograma,$this->tipoFormacion,$this->duracion);
        echo json_encode($objRespuesta);
    }
}

if (isset($_POST["listarTipoPrograma"])) {
    $objRespuesta = new tipoFormacionControlador();
    $objRespuesta->ctrListarTipoPrograma();
}

if (isset($_POST["agregarTipoPrograma"])){
    $objRespuesta = new tipoFormacionControlador();
    $objRespuesta->tipoFormacion = $_POST["tipoFormacion"];
    $objRespuesta->duracion = $_POST["duracion"];
    $objRespuesta->ctrAgrarTipoPrograma();
}

if (isset($_POST["editarTipoPrograma"])){
    $objRespuesta = new tipoFormacionControlador();
    $objRespuesta->idTipoPrograma = $_POST["idTipoPrograma"];
    $objRespuesta->tipoFormacion = $_POST["tipoFormacion"];
    $objRespuesta->duracion = $_POST["duracion"];
    $objRespuesta->ctrEditarTipoFormacion();
}

