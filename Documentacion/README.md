# Estado de la carpeta Documentacion

Esta carpeta se mantiene por compatibilidad histórica.

## Estado actual

- **No canónica**: no usar esta carpeta como fuente principal para documentación nueva.
- **Fuente oficial**: `docs/README.md` y documentos bajo `docs/`.
- **Uso permitido**: consulta histórica, respaldo, anexos gráficos y exportaciones.

## Clasificación recomendada del contenido actual

### Legado duplicado por formato
- Markdown legado en raíz de `Documentacion/`.
- Exportaciones HTML movidas a `Documentacion/exportaciones/`.

Archivos HTML actuales en exportaciones:
- `ANALISIS_COMPLETO_SISTEMA.html`
- `ANALISIS_COMPLETO_SISTEMA_BACKUP.html`
- `CASOS_DE_USO.html`
- `DICCIONARIO_DATOS.html`

> En estos casos, conservar **Markdown** como editable y tratar **HTML** como exportación.

### Historial / anexos
- `CHANGELOG_COTIZACIONES.md`
- `auditoria_sgpmrja.md`
- Diagramas (`.png`, `.jpg`) y documentos externos (`.pdf`, `.txt`).

## Regla operativa

Si un documento de esta carpeta se actualiza y sigue vigente, debe migrarse a `docs/` y referenciarse en `docs/README.md`.
