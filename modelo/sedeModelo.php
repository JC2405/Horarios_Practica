<?php

include_once "conexion.php";


class sedeModelo {

    public static function mdlListarSedes(){
        $mensaje = array();

        try {
            $objRespuesta = Conexion::Conectar()->prepare(" SELECT
                 s.idSede,
                 s.nombre,
                 s.direccion,
                 s.descripcion,
                 s.estado,
                 m.nombreMunicipio
             FROM sede s
             LEFT JOIN municipio m
                 ON s.idMunicipio = m.idMunicipio;");
            $objRespuesta->execute();
            $listarSedes = $objRespuesta->fetchAll(PDO::FETCH_ASSOC);
            $objRespuesta = null; 

            $mensaje = array("codigo"=>"200", "listarSedes"=>$listarSedes);
        } catch (Exception $e) {
            $mensaje = array("codigo"=>"400","mensaje" =>$e->getMessage());
        }
        return $mensaje;
    }


    public static function mdlRegistrarSede($nombre, $direccion, $descripcion, $estado, $idMunicipio){

    $mensaje = array();

    try {

        $objRespuesta = Conexion::Conectar()->prepare("
            INSERT INTO sede (
                nombre, direccion, descripcion, estado, idMunicipio
            )
            VALUES (
                :nombre, :direccion, :descripcion, :estado, :idMunicipio
            )
        ");

        $objRespuesta->bindParam(":nombre", $nombre);
        $objRespuesta->bindParam(":direccion", $direccion);
        $objRespuesta->bindParam(":descripcion", $descripcion);
        $objRespuesta->bindParam(":estado", $estado);
        $objRespuesta->bindParam(":idMunicipio", $idMunicipio);

        if ($objRespuesta->execute())
            $mensaje = array("codigo" => "200", "mensaje" => "Sede agregada correctamente");
        else
            $mensaje = array("codigo" => "401", "mensaje" => "Error al agregar la sede");

    } catch (Exception $e) {

        $mensaje = array("codigo" => "401", "mensaje" => $e->getMessage());

    }

    return $mensaje;
    }


    
    public static function mdlEditarSede($idSede, $nombre, $direccion, $descripcion, $estado, $idMunicipio){

    $mensaje = array();
    try {
        $objRespuesta = Conexion::Conectar()->prepare("
            UPDATE sede 
            SET 
                nombre = :nombre,
                direccion = :direccion,
                descripcion = :descripcion,
                estado = :estado,
                idMunicipio = :idMunicipio
            WHERE idSede = :idSede
        ");

        $objRespuesta->bindParam(":idSede", $idSede);
        $objRespuesta->bindParam(":nombre", $nombre);
        $objRespuesta->bindParam(":direccion", $direccion);
        $objRespuesta->bindParam(":descripcion", $descripcion);
        $objRespuesta->bindParam(":estado", $estado);
        $objRespuesta->bindParam(":idMunicipio", $idMunicipio);

        if ($objRespuesta->execute())
            $mensaje = array("codigo" => "200", "mensaje" => "Sede actualizada correctamente");
        else
            $mensaje = array("codigo" => "401", "mensaje" => "Error al actualizar la sede");

    } catch (Exception $e) {
        $mensaje = array("codigo" => "401", "mensaje" => $e->getMessage());
    }
    return $mensaje;
    }

}


