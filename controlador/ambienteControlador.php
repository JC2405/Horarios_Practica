<?php

include_once "../modelo/ambienteModelo.php";

class ambienteControlador{
    public $idAmbiente;
    public $codigo;
    public $numero;
    public $descripcion;
    public $capacidadPersonas;
    public $ubicacion; 
    public $estado; 
    public $idSede;

    public function ctrListarAmbiente(){
        $objRespuesta = ambienteModelo::mdlListarAmbiente();
        echo json_encode($objRespuesta);
    }
}