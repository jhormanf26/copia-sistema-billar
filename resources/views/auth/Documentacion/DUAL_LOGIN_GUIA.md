# 🔐 Guía de Implementación: Login Dual (Email o Número de Documento)

## 📋 Descripción General

El sistema de login ha sido actualizado para permitir que los usuarios se autentiquen usando **email O número de documento**. Esta flexibilidad mejora la experiencia del usuario y proporciona múltiples opciones de acceso.

---

## 🎯 Cambios Implementados

### 1. **Vista de Login Actualizada** (`resources/views/auth/login.blade.php`)

#### Cambio Principal:
- ✅ Agregado **selector de método de login** con dos opciones:
  - **📧 Correo**: Autenticación por email
  - **🆔 Documento**: Autenticación por número de documento

#### Características Visuales:
```html
<!-- Selector de tabs interactivos -->
<div class="mb-6">
    <label class="block text-sm font-medium text-gray-300 mb-3">
        Método de ingreso
    </label>
    <div class="flex gap-2">
        <button type="button" id="tab-email" class="login-method-tab ...">
            📧 Correo
        </button>
        <button type="button" id="tab-document" class="login-method-tab ...">
            🆔 Documento
        </button>
    </div>
</div>
```

#### Comportamiento JavaScript:
- El usuario puede cambiar entre tabs haciendo click
- Al cambiar de tab:
  - El campo activo se muestra
  - El campo inactivo se oculta
  - El input de document se deshabilita cuando está en modo email (y viceversa)
  - El foco se mueve al campo visible

---

### 2. **LoginRequest Actualizado** (`app/Http/Requests/Auth/LoginRequest.php`)

#### A) Validación de Reglas:
```php
public function rules(): array
{
    return [
        'email' => ['nullable', 'string', 'email'],
        'numerodocumento' => ['nullable', 'string'],
        'password' => ['required', 'string'],
    ];
}
```

**Cambios:**
- `email`: Cambió de `required` a `nullable` (ahora es opcional)
- `numerodocumento`: Nuevo campo `nullable` y `string`
- Se valida que AL MENOS uno de los dos esté presente en `prepareForValidation()`

#### B) Validación Previa (`prepareForValidation`):
```php
protected function prepareForValidation(): void
{
    // Validar que al menos uno de los dos campos esté presente
    if (!$this->input('email') && !$this->input('numerodocumento')) {
        throw ValidationException::withMessages([
            'email' => 'Debes ingresar un correo o número de documento.',
        ]);
    }
}
```

**Garantiza:** El usuario debe proporcionar al menos uno de los dos identificadores.

#### C) Autenticación Dual (`authenticate`):
```php
public function authenticate(): void
{
    $this->ensureIsNotRateLimited();

    // Determinar el método de autenticación
    $credentials = ['password' => $this->input('password')];
    
    if ($this->input('email')) {
        $credentials['email'] = $this->input('email');
        $loginField = 'email';
    } else {
        $credentials['numerodocumento'] = $this->input('numerodocumento');
        $loginField = 'numerodocumento';
    }

    if (! Auth::attempt($credentials, $this->boolean('remember'))) {
        RateLimiter::hit($this->throttleKey());

        throw ValidationException::withMessages([
            $loginField => trans('auth.failed'),
        ]);
    }

    // Verificar si el usuario está activo
    $user = Auth::user();
    if ($user && $user->estado === 'inactivo') {
        Auth::logout();
        RateLimiter::hit($this->throttleKey());

        throw ValidationException::withMessages([
            $loginField => 'Tu cuenta está inactiva. Contacta al administrador para activarla.',
        ]);
    }

    RateLimiter::clear($this->throttleKey());
}
```

**Lógica:**
1. Detecta cuál campo está presente (email o numerodocumento)
2. Intenta autenticar con el método seleccionado
3. Si falla, muestra error en el campo correspondiente
4. Si tiene éxito, valida que el usuario esté **activo**
5. Si está inactivo, lo desconecta y muestra error descriptivo

