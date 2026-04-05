# ✅ CHECKLIST DE VERIFICACIÓN - ROLES Y PERMISOS

## 1️⃣ Verificación de Archivos

- [ ] `app/Http/Requests/Auth/LoginRequest.php` - Contiene validación de estado inactivo
- [ ] `app/Http/Middleware/CheckRole.php` - Mejorado con lógica de admin
- [ ] `app/Http/Middleware/CheckEmployeeAccess.php` - Nuevo archivo creado
- [ ] `bootstrap/app.php` - Contiene registro del middleware `employee`
- [ ] `routes/web.php` - Rutas de mesasventas con middleware `employee`
- [ ] `resources/views/errors/403.blade.php` - Mejorada la vista de error

## 2️⃣ Pruebas de Acceso - Administrador

**Cuenta:** admin@billar.com (tipo: admin, estado: activo)

- [ ] **Login exitoso:** Debe permitir login
- [ ] **Acceso a /welcome:** ✅ Permitido
- [ ] **Acceso a /productos/index:** ✅ Permitido
- [ ] **Acceso a /mesasventas:** ✅ Permitido
- [ ] **Acceso a /usuarios:** ✅ Permitido
- [ ] **Acceso a /compras:** ✅ Permitido
- [ ] **Acceso a /proveedores/index:** ✅ Permitido
- [ ] **Acceso a /informes:** ✅ Permitido

## 3️⃣ Pruebas de Acceso - Empleado Activo

**Cuenta:** empleado@billar.com (tipo: empleado, estado: activo)

- [ ] **Login exitoso:** Debe permitir login
- [ ] **Acceso a /mesasventas:** ✅ Permitido
- [ ] **Acceso a /mesasventas/create:** ✅ Permitido
- [ ] **Acceso a /mesasventas/historial:** ✅ Permitido
- [ ] **Acceso a /welcome:** ❌ Error 403
- [ ] **Acceso a /productos/index:** ❌ Error 403
- [ ] **Acceso a /usuarios:** ❌ Error 403
- [ ] **Acceso a /compras:** ❌ Error 403
- [ ] **Acceso a /proveedores/index:** ❌ Error 403

## 4️⃣ Pruebas de Estado Inactivo

**Cuenta:** inactivo@billar.com (tipo: empleado, estado: inactivo)

- [ ] **Login:** ❌ Debe denegar con mensaje "Tu cuenta está inactiva..."
- [ ] **No puede acceder a ninguna ruta:** ✅ Correcto

## 5️⃣ Verificación de Mensajes

- [ ] Mensaje de login rechazado por inactividad es claro
- [ ] Vista 403 muestra botón "Ir a Mesas Ventas" para empleados
- [ ] Vista 403 muestra información del usuario
- [ ] Vista 403 tiene botón "Volver al Inicio"

## 6️⃣ Comandos de Verificación

Ejecuta en terminal:

```bash
# Limpiar caché
php artisan cache:clear

# Verificar que los middlewares estén registrados
php artisan tinker
# Luego ejecuta: dd(app('middleware.aliases'))

# Ver rutas
php artisan route:list | grep mesasventas
```

## 7️⃣ Verificación de Base de Datos

Ejecuta esta consulta para verificar la estructura:

```sql
SELECT id, name, email, tipo, estado FROM users;
```

**Resultado esperado:**
```
id | name           | email                    | tipo      | estado
1  | Admin User     | admin@billar.com         | admin     | activo
2  | Empleado Test  | empleado@billar.com      | empleado  | activo
3  | Inactivo Test  | inactivo@billar.com      | empleado  | inactivo
```

---

## 🚨 Problemas Comunes y Soluciones

### Problema 1: Empleado puede acceder a todas las rutas
**Solución:** Verifica que el middleware `employee` esté registrado en `bootstrap/app.php`

### Problema 2: Error 403 se muestra en blanco
**Solución:** Ejecuta `php artisan cache:clear` y verifica que la vista existe

### Problema 3: Login no rechaza usuarios inactivos
**Solución:** Verifica el campo `estado` en la tabla users (debe ser 'inactivo')

### Problema 4: El middleware `employee` no se aplica
**Solución:** 
1. Verifica `routes/web.php` line 53 - debe tener `'employee'` en middlewares
2. Ejecuta `php artisan route:cache`

---

## ✅ Checklist Final

- [ ] Todos los archivos están creados/modificados
- [ ] Los middlewares están registrados
- [ ] Las rutas están configuradas correctamente
- [ ] Las pruebas de acceso funcionan
- [ ] Los mensajes de error son claros
- [ ] La vista 403 se muestra correctamente
- [ ] Los usuarios inactivos no pueden loguear
- [ ] El proyecto está listo para producción

---

**Última actualización:** 18 de Noviembre de 2025  
**Estado:** ✅ LISTO PARA PROBAR
