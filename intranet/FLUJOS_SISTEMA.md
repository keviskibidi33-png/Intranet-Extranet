# 🔄 FLUJOS DEL SISTEMA GEOFAL

## 📋 RESUMEN EJECUTIVO

El sistema tiene **3 flujos principales**:

1. **Flujo Clientes** - Extranet donde los clientes ven sus documentos
2. **Flujo Administración** - Panel donde los vendedores/admin gestionan todo
3. **Páginas Públicas** - Sitio web corporativo (en proceso de reorganización)

---

## 1️⃣ FLUJO CLIENTES (Extranet)

### 📍 URL Base
```
http://localhost/public_html/intranet/
```

### 🔐 Autenticación
- **Login:** `?pagina=cliente` o `?pagina=login`
- **Credenciales:**
  - Campo: RUC
  - Campo: Clave
- **Tabla BD:** `clientes`
- **Sesión:** `$_SESSION['id_geo']`

### 📂 Estructura
```
intranet/
├── index.php                    ← Punto de entrada
├── config/
│   ├── config.local.php         ← Configuración local
│   ├── config.php               ← Configuración producción
│   └── sistema.php              ← Clase Conectar
├── app/
│   ├── controlador/             ← Controladores del flujo clientes
│   │   ├── loginCon.php         ← Login clientes
│   │   ├── ordenesCon.php       ← Ver documentos/órdenes
│   │   ├── clienteCon.php       ← Perfil cliente
│   │   └── salirCon.php         ← Cerrar sesión
│   ├── modelo/
│   │   ├── loginModelo.php      ← Lógica de login
│   │   └── inicioModelo.php     ← Lógica de negocio
│   └── vista/
│       ├── login.phtml          ← Vista login
│       └── ordenes_2.phtml       ← Vista documentos
└── publico/                     ← Páginas públicas (reorganización)
    ├── controlador/
    └── vista/
```

### 🔄 Flujo de Navegación

```
1. Usuario accede a: /intranet/
   ↓
2. index.php detecta que no hay sesión
   ↓
3. Redirige a: ?pagina=cliente (login)
   ↓
4. Usuario ingresa RUC + Clave
   ↓
5. loginCon.php → loginModelo.php valida credenciales
   ↓
6. Si es válido: Crea sesión $_SESSION['id_geo']
   ↓
7. Redirige a: ?pagina=ordenes
   ↓
8. ordenesCon.php → inicioModelo.php obtiene documentos
   ↓
9. Muestra: ordenes_2.phtml (lista de documentos PDF)
   ↓
10. Cliente puede:
    - Ver documentos
    - Marcar como "visto"
    - Ver estado (APROBADO/OBSERVADO)
    - Descargar PDFs
```

### ✅ Funcionalidades del Cliente
- ✅ Ver sus documentos/órdenes
- ✅ Marcar documentos como "visto"
- ✅ Ver estado de documentos
- ✅ Descargar PDFs
- ✅ Cerrar sesión

---

## 2️⃣ FLUJO ADMINISTRACIÓN (Panel Admin)

### 📍 URL Base
```
http://localhost/public_html/intranet/admin/
```

### 🔐 Autenticación
- **Login:** `/admin/login` o `/admin/` (redirige a login)
- **Credenciales:**
  - Campo: DNI/Usuario
  - Campo: Clave
- **Tabla BD:** `usuarios`
- **Sesión:** `$_SESSION['id_geofal']`

### 📂 Estructura
```
intranet/admin/
├── index.php                    ← Punto de entrada
├── config/
│   ├── config.local.php         ← Configuración local
│   ├── config.php               ← Configuración producción
│   └── sistema.php              ← Clase Conectar
├── app/
│   ├── controlador/             ← Controladores admin
│   │   ├── loginCon.php         ← Login admin
│   │   ├── inicioCon.php        ← Dashboard
│   │   ├── clientesCon.php      ← Gestión clientes
│   │   ├── pdfCon.php           ← Gestión documentos
│   │   ├── ordenesCon.php       ← Gestión órdenes
│   │   └── [otros controladores]
│   ├── modelo/
│   │   ├── loginModelo.php      ← Lógica de login
│   │   └── inicioModelo.php     ← Lógica de negocio
│   └── vista/
│       ├── login.phtml          ← Vista login (rediseñada)
│       └── index.phtml          ← Dashboard admin
├── assets/
│   └── images/
│       └── geofal.png           ← Logo Geofal
└── include/
    └── images/
        └── geofal.png           ← Logo (copia funcional)
```

### 🔄 Flujo de Navegación

