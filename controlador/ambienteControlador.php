<?php

include_once "../modelo/ambienteModelo.php";

class ambienteControlador{
    public $idAmbiente;
    public $codigo;
    public $numero;
    public $descripcion;
    public $capacidad;
    public $ubicacion; 
    public $estado; 
    public $idSede;

    public function ctrListarAmbiente(){
        $objRespuesta = ambienteModelo::mdlListarAmbiente();
        echo json_encode($objRespuesta);
    }

    // Listar ambientes por sede
    public function ctrListarAmbientesPorSede(){
        $objRespuesta = ambienteModelo::mdlListarAmbientesPorSede($this->idSede);
        echo json_encode($objRespuesta);
    }


    public function ctrRegistrarAmbientePorSede(){
        $objRespuesta = ambienteModelo::mdlRegistrarAmbientePorSede($this->codigo,$this->numero,$this->descripcion,$this->capacidad,$this->ubicacion,$this->estado,$this->idSede);
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

if (isset($_POST["registrarAmbientePorSede"])) {
    $objRespuesta = new ambienteControlador();
   
    $objRespuesta-> codigo=$_POST["codigo"];
    $objRespuesta-> numero=$_POST["numero"];
    $objRespuesta-> descripcion=$_POST["descripcion"];
    $objRespuesta-> capacidad=$_POST["capacidad"];
    $objRespuesta-> ubicacion=$_POST["ubicacion"];
    $objRespuesta-> estado=$_POST["estado"];
    $objRespuesta-> idSede=$_POST["idSede"];
    $objRespuesta->ctrRegistrarAmbientePorSede();

}


