# 🧪 Pruebas Rápidas: Dual Login

## Instrucciones de Prueba

### Test 1: Login por Email ✅
**Pasos:**
1. Abrir `/login`
2. Verificar que tab "Email" está activo (naranja)
3. Ingresar: `admin@example.com` | contraseña
4. Click "Ingresar"

**Resultado Esperado:** ✅ Redirecciona a dashboard

---

### Test 2: Login por Documento ✅
**Pasos:**
1. Abrir `/login`
2. Click en tab "🆔 Documento"
3. Verificar que campo email se oculta
4. Ingresar número de documento del usuario | contraseña
5. Click "Ingresar"

**Resultado Esperado:** ✅ Redirecciona a dashboard

---

### Test 3: Cambiar Método (Email → Documento) ✅
**Pasos:**
1. Abrir `/login`
2. Escribir email en campo email
3. Click en tab "Documento"
4. Verificar que campo email desaparece
5. Verificar que campo documento aparece y está enfocado

**Resultado Esperado:** ✅ Transición suave entre campos

---

### Test 4: Cambiar Método (Documento → Email) ✅
**Pasos:**
1. Abierto en tab "Documento"
2. Escribir número en campo documento
3. Click en tab "Email"
4. Verificar que campo documento desaparece
5. Verificar que campo email aparece y está enfocado

**Resultado Esperado:** ✅ Transición suave entre campos

---

### Test 5: Sin Ingresar Credenciales ❌
**Pasos:**
1. Abrir `/login`
2. Dejar ambos campos vacíos
3. Ingresar solo contraseña
4. Click "Ingresar"

**Resultado Esperado:** ❌ Error: "Debes ingresar un correo o número de documento."

---

### Test 6: Email Inválido ❌
**Pasos:**
1. En tab "Email"
2. Ingresar: `esto-no-es-email` | contraseña
3. Click "Ingresar"

**Resultado Esperado:** ❌ Error de validación: "El correo debe ser un email válido."

---

### Test 7: Credenciales Incorrectas ❌
**Pasos:**
1. Tab "Email"
2. Ingresar: `admin@example.com` | `contraseña-incorrecta`
3. Click "Ingresar"

**Resultado Esperado:** ❌ Error: "These credentials do not match our records."

---

### Test 8: Usuario Inactivo ❌
**Pasos:**
1. Primero, desactivar usuario en BD:
   ```bash
   php artisan tinker
   >>> User::find(2)->update(['estado' => 'inactivo']);
   ```
2. Intentar login con ese usuario
3. Usar email O documento

**Resultado Esperado:** ❌ Error: "Tu cuenta está inactiva. Contacta al administrador para activarla."

---

### Test 9: Rate Limiting ⏱️
**Pasos:**
1. Intentar login 5+ veces con credenciales incorrectas (mismo email/IP)
2. 6to intento

**Resultado Esperado:** ⏱️ Error: "Too many login attempts. Please try again in X seconds."

---

### Test 10: Recordar Sesión ✅
**Pasos:**
1. Tab "Email"
2. Marcar checkbox "Recordar"
3. Ingresar email válido | contraseña
4. Click "Ingresar"
5. Cerrar navegador
6. Reabrirlo

**Resultado Esperado:** ✅ Usuario sigue logeado (remember_token en cookie)

---

### Test 11: Tab Styling ✨
**Pasos:**
1. Abrir `/login`
2. Observar tab "Email": naranja (activo)
3. Observar tab "Documento": gris (inactivo)
4. Click en "Documento"
5. Verificar colores intercambiados

**Resultado Esperado:** ✨ Estilos Tailwind aplicados correctamente

---

### Test 12: Campos Deshabilitados 🔒
**Pasos:**
1. Abrir DevTools (F12)
2. Ir a Inspector → Elements
3. En tab "Email": verificar `<input ... >` (sin disabled)
4. Ir a tab "Documento"
5. Verificar campo anterior tiene `disabled="disabled"`

**Resultado Esperado:** 🔒 Campos inactivos tienen atributo `disabled`

---

## Tabla de Pruebas Rápidas

| Test # | Escenario | Entrada | Esperado | Estado |
|--------|-----------|---------|----------|--------|
| 1 | Email válido | admin@ex.com | ✅ Login OK | ⏳ |
| 2 | Documento válido | 12345678 | ✅ Login OK | ⏳ |
| 3 | Tab change | Click tab | ✅ Smooth | ⏳ |
| 4 | Tab reverse | Click tab | ✅ Smooth | ⏳ |
| 5 | Ambos vacíos | [vacío] | ❌ Error msg | ⏳ |
| 6 | Email inválido | abc | ❌ Format error | ⏳ |
| 7 | Creds incorrectas | wrong | ❌ Auth failed | ⏳ |
| 8 | User inactivo | valid | ❌ Inactive | ⏳ |
| 9 | Rate limit | 6 intentos | ⏱️ Throttled | ⏳ |
| 10 | Remember me | Check + login | ✅ Persists | ⏳ |
| 11 | Tab colors | Visual | ✨ Correct | ⏳ |
| 12 | Disabled attr | DevTools | 🔒 Present | ⏳ |

---

## Verificación de Código

### Verificar archivos modificados:

```bash
# 1. Comprobar que login.blade.php tiene selector
grep -n "login-method-tab" resources/views/auth/login.blade.php

# 2. Comprobar que LoginRequest valida ambos
grep -n "numerodocumento" app/Http/Requests/Auth/LoginRequest.php

# 3. Comprobar que authenticate() es dual
grep -n "credentials\['email'\]" app/Http/Requests/Auth/LoginRequest.php

# 4. Verificar User model tiene numerodocumento
grep -n "numerodocumento" app/Models/User.php
```

---

## Notas de Debugging

Si algo no funciona:

1. **Verificar logs**
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Verificar cache**
   ```bash
   php artisan cache:clear
   php artisan config:clear
   ```

3. **Verificar BD**
   ```bash
   php artisan tinker
   >>> User::first()->only(['email', 'numerodocumento', 'estado'])
   ```

4. **Verificar rutas**
   ```bash
   php artisan route:list | grep login
   ```

---

**Inicio de pruebas:** [Tu fecha]  
**Responsable:** [Tu nombre]  
**Resultado final:** ⏳ Pendiente
