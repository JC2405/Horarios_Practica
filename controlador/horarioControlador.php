<?php

    // Se incluye el modelo que contiene toda la lógica SQL
    include_once "../modelo/horarioModelo.php";

    /**
     * horarioControlador.php
     * ---------------------------------------------------------
     * REGLA IMPORTANTE:
     * ❌ No debe existir SQL en este archivo.
     * ✅ Solo recibe datos (POST), arma estructuras y delega al modelo.
     * ✅ Devuelve siempre respuesta en formato JSON.
     * ---------------------------------------------------------
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


 



    // ── LISTAR FICHAS CON HORARIO (tabla principal) ──
    if (isset($_POST["listarFichasConHorario"])) {
        $ctrl = new horarioControlador();
        $ctrl->ctrListarFichasConHorario();
    }


    // ── LISTAR TODOS LOS HORARIOS (uso legacy) ──
    if (isset($_POST["listarHorarios"])) {
        $ctrl = new horarioControlador();
        $ctrl->ctrListarHorarios();
    }


    // ── LISTAR HORARIOS POR FICHA (modal calendario / eliminar) ──
    if (isset($_POST["listarHorariosPorFicha"])) {
        $ctrl = new horarioControlador();
        $ctrl->ctrListarHorariosPorFicha($_POST['idFicha']);
    }


    // ── CREAR HORARIO ──
    if (isset($_POST["crearHorario"])) {

        // Se obtienen los días seleccionados
        $dias = isset($_POST['dias']) ? $_POST['dias'] : [];

        // Si vienen como string JSON, se convierten a array
        if (is_string($dias)) $dias = json_decode($dias, true);

        // Se arma el arreglo de datos para enviar al modelo
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


  
    if (isset($_POST["eliminarHorario"])) {
        $ctrl = new horarioControlador();
        $ctrl->ctrEliminarHorario($_POST['idHorario']);
    }



    if (isset($_POST["listarDias"])) {
        $ctrl = new horarioControlador();
        $ctrl->ctrListarDias();
    }


  
    if (isset($_POST["obtenerDiasHorario"])) {
        $ctrl = new horarioControlador();
        $ctrl->ctrObtenerDiasHorario($_POST['idHorario']);
    }



    if (isset($_POST["guardarHorariosCompleto"])) {
        $ctrl = new horarioControlador();
        $ctrl->ctrGuardarHorariosCompleto($_POST['horarios']);
    }


    if (isset($_POST["obtenerHorariosPorSede"])) {
        $ctrl = new horarioControlador();
        $ctrl->ctrObtenerHorariosPorSede($_POST['idSede']);
    }