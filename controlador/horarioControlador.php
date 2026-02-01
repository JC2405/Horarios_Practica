<?php

include_once "../modelo/horarioModelo.php";

class horarioControlador {

    public function ctrListarHorarios() {
        $objRespuesta = horarioModelo::mdlListarHorarios();
        echo json_encode($objRespuesta);
    }

    public function ctrCrearHorario($datos) {
        $objRespuesta = horarioModelo::mdlCrearHorario($datos);
        echo json_encode($objRespuesta);
    }

    public function ctrActualizarHorario($datos) {
        $objRespuesta = horarioModelo::mdlActualizarHorario($datos);
        echo json_encode($objRespuesta);
    }

    public function ctrEliminarHorario($idHorario) {
        $objRespuesta = horarioModelo::mdlEliminarHorario($idHorario);
        echo json_encode($objRespuesta);
    }
}


header('Content-Type: application/json');

if (isset($_POST["listarHorarios"])) {
    $objControlador = new horarioControlador();
    $objControlador->ctrListarHorarios();
}

if (isset($_POST["crearHorario"])) {
    $datos = array(
        'titulo' => $_POST['titulo'],
        'idInstructor' => $_POST['idInstructor'],
        'fechaInicio' => $_POST['fechaInicio'],
        'fechaFin' => $_POST['fechaFin'],
        'color' => $_POST['color'] ?? '#3788d8'
    );
    $objControlador = new horarioControlador();
    $objControlador->ctrCrearHorario($datos);
}

if (isset($_POST["actualizarHorario"])) {
    $datos = array(
        'idHorario' => $_POST['idHorario'],
        'fechaInicio' => $_POST['fechaInicio'],
        'fechaFin' => $_POST['fechaFin']
    );
    $objControlador = new horarioControlador();
    $objControlador->ctrActualizarHorario($datos);
}

if (isset($_POST["eliminarHorario"])) {
    $objControlador = new horarioControlador();
    $objControlador->ctrEliminarHorario($_POST['idHorario']);
}