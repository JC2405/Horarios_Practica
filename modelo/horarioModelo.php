<?php

include_once "conexion.php";

class horarioModelo {

    // Listar todos los horarios para el calendario
    public static function mdlListarHorarios() {
        $mensaje = array();
        try {
            $objRespuesta = Conexion::Conectar()->prepare(
                "SELECT h.idHorario, h.titulo, h.fechaInicio, h.fechaFin, h.color,
                        i.nombre as instructorNombre, h.idInstructor
                 FROM horario h
                 INNER JOIN instructor i ON h.idInstructor = i.idInstructor"
            );
            $objRespuesta->execute();
            $listarHorarios = $objRespuesta->fetchAll(PDO::FETCH_ASSOC);
            $objRespuesta = null;
            $mensaje = array("codigo" => "200", "horarios" => $listarHorarios);
        } catch (Exception $e) {
            $mensaje = array("codigo" => "400", "mensaje" => $e->getMessage());
        }
        return $mensaje;
    }

    // Crear un nuevo horario
    public static function mdlCrearHorario($datos) {
        $mensaje = array();
        try {
            $objRespuesta = Conexion::Conectar()->prepare(
                "INSERT INTO horario (titulo, idInstructor, fechaInicio, fechaFin, color)
                 VALUES (:titulo, :idInstructor, :fechaInicio, :fechaFin, :color)"
            );
            $objRespuesta->execute([
                ':titulo' => $datos['titulo'],
                ':idInstructor' => $datos['idInstructor'],
                ':fechaInicio' => $datos['fechaInicio'],
                ':fechaFin' => $datos['fechaFin'],
                ':color' => $datos['color'] ?? '#3788d8'
            ]);
            $idInsertado = Conexion::Conectar()->lastInsertId();
            $objRespuesta = null;
            $mensaje = array("codigo" => "200", "mensaje" => "Horario creado", "idHorario" => $idInsertado);
        } catch (Exception $e) {
            $mensaje = array("codigo" => "400", "mensaje" => $e->getMessage());
        }
        return $mensaje;
    }

    // Actualizar horario (cuando se arrastra en el calendario)
    public static function mdlActualizarHorario($datos) {
        $mensaje = array();
        try {
            $objRespuesta = Conexion::Conectar()->prepare(
                "UPDATE horario SET fechaInicio = :fechaInicio, fechaFin = :fechaFin
                 WHERE idHorario = :idHorario"
            );
            $objRespuesta->execute([
                ':fechaInicio' => $datos['fechaInicio'],
                ':fechaFin' => $datos['fechaFin'],
                ':idHorario' => $datos['idHorario']
            ]);
            $objRespuesta = null;
            $mensaje = array("codigo" => "200", "mensaje" => "Horario actualizado");
        } catch (Exception $e) {
            $mensaje = array("codigo" => "400", "mensaje" => $e->getMessage());
        }
        return $mensaje;
    }

    // Eliminar horario
    public static function mdlEliminarHorario($idHorario) {
        $mensaje = array();
        try {
            $objRespuesta = Conexion::Conectar()->prepare(
                "DELETE FROM horario WHERE idHorario = :idHorario"
            );
            $objRespuesta->execute([':idHorario' => $idHorario]);
            $objRespuesta = null;
            $mensaje = array("codigo" => "200", "mensaje" => "Horario eliminado");
        } catch (Exception $e) {
            $mensaje = array("codigo" => "400", "mensaje" => $e->getMessage());
        }
        return $mensaje;
    }
}