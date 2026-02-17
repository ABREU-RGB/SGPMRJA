# Matriz de Migración de Documentación

Objetivo: consolidar documentación en `docs/` como fuente única de verdad.

## Convenciones de estado

- `CANONICO`: documento oficial vigente en `docs/`.
- `LEGADO`: documento histórico; no editar como fuente principal.
- `PENDIENTE_MIGRAR`: requiere consolidación o actualización en `docs/`.
- `EXPORTACION`: artefacto derivado (`.html`, `.pdf`) no editable como fuente principal.

## Inventario y destino recomendado

| Origen | Estado actual | Destino canónico sugerido | Estado objetivo |
|---|---|---|---|
| `README.md` | Vigente | `README.md` (overview) + `docs/README.md` (índice técnico) | CANONICO |
| `docs/SGPMRJA_ANALISIS_TECNICO_2026-02-17.md` | Vigente | Mismo archivo | CANONICO |
| `docs/BUTTON_STANDARDS.md` | Vigente | Mismo archivo | CANONICO |
| `docs/BUTTON_STYLE_STANDARDS.md` | Vigente | Mismo archivo | CANONICO |
| `docs/IMPLEMENTATION_SUMMARY.md` | Vigente | Mismo archivo | CANONICO |
| `Documentacion/ANALISIS_COMPLETO_SISTEMA.md` | Origen legado | `docs/ARQUITECTURA_SISTEMA.md` | CANONICO (migrado) |
| `Documentacion/ANALISIS_COMPLETO_SISTEMA.html` | Duplicado formato | `Documentacion/exportaciones/ANALISIS_COMPLETO_SISTEMA.html` | EXPORTACION |
| `Documentacion/ANALISIS_COMPLETO_SISTEMA_BACKUP.md` | Respaldo histórico | `docs/ARQUITECTURA_SISTEMA.md` | LEGADO |
| `Documentacion/ANALISIS_COMPLETO_SISTEMA_BACKUP.html` | Respaldo | `Documentacion/exportaciones/ANALISIS_COMPLETO_SISTEMA_BACKUP.html` | LEGADO |
| `Documentacion/CASOS_DE_USO.md` | Origen legado | `docs/CASOS_DE_USO.md` | CANONICO (migrado) |
| `Documentacion/CASOS_DE_USO.html` | Duplicado formato | `Documentacion/exportaciones/CASOS_DE_USO.html` | EXPORTACION |
| `Documentacion/DICCIONARIO_DATOS.md` | Origen legado | `docs/DICCIONARIO_DATOS.md` | CANONICO (migrado y regenerado desde SQL) |
| `Documentacion/DICCIONARIO_DATOS.html` | Duplicado formato | `Documentacion/exportaciones/DICCIONARIO_DATOS.html` | EXPORTACION |
| `Documentacion/REQUERIMIENTOS_FUNCIONALES.md` | Origen legado | `docs/REQUERIMIENTOS_FUNCIONALES.md` | CANONICO (migrado) |
| `Documentacion/CHANGELOG_COTIZACIONES.md` | Origen legado | `docs/CHANGELOG_COTIZACIONES.md` | CANONICO (migrado) |
| `Documentacion/auditoria_sgpmrja.md` | Origen legado | `docs/AUDITORIA_SGPMRJA.md` | CANONICO (migrado) |

## Secuencia sugerida de ejecución

1. Migrar `CASOS_DE_USO.md` a `docs/`.
2. Regenerar `DICCIONARIO_DATOS.md` desde esquema SQL actual.
3. Consolidar arquitectura (`ANALISIS_COMPLETO_*`) en un único `docs/ARQUITECTURA_SISTEMA.md`.
4. Migrar changelog y auditoría. ✅
5. Dejar en `Documentacion/` solo legado y anexos gráficos. ✅
