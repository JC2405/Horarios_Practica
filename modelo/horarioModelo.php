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
                    p.nombre  AS nombrePrograma,
                    tp.tipoFormacion AS tipoPrograma,
                    MAX(s.nombre)AS sedeNombre,
                    MAX(ar.nombreArea)AS areaNombre,
                    MAX(CONCAT(a.codigo, ' - No.', a.numero))  AS ambienteNombre,
                    COUNT(h.idHorario)AS totalHorarios
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
       LISTAR HORARIOS POR FICHA (modal calendario con modal eliminar)
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

 
}