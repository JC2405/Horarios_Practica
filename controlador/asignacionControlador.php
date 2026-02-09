<?php

include_once "../modelo/asignacionModelo.php";

class asignacionControlador {
    
    public $idAsignacion;
    public $idAmbiente;
    public $idFicha;
    public $idInstructor;
    public $jornada;
    public $idSede;

    // Listar todas las asignaciones
    public function ctrListarAsignaciones(){
        $objRespuesta = asignacionModelo::mdlListarAsignaciones();
        echo json_encode($objRespuesta);
    }

    // Listar asignaciones por sede
    public function ctrListarAsignacionesPorSede(){
        $objRespuesta = asignacionModelo::mdlListarAsignacionesPorSede($this->idSede);
        echo json_encode($objRespuesta);
    }

    // Crear asignación
    public function ctrCrearAsignacion(){
        $datos = array(
            'idAmbiente' => $this->idAmbiente,
            'idFicha' => $this->idFicha,
            'idInstructor' => $this->idInstructor,
            'jornada' => $this->jornada
        );
        $objRespuesta = asignacionModelo::mdlCrearAsignacion($datos);
        echo json_encode($objRespuesta);
    }

    // Eliminar asignación
    public function ctrEliminarAsignacion(){
        $objRespuesta = asignacionModelo::mdlEliminarAsignacion($this->idAsignacion);
        echo json_encode($objRespuesta);
    }
}

// ========== ENDPOINTS ==========
header('Content-Type: application/json');

if(isset($_POST["listarAsignaciones"])){
    $obj = new asignacionControlador();
    $obj->ctrListarAsignaciones();
}

if(isset($_POST["listarAsignacionesPorSede"])){
    $obj = new asignacionControlador();
    $obj->idSede = $_POST["idSede"];
    $obj->ctrListarAsignacionesPorSede();
}

if(isset($_POST["crearAsignacion"])){
    $obj = new asignacionControlador();
    $obj->idAmbiente = $_POST["idAmbiente"];
    $obj->idFicha = $_POST["idFicha"];
    $obj->idInstructor = $_POST["idInstructor"];
    $obj->jornada = $_POST["jornada"];
    $obj->ctrCrearAsignacion();
}

if(isset($_POST["eliminarAsignacion"])){
    $obj = new asignacionControlador();
    $obj->idAsignacion = $_POST["idAsignacion"];
    $obj->ctrEliminarAsignacion();
}
