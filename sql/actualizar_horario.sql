-- Script para actualizar la tabla horario
-- Ejecutar en phpMyAdmin o línea de comandos MySQL

-- 1. Renombrar columnas existentes si es necesario
ALTER TABLE horario CHANGE fechaInicio fecha_inicioClase DATETIME DEFAULT NULL COMMENT 'Hora de inicio de la clase';
ALTER TABLE horario CHANGE fechaFin hora_finClase DATETIME DEFAULT NULL COMMENT 'Hora de fin de la clase';

-- 2. Agregar nuevas columnas para el rango del horario
ALTER TABLE horario ADD COLUMN fecha_inicioHorario DATE DEFAULT NULL COMMENT 'Fecha de inicio del rango del horario';
ALTER TABLE horario ADD COLUMN fecha_finHorario DATE DEFAULT NULL COMMENT 'Fecha de fin del rango del horario';

-- 3. Renombrar idInstructor a idFuncionario para consistencia
ALTER TABLE horario CHANGE idInstructor idFuncionario INT DEFAULT NULL COMMENT 'ID del instructor/funcionario';

-- 4. Agregar columna para el id de la ficha (asegurar que existe)
-- Si la columna idFicha no existe, agregarla
-- ALTER TABLE horario ADD COLUMN idFicha INT DEFAULT NULL COMMENT 'ID de la ficha';

-- 5. Verificar la estructura final
-- DESCRIBE horario;
