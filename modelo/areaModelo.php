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


    public static function mdlRegistrarArea($nombreArea){
        $mensaje = array();

        try {
            $objRespuesta = Conexion::Conectar()->prepare("INSERT INTO area (nombreArea) values (:nombreArea)");
            $objRespuesta->bindParam(":nombreArea",$nombreArea);

            if($objRespuesta->execute())
                $mensaje = array("codigo" => "200" , "mensaje" => "Area Registrada Correctamente");
             
                else

                $mensaje = array("codigo"=>"401","mensaje" => "Error al agregar el area");    
                
        } catch (Exception $e) {
            $mensaje = array("codigo" => "401", "mensaje" => $e->getMessage());
        }
        return $mensaje;
    }


    public static function mdlEditarArea($idArea,$nombreArea){
        $mensaje = array();

        try {
            $objRespuesta = Conexion::Conectar()->prepare("UPDATE area set nombreArea =:nombreArea WHERE idArea = :idArea");
            $objRespuesta->bindParam(":idArea",$idArea);
            $objRespuesta->bindParam(":nombreArea", $nombreArea);
            
         if ($objRespuesta->execute())
                       $mensaje = array("codigo" => "200", "mensaje" => "Area actualizada correctamente");
                         else
                      $mensaje = array("codigo" => "401", "mensaje" => "Error al actualizar el Area");
                     } catch (Exception $e) {
                         $mensaje = array("codigo" => "401", "mensaje" => $e->getMessage());
                     }
                     return $mensaje;
            }
}