<?php 

include_once "conexion.php";

class areaModelo {

    public static function mdlListarArea(){
            $mensaje = array();
            try {
               $objRespuesta = Conexion::Conectar()->prepare(
                "SELECT * FROM area"
               );
               $objRespuesta->execute();
               $listarArea = $objRespuesta->fetchAll();
               $objRespuesta = null; 
               $mensaje = array("codigo" => "200", "listarArea" => $listarArea);
            } catch (Exception $e) {
                 $mensaje = array("codigo" => "401", "mensaje" => $e->getMessage());
            }
            return $mensaje;
    }

}