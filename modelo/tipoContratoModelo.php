<?php


include_once "conexion.php";


class tipoContratoModelo {

    public static function mdlListarTipoContrato(){
    
    $mensaje = array();

    try {
         $objRespuesta = Conexion::Conectar()->prepare("SELECT * FROM tipocontrato");
         $objRespuesta -> execute();
        $listarTipoContrato = $objRespuesta->fetchAll();
         $objRespuesta = null ; 
         $mensaje = array("codigo"=>"200","listarTipoContrato"=> $listarTipoContrato );

    } catch (Exception $e) {
        $mensaje = array("codigo"=>"400","mensaje" => $e -> getMessage() );
    }
        return $mensaje;
    }
}