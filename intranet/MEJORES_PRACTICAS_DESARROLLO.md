# Mejores Prácticas para Desarrollo - GEOFAL

## 🎯 Problema Identificado

El archivo `inicioModelo.php` se sobrescribió completamente, perdiendo toda la estructura de la clase y causando errores de sintaxis.

## ✅ Mejores Prácticas para Modificar Archivos

### 1. **SIEMPRE Leer el Archivo Completo Antes de Modificar**

```php
// ❌ MAL - Sobrescribir sin leer
write("archivo.php", "contenido nuevo");

// ✅ BIEN - Leer primero, luego modificar
$contenido = read_file("archivo.php");
// Hacer cambios específicos
search_replace("archivo.php", "texto_antiguo", "texto_nuevo");
```

### 2. **Usar `search_replace` en Lugar de `write` para Archivos Existentes**

```php
// ❌ MAL - Sobrescribe todo el archivo
write("archivo.php", "<?php\nclass MiClase {\n  // ... todo el código\n}");

// ✅ BIEN - Modifica solo lo necesario
search_replace("archivo.php", 
  "public function funcion_antigua()", 
  "public function funcion_nueva()"
);
```

### 3. **Verificar la Estructura del Archivo**

Antes de modificar, verificar:
- ✅ ¿Tiene la declaración de clase?
- ✅ ¿Tiene el constructor?
- ✅ ¿Tiene todas las funciones necesarias?
- ✅ ¿Está correctamente cerrado con `}`?

### 4. **Hacer Backups Antes de Cambios Grandes**

```bash
# Crear backup antes de modificar
cp archivo.php archivo.php.backup
```

### 5. **Modificar Solo la Función Específica**

```php
// ✅ BIEN - Modificar solo la función que necesita cambio
search_replace("archivo.php",
  "header('Location:' . ruta . '?pagina=pdf');",
  "header('Location:' . ruta . '?pagina=pdf&id=' . $id_user);"
);
```

### 6. **Validar Sintaxis Después de Cambios**

```php
// Verificar que el archivo sea válido PHP
php -l archivo.php
```

## 📋 Checklist Antes de Modificar un Archivo

- [ ] Leer el archivo completo
- [ ] Entender la estructura (clase, métodos, etc.)
- [ ] Identificar exactamente qué cambiar
- [ ] Crear backup si es necesario
- [ ] Usar `search_replace` en lugar de `write`
- [ ] Verificar que el cambio no rompa la estructura
- [ ] Probar que el archivo sigue siendo válido

## 🔧 Ejemplo de Modificación Correcta

### Escenario: Corregir redirección en `for_pdf_editar()`

```php
// 1. Leer el archivo completo
$contenido = read_file("inicioModelo.php");

// 2. Identificar la función exacta
// Buscar: "public function for_pdf_editar()"

// 3. Hacer el cambio específico
search_replace("inicioModelo.php",
  "header('Location:' . ruta . '?pagina=pdf');",
  "// Obtener id_user del PDF para redirigir correctamente
    $id_user = isset($sql[0]['id_user']) ? $sql[0]['id_user'] : '';
    if (!empty($id_user)) {
      header('Location:' . ruta . '?pagina=pdf&id=' . $id_user);
    } else {
      header('Location:' . ruta . '?pagina=pdf');
    }"
);

// 4. Verificar sintaxis
// php -l inicioModelo.php
```

## ⚠️ Errores Comunes a Evitar

1. **Sobrescribir archivos completos** - Usa `search_replace`
2. **No leer el archivo primero** - Siempre lee antes de modificar
3. **Modificar sin entender la estructura** - Entiende el contexto
4. **No hacer backups** - Protege tu trabajo
5. **Cambiar múltiples cosas a la vez** - Un cambio a la vez es más seguro

## 🎓 Principio: "No Rompas lo que Funciona"

- Si algo funciona, no lo toques a menos que sea necesario
- Haz cambios mínimos y específicos
- Prueba después de cada cambio
- Si algo se rompe, restaura desde el backup

## 📝 Notas Adicionales

- **Archivos PHP grandes**: Si el archivo es muy grande, lee secciones específicas
- **Múltiples cambios**: Si necesitas hacer varios cambios, hazlos uno por uno
- **Validación**: Siempre valida que el código PHP sea sintácticamente correcto
- **Testing**: Prueba la funcionalidad después de cada cambio

---

**Fecha de creación:** 2025-01-24  
**Última actualización:** 2025-01-24

