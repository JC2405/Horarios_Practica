<?php

include_once "conexion.php";

class horarioModelo {

    // Listar todos los horarios para el calendario
    public static function mdlListarHorarios() {
        $mensaje = array();
        try {
            $objRespuesta = Conexion::Conectar()->prepare(
                "SELECT h.idHorario, h.hora_inicioClase, h.hora_finClase,
                        f.nombre as instructorNombre, h.idFuncionario, h.idAmbiente, h.idFicha,
                        h.fecha_inicioHorario, h.fecha_finHorario,
                        a.codigo as ambienteNumero, a.ubicacion as ambienteDescripcion,
                        fi.codigoFicha
                 FROM horario h
                 LEFT JOIN funcionario f ON h.idFuncionario = f.idFuncionario
                 LEFT JOIN ambiente a ON h.idAmbiente = a.idAmbiente
                 LEFT JOIN ficha fi ON h.idFicha = fi.idFicha"
            );
            $objRespuesta->execute();
            $listarHorarios = $objRespuesta->fetchAll(PDO::FETCH_ASSOC);
            $objRespuesta = null;
            
            // Renombrar campos para compatibilidad con el frontend
            foreach ($listarHorarios as &$horario) {
                $horario['fecha_inicioClase'] = $horario['hora_inicioClase'];
            }
            
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
                "SELECT h.idHorario, h.hora_inicioClase, h.hora_finClase,
                        f.nombre as instructorNombre, h.idFuncionario, h.idAmbiente, h.idFicha,
                        h.fecha_inicioHorario, h.fecha_finHorario,
                        a.codigo as ambienteNumero, a.ubicacion as ambienteDescripcion,
                        fi.codigoFicha
                 FROM horario h
                 LEFT JOIN funcionario f ON h.idFuncionario = f.idFuncionario
                 LEFT JOIN ambiente a ON h.idAmbiente = a.idAmbiente
                 LEFT JOIN ficha fi ON h.idFicha = fi.idFicha
                 WHERE h.idFicha = :idFicha"
            );
            $objRespuesta->execute([':idFicha' => $idFicha]);
            $listarHorarios = $objRespuesta->fetchAll(PDO::FETCH_ASSOC);
            $objRespuesta = null;
            
            // Renombrar campos para compatibilidad con el frontend
            foreach ($listarHorarios as &$horario) {
                $horario['fecha_inicioClase'] = $horario['hora_inicioClase'];
            }
            
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
            // Validar que idFicha no esté vacío
            if (empty($datos['idFicha'])) {
                return array("codigo" => "400", "mensaje" => "El ID de la ficha es obligatorio");
            }
            
            // Validar que idFicha sea numérico
            if (!is_numeric($datos['idFicha'])) {
                return array("codigo" => "400", "mensaje" => "El ID de la ficha debe ser numérico");
            }
            
            $objRespuesta = Conexion::Conectar()->prepare(
                "INSERT INTO horario (idFuncionario, idAmbiente, idFicha, 
                        hora_inicioClase, hora_finClase, fecha_inicioHorario, fecha_finHorario)
                 VALUES (:idFuncionario, :idAmbiente, :idFicha, 
                        :hora_inicioClase, :hora_finClase, :fecha_inicioHorario, :fecha_finHorario)"
            );
            
            $parametros = [
                ':idFuncionario' => !empty($datos['idFuncionario']) ? $datos['idFuncionario'] : null,
                ':idAmbiente' => !empty($datos['idAmbiente']) ? $datos['idAmbiente'] : null,
                ':idFicha' => $datos['idFicha'],
                ':hora_inicioClase' => $datos['hora_inicioClase'],
                ':hora_finClase' => $datos['hora_finClase'],
                ':fecha_inicioHorario' => !empty($datos['fecha_inicioHorario']) ? $datos['fecha_inicioHorario'] : null,
                ':fecha_finHorario' => !empty($datos['fecha_finHorario']) ? $datos['fecha_finHorario'] : null
            ];
            
            $objRespuesta->execute($parametros);
            $idInsertado = Conexion::Conectar()->lastInsertId();
            $objRespuesta = null;
            $mensaje = array("codigo" => "200", "mensaje" => "Horario creado exitosamente", "idHorario" => $idInsertado);
        } catch (Exception $e) {
            $mensaje = array("codigo" => "400", "mensaje" => "Error al crear horario: " . $e->getMessage());
        }
        return $mensaje;
    }

    // Actualizar horario (cuando se arrastra en el calendario)
    public static function mdlActualizarHorario($datos) {
        $mensaje = array();
        try {
            $objRespuesta = Conexion::Conectar()->prepare(
                "UPDATE horario SET idAmbiente = :idAmbiente, 
                        hora_inicioClase = :hora_inicioClase, hora_finClase = :hora_finClase,
                        fecha_inicioHorario = :fecha_inicioHorario, fecha_finHorario = :fecha_finHorario
                 WHERE idHorario = :idHorario"
            );
            $objRespuesta->execute([
                ':idAmbiente' => !empty($datos['idAmbiente']) ? $datos['idAmbiente'] : null,
                ':hora_inicioClase' => $datos['hora_inicioClase'],
                ':hora_finClase' => $datos['hora_finClase'],
                ':fecha_inicioHorario' => !empty($datos['fecha_inicioHorario']) ? $datos['fecha_inicioHorario'] : null,
                ':fecha_finHorario' => !empty($datos['fecha_finHorario']) ? $datos['fecha_finHorario'] : null,
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