<?php 

include_once "conexion.php";


class tipoProgramaModelo {

    public static function mdlListarTipoPrograma(){
        try {
            $mensaje = array();
            $objRespuesta = Conexion::Conectar()->prepare("SELECT * FROM tipoPrograma");
            $objRespuesta->execute();
            $listarTipoPrograma = $objRespuesta -> fetchAll();
            $objRespuesta = null; 
            $mensaje = array ("codigo"=>"200","listarTipoFormacion"=>$listarTipoPrograma);
        } catch (Exception $e) {
            $mensaje = array ("codigo"=>"400","mensaje"=>$e->getMessage());
        }
        return $mensaje;
    }
}