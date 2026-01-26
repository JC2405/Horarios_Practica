<?php

include_once "conexion.php";


class instructorModelo {

    public static function mdlListarInstructor(){
        try {

            $mensaje = array();
            $objRespuesta = Conexion::Conectar()->prepare("SELECT * FROM instructor");
            $objRespuesta->execute();
            $listarInstructor = $objRespuesta->fetchAll();
            $objRespuesta = null;
            $mensaje = array("codigo"=>"200","listarInstructor" => $listarInstructor);

        } catch (Exception $e) {
            $mensaje = array("codigo","400"=>$e->getMessage());
        }
        return $mensaje;
    }
}