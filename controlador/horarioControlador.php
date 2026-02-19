<?php

include_once "../modelo/horarioModelo.php";

class horarioControlador {

    public function ctrListarHorarios() {
        $objRespuesta = horarioModelo::mdlListarHorarios();
        echo json_encode($objRespuesta);
    }

    public function ctrCrearHorario($datos) {
        $objRespuesta = horarioModelo::mdlCrearHorario($datos);
        echo json_encode($objRespuesta);
    }

    public function ctrActualizarHorario($datos) {
        $objRespuesta = horarioModelo::mdlActualizarHorario($datos);
        echo json_encode($objRespuesta);
    }

    public function ctrEliminarHorario($idHorario) {
        $objRespuesta = horarioModelo::mdlEliminarHorario($idHorario);
        echo json_encode($objRespuesta);
    }
    
    public function ctrListarDias() {
        $objRespuesta = horarioModelo::mdlListarDias();
        echo json_encode($objRespuesta);
    }
    
    public function ctrObtenerDiasHorario($idHorario) {
        $objRespuesta = horarioModelo::mdlObtenerDiasHorario($idHorario);
        echo json_encode($objRespuesta);
    }
}


header('Content-Type: application/json');

// ========== LISTAR HORARIOS ==========
if (isset($_POST["listarHorarios"])) {
    $objControlador = new horarioControlador();
    $objControlador->ctrListarHorarios();
}

// ========== CREAR HORARIO ==========
if (isset($_POST["crearHorario"])) {
    $dias = isset($_POST['dias']) ? $_POST['dias'] : [];
    if (is_string($dias)) {
        $dias = json_decode($dias, true);
    }
    
    $datos = array(
        'idFuncionario' => $_POST['idFuncionario'] ?: null,
        'idAmbiente' => $_POST['idAmbiente'] ?: null,
        'idFicha' => $_POST['idFicha'] ?: null,
        'hora_inicioClase' => $_POST['hora_inicioClase'],
        'hora_finClase' => $_POST['hora_finClase'],
        'fecha_inicioHorario' => $_POST['fecha_inicioHorario'] ?: null,
        'fecha_finHorario' => $_POST['fecha_finHorario'] ?: null,
        'dias' => $dias // Array de IDs de días
    );
    
    $objControlador = new horarioControlador();
    $objControlador->ctrCrearHorario($datos);
}

// ========== ACTUALIZAR HORARIO ==========
if (isset($_POST["actualizarHorario"])) {
    // Procesar días como array
    $dias = isset($_POST['dias']) ? $_POST['dias'] : [];
    
    if (is_string($dias)) {
        $dias = json_decode($dias, true);
    }
    
    $datos = array(
        'idHorario' => $_POST['idHorario'],
        'idAmbiente' => $_POST['idAmbiente'] ?: null,
        'hora_inicioClase' => $_POST['hora_inicioClase'],
        'hora_finClase' => $_POST['hora_finClase'],
        'fecha_inicioHorario' => $_POST['fecha_inicioHorario'] ?: null,
        'fecha_finHorario' => $_POST['fecha_finHorario'] ?: null,
        'dias' => $dias
    );
    
    $objControlador = new horarioControlador();
    $objControlador->ctrActualizarHorario($datos);
}

// ========== ELIMINAR HORARIO ==========
if (isset($_POST["eliminarHorario"])) {
    $objControlador = new horarioControlador();
    $objControlador->ctrEliminarHorario($_POST['idHorario']);
}

// ========== LISTAR DÍAS DE LA SEMANA ==========
if (isset($_POST["listarDias"])) {
    $objControlador = new horarioControlador();
    $objControlador->ctrListarDias();
}

// ========== OBTENER DÍAS DE UN HORARIO ==========
if (isset($_POST["obtenerDiasHorario"])) {
    $objControlador = new horarioControlador();
    $objControlador->ctrObtenerDiasHorario($_POST['idHorario']);
}

if (isset($_POST["guardarHorariosCompleto"])) {
    guardarHorariosCompleto($_POST['horarios']);
}

