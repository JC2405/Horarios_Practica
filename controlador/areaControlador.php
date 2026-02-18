<?php

include_once "../modelo/areaModelo.php";



class areaControlador {
    public $idArea;
    public $nombreArea;


    public function ctrListarArea(){
        $objRespuesta = areaModelo ::mdlListarArea();
        echo json_encode($objRespuesta);
    }

    public function ctrAgregarArea(){
       $objRespuesta = areaModelo ::mdlRegistrarArea($this->nombreArea);
        echo json_encode($objRespuesta);
    }


    public function ctrEditarArea(){
        $objRespuesta = areaModelo::mdlEditarArea($this->idArea,$this->nombreArea);
        echo json_encode($objRespuesta);
    }
}


if (isset($_POST["listarArea"])) {
    $objControlador = new areaControlador();
    $objControlador->ctrListarArea();
}

if (isset($_POST["registrarArea"])) {
    $objRespuesta = new areaControlador();
    $objRespuesta->nombreArea = $_POST["nombreArea"];
    $objRespuesta->ctrAgregarArea();
}


if(isset($_POST["editarArea"])){
    $objRespuesta = new areaControlador();
    $objRespuesta->idArea = $_POST["idArea"];
    $objRespuesta->nombreArea = $_POST["nombreArea"];
    $objRespuesta->ctrEditarArea();
}