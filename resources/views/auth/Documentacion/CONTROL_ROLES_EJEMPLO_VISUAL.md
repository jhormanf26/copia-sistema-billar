# 📊 CONTROL DE ROLES - EJEMPLO VISUAL Y PRÁCTICO

---

## 🎯 Escenario: Crear Diferentes Tipos de Usuarios

```
BILLAR NEXUS
│
├─ 👤 ADMINISTRADOR (Juan)
│  ├─ Email: juan@billar.com
│  ├─ Tipo: admin
│  ├─ Acceso: TOTAL
│  └─ Puede: Crear, editar, eliminar usuarios
│
├─ 👷 EMPLEADO (Carlos)
│  ├─ Email: carlos@billar.com
│  ├─ Tipo: empleado
│  ├─ Acceso: Solo Mesas Ventas
│  └─ No puede: Ver usuarios, productos, reportes
│
└─ 📊 GERENTE (María) - Opcional
   ├─ Email: maria@billar.com
   ├─ Tipo: gerente
   ├─ Acceso: Mesas Ventas + Reportes
   └─ No puede: Gestión de usuarios
```

---

## 🔄 FLUJO 1: Admin Crea Usuario

```
┌─────────────────────────────────────┐
│ ADMIN INGRESA A /usuarios/crear     │
└──────────────┬──────────────────────┘
               │
               ▼
┌──────────────────────────────────────────────────┐
│ FORMULARIO - Crear Nuevo Usuario                │
├──────────────────────────────────────────────────┤
│ Nombre:        [Carlos]                         │
│ Apellidos:     [López]                          │
│ Email:         [carlos@billar.com]              │
│ Documento:     [0987654321]                     │
│ Tipo Doc:      [CC] ▼                           │
│ Tipo Usuario:  [empleado] ▼                     │
│                ├─ Administrador                 │
│                ├─ Empleado         ✓ SELECTED  │
│                └─ Gerente                       │
│ Contraseña:    [**]                            │
│ Confirmar:     [**]                            │
│                                                 │
│ [Cancelar] [Crear Usuario]                     │
└──────────────┬──────────────────────────────────┘
               │
               ▼
┌──────────────────────────────────────────────────┐
│ VALIDACIÓN EN SERVIDOR                          │
├──────────────────────────────────────────────────┤
│ ✓ Nombre válido                                 │
│ ✓ Email único                                   │
│ ✓ Documento único                               │
│ ✓ Tipo en lista: admin, empleado, gerente      │
│ ✓ Contraseña mín. 8 caracteres                │
└──────────────┬──────────────────────────────────┘
               │ ✓ PASS
               ▼
┌──────────────────────────────────────────────────┐
│ GUARDAR EN BASE DE DATOS                        │
├──────────────────────────────────────────────────┤
│ INSERT INTO users (                             │
│   name: 'Carlos',                               │
│   apellidos: 'López',                           │
│   email: 'carlos@billar.com',                   │
│   tipo: 'empleado',          ← TIPO GUARDADO   │
│   estado: 'activo',                             │
│   password: bcrypt(...)                         │
│ )                                               │
└──────────────┬──────────────────────────────────┘
               │
               ▼
┌──────────────────────────────────────────────────┐
│ ✅ USUARIO CREADO EXITOSAMENTE                  │
├──────────────────────────────────────────────────┤
│ "Usuario Carlos López creado correctamente"     │
│ Tipo: Empleado                                  │
│ Email: carlos@billar.com                        │
│                                                 │
│ [Ver Usuarios] [Crear Otro]                    │
└──────────────────────────────────────────────────┘
```

---

## 🔐 FLUJO 2: Empleado Intenta Login

```
┌──────────────────────────────┐
│ EMPLEADO (Carlos) en /login  │
└──────────┬───────────────────┘
           │
           ▼
┌──────────────────────────────┐
│ Email:    carlos@billar.com  │
│ Password: [**]               │
│ [Ingresar]                   │
└──────────┬───────────────────┘
           │
           ▼
┌────────────────────────────────────────┐
│ VALIDACIÓN LoginRequest                │
├────────────────────────────────────────┤
│ 1. ¿Email existe? ✓ SÍ               │
│ 2. ¿Contraseña válida? ✓ SÍ          │
│ 3. ¿Estado = 'activo'? ✓ SÍ          │
└────────────┬─────────────────────────┘
             │ ✓ PASS
             ▼
┌────────────────────────────────────────┐
│ ACCESO OTORGADO                        │
├────────────────────────────────────────┤
│ Usuario: Carlos López (empleado)       │
│ Tipo: empleado                         │
│ Estado: activo                         │
└────────────┬─────────────────────────┘
             │
             ▼
┌────────────────────────────────────────┐
│ REDIRIGIR A: /mesasventas              │
├────────────────────────────────────────┤
│ ✅ Acceso permitido: empleado          │
│                                        │
│ Bienvenido Carlos López                │
│ [Mesas Ventas] [Perfil] [Logout]      │
└────────────────────────────────────────┘
```

