-- ============================================
-- Script SQL: Agregar campo fecha_subida
-- ============================================
-- Descripción: Agrega el campo fecha_subida a la tabla pdf
--              para registrar cuándo se subió cada PDF
-- Fecha: 2024
-- ============================================

-- Agregar columna fecha_subida (timestamp de cuando se subió el PDF)
ALTER TABLE `pdf` 
ADD COLUMN IF NOT EXISTS `fecha_subida` DATETIME NULL DEFAULT NULL 
COMMENT 'Fecha y hora en que se subió el PDF' 
AFTER `fecha_eliminacion`;

-- Si tu versión de MySQL no soporta IF NOT EXISTS, usar este comando:
-- ALTER TABLE `pdf` 
-- ADD COLUMN `fecha_subida` DATETIME NULL DEFAULT NULL 
-- COMMENT 'Fecha y hora en que se subió el PDF' 
-- AFTER `fecha_eliminacion`;

-- Actualizar registros existentes con fecha_subida basada en el ID (aproximación)
-- Esto es solo para registros antiguos, los nuevos se crearán con fecha_subida automáticamente
-- UPDATE `pdf` 
-- SET `fecha_subida` = DATE_SUB(NOW(), INTERVAL (SELECT MAX(id) - id FROM pdf p2 WHERE p2.id = pdf.id) DAY)
-- WHERE `fecha_subida` IS NULL;

-- Crear índice para mejorar las consultas por fecha
CREATE INDEX IF NOT EXISTS `idx_fecha_subida` ON `pdf` (`fecha_subida`);

-- Si tu versión de MySQL no soporta IF NOT EXISTS para índices:
-- CREATE INDEX `idx_fecha_subida` ON `pdf` (`fecha_subida`);

