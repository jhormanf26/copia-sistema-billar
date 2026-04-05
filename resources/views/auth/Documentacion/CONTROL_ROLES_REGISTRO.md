# 🛠️ GUÍA - CONTROL DE ROLES EN REGISTRO DE USUARIOS

**Fecha:** 18 de Noviembre de 2025

---

## 📋 Tabla de Contenidos

1. [Entender los Roles](#1-entender-los-roles)
2. [Dónde se Controlan los Roles](#2-dónde-se-controlan-los-roles)
3. [Registro por Admin (Recomendado)](#3-registro-por-admin-recomendado)
4. [Registro Público (Empleados)](#4-registro-público-empleados)
5. [Cambiar Rol de Usuarios](#5-cambiar-rol-de-usuarios)
6. [Restricciones en Formularios](#6-restricciones-en-formularios)
7. [Mejores Prácticas](#7-mejores-prácticas)

---

## 1️⃣ Entender los Roles

### Roles Disponibles

```
┌─────────────────────────────────────────────┐
│ TIPO DE USUARIO                             │
├─────────────────────────────────────────────┤
│ 'admin'       → Administrador               │
│ 'empleado'    → Empleado                    │
│ 'gerente'     → Gerente (opcional)          │
└─────────────────────────────────────────────┘
```

### Permisos por Rol

```
┌──────────────────────────────────────────────────┐
│ ADMIN                                            │
├──────────────────────────────────────────────────┤
│ ✅ Acceso total a todas las rutas               │
│ ✅ Puede crear/editar/eliminar usuarios         │
│ ✅ Ve panel administrativo                      │
│ ✅ Puede cambiar roles                          │
└──────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────┐
│ EMPLEADO                                         │
├──────────────────────────────────────────────────┤
│ ✅ Acceso a Mesas Ventas                        │
│ ❌ No accede a gestión administrativa           │
│ ❌ No puede crear usuarios                      │
│ ❌ No ve panel de admin                         │
└──────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────┐
│ GERENTE (Opcional)                              │
├──────────────────────────────────────────────────┤
│ ✅ Acceso a Mesas Ventas                        │
│ ✅ Acceso a Reportes                           │
│ ❌ No puede crear usuarios                      │
│ ❌ No accede a Administración completa         │
└──────────────────────────────────────────────────┘
```

---

## 2️⃣ Dónde se Controlan los Roles

### 📁 Archivos Importantes

```
app/Http/Controllers/
├─ Auth/RegisteredUserController.php    ← Registro público
└─ UsersController.php                  ← Crear usuarios (admin)

resources/views/
├─ auth/register.blade.php              ← Formulario registro público
└─ users/create.blade.php               ← Formulario crear usuario (admin)

app/Models/
└─ User.php                             ← Modelo de usuario

database/migrations/
└─ *_create_users_table.php             ← Campo 'tipo'
```

---

## 3️⃣ Registro por Admin (Recomendado)

### ✅ La Mejor Práctica

**Solo los administradores pueden crear usuarios con roles específicos.**

### Ubicación: `resources/views/users/create.blade.php`

Ya existe un selector de roles:

```blade
<div class="form-group">
    <label for="tipo">
        <i class="fas fa-briefcase mr-2 text-danger"></i> 
        <strong>Tipo de Usuario</strong> *
    </label>
    <select class="form-control @error('tipo') is-invalid @enderror" 
            id="tipo" 
            name="tipo"
            required>
        <option value="">Selecciona un tipo</option>
        <option value="admin" @if(old('tipo') === 'admin') selected @endif>
            Administrador
        </option>
        <option value="empleado" @if(old('tipo') === 'empleado') selected @endif>
            Empleado
        </option>
        <option value="gerente" @if(old('tipo') === 'gerente') selected @endif>
            Gerente
        </option>
    </select>
    @error('tipo')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>
```

### Controlador: `app/Http/Controllers/UsersController.php`

```php
public function store(Request $request)
{
    // Validación - el admin puede crear cualquier tipo
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'apellidos' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'max:255', 'unique:users,email'],
        'numerodocumento' => ['required', 'string', 'max:255', 'unique:users,numerodocumento'],
        'tipodocumento' => ['required', 'string'],
        'tipo' => ['required', 'string', 'in:admin,empleado,gerente'],  // ← Roles permitidos
        'password' => ['required', 'string', 'min:8', 'confirmed'],
    ]);

    User::create([
        'name' => $request->name,
        'apellidos' => $request->apellidos,
        'email' => $request->email,
        'numerodocumento' => $request->numerodocumento,
        'tipodocumento' => $request->tipodocumento,
        'tipo' => $request->tipo,  // ← Se guarda el tipo enviado
        'estado' => 'activo',      // Nuevo usuario siempre activo
        'password' => Hash::make($request->password),
    ]);

    return redirect()->route('users.index')->with('success', 'Usuario creado correctamente.');
}
```

---

## 4️⃣ Registro Público (Empleados)

### ⚠️ Opción: Permitir Autoregistro como Empleado

Si quieres que los usuarios se registren solos como empleados:

### Ubicación: `app/Http/Controllers/Auth/RegisteredUserController.php`

```php
public function store(Request $request): RedirectResponse
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'numerodocumento' => ['required', 'string', 'max:255'],
        'tipodocumento' => ['required', 'string'],  // Añadido
        'apellidos' => ['required', 'string', 'max:255'],  // Añadido
    ]);

    $user = User::create([
        'name' => $request->name,
        'apellidos' => $request->apellidos,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'numerodocumento' => $request->numerodocumento,
        'tipodocumento' => $request->tipodocumento,
        'tipo' => 'empleado',  // ← SIEMPRE empleado en autoregistro
        'estado' => 'inactivo',  // ← Inactivo hasta que admin apruebe
    ]);

    event(new Registered($user));

    return redirect()->route('login')
                     ->with('success', 'Registro exitoso. Espera aprobación del administrador.');
}
```

### Ubicación: `resources/views/auth/register.blade.php`

```blade
<!-- Campo Tipo de Documento -->
<div>
    <x-input-label for="tipodocumento" :value="__('Tipo de Documento')" />
    <select id="tipodocumento" name="tipodocumento" class="form-control" required>
        <option value="">Selecciona un tipo</option>
        <option value="CC">Cédula de Ciudadanía</option>
        <option value="CE">Cédula de Extranjería</option>
        <option value="PA">Pasaporte</option>
        <option value="NIT">NIT</option>
    </select>
    <x-input-error :messages="$errors->get('tipodocumento')" class="mt-2" />
</div>

<!-- Campo Apellidos -->
<div class="mt-4">
    <x-input-label for="apellidos" :value="__('Apellidos')" />
    <x-text-input id="apellidos" class="block mt-1 w-full" type="text" 
                  name="apellidos" :value="old('apellidos')" required />
    <x-input-error :messages="$errors->get('apellidos')" class="mt-2" />
</div>

<!-- Campo Número de Documento -->
<div class="mt-4">
    <x-input-label for="numerodocumento" :value="__('Número de Documento')" />
    <x-text-input id="numerodocumento" class="block mt-1 w-full" type="text" 
                  name="numerodocumento" :value="old('numerodocumento')" required />
    <x-input-error :messages="$errors->get('numerodocumento')" class="mt-2" />
</div>

<!-- Nota importante -->
<div class="mt-4 p-3 alert alert-info">
    <strong>ℹ️ Nota:</strong> Al registrarte, tu cuenta será configurada como <strong>Empleado</strong> 
    con estado <strong>Inactivo</strong>. 
    Un administrador deberá activar tu cuenta.
</div>
```

---

## 5️⃣ Cambiar Rol de Usuarios

### Opción A: Por Panel de Admin

**Ubicación:** `/usuarios/{id}/editar`

```blade
<!-- En users/edit.blade.php -->
<div class="form-group">
    <label for="tipo">
        <i class="fas fa-briefcase mr-2 text-danger"></i> 
        <strong>Tipo de Usuario</strong> *
    </label>
    <select class="form-control @error('tipo') is-invalid @enderror" 
            id="tipo" 
            name="tipo"
            required>
        <option value="">Selecciona un tipo</option>
        <option value="admin" @if(old('tipo', $user->tipo) === 'admin') selected @endif>
            Administrador
        </option>
        <option value="empleado" @if(old('tipo', $user->tipo) === 'empleado') selected @endif>
            Empleado
        </option>
        <option value="gerente" @if(old('tipo', $user->tipo) === 'gerente') selected @endif>
            Gerente
        </option>
    </select>
    @error('tipo')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>
```

**Controlador:** `app/Http/Controllers/UsersController.php`

```php
public function update(Request $request, User $user)
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'apellidos' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
        'numerodocumento' => ['required', 'string', 'max:255', 'unique:users,numerodocumento,' . $user->id],
        'tipodocumento' => ['required', 'string'],
        'tipo' => ['required', 'string', 'in:admin,empleado,gerente'],
    ]);

    $user->update([
        'name' => $request->name,
        'apellidos' => $request->apellidos,
        'email' => $request->email,
        'numerodocumento' => $request->numerodocumento,
        'tipodocumento' => $request->tipodocumento,
        'tipo' => $request->tipo,  // ← Cambiar rol
    ]);

    return redirect()->route('users.index')->with('success', 'Usuario actualizado.');
}
```

### Opción B: Por CLI (Tinker)

```bash
php artisan tinker

# Ver usuarios
App\Models\User::all(['id', 'name', 'tipo']);

# Cambiar usuario a admin
$user = App\Models\User::find(2);
$user->update(['tipo' => 'admin']);
echo "Usuario actualizado a admin";

# Cambiar usuario a empleado
$user = App\Models\User::find(2);
$user->update(['tipo' => 'empleado']);
echo "Usuario actualizado a empleado";
```

---

## 6️⃣ Restricciones en Formularios

### Mostrar/Ocultar Campos según el Rol

```blade
<!-- En users/create.blade.php -->

<!-- Mostrar opciones de admin SOLO si el que crea es admin -->
@if(auth()->user()->tipo === 'admin')
    <div class="alert alert-warning" role="alert">
        <i class="fas fa-crown mr-2"></i>
        <strong>Eres Administrador:</strong> Puedes crear usuarios de cualquier tipo
    </div>
@endif

<!-- Seleccionar tipo de usuario -->
<div class="form-group">
    <label for="tipo">
        <i class="fas fa-briefcase mr-2 text-danger"></i> 
        <strong>Tipo de Usuario</strong> *
    </label>
    <select class="form-control @error('tipo') is-invalid @enderror" 
            id="tipo" 
            name="tipo"
            required>
        <option value="">Selecciona un tipo</option>
        <option value="admin">Administrador (🔐 Acceso total)</option>
        <option value="empleado">Empleado (💼 Solo Mesas Ventas)</option>
        <option value="gerente">Gerente (📊 Mesas + Reportes)</option>
    </select>
    @error('tipo')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>

<!-- Ayuda contextual por rol -->
<div class="mt-3">
    <div id="roleInfo" class="alert fade" role="alert">
        <!-- Se actualiza dinámicamente con JavaScript -->
    </div>
</div>

<script>
document.getElementById('tipo').addEventListener('change', function() {
    const tipo = this.value;
    const info = document.getElementById('roleInfo');
    
    let mensaje = '';
    let clase = '';
    
    switch(tipo) {
        case 'admin':
            mensaje = '<strong>👤 Administrador:</strong> Acceso total a todas las funciones, gestión de usuarios y reportes.';
            clase = 'alert-danger';
            break;
        case 'empleado':
            mensaje = '<strong>💼 Empleado:</strong> Solo puede usar Mesas Ventas.';
            clase = 'alert-info';
            break;
        case 'gerente':
            mensaje = '<strong>📊 Gerente:</strong> Acceso a Mesas Ventas y Reportes.';
            clase = 'alert-warning';
            break;
        default:
            info.classList.add('d-none');
            return;
    }
    
    info.textContent = '';
    info.innerHTML = mensaje;
    info.className = `alert ${clase} fade show`;
});
</script>
```

---

## 7️⃣ Mejores Prácticas

### ✅ DO's (Lo que SÍ hacer)

✅ **Validar en el servidor** que el tipo está en la lista permitida  
✅ **Usar middleware** para proteger rutas de creación de usuarios  
✅ **Solo admins** pueden crear otros admins  
✅ **Mostrar ayuda** en la UI sobre qué hace cada rol  
✅ **Registrar cambios** de rol en logs (auditoría)  
✅ **Confirmar cambios** críticos (cambiar a admin)  

### ❌ DON'Ts (Lo que NO hacer)

❌ **No permitir** que empleados creen admins  
❌ **No omitir** validación en servidor (solo frontend es inseguro)  
❌ **No guardar** tipo de usuario si no está validado  
❌ **No permitir** autoregistro como admin  
❌ **No mostrar** contraseñas en logs  

---

## 📊 Flujo Recomendado

```
┌─────────────────────────────────────────────────┐
│ NUEVO USUARIO                                   │
└─────┬───────────────────────────────────────────┘
      │
      ├─ Opción A: Autoregistro
      │  └─ Tipo: Siempre EMPLEADO
      │  └─ Estado: INACTIVO
      │  └─ Admin debe activar
      │
      ├─ Opción B: Admin lo crea
      │  └─ Tipo: Admin elige (admin/empleado/gerente)
      │  └─ Estado: ACTIVO
      │  └─ Listo para usar
      │
      └─ Opción C: Cambiar rol después
         └─ Admin edita usuario
         └─ Cambia tipo
         └─ Efectivo inmediatamente
```

---

## 🔐 Seguridad

### Validación en Servidor

```php
// SIEMPRE validar en servidor
'tipo' => ['required', 'string', 'in:admin,empleado,gerente'],
```

### Middleware de Protección

```php
// Solo admins pueden crear usuarios
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/usuarios/crear', [UsersController::class, 'create'])->name('users.create');
    Route::post('/usuarios', [UsersController::class, 'store'])->name('users.store');
});
```

### Auditoría

```php
// Log cuando cambia el rol
\Illuminate\Support\Facades\Log::info('Rol de usuario cambiado', [
    'usuario_id' => $user->id,
    'rol_anterior' => $old_tipo,
    'rol_nuevo' => $request->tipo,
    'cambiado_por' => auth()->user()->id,
]);
```

---

## 🧪 Ejemplos Prácticos

### Crear Admin por CLI

```bash
php artisan tinker
$user = App\Models\User::create([
    'name' => 'Juan',
    'apellidos' => 'Admin',
    'email' => 'juan@billar.com',
    'password' => bcrypt('password123'),
    'tipo' => 'admin',
    'estado' => 'activo',
    'tipodocumento' => 'CC',
    'numerodocumento' => '1234567890',
]);
echo "Admin creado: " . $user->id;
```

### Crear Empleado por CLI

```bash
php artisan tinker
$user = App\Models\User::create([
    'name' => 'Carlos',
    'apellidos' => 'Empleado',
    'email' => 'carlos@billar.com',
    'password' => bcrypt('password123'),
    'tipo' => 'empleado',
    'estado' => 'activo',
    'tipodocumento' => 'CC',
    'numerodocumento' => '0987654321',
]);
echo "Empleado creado: " . $user->id;
```

### Cambiar Rol por CLI

```bash
php artisan tinker
$user = App\Models\User::find(2);
$user->update(['tipo' => 'admin']);
echo "Usuario ahora es admin";
```

---

## 📋 Checklist de Implementación

- [ ] Entender diferencia entre roles
- [ ] Revisar `UsersController.php` 
- [ ] Revisar `RegisteredUserController.php`
- [ ] Verificar `users/create.blade.php`
- [ ] Verificar `auth/register.blade.php`
- [ ] Probar creación de admin
- [ ] Probar creación de empleado
- [ ] Probar cambio de rol
- [ ] Verificar permisos por rol
- [ ] Verificar acceso a rutas según rol

---

**¡Ahora sabes cómo controlar los roles en el registro! 🎉**

Próximo paso: Implementar auditoría de cambios de rol (Opcional)
