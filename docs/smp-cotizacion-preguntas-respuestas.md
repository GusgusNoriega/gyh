# SMP – Preguntas y respuestas sobre la cotización (Laravel)

Documento interno para responder dudas del cliente sobre la cotización (paquete “Custom” para sistema desde cero). **Las especificaciones del alcance cotizado se mantienen sin cambios**; cuando alguna pregunta implique ampliar alcance, se marca como **Adicional (posible reajuste)** con rangos aproximados.

> Contexto base
>
> - Tecnología: **Laravel** (no WordPress).
> - Hosting y dominio: **provistos por San Miguel Properties (SMP)**.
> - Objetivo: construir un sitio/sistema a medida, reutilizando módulos propios ya probados y adaptándolos a SMP.

---

## 1) Confirmaciones

### 1.1 La opción “custom” no será en WordPress, ¿correcto?
Correcto. La implementación será **100% en Laravel** (back-end + panel administrativo), con front-end en Blade/Tailwind (o equivalente) y componentes reutilizables.

### 1.2 Entendemos que usarás módulos/plantillas ya desarrollados y los adaptarás a SMP.
Confirmado. Se reutilizan **módulos propios** (autenticación, roles/permisos, media manager, estructura base de admin, etc.) y se **adaptan** al flujo y contenido de SMP. Esto reduce tiempos y riesgos, manteniendo un resultado “custom”.

### 1.3 Confirmado: puedes seguir nuestro brand book, y el look & feel será similar a tu sitio de ejemplo.
Confirmado. El diseño se alinea al **brand book de SMP** (tipografías, colores, tono visual) y el look & feel será consistente con el ejemplo compartido, ajustando lo necesario para el contenido/estructura específica.

### 1.4 Accesos/propiedad: todo debe quedar a nombre de SMP.
Confirmado.

**Propiedad y cuentas a nombre de SMP (recomendado/obligatorio):**
- Dominio/DNS
- Hosting
- Analíticas (GA4 / GTM / Search Console)
- CRM
- Cualquier panel/admin de terceros

**Accesos del desarrollador:**
- Acceso “developer/admin” mientras dure el desarrollo y soporte acordado.
- Al cierre del proyecto, se dejan accesos documentados y se puede retirar el acceso del desarrollador si SMP lo solicita.

---

## 2) Timeline realista (con fechas)

### 2.1 Si empezamos el lunes 22 de diciembre de 2025, ¿qué fecha exacta propones para la entrega final?
Propuesta de entrega final: **viernes 13 de febrero de 2026**.

Notas:
- El inicio (22/dic) coincide con semanas de festividades; el cronograma contempla ese riesgo (revisión/feedback puede ser más lento).
- La entrega final depende de que SMP entregue a tiempo: brand book final, contenido, accesos, credenciales de APIs/CRM, y feedback semanal.

### 2.2 ¿Cómo dividirías el proyecto en hitos semanales?
**Semana 1 (22–28 dic 2025)**
- Kickoff + checklist de accesos (hosting/DNS/GA/GTM/SC/CRM si aplica)
- Setup del repositorio, ambiente local y ambiente de staging
- Base del proyecto Laravel (estructura, auth, roles iniciales)

**Semana 2 (29 dic–4 ene 2026)**
- Maquetación base (home + layout + componentes UI)
- Estructura de páginas clave (plantillas) alineadas al brand book

**Semana 3 (5–11 ene 2026)**
- Módulo de propiedades (modelo de datos / vistas / SEO básico)
- Integración de “fuente de verdad” (p. ej. EasyBroker) en modo inicial (import + sync programado)

**Semana 4 (12–18 ene 2026)**
- Formularios de contacto/leads + almacenamiento + envío
- Integración CRM (si ya se define herramienta y credenciales)
- Endurecimiento de seguridad: rate limiting, validaciones, logs

**Semana 5 (19–25 ene 2026)**
- Panel admin: gestión de contenido, usuarios, roles/permisos
- Ajustes de UX + performance (caché, imágenes, etc.)

**Semana 6 (26 ene–1 feb 2026)**
- QA funcional (casos de prueba), correcciones
- SEO técnico y analítica (GA/GTM/SC) si ya hay accesos

