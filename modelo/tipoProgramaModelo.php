    <?php 

    include_once "conexion.php";


    class tipoProgramaModelo {

        public static function mdlListarTipoPrograma(){
            $mensaje = array();
            try {
            
                $objRespuesta = Conexion::Conectar()->prepare("SELECT * FROM tipoPrograma");
                $objRespuesta->execute();
                $listarTipoPrograma = $objRespuesta -> fetchAll();
                $objRespuesta = null; 
                $mensaje = array ("codigo"=>"200","listarTipoPrograma"=>$listarTipoPrograma);
            } catch (Exception $e) {
                $mensaje = array ("codigo"=>"400","mensaje"=>$e->getMessage());
            }
            return $mensaje;
        }



            public static function mdlRegistrarTipoPrograma($tipoFormacion,$duracion){
                $mensaje = array();

                try {
                    $objRespuesta = Conexion::Conectar()->prepare("INSERT INTO tipoPrograma (tipoFormacion, duracion) VALUES (:tipoFormacion,:duracion)");
                    $objRespuesta->bindParam(":tipoFormacion",$tipoFormacion);
                    $objRespuesta->bindParam(":duracion",$duracion);

                    if ($objRespuesta->execute())
                        $mensaje = array("codigo" => "200", "mensaje" => "Tipo Programa agregada correctamente");
                    else
                        $mensaje = array("codigo" => "401", "mensaje" => "Error al agregar la tipo Programa");
                    
                } catch (Exception $e) {
                    $mensaje = array("codigo" => "401", "mensaje" => $e->getMessage());
                }    
                return $mensaje;
            }


            public static function mdlEditarTipoPrograma($idTipoPrograma, $tipoFormacion, $duracion){
                $mensaje = array();

                try {
                    $objRespuesta = Conexion::Conectar()->prepare(
                        "UPDATE tipoPrograma set tipoFormacion = :tipoFormacion, duracion = :duracion
                        WHERE idTipoPrograma = :idTipoPrograma");
                    $objRespuesta->bindParam(":idTipoPrograma", $idTipoPrograma);
                    $objRespuesta->bindParam(":tipoFormacion", $tipoFormacion);
                    $objRespuesta->bindParam(":duracion",$duracion);
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