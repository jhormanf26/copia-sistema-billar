# 🎯 GUÍA PASO A PASO - CONTROL DE ROLES EN REGISTRO

---

## 📍 Paso 1: Entender la Estructura

### Campo `tipo` en la tabla `users`

```sql
-- En la migración ya existe este campo
CREATE TABLE users (
    id BIGINT PRIMARY KEY,
    name VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    tipo ENUM('admin', 'empleado', 'gerente'),  ← ESTE CAMPO
    estado VARCHAR(50),  -- 'activo' o 'inactivo'
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Valores Permitidos

```
'admin'     → Acceso total
'empleado'  → Solo Mesas Ventas
'gerente'   → Mesas Ventas + Reportes
```

---

## 📍 Paso 2: Ubicar el Formulario de Creación

### Ruta: `/usuarios/crear`
### Archivo: `resources/views/users/create.blade.php`

**Ya tiene el selector de tipo:**

```blade
<div class="form-group">
    <label for="tipo">Tipo de Usuario *</label>
    <select class="form-control" id="tipo" name="tipo" required>
        <option value="">Selecciona un tipo</option>
        <option value="admin">Administrador</option>
        <option value="empleado">Empleado</option>
        <option value="gerente">Gerente</option>
    </select>
</div>
```

---

## 📍 Paso 3: Verificar Validación en Controlador

### Archivo: `app/Http/Controllers/UsersController.php`

```php
public function store(Request $request)
{
    // VALIDACIÓN: tipo debe estar en la lista
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'apellidos' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'unique:users,email'],
        'numerodocumento' => ['required', 'string', 'unique:users,numerodocumento'],
        'tipodocumento' => ['required', 'string'],
        'tipo' => ['required', 'string', 'in:admin,empleado,gerente'],  ← IMPORTANTE
        'password' => ['required', 'string', 'min:8', 'confirmed'],
    ]);

    // GUARDAR: Se guarda el tipo
    User::create([
        'name' => $request->name,
        'apellidos' => $request->apellidos,
        'email' => $request->email,
        'numerodocumento' => $request->numerodocumento,
        'tipodocumento' => $request->tipodocumento,
        'tipo' => $request->tipo,  ← TIPO SELECCIONADO
        'password' => Hash::make($request->password),
    ]);

    return redirect()->route('users.index')->with('success', 'Usuario creado.');
}
```

---

## 📍 Paso 4: Verificar Middlewares

### Archivo: `bootstrap/app.php`

```php
$middleware->alias([
    'active' => CheckActiveStatus::class,
    'role' => CheckRole::class,
    'employee' => CheckEmployeeAccess::class,  // ← Para empleados
]);
```

---

## 📍 Paso 5: Verificar Rutas Protegidas

### Archivo: `routes/web.php`

```php
// RUTAS SOLO PARA ADMINS
Route::middleware('role:admin')->group(function () {
    Route::get('/usuarios', [UsersController::class, 'index']);
    Route::get('/usuarios/crear', [UsersController::class, 'create']);
    Route::post('/usuarios', [UsersController::class, 'store']);
    Route::get('/productos/index', [ProductosController::class, 'index']);
    // ... más rutas admin
});

