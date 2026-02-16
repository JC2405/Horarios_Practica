<?php

include_once "conexion.php";


class instructorModelo {

    public static function mdlListarInstructor(){
        try {

            $mensaje = array();
            $objRespuesta = Conexion::Conectar()->prepare("SELECT 
                f.nombre,
                f.correo,
                f.telefono,
                f.estado,
                f.correo,
                a.nombreArea,
                tc.tipoContrato,
                r.nombreRol
            FROM funcionario f
            INNER JOIN funcionariorol fr ON f.idFuncionario = fr.idFuncionario
            INNER JOIN rol r ON fr.idRol = r.idRol
            INNER JOIN area a ON f.idArea = a.idArea
            INNER JOIN tipocontrato tc ON f.idTipoContrato = tc.idTipoContrato
            WHERE r.nombreRol = 'instructor';");
            $objRespuesta->execute();
            $listarInstructor = $objRespuesta->fetchAll();
            $objRespuesta = null;
            $mensaje = array("codigo"=>"200","listarInstructor" => $listarInstructor);

        } catch (Exception $e) {
            $mensaje = array("codigo"=>"400", "mensaje"=>$e->getMessage());
        }
        return $mensaje;
    }




    
    public static function mdlEditarInstructor($idFuncionario, $nombre, $correo, $telefono, $estado, $idArea, $idTipoContrato){
    $mensaje = array();
    try {

        $objRespuesta = Conexion::Conectar()->prepare(
            "UPDATE funcionario SET
                nombre = :nombre,
                correo = :correo,
                telefono = :telefono,
                estado = :estado,
                idArea = :idArea,
                idTipoContrato = :idTipoContrato
            WHERE idFuncionario = :idFuncionario
        ");

        $objRespuesta->bindParam(":idFuncionario", $idFuncionario);
        $objRespuesta->bindParam(":nombre", $nombre);
        $objRespuesta->bindParam(":correo", $correo);
        $objRespuesta->bindParam(":telefono", $telefono);
        $objRespuesta->bindParam(":estado", $estado);
        $objRespuesta->bindParam(":idArea", $idArea);
        $objRespuesta->bindParam(":idTipoContrato", $idTipoContrato);

        if ($objRespuesta->execute())
            $mensaje = array("codigo"=>"200","mensaje"=>"Instructor actualizado correctamente");
        else
            $mensaje = array("codigo"=>"401","mensaje"=>"Error al actualizar instructor");

         } catch (Exception $e) {
        $mensaje = array("codigo"=>"400","mensaje"=>$e->getMessage());
        }

         return $mensaje;
    }




    public static function mdlRegistrarInstructor($nombre, $correo, $telefono, $estado, $idArea, $idTipoContrato){

    $mensaje = array();

    try {

        $conexion = Conexion::Conectar();
        $conexion->beginTransaction();

        // 1️⃣ Insertar en funcionario
        $objRespuesta = $conexion->prepare("
            INSERT INTO funcionario 
            (nombre, correo, telefono, estado, idArea, idTipoContrato)
            VALUES 
            (:nombre, :correo, :telefono, :estado, :idArea, :idTipoContrato)
        ");

        $objRespuesta->bindParam(":nombre", $nombre);
        $objRespuesta->bindParam(":correo", $correo);
        $objRespuesta->bindParam(":telefono", $telefono);
        $objRespuesta->bindParam(":estado", $estado);
        $objRespuesta->bindParam(":idArea", $idArea);
        $objRespuesta->bindParam(":idTipoContrato", $idTipoContrato);

        if ($objRespuesta->execute()) {

            $idFuncionario = $conexion->lastInsertId();

            // 2️⃣ Insertar en funcionariorol (rol instructor = 2)
            $objRol = $conexion->prepare("
                INSERT INTO funcionariorol (idFuncionario, idRol)
                VALUES (:idFuncionario, :idRol)
            ");

            $idRol = 2; // instructor

            $objRol->bindParam(":idFuncionario", $idFuncionario);
            $objRol->bindParam(":idRol", $idRol);

            if ($objRol->execute()) {
                $conexion->commit();
                $mensaje = array("codigo"=>"200","mensaje"=>"Instructor agregado correctamente");
            } else {
                $conexion->rollBack();
                $mensaje = array("codigo"=>"401","mensaje"=>"Error al asignar el rol instructor");
            }

        } else {
            $conexion->rollBack();
            $mensaje = array("codigo"=>"401","mensaje"=>"Error al registrar instructor");
        }

    } catch (Exception $e) {

        if (isset($conexion)) {
            $conexion->rollBack();
        }

        $mensaje = array("codigo"=>"400","mensaje"=>$e->getMessage());
    }

    return $mensaje;
    }

}
 

