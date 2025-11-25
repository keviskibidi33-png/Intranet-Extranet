# Scripts SQL para Base de Datos

Esta carpeta contiene todos los scripts SQL necesarios para configurar la base de datos del sistema de gestión de PDFs con notificaciones.

## 📋 Estructura de Archivos

### `00_SCRIPT_COMPLETO_DB_NUEVA.sql`
**Script maestro completo** - Contiene todas las modificaciones necesarias en un solo archivo. Recomendado para bases de datos nuevas.

### `01_agregar_fecha_eliminacion.sql`
Agrega el campo `fecha_eliminacion` a la tabla `pdf` para gestionar la eliminación automática de PDFs.

### `02_agregar_fecha_subida.sql`
Agrega el campo `fecha_subida` a la tabla `pdf` para registrar cuándo se subió cada PDF.

## 🚀 Instrucciones de Uso

### Para Base de Datos Nueva
1. Ejecutar `00_SCRIPT_COMPLETO_DB_NUEVA.sql` completo
2. Verificar que los campos se crearon correctamente

### Para Base de Datos Existente
1. Ejecutar los scripts en orden numérico:
   - `01_agregar_fecha_eliminacion.sql`
   - `02_agregar_fecha_subida.sql`
2. Los datos existentes no se verán afectados

## ⚠️ Notas Importantes

### Compatibilidad MySQL
- Si tu versión de MySQL no soporta `IF NOT EXISTS`:
  - Eliminar `IF NOT EXISTS` de los comandos `ALTER TABLE`
  - Eliminar `IF NOT EXISTS` de los comandos `CREATE INDEX`
  - Verificar manualmente que no existan los campos/índices antes de ejecutar

### Verificación
Después de ejecutar los scripts, verificar con:
```sql
-- Ver estructura de la tabla
DESCRIBE `pdf`;

-- Ver índices creados
SHOW INDEX FROM `pdf` WHERE Key_name LIKE 'idx_%';
```

## 📊 Campos Agregados

### `fecha_eliminacion` (DATE)
- **Tipo**: DATE
- **Nullable**: Sí (NULL por defecto)
- **Descripción**: Fecha en que se eliminará automáticamente el PDF
- **Índice**: `idx_fecha_eliminacion`

### `fecha_subida` (DATETIME)
- **Tipo**: DATETIME
- **Nullable**: Sí (NULL por defecto)
- **Descripción**: Fecha y hora en que se subió el PDF
- **Índice**: `idx_fecha_subida`

## 🔧 Funcionalidades que Requieren Estos Campos

- ✅ Sistema de notificaciones de PDFs por vencer
- ✅ Página de gestión "PDFs por Vencer"
- ✅ Eliminación automática de PDFs vencidos (cron job)
- ✅ Visualización de fecha de subida en listados
- ✅ Filtros y búsquedas por fecha

## 📝 Orden de Ejecución

1. `01_agregar_fecha_eliminacion.sql` - Primero
2. `02_agregar_fecha_subida.sql` - Segundo

O simplemente ejecutar:
- `00_SCRIPT_COMPLETO_DB_NUEVA.sql` - Todo en uno

## 🔍 Troubleshooting

### Error: "Duplicate column name"
- El campo ya existe en la tabla
- Verificar con: `DESCRIBE pdf;`
- Si existe, omitir ese script

### Error: "Duplicate key name"
- El índice ya existe
- Verificar con: `SHOW INDEX FROM pdf;`
- Si existe, omitir la creación del índice

### Error: "Unknown column 'fecha_eliminacion'"
- El campo no se creó correctamente
- Verificar permisos de usuario de base de datos
- Ejecutar manualmente el ALTER TABLE sin IF NOT EXISTS