**Semana 7 (2–8 feb 2026)**
- UAT con SMP (revisión final) + últimos ajustes
- Documentación técnica mínima (deploy/config)

**Semana 8 (9–13 feb 2026)**
- Deploy a producción
- Checklist post-lanzamiento + monitoreo inicial

> Importante: este plan asume alcance “core” según cotización vigente. Integraciones adicionales (múltiples MLS/portales, alertas, favoritos, etc.) agregan semanas.

### 2.3 ¿Tendremos un entorno de demo/staging para revisar avances? ¿Con qué frecuencia?
Sí. Se habilita un **staging** (demo) para revisión.

- Frecuencia recomendada: **semanal** (al cierre de cada hito) y/o cuando se complete un módulo.
- SMP revisa y aprueba por escrito (email/ClickUp) para evitar re-trabajo.

### 2.4 Si el proyecto requiere más tiempo del previsto: ¿cómo se maneja?
Se maneja con **control de cambios**:

1) **Ajuste de alcance (sin costo extra)**: se re-prioriza, se mueve funcionalidad a “Fase 2” manteniendo la tarifa fija.
2) **Horas adicionales (costo extra)**: si SMP decide mantener todo y agregar/expandir requerimientos.

**Cotización de adicionales:** se entrega estimación por escrito antes de ejecutar.

### 2.5 Como es tarifa fija, si hubiera retrasos por tu lado (no por SMP), ¿afecta el costo?
No. Si el retraso es atribuible al proveedor (desarrollo) y no a dependencias/feedback/entregables de SMP, **no se incrementa el costo**.

---

## 3) Plataforma / arquitectura (no WordPress)

### 3.1 ¿Qué stack propones y cuál es el enfoque de despliegue?
**Stack propuesto (Laravel):**
- Back-end: **Laravel 11+** (PHP 8.2+)
- Front-end: Blade + TailwindCSS + Vite (o equivalente)
- Base de datos: **MySQL/MariaDB** (según hosting)
- Cola/jobs: database queue o Redis (si el hosting lo soporta)
- Caching: file/database/Redis según disponibilidad

**Despliegue (deployment):**
- Repositorio Git (GitHub/GitLab) a nombre de SMP.
- Staging y producción con variables `.env` separadas.
- Despliegue por:
  - CI/CD (recomendado) si el hosting lo permite, o
  - Deploy manual documentado (si es shared hosting/cPanel con limitaciones).

### 3.2 Admin interno (roles/permisos/logs/sync): ¿se entrega tal cual o por fases?
Dentro del alcance se entrega un **admin funcional** con:
- Usuarios
- Roles/permisos (RBAC)
- Logs básicos (auditoría mínima)
- Sincronización (si aplica) con la fuente de propiedades definida

Si SMP desea una auditoría avanzada (históricos completos por campo, aprobaciones, workflows, etc.), se recomienda **Fase 2**.

**Adicional (posible reajuste):** auditoría avanzada y workflows.
- Estimación: **+250 a +600 USD**, según nivel de detalle.

### 3.3 Sobre administración del sitio: roles de usuario
Roles sugeridos:
- **Admin**: control total (usuarios, configuración, contenido).
- **Editor/Content Manager**: gestión de páginas, textos, media, SEO básico.
- **Agente/Asesor** (si aplica): acceso a leads asignados y propiedades relacionadas.
- **Soporte**: gestión operativa limitada (p. ej. revisar leads, logs básicos).

Los roles exactos se confirman en el kickoff.

### 3.4 ¿Limitación de cuántos administradores/usuarios internos?
No hay límite por el sistema (Laravel). La limitación real suele ser:
- El plan del **hosting** (recursos)
- El plan del **CRM** (cantidad de usuarios/licencias)

### 3.5 Entrega técnica: repo + documentación de despliegue/configuración
Incluido:
- Repositorio Git
- README con pasos de:
  - instalación
  - variables de entorno
  - comandos de build
  - despliegue en el hosting
  - jobs/cron necesarios

---

## 4) Publicación de propiedades: 1 sistema (máx. 2) + automatización

