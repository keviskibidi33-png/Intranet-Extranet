# 🗺️ RUTAS DEL SISTEMA ADMINISTRATIVO

## 📍 URL Base
```
http://localhost/public_html/intranet/admin/
```

## 🔐 Autenticación
- **Login:** `http://localhost/public_html/intranet/admin/login`
- **Logout:** `http://localhost/public_html/intranet/admin/?pagina=salir`

---

## 📋 RUTAS PRINCIPALES

### 🏠 Dashboard / Inicio
```
http://localhost/public_html/intranet/admin/?pagina=inicio
```
- **Controlador:** `app/controlador/inicioCon.php`
- **Vista:** `app/vista/index.phtml`
- **Descripción:** Dashboard principal con estadísticas

---

### 🏢 GESTIÓN DE EMPRESAS (Clientes)

#### Listado de Empresas
```
http://localhost/public_html/intranet/admin/?pagina=clientes
```
- **Controlador:** `app/controlador/clientesCon.php`
- **Vista:** `app/vista/clientes.phtml`
- **Descripción:** Lista todas las empresas con búsqueda y paginación
- **Parámetros opcionales:**
  - `?pagina=clientes&b=busqueda` - Búsqueda de empresas
  - `?pagina=clientes&p=2` - Paginación

#### Agregar Nueva Empresa
```
http://localhost/public_html/intranet/admin/?pagina=clientes_agregar
```
- **Controlador:** `app/controlador/clientes_agregarCon.php`
- **Vista:** `app/vista/clientes_agregar.phtml`
- **Descripción:** Formulario para agregar una nueva empresa

#### Editar Empresa
```
http://localhost/public_html/intranet/admin/?pagina=clientes_editar&id=[ID_EMPRESA]
```
- **Controlador:** `app/controlador/clientes_editarCon.php`
- **Vista:** `app/vista/clientes_editar.phtml`
- **Descripción:** Formulario para editar una empresa existente
- **Parámetro requerido:** `id` - ID de la empresa

---

### 📄 GESTIÓN DE PDFs

#### Listado de PDFs de una Empresa
```
http://localhost/public_html/intranet/admin/?pagina=pdf&id=[ID_EMPRESA]
```
- **Controlador:** `app/controlador/pdfCon.php`
- **Vista:** `app/vista/pdf.phtml`
- **Descripción:** Lista todos los PDFs asociados a una empresa
- **Parámetro requerido:** `id` - ID de la empresa
- **Parámetros opcionales:**
  - `?pagina=pdf&id=[ID]&b=busqueda` - Búsqueda de PDFs
  - `?pagina=pdf&id=[ID]&p=2` - Paginación

#### Agregar Nuevo PDF
```
http://localhost/public_html/intranet/admin/?pagina=pdf_agregar&id=[ID_EMPRESA]
```
- **Controlador:** `app/controlador/pdf_agregarCon.php`
- **Vista:** `app/vista/pdf_agregar.phtml`
- **Descripción:** Formulario para subir un nuevo PDF
- **Parámetro requerido:** `id` - ID de la empresa

#### Editar PDF
```
http://localhost/public_html/intranet/admin/?pagina=pdf_editar&id=[ID_PDF]
```
- **Controlador:** `app/controlador/pdf_editarCon.php`
- **Vista:** `app/vista/pdf_editar.phtml`
- **Descripción:** Formulario para editar un PDF existente con vista previa
- **Parámetro requerido:** `id` - ID del PDF

---

### 👤 PERFIL DE USUARIO

#### Perfil del Administrador
```
http://localhost/public_html/intranet/admin/?pagina=perfil
```
- **Controlador:** `app/controlador/perfilCon.php`
- **Vista:** `app/vista/perfil.phtml`
- **Descripción:** Formulario para editar el perfil del administrador actual

---

### 📦 OTRAS MÓDULOS (Si existen)

#### Órdenes
```
http://localhost/public_html/intranet/admin/?pagina=ordenes
```
- **Controlador:** `app/controlador/ordenesCon.php`
- **Descripción:** Gestión de órdenes

#### Productos
```
http://localhost/public_html/intranet/admin/?pagina=productos
http://localhost/public_html/intranet/admin/?pagina=productos_agregar
http://localhost/public_html/intranet/admin/?pagina=productos_editar&id=[ID]
```

#### Compras
```
http://localhost/public_html/intranet/admin/?pagina=compras
http://localhost/public_html/intranet/admin/?pagina=compras_agregar
http://localhost/public_html/intranet/admin/?pagina=compras_editar&id=[ID]
```

---

## 🔄 FLUJO DE NAVEGACIÓN TÍPICO

### Flujo Empresas → PDFs
```
1. Listado de Empresas
   → ?pagina=clientes

2. Click en "PDFs" de una empresa
   → ?pagina=pdf&id=[ID_EMPRESA]

3. Agregar nuevo PDF
   → ?pagina=pdf_agregar&id=[ID_EMPRESA]

4. Editar PDF
   → ?pagina=pdf_editar&id=[ID_PDF]

5. Volver a listado de PDFs
   → ?pagina=pdf&id=[ID_EMPRESA]

6. Volver a empresas
   → ?pagina=clientes
```

---

## 📝 NOTAS IMPORTANTES

1. **Variable `ruta`:** En las vistas se usa `<?= ruta ?>` que contiene la URL base del admin
2. **Sesión requerida:** Todas las rutas (excepto login) requieren sesión activa `$_SESSION['id_geofal']`
3. **Parámetros GET comunes:**
   - `pagina` - Nombre de la página/controlador
   - `id` - ID del registro (empresa, PDF, etc.)
   - `b` - Búsqueda
   - `p` - Página de paginación

---

## 🎯 RUTAS MÁS USADAS

| Ruta | Descripción |
|------|-------------|
| `?pagina=clientes` | Listado de empresas |
| `?pagina=clientes_agregar` | Agregar empresa |
| `?pagina=pdf&id=[ID]` | PDFs de una empresa |
| `?pagina=pdf_agregar&id=[ID]` | Agregar PDF |
| `?pagina=perfil` | Perfil del admin |

---

## 🚀 ACCESO RÁPIDO

Para desarrollo local:
```
http://localhost/public_html/intranet/admin/?pagina=clientes
http://localhost/public_html/intranet/admin/?pagina=pdf&id=1
http://localhost/public_html/intranet/admin/?pagina=perfil
```

