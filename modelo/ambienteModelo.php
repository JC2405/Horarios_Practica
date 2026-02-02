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

    // Listar ambientes por sede
    public static function mdlListarAmbientesPorSede($idSede) {
        $mensaje = array();
        try {
            $objRespuesta = Conexion::Conectar()->prepare(
                "SELECT a.*, s.municipio as sedeMunicipio, s.nombre as sedeNombre
                 FROM ambiente a
                 INNER JOIN sede s ON a.idSede = s.idSede
                 WHERE a.idSede = :idSede
                 ORDER BY a.numero"
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

    // Listar ambientes por ciudad (municipio)
    public static function mdlListarAmbientesPorCiudad($municipio) {
        $mensaje = array();
        try {
            $objRespuesta = Conexion::Conectar()->prepare(
                "SELECT a.*, s.municipio as sedeMunicipio, s.nombre as sedeNombre
                 FROM ambiente a
                 INNER JOIN sede s ON a.idSede = s.idSede
                 WHERE s.municipio = :municipio
                 ORDER BY s.nombre, a.numero"
            );
            $objRespuesta->execute([':municipio' => $municipio]);
            $listarAmbientes = $objRespuesta->fetchAll(PDO::FETCH_ASSOC);
            $objRespuesta = null;
            $mensaje = array("codigo" => "200", "ambientes" => $listarAmbientes);
        } catch (Exception $e) {
            $mensaje = array("codigo" => "400", "mensaje" => $e->getMessage());
        }
        return $mensaje;
    }
}
