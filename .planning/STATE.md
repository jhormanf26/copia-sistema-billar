# Estado del Proyecto - PROYECTO-BILLAR

## Última Actualización: 2026-04-11
**Fase Actual:** Rediseño UI/UX — Fase 1 Completada
**Hito:** Correcciones Críticas de UI/UX aplicadas. Fase 2 pendiente.

## Fase 1: Correcciones Críticas (COMPLETADA ✅)
- [x] SweetAlert2 centralizado globalmente en `config/adminlte.php` (plugin activado con v11)
- [x] Eliminación de 9 cargas CDN duplicadas de SweetAlert2 en vistas individuales
- [x] Emojis (📧🆔) eliminados del login y reemplazados por SVG inline
- [x] `aria-label` añadido al botón toggle de contraseña en login
- [x] `prompt()` nativo del calendario reemplazado por `Swal.fire({ input: 'text' })`
- [x] Clase CSS rota `col-` corregida a `col-6` en welcome.blade.php
- [x] Modal duplicada de "Eliminar Cuenta" eliminada de profile/show.blade.php
- [x] Copyright actualizado a `{{ date('Y') }}` dinámicamente

## Tareas Pendientes (Fase 2+)
- [x] Crear archivo `billarnexus-tokens.css` con custom properties del design system
- [x] Crear `dark-mode-overrides.css` para contrastes en dark mode
- [x] Migrar 7 `confirm()` nativos restantes a SweetAlert2 (users, proveedores, mesas, compras, profile)
- [x] Eliminar Bootstrap 5 CDN de `mesasventas/index.blade.php`
- [x] Eliminar emojis del registro (`auth/register.blade.php`)
- [x] Implementar shimmer/skeleton en KPIs del dashboard

## Tareas Pendientes (Fase 3: Refactorización Estructural)
- [x] Construir sistema de clases `.drawer.right` CSS puro sobre Bootstrap 4.
- [x] Migrar visualmente los pesados Modales de adición POS a un Drawer Offcanvas.
- [ ] Centralizar/optimizar modales (reducir sobrecarga del DOM) mediante AJAX (Opcional Futuro).

## Decisiones Recientes
- **SweetAlert2 Global:** Se activó el plugin de AdminLTE para cargarlo una sola vez en el layout, eliminando ~200KB de descargas redundantes por página.
- **SVG Inline vs FontAwesome:** Para el login (que no usa AdminLTE), se usaron SVG inline en lugar de cargar FontAwesome como dependencia adicional.
- **Modal duplicada eliminada:** Se mantuvo la versión con checkbox de confirmación (más segura) y se eliminó la que usaba `confirm()` nativo.
- Se ha decidido utilizar **Docker** para gestionar la base de datos en la nube.
