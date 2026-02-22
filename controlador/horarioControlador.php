<?php

include_once "../modelo/horarioModelo.php";

/**
 * horarioControlador.php
 * REGLA: Sin SQL aquí. Solo recibe, arma y delega al modelo.
 */

class horarioControlador {

    public function ctrListarFichasConHorario() {
        $resultado = horarioModelo::mdlListarFichasConHorario();
        echo json_encode($resultado);
    }

    public function ctrListarHorariosPorFicha($idFicha) {
        $resultado = horarioModelo::mdlListarHorariosPorFicha($idFicha);
        echo json_encode($resultado);
    }

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

// ── LISTAR FICHAS CON HORARIO (tabla principal) ──
if (isset($_POST["listarFichasConHorario"])) {
    $ctrl = new horarioControlador();
    $ctrl->ctrListarFichasConHorario();
}

// ── LISTAR HORARIOS (clase horario.js legacy) ──
if (isset($_POST["listarHorarios"])) {
    $ctrl = new horarioControlador();
    $ctrl->ctrListarHorarios();
}

// ── LISTAR HORARIOS POR FICHA (modal calendario + modal eliminar) ──
if (isset($_POST["listarHorariosPorFicha"])) {
    $ctrl = new horarioControlador();
    $ctrl->ctrListarHorariosPorFicha($_POST['idFicha']);
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