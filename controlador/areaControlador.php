<?php

include_once "../modelo/areaModelo.php";



class areaControlador {
    public $idArea;
    public $nombreArea;


    public function ctrListarArea(){
        $objRespuesta = areaModelo ::mdlListarArea();
        echo json_encode($objRespuesta);
    }


    


}


if (isset($_POST["listarArea"])) {
    $objControlador = new areaControlador();
    $objControlador->ctrListarArea();
}