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
    
    public function ctrListarDias() {
        $objRespuesta = horarioModelo::mdlListarDias();
        echo json_encode($objRespuesta);
    }
    
    public function ctrObtenerDiasHorario($idHorario) {
        $objRespuesta = horarioModelo::mdlObtenerDiasHorario($idHorario);
        echo json_encode($objRespuesta);
    }
}


header('Content-Type: application/json');

// ========== LISTAR HORARIOS ==========
if (isset($_POST["listarHorarios"])) {
    $objControlador = new horarioControlador();
    $objControlador->ctrListarHorarios();
}

// ========== CREAR HORARIO ==========
if (isset($_POST["crearHorario"])) {
    // Procesar días como array
    $dias = isset($_POST['dias']) ? $_POST['dias'] : [];
    
    // Si viene como string JSON, decodificar
    if (is_string($dias)) {
        $dias = json_decode($dias, true);
    }
    
    $datos = array(
        'idFuncionario' => $_POST['idFuncionario'] ?: null,
        'idAmbiente' => $_POST['idAmbiente'] ?: null,
        'idFicha' => $_POST['idFicha'] ?: null,
        'hora_inicioClase' => $_POST['hora_inicioClase'],
        'hora_finClase' => $_POST['hora_finClase'],
        'fecha_inicioHorario' => $_POST['fecha_inicioHorario'] ?: null,
        'fecha_finHorario' => $_POST['fecha_finHorario'] ?: null,
        'dias' => $dias // Array de IDs de días
    );
    
    $objControlador = new horarioControlador();
    $objControlador->ctrCrearHorario($datos);
}

// ========== ACTUALIZAR HORARIO ==========
if (isset($_POST["actualizarHorario"])) {
    // Procesar días como array
    $dias = isset($_POST['dias']) ? $_POST['dias'] : [];
    
    if (is_string($dias)) {
        $dias = json_decode($dias, true);
    }
    
    $datos = array(
        'idHorario' => $_POST['idHorario'],
        'idAmbiente' => $_POST['idAmbiente'] ?: null,
        'hora_inicioClase' => $_POST['hora_inicioClase'],
        'hora_finClase' => $_POST['hora_finClase'],
        'fecha_inicioHorario' => $_POST['fecha_inicioHorario'] ?: null,
        'fecha_finHorario' => $_POST['fecha_finHorario'] ?: null,
        'dias' => $dias
    );
    
    $objControlador = new horarioControlador();
    $objControlador->ctrActualizarHorario($datos);
}

// ========== ELIMINAR HORARIO ==========
if (isset($_POST["eliminarHorario"])) {
    $objControlador = new horarioControlador();
    $objControlador->ctrEliminarHorario($_POST['idHorario']);
}

// ========== LISTAR DÍAS DE LA SEMANA ==========
if (isset($_POST["listarDias"])) {
    $objControlador = new horarioControlador();
    $objControlador->ctrListarDias();
}

// ========== OBTENER DÍAS DE UN HORARIO ==========
if (isset($_POST["obtenerDiasHorario"])) {
    $objControlador = new horarioControlador();
    $objControlador->ctrObtenerDiasHorario($_POST['idHorario']);
}