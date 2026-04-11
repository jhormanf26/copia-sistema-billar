# Guía de Acceso y Credenciales

Este documento contiene la información necesaria para acceder al sistema y bases de datos.

## 💻 Acceso al Sistema (Web)
Por defecto, tras ejecutar los seeders, puedes usar las siguientes cuentas de prueba:

| Usuario | Email | Contraseña | Rol |
| :--- | :--- | :--- | :--- |
| **Administrador** | `admin@admin.com` | `admin123` | Administrador |
| **Desarrollo** | `a@a.a` | `12345678` | Administrador |

## 🛠️ Conexión DBeaver / TablePlus
Para gestionar la base de datos visualmente:

1. **Host:** `billar-db.desarollo.site`
2. **Puerto:** `3306`
3. **Database:** `billar_db`
4. **Username:** `admin`
5. **Password:** `admin123`
6. **Driver:** MySQL / MariaDB

> [!IMPORTANT]
> Asegúrate de que el puerto 3306 esté abierto en la lista de seguridad (Ingress Rules) de tu VCN en el panel de Oracle Cloud.

## 👤 Registro de Nuevos Usuarios
El proceso de registro ha sido configurado por seguridad:
1. El usuario se registra en `/register`.
2. El usuario queda en estado `inactivo` por defecto.
3. Un **Administrador** debe cambiar el estado a `activo` desde el panel antes de que el usuario pueda iniciar sesión.