---

## 🚫 FLUJO 3: Empleado Intenta Acceder a Admin

```
┌──────────────────────────────────────┐
│ EMPLEADO Intenta /usuarios           │
└──────────┬───────────────────────────┘
           │
           ▼
┌──────────────────────────────────────┐
│ Middleware: CheckEmployeeAccess      │
├──────────────────────────────────────┤
│ ¿Es admin? ✓ NO                      │
│ ¿Es empleado? ✓ SÍ                  │
│ ¿Ruta /usuarios en lista permitida?  │
│ ✓ NO                                 │
│                                      │
│ Rutas permitidas para empleado:      │
│ - /mesasventas                       │
│ - /mesasventas/create                │
│ - /mesasventas/{id}                 │
│ - /perfil                            │
└──────────┬──────────────────────────┘
           │ ❌ ACCESO DENEGADO
           ▼
┌──────────────────────────────────────┐
│ ERROR 403 - ACCESO DENEGADO          │
├──────────────────────────────────────┤
│ 🔒 No tienes permiso para acceder    │
│                                      │
│ Tu información:                      │
│ • Nombre: Carlos López               │
│ • Rol: Empleado                      │
│ • Como empleado, solo puedes         │
│   acceder a Mesas Ventas             │
│                                      │
│ [Ir a Mesas Ventas] [Volver] [Inicio]│
└──────────────────────────────────────┘
```

---

## 👨‍💼 FLUJO 4: Cambiar Rol de Usuario

```
┌─────────────────────────────────────────┐
│ ADMIN EN /usuarios/{id}/editar          │
│ (Editando a Carlos)                     │
└──────────┬────────────────────────────┘
           │
           ▼
┌──────────────────────────────────────────┐
│ FORMULARIO - Editar Usuario              │
├──────────────────────────────────────────┤
│ Nombre:       [Carlos]                  │
│ Apellidos:    [López]                   │
│ Email:        [carlos@billar.com]       │
│ Tipo Usuario: [empleado] ▼              │
│              ├─ Administrador           │
│              ├─ Empleado   ✓ SELECTED  │
│              └─ Gerente                 │
│              🖱️ Cambiar a "Gerente"     │
│                              ▼          │
│              ├─ Administrador           │
│              ├─ Empleado                │
│              ├─ Gerente     ← NUEVO    │
│              └─                         │
│                                         │
│ [Cancelar] [Guardar Cambios]           │
└──────────┬────────────────────────────┘
           │
           ▼
┌──────────────────────────────────────────┐
│ ACTUALIZAR EN BD                         │
├──────────────────────────────────────────┤
│ UPDATE users SET                         │
│   tipo = 'gerente'                       │
│ WHERE id = 2                             │
│                                          │
│ ✓ Registro actualizado                  │
└──────────┬────────────────────────────┘
           │
           ▼
┌──────────────────────────────────────────┐
│ ✅ CAMBIO EXITOSO                        │
├──────────────────────────────────────────┤
│ "Carlos López ahora es Gerente"          │
│ • Antes: Empleado                        │
│ • Ahora: Gerente                         │
│ • Acceso: Mesas + Reportes              │
│                                          │
│ Próximo login tendrá permisos nuevos    │
└──────────────────────────────────────────┘
```

---

## 📋 MATRIZ DE ACCESO

```
URL                          ADMIN    EMPLEADO    GERENTE    INACTIVO
────────────────────────────────────────────────────────────────────
/welcome                     ✅       ❌          ❌         ❌
/perfil                      ✅       ✅          ✅         ❌
/mesasventas                 ✅       ✅          ✅         ❌
/mesasventas/create          ✅       ✅          ✅         ❌
/usuarios                    ✅       ❌          ❌         ❌
/usuarios/crear              ✅       ❌          ❌         ❌
/usuarios/{id}/editar        ✅       ❌          ❌         ❌
/productos/index             ✅       ❌          ❌         ❌
/productos/create            ✅       ❌          ❌         ❌
/inventario/index            ✅       ❌          ❌         ❌
/compras                     ✅       ❌          ❌         ❌
/proveedores/index           ✅       ❌          ❌         ❌
/mesas/index                 ✅       ❌          ❌         ❌
/informes                    ✅       ❌          ✅         ❌
/api/informes/*              ✅       ❌          ✅         ❌
```

---

## 🛠️ COMANDOS CLI PARA GESTIONAR ROLES

### Ver Todos los Usuarios

