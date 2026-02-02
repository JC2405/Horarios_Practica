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
        'idFuncionario' => $_POST['idFuncionario'] ?: null,
        'idAmbiente' => $_POST['idAmbiente'] ?: null,
        'idFicha' => $_POST['idFicha'] ?: null,
        'hora_inicioClase' => $_POST['hora_inicioClase'],
        'hora_finClase' => $_POST['hora_finClase'],
        'fecha_inicioHorario' => $_POST['fecha_inicioHorario'] ?: null,
        'fecha_finHorario' => $_POST['fecha_finHorario'] ?: null
    );
    $objControlador = new horarioControlador();
    $objControlador->ctrCrearHorario($datos);
}

if (isset($_POST["actualizarHorario"])) {
    $datos = array(
        'idHorario' => $_POST['idHorario'],
        'idAmbiente' => $_POST['idAmbiente'] ?: null,
        'hora_inicioClase' => $_POST['hora_inicioClase'],
        'hora_finClase' => $_POST['hora_finClase'],
        'fecha_inicioHorario' => $_POST['fecha_inicioHorario'] ?: null,
        'fecha_finHorario' => $_POST['fecha_finHorario'] ?: null
    );
    $objControlador = new horarioControlador();
    $objControlador->ctrActualizarHorario($datos);
}

if (isset($_POST["eliminarHorario"])) {
    $objControlador = new horarioControlador();
    $objControlador->ctrEliminarHorario($_POST['idHorario']);
}