### 4.1 ¿Qué recomiendas como “fuente de verdad” para listings?
Recomendación: definir **1 fuente de verdad** para crear/editar propiedades, idealmente:

- **EasyBroker** si ya lo usan y el equipo está entrenado ahí.

Alternativa: un **MLS** (si su operación depende de MLS) o un admin propio; pero esto aumenta complejidad.

### 4.2 Para cada canal adicional: método de sync y frecuencia
Depende del canal:
- **API** (ideal): sincronización por jobs (cron) cada 15–60 min.
- **Feed/CSV/XML**: import programado (cron) cada 1–24 h.
- **Webhook**: tiempo real si el proveedor lo soporta.
- **Manual**: solo si no existe integración estable.

La frecuencia final se ajusta a límites de API y a la operación del negocio.

### 4.3 ¿Cómo manejas duplicados, cambios, vendidos/despublicados, redirecciones?
Estrategia general:
- **Identificador único** por canal (ID externo) + reglas de normalización.
- **Deduplicación** por (ID externo) y/o por combinaciones (título + dirección parcial + precio) según el origen.
- **Cambios de precio/estatus**: se actualizan por sync y se registran cambios importantes (log).
- **Vendido/despublicado**: se marca como inactivo y se retira de listados públicos.
- **Redirecciones**: si cambia el “slug/URL” de una propiedad, se crea redirección 301 (SEO).

### 4.4 Integraciones con MLS-Allende / AMPI MLS / OmniMLS / otros portales
Se puede, pero **no todos exponen APIs limpias**; algunos trabajan con feeds, otros requieren acuerdos.

**Claridad de alcance:**
- Incluido: sincronización con **1 fuente principal** (y opcionalmente una segunda, si ya está definida y técnicamente disponible).
- **Adicional (posible reajuste):** cada MLS/portal extra suele requerir desarrollo, pruebas y soporte continuo.

Estimaciones típicas por integración adicional:
- Si hay API bien documentada: **+300 a +900 USD**
- Si es feed/CSV con reglas especiales: **+200 a +600 USD**
- Si no hay API/feeds (scraping): **no recomendado**; alta fragilidad y costo.

---

## 5) CRM (integración) + flujo de leads

### 5.1 ¿Qué CRM propones exactamente?
Recomendación por adopción y ecosistema:

- **HubSpot CRM** (ideal si ya lo conocen). Para privacidad por agente normalmente se requiere **plan de pago** con permisos/teams.

Alternativa muy sólida en permisos:
- **Zoho CRM** (suele ofrecer controles de visibilidad por roles/jerarquías, dependiendo del plan).

**Nota clave:** el CRM exacto se confirma con SMP considerando:
- número de agentes
- necesidad de privacidad
- automatizaciones
- presupuesto mensual

### 5.2 ¿Cuál sería el flujo completo de leads?
Flujo propuesto:
1) Formulario (sitio) → endpoint seguro (Laravel)
2) Validación + anti-spam + deduplicación
3) Registro interno (DB) + notificación por email (opcional)
4) Envío/creación en CRM (API)
5) Asignación según reglas
6) Seguimiento: tareas/notificaciones según el CRM

### 5.3 Reglas de asignación (round-robin / zona / idioma / propiedad / agente)
Se soporta por reglas, por ejemplo:
- Round-robin por equipo
- Por zona/ubicación de propiedad
- Por idioma del lead
- Por agente específico (si el lead viene desde la página de un agente)

Mientras más reglas, mayor configuración y pruebas.

### 5.4 ¿Los leads pueden disparar tareas u otras acciones en ClickUp?
Sí, si ClickUp está disponible vía API y SMP provee token/estructura.

**Adicional (posible reajuste):** integración CRM → ClickUp (crear tarea, asignar, etiquetas, etc.)
- Estimación: **+150 a +400 USD** según complejidad.

### 5.5 ¿El formulario capturará UTMs + origen + idioma? ¿Deduplicación y anti-spam?
Sí.
- Captura: **UTMs**, URL de origen (página/listing), idioma, timestamp.
- Deduplicación: por email/teléfono + ventana de tiempo configurable.
- Anti-spam: **reCAPTCHA/Turnstile** + rate limiting.

