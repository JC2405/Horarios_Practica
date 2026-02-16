<?php

include_once "../modelo/instructorModelo.php";


class instructorControlador{
    
    public $idInstructor;
    public $nombre;
    public $correo;
    public $telefono;
    public $estado;
    public $idTipoContrato;
    public $idArea;

    public function ctrListarInstructor(){
        $objRespuesta = instructorModelo::mdlListarInstructor();
        echo json_encode($objRespuesta);
    }

    
    public function ctrAgregarInstructor(){
        $objRespuesta = instructorModelo::mdlRegistrarInstructor($this->nombre,$this->correo,$this->telefono,$this->estado,$this->idArea,$this->idTipoContrato);
        echo json_encode($objRespuesta);
    }


    public function ctrEditarInstructor(){
        $objRespuesta = instructorModelo::mdlEditarInstructor($this->idInstructor,$this->nombre,$this->correo,$this->telefono,$this->estado,$this->idArea,$this->idTipoContrato);
        echo json_encode($objRespuesta);
    }

}


    if(isset($_POST["listarInstructor"])){
        $objRespuesta = new instructorControlador();
        $objRespuesta ->ctrListarInstructor();
    }


    
    if(isset($_POST["agregarInstructor"])){
    $objRespuesta = new instructorControlador();
    $objRespuesta->nombre = $_POST["nombre"];
    $objRespuesta->correo = $_POST["correo"];
    $objRespuesta->telefono = $_POST["telefono"];
    $objRespuesta->estado = $_POST["estado"];
    $objRespuesta->idArea = $_POST["idArea"];
    $objRespuesta->idTipoContrato = $_POST["idTipoContrato"];
    $objRespuesta->ctrAgregarInstructor();
    }


    if(isset($_POST["editarInstructor"])){
    $objRespuesta = new instructorControlador();
    $objRespuesta->idInstructor = $_POST["idInstructor"];
    $objRespuesta->nombre = $_POST["nombre"];
    $objRespuesta->correo = $_POST["correo"];
    $objRespuesta->telefono = $_POST["telefono"];
    $objRespuesta->estado = $_POST["estado"];
    $objRespuesta->idArea = $_POST["idArea"];
    $objRespuesta->idTipoContrato = $_POST["idTipoContrato"];
    $objRespuesta->ctrEditarInstructor();
    }