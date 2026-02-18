<?php

use FFI\Exception;

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



    public static function mdlAgregarTipoContrato($tipoContrato){
    $mensaje = array();

    try {
        $objRespuesta = Conexion::Conectar()->prepare("INSERT INTO tipoContrato (tipoContrato) values (:tipoContrato)");
        $objRespuesta->bindParam(":tipoContrato",$tipoContrato);

        if ($objRespuesta->execute())
            $mensaje = array("codigo" => "200" ,"mensaje" => "Tipo De Contrato Registrado correctamente");
        else
            $mensaje = array("codigo" => "401" , "mensaje" => "Error al registrar tipo de contrato");

    } catch (Exception $e) {
        $mensaje = array("codigo" => "401" , "mensaje" => $e->getMessage());
        }
        return $mensaje;
    }


    public static function mdlEditarTipoContrato($idTipoContrato, $tipoContrato){
        $mensaje = array();

        try {
            $objRespuesta = Conexion::Conectar()->prepare(
                "UPDATE tipoContrato SET tipoContrato =:tipoContrato
                WHERE idTipoContrato =:idTipoContrato");
                $objRespuesta->bindParam(":idTipoContrato",$idTipoContrato);
                $objRespuesta->bindParam(":tipoContrato",$tipoContrato);

                if ($objRespuesta->execute()) 
                     $mensaje = array("codigo" => "200", "mensaje" => "Tipo Contrato actualizada correctamente");
                               else
                     $mensaje = array("codigo" => "401", "mensaje" => "Error al actualizar el Tipo Del Contrato");

        } catch (Exception $e) {
              $mensaje = array("codigo" => "401", "mensaje" => $e->getMessage());
        }
        return $mensaje;
    }
}