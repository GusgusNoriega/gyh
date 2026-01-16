<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\Lead;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class IEMarianoMelgarQuoteSeeder extends Seeder
{
    /**
     * Crea:
     * - Usuario cliente (Gustavo Suárez Saavedra)
     * - Lead para institución educativa (I.E. N° 1225 "Mariano Melgar")
     * - Cotización en PEN (S/ 1,700 + IGV 18% = S/ 2,006) para:
     *   - Plan Empresarial completo (S/ 1,400):
     *     - Vistas institucionales (Inicio, Nosotros, Oferta Educativa, Galería/Logros, Noticias/Comunicados, Contacto, FAQ)
     *     - SEO avanzado + Google Analytics
     *     - Integración redes sociales
     *     - Formularios personalizados
     *     - Optimización de velocidad
     *     - Panel de administración
     *     - Hosting y Dominio
     *   - Módulos adicionales (S/ 300):
     *     - Gestión de eventos anuales
     *     - Portal de padres (consulta de notas)
     *     - Portal docente (publicación de resultados de exámenes)
     *     - Dashboard de dirección (monitoreo en tiempo real)
     */
    public function run(): void
    {
        DB::transaction(function () {
            // =========================
            // Datos del cliente / institución
            // =========================
            $clientName = 'Gustavo Suárez Saavedra';
            // Email ficticio solicitado (no existe correo real actualmente)
            $clientEmail = 'gustavo.suarez.saavedra@ie1225-mariano-melgar.pe';
            $clientPhone = '+51952691299';
            $clientAddress = null;

            $institutionName = 'I.E. N° 1225 "Mariano Melgar"';
            $institutionRuc = null;
            $institutionType = 'Institución educativa estatal (Perú)';

            // =========================
            // Moneda: PEN
            // =========================
            $pen = Currency::firstOrCreate(
                ['code' => 'PEN'],
                [
                    'name' => 'Sol Peruano',
                    'symbol' => 'S/',
                    'exchange_rate' => 1.000000,
                    'is_base' => true,
                ]
            );

            // =========================
            // Usuario cliente
            // =========================
            $clientUser = User::updateOrCreate(
                ['email' => $clientEmail],
                [
                    'name' => $clientName,
                    'password' => Hash::make('12345678'),
                    'email_verified_at' => now(),
                ]
            );

            // =========================
            // Lead (institución)
            // =========================
            Lead::updateOrCreate(
                [
                    'email' => $clientEmail,
                    'company_name' => $institutionName,
                ],
                [
                    'name' => $clientName,
                    'phone' => $clientPhone,
                    'is_company' => true,
                    'company_ruc' => $institutionRuc,
                    'project_type' => 'Web institucional (Plan Empresarial) + Eventos + Portal académico (notas/exámenes) + Dashboard dirección + Panel admin + Hosting/Dominio',
                    'budget_up_to' => 1700,
                    'message' => "Cotización solicitada para: {$institutionName}. Tipo: {$institutionType}. Alcance: web institucional + módulos académicos (notas/exámenes) + monitoreo para dirección.",
                    'status' => 'new',
                    'source' => 'seed',
                    'notes' => 'Lead creado automáticamente desde seeder para generar cotización formal (institución educativa).',
                ]
            );

            // =========================
            // Usuario creador (interno)
            // =========================
            $createdBy = User::where('email', 'gusgusnoriega@gmail.com')->first()
                ?? User::first()
                ?? $clientUser;

            // =========================
            // Cotización (PEN)
            // =========================
            $quoteNumber = 'COT-IE1225-MARIANOMELGAR-WEB-0001';

            $quote = Quote::withTrashed()->updateOrCreate(
                ['quote_number' => $quoteNumber],
                [
                    'user_id' => $clientUser->id,
                    'created_by' => $createdBy?->id,
                    'title' => 'Cotización: Web institucional (Plan Empresarial) + Eventos + Portal académico (notas/exámenes) + Dashboard dirección - I.E. N° 1225 "Mariano Melgar"',
                    'description' => "Desarrollo de un sitio web institucional para {$institutionName} ({$institutionType}).\n\n" .
                        "PLAN EMPRESARIAL - Vistas incluidas:\n" .
                        "- Inicio (Home)\n" .
                        "- Nosotros (reseña, misión, visión, valores)\n" .
                        "- Oferta Educativa / Servicios Educativos\n" .
                        "- Galería / Infraestructura y logros\n" .
                        "- Noticias y Comunicados (tipo blog)\n" .
                        "- Contacto\n" .
                        "- FAQ (Preguntas frecuentes)\n\n" .
                        "Características del Plan Empresarial:\n" .
                        "- Diseño responsive (móvil/tablet/desktop)\n" .
                        "- SEO avanzado + Google Analytics\n" .
                        "- Galería avanzada de imágenes\n" .
                        "- Integración con redes sociales\n" .
                        "- Formularios personalizados\n" .
                        "- Optimización de velocidad\n" .
                        "- Panel de administración para editar contenido\n" .
                        "- Hosting + Dominio + SSL\n" .
                        "- Soporte prioritario 30 días\n\n" .
                        "MÓDULOS ADICIONALES (académicos y de gestión):\n" .
                        "- Eventos institucionales (calendario y publicaciones)\n" .
                        "- Portal de padres: consulta de notas\n" .
                        "- Portal docente: carga/publicación de resultados de exámenes\n" .
                        "- Dashboard de dirección: monitoreo en tiempo real (actividad, publicaciones y reportes)\n\n" .
                        "Tiempo estimado de entrega: 3-4 semanas (sin imprevistos y con entrega oportuna de accesos/contenido).",
                    'currency_id' => $pen->id,
                    'tax_rate' => 18,
                    'discount_amount' => 0,
                    'status' => 'draft',
                    'valid_until' => now()->addDays(15)->toDateString(),
                    'estimated_start_date' => now()->addDays(1)->toDateString(),
                    'notes' => "Requisitos para iniciar:\n" .
                        "- Logo institucional (si existe) y lineamientos de colores (si aplica).\n" .
                        "- Textos base para: reseña histórica, misión, visión, valores, oferta educativa y datos de contacto.\n" .
                        "- Imágenes (infraestructura, actividades, eventos) en buena calidad.\n" .
                        "- Redes sociales oficiales (Facebook/Instagram/YouTube) si se desea integración.\n" .
                        "- Para portal académico: estructura de grados/secciones, listado inicial de alumnos (o formato), criterios de autenticación y responsables por rol (padres/docentes/dirección).\n\n" .
                        "Entregables:\n" .
                        "- Sitio web institucional publicado con SSL, sitemap y robots configurado.\n" .
                        "- Panel admin para actualizar páginas, galería y noticias/comunicados.\n" .
                        "- Módulo de eventos institucionales.\n" .
                        "- Portal de padres (consulta de notas) y portal docente (publicación de resultados).\n" .
                        "- Dashboard de dirección con reportes y monitoreo en tiempo real.\n" .
                        "- Manual + capacitación de uso.\n\n" .
                        "Nota de seguridad y privacidad:\n" .
                        "- El portal académico manejará información sensible; se implementarán controles de acceso por roles, buenas prácticas de seguridad y trazabilidad básica (logs).",
                    'terms_conditions' => "Condiciones comerciales:\n" .
                        "- Moneda: PEN (S/).\n" .
                        "- Subtotal: S/ 1,700.00\n" .
                        "  • Plan Empresarial (desarrollo web institucional): S/ 1,400.00\n" .
                        "  • Módulos adicionales (eventos + portal académico + dashboard dirección): S/ 300.00\n" .
                        "- IGV (18%): S/ 306.00\n" .
                        "- Total: S/ 2,006.00\n" .
                        "- Forma de pago: 50% al iniciar el proyecto (S/ 1,003.00) / 50% al culminar el proyecto y previo a la publicación final (S/ 1,003.00).\n" .
                        "- Hosting + dominio: se incluye la gestión y el costo estimado dentro de esta cotización; el pago anual de hosting y dominio puede ascender a S/ 350 (según proveedor/plan). Renovaciones anuales posteriores corren por cuenta de la institución.\n" .
                        "- Propiedad del código y entregables: 100% del código fuente y entregables serán del cliente.\n" .
                        "- Alcance: incluye Plan Empresarial + módulos descritos. Integraciones extra (MINEDU/SIAGIE u otros) o ampliaciones funcionales se cotizan por separado.\n" .
                        "- Plazo estimado: 3-4 semanas, sujeto a entrega de contenido y aprobaciones sin demoras.",
                    'client_name' => $clientName,
                    'client_ruc' => $institutionRuc,
                    'client_email' => $clientEmail,
                    'client_phone' => $clientPhone,
                    'client_address' => $clientAddress,
                ]
            );

            if (method_exists($quote, 'trashed') && $quote->trashed()) {
                $quote->restore();
            }

            // Reemplazar items y tareas (idempotente)
            $quote->items()->delete();

            // Total items (subtotal) = 1700.00
            // Plan Empresarial = 1400.00
            // Módulos adicionales = 300.00
            // IGV (18%) = 306.00
            // Total con IGV = 2006.00
            $items = [
                // =============================================
                // PLAN EMPRESARIAL - VISTAS (S/ 1,400 total)
                // =============================================
                [
                    'name' => 'Vista: Inicio (Home)',
                    'description' => "Diseño e implementación de la vista de Inicio para {$institutionName}: propuesta institucional, secciones clave, llamados a la acción, accesos rápidos y secciones destacadas.",
                    'quantity' => 1,
                    'unit' => 'vista',
                    'unit_price' => 200,
                    'tasks' => [
                        ['name' => 'Arquitectura de secciones + UI kit', 'description' => 'Definir estructura: hero, secciones institucionales, accesos rápidos, noticias destacadas, CTA y componentes reutilizables.', 'duration_value' => 3, 'duration_unit' => 'hours'],
                        ['name' => 'Maquetación responsive', 'description' => 'Implementar layout responsive (móvil/tablet/desktop) con estilos modernos y legibles para sitio institucional.', 'duration_value' => 7, 'duration_unit' => 'hours'],
                        ['name' => 'Integración con contenido editable (panel)', 'description' => 'Conectar secciones a campos editables: textos, imágenes, enlaces y orden.', 'duration_value' => 3, 'duration_unit' => 'hours'],
                        ['name' => 'SEO on-page + metatags', 'description' => 'Implementar títulos, descripciones, Open Graph y metadatos para posicionamiento.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                        ['name' => 'QA + performance', 'description' => 'Verificar navegación, enlaces, pesos de imágenes y pruebas cross-device.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                    ],
                ],
                [
                    'name' => 'Vista: Nosotros (Institucional)',
                    'description' => 'Vista institucional para presentar reseña histórica, misión, visión, valores, equipo directivo y secciones relevantes.',
                    'quantity' => 1,
                    'unit' => 'vista',
                    'unit_price' => 140,
                    'tasks' => [
                        ['name' => 'Estructura de contenido institucional', 'description' => 'Definir secciones: reseña histórica, misión/visión/valores, equipo directivo, reseñas/alianzas si aplica.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                        ['name' => 'Maquetación + estilos', 'description' => 'Implementación UI aplicando tipografías y estilo visual institucional.', 'duration_value' => 5, 'duration_unit' => 'hours'],
                        ['name' => 'Integración con panel', 'description' => 'Vincular contenido a administración (edición de textos e imágenes).', 'duration_value' => 2, 'duration_unit' => 'hours'],
                        ['name' => 'SEO on-page', 'description' => 'Metatags, títulos y descripciones optimizadas.', 'duration_value' => 1, 'duration_unit' => 'hours'],
                        ['name' => 'QA', 'description' => 'Revisión de contenido, enlaces y accesibilidad básica.', 'duration_value' => 1, 'duration_unit' => 'hours'],
                    ],
                ],
                [
                    'name' => 'Vista: Oferta Educativa / Servicios Educativos',
                    'description' => 'Página para presentar niveles, áreas, talleres, servicios educativos, horarios y propuestas pedagógicas (según información provista).',
                    'quantity' => 1,
                    'unit' => 'vista',
                    'unit_price' => 150,
                    'tasks' => [
                        ['name' => 'Definición de estructura', 'description' => 'Organizar secciones por niveles/servicios, descripciones, beneficios, requisitos y CTAs.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                        ['name' => 'Maquetación con cards/secciones', 'description' => 'Diseño de bloques informativos con jerarquía clara para padres y comunidad.', 'duration_value' => 5, 'duration_unit' => 'hours'],
                        ['name' => 'Integración con panel admin', 'description' => 'Campos editables por sección (título, descripción, imagen, orden).', 'duration_value' => 3, 'duration_unit' => 'hours'],
                        ['name' => 'SEO on-page', 'description' => 'Optimización de títulos y descripciones.', 'duration_value' => 1, 'duration_unit' => 'hours'],
                        ['name' => 'QA', 'description' => 'Pruebas de navegación y visualización responsive.', 'duration_value' => 1, 'duration_unit' => 'hours'],
                    ],
                ],
                [
                    'name' => 'Vista: Galería / Infraestructura y Logros',
                    'description' => 'Galería avanzada de infraestructura, actividades y logros institucionales, con organización por categorías y vista de detalle.',
                    'quantity' => 1,
                    'unit' => 'vista',
                    'unit_price' => 180,
                    'tasks' => [
                        ['name' => 'Definición de categorías', 'description' => 'Definir taxonomías (infraestructura, actividades, logros, eventos pasados, etc.).', 'duration_value' => 2, 'duration_unit' => 'hours'],
                        ['name' => 'Maquetación de galería + grid responsive', 'description' => 'Grid de imágenes con filtros, paginación y presentación cuidada.', 'duration_value' => 6, 'duration_unit' => 'hours'],
                        ['name' => 'Vista detalle', 'description' => 'Página individual con galería, descripción y metadatos.', 'duration_value' => 4, 'duration_unit' => 'hours'],
                        ['name' => 'Integración con panel (CRUD galería)', 'description' => 'Crear/editar/eliminar entradas, subir imágenes, asignar categorías y ordenar.', 'duration_value' => 4, 'duration_unit' => 'hours'],
                        ['name' => 'SEO + optimización de imágenes', 'description' => 'Alt texts, lazy loading, compresión de imágenes.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                        ['name' => 'QA', 'description' => 'Pruebas de filtros, navegación y carga de imágenes.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                    ],
                ],
                [
                    'name' => 'Vista: Noticias y Comunicados (listado + detalle)',
                    'description' => 'Sección tipo blog para publicar comunicados, noticias, avisos y documentos informativos. Incluye listado y vista de publicación individual.',
                    'quantity' => 1,
                    'unit' => 'vista',
                    'unit_price' => 180,
                    'tasks' => [
                        ['name' => 'Modelo de contenido (categorías/etiquetas)', 'description' => 'Definir categorías y tags (comunicados, avisos, noticias, etc.) con slugs amigables.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                        ['name' => 'Maquetación listado de publicaciones', 'description' => 'Cards con imagen, título, extracto, fecha, categoría y paginación.', 'duration_value' => 4, 'duration_unit' => 'hours'],
                        ['name' => 'Filtros por categoría/etiqueta', 'description' => 'Implementación de filtros para navegación por contenido.', 'duration_value' => 3, 'duration_unit' => 'hours'],
                        ['name' => 'Vista de publicación individual', 'description' => 'Diseño de lectura optimizado con CTAs y contenido relacionado.', 'duration_value' => 4, 'duration_unit' => 'hours'],
                        ['name' => 'Integración con panel (CRUD publicaciones)', 'description' => 'Crear/editar/eliminar publicaciones, gestión de categorías/etiquetas, borradores y publicación.', 'duration_value' => 4, 'duration_unit' => 'hours'],
                        ['name' => 'SEO avanzado', 'description' => 'Schema Article, Open Graph por post, titles/descriptions dinámicos.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                        ['name' => 'QA', 'description' => 'Pruebas de navegación, filtros, paginación y legibilidad.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                    ],
                ],
                [
                    'name' => 'Vista: Contacto',
                    'description' => "Vista de contacto con datos de la institución, formulario personalizado con validaciones y enlaces a redes sociales de {$institutionName}.",
                    'quantity' => 1,
                    'unit' => 'vista',
                    'unit_price' => 120,
                    'tasks' => [
                        ['name' => 'Maquetación de contacto', 'description' => 'Layout con datos de contacto, horarios y enlaces a redes sociales.', 'duration_value' => 3, 'duration_unit' => 'hours'],
                        ['name' => 'Formulario personalizado', 'description' => 'Formulario con validaciones y anti-spam básico (honeypot).', 'duration_value' => 4, 'duration_unit' => 'hours'],
                        ['name' => 'Integración con redes sociales', 'description' => 'Enlaces/íconos destacados a perfiles de redes sociales.', 'duration_value' => 1, 'duration_unit' => 'hours'],
                        ['name' => 'Notificaciones por email', 'description' => 'Configuración de envío de mensajes por email + confirmación al visitante.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                        ['name' => 'Contenido editable desde panel', 'description' => 'Datos de contacto, horarios y redes editables.', 'duration_value' => 1, 'duration_unit' => 'hours'],
                        ['name' => 'QA + pruebas end-to-end', 'description' => 'Verificar envío, validaciones y confirmaciones.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                    ],
                ],
                [
                    'name' => 'Vista: FAQ (Preguntas Frecuentes)',
                    'description' => 'Página de preguntas frecuentes para padres y comunidad educativa (matrícula, horarios, requisitos, trámites, etc.).',
                    'quantity' => 1,
                    'unit' => 'vista',
                    'unit_price' => 90,
                    'tasks' => [
                        ['name' => 'Estructura de FAQ (categorías)', 'description' => 'Organizar preguntas por categorías (matrícula, documentación, horarios, trámites).', 'duration_value' => 1, 'duration_unit' => 'hours'],
                        ['name' => 'Maquetación acordeón/expandible', 'description' => 'Diseño de FAQ con acordeones expandibles y categorías.', 'duration_value' => 4, 'duration_unit' => 'hours'],
                        ['name' => 'Integración con panel (CRUD FAQ)', 'description' => 'Gestión de preguntas/respuestas: crear, editar, ordenar y activar/desactivar.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                        ['name' => 'Schema FAQ para SEO', 'description' => 'Implementar structured data FAQPage para rich snippets en Google.', 'duration_value' => 1, 'duration_unit' => 'hours'],
                        ['name' => 'QA', 'description' => 'Pruebas de interacción y responsive.', 'duration_value' => 1, 'duration_unit' => 'hours'],
                    ],
                ],
                // =============================================
                // SEO AVANZADO + GOOGLE ANALYTICS
                // =============================================
                [
                    'name' => 'SEO Avanzado + Google Analytics',
                    'description' => 'Optimización SEO técnica completa + configuración de Google Analytics 4 y verificación en Search Console.',
                    'quantity' => 1,
                    'unit' => 'servicio',
                    'unit_price' => 120,
                    'tasks' => [
                        ['name' => 'Estrategia SEO (keywords + arquitectura)', 'description' => 'Definir keywords institucionales y jerarquía H1/H2 por vista.', 'duration_value' => 4, 'duration_unit' => 'hours'],
                        ['name' => 'Metadatos avanzados + OpenGraph', 'description' => 'Titles/descriptions por vista, canonical, OG/Twitter cards.', 'duration_value' => 3, 'duration_unit' => 'hours'],
                        ['name' => 'Schema.org (structured data)', 'description' => 'Implementar schema Organization y contenido (Article/FAQ).', 'duration_value' => 3, 'duration_unit' => 'hours'],
                        ['name' => 'Sitemap + robots + Search Console', 'description' => 'Configurar sitemap.xml, robots.txt y verificación.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                        ['name' => 'Configuración de Google Analytics 4', 'description' => 'Implementación de GA4 y eventos básicos (conversiones de formulario).', 'duration_value' => 3, 'duration_unit' => 'hours'],
                        ['name' => 'Optimización de velocidad', 'description' => 'Core Web Vitals: lazy loading, compresión, minificación y caché básico.', 'duration_value' => 3, 'duration_unit' => 'hours'],
                    ],
                ],
                // =============================================
                // PANEL DE ADMINISTRACIÓN + CAPACITACIÓN
                // =============================================
                [
                    'name' => 'Panel de Administración + Capacitación + Manual',
                    'description' => 'Panel para gestionar todo el contenido institucional: páginas, galería, noticias/comunicados, FAQ y datos de contacto. Incluye capacitación y manual.',
                    'quantity' => 1,
                    'unit' => 'módulo',
                    'unit_price' => 130,
                    'tasks' => [
                        ['name' => 'Accesos y seguridad del panel', 'description' => 'Configuración de usuarios, credenciales seguras y protección de rutas administrativas.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                        ['name' => 'Gestión de contenido general', 'description' => 'Módulos para editar contenido de Inicio, Nosotros, Oferta Educativa, Contacto y FAQ.', 'duration_value' => 5, 'duration_unit' => 'hours'],
                        ['name' => 'Módulo de galería', 'description' => 'CRUD de galería con categorías y ordenamiento.', 'duration_value' => 4, 'duration_unit' => 'hours'],
                        ['name' => 'Módulo de noticias/comunicados', 'description' => 'CRUD de publicaciones, categorías, etiquetas, borradores y publicación.', 'duration_value' => 4, 'duration_unit' => 'hours'],
                        ['name' => 'Gestión de media (imágenes)', 'description' => 'Biblioteca de medios para subir, organizar y seleccionar imágenes.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                        ['name' => 'Capacitación (sesión remota)', 'description' => 'Sesión de capacitación sobre uso del panel.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                        ['name' => 'Manual de uso (PDF)', 'description' => 'Documento paso a paso con capturas de pantalla.', 'duration_value' => 3, 'duration_unit' => 'hours'],
                        ['name' => 'QA final del panel', 'description' => 'Pruebas de flujos principales y ajustes menores.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                    ],
                ],
                // =============================================
                // HOSTING Y DOMINIO
                // =============================================
                [
                    'name' => 'Hosting y Dominio (compra, configuración, publicación)',
                    'description' => 'Gestión de compra/registro de dominio y hosting, configuración técnica y despliegue para dejar el sitio publicado con SSL y DNS correctamente configurado.',
                    'quantity' => 1,
                    'unit' => 'servicio',
                    'unit_price' => 90,
                    'tasks' => [
                        ['name' => 'Selección de proveedor + compra', 'description' => 'Selección del plan de hosting y registro de dominio acorde a un sitio institucional.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                        ['name' => 'Configuración inicial de hosting + SSL', 'description' => 'Preparación del hosting, SSL (Let\'s Encrypt), variables de entorno y servidor.', 'duration_value' => 3, 'duration_unit' => 'hours'],
                        ['name' => 'Sincronización dominio ↔ hosting (DNS)', 'description' => 'Configuración de registros DNS (A, CNAME), verificación de propagación, redirecciones www/no-www.', 'duration_value' => 3, 'duration_unit' => 'hours'],
                        ['name' => 'Deploy + verificación final', 'description' => 'Despliegue en producción y verificación de formularios, rutas y performance básica.', 'duration_value' => 4, 'duration_unit' => 'hours'],
                    ],
                ],
                // =============================================
                // MÓDULOS ADICIONALES (S/ 300 total)
                // =============================================
                [
                    'name' => 'Módulo adicional: Eventos institucionales (calendario anual)',
                    'description' => 'Gestión y publicación de eventos durante el año: listado, calendario, detalle de evento y visibilidad pública (según permisos).',
                    'quantity' => 1,
                    'unit' => 'módulo',
                    'unit_price' => 80,
                    'tasks' => [
                        ['name' => 'Modelo de datos de eventos', 'description' => 'Definir campos: título, fecha/hora, ubicación, descripción, imagen, estado y etiquetas.', 'duration_value' => 3, 'duration_unit' => 'hours'],
                        ['name' => 'Vista pública (listado + detalle)', 'description' => 'Crear páginas para ver próximos eventos y detalle.', 'duration_value' => 4, 'duration_unit' => 'hours'],
                        ['name' => 'CRUD en panel + permisos', 'description' => 'Crear/editar/eliminar eventos desde el panel con control de acceso.', 'duration_value' => 4, 'duration_unit' => 'hours'],
                        ['name' => 'QA', 'description' => 'Pruebas de fechas, orden, publicación y responsive.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                    ],
                ],
                [
                    'name' => 'Módulo adicional: Portal de padres (consulta de notas)',
                    'description' => 'Acceso seguro para padres/tutores para consultar notas por alumno/periodo (según estructura entregada por la institución).',
                    'quantity' => 1,
                    'unit' => 'módulo',
                    'unit_price' => 90,
                    'tasks' => [
                        ['name' => 'Definición de roles y acceso', 'description' => 'Roles mínimos: padre/tutor, docente, dirección. Reglas de acceso por alumno y periodo.', 'duration_value' => 3, 'duration_unit' => 'hours'],
                        ['name' => 'Pantallas de consulta', 'description' => 'Vista de login y vista de consulta de notas con filtros por periodo/curso.', 'duration_value' => 6, 'duration_unit' => 'hours'],
                        ['name' => 'Seguridad y trazabilidad básica', 'description' => 'Buenas prácticas: validaciones, sesiones, y registros básicos de actividad.', 'duration_value' => 3, 'duration_unit' => 'hours'],
                        ['name' => 'QA', 'description' => 'Pruebas de permisos (solo ver notas correspondientes) y escenarios comunes.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                    ],
                ],
                [
                    'name' => 'Módulo adicional: Portal docente (publicación de resultados de exámenes)',
                    'description' => 'Interfaz para docentes para registrar/publicar resultados de evaluaciones por sección/curso y visibilidad controlada para padres.',
                    'quantity' => 1,
                    'unit' => 'módulo',
                    'unit_price' => 70,
                    'tasks' => [
                        ['name' => 'Modelo de evaluaciones/resultados', 'description' => 'Definir estructura mínima: examen, curso, sección, fecha y calificaciones.', 'duration_value' => 3, 'duration_unit' => 'hours'],
                        ['name' => 'Pantallas de carga/publicación', 'description' => 'Formularios para registrar resultados y control de estado (borrador/publicado).', 'duration_value' => 5, 'duration_unit' => 'hours'],
                        ['name' => 'Reglas de publicación', 'description' => 'Permisos para que solo docentes autorizados publiquen resultados de su sección/curso.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                        ['name' => 'QA', 'description' => 'Pruebas de flujos: guardar, editar, publicar y verificación de permisos.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                    ],
                ],
                [
                    'name' => 'Módulo adicional: Dashboard de dirección (monitoreo en tiempo real)',
                    'description' => 'Panel de dirección para monitorear en tiempo real actividad y registros: publicaciones, eventos, resultados y avances.',
                    'quantity' => 1,
                    'unit' => 'módulo',
                    'unit_price' => 60,
                    'tasks' => [
                        ['name' => 'Definición de KPIs y reportes', 'description' => 'Definir indicadores: cantidad de resultados publicados, eventos próximos, últimas actualizaciones y actividad por rol.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                        ['name' => 'Dashboard (UI)', 'description' => 'Pantalla con tarjetas, tablas y filtros básicos (fecha/periodo).', 'duration_value' => 4, 'duration_unit' => 'hours'],
                        ['name' => 'Actualización en tiempo real', 'description' => 'Refresco automático/consultas periódicas para ver cambios sin recargar (según arquitectura).', 'duration_value' => 3, 'duration_unit' => 'hours'],
                        ['name' => 'QA', 'description' => 'Pruebas de acceso (solo dirección) y consistencia de datos.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                    ],
                ],
            ];

            foreach ($items as $index => $itemData) {
                $tasks = $itemData['tasks'] ?? [];
                unset($itemData['tasks']);

                $item = $quote->items()->create(array_merge($itemData, [
                    'sort_order' => $index,
                    'discount_percent' => 0,
                ]));

                foreach ($tasks as $tIndex => $taskData) {
                    $item->tasks()->create(array_merge($taskData, [
                        'sort_order' => $tIndex,
                    ]));
                }
            }

            // Recalcular totales
            $quote->load('items');
            $quote->calculateTotals();
            $quote->save();
        });
    }
}

