<?php

include_once "conexion.php";

class ambienteModelo {

    public static function mdlListarAmbiente(){
        $mensaje = array();

        try {
            $objRespuesta = Conexion::Conectar()->prepare("SELECT * FROM ambiente");
            $objRespuesta->execute();
            $listarAmbiente = $objRespuesta->fetchAll();
            $objRespuesta = null;
            $mensaje = array("codigo"=>"200","mensaje"=>$listarAmbiente);
        } catch (Exception $e) {
            $mensaje = array("codigo"=>"400","mensaje"=>$e->getMessage());
        }
        return $mensaje;
    }

    
    public static function mdlListarAmbientesPorSede($idSede) {
        $mensaje = array();
        try {
             $objRespuesta = Conexion::Conectar()->prepare(
            "SELECT 
                a.*,
                s.nombre AS sedeNombre,
                m.nombreMunicipio AS sedeMunicipio
             FROM ambiente a
             INNER JOIN sede s ON a.idSede = s.idSede
             LEFT JOIN municipio m ON s.idMunicipio = m.idMunicipio
             WHERE a.idSede = :idSede
             ORDER BY a.codigo");
            $objRespuesta->execute([':idSede' => $idSede]);
            $listarAmbientes = $objRespuesta->fetchAll(PDO::FETCH_ASSOC);
            $objRespuesta = null;
            $mensaje = array("codigo" => "200", "ambientes" => $listarAmbientes);
        } catch (Exception $e) {
            $mensaje = array("codigo" => "400", "mensaje" => $e->getMessage());
        }
        return $mensaje;
    }

    
    public static function mdlRegistrarAmbientePorSede($codigo,$numero,$descripcion,$capacidad,$ubicacion,$estado,$idSede){
            $mensaje = array();

           try {
        $objRespuesta = Conexion::Conectar()->prepare(
            "INSERT INTO ambiente (codigo, capacidad, numero, descripcion, ubicacion, estado, idSede
            ) VALUES (:codigo, :capacidad, :numero, :descripcion, :ubicacion, :estado, :idSede
            )
        ");

        $objRespuesta->bindParam(":codigo", $codigo);
        $objRespuesta->bindParam(":capacidad", $capacidad);
        $objRespuesta->bindParam(":numero", $numero);
        $objRespuesta->bindParam(":descripcion", $descripcion);
        $objRespuesta->bindParam(":ubicacion", $ubicacion);
        $objRespuesta->bindParam(":estado", $estado);
        $objRespuesta->bindParam(":idSede", $idSede);

        if ($objRespuesta->execute()) {
            $mensaje = array("codigo" => "200", "mensaje" => "Ambiente registrado correctamente");
        } else {
            $mensaje = array("codigo" => "401", "mensaje" => "Error al registrar el ambiente");
        }

    } catch (Exception $e) {
        $mensaje = array("codigo" => "400", "mensaje" => $e->getMessage());
    }

    return $mensaje;
    }



    public static function mdlEditarAmbientePorSede($idAmbiente,$codigo,$numero,$descripcion,$capacidad,$ubicacion,$estado,$idSede){
    $mensaje = array();

    try {

        // (Opcional) validar duplicado: mismo codigo en la misma sede, pero otro idAmbiente
        // Si no lo necesitas, puedes borrar este bloque completo.
        $validar = Conexion::Conectar()->prepare("
            SELECT COUNT(*) as total
            FROM ambiente
            WHERE codigo = :codigo
              AND idSede = :idSede
              AND idAmbiente <> :idAmbiente
        ");
        $validar->bindParam(":codigo", $codigo);
        $validar->bindParam(":idSede", $idSede);
        $validar->bindParam(":idAmbiente", $idAmbiente);
        $validar->execute();
        $existe = $validar->fetch(PDO::FETCH_ASSOC);

        if ($existe && intval($existe["total"]) > 0) {
            return array("codigo"=>"409","mensaje"=>"Ya existe otro ambiente con ese código en la sede");
        }

        $objRespuesta = Conexion::Conectar()->prepare(
            "UPDATE ambiente SET
                codigo = :codigo,
                capacidad = :capacidad,
                numero = :numero,
                descripcion = :descripcion,
                ubicacion = :ubicacion,
                estado = :estado,
                idSede = :idSede
            WHERE idAmbiente = :idAmbiente");
        $objRespuesta->bindParam(":codigo", $codigo);
        $objRespuesta->bindParam(":capacidad", $capacidad);
        $objRespuesta->bindParam(":numero", $numero);
        $objRespuesta->bindParam(":descripcion", $descripcion);
        $objRespuesta->bindParam(":ubicacion", $ubicacion);
        $objRespuesta->bindParam(":estado", $estado);
        $objRespuesta->bindParam(":idSede", $idSede);
        $objRespuesta->bindParam(":idAmbiente", $idAmbiente);

        if ($objRespuesta->execute()) {
            $mensaje = array("codigo" => "200", "mensaje" => "Ambiente actualizado correctamente");
        } else {
            $mensaje = array("codigo" => "401", "mensaje" => "Error al actualizar el ambiente");
        }

    } catch (Exception $e) {
        $mensaje = array("codigo"=>"400","mensaje"=>$e->getMessage());
    }

    return $mensaje;
    }

}
