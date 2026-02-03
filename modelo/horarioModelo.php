<?php

include_once "conexion.php";

class horarioModelo {

    // ========== VALIDAR CONFLICTOS DE HORARIO ==========
    private static function validarConflictosHorario($datos, $idHorarioExcluir = null) {
        $conn = Conexion::Conectar();
        
        // 🔥 1. VALIDAR CONFLICTO DE INSTRUCTOR (mismo instructor, mismo horario, mismo día)
        if (!empty($datos['idFuncionario'])) {
            $sqlInstructor = "
                SELECT h.idHorario, h.hora_inicioClase, h.hora_finClase, 
                       GROUP_CONCAT(d.diasSemanales) as dias,
                       f.codigoFicha
                FROM horario h
                INNER JOIN horariodia hd ON h.idHorario = hd.id_horarios
                INNER JOIN dia d ON hd.id_dias = d.idDia
                LEFT JOIN ficha f ON h.idFicha = f.idFicha
                WHERE h.idFuncionario = :idFuncionario
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
            $params = [':idFuncionario' => $datos['idFuncionario']];
            
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
                            "mensaje" => "⚠️ CONFLICTO: El instructor ya tiene clase de {$horaInicioExistente} a {$horaFinExistente} en la ficha {$horario['codigoFicha']} los días: {$horario['dias']}"
                        );
                    }
                }
            }
        }
        
        // 🔥 2. VALIDAR CONFLICTO DE AMBIENTE (mismo ambiente, mismo horario, mismo día)
        if (!empty($datos['idAmbiente'])) {
            $sqlAmbiente = "
                SELECT h.idHorario, h.hora_inicioClase, h.hora_finClase,
                       GROUP_CONCAT(d.diasSemanales) as dias,
                       f.codigoFicha,
                       func.nombre as instructor
                FROM horario h
                INNER JOIN horariodia hd ON h.idHorario = hd.id_horarios
                INNER JOIN dia d ON hd.id_dias = d.idDia
                LEFT JOIN ficha f ON h.idFicha = f.idFicha
                LEFT JOIN funcionario func ON h.idFuncionario = func.idFuncionario
                WHERE h.idAmbiente = :idAmbiente
            ";
            
            if ($idHorarioExcluir) {
                $sqlAmbiente .= " AND h.idHorario != :idHorarioExcluir";
            }
            
            if (!empty($datos['fecha_inicioHorario']) && !empty($datos['fecha_finHorario'])) {
                $sqlAmbiente .= " AND (
                    (h.fecha_inicioHorario IS NULL OR h.fecha_finHorario IS NULL) OR
                    (h.fecha_inicioHorario <= :fechaFin AND h.fecha_finHorario >= :fechaInicio)
                )";
            }
            
            $sqlAmbiente .= " GROUP BY h.idHorario";
            
            $stmt = $conn->prepare($sqlAmbiente);
            $params = [':idAmbiente' => $datos['idAmbiente']];
            
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
                $stmtDias = $conn->prepare("SELECT id_dias FROM horariodia WHERE id_horarios = :idHorario");
                $stmtDias->execute([':idHorario' => $horario['idHorario']]);
                $diasExistentes = $stmtDias->fetchAll(PDO::FETCH_COLUMN);
                
                $diasComunes = array_intersect($datos['dias'], $diasExistentes);
                
                if (!empty($diasComunes)) {
                    $horaInicioNueva = $datos['hora_inicioClase'];
                    $horaFinNueva = $datos['hora_finClase'];
                    $horaInicioExistente = $horario['hora_inicioClase'];
                    $horaFinExistente = $horario['hora_finClase'];
                    
                    if (($horaInicioNueva < $horaFinExistente) && ($horaFinNueva > $horaInicioExistente)) {
                        $instructor = $horario['instructor'] ?: 'Sin instructor';
                        return array(
                            "codigo" => "409",
                            "mensaje" => "⚠️ CONFLICTO: El ambiente ya está ocupado de {$horaInicioExistente} a {$horaFinExistente} por {$instructor} (Ficha {$horario['codigoFicha']}) los días: {$horario['dias']}"
                        );
                    }
                }
            }
        }
        
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
                 GROUP BY h.idHorario"
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

    // ========== CREAR HORARIO CON VALIDACIÓN ==========
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
            
            // 🔥 VALIDAR CONFLICTOS ANTES DE INSERTAR
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
            // 🔥 VALIDAR CONFLICTOS (excluyendo el horario actual)
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
            
            // 1. Eliminar registros de horariodia (por integridad referencial)
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