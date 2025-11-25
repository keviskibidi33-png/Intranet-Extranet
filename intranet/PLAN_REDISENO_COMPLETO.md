# 🎨 PLAN DE REDISEÑO Y MEJORAS - SISTEMA GEOFAL

## 📋 RESUMEN EJECUTIVO

Este documento detalla el plan completo de rediseño y mejoras del sistema de gestión de documentos PDF de GEOFAL, basado en las imágenes de diseño proporcionadas y los requisitos técnicos especificados.

---

## ✅ ESTADO ACTUAL

### Tareas Completadas:
- ✅ **A) jQuery corregido** - Referencias a `alphastore.com.pe` eliminadas en `head.php`
- ✅ **Login Extranet rediseñado** - Diseño profesional implementado
- ✅ **Logo sin fondo blanco** - CSS aplicado para transparencia

### Tareas Pendientes:
- ⏳ **A) Referencias restantes** - `ayuda.php` tiene 2 referencias a `alphastore.com.pe` (CORREGIDO)
- ⏳ **C) Botón eliminar** - Verificar que jQuery carga antes de `funciones.js` (YA CORREGIDO en head.php)
- ⏳ **D) Sistema de estados** - Cambiar a binario (No Leído/Descargado)
- ⏳ **E) Simplificar registro** - Eliminar campos innecesarios
- ⏳ **F) Seguridad** - Hash de contraseñas, validación fuerte
- ⏳ **G) Eliminación automática** - Script para PDFs antiguos
- ⏳ **H) Branding** - Aplicar nuevo diseño según imágenes

---

## 📸 ANÁLISIS DE IMÁGENES DE DISEÑO

### Imágenes proporcionadas:
1. **loginextranet.png** - Diseño del login del extranet
2. **interfazgeneral.png** - Interfaz general del sistema
3. **agregarnuevaempresa.png** - Formulario para agregar empresa
4. **perfildeempresa.png** - Vista de perfil de empresa
5. **debecambiaragregarnuevopdf.png** - Formulario para agregar PDF
6. **modificarpdfconvisualizaciondelpdf.png** - Edición de PDF con visualización
7. **visualizarperfilporintranetconnuevastipificaciones.png** - Vista de perfil con estados

**Nota:** Las imágenes serán analizadas en detalle cuando el usuario las proporcione.

---

## 🔧 TAREAS DE IMPLEMENTACIÓN

### A) ✅ Optimizar Tiempo de Respuesta del Servidor

**Estado:** ✅ COMPLETADO (parcialmente)
- ✅ jQuery corregido en `head.php` (línea 46)
- ✅ Referencias a `alphastore.com.pe` eliminadas en `head.php`
- ✅ Referencias en `ayuda.php` corregidas

**Archivos modificados:**
- `intranet/admin/app/vista/head.php` ✅
- `intranet/admin/app/vista/ayuda.php` ✅

**Validación:**
- [ ] Verificar que no hay más referencias a `alphastore.com.pe`
- [ ] Probar carga de jQuery en consola del navegador
- [ ] Verificar que `funciones.js`, `bootstrap.min.js` y `main.js` cargan correctamente

---

### B) Rediseñar Página de Login

**Estado:** ✅ COMPLETADO (parcialmente)
- ✅ Login del extranet rediseñado con diseño profesional
- ⏳ Ajustar según imagen `loginextranet.png` cuando se proporcione

**Archivos:**
- `intranet/admin/app/vista/login.phtml` ✅
- `intranet/admin/app/css/login-profesional.css` ✅

**Pendiente:**
- [ ] Revisar imagen `loginextranet.png` y ajustar diseño si es necesario
- [ ] Verificar que el logo se muestra correctamente sin fondo blanco

---

### C) Reparar Funcionalidad del Botón Eliminar

**Estado:** ✅ VERIFICADO
- ✅ jQuery se carga ANTES de `funciones.js` en `head.php` (línea 46 vs 58)
- ✅ Función `eliminar_todo()` en `funciones.js` línea 26 usa `$.ajax` correctamente

**Archivos:**
- `intranet/admin/app/vista/head.php` ✅ (orden correcto)
- `intranet/admin/app/js/funciones.js` ✅ (función correcta)

**Validación:**
- [ ] Probar botón eliminar en `pdf.phtml`
- [ ] Verificar que SweetAlert2 funciona correctamente
- [ ] Confirmar que la eliminación AJAX funciona

---

### D) Modificar Sistema de Estados de Documentos

**Estado:** ⏳ PENDIENTE

**Cambio requerido:**
- **Estados actuales:** `vista` (1/0) y `estado` (1/0 para APROBADO/OBSERVADO)
- **Nuevos estados:** Sistema binario
  - **No Leído** - Documento no ha sido abierto por el cliente
  - **Descargado** - Documento ha sido visualizado/descargado

