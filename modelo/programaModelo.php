<?php

include_once "conexion.php";


class programaModelo {

    public static function mdlListarPrograma(){
        $mensaje = array();

        try {
            $objRespuesta = Conexion::Conectar()->prepare("SELECT 
              p.idPrograma,
              p.nombre,
              p.codigo,
              p.version,
              p.estado,
              t.tipoFormacion,
              t.duracion
            FROM programa p
            INNER JOIN tipoprograma t
              ON p.idTipoFormacion = t.idTipoPrograma;");
            $objRespuesta->execute();
            $objListarPrograma = $objRespuesta->fetchAll();
            $objRespuesta = null ; 
            $mensaje = array("codigo"=>"200","listarPrograma"=>$objListarPrograma);
        } catch (Exception $e) {
            $mensaje = array("codigo"=>"400","mensaje"=>$e->getMessage());
        }
        return $mensaje;
    }

    public static function mdlRegistrarPrograma($nombre, $codigo, $idTipoFormacion, $version, $estado){
    $mensaje = array();
    try {
        $objRespuesta = Conexion::Conectar()->prepare("INSERT INTO programa (nombre,codigo,idTipoFormacion,version,estado)
         VALUES (:nombre,:codigo,:idTipoFormacion,:version,:estado)");
        $objRespuesta->bindParam(":nombre",$nombre);
        $objRespuesta->bindParam(":codigo",$codigo);
        $objRespuesta->bindParam(":idTipoFormacion",$idTipoFormacion);
        $objRespuesta->bindParam(":version",$version);
        $objRespuesta->bindParam(":estado",$estado);

            if ($objRespuesta->execute())
             $mensaje = array("codigo" => "200", "mensaje" => "Programa agregada correctamente");
            else
            $mensaje = array("codigo" => "401", "mensaje" => "Error al agregar la Programa");
                
            } catch (Exception $e) {
                $mensaje = array("codigo" => "401", "mensaje" => $e->getMessage());
            }    
            return $mensaje;
    }


    public static function mdlEditarPrograma($idPrograma,$nombre, $codigo, $idTipoFormacion, $version, $estado){

    $mensaje = array();

    try {
        $objRespuesta = Conexion::Conectar()->prepare(
            "UPDATE programa set nombre =:nombre , codigo =:codigo , idTipoFormacion =:idTipoFormacion , version=:version,estado=:estado
            WHERE idPrograma =:idPrograma");
        $objRespuesta->bindParam(":idPrograma",$idPrograma);
        $objRespuesta->bindParam(":nombre",$nombre);
        $objRespuesta->bindParam(":codigo",$codigo);
        $objRespuesta->bindParam(":idTipoFormacion",$idTipoFormacion);
        $objRespuesta->bindParam(":version",$version);
        $objRespuesta->bindParam(":estado",$estado);

        if ($objRespuesta->execute())
                $mensaje = array("codigo" => "200", "mensaje" => "Tipo Programa actualizada correctamente");
        else
                 $mensaje = array("codigo" => "401", "mensaje" => "Error al actualizar el Tipo Del Programa");

          } catch (Exception $e) {
              $mensaje = array("codigo" => "401", "mensaje" => $e->getMessage());
          }
          return $mensaje;
            }
}