<?php

include_once "conexion.php";

class instructorModelo {

    // ========== LISTAR TODOS LOS INSTRUCTORES ==========
    public static function mdlListarInstructor(){
        try {
            $mensaje = array();
            $objRespuesta = Conexion::Conectar()->prepare("
                SELECT
                    f.idFuncionario,
                    f.nombre,
                    f.correo,
                    f.telefono,
                    f.estado,
                    GROUP_CONCAT(a.nombreArea ORDER BY a.nombreArea SEPARATOR ', ') AS nombreArea,
                    MIN(fa.idArea) AS idArea,
                    tc.tipoContrato,
                    r.nombreRol
                FROM funcionario f
                INNER JOIN funcionariorol fr  ON fr.idFuncionario = f.idFuncionario
                INNER JOIN rol r              ON r.idRol          = fr.idRol
                INNER JOIN tipocontrato tc    ON tc.idTipoContrato = f.idTipoContrato
                INNER JOIN funcionarioarea fa ON fa.idFuncionario = f.idFuncionario
                INNER JOIN area a             ON a.idArea         = fa.idArea
                WHERE r.nombreRol = 'instructor'
                GROUP BY f.idFuncionario
                ORDER BY f.nombre;
            ");
            $objRespuesta->execute();
            $listarInstructor = $objRespuesta->fetchAll(PDO::FETCH_ASSOC);
            $objRespuesta = null;
            $mensaje = array("codigo" => "200", "listarInstructor" => $listarInstructor);
        } catch (Exception $e) {
            $mensaje = array("codigo" => "400", "mensaje" => $e->getMessage());
        }
        return $mensaje;
    }

    // ========== LISTAR INSTRUCTORES POR ÁREA ==========
    public static function mdlListarInstructoresPorArea($idArea) {
        try {
            $conn = Conexion::Conectar();

            $stmtDelArea = $conn->prepare("
                SELECT
                    f.idFuncionario,
                    f.nombre,
                    MIN(fa.idArea)  AS idArea,
                    GROUP_CONCAT(a.nombreArea ORDER BY a.nombreArea SEPARATOR ', ') AS nombreArea
                FROM funcionario f
                INNER JOIN funcionariorol  fr ON fr.idFuncionario = f.idFuncionario
                INNER JOIN rol              r ON r.idRol          = fr.idRol
                INNER JOIN funcionarioarea fa ON fa.idFuncionario = f.idFuncionario
                INNER JOIN area             a ON a.idArea         = fa.idArea
                WHERE r.nombreRol = 'instructor'
                  AND fa.idArea   = :idArea
                GROUP BY f.idFuncionario
                ORDER BY f.nombre
            ");
            $stmtDelArea->bindParam(':idArea', $idArea, PDO::PARAM_INT);
            $stmtDelArea->execute();
            $instructoresDelArea = $stmtDelArea->fetchAll(PDO::FETCH_ASSOC);

            // Grupo 2: instructores que NO tienen esa área asignada
            $stmtResto = $conn->prepare("
                SELECT
                    f.idFuncionario,
                    f.nombre,
                    MIN(fa.idArea) AS idArea,
                    GROUP_CONCAT(a.nombreArea ORDER BY a.nombreArea SEPARATOR ', ') AS nombreArea
                FROM funcionario f
                INNER JOIN funcionariorol  fr ON fr.idFuncionario = f.idFuncionario
                INNER JOIN rol              r ON r.idRol          = fr.idRol
                INNER JOIN funcionarioarea fa ON fa.idFuncionario = f.idFuncionario
                INNER JOIN area             a ON a.idArea         = fa.idArea
                WHERE r.nombreRol = 'instructor'
                  AND f.idFuncionario NOT IN (
                      SELECT fa2.idFuncionario
                      FROM funcionarioarea fa2
                      WHERE fa2.idArea = :idArea
                  )
                GROUP BY f.idFuncionario
                ORDER BY f.nombre
            ");
            $stmtResto->bindParam(':idArea', $idArea, PDO::PARAM_INT);
            $stmtResto->execute();
            $instructoresResto = $stmtResto->fetchAll(PDO::FETCH_ASSOC);

            return array(
                "codigo"              => "200",
                "instructoresDelArea" => $instructoresDelArea,
                "instructoresResto"   => $instructoresResto,
                "totalDelArea"        => count($instructoresDelArea)
            );

        } catch (Exception $e) {
            return array("codigo" => "400", "mensaje" => $e->getMessage());
        }
    }

    // ========== EDITAR INSTRUCTOR ==========
    public static function mdlEditarInstructor($idFuncionario, $nombre, $correo, $telefono, $estado, $idArea, $idTipoContrato){
        $mensaje = array();
        try {
            $objRespuesta = Conexion::Conectar()->prepare(
                "UPDATE funcionario f
                INNER JOIN funcionarioarea fa ON fa.idFuncionario = f.idFuncionario
                SET
                    f.nombre         = :nombre,
                    f.correo         = :correo,
                    f.telefono       = :telefono,
                    f.estado         = :estado,
                    f.idTipoContrato = :idTipoContrato,
                    fa.idArea        = :idArea
                WHERE f.idFuncionario = :idFuncionario"
            );
            $objRespuesta->bindParam(":idFuncionario", $idFuncionario);
            $objRespuesta->bindParam(":nombre",        $nombre);
            $objRespuesta->bindParam(":correo",        $correo);
            $objRespuesta->bindParam(":telefono",      $telefono);
            $objRespuesta->bindParam(":estado",        $estado);
            $objRespuesta->bindParam(":idTipoContrato",$idTipoContrato);
            $objRespuesta->bindParam(":idArea",        $idArea);

            if ($objRespuesta->execute())
                $mensaje = array("codigo" => "200", "mensaje" => "Instructor actualizado correctamente");
            else
                $mensaje = array("codigo" => "401", "mensaje" => "Error al actualizar instructor");
        } catch (Exception $e) {
            $mensaje = array("codigo" => "400", "mensaje" => $e->getMessage());
        }
        return $mensaje;
    }



    // ========== REGISTRAR INSTRUCTOR ==========
    public static function mdlRegistrarInstructor($nombre, $correo, $telefono, $estado, $idArea, $idTipoContrato, $password){
        $mensaje = array();
        try {
            $conexion = Conexion::Conectar();
            $conexion->beginTransaction();

            $objRespuesta = $conexion->prepare("
                INSERT INTO funcionario (nombre, correo, telefono, password, estado, idTipoContrato)
                VALUES (:nombre, :correo, :telefono, :password, :estado, :idTipoContrato)
            ");
            $objRespuesta->bindParam(":nombre",        $nombre);
            $objRespuesta->bindParam(":correo",        $correo);
            $objRespuesta->bindParam(":telefono",      $telefono);
            $objRespuesta->bindParam(":password",      $password);
            $objRespuesta->bindParam(":estado",        $estado);
            $objRespuesta->bindParam(":idTipoContrato",$idTipoContrato);

            if ($objRespuesta->execute()) {
                $idFuncionario = $conexion->lastInsertId();

                $objFA = $conexion->prepare("INSERT INTO funcionarioarea (idFuncionario, idArea) VALUES (:idFuncionario, :idArea)");
                $objFA->bindParam(":idFuncionario", $idFuncionario);
                $objFA->bindParam(":idArea",        $idArea);

                if ($objFA->execute()) {
                    $objRol  = $conexion->prepare("INSERT INTO funcionariorol (idFuncionario, idRol) VALUES (:idFuncionario, :idRol)");
                    $idRol   = 2;
                    $objRol->bindParam(":idFuncionario", $idFuncionario);
                    $objRol->bindParam(":idRol",         $idRol);

                    if ($objRol->execute()) {
                        $conexion->commit();
                        $mensaje = array("codigo" => "200", "mensaje" => "Instructor agregado correctamente");
                    } else {
                        $conexion->rollBack();
                        $mensaje = array("codigo" => "401", "mensaje" => "Error al asignar el rol instructor");
                    }
                } else {
                    $conexion->rollBack();
                    $mensaje = array("codigo" => "401", "mensaje" => "Error al asignar el área al instructor");
                }
            } else {
                $conexion->rollBack();
                $mensaje = array("codigo" => "401", "mensaje" => "Error al registrar instructor");
            }
        } catch (Exception $e) {
            if (isset($conexion)) $conexion->rollBack();
            $mensaje = array("codigo" => "400", "mensaje" => $e->getMessage());
        }
        return $mensaje;
    }
}