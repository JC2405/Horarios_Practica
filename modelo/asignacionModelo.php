<?php

include_once "conexion.php";

class asignacionModelo {

    // ========== LISTAR TODAS LAS ASIGNACIONES ==========
    public static function mdlListarAsignaciones(){
        $mensaje = array();
        try {
            $objRespuesta = Conexion::Conectar()->prepare("
                SELECT 
                    a.idAsignacion,
                    a.idAmbiente,
                    a.idFicha,
                    a.idInstructor,
                    a.jornada,
                    a.fechaCreacion,
                    amb.codigo as ambienteCodigo,
                    amb.descripcion as ambienteDescripcion,
                    amb.capacidad as ambienteCapacidad,
                    f.codigoFicha,
                    p.nombre as programaNombre,
                    i.nombre as instructorNombre,
                    s.idSede,
                    s.nombre as sedeNombre,
                    s.municipio as sedeMunicipio
                FROM asignacion a
                INNER JOIN ambiente amb ON a.idAmbiente = amb.idAmbiente
                INNER JOIN ficha f ON a.idFicha = f.idFicha
                INNER JOIN programa p ON f.idPrograma = p.idPrograma
                INNER JOIN funcionario i ON a.idInstructor = i.idFuncionario
                INNER JOIN sede s ON amb.idSede = s.idSede
                ORDER BY s.municipio, amb.codigo, a.jornada
            ");
            $objRespuesta->execute();
            $listarAsignaciones = $objRespuesta->fetchAll(PDO::FETCH_ASSOC);
            $objRespuesta = null;
            $mensaje = array("codigo" => "200", "asignaciones" => $listarAsignaciones);
        } catch (Exception $e) {
            $mensaje = array("codigo" => "400", "mensaje" => $e->getMessage());
        }
        return $mensaje;
    }

    // ========== LISTAR ASIGNACIONES POR SEDE ==========
    public static function mdlListarAsignacionesPorSede($idSede){
        $mensaje = array();
        try {
            $objRespuesta = Conexion::Conectar()->prepare("
                SELECT 
                    a.idAsignacion,
                    a.idAmbiente,
                    a.idFicha,
                    a.idInstructor,
                    a.jornada,
                    amb.codigo as ambienteCodigo,
                    amb.descripcion as ambienteDescripcion,
                    amb.capacidad as ambienteCapacidad,
                    f.codigoFicha,
                    p.nombre as programaNombre,
                    i.nombre as instructorNombre
                FROM asignacion a
                INNER JOIN ambiente amb ON a.idAmbiente = amb.idAmbiente
                INNER JOIN ficha f ON a.idFicha = f.idFicha
                INNER JOIN programa p ON f.idPrograma = p.idPrograma
                INNER JOIN funcionario i ON a.idInstructor = i.idFuncionario
                WHERE amb.idSede = :idSede
                ORDER BY amb.codigo, a.jornada
            ");
            $objRespuesta->execute([':idSede' => $idSede]);
            $listarAsignaciones = $objRespuesta->fetchAll(PDO::FETCH_ASSOC);
            $objRespuesta = null;
            $mensaje = array("codigo" => "200", "asignaciones" => $listarAsignaciones);
        } catch (Exception $e) {
            $mensaje = array("codigo" => "400", "mensaje" => $e->getMessage());
        }
        return $mensaje;
    }

    // ========== CREAR ASIGNACIÓN ==========
    public static function mdlCrearAsignacion($datos){
        $mensaje = array();
        $conn = Conexion::Conectar();
        
        try {
            // Validar que no exista ya esa combinación ambiente-jornada
            $objValidacion = $conn->prepare("
                SELECT COUNT(*) as total 
                FROM asignacion 
                WHERE idAmbiente = :idAmbiente 
                AND jornada = :jornada
            ");
            $objValidacion->execute([
                ':idAmbiente' => $datos['idAmbiente'],
                ':jornada' => $datos['jornada']
            ]);
            $resultado = $objValidacion->fetch(PDO::FETCH_ASSOC);
            
            if($resultado['total'] > 0){
                return array("codigo" => "409", "mensaje" => "Ya existe una asignación para este ambiente en esta jornada");
            }

            // Crear asignación
            $objRespuesta = $conn->prepare("
                INSERT INTO asignacion (idAmbiente, idFicha, idInstructor, jornada)
                VALUES (:idAmbiente, :idFicha, :idInstructor, :jornada)
            ");
            $objRespuesta->execute([
                ':idAmbiente' => $datos['idAmbiente'],
                ':idFicha' => $datos['idFicha'],
                ':idInstructor' => $datos['idInstructor'],
                ':jornada' => $datos['jornada']
            ]);
            $idAsignacion = $conn->lastInsertId();
            $objRespuesta = null;
            $mensaje = array("codigo" => "200", "mensaje" => "Asignación creada exitosamente", "idAsignacion" => $idAsignacion);
        } catch (Exception $e) {
            $mensaje = array("codigo" => "400", "mensaje" => $e->getMessage());
        }
        return $mensaje;
    }

    // ========== ELIMINAR ASIGNACIÓN ==========
    public static function mdlEliminarAsignacion($idAsignacion){
        $mensaje = array();
        try {
            $objRespuesta = Conexion::Conectar()->prepare("
                DELETE FROM asignacion WHERE idAsignacion = :idAsignacion
            ");
            $objRespuesta->execute([':idAsignacion' => $idAsignacion]);
            $objRespuesta = null;
            $mensaje = array("codigo" => "200", "mensaje" => "Asignación eliminada exitosamente");
        } catch (Exception $e) {
            $mensaje = array("codigo" => "400", "mensaje" => $e->getMessage());
        }
        return $mensaje;
    }
}
