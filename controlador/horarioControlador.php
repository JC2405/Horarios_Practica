<?php

include_once "../modelo/horarioModelo.php";

/**
 * horarioControlador.php
 * 
 * REGLA: Este controlador NO contiene consultas SQL.
 * Toda la lógica de datos está en horarioModelo.php.
 * El controlador solo recibe el request, arma los datos y delega al modelo.
 */

class horarioControlador {

    public function ctrListarHorarios() {
        $resultado = horarioModelo::mdlListarHorarios();
        echo json_encode($resultado);
    }

    public function ctrCrearHorario($datos) {
        $resultado = horarioModelo::mdlCrearHorario($datos);
        echo json_encode($resultado);
    }

    public function ctrActualizarHorario($datos) {
        $resultado = horarioModelo::mdlActualizarHorario($datos);
        echo json_encode($resultado);
    }

    public function ctrEliminarHorario($idHorario) {
        $resultado = horarioModelo::mdlEliminarHorario($idHorario);
        echo json_encode($resultado);
    }

    public function ctrListarDias() {
        $resultado = horarioModelo::mdlListarDias();
        echo json_encode($resultado);
    }

    public function ctrObtenerDiasHorario($idHorario) {
        $resultado = horarioModelo::mdlObtenerDiasHorario($idHorario);
        echo json_encode($resultado);
    }

    public function ctrGuardarHorariosCompleto($horariosJSON) {
        $resultado = horarioModelo::mdlGuardarHorariosCompleto($horariosJSON);
        echo json_encode($resultado);
    }

    public function ctrObtenerHorariosPorSede($idSede) {
        $resultado = horarioModelo::mdlObtenerHorariosPorSede($idSede);
        echo json_encode($resultado);
    }
}


header('Content-Type: application/json');

// ── LISTAR HORARIOS ──
if (isset($_POST["listarHorarios"])) {
    $ctrl = new horarioControlador();
    $ctrl->ctrListarHorarios();
}

// ── CREAR HORARIO ──
if (isset($_POST["crearHorario"])) {
    $dias = isset($_POST['dias']) ? $_POST['dias'] : [];
    if (is_string($dias)) $dias = json_decode($dias, true);

    $datos = array(
        'idFuncionario'       => $_POST['idFuncionario']       ?: null,
        'idAmbiente'          => $_POST['idAmbiente']          ?: null,
        'idFicha'             => $_POST['idFicha']             ?: null,
        'hora_inicioClase'    => $_POST['hora_inicioClase'],
        'hora_finClase'       => $_POST['hora_finClase'],
        'fecha_inicioHorario' => $_POST['fecha_inicioHorario'] ?: null,
        'fecha_finHorario'    => $_POST['fecha_finHorario']    ?: null,
        'dias'                => $dias,
    );

    $ctrl = new horarioControlador();
    $ctrl->ctrCrearHorario($datos);
}

// ── ACTUALIZAR HORARIO ──
if (isset($_POST["actualizarHorario"])) {
    $dias = isset($_POST['dias']) ? $_POST['dias'] : [];
    if (is_string($dias)) $dias = json_decode($dias, true);

    $datos = array(
        'idHorario'           => $_POST['idHorario'],
        'idAmbiente'          => $_POST['idAmbiente']          ?: null,
        'idFuncionario'       => $_POST['idFuncionario']       ?? null,
        'hora_inicioClase'    => $_POST['hora_inicioClase'],
        'hora_finClase'       => $_POST['hora_finClase'],
        'fecha_inicioHorario' => $_POST['fecha_inicioHorario'] ?: null,
        'fecha_finHorario'    => $_POST['fecha_finHorario']    ?: null,
        'dias'                => $dias,
    );

    $ctrl = new horarioControlador();
    $ctrl->ctrActualizarHorario($datos);
}

// ── ELIMINAR HORARIO ──
if (isset($_POST["eliminarHorario"])) {
    $ctrl = new horarioControlador();
    $ctrl->ctrEliminarHorario($_POST['idHorario']);
}

// ── LISTAR DÍAS ──
if (isset($_POST["listarDias"])) {
    $ctrl = new horarioControlador();
    $ctrl->ctrListarDias();
}

// ── OBTENER DÍAS DE UN HORARIO ──
if (isset($_POST["obtenerDiasHorario"])) {
    $ctrl = new horarioControlador();
    $ctrl->ctrObtenerDiasHorario($_POST['idHorario']);
}

// ── GUARDAR HORARIOS EN BATCH ──
if (isset($_POST["guardarHorariosCompleto"])) {
    $ctrl = new horarioControlador();
    $ctrl->ctrGuardarHorariosCompleto($_POST['horarios']);
}

// ── OBTENER HORARIOS POR SEDE ──
if (isset($_POST["obtenerHorariosPorSede"])) {
    $ctrl = new horarioControlador();
    $ctrl->ctrObtenerHorariosPorSede($_POST['idSede']);
}