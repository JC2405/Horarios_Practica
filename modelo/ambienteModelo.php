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

    
    public static function mdlRegistrarAmbientePorSede($codigo, $numero, $descripcion, $capacidad, $bloque, $estado, $idSede, $nombre, $tipoAmbiente){
    $mensaje = array();
    try {
        $objRespuesta = Conexion::Conectar()->prepare(
            "INSERT INTO ambiente (codigo, capacidad, numero, descripcion, bloque, estado, idSede, nombre, tipoAmbiente)
             VALUES (:codigo, :capacidad, :numero, :descripcion, :bloque, :estado, :idSede, :nombre, :tipoAmbiente)"
        );
        $objRespuesta->bindParam(":codigo", $codigo);
        $objRespuesta->bindParam(":capacidad", $capacidad);
        $objRespuesta->bindParam(":numero", $numero);
        $objRespuesta->bindParam(":descripcion", $descripcion);
        $objRespuesta->bindParam(":bloque", $bloque);
        $objRespuesta->bindParam(":estado", $estado);
        $objRespuesta->bindParam(":idSede", $idSede);
        $objRespuesta->bindParam(":nombre", $nombre);
        $objRespuesta->bindParam(":tipoAmbiente", $tipoAmbiente);

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


    public static function mdlEditarAmbientePorSede($idAmbiente, $codigo, $numero, $descripcion, $capacidad, $bloque, $estado, $idSede, $nombre, $tipoAmbiente){
    $mensaje = array();
    try {
        $validar = Conexion::Conectar()->prepare("
            SELECT COUNT(*) as total FROM ambiente
            WHERE codigo = :codigo AND idSede = :idSede AND idAmbiente <> :idAmbiente
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
                bloque = :bloque,
                estado = :estado,
                idSede = :idSede,
                nombre = :nombre,
                tipoAmbiente = :tipoAmbiente
            WHERE idAmbiente = :idAmbiente"
        );
        $objRespuesta->bindParam(":codigo", $codigo);
        $objRespuesta->bindParam(":capacidad", $capacidad);
        $objRespuesta->bindParam(":numero", $numero);
        $objRespuesta->bindParam(":descripcion", $descripcion);
        $objRespuesta->bindParam(":bloque", $bloque);
        $objRespuesta->bindParam(":estado", $estado);
        $objRespuesta->bindParam(":idSede", $idSede);
        $objRespuesta->bindParam(":nombre", $nombre);
        $objRespuesta->bindParam(":tipoAmbiente", $tipoAmbiente);
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