```bash
php artisan tinker
App\Models\User::all(['id', 'name', 'email', 'tipo', 'estado']);

# Resultado:
# id | name      | email               | tipo      | estado
# 1  | Juan      | juan@billar.com     | admin     | activo
# 2  | Carlos    | carlos@billar.com   | empleado  | activo
# 3  | María     | maria@billar.com    | gerente   | activo
```

### Crear Admin Rápidamente

```bash
php artisan tinker

$user = App\Models\User::create([
    'name' => 'Pedro',
    'apellidos' => 'Rodríguez',
    'email' => 'pedro@billar.com',
    'password' => bcrypt('admin123'),
    'tipo' => 'admin',
    'estado' => 'activo',
    'tipodocumento' => 'CC',
    'numerodocumento' => '5555555555',
]);

echo "Admin creado: " . $user->id;
```

### Ver Solo Admins

```bash
php artisan tinker
App\Models\User::where('tipo', 'admin')->get(['id', 'name', 'email']);
```

### Ver Solo Empleados

```bash
php artisan tinker
App\Models\User::where('tipo', 'empleado')->get(['id', 'name', 'email', 'estado']);
```

### Cambiar Usuario a Admin

```bash
php artisan tinker
$user = App\Models\User::find(2);
$user->update(['tipo' => 'admin']);
echo "Usuario ahora es admin";
```

### Desactivar Usuario

```bash
php artisan tinker
$user = App\Models\User::find(2);
$user->update(['estado' => 'inactivo']);
echo "Usuario desactivado";
```

---

## 🎯 RESUMEN: Cómo Controlar Roles

```
┌─────────────────────────────────────────────────────────────┐
│ FORMAS DE CONTROLAR ROLES EN REGISTRO                       │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│ 1. POR FORMULARIO ADMIN                                    │
│    └─ Ruta: /usuarios/crear                               │
│    └─ Quien: Solo admin puede crear                       │
│    └─ Resultado: Elige tipo directamente                  │
│    └─ Recomendado: ✅ SÍ                                   │
│                                                             │
│ 2. POR AUTOREGISTRO (Empleado)                            │
│    └─ Ruta: /register                                     │
│    └─ Quien: Cualquier persona                            │
│    └─ Resultado: Siempre empleado, inactivo               │
│    └─ Admin debe activar: ✅ SÍ                            │
│                                                             │
│ 3. CAMBIAR ROL DESPUÉS                                    │
│    └─ Ruta: /usuarios/{id}/editar                        │
│    └─ Quien: Solo admin puede cambiar                     │
│    └─ Resultado: Inmediato al guardar                     │
│    └─ Recomendado: ✅ SÍ                                   │
│                                                             │
│ 4. POR CLI (Terminal)                                     │
│    └─ Comando: php artisan tinker                         │
│    └─ Quien: Solo en desarrollo                           │
│    └─ Resultado: Directo en BD                            │
│    └─ Para producción: ❌ NO                               │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

---

## ✨ MEJORA: Mostrar Información de Rol en Formulario

```blade
<!-- En users/create.blade.php -->
<!-- Antes del campo tipo -->

<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-info mb-3">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle mr-2"></i>
                    Información de Roles
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <h5>
                            <i class="fas fa-crown text-danger mr-2"></i>
                            Administrador
                        </h5>
                        <small class="text-muted">
                            Acceso total a todas las funciones,
                            gestión de usuarios, reportes y más.
                        </small>
                    </div>
                    <div class="col-md-4">
                        <h5>
                            <i class="fas fa-user-tie text-info mr-2"></i>
                            Empleado
                        </h5>
                        <small class="text-muted">
                            Solo acceso a la sección de
                            Mesas Ventas.
                        </small>
                    </div>
                    <div class="col-md-4">
                        <h5>
                            <i class="fas fa-chart-line text-success mr-2"></i>
                            Gerente
                        </h5>
                        <small class="text-muted">
                            Acceso a Mesas Ventas y
                            Reportes de negocio.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
```

---

## 🎓 Conclusión

**Para controlar roles en el registro:**

1. ✅ **Admin crea usuarios** con rol específico
2. ✅ **Validar en servidor** el tipo de usuario
3. ✅ **Mostrar selección de rol** en formulario
4. ✅ **Proteger rutas** con middleware
5. ✅ **Permitir cambios** solo para admin
6. ✅ **Documentar** qué puede hacer cada rol

**¡Tu sistema de control de roles está completamente implementado! 🎉**

---

**Próximos pasos:**
- [ ] Crear admin para pruebas
- [ ] Crear empleado para pruebas
- [ ] Probar permisos en cada ruta
- [ ] Verificar vista 403
- [ ] Cambiar rol de un usuario
- [ ] Desactivar un usuario inactivo
