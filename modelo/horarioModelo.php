<?php

include_once "conexion.php";

/**
 * VERSIÓN: Validación Inteligente por Ficha
 * 
 * PERMITE:
 * - Múltiples instructores en el mismo ambiente (misma ficha)
 * - Duplicados dentro de la misma ficha
 * 
 * VALIDA:
 * - Un instructor NO puede estar en dos FICHAS diferentes al mismo tiempo
 * - Evita conflictos de horario de instructores entre fichas
 */

class horarioModelo {

    // ========== VALIDAR CONFLICTOS ENTRE FICHAS ==========
    private static function validarConflictosHorario($datos, $idHorarioExcluir = null) {
        $conn = Conexion::Conectar();
        
        // 🔥 SOLO VALIDAR CONFLICTOS DE INSTRUCTOR ENTRE FICHAS DIFERENTES
        if (!empty($datos['idFuncionario']) && !empty($datos['idFicha'])) {
            $sqlInstructor = "
                SELECT 
                    h.idHorario, 
                    h.hora_inicioClase, 
                    h.hora_finClase, 
                    h.idFicha,
                    GROUP_CONCAT(DISTINCT d.diasSemanales ORDER BY d.idDia) as dias,
                    f.codigoFicha,
                    a.codigo as ambiente
                FROM horario h
                INNER JOIN horariodia hd ON h.idHorario = hd.id_horarios
                INNER JOIN dia d ON hd.id_dias = d.idDia
                LEFT JOIN ficha f ON h.idFicha = f.idFicha
                LEFT JOIN ambiente a ON h.idAmbiente = a.idAmbiente
                WHERE h.idFuncionario = :idFuncionario
                AND h.idFicha != :idFicha
            ";
            
            if ($idHorarioExcluir) {
                $sqlInstructor .= " AND h.idHorario != :idHorarioExcluir";
            }
            
            // Filtrar por rango de fechas si existen
            if (!empty($datos['fecha_inicioHorario']) && !empty($datos['fecha_finHorario'])) {
                $sqlInstructor .= " AND (
                    (h.fecha_inicioHorario IS NULL OR h.fecha_finHorario IS NULL) OR
                    (h.fecha_inicioHorario <= :fechaFin AND h.fecha_finHorario >= :fechaInicio)
                )";
            }
            
            $sqlInstructor .= " GROUP BY h.idHorario";
            
            $stmt = $conn->prepare($sqlInstructor);
            $params = [
                ':idFuncionario' => $datos['idFuncionario'],
                ':idFicha' => $datos['idFicha']
            ];
            
            if ($idHorarioExcluir) {
                $params[':idHorarioExcluir'] = $idHorarioExcluir;
            }
            
            if (!empty($datos['fecha_inicioHorario']) && !empty($datos['fecha_finHorario'])) {
                $params[':fechaInicio'] = $datos['fecha_inicioHorario'];
                $params[':fechaFin'] = $datos['fecha_finHorario'];
            }
            
            $stmt->execute($params);
            $horariosExistentes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($horariosExistentes as $horario) {
                // Obtener días del horario existente
                $stmtDias = $conn->prepare("SELECT id_dias FROM horariodia WHERE id_horarios = :idHorario");
                $stmtDias->execute([':idHorario' => $horario['idHorario']]);
                $diasExistentes = $stmtDias->fetchAll(PDO::FETCH_COLUMN);
                
                // Verificar si hay días en común
                $diasComunes = array_intersect($datos['dias'], $diasExistentes);
                
                if (!empty($diasComunes)) {
                    // Verificar conflicto de horario
                    $horaInicioNueva = $datos['hora_inicioClase'];
                    $horaFinNueva = $datos['hora_finClase'];
                    $horaInicioExistente = $horario['hora_inicioClase'];
                    $horaFinExistente = $horario['hora_finClase'];
                    
                    // Detectar superposición
                    if (($horaInicioNueva < $horaFinExistente) && ($horaFinNueva > $horaInicioExistente)) {
                        return array(
                            "codigo" => "409",
                            "mensaje" => "⚠️ CONFLICTO ENTRE FICHAS: El instructor ya tiene clase de {$horaInicioExistente} a {$horaFinExistente} en la ficha {$horario['codigoFicha']} (Ambiente {$horario['ambiente']}) los días: {$horario['dias']}"
                        );
                    }
                }
            }
        }
        
        // ✅ NO VALIDAR dentro de la misma ficha
        // Permite duplicados y múltiples instructores en el mismo ambiente
        