### 5.6 Requisito clave: contactos por agente deben ser privados
Esto se garantiza principalmente con **permisos del CRM**:
- Configurar “teams/roles” y reglas de visibilidad: cada agente ve solo sus leads.

En el sistema Laravel:
- Se puede replicar el modelo (lead asignado a agente) para que en el admin interno también existan restricciones.

**Nota:** si el CRM elegido no soporta privacidad real por usuario en el plan contratado, habrá que subir de plan o ajustar el flujo.

---

## 6) Diseño + “features extra” (para evitar sorpresas)

### 6.1 ¿Cuántas rondas de ajustes visuales están incluidas?
Incluido: **2 rondas** de ajustes visuales posteriores a aplicar el brand book sobre las pantallas/páginas acordadas.

Rondas adicionales se cotizan como extra.

### 6.2 Funcionalidades “plus”: cuáles están incluidas y cuáles serían adicionales
Para evitar sorpresas, se propone esta lectura:

**Incluibles dentro del alcance (si se mantiene simple y sin cuentas de usuario):**
- Cambio de divisa (MXN/USD) **solo visual** (con tasa fija o tasa diaria simple)
- Cambio de unidades (m²/ft²) **visual**

**Adicional (posible reajuste):**
- Comparador de propiedades: **+150 a +350 USD**
- Favoritos con usuarios registrados (login, persistencia, GDPR/privacidad): **+250 a +600 USD**
- Mapas “bien configurados” (API keys, geocoding, pin exacto):
  - si solo es mapa en listing con coordenadas existentes: **+100 a +250 USD**
  - si requiere geocoding/normalización de direcciones: **+200 a +500 USD**
- Búsqueda/filtros avanzados (multi-filtros, rangos, performance, indexación): **+250 a +800 USD**

### 6.3 Funcionalidades para validar en segunda revisión
**Adicional (posible reajuste):**
- Búsqueda por colonia con mapa clickeable (geodata + UX): **+300 a +900 USD**
- “Notifícame” (alertas): requiere usuarios o al menos suscripción por email + lógica de matching + envíos programados.
  - Estimación: **+250 a +700 USD** (dependiendo de si hay login, segmentación y frecuencia)

---

## 7) Costos externos / recurrentes (solo claridad)

Fuera de alcance (pagos a terceros), aunque SMP ya cubra algunos:

- **Hosting** (shared/VPS/managed)
- **Dominio/DNS** (si aplica renovación)
- **CDN** (Cloudflare u otro, si se requiere)
- **APIs de mapas** (Google Maps Platform u otra) – según consumo
- **Servicio de emails transaccionales** (si se usa: Mailgun/SES/SendGrid) – según volumen
- **Plan del CRM** (HubSpot/Zoho/Pipedrive, etc.)
- **Servicios de SMS/WhatsApp** (si se agregan) – Twilio/Meta/otros

---

## 8) Soporte + capacitación

### 8.1 ¿Incluyes soporte post-lanzamiento?
Propuesta incluida (recomendada para cierre ordenado):
- **14 días** post-lanzamiento para:
  - corrección de bugs
  - ajustes menores (no nuevos módulos)
  - monitoreo básico

SLA (referencia):
- Crítico (sitio caído): respuesta 24h hábiles
- Alto (funcionalidad principal afectada): 48h hábiles
- Medio/Bajo: 3–5 días hábiles

Soporte mensual continuo puede cotizarse como retainer.

### 8.2 ¿Incluyes capacitación breve + documentación operativa?
Sí.
- Capacitación: 60–90 min (remota) para el equipo.
- Documentación operativa básica: cómo crear/editar contenido, revisar leads, roles y flujo general.

---

## Anexos (para control de alcance)

### A. Supuestos para mantener la tarifa fija
- SMP entrega a tiempo accesos y credenciales.
- Se define 1 fuente principal de propiedades.
- Se limita a las funcionalidades descritas en la cotización vigente.

### B. Proceso para “adicionales”
- Se documenta requerimiento → estimación → aprobación → ejecución.
- Todo adicional queda como **Fase 2** o “change request” con costo/tiempo.

