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
                "SELECT a.*, s.municipio as sedeMunicipio, s.nombre as sedeNombre
                 FROM ambiente a
                 INNER JOIN sede s ON a.idSede = s.idSede
                 WHERE a.idSede = :idSede
                 ORDER BY a.codigo"
            );
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
}