        return array("codigo" => "200", "mensaje" => "Sin conflictos");
    }

    // ========== LISTAR TODOS LOS HORARIOS ==========
    public static function mdlListarHorarios() {
        $mensaje = array();
        try {
            $objRespuesta = Conexion::Conectar()->prepare(
                "SELECT h.idHorario, h.hora_inicioClase, h.hora_finClase,
                        f.nombre as instructorNombre, h.idFuncionario, h.idAmbiente, h.idFicha,
                        h.fecha_inicioHorario, h.fecha_finHorario,
                        a.codigo as ambienteNumero, a.ubicacion as ambienteDescripcion,
                        fi.codigoFicha,
                        GROUP_CONCAT(d.idDia) as dias,
                        GROUP_CONCAT(d.diasSemanales) as diasNombres
                 FROM horario h
                 LEFT JOIN funcionario f ON h.idFuncionario = f.idFuncionario
                 LEFT JOIN ambiente a ON h.idAmbiente = a.idAmbiente
                 LEFT JOIN ficha fi ON h.idFicha = fi.idFicha
                 LEFT JOIN horariodia hd ON h.idHorario = hd.id_horarios
                 LEFT JOIN dia d ON hd.id_dias = d.idDia
                 GROUP BY h.idHorario
                 ORDER BY h.hora_inicioClase, f.nombre"
            );
            $objRespuesta->execute();
            $listarHorarios = $objRespuesta->fetchAll(PDO::FETCH_ASSOC);
            $objRespuesta = null;
            
            $mensaje = array("codigo" => "200", "horarios" => $listarHorarios);
        } catch (Exception $e) {
            $mensaje = array("codigo" => "400", "mensaje" => $e->getMessage());
        }
        return $mensaje;
    }

    //  crear horario
    public static function mdlCrearHorario($datos) {
        $mensaje = array();
        $conn = Conexion::Conectar();
        
        try {
            // Validaciones básicas
            if (empty($datos['idFicha'])) {
                return array("codigo" => "400", "mensaje" => "El ID de la ficha es obligatorio");
            }
            
            if (!is_numeric($datos['idFicha'])) {
                return array("codigo" => "400", "mensaje" => "El ID de la ficha debe ser numérico");
            }
            
            if (empty($datos['dias']) || !is_array($datos['dias'])) {
                return array("codigo" => "400", "mensaje" => "Debe seleccionar al menos un día de la semana");
            }
            
            // validacion para problemas entre fichas
            $resultadoValidacion = self::validarConflictosHorario($datos);
            if ($resultadoValidacion['codigo'] !== "200") {
                return $resultadoValidacion;
            }
            
            // Iniciar transacción
            $conn->beginTransaction();
            
            // 1. Insertar horario base
            $objRespuesta = $conn->prepare(
                "INSERT INTO horario (idFuncionario, idAmbiente, idFicha, 
                        hora_inicioClase, hora_finClase, fecha_inicioHorario, fecha_finHorario)
                 VALUES (:idFuncionario, :idAmbiente, :idFicha, 
                        :hora_inicioClase, :hora_finClase, :fecha_inicioHorario, :fecha_finHorario)"
            );
            
            $parametros = [
                ':idFuncionario' => !empty($datos['idFuncionario']) ? $datos['idFuncionario'] : null,
                ':idAmbiente' => !empty($datos['idAmbiente']) ? $datos['idAmbiente'] : null,
                ':idFicha' => $datos['idFicha'],
                ':hora_inicioClase' => $datos['hora_inicioClase'],
                ':hora_finClase' => $datos['hora_finClase'],
                ':fecha_inicioHorario' => !empty($datos['fecha_inicioHorario']) ? $datos['fecha_inicioHorario'] : null,
                ':fecha_finHorario' => !empty($datos['fecha_finHorario']) ? $datos['fecha_finHorario'] : null
            ];
            
            $objRespuesta->execute($parametros);
            $idHorario = $conn->lastInsertId();
            
            // 2. Insertar días de la semana en horariodia
            $stmtDias = $conn->prepare(
                "INSERT INTO horariodia (id_horarios, id_dias) VALUES (:idHorario, :idDia)"
            );
            
            foreach ($datos['dias'] as $idDia) {
                $stmtDias->execute([
                    ':idHorario' => $idHorario,
                    ':idDia' => $idDia
                ]);
            }
            
            // Confirmar transacción
            $conn->commit();
            
            $mensaje = array(
                "codigo" => "200", 
                "mensaje" => "Horario creado exitosamente", 
                "idHorario" => $idHorario,
                "dias" => $datos['dias']
            );
            
        } catch (Exception $e) {
            // Revertir transacción en caso de error
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            $mensaje = array("codigo" => "400", "mensaje" => "Error al crear horario: " . $e->getMessage());
        }
        
        return $mensaje;
    }

    // ========== ACTUALIZAR HORARIO CON VALIDACIÓN ==========
    public static function mdlActualizarHorario($datos) {
        $mensaje = array();
        $conn = Conexion::Conectar();
        
        try {
            //  valida conflictos excluyendo el horario actual
            $resultadoValidacion = self::validarConflictosHorario($datos, $datos['idHorario']);
            if ($resultadoValidacion['codigo'] !== "200") {
                return $resultadoValidacion;
            }
            
            $conn->beginTransaction();
            
            // 1. Actualizar horario base
            $objRespuesta = $conn->prepare(
                "UPDATE horario SET idAmbiente = :idAmbiente, 
                        hora_inicioClase = :hora_inicioClase, hora_finClase = :hora_finClase,
                        fecha_inicioHorario = :fecha_inicioHorario, fecha_finHorario = :fecha_finHorario
                 WHERE idHorario = :idHorario"
            );
            $objRespuesta->execute([
                ':idAmbiente' => !empty($datos['idAmbiente']) ? $datos['idAmbiente'] : null,
                ':hora_inicioClase' => $datos['hora_inicioClase'],
                ':hora_finClase' => $datos['hora_finClase'],
                ':fecha_inicioHorario' => !empty($datos['fecha_inicioHorario']) ? $datos['fecha_inicioHorario'] : null,
                ':fecha_finHorario' => !empty($datos['fecha_finHorario']) ? $datos['fecha_finHorario'] : null,
                ':idHorario' => $datos['idHorario']
            ]);
            
            // 2. Si se enviaron días, actualizar
            if (isset($datos['dias']) && is_array($datos['dias'])) {
                // Eliminar días anteriores
                $stmtDelete = $conn->prepare("DELETE FROM horariodia WHERE id_horarios = :idHorario");
                $stmtDelete->execute([':idHorario' => $datos['idHorario']]);
                
                // Insertar nuevos días
                $stmtInsert = $conn->prepare(
                    "INSERT INTO horariodia (id_horarios, id_dias) VALUES (:idHorario, :idDia)"
                );
                
                foreach ($datos['dias'] as $idDia) {
                    $stmtInsert->execute([
                        ':idHorario' => $datos['idHorario'],
                        ':idDia' => $idDia
                    ]);
                }
            }
            
            $conn->commit();
            $mensaje = array("codigo" => "200", "mensaje" => "Horario actualizado");
            
        } catch (Exception $e) {
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            $mensaje = array("codigo" => "400", "mensaje" => $e->getMessage());
        }
        
        return $mensaje;
    }

    // ========== ELIMINAR HORARIO ==========
    public static function mdlEliminarHorario($idHorario) {
        $mensaje = array();
        $conn = Conexion::Conectar();
        
        try {
            $conn->beginTransaction();
            
            // 1. Eliminar registros de horariodia
            $stmtDias = $conn->prepare("DELETE FROM horariodia WHERE id_horarios = :idHorario");
            $stmtDias->execute([':idHorario' => $idHorario]);
            
            // 2. Eliminar horario
            $objRespuesta = $conn->prepare("DELETE FROM horario WHERE idHorario = :idHorario");
            $objRespuesta->execute([':idHorario' => $idHorario]);
            
            $conn->commit();
            $mensaje = array("codigo" => "200", "mensaje" => "Horario eliminado");
            
        } catch (Exception $e) {
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            $mensaje = array("codigo" => "400", "mensaje" => $e->getMessage());
        }
        
        return $mensaje;
    }
    
    // ========== LISTAR DÍAS DE LA SEMANA ==========
    public static function mdlListarDias() {
        $mensaje = array();
        try {
            $objRespuesta = Conexion::Conectar()->prepare(
                "SELECT idDia, diasSemanales FROM dia ORDER BY idDia"
            );
            $objRespuesta->execute();
            $dias = $objRespuesta->fetchAll(PDO::FETCH_ASSOC);
            $objRespuesta = null;
            
            $mensaje = array("codigo" => "200", "dias" => $dias);
        } catch (Exception $e) {
            $mensaje = array("codigo" => "400", "mensaje" => $e->getMessage());
        }
        return $mensaje;
    }
    
    // ========== OBTENER DÍAS DE UN HORARIO ==========
    public static function mdlObtenerDiasHorario($idHorario) {
        $mensaje = array();
        try {
            $objRespuesta = Conexion::Conectar()->prepare(
                "SELECT d.idDia, d.diasSemanales 
                 FROM horariodia hd
                 INNER JOIN dia d ON hd.id_dias = d.idDia
                 WHERE hd.id_horarios = :idHorario"
            );
            $objRespuesta->execute([':idHorario' => $idHorario]);
            $dias = $objRespuesta->fetchAll(PDO::FETCH_ASSOC);
            $objRespuesta = null;
            
            $mensaje = array("codigo" => "200", "dias" => $dias);
        } catch (Exception $e) {
            $mensaje = array("codigo" => "400", "mensaje" => $e->getMessage());
        }
        return $mensaje;
    }
}