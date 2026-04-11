# Estándares de Código y Refactorización

Se ha realizado una auditoría y limpieza profunda del código para cumplir con los estándares modernos de Laravel (PSR-12).

## 🚀 Arquitectura de Modelos
Se renombraron los modelos de nombres en plural y minúsculas a **Singular PascalCase** para mejorar la legibilidad y evitar problemas en servidores Linux.

### Mapeo de Nombres
- `mesas` ➔ `Mesa`
- `productos` ➔ `Producto`
- `mesasventas` ➔ `MesaVenta`
- `productosventas` ➔ `ProductoVenta`
- `proveedores` ➔ `Proveedor`
- `patrocinadores` ➔ `Patrocinador`
- `productosproveedor` ➔ `ProductoProveedor`

## 🧪 Estrategia de Pruebas
Se ha configurado la suite de pruebas para validar la integridad del sistema:

### Comandos de Test
```bash
# Ejecutar todas las pruebas
php artisan test

# Ejecutar una prueba específica
php artisan test --filter ProfileTest
```

### Reglas de Negocio Validadas
- Autenticación de usuarios.
- Registro con campos obligatorios (`numerodocumento`, `apellidos`).
- Flujo de redirección tras login.
- Actualización de perfil con validaciones Regex.

## 📦 Recomendaciones de Mantenimiento
1. **Actualización de Modelos:** Siempre usar nombres en singular.
2. **Migrations:** Mantener el campo `numerodocumento` y `apellidos` como campos clave para la identificación de usuarios.
3. **Factories:** Cualquier campo `NOT NULL` nuevo en la DB debe reflejarse en `database/factories/UserFactory.php`.
