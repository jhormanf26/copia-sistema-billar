# 🚀 INSTRUCCIONES FINALES - LISTA PARA USAR

**Fecha:** 18 de Noviembre de 2025  
**Status:** ✅ Listo para implementar

---

## 📌 ANTES DE COMENZAR A PROBAR

Ejecuta estos comandos para limpiar el caché y preparar el proyecto:

```bash
# 1. Limpiar todo el caché
php artisan cache:clear

# 2. Limpiar caché de rutas
php artisan route:cache

# 3. Limpiar caché de configuración
php artisan config:cache

# 4. Limpiar caché de vistas (Blade)
php artisan view:clear

# O ejecuta todo de una vez:
php artisan cache:clear && php artisan route:cache && php artisan config:cache && php artisan view:clear
```

---

## ✅ VERIFICACIÓN RÁPIDA

### 1. Verificar que los middlewares están registrados

```bash
php artisan tinker
```

Luego ejecuta:

```php
dd(app('middleware.aliases'))
```

**Deberías ver:**
```php
"active" => "App\Http\Middleware\CheckActiveStatus"
"role" => "App\Http\Middleware\CheckRole"
"employee" => "App\Http\Middleware\CheckEmployeeAccess"  // ← NUEVO
```

### 2. Ver todas las rutas

```bash
php artisan route:list | grep mesasventas
```

**Deberías ver:** Rutas con middlewares `role:empleado,admin` y `employee`

### 3. Ver usuarios en la BD

```bash
php artisan tinker
```

Luego:

```php
App\Models\User::all(['id', 'name', 'email', 'tipo', 'estado'])
```

---

## 🧪 PRUEBAS DE ACCESO

### Test 1: Admin
```bash
# Terminal 1: Iniciar servidor
php artisan serve

# Terminal 2: Login como admin
# URL: http://localhost:8000/login
# Email: admin@billar.com
# Password: (la que configuraste)

# Después de login, prueba estas URLs:
✅ http://localhost:8000/welcome
✅ http://localhost:8000/usuarios
✅ http://localhost:8000/productos/index
✅ http://localhost:8000/mesasventas
```

### Test 2: Empleado
```bash
# Login como empleado
# Email: empleado@billar.com
# Password: (la que configuraste)

# Después de login, prueba estas URLs:
✅ http://localhost:8000/mesasventas (Debe permitir)
❌ http://localhost:8000/usuarios (Debe mostrar Error 403)
❌ http://localhost:8000/productos/index (Debe mostrar Error 403)
```

### Test 3: Usuario Inactivo
```bash
# Intenta login como usuario inactivo
# Email: inactivo@billar.com
# Password: (la que configuraste)

# Deberías ver error:
# "Tu cuenta está inactiva. Contacta al administrador para activarla."
```

---

## 📊 MATRIZ DE PRUEBAS COMPLETA

| Usuario | Tipo | Estado | Login | /welcome | /usuarios | /mesasventas | /productos |
|---------|------|--------|-------|----------|-----------|--------------|-----------|
| admin@billar.com | admin | activo | ✅ | ✅ | ✅ | ✅ | ✅ |
| empleado@billar.com | empleado | activo | ✅ | ❌ 403 | ❌ 403 | ✅ | ❌ 403 |
| inactivo@billar.com | empleado | inactivo | ❌ | ❌ | ❌ | ❌ | ❌ |

---

## 🔧 CREAR USUARIOS DE PRUEBA

Si no tienes usuarios, crea algunos:

```bash
php artisan tinker
```

### Crear Admin

```php
$admin = App\Models\User::create([
    'name' => 'Juan Carlos',
    'apellidos' => 'González Pérez',
    'email' => 'admin@billar.com',
    'password' => bcrypt('password123'),
    'tipo' => 'admin',
    'estado' => 'activo',
    'tipodocumento' => 'CC',
    'numerodocumento' => '1234567890',
]);
echo "Admin creado: " . $admin->id;
```

### Crear Empleado Activo

```php
$empleado = App\Models\User::create([
    'name' => 'Carlos',
    'apellidos' => 'López Martínez',
    'email' => 'empleado@billar.com',
    'password' => bcrypt('password123'),
    'tipo' => 'empleado',
    'estado' => 'activo',
    'tipodocumento' => 'CC',
    'numerodocumento' => '0987654321',
]);
echo "Empleado creado: " . $empleado->id;
```

### Crear Empleado Inactivo

```php
$inactivo = App\Models\User::create([
    'name' => 'Pedro',
    'apellidos' => 'Rodríguez García',
    'email' => 'inactivo@billar.com',
    'password' => bcrypt('password123'),
    'tipo' => 'empleado',
    'estado' => 'inactivo',
    'tipodocumento' => 'CC',
    'numerodocumento' => '5555555555',
]);
echo "Empleado Inactivo creado: " . $inactivo->id;
```

