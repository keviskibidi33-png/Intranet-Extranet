-- ============================================
-- SCRIPT COMPLETO PARA BASE DE DATOS NUEVA
-- ============================================
-- Este script contiene todas las modificaciones necesarias
-- para el sistema de notificaciones y gestión de PDFs
-- 
-- IMPORTANTE: Ejecutar en orden los scripts numerados
-- o ejecutar este script completo
-- ============================================

-- ============================================
-- 1. AGREGAR CAMPO fecha_eliminacion
-- ============================================
-- Agrega el campo fecha_eliminacion a la tabla pdf
-- para gestionar la eliminación automática de PDFs

ALTER TABLE `pdf` 
ADD COLUMN IF NOT EXISTS `fecha_eliminacion` DATE NULL DEFAULT NULL 
COMMENT 'Fecha en que se eliminará automáticamente el PDF' 
AFTER `estado`;

-- Crear índice para mejorar las consultas de notificaciones
CREATE INDEX IF NOT EXISTS `idx_fecha_eliminacion` ON `pdf` (`fecha_eliminacion`);

-- ============================================
-- 2. AGREGAR CAMPO fecha_subida
-- ============================================
-- Agrega el campo fecha_subida a la tabla pdf
-- para registrar cuándo se subió cada PDF

ALTER TABLE `pdf` 
ADD COLUMN IF NOT EXISTS `fecha_subida` DATETIME NULL DEFAULT NULL 
COMMENT 'Fecha y hora en que se subió el PDF' 
AFTER `fecha_eliminacion`;

-- Crear índice para mejorar las consultas por fecha
CREATE INDEX IF NOT EXISTS `idx_fecha_subida` ON `pdf` (`fecha_subida`);

-- ============================================
-- VERIFICACIÓN
-- ============================================
-- Ejecutar estas consultas para verificar que los campos se agregaron correctamente

-- Ver estructura de la tabla pdf
-- DESCRIBE `pdf`;

-- Verificar índices creados
-- SHOW INDEX FROM `pdf` WHERE Key_name LIKE 'idx_%';

-- ============================================
-- NOTAS IMPORTANTES
-- ============================================
-- 1. Si tu versión de MySQL no soporta IF NOT EXISTS:
--    - Eliminar "IF NOT EXISTS" de los comandos ALTER TABLE
--    - Eliminar "IF NOT EXISTS" de los comandos CREATE INDEX
--    - Ejecutar manualmente verificando que no existan los campos/índices
--
-- 2. Para bases de datos existentes:
--    - Los campos se agregarán sin afectar datos existentes
--    - Los registros antiguos tendrán NULL en estos campos
--    - Los nuevos registros se crearán con fecha_subida automáticamente
--
-- 3. Para bases de datos nuevas:
--    - Ejecutar este script completo
--    - Los campos estarán disponibles desde el inicio
--
-- 4. Funcionalidades que requieren estos campos:
--    - Sistema de notificaciones de PDFs por vencer
--    - Página de gestión "PDFs por Vencer"
--    - Eliminación automática de PDFs vencidos (cron job)
--    - Visualización de fecha de subida en listados

