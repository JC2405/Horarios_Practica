<?php
include_once "conexion.php";

class fichaModelo{


    public static function mdlListarMunicipios(){
        $mensaje = array();

        try {
            $objRespuesta = Conexion::Conectar()->prepare(
                "SELECT idMunicipio, nombreMunicipio
                 FROM municipio
                 ORDER BY nombreMunicipio ASC"
            );

            $objRespuesta->execute();
            $listarMunicipios = $objRespuesta->fetchAll(PDO::FETCH_ASSOC);
            $objRespuesta = null;

            $mensaje = array("codigo"=>"200", "listarMunicipios"=>$listarMunicipios);

        } catch (Exception $e) {
            $mensaje = array("codigo"=>"400","mensaje"=>$e->getMessage());
        }

        return $mensaje;
    }


    public static function mdlListarSedesPorMunicipio($idMunicipio){
        $mensaje = array();

        try {
            $objRespuesta = Conexion::Conectar()->prepare(
                "SELECT idSede, nombre
                 FROM sede
                 WHERE idMunicipio = :idMunicipio
                 ORDER BY nombre ASC"
            );

            $objRespuesta->bindParam(":idMunicipio", $idMunicipio, PDO::PARAM_INT);
            $objRespuesta->execute();

            $listarSedes = $objRespuesta->fetchAll(PDO::FETCH_ASSOC);
            $objRespuesta = null;

            $mensaje = array("codigo"=>"200", "listarSedes"=>$listarSedes);

        } catch (Exception $e) {
            $mensaje = array("codigo"=>"400","mensaje"=>$e->getMessage());
        }

        return $mensaje;
    }


    
    public static function mdlListarAmbientesPorSede($idSede){
        $mensaje = array();

        try {
            $objRespuesta = Conexion::Conectar()->prepare(
                "SELECT 
                    idAmbiente,
                    codigo,
                    numero
                 FROM ambiente
                 WHERE idSede = :idSede
                 ORDER BY codigo ASC"
            );

            $objRespuesta->bindParam(":idSede", $idSede, PDO::PARAM_INT);
            $objRespuesta->execute();

            $listarAmbientes = $objRespuesta->fetchAll(PDO::FETCH_ASSOC);
            $objRespuesta = null;

            $mensaje = array("codigo"=>"200", "listarAmbientes"=>$listarAmbientes);

        } catch (Exception $e) {
            $mensaje = array("codigo"=>"400","mensaje"=>$e->getMessage());
        }

        return $mensaje;
    }


   
    public static function mdlListarProgramas(){
        $mensaje = array();

        try {
            $objRespuesta = Conexion::Conectar()->prepare(
                "SELECT 
                    p.idPrograma,
                    p.nombre,
                    tp.tipoFormacion,
                    tp.duracion
                 FROM programa p
                 INNER JOIN tipoprograma tp
                    ON p.idTipoFormacion = tp.idTipoPrograma
                 ORDER BY p.nombre ASC"
            );

            $objRespuesta->execute();
            $listarProgramas = $objRespuesta->fetchAll(PDO::FETCH_ASSOC);
            $objRespuesta = null;

            $mensaje = array("codigo"=>"200", "listarProgramas"=>$listarProgramas);

        } catch (Exception $e) {
            $mensaje = array("codigo"=>"400","mensaje"=>$e->getMessage());
        }

        return $mensaje;
    }


    
    public static function mdlListarFicha(){
        $mensaje = array();

        try {
          $objRespuesta = Conexion::Conectar()->prepare(
             "SELECT 
                f.codigoFicha,
                p.nombre AS programa,
                a.codigo AS ambiente,
                a.numero AS numeroAmbiente,
                f.estado,
                f.jornada,
                f.fechaInicio,
                f.fechaFin
            FROM ficha f
            INNER JOIN programa p 
                ON f.idPrograma = p.idPrograma
            INNER JOIN ambiente a 
                ON f.idAmbiente = a.idAmbiente
            ORDER BY f.idFicha DESC
            "
            );

            $objRespuesta->execute();
            $listarFicha = $objRespuesta->fetchAll(PDO::FETCH_ASSOC);
            $objRespuesta = null;

            $mensaje = array("codigo"=>"200", "listarFicha"=>$listarFicha);

        } catch (Exception $e) {
            $mensaje = array("codigo"=>"400","mensaje"=>$e->getMessage());
        }

        return $mensaje;
    }

}
