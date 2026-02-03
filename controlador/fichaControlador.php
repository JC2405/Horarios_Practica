<?php

include_once "../modelo/fichaModelo.php";

class fichaControlador {
    public $idFicha;
    public $codigoFicha;
    public $idPrograma;

    public function ctrListarFichas(){
        $objRespuesta = fichaModelo::mdlListarFichas();
        echo json_encode($objRespuesta);
    }

    public function ctrListarFichaHorario(){
        $objRespuesta = fichaModelo::mdlListarFichaHorario();
        echo json_encode($objRespuesta);
    }

    public function ctrListarTecnologos(){
        $objRespuesta = fichaModelo::mdlListarTecnologos();
        echo json_encode($objRespuesta);
    }
}

// Establecer el header de respuesta JSON
header('Content-Type: application/json');

if(isset($_POST["listarFichas"])){
    $objRespuesta = new fichaControlador();
    $objRespuesta->ctrListarFichas();
}

if (isset($_POST["listarFichaHorario"])){
    $objRespuesta = new fichaControlador();
    $objRespuesta->ctrListarFichaHorario();
}

if (isset($_POST["listarTecnologos"])){
    $objRespuesta = new fichaControlador();
    $objRespuesta->ctrListarTecnologos();
}