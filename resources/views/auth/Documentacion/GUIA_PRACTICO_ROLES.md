# 📖 GUÍA PRÁCTICA - USANDO ROLES Y PERMISOS

## Introducción

Esta guía te enseña cómo usar los roles y permisos implementados en el proyecto BILLAR NEXUS.

---

## 1️⃣ Crear un Administrador

```bash
# Acceder a Tinker
php artisan tinker

# Crear usuario admin
$admin = App\Models\User::create([
    'name' => 'Juan',
    'apellidos' => 'González',
    'email' => 'juan.admin@billar.com',
    'password' => bcrypt('password123'),
    'tipo' => 'admin',           # ← Admin
    'estado' => 'activo',         # ← Activo
    'tipodocumento' => 'CC',
    'numerodocumento' => '1234567890',
]);

echo "Admin creado con ID: " . $admin->id;
```

### Resultado:
- ✅ Puede loguear sin problemas
- ✅ Tiene acceso a TODAS las rutas
- ✅ Ve el panel de administración completo

---

## 2️⃣ Crear un Empleado Activo

```bash
php artisan tinker

# Crear usuario empleado
$empleado = App\Models\User::create([
    'name' => 'Carlos',
    'apellidos' => 'López',
    'email' => 'carlos.empleado@billar.com',
    'password' => bcrypt('password123'),
    'tipo' => 'empleado',        # ← Empleado
    'estado' => 'activo',         # ← Activo
    'tipodocumento' => 'CC',
    'numerodocumento' => '0987654321',
]);

echo "Empleado creado con ID: " . $empleado->id;
```

### Resultado:
- ✅ Puede loguear sin problemas
- ✅ Tiene acceso SOLO a `/mesasventas`
- ✅ No puede ver /usuarios, /productos, /compras, etc.

---

## 3️⃣ Crear un Empleado Inactivo

```bash
php artisan tinker

# Crear usuario empleado inactivo
$empleado_inactivo = App\Models\User::create([
    'name' => 'Pedro',
    'apellidos' => 'Martínez',
    'email' => 'pedro.inactivo@billar.com',
    'password' => bcrypt('password123'),
    'tipo' => 'empleado',        # ← Empleado
    'estado' => 'inactivo',       # ← INACTIVO
    'tipodocumento' => 'CC',
    'numerodocumento' => '5555555555',
]);

echo "Empleado Inactivo creado con ID: " . $empleado_inactivo->id;
```

### Resultado:
- ❌ NO puede loguear
- ❌ Mensaje: "Tu cuenta está inactiva. Contacta al administrador para activarla."

---

## 4️⃣ Cambiar Estado de un Usuario (Activo ↔ Inactivo)

### Opción A: Por CLI

```bash
php artisan tinker

# Inactivar un usuario
$user = App\Models\User::find(2);
$user->update(['estado' => 'inactivo']);
echo "Usuario desactivado";

# Activar un usuario
$user = App\Models\User::find(2);
$user->update(['estado' => 'activo']);
echo "Usuario activado";
```

### Opción B: Por SQL

```sql
-- Inactivar usuario
UPDATE users SET estado = 'inactivo' WHERE id = 2;

-- Activar usuario
UPDATE users SET estado = 'activo' WHERE id = 2;
```

### Opción C: En el Controlador

Si existe un método `toggleStatus` en `UsersController`:

```blade
<!-- En la vista -->
<form action="{{ route('users.toggleStatus', $user) }}" method="POST">
    @csrf
    <button type="submit" class="btn btn-warning">
        Toggle Estado
    </button>
</form>
```

---

## 5️⃣ Cambiar Rol de un Usuario

```bash
php artisan tinker

# Convertir empleado a admin
$user = App\Models\User::find(2);
$user->update(['tipo' => 'admin']);
echo "Usuario ahora es admin";

# Convertir admin a empleado
$user = App\Models\User::find(1);
$user->update(['tipo' => 'empleado']);
echo "Usuario ahora es empleado";
```

---

## 6️⃣ Verificar Tipo de Usuario en Vistas

### En Blade Template:

```blade
@if(auth()->check())
    <!-- Para todos los usuarios -->
    <p>Bienvenido {{ auth()->user()->name }}</p>
    
    <!-- Solo para admins -->
    @if(auth()->user()->tipo === 'admin')
        <a href="{{ route('usuarios') }}">Gestionar Usuarios</a>
    @endif
    
    <!-- Solo para empleados -->
    @if(auth()->user()->tipo === 'empleado')
        <a href="{{ route('mesasventas.index') }}">Mesas Ventas</a>
    @endif
    
    <!-- Mostrar tipo de usuario -->
    <p>Tu rol: {{ ucfirst(auth()->user()->tipo) }}</p>
@endif
```

---

## 7️⃣ Verificar Estado de Usuario en Vistas

```blade
@if(auth()->check())
    <!-- Mostrar estado -->
    @if(auth()->user()->estado === 'activo')
        <span class="badge badge-success">Activo</span>
    @else
        <span class="badge badge-danger">Inactivo</span>
    @endif
    
    <!-- Acciones según estado -->
    @if(auth()->user()->estado === 'inactivo')
        <p class="text-warning">Tu cuenta está inactiva</p>
    @endif
@endif
```

---

## 8️⃣ Rutas Accesibles por Rol

