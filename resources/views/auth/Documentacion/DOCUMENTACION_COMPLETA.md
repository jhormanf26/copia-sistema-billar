
#  DOCUMENTACIÓN COMPLETA - PROYECTO BILLAR NEXUS

**Última actualización:** 17 de Noviembre de 2025  
**Versión del Proyecto:** 1.0.0  
**Framework:** Laravel 12.0  
**UI Framework:** AdminLTE 3.15  
**Base de Datos:** MySQL/PostgreSQL  

---

##  Tabla de Contenidos

1. [Descripción General](#descripción-general)
2. [Requisitos del Sistema](#requisitos-del-sistema)
3. [Instalación y Configuración](#instalación-y-configuración)
4. [Estructura del Proyecto](#estructura-del-proyecto)
5. [Componentes Principales](#componentes-principales)
6. [Módulos Implementados](#módulos-implementados)
7. [Rutas de la Aplicación](#rutas-de-la-aplicación)
8. [Modelos de Base de Datos](#modelos-de-base-de-datos)
9. [Seguridad](#seguridad)
10. [Guía de Desarrollo](#guía-de-desarrollo)
11. [Troubleshooting](#troubleshooting)

---

##  Descripción General

**BILLAR NEXUS** es una aplicación web desarrollada con Laravel para la gestión integral de un negocio de billar. El sistema permite:

- Gestión de usuarios y roles
- Control de mesas de billar
- Registro de ventas y compras
- Gestión de inventario de productos
- Administración de proveedores y patrocinadores
- Reportes e informes
- Gestión de perfiles de usuario

### Características Principales:
- ✅ Autenticación y autorización basada en roles
- ✅ Panel de control administrativo (AdminLTE)
- ✅ Interfaz responsive y moderna
- ✅ Validación de datos robusta
- ✅ Sistema de logs y auditoría
- ✅ Seguridad CSRF en todos los formularios

---

##  Requisitos del Sistema

### Obligatorios:
- **PHP:** 8.2 o superior
- **Composer:** 2.0 o superior
- **Node.js:** 16.0 o superior
- **npm:** 8.0 o superior
- **Base de Datos:** MySQL 8.0+ o PostgreSQL 12+

### Recomendados:
- Git para control de versiones
- Postman para pruebas de API
- DBeaver o similar para gestión de BD

---

##  Instalación y Configuración

### 1. Clonar el Repositorio
```bash
git clone https://github.com/nyllereth421/PROYECTO-BILLAR.git
cd PROYECTO-BILLAR
```

### 2. Instalar Dependencias PHP
```bash
composer install
```

### 3. Instalar Dependencias Frontend
```bash
npm install
```

### 4. Configurar Archivo .env
```bash
cp .env.example .env
php artisan key:generate
```

Edita `.env` con tus datos de conexión:
```env
APP_NAME="Billar Nexus"
APP_ENV=local
APP_KEY=base64:xxxxxxxxxxxxx
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=billar_nexus
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Crear Base de Datos
```bash
php artisan migrate
php artisan db:seed
```

### 6. Generar Enlace Simbólico de Storage
```bash
php artisan storage:link
```

### 7. Iniciar el Servidor de Desarrollo
```bash
# Terminal 1: Servidor Laravel
php artisan serve

# Terminal 2: Compilación de assets
npm run dev
```

La aplicación estará disponible en: `http://localhost:8000`

---

##  Estructura del Proyecto

```
PROYECTO-BILLAR/
├── app/
│   ├── Helpers/
│   │   └── RoleHelper.php              # Funciones auxiliares de roles
│   ├── Http/
│   │   ├── Controllers/                # Controladores de la aplicación
│   │   │   ├── Admin/
│   │   │   ├── Auth/
│   │   │   ├── ComprasController.php
│   │   │   ├── MesasController.php
│   │   │   ├── ProductosController.php
│   │   │   ├── ProfileController.php
│   │   │   ├── ProveedoresController.php
│   │   │   └── UsersController.php
│   │   ├── Middleware/                 # Middleware personalizado
│   │   └── Requests/                   # Form Requests para validación
│   ├── Models/
│   │   ├── User.php                    # Modelo de usuario
│   │   ├── Compra.php                  # Modelo de compras
│   │   ├── CompraDetalle.php           # Detalles de compras
│   │   ├── Mesas.php                   # Mesas de billar
│   │   ├── MesasVentas.php             # Ventas por mesa
│   │   ├── Productos.php               # Catálogo de productos
│   │   ├── ProductosVentas.php         # Ventas de productos
│   │   ├── Proveedores.php             # Gestión de proveedores
│   │   ├── Patrocinadores.php          # Patrocinadores
│   │   └── ProductosProveedor.php      # Relación productos-proveedores
│   ├── Providers/
│   │   └── AppServiceProvider.php
│   └── View/
│       └── Components/                 # Componentes Blade reutilizables
├── bootstrap/
│   ├── app.php
│   ├── providers.php
│   └── cache/
├── config/
│   ├── app.php                         # Configuración de aplicación
│   ├── adminlte.php                    # Configuración de AdminLTE
│   ├── auth.php                        # Configuración de autenticación
│   ├── database.php                    # Configuración de BD
│   └── ...
├── database/
│   ├── migrations/                     # Archivos de migración
│   ├── seeders/                        # Pobladores de datos
│   └── factories/
├── resources/
│   ├── css/                            # Estilos CSS
│   ├── js/                             # Código JavaScript
│   └── views/                          # Plantillas Blade
│       ├── layouts/
│       ├── auth/
│       ├── profile/                    # Vistas de perfil de usuario
│       └── ...
├── routes/
│   ├── web.php                         # Rutas web
│   ├── auth.php                        # Rutas de autenticación
│   └── console.php
├── storage/
│   ├── app/
│   ├── framework/
│   └── logs/
├── tests/
│   ├── Feature/
│   └── Unit/
├── public/
│   ├── index.php                       # Punto de entrada
│   ├── storage/                        # Acceso público a archivos
│   └── build/
├── vendor/                             # Dependencias (generado por Composer)
├── package.json                        # Dependencias de frontend
├── composer.json                       # Dependencias de PHP
├── vite.config.js                      # Configuración de Vite
├── tailwind.config.js                  # Configuración de Tailwind CSS
├── phpunit.xml                         # Configuración de tests
└── artisan                             # CLI de Laravel
```

---

##  Componentes Principales

### 1. **Controladores (Controllers)**

#### ComprasController.php
- Gestiona las compras de productos a proveedores
- Métodos: index, create, store, edit, update, destroy

#### MesasController.php
- Administra las mesas de billar
- Métodos: index, create, store, edit, update, destroy

#### ProductosController.php
- Gestión del catálogo de productos
- Métodos: index, create, store, edit, update, destroy

#### ProfileController.php
- Gestión del perfil del usuario autenticado
- Métodos:
  - `show()` - Muestra la vista de perfil
  - `updateProfile()` - Actualiza información personal
  - `updatePassword()` - Cambia la contraseña
  - `destroy()` - Elimina la cuenta del usuario

#### ProveedoresController.php
- Administra los proveedores
- Métodos: index, create, store, edit, update, destroy

#### UsersController.php
- Gestión de usuarios del sistema
- Métodos: index, create, store, edit, update, destroy

#### MesasventasController.php
- Registra las ventas realizadas en las mesas

#### InformesController.php
- Genera reportes e informes del negocio

#### InventarioController.php
- Gestión del inventario de productos

### 2. **Modelos (Models)**

#### User.php
```php
Relaciones:
- compras() - Compras realizadas
- mesas() - Mesas asignadas
- ventas() - Ventas registradas

Atributos:
- id, name, email, password, tipo_documento, numero_documento, tipo_usuario
- email_verified_at, created_at, updated_at
```

#### Productos.php
```php
Relaciones:
- proveedores() - Proveedores que ofrecen este producto
- ventas() - Ventas de este producto

Atributos:
- id, nombre, descripcion, precio, cantidad_stock
- created_at, updated_at
```

#### Mesas.php
```php
Relaciones:
- ventas() - Ventas realizadas en esta mesa
- usuario() - Usuario que usa la mesa

Atributos:
- id, numero, estado, precio_hora
- created_at, updated_at
```

#### Compra.php
```php
Relaciones:
- detalles() - Detalles de la compra (items)
- proveedor() - Proveedor de la compra
- usuario() - Usuario que realizó la compra

Atributos:
- id, proveedor_id, usuario_id, fecha_compra, total
- estado, created_at, updated_at
```

#### Proveedores.php
```php
Relaciones:
- compras() - Compras realizadas a este proveedor
- productos() - Productos ofrecidos

Atributos:
- id, nombre, contacto, telefono, email
- direccion, ciudad, estado, created_at, updated_at
```

---

##  Módulos Implementados

### 1. **Sistema de Autenticación**
- Login con email/usuario
- Registro de nuevos usuarios
- Recuperación de contraseña
- Verificación de email
- Sesiones seguras
- CSRF protection

### 2. **Gestión de Usuarios**
- Crear usuarios
- Editar información
- Cambiar contraseña
- Asignar roles
- Desactivar/eliminar usuarios
- Visualizar perfil personal

### 3. **Gestión de Mesas**
- Registrar mesas de billar
- Asignar precio por hora
- Controlar disponibilidad
- Historial de uso
- Reportes de uso

### 4. **Gestión de Productos**
- Catálogo de productos
- Control de inventario
- Precios y costos
- Asociar con proveedores
- Stock mínimo/máximo

### 5. **Gestión de Compras**
- Registrar compras a proveedores
- Detalles de cada compra
- Control de facturas
- Historial de compras
- Reportes de gasto

### 6. **Gestión de Ventas**
- Registrar ventas en mesas
- Ventas de productos
- Cálculo automático de totales
- Historial de ventas
- Reportes de ingresos

### 7. **Gestión de Proveedores**
- Registro de proveedores
- Información de contacto
- Historial de compras
- Evaluación de proveedores

### 8. **Gestión de Patrocinadores**
- Registro de patrocinadores
- Términos y condiciones
- Histórico de patrocinios

### 9. **Perfil de Usuario**
- Ver información personal
- Editar información personal
- Cambiar contraseña
- Eliminar cuenta
- Avatar de usuario

### 10. **Reportes e Informes**
- Reportes de ventas
- Reportes de compras
- Reportes de inventario
- Análisis de mesas más usadas
- Gráficos y estadísticas

---

## 🛣️ Rutas de la Aplicación

### Autenticación
```
POST   /login                    → Iniciar sesión
POST   /logout                   → Cerrar sesión
GET    /register                 → Formulario de registro
POST   /register                 → Crear nueva cuenta
GET    /forgot-password          → Solicitar reset de contraseña
POST   /forgot-password          → Enviar email de reset
GET    /reset-password/{token}   → Formulario de reset
POST   /reset-password           → Guardar nueva contraseña
```

### Perfil de Usuario
```
GET    /perfil                   → profile.show       (Ver perfil)
PUT    /perfil/actualizar        → profile.updateProfile (Actualizar info)
PUT    /perfil/contraseña        → profile.updatePassword (Cambiar contraseña)
DELETE /perfil                   → profile.destroy    (Eliminar cuenta)
```

### Usuarios
```
GET    /usuarios                 → users.index        (Listar usuarios)
GET    /usuarios/crear           → users.create       (Formulario crear)
POST   /usuarios                 → users.store        (Guardar usuario)
GET    /usuarios/{id}            → users.show         (Ver detalles)
GET    /usuarios/{id}/editar     → users.edit         (Formulario editar)
PUT    /usuarios/{id}            → users.update       (Actualizar usuario)
DELETE /usuarios/{id}            → users.destroy      (Eliminar usuario)
```

### Mesas
```
GET    /mesas                    → mesas.index        (Listar mesas)
GET    /mesas/crear              → mesas.create       (Crear mesa)
POST   /mesas                    → mesas.store        (Guardar mesa)
GET    /mesas/{id}/editar        → mesas.edit         (Editar mesa)
PUT    /mesas/{id}               → mesas.update       (Actualizar mesa)
DELETE /mesas/{id}               → mesas.destroy      (Eliminar mesa)
```

### Productos
```
GET    /productos                → productos.index    (Listar productos)
GET    /productos/crear          → productos.create   (Crear producto)
POST   /productos                → productos.store    (Guardar producto)
GET    /productos/{id}/editar    → productos.edit     (Editar producto)
PUT    /productos/{id}           → productos.update   (Actualizar producto)
DELETE /productos/{id}           → productos.destroy  (Eliminar producto)
```

### Compras
```
GET    /compras                  → compras.index      (Listar compras)
GET    /compras/crear            → compras.create     (Crear compra)
POST   /compras                  → compras.store      (Guardar compra)
GET    /compras/{id}             → compras.show       (Ver detalles)
GET    /compras/{id}/editar      → compras.edit       (Editar compra)
PUT    /compras/{id}             → compras.update     (Actualizar compra)
DELETE /compras/{id}             → compras.destroy    (Eliminar compra)
```

### Proveedores
```
GET    /proveedores              → proveedores.index  (Listar proveedores)
GET    /proveedores/crear        → proveedores.create (Crear proveedor)
POST   /proveedores              → proveedores.store  (Guardar proveedor)
GET    /proveedores/{id}/editar  → proveedores.edit   (Editar proveedor)
PUT    /proveedores/{id}         → proveedores.update (Actualizar proveedor)
DELETE /proveedores/{id}         → proveedores.destroy(Eliminar proveedor)
```

### Reportes
```
GET    /reportes                 → informes.index     (Panel de reportes)
GET    /reportes/ventas          → informes.ventas    (Reporte de ventas)
GET    /reportes/compras         → informes.compras   (Reporte de compras)
GET    /reportes/inventario      → informes.inventario(Reporte de inventario)
```

---

##  Modelos de Base de Datos

### Tabla: users
```sql
id (PK)
name (string)
email (unique)
email_verified_at (datetime, nullable)
password (hashed)
tipo_documento (enum: CC, CE, PA, NIT)
numero_documento (string)
tipo_usuario (enum: admin, empleado, cliente)
remember_token (nullable)
created_at (timestamp)
updated_at (timestamp)
```

### Tabla: mesas
```sql
id (PK)
numero (integer, unique)
estado (enum: disponible, ocupada, mantenimiento)
precio_hora (decimal)
created_at (timestamp)
updated_at (timestamp)
```

### Tabla: productos
```sql
id (PK)
nombre (string, unique)
descripcion (text, nullable)
precio (decimal)
costo (decimal)
cantidad_stock (integer)
cantidad_minima (integer)
created_at (timestamp)
updated_at (timestamp)
```

### Tabla: proveedores
```sql
id (PK)
nombre (string)
contacto (string)
telefono (string)
email (string)
direccion (text)
ciudad (string)
estado (enum: activo, inactivo)
created_at (timestamp)
updated_at (timestamp)
```

### Tabla: compras
```sql
id (PK)
proveedor_id (FK)
usuario_id (FK)
numero_factura (string)
fecha_compra (date)
total (decimal)
estado (enum: pendiente, recibida, cancelada)
created_at (timestamp)
updated_at (timestamp)
```

### Tabla: compra_detalles
```sql
id (PK)
compra_id (FK)
producto_id (FK)
cantidad (integer)
precio_unitario (decimal)
subtotal (decimal)
created_at (timestamp)
updated_at (timestamp)
```

### Tabla: mesas_ventas
```sql
id (PK)
mesa_id (FK)
usuario_id (FK)
hora_inicio (datetime)
hora_fin (datetime, nullable)
total (decimal)
estado (enum: abierta, cerrada)
created_at (timestamp)
updated_at (timestamp)
```

### Tabla: productos_ventas
```sql
id (PK)
producto_id (FK)
usuario_id (FK)
cantidad (integer)
precio_unitario (decimal)
total (decimal)
fecha_venta (date)
created_at (timestamp)
updated_at (timestamp)
```

### Tabla: patrocinadores
```sql
id (PK)
nombre (string)
descripcion (text)
terminos_condiciones (text)
estado (enum: activo, inactivo)
created_at (timestamp)
updated_at (timestamp)
```

---

##  Seguridad

### Medidas de Seguridad Implementadas:

#### 1. **Autenticación**
- Contraseñas hasheadas con bcrypt
- Verificación de email
- Recuperación segura de contraseña
- Sesiones seguras con cookie HTTPS

#### 2. **Autorización**
- Control de acceso basado en roles
- Middleware de autenticación
- Políticas de autorización (Policies)

#### 3. **Protección CSRF**
- Tokens CSRF en todos los formularios
- Validación en lado del servidor
- Headers X-CSRF-Token

#### 4. **Validación de Datos**
- Form Requests para validación
- Reglas de validación en modelos
- Sanitización de entrada

#### 5. **Encriptación**
- Contraseñas encriptadas
- Datos sensibles encriptados en base de datos
- HTTPS en producción

#### 6. **Control de Acceso**
- Middleware de rol
- Restricción de rutas
- Verificación de propiedad de recursos

#### 7. **Logging y Auditoría**
- Registro de acciones importantes
- Log de cambios en datos
- Log de intentos fallidos

---

##  Guía de Desarrollo

### Crear un Nuevo Controlador
```bash
php artisan make:controller MiControlador
```

### Crear un Nuevo Modelo con Migración
```bash
php artisan make:model MiModelo -m
```

### Crear una Nueva Migración
```bash
php artisan make:migration crear_tabla_mesas
```

### Crear un Form Request
```bash
php artisan make:request MiFormRequest
```

### Crear una Política (Policy)
```bash
php artisan make:policy MiPolicy
```

### Ejecutar Migraciones
```bash
php artisan migrate
```

### Revertir Última Migración
```bash
php artisan migrate:rollback
```

### Crear un Seeder
```bash
php artisan make:seeder NombreSeeder
php artisan db:seed --class=NombreSeeder
```

### Ejecutar Tests
```bash
php artisan test
```

### Compilar Assets
```bash
npm run build    # Producción
npm run dev      # Desarrollo
```

---

## 🐛 Troubleshooting

### Error: "Class not found"
**Solución:**
```bash
composer dump-autoload
```

### Error: "No application encryption key"
**Solución:**
```bash
php artisan key:generate
```

### Error: "SQLSTATE[HY000]: General error"
**Solución:**
```bash
php artisan migrate:reset
php artisan migrate
```

### Error: "Target class does not exist"
**Solución:**
- Verifica que el namespace sea correcto en el controlador
- Ejecuta `composer dump-autoload`

### Error: "CSRF token mismatch"
**Solución:**
- Verifica que incluyas `@csrf` en los formularios
- Limpia el caché: `php artisan cache:clear`

### Error: "view not found"
**Solución:**
- Verifica la ruta del archivo en `resources/views/`
- Recuerda usar puntos en lugar de barras: `views.auth.login`

### Error: "No query results found"
**Solución:**
- Usa `first()` en lugar de `firstOrFail()` si quieres comprobar existencia
- Verifica que el ID existe en la base de datos

### Error: "Allowed memory exhausted"
**Solución:**
```bash
# En .env o php.ini
memory_limit = 256M
```

### Error: "Database connection refused"
**Solución:**
- Verifica que el servidor de BD esté corriendo
- Verifica las credenciales en `.env`
- Verifica el puerto de la BD

---

##  Características de Perfil de Usuario

### Vista: `/resources/views/profile/show.blade.php`

**Estado:**  Completamente implementada

**Características Implementadas:**
- Tarjeta de perfil con avatar dinámico
- Información personal (nombre, email, documento, tipo)
- Modal para editar información personal
- Modal para cambiar contraseña con toggle de visibilidad
- Modal para eliminar cuenta (Zona de Peligro)
- Validación en tiempo real de contraseña
- Estilos AdminLTE con Bootstrap 5

### Rutas de Perfil

```
GET    /perfil                    → profile.show
PUT    /perfil/actualizar         → profile.updateProfile
PUT    /perfil/contraseña         → profile.updatePassword
DELETE /perfil                    → profile.destroy
```

### Acceso al Perfil

**Método 1: URL Directa**
```
https://tu-dominio.com/perfil
```

**Método 2: Menú de Usuario AdminLTE**
- Haz clic en el avatar/nombre de usuario en la esquina superior derecha
- Selecciona "Perfil" en el dropdown

**Método 3: En Plantillas Blade**
```blade
<a href="{{ route('profile.show') }}">Mi Perfil</a>
```

### Funcionalidades

####  Ver Información Personal
- Nombre y Apellidos
- Email
- Tipo de Documento (CC, CE, PA, NIT)
- Número de Documento
- Tipo de Usuario
- Fecha de Creación
- Última Actualización

####  Editar Información Personal (Modal)
- Cambiar Nombre
- Cambiar Apellidos
- Cambiar Email
- Seleccionar Tipo de Documento
- Cambiar Número de Documento
- Validación del lado del servidor
- Mensaje de éxito

####  Cambiar Contraseña (Modal)
- Ingresa contraseña actual
- Nueva contraseña (mín. 8 caracteres)
- Confirmación de contraseña
- Botones para mostrar/ocultar contraseña
- Validación en tiempo real
- Confirmación automática de coincidencia

####  Zona de Peligro - Eliminar Cuenta (Modal)
- Confirmación de contraseña
- Advertencias sobre irreversibilidad
- Doble confirmación
- Logout automático
- Eliminación completa de datos

---

##  Configuración AdminLTE

**Archivo:** `/config/adminlte.php`

**Configuración Aplicada:**
```php
'usermenu_enabled' => true,           // Menú de usuario habilitado
'usermenu_header' => true,            // Mostrar encabezado con nombre
'usermenu_image' => true,             // Mostrar avatar
'usermenu_desc' => true,              // Mostrar descripción (tipo)
'usermenu_profile_url' => '/perfil',  // URL del perfil
```

---

##  Tecnologías Utilizadas

| Categoría | Tecnología |
|-----------|-----------|
| **Framework Backend** | Laravel 12.0 |
| **Framework Frontend** | AdminLTE 3.15 |
| **Lenguaje Backend** | PHP 8.2+ |
| **Motor de Plantillas** | Blade |
| **Base de Datos** | MySQL 8.0+ / PostgreSQL 12+ |
| **CSS** | Tailwind CSS 3.1 |
| **JavaScript** | Alpine.js 3.4 + Axios |
| **Herramienta Build** | Vite 7.0 |
| **Testing** | Pest PHP 3.8 |
| **Control de Versiones** | Git |

---

##  Contacto y Soporte

- **GitHub:** https://github.com/nyllereth421/PROYECTO-BILLAR
- **Rama Actual:** `prueba`
- **Rama Principal:** `main`

---

##  Historial de Cambios

### Versión 1.0.0 (17 Nov 2025)
- ✅ Integración completa de perfil de usuario
- ✅ Sistema de autenticación
- ✅ Gestión de usuarios
- ✅ Gestión de mesas
- ✅ Gestión de productos
- ✅ Gestión de compras y ventas
- ✅ Reportes e informes
- ✅ AdminLTE integrado
- ✅ Seguridad CSRF
- ✅ Validación de datos

---

##  Licencia

Este proyecto está bajo la licencia MIT.

---

**Documento Generado:** 17 de Noviembre de 2025  
**Versión:** 1.0.0  
**Estado:** Producción
