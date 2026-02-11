<?php

include_once "../modelo/programaModelo.php";


class ProgramaControlador {

    public $idPrograma;
    public $nombre;
    public $codigo;
    public $idTipoFormacion;
    public $version;
    public $estado;


    public function crtListarPrograma(){
        $objRespuesta = programaModelo::mdlListarPrograma();
        echo json_encode($objRespuesta);
    }


    public function crtAgregarPrograma(){
        $objRespuesta = programaModelo::mdlRegistrarPrograma($this->nombre,$this->codigo,$this->idTipoFormacion,$this->version,$this->estado);
        echo json_encode($objRespuesta);
    }


    public function crtEditarPrograma(){
        $objRespuesta = programaModelo::mdlEditarPrograma($this->idPrograma,$this->nombre,$this->codigo,$this->idTipoFormacion,$this->version,$this->estado);
        echo json_encode($objRespuesta);
    }
}


if (isset($_POST["listarPrograma"])) {
    $objRespuesta = new ProgramaControlador();
    $objRespuesta -> crtListarPrograma();
}

if (isset($_POST["agregarPrograma"])){
    $objRespuesta = new ProgramaControlador();
    $objRespuesta->nombre = $_POST["nombre"];
    $objRespuesta->codigo = $_POST["codigo"];
    $objRespuesta->idTipoFormacion = $_POST["idTipoFormacion"];
    $objRespuesta->version = $_POST["version"];
    $objRespuesta->estado = $_POST["estado"];
    $objRespuesta->crtAgregarPrograma();
}


if (isset($_POST["editarPrograma"])){
    $objRespuesta = new ProgramaControlador();
    $objRespuesta->idPrograma= $_POST["idPrograma"];
    $objRespuesta->nombre = $_POST["nombre"];
    $objRespuesta->codigo = $_POST["codigo"];
    $objRespuesta->idTipoFormacion = $_POST["idTipoFormacion"];
    $objRespuesta->version = $_POST["version"];
    $objRespuesta->estado = $_POST["estado"];
    $objRespuesta->crtEditarPrograma();
}