#### D) Throttle Key Mejorado:
```php
public function throttleKey(): string
{
    $identifier = $this->input('email') ?? $this->input('numerodocumento');
    return Str::transliterate(Str::lower($identifier).'|'.$this->ip());
}
```

**Beneficio:** El rate limiting funciona correctamente con ambos métodos de login.

---

## 🔄 Flujo de Autenticación

### Escenario 1: Login por Email ✉️
```
1. Usuario ingresa: usuario@correo.com | contraseña
2. Click en "Ingresar"
3. Laravel valida que email sea un correo válido
4. Auth::attempt(['email' => 'usuario@correo.com', 'password' => '...'])
5. Usuario encontrado → Validar estado activo
6. Estado activo → Login exitoso
```

### Escenario 2: Login por Número de Documento 🆔
```
1. Usuario selecciona tab "Documento"
2. Ingresa: 1234567890 | contraseña
3. Click en "Ingresar"
4. Laravel valida que documento sea string
5. Auth::attempt(['numerodocumento' => '1234567890', 'password' => '...'])
6. Usuario encontrado → Validar estado activo
7. Estado activo → Login exitoso
```

### Escenario 3: Ambos Campos Vacíos ❌
```
1. Usuario no ingresa nada o solo contraseña
2. prepareForValidation() lo detecta
3. Error: "Debes ingresar un correo o número de documento."
```

### Escenario 4: Usuario No Encontrado ❌
```
1. Email/documento ingresados no existen en BD
2. Auth::attempt() falla
3. Error: "These credentials do not match our records."
```

### Escenario 5: Usuario Inactivo ❌
```
1. Email/documento existe y contraseña es correcta
2. Auth::attempt() exitoso
3. Validación de estado detecta: estado === 'inactivo'
4. Usuario se desconecta inmediatamente
5. Error: "Tu cuenta está inactiva. Contacta al administrador para activarla."
```

---

## 📊 Estructura de Base de Datos

