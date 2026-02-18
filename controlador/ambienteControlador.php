<?php

include_once "../modelo/ambienteModelo.php";

class ambienteControlador{
  public $idAmbiente;
    public $codigo;
    public $numero;
    public $descripcion;
    public $capacidad;
    public $bloque;      
    public $estado; 
    public $idSede;
    public $idArea;      
    public $tipoAmbiente; 

    public function ctrListarAmbiente(){
        $objRespuesta = ambienteModelo::mdlListarAmbiente();
        echo json_encode($objRespuesta);
    }

    public function ctrListarArea(){
        $objRespuesta = ambienteModelo::mdlListarAreas();
        echo json_encode($objRespuesta);
    }

    // Listar ambientes por sede
    public function ctrListarAmbientesPorSede(){
        $objRespuesta = ambienteModelo::mdlListarAmbientesPorSede($this->idSede);
        echo json_encode($objRespuesta);
    }


  public function ctrRegistrarAmbientePorSede(){
        $objRespuesta = ambienteModelo::mdlRegistrarAmbientePorSede($this->codigo, $this->numero, $this->descripcion,$this->capacidad, $this->bloque, $this->estado,$this->idSede, $this->idArea, $this->tipoAmbiente);
        echo json_encode($objRespuesta);
    }

    public function ctrEditarAmbientePorSede(){
        $objRespuesta = ambienteModelo::mdlEditarAmbientePorSede($this->idAmbiente, $this->codigo, $this->numero, $this->descripcion,$this->capacidad, $this->bloque, $this->estado,$this->idSede, $this->idArea, $this->tipoAmbiente);
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
    $objRespuesta->codigo       = $_POST["codigo"];
    $objRespuesta->numero       = $_POST["numero"];
    $objRespuesta->descripcion  = $_POST["descripcion"];
    $objRespuesta->capacidad    = $_POST["capacidad"];
    $objRespuesta->bloque       = $_POST["bloque"];       // antes: ubicacion
    $objRespuesta->estado       = $_POST["estado"];
    $objRespuesta->idSede       = $_POST["idSede"];
    $objRespuesta->idArea    = $_POST["idArea"];       // NUEVO
    $objRespuesta->tipoAmbiente = $_POST["tipoAmbiente"]; // NUEVO
    $objRespuesta->ctrRegistrarAmbientePorSede();
}

if (isset($_POST["editarAmbientePorSede"])) {
    $objRespuesta = new ambienteControlador();
    $objRespuesta->idAmbiente   = $_POST["idAmbiente"];
    $objRespuesta->codigo       = $_POST["codigo"];
    $objRespuesta->numero       = $_POST["numero"];
    $objRespuesta->descripcion  = $_POST["descripcion"];
    $objRespuesta->capacidad    = $_POST["capacidad"];
    $objRespuesta->bloque       = $_POST["bloque"];       // antes: ubicacion
    $objRespuesta->estado       = $_POST["estado"];
    $objRespuesta->idSede       = $_POST["idSede"];
    $objRespuesta->idArea       = $_POST["idArea"];       // NUEVO
    $objRespuesta->tipoAmbiente = $_POST["tipoAmbiente"]; // NUEVO
    $objRespuesta->ctrEditarAmbientePorSede();
}


if(isset($_POST["listarAreas"])){
    $objRespuesta = new ambienteControlador();
    $objRespuesta->ctrListarArea();
}
