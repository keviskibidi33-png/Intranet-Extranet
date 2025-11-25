# Guía de Pruebas Q/A - Sistema de Eliminación Automática de PDFs

## 📋 Descripción del Sistema

El sistema permite:
1. **Asignar fecha de eliminación** a cada PDF (opcional)
2. **Notificaciones automáticas** cuando un PDF se acerca a su fecha de eliminación (próximos 7 días)
3. **Eliminación automática** de PDFs cuando su fecha de eliminación ha pasado

## 🧪 Scripts de Prueba Creados

### 1. Crear PDFs Dummy (`crear_pdfs_dummyCon.php`)

**Propósito:** Crear PDFs de prueba con diferentes fechas de eliminación para probar el sistema.

**Cómo usar:**
1. Inicia sesión como administrador
2. Accede a: `?pagina=crear_pdfs_dummy`
3. El script creará automáticamente 6 PDFs dummy con fechas:
   - **HOY** - Para pruebas urgentes
   - **MAÑANA** - Para pruebas de alerta inmediata
   - **3 días** - Para pruebas de alerta temprana
   - **7 días** - Límite de notificaciones
   - **2 meses** - Eliminación automática estándar
   - **60 días** - Eliminación automática alternativa

**Resultado esperado:**
- PDFs creados en la base de datos
- Archivos PDF dummy creados en `intranet/publico/img_data/`
- Asignados al primer cliente disponible

### 2. Eliminar PDFs Vencidos (`eliminar_pdfs_vencidosCon.php`)

**Propósito:** Ejecutar la eliminación automática de PDFs cuya fecha de eliminación ya pasó.

**Cómo usar desde navegador:**
1. Inicia sesión como administrador
2. Accede a: `?pagina=eliminar_pdfs_vencidos`
3. El script mostrará los PDFs eliminados

**Cómo usar desde línea de comandos (Cron Job):**
```bash
# Ejecutar diariamente a las 2:00 AM
0 2 * * * php /ruta/al/proyecto/intranet/admin/app/controlador/eliminar_pdfs_vencidosCon.php
```

**Resultado esperado:**
- PDFs con `fecha_eliminacion < fecha_actual` eliminados de la BD
- Archivos físicos eliminados del servidor
- Reporte de eliminaciones y errores

## ✅ Checklist de Pruebas

### Prueba 1: Crear PDFs Dummy
- [ ] Acceder a `?pagina=crear_pdfs_dummy`
- [ ] Verificar que se crean 6 PDFs correctamente
- [ ] Verificar que los archivos PDF se crean en `intranet/publico/img_data/`
- [ ] Verificar que las fechas de eliminación son correctas

### Prueba 2: Verificar Notificaciones
- [ ] Abrir el dropdown de notificaciones (campana) en el header
- [ ] Verificar que aparecen notificaciones para PDFs con fecha <= 7 días
- [ ] Verificar colores de urgencia:
  - Rojo: HOY o MAÑANA
  - Amarillo: 2-7 días
  - Azul: > 7 días
- [ ] Verificar que el contador muestra el número correcto
- [ ] Hacer clic en una notificación y verificar que redirige al PDF correcto

### Prueba 3: Eliminación Automática
- [ ] Esperar o cambiar manualmente la fecha de un PDF dummy a ayer
- [ ] Ejecutar `?pagina=eliminar_pdfs_vencidos`
- [ ] Verificar que el PDF se elimina de la base de datos
- [ ] Verificar que el archivo físico se elimina del servidor
- [ ] Verificar que las notificaciones ya no muestran el PDF eliminado

### Prueba 4: Notificaciones en Tiempo Real
- [ ] Crear un PDF con fecha de eliminación = HOY
- [ ] Abrir el dropdown de notificaciones
- [ ] Verificar que aparece inmediatamente con urgencia ROJA
- [ ] Verificar que el mensaje dice "Se elimina HOY"

### Prueba 5: Múltiples Clientes
- [ ] Crear PDFs dummy para diferentes clientes
- [ ] Verificar que las notificaciones muestran el nombre del cliente correcto
- [ ] Verificar que los enlaces redirigen al cliente correcto

## 🔍 Verificaciones Adicionales

### Base de Datos
```sql
-- Ver PDFs con fecha de eliminación próxima
SELECT p.id, p.titulo, p.fecha_eliminacion, c.razon_social
FROM pdf p
LEFT JOIN clientes c ON p.id_user = c.id
WHERE p.fecha_eliminacion IS NOT NULL
AND p.fecha_eliminacion <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)
ORDER BY p.fecha_eliminacion ASC;

-- Ver PDFs vencidos (para eliminar)
SELECT * FROM pdf 
WHERE fecha_eliminacion IS NOT NULL 
AND fecha_eliminacion < CURDATE();
```

### Archivos Físicos
- Verificar que los PDFs dummy se crean en: `intranet/publico/img_data/`
- Verificar permisos de escritura/lectura
- Verificar que los archivos eliminados se borran correctamente

## 🐛 Problemas Comunes y Soluciones

### Problema: No aparecen notificaciones
**Solución:**
- Verificar que hay PDFs con `fecha_eliminacion` <= 7 días
- Verificar que el JavaScript `notificaciones.js` está cargado
- Verificar la consola del navegador para errores
- Verificar que el endpoint `?pagina=notificaciones` funciona

### Problema: Los PDFs no se eliminan automáticamente
**Solución:**
- Verificar que el cron job está configurado correctamente
- Ejecutar manualmente `eliminar_pdfs_vencidos.php`
- Verificar permisos de escritura en el directorio de PDFs
- Verificar logs de errores de PHP

### Problema: Las notificaciones no se actualizan
**Solución:**
- Limpiar caché del navegador
- Verificar que `notificaciones.js` se recarga cada 5 minutos
- Verificar que el endpoint devuelve JSON correcto

## 📝 Notas Importantes

1. **Los PDFs dummy son solo para pruebas** - Elimínalos después de las pruebas
2. **El sistema de eliminación automática requiere un cron job** - Configúralo en producción
3. **Las notificaciones se actualizan cada 5 minutos** automáticamente
4. **Las notificaciones solo muestran PDFs con fecha <= 7 días** desde hoy

## 🚀 Próximos Pasos

1. Ejecutar `crear_pdfs_dummy.php` para crear datos de prueba
2. Verificar las notificaciones en el dropdown
3. Probar la eliminación automática
4. Configurar el cron job en producción
5. Documentar los resultados de las pruebas