El sistema asume estos campos en la tabla `users`:

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id` | INT | Identificador único |
| `email` | VARCHAR(255) | Correo electrónico único |
| `numerodocumento` | VARCHAR(50) | Número de documento único |
| `password` | VARCHAR(255) | Contraseña hasheada |
| `estado` | ENUM('activo','inactivo') | Estado de la cuenta |
| `tipo` | ENUM('admin','empleado','gerente') | Rol del usuario |
| `remember_token` | VARCHAR(100) | Token para "Recordar sesión" |

---

## 🛠️ Uso en Producción

### Para Usuarios (Frontend):

1. **Primera vez:**
   - Ir a `/login`
   - Elegir método: Email o Documento
   - Ingresar credenciales
   - Click en "Ingresar"

2. **Cambiar método:**
   - Hacer click en el otro tab (Email ↔ Documento)
   - El formulario se actualiza automáticamente

3. **Errores comunes:**
   - "Debes ingresar un correo o número de documento" → Llenar al menos un campo
   - "These credentials do not match our records." → Verificar email/documento y contraseña
   - "Tu cuenta está inactiva" → Contactar administrador

### Para Administradores:

1. **Habilitar/Deshabilitar usuarios:**
   ```sql
   UPDATE users SET estado = 'activo' WHERE id = 1;
   UPDATE users SET estado = 'inactivo' WHERE id = 2;
   ```

2. **Verificar intentos de login fallidos:**
   - Laravel guarda en `cache` los intentos de rate-limiting
   - Máximo 5 intentos antes de bloquear por minuto

3. **Resguardo de seguridad:**
   - Todos los intentos se registran en logs
   - Las contraseñas se hashean con bcrypt
   - CSRF protection activada

---

## 🔐 Medidas de Seguridad

| Medida | Detalle |
|--------|--------|
| **Rate Limiting** | Máximo 5 intentos fallidos por minuto |
| **Hash de Contraseña** | Bcrypt con sal automática |
| **CSRF Token** | Protección contra ataques CSRF |
| **Validación Email** | Verificación RFC 5322 |
| **Estado Activo** | Validación adicional después de autenticación |
| **Throttle Key** | Único por IP + email/documento |
| **Remember Me** | Token seguro en base de datos |

---

## 📝 Ejemplos de Uso

### Ejemplo 1: Usuario con Email
```
Usuario: test@example.com
Documento: 1234567890
Método elegido: Email
Resultado: ✅ Login exitoso
```

### Ejemplo 2: Usuario con Documento
```
Usuario: test@example.com
Documento: 1234567890
Método elegido: Documento
Resultado: ✅ Login exitoso
```

### Ejemplo 3: Cambiar Método
```
1. Usuario en formulario email vacío
2. Click en tab "Documento"
3. Ingresa documento "1234567890"
4. Click "Ingresar"
5. Resultado: ✅ Login exitoso con documento
```

---

## 🐛 Debugging

### Para verificar si el sistema funciona:

1. **Prueba con email:**
   ```bash
   # En terminal del servidor
   php artisan tinker
   >>> $user = User::first();
   >>> $user->email
   // Debería mostrar un email
   ```

2. **Prueba con documento:**
   ```bash
   >>> $user->numerodocumento
   // Debería mostrar un número de documento
   ```

3. **Revisar logs:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

---

## 📋 Checklist de Verificación

- [ ] Vista de login muestra selector de método
- [ ] Tab "Email" está activo por defecto
- [ ] Tab "Documento" oculta el campo de email
- [ ] Cambiar entre tabs funciona suavemente
- [ ] Login con email funciona
- [ ] Login con documento funciona
- [ ] Error de campo vacío funciona
- [ ] Rate limiting se aplica a ambos métodos
- [ ] Usuario inactivo no puede ingresar
- [ ] Contraseña incorrecta rechaza al usuario
- [ ] Checkbox "Recordar" funciona

---

## 🔗 Archivos Modificados

1. **`resources/views/auth/login.blade.php`**
   - ✅ Agregado selector de método
   - ✅ Agregado JavaScript para manejo de tabs
   - ✅ Campo de número de documento

2. **`app/Http/Requests/Auth/LoginRequest.php`**
   - ✅ Validación dual (email OR documento)
   - ✅ Autenticación flexible
   - ✅ Throttle key mejorado

---

## 📞 Soporte Técnico

Si el login dual no funciona:

1. **Verificar que `numerodocumento` existe en tabla `users`**
   ```bash
   php artisan migrate
   ```

2. **Limpiar caché de Laravel**
   ```bash
   php artisan cache:clear
   php artisan config:clear
   ```

3. **Verificar que User model use numerodocumento**
   ```php
   // app/Models/User.php
   protected $fillable = [..., 'numerodocumento', ...];
   ```

---

## ✅ Estado de Implementación

| Componente | Estado | Notas |
|-----------|--------|-------|
| UI Selector | ✅ Completo | Tabs interactivos con Tailwind CSS |
| Validación | ✅ Completo | Ambos campos soportados |
| Autenticación | ✅ Completo | Auth::attempt dual |
| Rate Limiting | ✅ Completo | Funciona con ambos métodos |
| Verificación de Estado | ✅ Completo | Inactivos bloqueados |
| Seguridad | ✅ Completo | CSRF, Hash, Rate Limit |

---

## 🚀 Próximas Mejoras (Opcionales)

- [ ] Agregar opción de "2FA" (Two Factor Authentication)
- [ ] Agregar "Login con Google/GitHub"
- [ ] Historial de intentos de login
- [ ] SMS de verificación para primer login
- [ ] Biometría (fingerprint/face en móvil)

---

**Última actualización:** 2025  
**Versión:** 1.0  
**Framework:** Laravel 12.0  
**UI:** Tailwind CSS + AdminLTE
