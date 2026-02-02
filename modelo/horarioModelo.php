<?php

include_once "conexion.php";

class horarioModelo {

    // Listar todos los horarios para el calendario
    public static function mdlListarHorarios() {
        $mensaje = array();
        try {
            $objRespuesta = Conexion::Conectar()->prepare(
                "SELECT h.idHorario, h.titulo, h.fechaInicio, h.fechaFin, h.color,
                        i.nombre as instructorNombre, h.idInstructor, h.idAmbiente, h.idFicha,
                        a.numero as ambienteNumero, a.descripcion as ambienteDescripcion,
                        f.codigoFicha
                 FROM horario h
                 LEFT JOIN instructor i ON h.idInstructor = i.idInstructor
                 LEFT JOIN ambiente a ON h.idAmbiente = a.idAmbiente
                 LEFT JOIN ficha f ON h.idFicha = f.id"
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

    // Listar horarios por ficha
    public static function mdlListarHorariosPorFicha($idFicha) {
        $mensaje = array();
        try {
            $objRespuesta = Conexion::Conectar()->prepare(
                "SELECT h.idHorario, h.titulo, h.fechaInicio, h.fechaFin, h.color,
                        i.nombre as instructorNombre, h.idInstructor, h.idAmbiente, h.idFicha,
                        a.numero as ambienteNumero, a.descripcion as ambienteDescripcion,
                        f.codigoFicha
                 FROM horario h
                 LEFT JOIN instructor i ON h.idInstructor = i.idInstructor
                 LEFT JOIN ambiente a ON h.idAmbiente = a.idAmbiente
                 LEFT JOIN ficha f ON h.idFicha = f.id
                 WHERE h.idFicha = :idFicha"
            );
            $objRespuesta->execute([':idFicha' => $idFicha]);
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
                "INSERT INTO horario (titulo, idInstructor, idAmbiente, idFicha, fechaInicio, fechaFin, color)
                 VALUES (:titulo, :idInstructor, :idAmbiente, :idFicha, :fechaInicio, :fechaFin, :color)"
            );
            $objRespuesta->execute([
                ':titulo' => $datos['titulo'],
                ':idInstructor' => $datos['idInstructor'],
                ':idAmbiente' => $datos['idAmbiente'] ?? null,
                ':idFicha' => $datos['idFicha'] ?? null,
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
                "UPDATE horario SET idAmbiente = :idAmbiente, fechaInicio = :fechaInicio, fechaFin = :fechaFin
                 WHERE idHorario = :idHorario"
            );
            $objRespuesta->execute([
                ':idAmbiente' => $datos['idAmbiente'] ?? null,
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
