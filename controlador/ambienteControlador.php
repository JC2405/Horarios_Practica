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
    public $municipio;

    public function ctrListarAmbiente(){
        $objRespuesta = ambienteModelo::mdlListarAmbiente();
        echo json_encode($objRespuesta);
    }

    // Listar ambientes por sede
    public function ctrListarAmbientesPorSede(){
        $objRespuesta = ambienteModelo::mdlListarAmbientesPorSede($this->idSede);
        echo json_encode($objRespuesta);
    }

    // Listar ambientes por ciudad
    public function ctrListarAmbientesPorCiudad(){
        $objRespuesta = ambienteModelo::mdlListarAmbientesPorCiudad($this->municipio);
        echo json_encode($objRespuesta);
    }
}

if(isset($_POST["listarAmbiente"])){
    $objRespuesta = new ambienteControlador();
    $objRespuesta->ctrListarAmbiente();
}

if(isset($_POST["listarAmbientesPorSede"])){
    $objRespuesta = new ambienteControlador();
    $objRespuesta->idSede = $_POST["idSede"];
    $objRespuesta->ctrListarAmbientesPorSede();
}

if(isset($_POST["listarAmbientesPorCiudad"])){
    $objRespuesta = new ambienteControlador();
    $objRespuesta->municipio = $_POST["municipio"];
    $objRespuesta->ctrListarAmbientesPorCiudad();
}
