<?php 

include_once "conexion.php";


class tipoFormacionModelo {

    public static function mdlListarTipoFormacion(){
        try {
            $mensaje = array();
            $objRespuesta = Conexion::Conectar()->prepare("SELECT * FROM tipoformacion");
            $objRespuesta->execute();
            $listarTipoFormacion = $objRespuesta -> fetchAll();
            $objRespuesta = null; 
            $mensaje = array ("codigo"=>"200","listarTipoFormacion"=>$listarTipoFormacion);
        } catch (Exception $e) {
            $mensaje = array ("codigo"=>"400","mensaje"=>$e->getMessage());
        }
        return $mensaje;
    }
}