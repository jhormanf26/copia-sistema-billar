# Guía de Despliegue e Infraestructura (Oracle Cloud)

Esta guía detalla la configuración del entorno de base de datos para el Sistema de Billares utilizando Docker en una instancia Ubuntu en Oracle Cloud.

## 🐳 Configuración Docker
El sistema utiliza Docker Compose para gestionar la base de datos MySQL 8.0 con límites de recursos estrictos para no saturar la instancia gratuita de Oracle.

### Archivo: `docker-compose.yml`
- **Contenedor:** `billar-db`
- **Puerto Externo:** `3306`
- **Límites de Recursos:**
  - Memoria: `512MB`
  - CPU: `0.5` (50% de un core)

### Comandos de Gestión
Ejecutar desde la terminal en el servidor:
```bash
# Iniciar la base de datos (segundo plano)
docker-compose up -d

# Ver logs en tiempo real
docker-compose logs -f

# Detener el servicio
docker-compose down

# Reiniciar si hay cambios en config
docker-compose restart
```

## 🗄️ Configuración de Base de Datos (MySQL)
- **Host:** `billar-db.desarollo.site`
- **Puerto:** `3306`
- **Usuario:** `admin`
- **Base de Datos:** `billar_db`
- **Acceso:** Configurado para permitir conexiones remotas seguras.

## 🔗 Conexión desde Laravel (`.env`)
El archivo `.env` local debe apuntar a la nube para compartir la data:
```env
DB_CONNECTION=mysql
DB_HOST=billar-db.desarollo.site
DB_PORT=3306
DB_DATABASE=billar_db
DB_USERNAME=admin
DB_PASSWORD=admin123
```

## 🧪 Validación de Salud
Para verificar que el sistema está operativo tras un despliegue:
```bash
php artisan migrate:status
php artisan test
```