### 🔑 ADMINISTRADOR - Acceso Total

```
✅ /welcome                          (Dashboard)
✅ /perfil                           (Perfil)
✅ /productos/index                  (Gestionar Productos)
✅ /inventario/index                 (Inventario)
✅ /proveedores/index                (Gestionar Proveedores)
✅ /mesas/index                      (Gestionar Mesas)
✅ /mesasventas                      (Mesas Ventas)
✅ /informes                         (Reportes)
✅ /compras                          (Gestionar Compras)
✅ /usuarios                         (Gestionar Usuarios)
✅ Todas las demás rutas...
```

### 👷 EMPLEADO - Acceso Limitado

```
✅ /perfil                           (Perfil personal)
✅ /mesasventas                      (VER y GESTIONAR mesas)
✅ /mesasventas/create
✅ /mesasventas/historial
✅ /mesasventas/{id}
✅ POST /mesasventas/store
✅ POST /mesasventas/{id}/iniciar
✅ POST /mesasventas/{id}/finalizar
✅ etc... (todas las rutas de mesasventas)

❌ /welcome
❌ /productos/index
❌ /inventario/index
❌ /proveedores/index
❌ /mesas/index
❌ /informes
❌ /compras
❌ /usuarios
❌ Cualquier ruta de admin
```

---

## 9️⃣ Proteger Rutas en Código

### En el Controlador:

```php
public function index()
{
    // Verificar si es admin
    if (auth()->user()->tipo !== 'admin') {
        return redirect()->route('mesasventas.index')
                         ->with('error', 'No tienes permiso');
    }
    
    // Lógica del controlador...
}
```

### En la Vista - Mostrar/Ocultar Elementos:

```blade
<!-- Menú solo para admins -->
@if(auth()->user()->tipo === 'admin')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('usuarios') }}">
            <i class="fas fa-users"></i> Usuarios
        </a>
    </li>
@endif
```

---

## 🔟 Listar Todos los Usuarios y Sus Roles

### CLI:

```bash
php artisan tinker

# Ver todos los usuarios
App\Models\User::all(['id', 'name', 'email', 'tipo', 'estado']);

# Ver solo admins
App\Models\User::where('tipo', 'admin')->get(['id', 'name', 'email', 'estado']);

# Ver solo empleados
App\Models\User::where('tipo', 'empleado')->get(['id', 'name', 'email', 'estado']);

# Ver solo activos
App\Models\User::where('estado', 'activo')->get(['id', 'name', 'email', 'tipo']);

# Ver solo inactivos
App\Models\User::where('estado', 'inactivo')->get(['id', 'name', 'email', 'tipo']);
```

### SQL:

```sql
-- Todos los usuarios
SELECT id, name, email, tipo, estado FROM users;

-- Solo admins activos
SELECT id, name, email FROM users WHERE tipo = 'admin' AND estado = 'activo';

-- Solo empleados
SELECT id, name, email, estado FROM users WHERE tipo = 'empleado';

-- Usuarios inactivos
SELECT id, name, email, tipo FROM users WHERE estado = 'inactivo';
```

---

## 1️⃣1️⃣ Manejo de Sesión

### En el Controlador:

```php
public function verificarPermiso()
{
    $user = auth()->user();
    
    // Verificar si está autenticado
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    
    // Verificar si es admin
    if ($user->tipo !== 'admin') {
        abort(403, 'No tienes permiso');
    }
    
    // Verificar si está activo
    if ($user->estado === 'inactivo') {
        auth()->logout();
        return redirect()->route('login')
                         ->with('error', 'Tu cuenta está inactiva');
    }
    
    // Si todo está bien, continuar...
}
```

---

## 1️⃣2️⃣ Ejemplo Completo - Crear Panel Dinámico

### Vista (profile/show.blade.php):

```blade
@extends('adminlte::page')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Mi Información</h3>
                </div>
                <div class="card-body">
                    <p><strong>Nombre:</strong> {{ auth()->user()->name }}</p>
                    <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                    <p><strong>Rol:</strong> <span class="badge badge-info">{{ ucfirst(auth()->user()->tipo) }}</span></p>
                    <p><strong>Estado:</strong> 
                        @if(auth()->user()->estado === 'activo')
                            <span class="badge badge-success">Activo</span>
                        @else
                            <span class="badge badge-danger">Inactivo</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel especial para admins -->
    @if(auth()->user()->tipo === 'admin')
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-crown mr-2"></i> Panel Administrativo
                        </h3>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('users.index') }}" class="btn btn-primary">
                            Gestionar Usuarios
                        </a>
                        <a href="{{ route('productos.index') }}" class="btn btn-success">
                            Gestionar Productos
                        </a>
                        <a href="{{ route('informes.index') }}" class="btn btn-info">
                            Ver Reportes
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
```

---

## 🎯 Resumen de Mejores Prácticas

✅ Siempre validar el rol en el controlador  
✅ Usar middlewares para proteger rutas  
✅ Mostrar/ocultar elementos en vistas según el rol  
✅ Usar `auth()->user()->tipo` para verificaciones  
✅ Comprobar `estado === 'activo'` antes de operaciones  
✅ Proporcionar mensajes claros en errores 403  
✅ Loguear intentos de acceso no autorizado (opcional)  

---

**Última actualización:** 18 de Noviembre de 2025  
**Versión:** 1.0
