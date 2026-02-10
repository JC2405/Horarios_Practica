<?php

include_once "conexion.php";


class programaModelo {

    public static function mdlListarPrograma(){
        $mensaje = array();

        try {
            $objRespuesta = Conexion::Conectar()->prepare("SELECT 
              p.idPrograma,
              p.nombre,
              p.codigo,
              p.version,
              p.estado,
              t.tipoFormacion,
              t.duracion
            FROM programa p
            INNER JOIN tipoprograma t
              ON p.idTipoFormacion = t.idTipoPrograma;");
            $objRespuesta->execute();
            $objListarPrograma = $objRespuesta->fetchAll();
            $objRespuesta = null ; 
            $mensaje = array("codigo"=>"200","listarPrograma"=>$objListarPrograma);
        } catch (Exception $e) {
            $mensaje = array("codigo"=>"400","mensaje"=>$e->getMessage());
        }
        return $mensaje;
    }


}