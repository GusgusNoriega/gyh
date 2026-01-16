<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\Lead;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class YieldSteelDevelopmentQuoteSeeder extends Seeder
{
    /**
     * Crea:
     * - Usuario cliente (Alexander Bajaña Otero)
     * - Lead de empresa (YIELDSTEELDEVELOPMENT / RUC: 201303030303)
     * - Cotización en PEN (S/ 1,900 + IGV 18% = S/ 2,242) para una web empresarial
     *   del rubro agrícola que incluye:
     *   - Plan Empresarial completo (S/ 1,400):
     *     - 6-8 vistas (Inicio, Nosotros, Servicios, Proyectos, Blog, Contacto, FAQ)
     *     - SEO avanzado + Google Analytics
     *     - Galería avanzada de imágenes/proyectos
     *     - Integración redes sociales
     *     - Formularios personalizados
     *     - Optimización de velocidad
     *     - Panel de administración
     *     - Hosting y Dominio
     *   - Identidad de marca (S/ 500):
     *     - 3 presentaciones de logo (cliente elige 1)
     *     - Tipografías corporativas
     *     - Paleta de colores
     *     - Manual de identidad de marca
     * Tiempo máximo: 3-4 semanas (sin imprevistos).
     */
    public function run(): void
    {
        DB::transaction(function () {
            // =========================
            // Datos del cliente/empresa
            // =========================
            $clientName = 'Alexander Bajaña Otero';
            $clientEmail = 'alexander.bajana0002@hotmail.com';
            $clientPhone = '+51963170987';
            $clientAddress = null;

            $companyName = 'YIELDSTEELDEVELOPMENT';
            $companyRuc = '201303030303';
            $companyIndustry = 'Sector agrícola';

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
            // Lead (empresa)
            // =========================
            Lead::updateOrCreate(
                [
                    'email' => $clientEmail,
                    'company_name' => $companyName,
                ],
                [
                    'name' => $clientName,
                    'phone' => $clientPhone,
                    'is_company' => true,
                    'company_ruc' => $companyRuc,
                    'project_type' => 'Página web empresarial + SEO avanzado + Identidad de marca + Panel de administración + Hosting y Dominio',
                    'budget_up_to' => 1900,
                    'message' => "Cotización solicitada para: {$companyName} (RUC: {$companyRuc}). Rubro: {$companyIndustry}. Plan: Web empresarial + Identidad de marca.",
                    'status' => 'new',
                    'source' => 'seed',
                    'notes' => 'Lead creado automáticamente desde seeder para generar cotización del plan empresarial con identidad de marca.',
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
            $quoteNumber = 'COT-201303030303-WEBEMP-0001';

            $quote = Quote::withTrashed()->updateOrCreate(
                ['quote_number' => $quoteNumber],
                [
                    'user_id' => $clientUser->id,
                    'created_by' => $createdBy?->id,
                    'title' => 'Cotización: Página web empresarial + SEO avanzado + Identidad de marca + Hosting/Dominio + Panel admin (YIELDSTEELDEVELOPMENT)',
                    'description' => "Desarrollo de una página web empresarial profesional para {$companyName} ({$companyIndustry}).\n\n" .
                        "PLAN EMPRESARIAL - Vistas incluidas:\n" .
                        "- Inicio (Home)\n" .
                        "- Nosotros\n" .
                        "- Servicios\n" .
                        "- Nuestros Proyectos/Galería\n" .
                        "- Blog\n" .
                        "- Contacto\n" .
                        "- FAQ (Preguntas frecuentes)\n\n" .
                        "Características del Plan Empresarial:\n" .
                        "- Diseño responsive (móvil/tablet/desktop)\n" .
                        "- SEO avanzado + Google Analytics\n" .
                        "- Galería avanzada de imágenes/proyectos\n" .
                        "- Integración con redes sociales\n" .
                        "- Formularios personalizados\n" .
                        "- Optimización de velocidad\n" .
                        "- Panel de administración para editar contenido\n" .
                        "- Hosting + Dominio + SSL\n" .
                        "- Soporte prioritario 30 días\n\n" .
                        "IDENTIDAD DE MARCA (adicional):\n" .
                        "- 3 presentaciones de logo (el cliente elige 1)\n" .
                        "- Definición de tipografías corporativas\n" .
                        "- Paleta de colores institucional\n" .
                        "- Manual de identidad de marca\n\n" .
                        "Tiempo estimado de entrega: 3-4 semanas (sin imprevistos y con entrega oportuna de accesos/contenido).",
                    'currency_id' => $pen->id,
                    'tax_rate' => 18,
                    'discount_amount' => 0,
                    'status' => 'draft',
                    'valid_until' => now()->addDays(15)->toDateString(),
                    'estimated_start_date' => now()->addDays(1)->toDateString(),
                    'notes' => "Requisitos para iniciar:\n" .
                        "- Definición del nombre de dominio deseado (2-3 opciones) y datos para compra/registro.\n" .
                        "- Contenido base: textos para Inicio/Nosotros/Servicios, datos de contacto, imágenes del rubro agrícola.\n" .
                        "- Portafolio: lista de proyectos o trabajos realizados (títulos, descripciones, fotos).\n" .
                        "- Información para el blog: temas iniciales o artículos que se desean publicar.\n" .
                        "- Para identidad de marca: referencias de logos que le gusten, colores preferidos (si existen), estilo visual deseado.\n" .
                        "- Palabras clave objetivo para SEO (si existen) y competidores a considerar.\n\n" .
                        "Entregables:\n" .
                        "- Sitio web publicado con certificado SSL, sitemap y robots configurado.\n" .
                        "- Panel admin para actualizar contenido, proyectos y posts.\n" .
                        "- Logo final en formatos editables y para web (AI/EPS/PNG/SVG).\n" .
                        "- Manual de identidad de marca (PDF).\n" .
                        "- Manual + capacitación de uso del sitio web.\n",
                    'terms_conditions' => "Condiciones comerciales:\n" .
                        "- Moneda: PEN (S/).\n" .
                        "- Subtotal: S/ 1,900.00\n" .
                        "  • Plan Empresarial (desarrollo web): S/ 1,400.00\n" .
                        "  • Identidad de marca: S/ 500.00\n" .
                        "- IGV (18%): S/ 342.00\n" .
                        "- Total: S/ 2,242.00\n" .
                        "- Forma de pago: 50% al iniciar el proyecto (S/ 1,121.00) / 50% al culminar el proyecto y previo a la publicación final (S/ 1,121.00).\n" .
                        "- Hosting + dominio: se incluye la gestión y el costo estimado dentro de esta cotización; el pago anual de hosting y dominio puede ascender a S/ 350 (según proveedor/plan). Renovaciones anuales posteriores corren por cuenta del cliente.\n" .
                        "- Propiedad del código y diseños: 100% del código fuente, logo y material gráfico serán del cliente.\n" .
                        "- Identidad de marca: Se presentarán 3 propuestas de logo; el cliente elige 1 y se realizan hasta 2 rondas de ajustes menores.\n" .
                        "- Alcance: incluye panel de administración, capacitación, manual de uso y manual de identidad de marca. Cambios mayores o nuevos módulos fuera del alcance se cotizan por separado.\n" .
                        "- Plazo estimado: 3-4 semanas, sujeto a entrega de contenido y aprobaciones sin demoras.",
                    'client_name' => $clientName,
                    'client_ruc' => $companyRuc,
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

            // Total items (subtotal) = 1900.00
            // Plan Empresarial = 1400.00
            // Identidad de marca = 500.00
            // IGV (18%) = 342.00
            // Total con IGV = 2242.00
            $items = [
                // =============================================
                // IDENTIDAD DE MARCA (S/ 500)
                // =============================================
                [
                    'name' => 'Identidad de Marca Completa',
                    'description' => "Desarrollo de identidad visual corporativa para {$companyName} del sector agrícola. Incluye diseño de logo (3 propuestas), tipografías, paleta de colores y manual de identidad de marca.",
                    'quantity' => 1,
                    'unit' => 'servicio',
                    'unit_price' => 500,
                    'tasks' => [
                        [
                            'name' => 'Brief creativo e investigación',
                            'description' => 'Reunión para entender la visión del negocio, valores, público objetivo, competencia y preferencias visuales. Investigación del sector agrícola para referencias de diseño.',
                            'duration_value' => 4,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Diseño de 3 propuestas de logo',
                            'description' => 'Creación de 3 conceptos de logo diferentes, cada uno con su justificación y aplicación básica (versiones a color, escala de grises y monocromo).',
                            'duration_value' => 12,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Presentación de propuestas al cliente',
                            'description' => 'Sesión de presentación de las 3 propuestas de logo con mockups de aplicación. El cliente elige 1 propuesta para desarrollo final.',
                            'duration_value' => 2,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Ajustes y refinamiento del logo elegido',
                            'description' => 'Hasta 2 rondas de ajustes menores sobre la propuesta elegida (colores, proporciones, detalles tipográficos).',
                            'duration_value' => 4,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Definición de tipografías corporativas',
                            'description' => 'Selección de tipografías primaria y secundaria que complementen el logo y reflejen la identidad de la marca agrícola.',
                            'duration_value' => 2,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Definición de paleta de colores',
                            'description' => 'Desarrollo de paleta de colores institucional (colores primarios, secundarios y complementarios) con códigos HEX, RGB y CMYK.',
                            'duration_value' => 2,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Elaboración del Manual de Identidad de Marca',
                            'description' => 'Documento PDF con lineamientos de uso del logo (versiones, áreas de respeto, usos incorrectos), tipografías, colores y aplicaciones básicas.',
                            'duration_value' => 6,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Entrega de archivos finales',
                            'description' => 'Entrega del logo en formatos editables (AI, EPS, PDF) y para web (PNG, SVG, JPG), tipografías (si son libres de uso) y manual de identidad.',
                            'duration_value' => 2,
                            'duration_unit' => 'hours',
                        ],
                    ],
                ],
                // =============================================
                // PLAN EMPRESARIAL - VISTAS (S/ 1,400 total)
                // =============================================
                [
                    'name' => 'Vista: Inicio (Home)',
                    'description' => "Diseño e implementación de la vista de Inicio orientada al sector agrícola: propuesta de valor, servicios destacados, proyectos, CTA y secciones clave para {$companyName}.",
                    'quantity' => 1,
                    'unit' => 'vista',
                    'unit_price' => 200,
                    'tasks' => [
                        ['name' => 'Arquitectura de secciones + UI kit', 'description' => 'Definir estructura (hero con slogan agrícola, servicios, proyectos destacados, beneficios, CTA) y componentes reutilizables.', 'duration_value' => 3, 'duration_unit' => 'hours'],
                        ['name' => 'Maquetación responsive', 'description' => 'Implementar layout responsive (móvil/tablet/desktop) aplicando la nueva identidad de marca (colores, tipografías, logo).', 'duration_value' => 7, 'duration_unit' => 'hours'],
                        ['name' => 'Integración con contenido editable (panel)', 'description' => 'Conectar secciones a campos editables: textos, imágenes, enlaces y orden de secciones.', 'duration_value' => 3, 'duration_unit' => 'hours'],
                        ['name' => 'SEO on-page + metatags', 'description' => 'Implementar títulos, descripciones, Open Graph, keywords enfocadas al sector agrícola.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                        ['name' => 'QA + performance', 'description' => 'Verificar navegación, enlaces, pesos de imágenes y pruebas cross-device.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                    ],
                ],
                [
                    'name' => 'Vista: Nosotros',
                    'description' => 'Vista institucional para presentar la empresa agrícola, su historia, misión, visión, valores y equipo.',
                    'quantity' => 1,
                    'unit' => 'vista',
                    'unit_price' => 140,
                    'tasks' => [
                        ['name' => 'Estructura de contenido institucional', 'description' => 'Definir secciones: historia de la empresa, misión/visión/valores, equipo (si aplica), certifications/alianzas del sector agrícola.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                        ['name' => 'Maquetación + estilos con identidad de marca', 'description' => 'Implementación UI aplicando colores, tipografías y logo de la nueva identidad corporativa.', 'duration_value' => 5, 'duration_unit' => 'hours'],
                        ['name' => 'Integración con panel', 'description' => 'Vincular contenido a administración (edición de textos e imágenes).', 'duration_value' => 2, 'duration_unit' => 'hours'],
                        ['name' => 'SEO on-page', 'description' => 'Metatags, títulos y descripciones optimizadas para buscadores.', 'duration_value' => 1, 'duration_unit' => 'hours'],
                        ['name' => 'QA', 'description' => 'Revisión de contenido, enlaces y accesibilidad básica.', 'duration_value' => 1, 'duration_unit' => 'hours'],
                    ],
                ],
                [
                    'name' => 'Vista: Servicios',
                    'description' => 'Página para presentar los servicios del sector agrícola que ofrece la empresa: descripción detallada, beneficios y CTAs.',
                    'quantity' => 1,
                    'unit' => 'vista',
                    'unit_price' => 150,
                    'tasks' => [
                        ['name' => 'Definición de estructura de servicios', 'description' => 'Organizar servicios agrícolas (productos, asesoría, distribución, etc.) con descripciones, íconos y CTAs.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                        ['name' => 'Maquetación con cards/secciones', 'description' => 'Diseño de tarjetas de servicios con imágenes representativas del sector agrícola, descripciones y enlaces a contacto.', 'duration_value' => 5, 'duration_unit' => 'hours'],
                        ['name' => 'Integración con panel admin', 'description' => 'Campos editables para cada servicio (nombre, descripción, imagen, orden).', 'duration_value' => 3, 'duration_unit' => 'hours'],
                        ['name' => 'SEO on-page', 'description' => 'Optimización de títulos y descripciones para servicios agrícolas.', 'duration_value' => 1, 'duration_unit' => 'hours'],
                        ['name' => 'QA', 'description' => 'Pruebas de navegación y visualización responsive.', 'duration_value' => 1, 'duration_unit' => 'hours'],
                    ],
                ],
                [
                    'name' => 'Vista: Nuestros Proyectos/Galería',
                    'description' => 'Galería avanzada de proyectos y trabajos realizados en el sector agrícola, con filtros y vista de detalle.',
                    'quantity' => 1,
                    'unit' => 'vista',
                    'unit_price' => 180,
                    'tasks' => [
                        ['name' => 'Definición de categorías/filtros', 'description' => 'Definir taxonomías para proyectos agrícolas (tipo de cultivo, región, tipo de servicio, etc.).', 'duration_value' => 2, 'duration_unit' => 'hours'],
                        ['name' => 'Maquetación de galería + grid responsive', 'description' => 'Grid de proyectos con imágenes, overlay con información, filtros interactivos y paginación.', 'duration_value' => 6, 'duration_unit' => 'hours'],
                        ['name' => 'Vista detalle de proyecto', 'description' => 'Página individual con galería de imágenes, descripción del proyecto, resultados y CTA a contacto.', 'duration_value' => 4, 'duration_unit' => 'hours'],
                        ['name' => 'Integración con panel (CRUD proyectos)', 'description' => 'Crear/editar/eliminar proyectos, subir imágenes, asignar categorías y ordenar.', 'duration_value' => 4, 'duration_unit' => 'hours'],
                        ['name' => 'SEO + optimización de imágenes', 'description' => 'Alt texts, lazy loading, compresión de imágenes para performance.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                        ['name' => 'QA', 'description' => 'Pruebas de filtros, navegación y carga de imágenes.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                    ],
                ],
                [
                    'name' => 'Vista: Blog (listado + post individual)',
                    'description' => 'Blog para publicar artículos sobre el sector agrícola: noticias, consejos, tendencias. Incluye listado con filtros y vista de post individual.',
                    'quantity' => 1,
                    'unit' => 'vista',
                    'unit_price' => 180,
                    'tasks' => [
                        ['name' => 'Modelo de contenido (categorías/etiquetas)', 'description' => 'Definir estructura de categorías y tags para artículos del sector agrícola (slugs amigables).', 'duration_value' => 2, 'duration_unit' => 'hours'],
                        ['name' => 'Maquetación listado de posts', 'description' => 'Cards de artículos con imagen destacada, título, extracto, fecha, categoría y paginación.', 'duration_value' => 4, 'duration_unit' => 'hours'],
                        ['name' => 'Filtros por categoría/etiqueta', 'description' => 'Implementación de filtros laterales o superiores para navegación del blog.', 'duration_value' => 3, 'duration_unit' => 'hours'],
                        ['name' => 'Vista de post individual', 'description' => 'Diseño de lectura optimizado con imagen destacada, contenido, autor, fecha, posts relacionados y CTAs.', 'duration_value' => 4, 'duration_unit' => 'hours'],
                        ['name' => 'Integración con panel (CRUD posts)', 'description' => 'Crear/editar/eliminar posts, gestión de categorías/etiquetas, borradores y publicación.', 'duration_value' => 4, 'duration_unit' => 'hours'],
                        ['name' => 'SEO avanzado para blog', 'description' => 'Schema Article, breadcrumbs, Open Graph por post, titles/descriptions dinámicos.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                        ['name' => 'QA', 'description' => 'Pruebas de navegación, filtros, paginación y legibilidad.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                    ],
                ],
                [
                    'name' => 'Vista: Contacto',
                    'description' => 'Vista de contacto con datos de la empresa, mapa de ubicación (si aplica), formulario personalizado con validaciones y redes sociales.',
                    'quantity' => 1,
                    'unit' => 'vista',
                    'unit_price' => 120,
                    'tasks' => [
                        ['name' => 'Maquetación de contacto', 'description' => 'Layout con datos de contacto, horarios, ubicación (mapa embebido si aplica) y redes sociales.', 'duration_value' => 3, 'duration_unit' => 'hours'],
                        ['name' => 'Formulario personalizado', 'description' => 'Formulario con campos específicos para el sector agrícola (tipo de consulta, producto de interés, etc.), validaciones y anti-spam.', 'duration_value' => 4, 'duration_unit' => 'hours'],
                        ['name' => 'Integración con redes sociales', 'description' => 'Enlaces/íconos destacados a perfiles de redes sociales de la empresa.', 'duration_value' => 1, 'duration_unit' => 'hours'],
                        ['name' => 'Notificaciones por email', 'description' => 'Configuración de envío de leads por email + confirmación al visitante.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                        ['name' => 'Contenido editable desde panel', 'description' => 'Datos de contacto, horarios y enlaces a redes editables desde el panel.', 'duration_value' => 1, 'duration_unit' => 'hours'],
                        ['name' => 'QA + pruebas end-to-end', 'description' => 'Verificar envío de formulario, validaciones y confirmaciones.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                    ],
                ],
                [
                    'name' => 'Vista: FAQ (Preguntas Frecuentes)',
                    'description' => 'Página de preguntas frecuentes sobre los servicios agrícolas, productos y procesos de la empresa.',
                    'quantity' => 1,
                    'unit' => 'vista',
                    'unit_price' => 90,
                    'tasks' => [
                        ['name' => 'Estructura de FAQ (categorías)', 'description' => 'Organizar preguntas por categorías (servicios, productos, procesos, pagos, etc.).', 'duration_value' => 1, 'duration_unit' => 'hours'],
                        ['name' => 'Maquetación acordeón/expandible', 'description' => 'Diseño de FAQ con acordeones expandibles, buscador opcional y categorías.', 'duration_value' => 4, 'duration_unit' => 'hours'],
                        ['name' => 'Integración con panel (CRUD FAQ)', 'description' => 'Gestión de preguntas/respuestas desde el panel: crear, editar, ordenar y activar/desactivar.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                        ['name' => 'Schema FAQ para SEO', 'description' => 'Implementar structured data FAQPage para rich snippets en Google.', 'duration_value' => 1, 'duration_unit' => 'hours'],
                        ['name' => 'QA', 'description' => 'Pruebas de interacción acordeón y responsive.', 'duration_value' => 1, 'duration_unit' => 'hours'],
                    ],
                ],
                // =============================================
                // SEO AVANZADO + GOOGLE ANALYTICS
                // =============================================
                [
                    'name' => 'SEO Avanzado + Google Analytics',
                    'description' => 'Optimización SEO técnica completa para posicionamiento en buscadores enfocado al sector agrícola + configuración de Google Analytics.',
                    'quantity' => 1,
                    'unit' => 'servicio',
                    'unit_price' => 120,
                    'tasks' => [
                        ['name' => 'Estrategia SEO (keywords + arquitectura)', 'description' => 'Definir keywords principales del sector agrícola, arquitectura de información y jerarquía H1/H2 por vista.', 'duration_value' => 4, 'duration_unit' => 'hours'],
                        ['name' => 'Metadatos avanzados + OpenGraph', 'description' => 'Titles/descriptions por vista, canonical, OG/Twitter cards, imagen social por defecto de la marca.', 'duration_value' => 3, 'duration_unit' => 'hours'],
                        ['name' => 'Schema.org (structured data)', 'description' => 'Implementar schema Organization, LocalBusiness (si aplica), Article para blog, FAQPage, BreadcrumbList.', 'duration_value' => 3, 'duration_unit' => 'hours'],
                        ['name' => 'Sitemap + robots + Search Console', 'description' => 'Configurar sitemap.xml, robots.txt y verificación en Google Search Console.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                        ['name' => 'Configuración de Google Analytics 4', 'description' => 'Implementación de GA4, configuración de eventos básicos (conversiones de formulario), y dashboard inicial.', 'duration_value' => 3, 'duration_unit' => 'hours'],
                        ['name' => 'Optimización de velocidad', 'description' => 'Core Web Vitals: lazy loading, compresión de imágenes, minificación CSS/JS, caché básico.', 'duration_value' => 3, 'duration_unit' => 'hours'],
                    ],
                ],
                // =============================================
                // PANEL DE ADMINISTRACIÓN + CAPACITACIÓN
                // =============================================
                [
                    'name' => 'Panel de Administración + Capacitación + Manual',
                    'description' => 'Panel completo para gestionar todo el contenido del sitio web: páginas, proyectos, blog, FAQ, contacto. Incluye capacitación y manual de uso.',
                    'quantity' => 1,
                    'unit' => 'módulo',
                    'unit_price' => 130,
                    'tasks' => [
                        ['name' => 'Accesos y seguridad del panel', 'description' => 'Configuración de usuarios, credenciales seguras y protección de rutas administrativas.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                        ['name' => 'Gestión de contenido general', 'description' => 'Módulos para editar contenido de todas las vistas: Inicio, Nosotros, Servicios, Contacto, FAQ.', 'duration_value' => 5, 'duration_unit' => 'hours'],
                        ['name' => 'Módulo de proyectos/galería', 'description' => 'CRUD de proyectos con galería de imágenes, categorización y ordenamiento.', 'duration_value' => 4, 'duration_unit' => 'hours'],
                        ['name' => 'Módulo de blog', 'description' => 'CRUD de posts, categorías, etiquetas, borradores y publicación programada.', 'duration_value' => 4, 'duration_unit' => 'hours'],
                        ['name' => 'Gestión de media (imágenes)', 'description' => 'Biblioteca de medios para subir, organizar y seleccionar imágenes con recomendaciones de tamaños.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                        ['name' => 'Capacitación (sesión remota)', 'description' => 'Sesión de capacitación sobre uso completo del panel: edición de contenido, proyectos, blog, FAQ.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                        ['name' => 'Manual de uso (PDF)', 'description' => 'Documento paso a paso con capturas de pantalla para gestionar todo el contenido del sitio.', 'duration_value' => 3, 'duration_unit' => 'hours'],
                        ['name' => 'QA final del panel', 'description' => 'Pruebas de todos los flujos: editar página, crear proyecto, publicar post, agregar FAQ.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                    ],
                ],
                // =============================================
                // HOSTING Y DOMINIO
                // =============================================
                [
                    'name' => 'Hosting y Dominio (compra, configuración, publicación)',
                    'description' => 'Gestión de compra/registro de dominio y hosting, configuración técnica para dejar el sitio publicado con SSL y DNS correctamente configurado.',
                    'quantity' => 1,
                    'unit' => 'servicio',
                    'unit_price' => 90,
                    'tasks' => [
                        ['name' => 'Selección de proveedor + compra', 'description' => 'Selección del plan de hosting y registro de dominio acorde a las necesidades de la empresa agrícola.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                        ['name' => 'Configuración inicial de hosting + SSL', 'description' => 'Preparación del hosting, certificado SSL (Let\'s Encrypt), variables de entorno y configuración del servidor.', 'duration_value' => 3, 'duration_unit' => 'hours'],
                        ['name' => 'Sincronización dominio ↔ hosting (DNS)', 'description' => 'Configuración de registros DNS (A, CNAME), verificación de propagación, redirecciones www/no-www.', 'duration_value' => 3, 'duration_unit' => 'hours'],
                        ['name' => 'Deploy (publicación) + verificación final', 'description' => 'Despliegue del sitio a producción, verificación de SSL, formularios, rutas y performance básica.', 'duration_value' => 4, 'duration_unit' => 'hours'],
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
