-- Script para crear un usuario de prueba en la base de datos local
-- Ejecutar este script en phpMyAdmin o desde la línea de comandos de MySQL

-- Insertar usuario de prueba (si no existe)
INSERT INTO `clientes` (`ruc`, `razon_social`, `telefono`, `direccion`, `representante`, `clave`, `correo`) 
VALUES ('admin', 'Usuario Administrador', '999999999', 'Dirección de prueba', 'Admin', 'admin123', 'admin@geofal.com.pe')
ON DUPLICATE KEY UPDATE `clave` = 'admin123';

-- Si la tabla tiene un campo 'id' autoincremental, puedes usar:
-- INSERT IGNORE INTO `clientes` (`ruc`, `razon_social`, `telefono`, `direccion`, `representante`, `clave`, `correo`) 
-- VALUES ('admin', 'Usuario Administrador', '999999999', 'Dirección de prueba', 'Admin', 'admin123', 'admin@geofal.com.pe');

-- Para verificar que se creó correctamente:
-- SELECT * FROM `clientes` WHERE `ruc` = 'admin';

