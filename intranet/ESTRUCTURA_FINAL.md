# 📁 ESTRUCTURA FINAL DEL PROYECTO GEOFAL

## ✅ REORGANIZACIÓN COMPLETADA

### 🎯 Estructura Final

```
intranet/
├── app/                          ← FLUJO CLIENTES (solo autenticado)
│   ├── controlador/
│   │   ├── loginCon.php         ← Login clientes
│   │   ├── ordenesCon.php       ← Ver documentos
│   │   ├── clienteCon.php       ← Perfil cliente
│   │   └── salirCon.php         ← Cerrar sesión
│   ├── modelo/
│   │   ├── loginModelo.php
│   │   └── inicioModelo.php
│   └── vista/
│       ├── login.phtml
│       ├── ordenes_2.phtml
│       ├── head.php              ← Compartido con páginas públicas
│       ├── header.php            ← Compartido con páginas públicas
│       └── footer.php           ← Compartido con páginas públicas
│
├── publico/                      ← PÁGINAS PÚBLICAS (sin autenticación)
│   ├── controlador/
│   │   ├── contactoCon.php
│   │   ├── nosotrosCon.php
│   │   ├── galeriaCon.php
│   │   ├── inicioCon.php
│   │   ├── estudio-de-suelosCon.php
│   │   ├── laboratorio-de-suelo-concreto-pavimento-y-albanileriaCon.php
│   │   ├── control-de-calidad-de-obras-civilesCon.php
│   │   └── evaluacion-estructural-e-ingenieriaCon.php
│   ├── vista/
│   │   ├── contacto.phtml
│   │   ├── nosotros.phtml
│   │   ├── galeria.phtml
│   │   ├── index.phtml
│   │   └── [otras vistas públicas]
│   ├── img/                      ← Imágenes del sitio público
│   └── img_data/                 ← PDFs/documentos
│
├── admin/                        ← FLUJO ADMINISTRACIÓN
│   ├── app/
│   │   ├── controlador/          ← Controladores admin
│   │   ├── modelo/               ← Modelos admin
│   │   ├── vista/                ← Vistas admin
│   │   │   └── login.phtml       ← Login rediseñado
│   │   └── css/
│   │       └── login-profesional.css
│   ├── assets/
│   │   └── images/
│   │       └── geofal.png        ← Logo Geofal
│   └── include/
│       └── images/
│           └── geofal.png        ← Logo (copia funcional)
│
├── config/                       ← Configuración principal
│   ├── config.local.php          ← Config local
│   ├── config.php                ← Config producción
│   └── sistema.php               ← Clase Conectar
│
└── index.php                     ← Router principal (busca en app/ y publico/)
```

---

## 🔄 FLUJOS DEL SISTEMA

### 1. Flujo Clientes
- **URL:** `/intranet/` o `/intranet/?pagina=cliente`
- **Login:** RUC + Clave
- **Controladores:** `app/controlador/`
- **Vistas:** `app/vista/`

### 2. Flujo Administración
- **URL:** `/intranet/admin/`
- **Login:** DNI + Clave
- **Controladores:** `admin/app/controlador/`
- **Vistas:** `admin/app/vista/`

### 3. Páginas Públicas
- **URL:** `/intranet/?pagina=contacto` (o cualquier página pública)
- **Sin login**
- **Controladores:** `publico/controlador/`
- **Vistas:** `publico/vista/`

---

## 📋 RUTAS CORREGIDAS

### Controladores Públicos
- ✅ `app/modelo/inicioModelo.php` → `../../app/modelo/inicioModelo.php`
- ✅ `app/vista/{pagina}.phtml` → `../vista/{pagina}.phtml`
- ✅ `keys.php` → `../../app/controlador/keys.php`

### Vistas Públicas
- ✅ `head.php` → `../../app/vista/head.php`
- ✅ `header.php` → `../../app/vista/header.php`
- ✅ `footer.php` → `../../app/vista/footer.php`
- ✅ `carusel.php` → `../../app/vista/carusel.php`

---

## ✅ ESTADO ACTUAL

- ✅ Login admin rediseñado profesionalmente
- ✅ Logo Geofal configurado y visible
- ✅ Páginas públicas reorganizadas en `publico/`
- ✅ Rutas corregidas en controladores públicos
- ✅ Rutas corregidas en vistas públicas
- ✅ `index.php` busca en ambas carpetas (app/ y publico/)
- ✅ Flujo clientes limpio (solo 4 archivos)
- ✅ Estructura profesional y organizada

---

## 🎯 PRÓXIMOS PASOS (Opcional)

1. ⏳ Eliminar carpetas duplicadas (admin2/, intranet2/)
2. ⏳ Optimización de rendimiento
3. ⏳ Documentación adicional

---

## 📝 NOTAS

- Los archivos `head.php`, `header.php`, `footer.php` están en `app/vista/` y son compartidos
- El logo está en `admin/include/images/geofal.png` (ruta funcional)
- La estructura está lista para producción

