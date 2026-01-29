<?php

include_once "conexion.php";

class fichaModelo{

    public static function mdlListarFichas(){
        try {
        $mensaje = array();
        $objRespuesta = Conexion::Conectar()->prepare("SELECT p.codigo,p.nombre,p.duracion,p.jornada,f.codigoFicha
        from ficha f
        inner join programa p 
        on p.idPrograma = f.idFicha;");
        $objRespuesta->execute();
        $listarFicha = $objRespuesta->fetchAll();
        $objRespuesta = null;
        $mensaje = array("codigo" => "200" , "listarFicha" => $listarFicha);
        } catch (Exception $e) {
        $mensaje = array("codigo" => "400" , "mensaje" =>$e->getMessage());
        }
        return $mensaje;
    }
}