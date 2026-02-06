<?php

include_once "conexion.php";


class sedeModelo {

    public static function mdlListarSedes(){
        $mensaje = array();

        try {
            $objRespuesta = Conexion::Conectar()->prepare("SELECT
              s.*,
              m.idMunicipio,
              m.nombreMunicipio
            FROM sede s
            INNER JOIN municipio m
              ON m.idSede = s.idSede;");
            $objRespuesta->execute();
            $listarSedes = $objRespuesta->fetchAll(PDO::FETCH_ASSOC);
            $objRespuesta = null; 

            $mensaje = array("codigo"=>"200", "listarSedes"=>$listarSedes);
        } catch (Exception $e) {
            $mensaje = array("codigo"=>"400","mensaje" =>$e->getMessage());
        }
        return $mensaje;
    }


}


