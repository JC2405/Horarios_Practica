<?php

include_once "conexion.php";

class fichaModelo{

    public static function mdlListarFichas(){
        try {
        $mensaje = array();
        $objRespuesta = Conexion::Conectar()->prepare("SELECT p.codigo,
             p.nombre,
             p.duracion,
             p.jornada,
             f.codigoFicha
        FROM ficha f
        INNER JOIN programa p
        ON p.idPrograma = f.idPrograma;
        ;");
        $objRespuesta->execute();
        $listarFichas = $objRespuesta->fetchAll();
        $objRespuesta = null;
        $mensaje = array("codigo" => "200" , "listarFichas" => $listarFichas);
        } catch (Exception $e) {
        $mensaje = array("codigo" => "400" , "mensaje" =>$e->getMessage());
        }
        return $mensaje;
    }





    public static function mdlListarFichaHorario(){
        try {
            $mensaje = array();
            $objRespuesta = Conexion::Conectar()->prepare("SELECT f.codigoFicha, p.nombre, p.duracion,p.jornada, s.municipio
            from ficha f
            inner join programa p ,sede s");
            $objRespuesta->execute();
            $listarFichaHorario = $objRespuesta->fetchAll();
            $objRespuesta = null ; 
            $mensaje = array("codigo"=>"200","listarFichaHorario"=> $listarFichaHorario);
        } catch (Exception $e) {
            $mensaje = array("codigo"=>"400", "mensaje"=>$e->getMessage());
        }
        return $mensaje;
    }




    public static function mdlListarTecnologos(){
        try {
            $mensaje = array();
            $objRespuesta = Conexion::Conectar()->prepare("SELECT f.codigoFicha,
       p.nombre AS programa,
       p.duracion,
       p.jornada,
       s.municipio
        FROM ficha f
        INNER JOIN programa p 
            ON p.idPrograma = f.idPrograma
        INNER JOIN sede s 
            ON s.idSede = f.idSede
        INNER JOIN tipoformacion tf 
            ON tf.idTipoFormacion = p.idTipoFormacion
        WHERE tf.`tipoFormacion` = 'Tecnólogo';");
            $objRespuesta->execute();
            $listarTecnologos = $objRespuesta->fetchAll();
            $objRespuesta = null;
            $mensaje = array("codigo"=>"200","listarTecnologos"=> $listarTecnologos);
        } catch (Exception $e) {
            $mensaje = array("codigo"=>"400", "mensaje"=>$e->getMessage());
        }
        return $mensaje;
    }
}