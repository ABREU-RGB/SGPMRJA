# TASK-018: QA final del wizard de pedidos + doc del patrón

**Feature**: FEAT-002 — pedidos-wizard
**Spec**: `sdd/specs/pedidos-wizard.spec.md`
**Status**: pending
**Priority**: high
**Esfuerzo estimado**: M (2–4h)
**Depends-on**: TASK-017
**Assigned-to**: emmanuel

---

## Contexto

Última task del feature. Ejecutar el QA manual completo de la sección 4 del spec en los 3 modos (crear, completar desde cotización, editar), corregir regresiones, documentar el patrón del wizard como reusable y preparar la PR única contra `enmanuel`.

Sección del spec: `## 3 → Módulo 9` + `## 4 Test/QA` + criterio "doc `docs/conventions/wizard-pattern.md`".

---

## Scope

- Ejecutar el QA manual completo (spec sección 4) en light + dark mode
- Verificar los 3 modos: crear nuevo, completar desde cotización, editar
- Verificar todos los edge cases del spec sección 4
- Corregir cualquier regresión encontrada (commits en la misma rama)
- Crear `docs/conventions/wizard-pattern.md` documentando el patrón `.wiz-*` reusable
- Verificar mobile (modal-fullscreen-sm-down)
- Preparar y abrir la PR única `feat/pedidos-wizard` → `enmanuel`

**NO está en alcance**:
- Features nuevas — solo QA + fixes + doc
- Mergear a `main` (eso lo hace Emmanuel cuando el profesor revise)

---

## Archivos a crear / modificar

| Archivo | Acción | Descripción |
|---|---|---|
| `docs/conventions/wizard-pattern.md` | CREATE | Doc del patrón wizard `.wiz-*` reusable |
| `docs/conventions/README.md` | MODIFY | Añadir entrada al índice de 17→18 docs |
| (varios) | MODIFY | Fixes de regresiones que surjan en QA |

---

## Codebase Contract (Anti-Alucinación)

### Checklist de QA (del spec sección 4)

**Modo crear**: pasos 1→4, validaciones por paso, submit POST, toast, reload
**Modo completar desde cotización**: abre en paso 3, banner heredado, cotizacion_id persiste
**Modo edit**: campos protegidos, solo pago editable, submit PUT
**Importación desde paso 2**: modal anidado, hidratación, badge
**Edge cases**: cancelar a media, abono 0, abono total, abono > total, sin productos, modal anidado

### Doc índice

```
docs/conventions/README.md — índice de 17 docs canónicos (ver memoria MEMORY.md)
→ añadir entrada wizard-pattern.md (pasaría a 18)
```

### Convenciones a respetar

- `docs/conventions/` — formato de los docs existentes (seguir el mismo estilo)
- `AGENTS.md` — reglas no-negociables

### NO existe — no referenciar

- ~~tests automatizados (PHPUnit/Pest) para el wizard~~ — el proyecto usa QA manual; no inventar suite de tests
- ~~CI pipeline~~ — no hay; la verificación es manual

---

## Notas de implementación

### Patrón a seguir

El doc `wizard-pattern.md` debe cubrir:
- Cuándo usar el patrón wizard (forms multi-paso complejos)
- Estructura de clases `.wiz-*` (stepper, step-content, step-header)
- Cómo se cablea la navegación (`irAPaso`, validación por paso)
- Ejemplo mínimo de un paso
- Referencia a las 2 implementaciones (cotizaciones, pedidos)

### Restricciones clave

- NO mergear si algún caso de QA falla
- Cada regresión corregida = commit descriptivo en `feat/pedidos-wizard`
- La PR debe enlazar el spec y listar las 10 tasks completadas

### Referencias en el código

- `sdd/specs/pedidos-wizard.spec.md` § 4 — checklist de QA completo
- `docs/conventions/ux-search-filters.md` — ejemplo de doc de convención bien estructurado

---

## Criterios de aceptación

- [ ] QA manual completo (spec sección 4) pasa en los 3 modos
- [ ] Todos los edge cases verificados
- [ ] Light + dark mode OK
- [ ] Mobile (modal-fullscreen-sm-down) OK
- [ ] Consola sin errores JS en ningún flujo
- [ ] `docs/conventions/wizard-pattern.md` creado
- [ ] `docs/conventions/README.md` actualizado (índice)
- [ ] Wizard de cotizaciones sigue funcionando (no regresión por el rename)
- [ ] PR única `feat/pedidos-wizard` → `enmanuel` abierta, enlazando spec + 10 tasks
- [ ] Las 10 tasks (009-018) movidas a `sdd/tasks/completed/`

---

## QA manual

> Ejecutar el checklist completo del spec sección 4. Resumen:

1. **Crear**: pasos 1-4, validaciones, guardar → pedido en listado
2. **Desde cotización**: convertir → abre paso 3 → guardar → cotizacion_id correcto
3. **Editar**: campos protegidos, pago editable → actualizar
4. **Importar paso 2**: modal anidado → hidratar → badge
5. **Edge cases**: cancelar, abono 0/total/>total, sin productos
6. **Cotización (regresión)**: wizard de cotización funciona idéntico
7. **Mobile**: fullscreen activa
8. **Dark mode**: todo lo anterior

---

## Instrucciones para el ejecutor

1. Lee spec completo + verifica TASK-017 `completed`
2. Header: `Status: in-progress`
3. Rama `feat/pedidos-wizard`
4. Ejecuta QA exhaustivo, corrige regresiones
5. Crea el doc del patrón
6. Mueve esta task + verifica que las 9 previas estén en `completed/`
7. Abre la PR única contra `enmanuel`
8. Llena Nota de Completitud

---

## Nota de Completitud

*(Llenar al terminar)*

**Completado por**:
**Fecha**:
**Commits**:
**Notas**:

**Desviaciones del spec**:
