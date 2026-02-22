<?php

include_once "conexion.php";

class horarioModelo {

    /* ══════════════════════════════════════════════════════════
       LISTAR SOLO FICHAS QUE TIENEN HORARIO
       Basado en la consulta del usuario, filtrado con INNER JOIN
       para traer solo las fichas CON horario asignado
    ══════════════════════════════════════════════════════════ */
    public static function mdlListarFichasConHorario() {
        try {
            $stmt = Conexion::Conectar()->prepare(
                "SELECT 
                    f.idFicha,
                    f.codigoFicha,
                    f.jornada,
                    p.nombre                                    AS nombrePrograma,
                    tp.tipoFormacion                            AS tipoPrograma,
                    MAX(s.nombre)                               AS sedeNombre,
                    MAX(ar.nombreArea)                          AS areaNombre,
                    MAX(CONCAT(a.codigo, ' - No.', a.numero))   AS ambienteNombre,
                    COUNT(h.idHorario)                          AS totalHorarios
                FROM ficha f
                INNER JOIN horario      h   ON h.idFicha        = f.idFicha
                LEFT  JOIN programa     p   ON f.idPrograma      = p.idPrograma
                LEFT  JOIN tipoprograma tp  ON p.idTipoFormacion = tp.idTipoPrograma
                LEFT  JOIN ambiente     a   ON h.idAmbiente      = a.idAmbiente
                LEFT  JOIN sede         s   ON a.idSede          = s.idSede
                LEFT  JOIN area         ar  ON a.idArea          = ar.idArea
                GROUP BY 
                    f.idFicha,
                    f.codigoFicha,
                    f.jornada,
                    p.nombre,
                    tp.tipoFormacion
                ORDER BY f.codigoFicha ASC"
            );
            $stmt->execute();
            return array(
                "codigo"   => "200",
                "horarios" => $stmt->fetchAll(PDO::FETCH_ASSOC)
            );
        } catch (Exception $e) {
            return array("codigo" => "400", "mensaje" => $e->getMessage());
        }
    }

    /* ══════════════════════════════════════════════════════════
       LISTAR HORARIOS POR FICHA (modal calendario + modal eliminar)
    ══════════════════════════════════════════════════════════ */
    public static function mdlListarHorariosPorFicha($idFicha) {
        try {
            $stmt = Conexion::Conectar()->prepare(
                "SELECT
                    h.idHorario,
                    h.idFicha,
                    h.idFuncionario,
                    h.hora_inicioClase,
                    h.hora_finClase,
                    h.fecha_inicioHorario,
                    h.fecha_finHorario,
                    func.nombre                                                             AS instructorNombre,
                    ar.nombreArea                                                           AS areaNombre,
                    CONCAT(a.codigo, ' - No.', a.numero)                                   AS ambienteNombre,
                    GROUP_CONCAT(DISTINCT d.diasSemanales ORDER BY d.idDia SEPARATOR ',')   AS diasNombres
                FROM horario h
                LEFT JOIN funcionario  func ON h.idFuncionario = func.idFuncionario
                LEFT JOIN ambiente     a    ON h.idAmbiente    = a.idAmbiente
                LEFT JOIN area         ar   ON a.idArea        = ar.idArea
                LEFT JOIN horariodia   hd   ON h.idHorario     = hd.id_horarios
                LEFT JOIN dia          d    ON hd.id_dias      = d.idDia
                WHERE h.idFicha = :idFicha
                GROUP BY
                    h.idHorario, h.idFicha, h.idFuncionario,
                    h.hora_inicioClase, h.hora_finClase,
                    h.fecha_inicioHorario, h.fecha_finHorario,
                    func.nombre, ar.nombreArea, a.codigo, a.numero
                ORDER BY h.hora_inicioClase ASC"
            );
            $stmt->execute(array(':idFicha' => $idFicha));
            return array(
                "codigo"   => "200",
                "horarios" => $stmt->fetchAll(PDO::FETCH_ASSOC),
                "total"    => $stmt->rowCount()
            );
        } catch (Exception $e) {
            return array("codigo" => "400", "mensaje" => $e->getMessage());
        }
    }

    /* ══════════════════════════════════════════════════════════
       VALIDACIÓN 1: Cruce de horario del instructor
    ══════════════════════════════════════════════════════════ */
    private static function validarConflictoInstructor($datos, $idHorarioExcluir = null) {
        try {
            if (empty($datos['idFuncionario'])) return array("codigo" => "200");
            if (empty($datos['dias']) || !is_array($datos['dias']))
                return array("codigo" => "400", "mensaje" => "Debe enviar los días correctamente");

            $dias = implode(',', array_map('intval', $datos['dias']));
            $sql  = "SELECT h.idHorario, h.hora_inicioClase, h.hora_finClase,
                        f.codigoFicha, a.codigo AS ambiente,
                        GROUP_CONCAT(DISTINCT d.diasSemanales ORDER BY d.idDia SEPARATOR ', ') AS dias
                     FROM horario h
                     INNER JOIN horariodia hd ON h.idHorario = hd.id_horarios
                     INNER JOIN dia d ON hd.id_dias = d.idDia
                     LEFT JOIN ficha f ON h.idFicha = f.idFicha
                     LEFT JOIN ambiente a ON h.idAmbiente = a.idAmbiente
                     WHERE h.idFuncionario = :idFuncionario
                       AND hd.id_dias IN ($dias)
                       AND (:horaInicio < h.hora_finClase AND :horaFin > h.hora_inicioClase)";
            if ($idHorarioExcluir !== null) $sql .= " AND h.idHorario != :idHorarioExcluir";
            $sql .= " GROUP BY h.idHorario LIMIT 1";

            $stmt = Conexion::Conectar()->prepare($sql);
            $idFuncionario = $datos['idFuncionario'];
            $horaInicio    = $datos['hora_inicioClase'];
            $horaFin       = $datos['hora_finClase'];
            $stmt->bindParam(":idFuncionario", $idFuncionario, PDO::PARAM_INT);
            $stmt->bindParam(":horaInicio", $horaInicio);
            $stmt->bindParam(":horaFin",    $horaFin);
            if ($idHorarioExcluir !== null) $stmt->bindParam(":idHorarioExcluir", $idHorarioExcluir, PDO::PARAM_INT);
            $stmt->execute();
            $c = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($c) return array("codigo" => "409",
                "mensaje" => "⚠️ <b>Conflicto de instructor:</b> Ya tiene clase de <b>{$c['hora_inicioClase']}</b> a <b>{$c['hora_finClase']}</b> en la ficha <b>{$c['codigoFicha']}</b> (Ambiente {$c['ambiente']}) los días: {$c['dias']}");
            return array("codigo" => "200");
        } catch (Exception $e) {
            return array("codigo" => "500", "mensaje" => $e->getMessage());
        }
    }

    /* ══════════════════════════════════════════════════════════
       VALIDACIÓN 2: Conflicto de ambiente
    ══════════════════════════════════════════════════════════ */
    private static function validarConflictoAmbiente($datos, $idHorarioExcluir = null) {
        try {
            if (empty($datos['idAmbiente'])) return array("codigo" => "200");

            $dias = implode(',', array_map('intval', $datos['dias']));
            $sql  = "SELECT h.idHorario, h.hora_inicioClase, h.hora_finClase, f.codigoFicha,
                        GROUP_CONCAT(DISTINCT d.diasSemanales ORDER BY d.idDia SEPARATOR ', ') AS dias
                     FROM horario h
                     INNER JOIN horariodia hd ON h.idHorario = hd.id_horarios
                     INNER JOIN dia d ON hd.id_dias = d.idDia
                     LEFT JOIN ficha f ON h.idFicha = f.idFicha
                     WHERE h.idAmbiente = :idAmbiente
                       AND hd.id_dias IN ($dias)
                       AND (:horaInicio < h.hora_finClase AND :horaFin > h.hora_inicioClase)";
            if ($idHorarioExcluir !== null) $sql .= " AND h.idHorario != :idHorarioExcluir";
            $sql .= " GROUP BY h.idHorario LIMIT 1";

            $stmt = Conexion::Conectar()->prepare($sql);
            $idAmbiente = $datos['idAmbiente'];
            $horaInicio = $datos['hora_inicioClase'];
            $horaFin    = $datos['hora_finClase'];
            $stmt->bindParam(":idAmbiente", $idAmbiente, PDO::PARAM_INT);
            $stmt->bindParam(":horaInicio", $horaInicio);
            $stmt->bindParam(":horaFin",    $horaFin);
            if ($idHorarioExcluir !== null) $stmt->bindParam(":idHorarioExcluir", $idHorarioExcluir, PDO::PARAM_INT);
            $stmt->execute();
            $c = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($c) return array("codigo" => "409",
                "mensaje" => "⚠️ <b>Ambiente ocupado:</b> Ya está asignado a la ficha <b>{$c['codigoFicha']}</b> de <b>{$c['hora_inicioClase']}</b> a <b>{$c['hora_finClase']}</b> los días: {$c['dias']}");
            return array("codigo" => "200");
        } catch (Exception $e) {
            return array("codigo" => "500", "mensaje" => $e->getMessage());
        }
    }

    /* ══════════════════════════════════════════════════════════
       CREAR HORARIO
    ══════════════════════════════════════════════════════════ */
    public static function mdlCrearHorario($datos) {
        $conn = Conexion::Conectar();
        try {
            if (empty($datos['idFuncionario']) || !is_numeric($datos['idFuncionario']))
                return array("codigo" => "400", "mensaje" => "El instructor es obligatorio");
            if (empty($datos['idFicha']) || !is_numeric($datos['idFicha']))
                return array("codigo" => "400", "mensaje" => "La ficha es obligatoria");
            if (empty($datos['hora_inicioClase']) || empty($datos['hora_finClase']))
                return array("codigo" => "400", "mensaje" => "Debe ingresar hora de inicio y fin");
            if ($datos['hora_inicioClase'] >= $datos['hora_finClase'])
                return array("codigo" => "400", "mensaje" => "La hora de inicio debe ser menor que la de fin");
            if (empty($datos['dias']) || !is_array($datos['dias']))
                return array("codigo" => "400", "mensaje" => "Debe seleccionar al menos un día");
            foreach ($datos['dias'] as $dia)
                if (!is_numeric($dia)) return array("codigo" => "400", "mensaje" => "Día inválido detectado");

            $v1 = self::validarConflictoInstructor($datos);
            if ($v1['codigo'] !== "200") return $v1;
            $v2 = self::validarConflictoAmbiente($datos);
            if ($v2['codigo'] !== "200") return $v2;

            $conn->beginTransaction();
            $stmt = $conn->prepare(
                "INSERT INTO horario (idFuncionario, idAmbiente, idFicha, hora_inicioClase, hora_finClase, fecha_inicioHorario, fecha_finHorario)
                 VALUES (:idFuncionario, :idAmbiente, :idFicha, :hora_inicioClase, :hora_finClase, :fecha_inicioHorario, :fecha_finHorario)"
            );
            $stmt->execute(array(
                ':idFuncionario'       => $datos['idFuncionario'],
                ':idAmbiente'          => !empty($datos['idAmbiente'])          ? $datos['idAmbiente']          : null,
                ':idFicha'             => $datos['idFicha'],
                ':hora_inicioClase'    => $datos['hora_inicioClase'],
                ':hora_finClase'       => $datos['hora_finClase'],
                ':fecha_inicioHorario' => !empty($datos['fecha_inicioHorario']) ? $datos['fecha_inicioHorario'] : null,
                ':fecha_finHorario'    => !empty($datos['fecha_finHorario'])    ? $datos['fecha_finHorario']    : null,
            ));
            $idHorario = $conn->lastInsertId();

            $stmtDias = $conn->prepare("INSERT INTO horariodia (id_horarios, id_dias) VALUES (:idHorario, :idDia)");
            foreach ($datos['dias'] as $idDia)
                $stmtDias->execute(array(':idHorario' => $idHorario, ':idDia' => $idDia));

            $conn->commit();
            return array("codigo" => "200", "mensaje" => "Horario creado exitosamente", "idHorario" => $idHorario, "dias" => $datos['dias']);
        } catch (Exception $e) {
            if ($conn->inTransaction()) $conn->rollBack();
            return array("codigo" => "500", "mensaje" => "Error al crear horario: " . $e->getMessage());
        }
    }