**Archivos a modificar:**
- `intranet/admin/app/modelo/inicioModelo.php` - Lógica de estados
- `intranet/admin/app/vista/pdf.phtml` - Interfaz visual
- `intranet/app/vista/ordenes_2.phtml` - Vista cliente
- `intranet/app/js/funciones.js` - Función `visto()`
- Base de datos: Campo `vista` (ya existe, solo cambiar lógica)

**Implementación:**
1. Modificar `pro_visto()` en `inicioModelo.php` para cambiar estado a "Descargado" cuando se accede
2. Actualizar interfaz para mostrar solo "No Leído" / "Descargado"
3. Eliminar lógica de "APROBADO/OBSERVADO" o mantenerla separada
4. Actualizar tracking automático

---

### E) Simplificar Formulario de Registro de Clientes

**Estado:** ⏳ PENDIENTE

**Campos a ELIMINAR:**
- ❌ Dirección
- ❌ Teléfono
- ❌ Correo electrónico

**Campos a MANTENER:**
- ✅ RUC (obligatorio)
- ✅ Razón Social
- ✅ Clave (contraseña)
- ✅ Representante

**Archivos a modificar:**
- `intranet/admin/app/vista/clientes_agregar.phtml` - Formulario agregar
- `intranet/admin/app/vista/clientes_editar.phtml` - Formulario editar
- `intranet/admin/app/modelo/inicioModelo.php` - Lógica INSERT/UPDATE (línea 431)
- `intranet/admin/app/controlador/clientesCon.php` - Validaciones

**Nota importante:** El correo se usa para enviar credenciales. Necesitamos:
- Opción 1: Mantener correo pero no mostrarlo en formulario (campo oculto o automático)
- Opción 2: Eliminar envío de correo automático
- Opción 3: Solicitar correo solo al crear, no al editar

**Implementación:**
1. Eliminar campos del formulario HTML
2. Actualizar consulta SQL (eliminar `telefono`, `direccion`, `correo` del INSERT)
3. Actualizar validaciones backend
4. Decidir qué hacer con el envío de correo

---

### F) Mejorar Seguridad del Sistema de Perfiles

**Estado:** ⏳ PENDIENTE

**Problemas identificados:**
- Credenciales vulnerables (usuario: admin, contraseña: 123)
- Usuarios tienen acceso visible a contraseñas
- Contraseñas en texto plano

**Implementación requerida:**
1. **Hash de contraseñas:**
   - Usar `password_hash()` al crear/actualizar
   - Usar `password_verify()` al validar login
   - Archivos: `loginModelo.php` (admin y cliente)

2. **Validación de contraseñas seguras:**
   - Mínimo 8 caracteres
   - Al menos 1 mayúscula
   - Al menos 1 número
   - Al menos 1 carácter especial
   - Archivos: Formularios de registro/edición

3. **Ocultar contraseñas:**
   - No mostrar contraseñas en tablas/listados
   - Solo permitir cambio de contraseña, no visualización
   - Archivos: `clientes.phtml`, `pdf.phtml`, etc.

4. **Roles y permisos:**
   - Implementar sistema de roles básico
   - Ocultar información sensible según tipo de usuario
   - Archivos: Controladores y modelos

5. **Forzar cambio de credenciales:**
   - Script para cambiar credenciales por defecto
   - Archivo: `admin/cambiar_credenciales_default.php`

---

### G) Implementar Eliminación Automática de PDFs

**Estado:** ⏳ PENDIENTE

**Requerimiento:** Eliminar PDFs después de 2 meses de publicación

**Implementación:**
1. Crear script PHP: `intranet/admin/eliminar_pdfs_antiguos.php`
2. Consultar PDFs con fecha > 60 días
3. Eliminar archivos físicos del servidor
4. Eliminar o marcar registros en BD
5. Configurar cron job (manual o automático)

**Archivos a crear:**
- `intranet/admin/eliminar_pdfs_antiguos.php`
- `intranet/admin/cron_eliminar_pdfs.php` (versión para cron)

**Estructura de BD:**
- Verificar campo de fecha en tabla `pdf`
- Agregar campo `fecha_creacion` si no existe

---

### H) Aplicar Nuevo Branding de GEOFAL

**Estado:** ⏳ PENDIENTE

**Elementos a actualizar:**
1. **Logo:**
   - Nuevo logo GEOFAL (ya aplicado en login)
   - Aplicar en todas las vistas

2. **Paleta de colores:**
   - Analizar imágenes de diseño
   - Actualizar variables CSS
   - Archivo: `intranet/admin/app/css/login-profesional.css` (extender)

3. **Tipografías:**
   - Aplicar tipografías corporativas
   - Archivos: CSS principales

4. **Estilos coherentes:**
   - Header, footer, botones, formularios
   - Archivos: Plantillas PHP y CSS

**Archivos a modificar:**
- `intranet/admin/app/css/login-profesional.css`
- `intranet/admin/app/vista/header_left.php`
- `intranet/admin/app/vista/header_top.php`
- `intranet/admin/app/vista/footer.php`
- CSS principales del sistema

