<?php

include_once "conexion.php";


/*SELECT
  f.idFuncionario,
  f.nombre,
  f.correo,
  f.telefono,
  f.estado,
  a.idArea,
  a.nombreArea,
  tc.tipoContrato,
  r.nombreRol
FROM funcionario f
INNER JOIN funcionariorol fr ON fr.idFuncionario = f.idFuncionario
INNER JOIN rol r ON r.idRol = fr.idRol
INNER JOIN tipocontrato tc ON tc.idTipoContrato = f.idTipoContrato
INNER JOIN funcionarioarea fa ON fa.idFuncionario = f.idFuncionario
INNER JOIN area a ON a.idArea = fa.idArea
WHERE r.nombreRol = 'instructor'
ORDER BY f.nombre, a.nombreArea;*/


class instructorModelo {

    public static function mdlListarInstructor(){
        try {

            $mensaje = array();
            $objRespuesta = Conexion::Conectar()->prepare("SELECT
                      f.idFuncionario,
                      f.nombre,
                      f.correo,
                      f.telefono,
                      f.estado,
                      GROUP_CONCAT(a.nombreArea ORDER BY a.nombreArea SEPARATOR ', ') AS nombreArea,
                      tc.tipoContrato,
                      r.nombreRol
                    FROM funcionario f
                    INNER JOIN funcionariorol fr ON fr.idFuncionario = f.idFuncionario
                    INNER JOIN rol r ON r.idRol = fr.idRol
                    INNER JOIN tipocontrato tc ON tc.idTipoContrato = f.idTipoContrato
                    INNER JOIN funcionarioarea fa ON fa.idFuncionario = f.idFuncionario
                    INNER JOIN area a ON a.idArea = fa.idArea
                    WHERE r.nombreRol = 'instructor'
                    GROUP BY f.idFuncionario
                    ORDER BY f.nombre;");
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
            "UPDATE funcionario f
            INNER JOIN funcionarioarea fa 
              ON fa.idFuncionario = f.idFuncionario
            SET
              f.nombre = :nombre,
              f.correo = :correo,
              f.telefono = :telefono,
              f.estado = :estado,
              f.idTipoContrato = :idTipoContrato,
              fa.idArea = :idArea
            WHERE f.idFuncionario = :idFuncionario;
        ");

    $objRespuesta->bindParam(":idFuncionario", $idFuncionario);
    $objRespuesta->bindParam(":nombre", $nombre);
    $objRespuesta->bindParam(":correo", $correo);
    $objRespuesta->bindParam(":telefono", $telefono);
    $objRespuesta->bindParam(":estado", $estado);
    $objRespuesta->bindParam(":idTipoContrato", $idTipoContrato);
    $objRespuesta->bindParam(":idArea", $idArea);


        if ($objRespuesta->execute())
            $mensaje = array("codigo"=>"200","mensaje"=>"Instructor actualizado correctamente");
        else
            $mensaje = array("codigo"=>"401","mensaje"=>"Error al actualizar instructor");

         } catch (Exception $e) {
        $mensaje = array("codigo"=>"400","mensaje"=>$e->getMessage());
        }

         return $mensaje;
    }




   public static function mdlRegistrarInstructor($nombre, $correo, $telefono, $estado, $idArea, $idTipoContrato, $password){

    $mensaje = array();

    try {

        $conexion = Conexion::Conectar();
        $conexion->beginTransaction();

        // 1️⃣ Insertar en funcionario (SIN idArea)
        $objRespuesta = $conexion->prepare("
            INSERT INTO funcionario 
            (nombre, correo, telefono, password, estado, idTipoContrato)
            VALUES 
            (:nombre, :correo, :telefono, :password, :estado, :idTipoContrato)
        ");

        $objRespuesta->bindParam(":nombre", $nombre);
        $objRespuesta->bindParam(":correo", $correo);
        $objRespuesta->bindParam(":telefono", $telefono);
        $objRespuesta->bindParam(":password", $password); // <-- SIN HASH
        $objRespuesta->bindParam(":estado", $estado);
        $objRespuesta->bindParam(":idTipoContrato", $idTipoContrato);

        if ($objRespuesta->execute()) {

            $idFuncionario = $conexion->lastInsertId();

            // 2️⃣ Insertar en funcionarioarea
            $objFA = $conexion->prepare("
                INSERT INTO funcionarioarea (idFuncionario, idArea)
                VALUES (:idFuncionario, :idArea)
            ");
            $objFA->bindParam(":idFuncionario", $idFuncionario);
            $objFA->bindParam(":idArea", $idArea);

            if ($objFA->execute()) {

                // 3️⃣ Insertar rol instructor
                $objRol = $conexion->prepare("
                    INSERT INTO funcionariorol (idFuncionario, idRol)
                    VALUES (:idFuncionario, :idRol)
                ");
                $idRol = 2;
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
                $mensaje = array("codigo"=>"401","mensaje"=>"Error al asignar el área al instructor");
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
 