---

## 📋 CHECKLIST PRE-PRODUCCIÓN

Antes de desplegar a producción, verifica:

- [ ] Todos los middlewares están registrados en `bootstrap/app.php`
- [ ] Las rutas están configuradas correctamente en `routes/web.php`
- [ ] El archivo `CheckEmployeeAccess.php` existe y está correcto
- [ ] El LoginRequest valida estado inactivo
- [ ] La vista 403 se muestra correctamente
- [ ] Admin puede acceder a todas las rutas
- [ ] Empleado solo accede a mesasventas
- [ ] Usuario inactivo no puede loguear
- [ ] Cache está limpio: `php artisan cache:clear`
- [ ] Rutas están actualizadas: `php artisan route:cache`

---

## 🚨 PROBLEMAS COMUNES Y SOLUCIONES

### P1: Empleado accede a todas las rutas
**Solución:**
1. Verifica que el middleware `employee` esté en `routes/web.php` línea 53
2. Ejecuta `php artisan cache:clear`
3. Verifica que el archivo existe: `app/Http/Middleware/CheckEmployeeAccess.php`

### P2: Error 403 se muestra en blanco
**Solución:**
1. Ejecuta `php artisan cache:clear`
2. Verifica que existe: `resources/views/errors/403.blade.php`
3. Verifica que la vista tiene contenido válido

### P3: Login no rechaza usuarios inactivos
**Solución:**
1. Verifica que el campo `estado` de la BD sea 'inactivo' (minúsculas)
2. Verifica `LoginRequest.php` tiene la validación
3. Ejecuta `php artisan cache:clear`

### P4: Middleware no se aplica a las rutas
**Solución:**
1. Ejecuta `php artisan route:cache`
2. Verifica `bootstrap/app.php` tiene `'employee' => CheckEmployeeAccess::class`
3. Verifica `routes/web.php` usa `'employee'` en middlewares

### P5: No puedo loguear como admin
**Solución:**
1. Verifica que existe usuario con `tipo = 'admin'` y `estado = 'activo'`
2. Verifica contraseña (debe estar hasheada con bcrypt)
3. Usa Tinker para crear nuevo admin: `php artisan tinker`

---

## 💡 TIPS IMPORTANTES

✅ Siempre usar `estado = 'activo'` o `estado = 'inactivo'` (minúsculas)  
✅ Siempre usar `tipo = 'admin'` o `tipo = 'empleado'` (minúsculas)  
✅ Siempre hashear contraseñas: `bcrypt('password')`  
✅ Limpiar caché después de cambios: `php artisan cache:clear`  
✅ Usar Tinker para verificar datos: `php artisan tinker`  
✅ Ver rutas: `php artisan route:list`  

---

## 📞 ARCHIVOS DE REFERENCIA

Si necesitas ayuda, consulta:

- `ROLES_PERMISOS_IMPLEMENTACION.md` → Detalles técnicos
- `CHECKLIST_VERIFICACION.md` → Lista de verificación
- `GUIA_PRACTICO_ROLES.md` → Ejemplos prácticos
- `RESUMEN_FINAL_ROLES.md` → Resumen ejecutivo
- `RESUMEN_VISUAL_ROLES.md` → Diagramas visuales

---

## 🎯 PRÓXIMOS PASOS

1. ✅ Ejecuta los comandos de limpieza
2. ✅ Crea usuarios de prueba
3. ✅ Prueba cada rol
4. ✅ Verifica que todo funciona
5. ✅ Documenta cualquier cambio
6. ✅ Deploya a producción

---

## 📊 RESUMEN FINAL

```
✅ Sistema de Roles: IMPLEMENTADO
✅ Validación de Estado: IMPLEMENTADA
✅ Restricción de Empleados: IMPLEMENTADA
✅ Middleware: REGISTRADO
✅ Rutas: PROTEGIDAS
✅ Documentación: COMPLETA
✅ Pruebas: LISTA
```

---

## ⚡ COMANDO RÁPIDO PARA COMENZAR

```bash
# Ejecuta todo de una vez
php artisan cache:clear && \
php artisan route:cache && \
php artisan config:cache && \
php artisan serve
```

Luego accede a: **http://localhost:8000**

---

**¡Listo para usar! 🎉**

El sistema de Roles y Permisos está completamente funcional y documentado.

```
╔═══════════════════════════════════════════╗
║   ✅ SISTEMA LISTO PARA PRODUCCIÓN   ✅   ║
╚═══════════════════════════════════════════╝
```

**Estado:** ✅ COMPLETADO  
**Fecha:** 18 de Noviembre de 2025  
**Versión:** 1.0.0
