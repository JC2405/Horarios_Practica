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
                        a.idAmbiente,
                        a.codigo,
                        a.numero,
                        ar.idArea,
                        ar.nombreArea
                    FROM ambiente a
                    INNER JOIN area ar 
                        ON a.idArea = ar.idArea
                    WHERE a.idSede = :idSede
                    ORDER BY a.codigo ASC"
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
                f.idFicha,
                f.codigoFicha,
                f.idAmbiente,         
                p.nombre AS programa,
                s.idSede,             
                s.nombre AS sede,
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
            INNER JOIN sede s
                ON a.idSede = s.idSede"
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

            

        public static function mdlRegistrarFicha($codigoFicha, $idPrograma, $idAmbiente, $estado, $jornada, $fechaInicio, $fechaFin){
         $mensaje = array();
    
            try {
        $conexion = Conexion::Conectar();
        
        // ✅ VALIDACIÓN CRÍTICA: Verificar conflicto de fechas/jornada en el mismo ambiente
        $sqlValidacion = "
            SELECT COUNT(*) as total 
            FROM ficha 
            WHERE idAmbiente = :idAmbiente 
            AND jornada = :jornada
            AND estado = 'Activo'
            AND (
                -- Caso 1: La nueva ficha comienza dentro de una existente
                (:fechaInicio BETWEEN fechaInicio AND fechaFin)
                OR
                -- Caso 2: La nueva ficha termina dentro de una existente
                (:fechaFin BETWEEN fechaInicio AND fechaFin)
                OR
                -- Caso 3: La nueva ficha abarca completamente una existente
                (fechaInicio BETWEEN :fechaInicio AND :fechaFin)
                OR
                -- Caso 4: La nueva ficha está contenida en una existente
                (fechaFin BETWEEN :fechaInicio AND :fechaFin)
            )
        ";
        
        $stmtValidacion = $conexion->prepare($sqlValidacion);
        $stmtValidacion->bindParam(":idAmbiente", $idAmbiente, PDO::PARAM_INT);
        $stmtValidacion->bindParam(":jornada", $jornada, PDO::PARAM_STR);
        $stmtValidacion->bindParam(":fechaInicio", $fechaInicio, PDO::PARAM_STR);
        $stmtValidacion->bindParam(":fechaFin", $fechaFin, PDO::PARAM_STR);
        $stmtValidacion->execute();
        
        $resultado = $stmtValidacion->fetch(PDO::FETCH_ASSOC);
        
        if ($resultado['total'] > 0) {
            return array(
                "codigo" => "409",
                "mensaje" => "⚠️ CONFLICTO: Ya existe una ficha activa en este ambiente durante esa jornada y rango de fechas. Un ambiente solo puede tener 1 ficha por jornada."
            );
        }
        

        $objRespuesta = $conexion->prepare(
            "INSERT INTO ficha (codigoFicha, idPrograma, idAmbiente, estado, jornada, fechaInicio, fechaFin)
            VALUES (:codigoFicha, :idPrograma, :idAmbiente, :estado, :jornada, :fechaInicio, :fechaFin)"
        );
        
        $objRespuesta->bindParam(":codigoFicha", $codigoFicha);
        $objRespuesta->bindParam(":idPrograma", $idPrograma);
        $objRespuesta->bindParam(":idAmbiente", $idAmbiente);
        $objRespuesta->bindParam(":estado", $estado);
        $objRespuesta->bindParam(":jornada", $jornada);
        $objRespuesta->bindParam(":fechaInicio", $fechaInicio);
        $objRespuesta->bindParam(":fechaFin", $fechaFin);
        
        if ($objRespuesta->execute()) {
            $mensaje = array("codigo" => "200", "mensaje" => "✅ Ficha registrada correctamente");
        } else {
            $mensaje = array("codigo" => "401", "mensaje" => "❌ Error al registrar la ficha");
        }
        
    } catch (Exception $e) {
        $mensaje = array("codigo" => "400", "mensaje" => $e->getMessage());
    }
    
    return $mensaje;
    }

    


    public static function mdlEditarFicha($idFicha, $idAmbiente, $estado, $fechaInicio, $fechaFin, $jornada){
    $mensaje = array();

    try {
        $conexion = Conexion::Conectar();

        
        $sqlValidacion = "
            SELECT COUNT(*) as total 
            FROM ficha 
            WHERE idAmbiente = :idAmbiente
            AND jornada = :jornada
            AND estado = 'Activo'
            AND idFicha <> :idFicha
            AND (
                (:fechaInicio BETWEEN fechaInicio AND fechaFin)
                OR
                (:fechaFin BETWEEN fechaInicio AND fechaFin)
                OR
                (fechaInicio BETWEEN :fechaInicio AND :fechaFin)
                OR
                (fechaFin BETWEEN :fechaInicio AND :fechaFin)
            )
        ";

        $stmtValidacion = $conexion->prepare($sqlValidacion);
        $stmtValidacion->bindParam(":idAmbiente", $idAmbiente, PDO::PARAM_INT);
        $stmtValidacion->bindParam(":jornada", $jornada, PDO::PARAM_STR);
        $stmtValidacion->bindParam(":idFicha", $idFicha, PDO::PARAM_INT);
        $stmtValidacion->bindParam(":fechaInicio", $fechaInicio, PDO::PARAM_STR);
        $stmtValidacion->bindParam(":fechaFin", $fechaFin, PDO::PARAM_STR);
        $stmtValidacion->execute();

        $resultado = $stmtValidacion->fetch(PDO::FETCH_ASSOC);

        if ($resultado["total"] > 0) {
            return array(
                "codigo" => "409",
                "mensaje" => "⚠️ CONFLICTO: Ya existe una ficha activa en ese ambiente, jornada y rango de fechas."
            );
        }

        
        $objRespuesta = $conexion->prepare(
            "UPDATE ficha
             SET idAmbiente = :idAmbiente,
                 estado = :estado,
                 fechaInicio = :fechaInicio,
                 fechaFin = :fechaFin
             WHERE idFicha = :idFicha"
        );

        $objRespuesta->bindParam(":idFicha", $idFicha, PDO::PARAM_INT);
        $objRespuesta->bindParam(":idAmbiente", $idAmbiente, PDO::PARAM_INT);
        $objRespuesta->bindParam(":estado", $estado, PDO::PARAM_STR);
        $objRespuesta->bindParam(":fechaInicio", $fechaInicio, PDO::PARAM_STR);
        $objRespuesta->bindParam(":fechaFin", $fechaFin, PDO::PARAM_STR);

        if ($objRespuesta->execute()) {
            $mensaje = array("codigo"=>"200","mensaje"=>"✅ Ficha actualizada correctamente");
        } else {
            $mensaje = array("codigo"=>"401","mensaje"=>"❌ Error al actualizar la ficha");
        }

    } catch (Exception $e) {
        $mensaje = array("codigo"=>"400","mensaje"=>$e->getMessage());
    }

    return $mensaje;
}

}
