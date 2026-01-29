<?php

include_once "../modelo/fichaModelo.php";

class fichaControlador {
    public $idFicha;
    public $codigoFicha;
    public $idPrograma;

    public function ctrListarFicha(){
        $objRespuesta = fichaModelo ::mdlListarFichas();
        echo json_encode($objRespuesta);
    }

}


if(isset($_POST["listarFicha"])){
    $objRespuesta = new fichaControlador();
    $objRespuesta -> ctrListarFicha();
}