// RUTAS PARA EMPLEADOS Y ADMINS (restringida)
Route::middleware(['role:empleado,admin', 'employee'])->group(function () {
    Route::get('/mesasventas', [MesasventasController::class, 'index']);
    Route::get('/mesasventas/create', [MesasventasController::class, 'create']);
    // ... rutas mesasventas
});
```

---

## 🧪 Paso 6: Probar Creación de Usuario

### Prueba 1: Crear Empleado

1. **Login como admin**
   - Email: `admin@billar.com`
   - Contraseña: `password`

2. **Ir a** `/usuarios/crear`

3. **Llenar formulario:**
   ```
   Nombre:        Carlos
   Apellidos:     López
   Email:         carlos@billar.com
   Documento:     0987654321
   Tipo Doc:      CC
   Tipo Usuario:  Empleado  ← IMPORTANTE
   Contraseña:    password123
   Confirmar:     password123
   ```

4. **Click en** "Crear Usuario"

5. **Verificar:**
   - Mensaje de éxito
   - Usuario aparece en lista con tipo "Empleado"

### Prueba 2: Crear Admin

1. **Repetir Prueba 1 pero cambiar:**
   ```
   Nombre:        Pedro
   Email:         pedro@billar.com
   Tipo Usuario:  Administrador  ← CAMBIAR A ADMIN
   ```

2. **Verificar:**
   - Usuario aparece con tipo "Administrador"

---

## 🧪 Paso 7: Probar Acceso por Rol

### Prueba 3: Empleado accede a Mesasventas

1. **Logout**
   - Click en nombre de usuario → Logout

2. **Login como empleado**
   - Email: `carlos@billar.com`
   - Contraseña: `password123`

3. **Verificar:**
   - ✅ Redirige a `/mesasventas` (Permitido)
   - ✅ Puede ver listado de mesas

### Prueba 4: Empleado intenta acceder a Usuarios

1. **En la URL escribe:** `/usuarios`

2. **Verificar:**
   - ❌ Error 403 "Acceso Denegado"
   - ❌ Muestra mensaje claro

### Prueba 5: Admin accede a TODO

1. **Logout y login como admin**

2. **Prueba estas rutas:**
   - ✅ `/welcome` → Funciona
   - ✅ `/usuarios` → Funciona
   - ✅ `/productos/index` → Funciona
   - ✅ `/mesasventas` → Funciona
   - ✅ `/compras` → Funciona

3. **Verificar:**
   - Todas las rutas funcionan

---

## 🔄 Paso 8: Cambiar Rol de Usuario

### Para cambiar el tipo después de crear el usuario:

1. **Como admin, ve a** `/usuarios`

2. **Click en el nombre del usuario** (Ej: Carlos López)

3. **Click en "Editar"**

4. **Busca el campo "Tipo de Usuario"**
   - Cambiar de "Empleado" a otro rol

5. **Click en "Guardar Cambios"**

6. **Verificar:**
   - Mensaje de éxito
   - Usuario actualizado

---

## 📋 Checklist de Implementación

```
VERIFICACIÓN DE CÓDIGO:
[ ] Campo 'tipo' existe en tabla users
[ ] Formulario users/create.blade.php tiene selector
[ ] UsersController.php valida tipo
[ ] Middlewares están registrados en bootstrap/app.php
[ ] Rutas están protegidas en web.php
[ ] Vista 403 existe y funciona

PRUEBAS FUNCIONALES:
[ ] Crear usuario empleado exitosamente
[ ] Crear usuario admin exitosamente
[ ] Empleado login exitoso
[ ] Empleado accede a /mesasventas ✅
[ ] Empleado accede a /usuarios ❌ (Error 403)
[ ] Admin accede a todas las rutas ✅
[ ] Error 403 muestra interfaz correcta
[ ] Cambiar rol de usuario funciona

SEGURIDAD:
[ ] Solo admins pueden crear usuarios
[ ] Solo admins pueden cambiar roles
[ ] Validación en servidor (no solo cliente)
[ ] Usuarios inactivos no pueden loguear
[ ] Rol se respeta en cada solicitud
```

---

## 🐛 Troubleshooting

### Problema 1: Selector de tipo no aparece

**Solución:**
```bash
# Verificar archivo existe
ls resources/views/users/create.blade.php

# Verificar contiene select tipo
grep -n "tipo" resources/views/users/create.blade.php

# Debe mostrar la línea con <select... id="tipo"
```

### Problema 2: Validación rechaza tipos válidos

**Solución:**
```php
// En UsersController.php verificar:
'tipo' => ['required', 'string', 'in:admin,empleado,gerente'],

// Los valores deben coincidir exactamente:
// 'admin'    ✅
// 'empleado' ✅
// 'gerente'  ✅
// 'empleado ' ❌ (con espacio)
```

### Problema 3: Empleado accede a rutas admin

**Solución:**
```bash
# Limpiar caché
php artisan cache:clear

# Recargar rutas
php artisan route:cache

# Verificar middlewares en web.php
grep -n "employee" routes/web.php
```

### Problema 4: Error 403 no se muestra

**Solución:**
```bash
# Verificar vista existe
ls resources/views/errors/403.blade.php

# Limpiar caché de vistas
php artisan view:clear
```

---

## ✅ Resumen Rápido

```
CONTROL DE ROLES EN 3 PASOS:

1. CREAR
   └─ Admin va a /usuarios/crear
   └─ Selecciona tipo: admin/empleado
   └─ Sistema valida y guarda

2. VERIFICAR
   └─ Sistema obtiene tipo de la BD
   └─ Middleware verifica tipo

3. AUTORIZAR
   └─ Admin → Acceso total ✅
   └─ Empleado → Solo mesasventas ✅
   └─ Otro → Error 403 ❌
```

---

## 🎓 Conclusión

**Ahora sabes cómo:**

✅ Crear usuarios con tipos específicos  
✅ Validar tipos en el servidor  
✅ Proteger rutas según el tipo  
✅ Cambiar tipos de usuarios  
✅ Probar el control de roles  
✅ Solucionar problemas comunes  

**¡Tu sistema está completamente configurado! 🎉**

---

**Última Actualización:** 18 de Noviembre de 2025  
**Estado:** ✅ COMPLETADO