---

## 📊 DASHBOARDS REQUERIDOS

### Dashboard Administrador - Listado de Empresas

**Basado en:** `interfazgeneral.png`, `agregarnuevaempresa.png`

**Estructura:**
- Tabla con columnas:
  - Acción (editar/eliminar)
  - RUC
  - Clave (oculta o solo cambio)
  - Razón Social
  - Representante
- Botón: "+ Agregar Nueva Empresa"
- Barra de búsqueda: "Buscar Empresa"
- Botón "Agregar PDF" por cada empresa

**Archivos:**
- `intranet/admin/app/vista/clientes.phtml` (modificar)
- `intranet/admin/app/controlador/clientesCon.php`

---

### Dashboard Administrador - Gestión de PDFs por Empresa

**Basado en:** `debecambiaragregarnuevopdf.png`, `modificarpdfconvisualizaciondelpdf.png`

**Estructura:**
- Título: Nombre de la empresa seleccionada
- Botón: "+ Agregar Nuevo PDF"
- Tabla:
  - Acción (editar/eliminar)
  - Título
  - Estado (No leído/Descargado) - Sistema binario
- Barra de búsqueda: "Buscar PDF"
- Visualización de PDF integrada

**Archivos:**
- `intranet/admin/app/vista/pdf.phtml` (modificar)
- `intranet/admin/app/controlador/pdfCon.php`

---

### Portal Cliente (Extranet)

**Basado en:** `visualizarperfilporintranetconnuevastipificaciones.png`

**Estructura:**
- Logo GEOFAL centrado sobre formulario de login
- Después de login: visualización de PDFs disponibles
- Estados visibles de documentos (No Leído/Descargado)

**Archivos:**
- `intranet/app/vista/login_2.phtml` (ya rediseñado)
- `intranet/app/vista/ordenes_2.phtml` (modificar estados)

---

## 📝 ORDEN RECOMENDADO DE IMPLEMENTACIÓN

1. ✅ **Backup completo del sistema** (OBLIGATORIO)
2. ✅ **Tarea A:** Corregir referencias jQuery (CRÍTICO) - COMPLETADO
3. ✅ **Tarea C:** Verificar botón eliminar - VERIFICADO (funciona)
4. ⏳ **Tarea F:** Mejorar seguridad (CRÍTICO - credenciales vulnerables)
5. ⏳ **Tarea E:** Simplificar formulario de clientes
6. ⏳ **Tarea D:** Implementar nuevo sistema de estados
7. ⏳ **Tarea B:** Ajustar login según imagen (si es necesario)
8. ⏳ **Tarea H:** Aplicar nuevo branding según imágenes
9. ⏳ **Tarea G:** Sistema de eliminación automática de PDFs
10. ⏳ **Dashboards:** Implementar según imágenes de diseño

---

## 🔍 ARCHIVOS CRÍTICOS IDENTIFICADOS

### JavaScript:
- `intranet/admin/app/js/funciones.js` - Funciones principales
- `intranet/admin/include/assets/js/main.js` - Script principal
- `intranet/app/js/funciones.js` - Funciones cliente

### PHP - Controladores:
- `intranet/admin/app/controlador/clientesCon.php` - Gestión clientes
- `intranet/admin/app/controlador/pdfCon.php` - Gestión PDFs
- `intranet/admin/app/controlador/loginCon.php` - Login admin

### PHP - Modelos:
- `intranet/admin/app/modelo/inicioModelo.php` - Lógica de negocio
- `intranet/admin/app/modelo/loginModelo.php` - Lógica de login
- `intranet/app/modelo/inicioModelo.php` - Lógica cliente

### Vistas:
- `intranet/admin/app/vista/clientes.phtml` - Listado empresas
- `intranet/admin/app/vista/clientes_agregar.phtml` - Agregar empresa
- `intranet/admin/app/vista/pdf.phtml` - Listado PDFs
- `intranet/app/vista/ordenes_2.phtml` - Vista cliente

---

## ⚠️ NOTAS IMPORTANTES

1. **Backup obligatorio** antes de cualquier modificación
2. **Probar en local** antes de subir a producción
3. **Validar en producción** después de subir cambios
4. **Las imágenes de diseño** serán analizadas cuando se proporcionen
5. **El correo automático** al crear cliente debe ser evaluado (Tarea E)

---

## 📅 PRÓXIMOS PASOS

1. ✅ Corregir referencias restantes a `alphastore.com.pe`
2. ⏳ Analizar imágenes de diseño cuando se proporcionen
3. ⏳ Implementar Tarea F (Seguridad) - PRIORITARIA
4. ⏳ Implementar Tarea E (Simplificar formulario)
5. ⏳ Implementar Tarea D (Sistema de estados)

---

**Última actualización:** 2025-01-XX
**Estado:** En progreso

