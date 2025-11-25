# 📦 DEPENDENCIAS DEL SISTEMA ADMIN

## ✅ LIMPIEZA REALIZADA

### Imágenes Eliminadas
- ✅ `admin.jpg` - Imagen de ejemplo
- ✅ `logo.png` - Logo antiguo
- ✅ `logo2.png` - Logo antiguo 2
- ✅ `placeholder.png` - Placeholder
- ✅ `twitter_corner_black.png` - Icono Twitter
- ✅ `twitter_corner_blue.png` - Icono Twitter
- ✅ `avatar/` - Carpeta completa con avatares de ejemplo

### Imágenes Mantenidas
- ✅ `geofal.png` - Logo Geofal (sin fondo blanco)

---

## 📧 PHPMailer (include/phpmiler/)

### ¿Es Necesario?
**✅ SÍ, es OBLIGATORIO**

### ¿Para qué se usa?
- Envía emails automáticamente cuando se crea un nuevo cliente
- El email contiene las credenciales de acceso (RUC y contraseña)
- Ubicación del código: `app/modelo/inicioModelo.php` (línea ~445)

### Función
```php
require 'include/phpmiler/vendor/autoload.php';
$mail = new PHPMailer;
// Envía email con credenciales al cliente nuevo
```

### Recomendación
**MANTENER** - Es necesario para el funcionamiento del sistema de registro de clientes.

---

## 📚 Vendors (include/vendors/)

### ¿Son Necesarios?
**Algunos SÍ, otros NO**

### Vendors NECESARIOS (NO eliminar)
1. **jquery** - Base de JavaScript
   - Usado en: Todo el admin
   - Función: Manipulación DOM, AJAX

2. **bootstrap** - Framework CSS/JS
   - Usado en: Todo el admin
   - Función: Estilos, componentes, grid

3. **font-awesome** - Iconos
   - Usado en: Todo el admin
   - Función: Iconos (fa-user, fa-lock, etc.)

4. **popper.js** - Requerido por Bootstrap
   - Usado en: Bootstrap dropdowns, tooltips
   - Función: Posicionamiento de elementos

### Vendors USADOS (verificar antes de eliminar)
1. **themify-icons** - Iconos adicionales
   - Usado en: Algunas vistas del admin

2. **flag-icon-css** - Banderas de países
   - Usado en: Si hay selección de países

3. **selectFX** - Selectores personalizados
   - Usado en: Formularios del admin

4. **jqvmap** - Mapas interactivos
   - Usado en: Si hay mapas en el dashboard

### Vendors OPCIONALES (se pueden eliminar si no se usan)
- `chart.js` - Gráficos (solo si no hay gráficos)
- `datatables` - Tablas avanzadas (solo si no se usan)
- `animate.css` - Animaciones (opcional)
- `chosen` - Selectores (opcional)
- `flot` - Gráficos (opcional)
- `gaugejs` - Medidores (opcional)
- `gmaps` - Google Maps (opcional)
- `jquery-validation` - Validación (opcional)
- `peity` - Gráficos pequeños (opcional)
- `pdfmake` - Generación PDFs (opcional)
- `jszip` - Compresión ZIP (opcional)

### Recomendación
1. **MANTENER:** jQuery, Bootstrap, Font Awesome, Popper.js
2. **VERIFICAR:** themify-icons, flag-icon-css, selectFX, jqvmap
3. **ELIMINAR (si no se usan):** chart.js, datatables, animate.css, chosen, flot, gaugejs, gmaps, jquery-validation, peity, pdfmake, jszip

---

## 🎯 RESUMEN

| Dependencia | Estado | Acción |
|-------------|--------|--------|
| **PHPMailer** | ✅ Necesario | MANTENER |
| **jQuery** | ✅ Necesario | MANTENER |
| **Bootstrap** | ✅ Necesario | MANTENER |
| **Font Awesome** | ✅ Necesario | MANTENER |
| **Popper.js** | ✅ Necesario | MANTENER |
| **Otros vendors** | ⚠️ Opcional | VERIFICAR uso |
| **Imágenes** | ✅ Limpiado | Solo geofal.png |

---

## 📝 NOTA IMPORTANTE

**PHPMailer** y los **vendors esenciales** son necesarios para el funcionamiento del sistema. No eliminar sin verificar primero su uso en el código.

