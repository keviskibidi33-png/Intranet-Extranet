-- ============================================
-- Script SQL: Agregar campo fecha_eliminacion
-- ============================================
-- Descripción: Agrega el campo fecha_eliminacion a la tabla pdf
--              para gestionar la eliminación automática de PDFs
-- Fecha: 2024
-- ============================================

-- Agregar columna fecha_eliminacion
ALTER TABLE `pdf` 
ADD COLUMN IF NOT EXISTS `fecha_eliminacion` DATE NULL DEFAULT NULL 
COMMENT 'Fecha en que se eliminará automáticamente el PDF' 
AFTER `estado`;

-- Si tu versión de MySQL no soporta IF NOT EXISTS, usar este comando:
-- ALTER TABLE `pdf` 
-- ADD COLUMN `fecha_eliminacion` DATE NULL DEFAULT NULL 
-- COMMENT 'Fecha en que se eliminará automáticamente el PDF' 
-- AFTER `estado`;

-- Crear índice para mejorar las consultas de notificaciones
CREATE INDEX IF NOT EXISTS `idx_fecha_eliminacion` ON `pdf` (`fecha_eliminacion`);

-- Si tu versión de MySQL no soporta IF NOT EXISTS para índices:
-- CREATE INDEX `idx_fecha_eliminacion` ON `pdf` (`fecha_eliminacion`);