function guardarHorariosCompleto($horariosJSON) {
    include_once "../modelo/conexion.php";
    
    try {
        $horarios = json_decode($horariosJSON, true);
        
        if (!$horarios || !is_array($horarios)) {
            echo json_encode([
                'success' => false,
                'message' => 'Datos de horarios inválidos'
            ]);
            return;
        }
        
        $conexion = Conexion::conectar();
        $conexion->beginTransaction();
        
        $guardados = 0;
        $errores = [];
        
        foreach ($horarios as $horario) {
            try {
                // Verificar si ya existe un horario para este ambiente y franja
                $sqlVerificar = "SELECT idHorario FROM horario 
                                WHERE idAmbiente = :idAmbiente 
                                AND idFranja = :idFranja 
                                AND (fecha_finHorario IS NULL OR fecha_finHorario >= CURDATE())";
                
                $stmtVerificar = $conexion->prepare($sqlVerificar);
                $stmtVerificar->bindParam(':idAmbiente', $horario['idAmbiente'], PDO::PARAM_INT);
                $stmtVerificar->bindParam(':idFranja', $horario['idFranja'], PDO::PARAM_INT);
                $stmtVerificar->execute();
                
                $horarioExistente = $stmtVerificar->fetch(PDO::FETCH_ASSOC);
                
                if ($horarioExistente) {
                    // Actualizar horario existente
                    $sqlUpdate = "UPDATE horario 
                                 SET idFuncionario = :idFuncionario,
                                     idFicha = :idFicha
                                 WHERE idHorario = :idHorario";
                    
                    $stmtUpdate = $conexion->prepare($sqlUpdate);
                    $stmtUpdate->bindParam(':idFuncionario', $horario['idFuncionario'], PDO::PARAM_INT);
                    $stmtUpdate->bindParam(':idFicha', $horario['idFicha'], PDO::PARAM_INT);
                    $stmtUpdate->bindParam(':idHorario', $horarioExistente['idHorario'], PDO::PARAM_INT);
                    $stmtUpdate->execute();
                } else {
                    // Insertar nuevo horario
                    $sqlInsert = "INSERT INTO horario 
                                 (idFuncionario, idAmbiente, idFicha, idFranja, fecha_inicioHorario) 
                                 VALUES 
                                 (:idFuncionario, :idAmbiente, :idFicha, :idFranja, CURDATE())";
                    
                    $stmtInsert = $conexion->prepare($sqlInsert);
                    $stmtInsert->bindParam(':idFuncionario', $horario['idFuncionario'], PDO::PARAM_INT);
                    $stmtInsert->bindParam(':idAmbiente', $horario['idAmbiente'], PDO::PARAM_INT);
                    $stmtInsert->bindParam(':idFicha', $horario['idFicha'], PDO::PARAM_INT);
                    $stmtInsert->bindParam(':idFranja', $horario['idFranja'], PDO::PARAM_INT);
                    $stmtInsert->execute();
                    
                    $idHorario = $conexion->lastInsertId();
                    
                    // Guardar información de transversales si existe
                    if (isset($horario['transversales']) && $horario['transversales']) {
                        guardarTransversales($conexion, $idHorario, $horario['transversales']);
                    }
                }
                
                $guardados++;
                
            } catch (Exception $e) {
                $errores[] = "Error en ambiente {$horario['idAmbiente']}: " . $e->getMessage();
            }
        }
        
        $conexion->commit();
        
        echo json_encode([
            'success' => true,
            'guardados' => $guardados,
            'errores' => $errores,
            'message' => "Se guardaron $guardados horarios correctamente"
        ]);
        
    } catch (Exception $e) {
        if (isset($conexion)) {
            $conexion->rollBack();
        }
        
        echo json_encode([
            'success' => false,
            'message' => 'Error al guardar horarios: ' . $e->getMessage()
        ]);
    }
}

function guardarTransversales($conexion, $idHorario, $transversales) {
    // Crear tabla de transversales si no existe
    $sqlCreateTable = "CREATE TABLE IF NOT EXISTS horario_transversales (
        idTransversal INT AUTO_INCREMENT PRIMARY KEY,
        idHorario INT NOT NULL,
        jornada VARCHAR(50),
        competencias TEXT,
        FOREIGN KEY (idHorario) REFERENCES horario(idHorario) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $conexion->exec($sqlCreateTable);
    
    // Insertar transversales
    $sqlInsert = "INSERT INTO horario_transversales (idHorario, jornada, competencias) 
                 VALUES (:idHorario, :jornada, :competencias)
                 ON DUPLICATE KEY UPDATE 
                 jornada = VALUES(jornada),
                 competencias = VALUES(competencias)";
    
    $stmt = $conexion->prepare($sqlInsert);
    $stmt->bindParam(':idHorario', $idHorario, PDO::PARAM_INT);
    $stmt->bindParam(':jornada', $transversales['jornada'], PDO::PARAM_STR);
    
    $competenciasJSON = json_encode($transversales['competencias']);
    $stmt->bindParam(':competencias', $competenciasJSON, PDO::PARAM_STR);
    
    $stmt->execute();
}

// ========================================
// MÉTODO PARA OBTENER HORARIOS POR SEDE
// ========================================

if (isset($_POST["obtenerHorariosPorSede"])) {
    obtenerHorariosPorSede($_POST['idSede']);
}

function obtenerHorariosPorSede($idSede) {
    include_once "../modelo/conexion.php";
    
    try {
        $conexion = Conexion::conectar();
        
        $sql = "SELECT 
                    h.idHorario,
                    h.idFuncionario,
                    h.idAmbiente,
                    h.idFicha,
                    h.idFranja,
                    f.nombre as nombreInstructor,
                    a.codigo as codigoAmbiente,
                    a.numero as numeroAmbiente,
                    fi.codigoFicha,
                    fr.nombre as nombreFranja,
                    fr.hora_inicio,
                    fr.hora_fin
                FROM horario h
                INNER JOIN ambiente a ON h.idAmbiente = a.idAmbiente
                LEFT JOIN funcionario f ON h.idFuncionario = f.idFuncionario
                LEFT JOIN ficha fi ON h.idFicha = fi.idFicha
                LEFT JOIN franja fr ON h.idFranja = fr.idFranja
                WHERE a.idSede = :idSede
                AND (h.fecha_finHorario IS NULL OR h.fecha_finHorario >= CURDATE())
                ORDER BY a.codigo, fr.idFranja";
        
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idSede', $idSede, PDO::PARAM_INT);
        $stmt->execute();
        
        $horarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode($horarios);
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error al obtener horarios: ' . $e->getMessage()
        ]);
    }
}