```
1. Usuario accede a: /intranet/admin/
   ↓
2. index.php detecta que no hay sesión
   ↓
3. Redirige a: /admin/login
   ↓
4. Usuario ingresa DNI + Clave
   ↓
5. loginCon.php → loginModelo.php valida credenciales
   ↓
6. Si es válido: Crea sesión $_SESSION['id_geofal']
   ↓
7. Redirige a: /admin/inicio
   ↓
8. inicioCon.php → inicioModelo.php carga datos
   ↓
9. Muestra: index.phtml (dashboard administrativo)
   ↓
10. Admin puede:
    - Ver dashboard con estadísticas
    - Gestionar clientes (CRUD)
    - Subir/editar documentos PDF
    - Gestionar órdenes
    - Ver reportes
    - Gestionar usuarios/vendedores
```

### ✅ Funcionalidades del Admin
- ✅ Dashboard con estadísticas
- ✅ CRUD completo de clientes
- ✅ Subir/editar/eliminar documentos PDF
- ✅ Asociar documentos a clientes
- ✅ Cambiar estados de documentos
- ✅ Gestionar órdenes
- ✅ Gestionar usuarios/vendedores
- ✅ Ver reportes y estadísticas

---

## 3️⃣ PÁGINAS PÚBLICAS (Sitio Web)

### 📍 URL Base
```
http://localhost/public_html/intranet/?pagina=[nombre]
```

### 📂 Estructura (En Reorganización)
```
intranet/
├── publico/                     ← NUEVA estructura
│   ├── controlador/
│   │   ├── contactoCon.php     ← Página contacto
│   │   ├── nosotrosCon.php     ← Página nosotros
│   │   ├── galeriaCon.php      ← Página galería
│   │   └── [otras páginas]
│   └── vista/
│       ├── contacto.phtml
│       ├── nosotros.phtml
│       └── [otras vistas]
└── app/controlador/             ← SOLO flujo clientes ahora
    ├── loginCon.php
    ├── ordenesCon.php
    ├── clienteCon.php
    └── salirCon.php
```

### 🔄 Flujo de Navegación

```
1. Usuario accede a: /intranet/?pagina=contacto
   ↓
2. index.php busca controlador:
   - Primero en: app/controlador/contactoCon.php
   - Si no existe: publico/controlador/contactoCon.php
   ↓
3. Carga el controlador correspondiente
   ↓
4. Muestra la vista pública (sin autenticación)
```

### ✅ Páginas Públicas Identificadas
- ✅ Contacto
- ✅ Nosotros
- ✅ Galería
- ✅ Servicios
- ✅ Inicio (página principal)
- ✅ Estudio de Suelos
- ✅ Laboratorio
- ✅ Control de Calidad
- ✅ Evaluación Estructural

---

## 🔀 RUTEO DEL SISTEMA

### `intranet/index.php` (Flujo Clientes + Público)
```php
// Busca controlador en este orden:
1. app/controlador/{pagina}Con.php      ← Flujo clientes
2. publico/controlador/{pagina}Con.php  ← Páginas públicas
3. app/controlador/errorCon.php         ← Error 404
```

### `intranet/admin/index.php` (Flujo Admin)
```php
// Busca controlador en:
app/controlador/{pagina}Con.php
```

---

## 🔐 SISTEMA DE SESIONES

### Clientes
- **Variable:** `$_SESSION['id_geo']`
- **Cookie:** No usa cookie
- **Validación:** En cada controlador del flujo clientes

### Administración
- **Variable:** `$_SESSION['id_geofal']`
- **Cookie:** `id_geofal` (200 días)
- **Validación:** En cada controlador del flujo admin

---

## 📊 BASE DE DATOS

### Tablas Principales
- **`clientes`** - Datos de clientes (RUC, clave, etc.)
- **`usuarios`** - Datos de administradores/vendedores (DNI, clave, etc.)
- **`ordenes`** - Órdenes/documentos asociados a clientes
- **Otras tablas** - Según funcionalidades específicas

---

## 🎯 RESUMEN DE FLUJOS

| Flujo | URL | Login | Sesión | Funcionalidad |
|-------|-----|-------|--------|---------------|
| **Clientes** | `/intranet/` | RUC + Clave | `id_geo` | Ver documentos |
| **Admin** | `/intranet/admin/` | DNI + Clave | `id_geofal` | Gestión completa |
| **Público** | `/intranet/?pagina=X` | Sin login | - | Páginas informativas |

---

## 📝 NOTAS IMPORTANTES

1. **Separación de Flujos:** Los flujos están bien separados (clientes vs admin)
2. **Páginas Públicas:** En proceso de reorganización a `publico/`
3. **Seguridad:** Cada flujo valida su propia sesión
4. **Configuración:** Diferentes archivos de config para local/producción
5. **Logo:** Ubicado en `admin/include/images/geofal.png` (funcional)

---

## 🚀 PRÓXIMOS PASOS

1. ✅ Login admin rediseñado
2. ✅ Logo configurado
3. ⏳ Reorganizar páginas públicas (script listo)
4. ⏳ Eliminar carpetas duplicadas (admin2/, intranet2/)
5. ⏳ Optimización final