    /* ══════════════════════════════════════════════════════════
       LISTAR HORARIOS (legacy, usado por horario.js clase)
    ══════════════════════════════════════════════════════════ */
    public static function mdlListarHorarios() {
        try {
            $stmt = Conexion::Conectar()->prepare(
                "SELECT
                    h.idHorario, h.hora_inicioClase, h.hora_finClase,
                    h.fecha_inicioHorario, h.fecha_finHorario,
                    h.idFuncionario, h.idAmbiente, h.idFicha,
                    func.nombre                                                              AS instructorNombre,
                    a.codigo                                                                 AS ambienteCodigo,
                    s.idSede, s.nombre                                                       AS sedeNombre,
                    ar.nombreArea                                                            AS areaNombre,
                    fi.codigoFicha, fi.jornada,
                    p.nombre                                                                 AS programaNombre,
                    tp.tipoFormacion                                                         AS tipoPrograma,
                    GROUP_CONCAT(DISTINCT d.idDia        ORDER BY d.idDia SEPARATOR ',')    AS dias,
                    GROUP_CONCAT(DISTINCT d.diasSemanales ORDER BY d.idDia SEPARATOR ',')   AS diasNombres
                FROM horario h
                LEFT JOIN funcionario  func ON h.idFuncionario   = func.idFuncionario
                LEFT JOIN ambiente     a    ON h.idAmbiente      = a.idAmbiente
                LEFT JOIN sede         s    ON a.idSede          = s.idSede
                LEFT JOIN area         ar   ON a.idArea          = ar.idArea
                LEFT JOIN ficha        fi   ON h.idFicha         = fi.idFicha
                LEFT JOIN programa     p    ON fi.idPrograma     = p.idPrograma
                LEFT JOIN tipoprograma tp   ON p.idTipoFormacion = tp.idTipoPrograma
                LEFT JOIN horariodia   hd   ON h.idHorario       = hd.id_horarios
                LEFT JOIN dia          d    ON hd.id_dias        = d.idDia
                GROUP BY h.idHorario
                ORDER BY s.nombre, h.hora_inicioClase, func.nombre"
            );
            $stmt->execute();
            return array("codigo" => "200", "horarios" => $stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (Exception $e) {
            return array("codigo" => "400", "mensaje" => $e->getMessage());
        }
    }

    /* ══════════════════════════════════════════════════════════
       ACTUALIZAR HORARIO
    ══════════════════════════════════════════════════════════ */
    public static function mdlActualizarHorario($datos) {
        $conn = Conexion::Conectar();
        try {
            $v1 = self::validarConflictoInstructor($datos, $datos['idHorario']);
            if ($v1['codigo'] !== "200") return $v1;
            $v2 = self::validarConflictoAmbiente($datos, $datos['idHorario']);
            if ($v2['codigo'] !== "200") return $v2;

            $conn->beginTransaction();
            $conn->prepare(
                "UPDATE horario SET idAmbiente=:idAmbiente, hora_inicioClase=:hora_inicioClase,
                 hora_finClase=:hora_finClase, fecha_inicioHorario=:fecha_inicioHorario,
                 fecha_finHorario=:fecha_finHorario WHERE idHorario=:idHorario"
            )->execute(array(
                ':idAmbiente'          => !empty($datos['idAmbiente'])          ? $datos['idAmbiente']          : null,
                ':hora_inicioClase'    => $datos['hora_inicioClase'],
                ':hora_finClase'       => $datos['hora_finClase'],
                ':fecha_inicioHorario' => !empty($datos['fecha_inicioHorario']) ? $datos['fecha_inicioHorario'] : null,
                ':fecha_finHorario'    => !empty($datos['fecha_finHorario'])    ? $datos['fecha_finHorario']    : null,
                ':idHorario'           => $datos['idHorario'],
            ));

            if (isset($datos['dias']) && is_array($datos['dias'])) {
                $conn->prepare("DELETE FROM horariodia WHERE id_horarios = :id")->execute(array(':id' => $datos['idHorario']));
                $stmtDias = $conn->prepare("INSERT INTO horariodia (id_horarios, id_dias) VALUES (:idHorario, :idDia)");
                foreach ($datos['dias'] as $idDia)
                    $stmtDias->execute(array(':idHorario' => $datos['idHorario'], ':idDia' => $idDia));
            }
            $conn->commit();
            return array("codigo" => "200", "mensaje" => "Horario actualizado correctamente");
        } catch (Exception $e) {
            if ($conn->inTransaction()) $conn->rollBack();
            return array("codigo" => "400", "mensaje" => $e->getMessage());
        }
    }

    /* ══════════════════════════════════════════════════════════
       ELIMINAR HORARIO
    ══════════════════════════════════════════════════════════ */
    public static function mdlEliminarHorario($idHorario) {
        $conn = Conexion::Conectar();
        try {
            $conn->beginTransaction();
            $conn->prepare("DELETE FROM horariodia WHERE id_horarios = :id")->execute(array(':id' => $idHorario));
            $conn->prepare("DELETE FROM horario    WHERE idHorario   = :id")->execute(array(':id' => $idHorario));
            $conn->commit();
            return array("codigo" => "200", "mensaje" => "Horario eliminado correctamente");
        } catch (Exception $e) {
            if ($conn->inTransaction()) $conn->rollBack();
            return array("codigo" => "400", "mensaje" => $e->getMessage());
        }
    }

    /* ══════════════════════════════════════════════════════════
       LISTAR DÍAS DE LA SEMANA
    ══════════════════════════════════════════════════════════ */
    public static function mdlListarDias() {
        try {
            $stmt = Conexion::Conectar()->prepare("SELECT idDia, diasSemanales FROM dia ORDER BY idDia");
            $stmt->execute();
            return array("codigo" => "200", "dias" => $stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (Exception $e) {
            return array("codigo" => "400", "mensaje" => $e->getMessage());
        }
    }

    /* ══════════════════════════════════════════════════════════
       OBTENER DÍAS DE UN HORARIO
    ══════════════════════════════════════════════════════════ */
    public static function mdlObtenerDiasHorario($idHorario) {
        try {
            $stmt = Conexion::Conectar()->prepare(
                "SELECT d.idDia, d.diasSemanales FROM horariodia hd
                 INNER JOIN dia d ON hd.id_dias = d.idDia
                 WHERE hd.id_horarios = :idHorario"
            );
            $stmt->execute(array(':idHorario' => $idHorario));
            return array("codigo" => "200", "dias" => $stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (Exception $e) {
            return array("codigo" => "400", "mensaje" => $e->getMessage());
        }
    }

    /* ══════════════════════════════════════════════════════════
       GUARDAR HORARIOS COMPLETO (batch)
    ══════════════════════════════════════════════════════════ */
    public static function mdlGuardarHorariosCompleto($horariosJSON) {
        $conn = Conexion::Conectar();
        try {
            $horarios = json_decode($horariosJSON, true);
            if (!$horarios || !is_array($horarios))
                return array("success" => false, "message" => "Datos de horarios inválidos");

            $conn->beginTransaction();
            $guardados = 0; $errores = array();
            foreach ($horarios as $horario) {
                try {
                    $stmtCheck = $conn->prepare("SELECT idHorario FROM horario WHERE idAmbiente=:idAmbiente AND idFranja=:idFranja AND (fecha_finHorario IS NULL OR fecha_finHorario >= CURDATE())");
                    $stmtCheck->execute(array(':idAmbiente' => $horario['idAmbiente'], ':idFranja' => $horario['idFranja']));
                    $existente = $stmtCheck->fetch(PDO::FETCH_ASSOC);
                    if ($existente) {
                        $conn->prepare("UPDATE horario SET idFuncionario=:idFuncionario, idFicha=:idFicha WHERE idHorario=:idHorario")
                            ->execute(array(':idFuncionario' => $horario['idFuncionario'], ':idFicha' => $horario['idFicha'], ':idHorario' => $existente['idHorario']));
                    } else {
                        $conn->prepare("INSERT INTO horario (idFuncionario, idAmbiente, idFicha, idFranja, fecha_inicioHorario) VALUES (:idFuncionario, :idAmbiente, :idFicha, :idFranja, CURDATE())")
                            ->execute(array(':idFuncionario' => $horario['idFuncionario'], ':idAmbiente' => $horario['idAmbiente'], ':idFicha' => $horario['idFicha'], ':idFranja' => $horario['idFranja']));
                    }
                    $guardados++;
                } catch (Exception $e) {
                    $errores[] = "Error en ambiente {$horario['idAmbiente']}: " . $e->getMessage();
                }
            }
            $conn->commit();
            return array("success" => true, "guardados" => $guardados, "errores" => $errores, "message" => "Se guardaron $guardados horarios correctamente");
        } catch (Exception $e) {
            if ($conn->inTransaction()) $conn->rollBack();
            return array("success" => false, "message" => "Error al guardar horarios: " . $e->getMessage());
        }
    }

    /* ══════════════════════════════════════════════════════════
       OBTENER HORARIOS POR SEDE
    ══════════════════════════════════════════════════════════ */
    public static function mdlObtenerHorariosPorSede($idSede) {
        try {
            $stmt = Conexion::Conectar()->prepare(
                "SELECT h.idHorario, h.idFuncionario, h.idAmbiente, h.idFicha, h.idFranja,
                        f.nombre AS nombreInstructor, a.codigo AS codigoAmbiente, a.numero AS numeroAmbiente,
                        fi.codigoFicha, fr.nombre AS nombreFranja, fr.hora_inicio, fr.hora_fin
                 FROM horario h
                 INNER JOIN ambiente a ON h.idAmbiente = a.idAmbiente
                 LEFT JOIN funcionario f ON h.idFuncionario = f.idFuncionario
                 LEFT JOIN ficha fi ON h.idFicha = fi.idFicha
                 LEFT JOIN franja fr ON h.idFranja = fr.idFranja
                 WHERE a.idSede = :idSede AND (h.fecha_finHorario IS NULL OR h.fecha_finHorario >= CURDATE())
                 ORDER BY a.codigo, fr.idFranja"
            );
            $stmt->execute(array(':idSede' => $idSede));
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return array("success" => false, "message" => $e->getMessage());
        }
    }
}