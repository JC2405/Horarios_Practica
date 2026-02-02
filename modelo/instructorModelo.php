<?php

include_once "conexion.php";


class instructorModelo {

    public static function mdlListarInstructor(){
        try {

            $mensaje = array();
            $objRespuesta = Conexion::Conectar()->prepare("SELECT f.idFuncionario as idInstructor,
                   f.nombre,
                   a.nombreArea,
                   fu.idRol
            FROM funcionario f
            INNER JOIN funcionariorol fu 
                ON fu.idFuncionario = f.idFuncionario
            INNER JOIN area a 
                ON a.idArea = f.idArea
            WHERE fu.idRol = 2;");
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
