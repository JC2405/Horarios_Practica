<?php

include_once "conexion.php";

class horarioModelo {

    // Listar todos los horarios para el calendario
    public static function mdlListarHorarios() {
        $mensaje = array();
        try {
            $objRespuesta = Conexion::Conectar()->prepare(
                "SELECT h.idHorario, h.fecha_inicioClase, h.hora_finClase,
                        f.nombre as instructorNombre, h.idFuncionario, h.idAmbiente, h.idFicha,
                        h.fecha_inicioHorario, h.fecha_finHorario,
                        a.codigo as ambienteNumero, a.ubicacion as ambienteDescripcion,
                        fi.codigoFicha
                 FROM horario h
                 LEFT JOIN funcionario f ON h.idFuncionario = f.idFuncionario
                 LEFT JOIN ambiente a ON h.idAmbiente = a.idAmbiente
                 LEFT JOIN ficha fi ON h.idFicha = fi.id"
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
                "SELECT h.idHorario, h.fecha_inicioClase, h.hora_finClase,
                        f.nombre as instructorNombre, h.idFuncionario, h.idAmbiente, h.idFicha,
                        h.fecha_inicioHorario, h.fecha_finHorario,
                        a.codigo as ambienteNumero, a.ubicacion as ambienteDescripcion,
                        fi.codigoFicha
                 FROM horario h
                 LEFT JOIN funcionario f ON h.idFuncionario = f.idFuncionario
                 LEFT JOIN ambiente a ON h.idAmbiente = a.idAmbiente
                 LEFT JOIN ficha fi ON h.idFicha = fi.id
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
                "INSERT INTO horario (idFuncionario, idAmbiente, idFicha, 
                        fecha_inicioClase, hora_finClase, fecha_inicioHorario, fecha_finHorario)
                 VALUES (:idFuncionario, :idAmbiente, :idFicha, 
                        :fecha_inicioClase, :hora_finClase, :fecha_inicioHorario, :fecha_finHorario)"
            );
            $objRespuesta->execute([
                ':idFuncionario' => $datos['idFuncionario'] ?? null,
                ':idAmbiente' => $datos['idAmbiente'] ?? null,
                ':idFicha' => $datos['idFicha'] ?? null,
                ':fecha_inicioClase' => $datos['hora_inicioClase'],
                ':hora_finClase' => $datos['hora_finClase'],
                ':fecha_inicioHorario' => $datos['fecha_inicioHorario'] ?? null,
                ':fecha_finHorario' => $datos['fecha_finHorario'] ?? null
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
                "UPDATE horario SET idAmbiente = :idAmbiente, 
                        fecha_inicioClase = :fecha_inicioClase, hora_finClase = :hora_finClase,
                        fecha_inicioHorario = :fecha_inicioHorario, fecha_finHorario = :fecha_finHorario
                 WHERE idHorario = :idHorario"
            );
            $objRespuesta->execute([
                ':idAmbiente' => $datos['idAmbiente'] ?? null,
                ':fecha_inicioClase' => $datos['hora_inicioClase'],
                ':hora_finClase' => $datos['hora_finClase'],
                ':fecha_inicioHorario' => $datos['fecha_inicioHorario'] ?? null,
                ':fecha_finHorario' => $datos['fecha_finHorario'] ?? null,
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
