<?php

include_once "conexion.php";


class sedeModelo {

    public static function mdlListarSedes(){
        $mensaje = array();

        try {
            $objRespuesta = Conexion::Conectar()->prepare("SELECT * FROM sede");
            $objRespuesta->execute();
            $listarSedes = $objRespuesta->fetchAll();
            $objRespuesta = null; 

            $mensaje = array("codigo"=>"200", "listarSedes"=>$listarSedes);
        } catch (Exception $e) {
            $mensaje = array("codigo"=>"400","mensaje" =>$e->getMessage());
        }
        return $mensaje;
    }
}