# Estado del Proyecto - PROYECTO-BILLAR

## Última Actualización: 2026-04-10
**Fase Actual:** Auditoría y Validación de Calidad
**Hito:** Migración completada. Iniciando revisión profunda del código.

## Tareas Pendientes
- [ ] Mapeo completo de rutas y funcionalidades.
- [ ] Ejecución de pruebas unitarias y de integración existentes.
- [ ] Creación de nuevas pruebas para lógica de negocio (Mesas, Ventas).
- [ ] Revisión de estándares (PSR-12, SOLID) y nomenclatura.
- [ ] Auditoría de seguridad (validación de inputs, middleware).
- [ ] Optimización de rendimiento en consultas Eloquent.

## Decisiones Recientes
- Se ha decidido utilizar **Docker** para gestionar la base de datos en la nube. Esto permitirá un control preciso de los recursos (CPU/RAM) y facilitará el encendido/apagado del servicio.
- **No se migrarán datos históricos**: Se utilizará el sistema de migraciones y seeders de Laravel para inicializar la estructura y datos de prueba necesarios.
- El sistema operativo destino sigue siendo Ubuntu en Oracle Cloud.
