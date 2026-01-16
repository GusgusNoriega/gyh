<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\Lead;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DecorygnacioProfessionalWebsiteQuoteSeeder extends Seeder
{
    /**
     * Crea:
     * - Usuario cliente (Ygnacio)
     * - Lead de empresa (DECORYGNACIO / RUC: 10453442755)
     * - Cotización en PEN (S/ 1200 + IGV 18%) para una web profesional de "decoración de interiores"
     *   que incluye:
     *   - Todo lo de la cotización de web estándar
     *   - Blog (listado) + post individual
     *   - Carga inicial de 3 posts (contenido a definir por el cliente)
     *   - Filtros personalizados para proyectos y posts
     *   - SEO avanzado
     *   - 2 vistas adicionales para un sitio más profesional
     * Tiempo máximo: 3 semanas (sin imprevistos).
     */
    public function run(): void
    {
        DB::transaction(function () {
            // =========================
            // Datos del cliente/empresa
            // =========================
            $clientName = 'Ygnacio';
            $clientEmail = 'asis_12345@hotmail.com';
            $clientPhone = null;
            $clientAddress = null;

            $companyName = 'DECORYGNACIO';
            $companyRuc = '10453442755';
            $companyIndustry = 'Decoración de interiores';

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
                    'project_type' => 'Página web profesional + Blog + Panel de administración + SEO avanzado + Hosting y Dominio',
                    'budget_up_to' => 1200,
                    'message' => "Cotización solicitada para: {$companyName} (RUC: {$companyRuc}). Rubro: {$companyIndustry}. Plan: Web profesional.",
                    'status' => 'new',
                    'source' => 'seed',
                    'notes' => 'Lead actualizado automáticamente desde seeder para generar cotización profesional.',
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
            $quoteNumber = 'COT-10453442755-WEBPRO-0002';

            $quote = Quote::withTrashed()->updateOrCreate(
                ['quote_number' => $quoteNumber],
                [
                    'user_id' => $clientUser->id,
                    'created_by' => $createdBy?->id,
                    'title' => 'Cotización: Página web profesional + Blog + SEO avanzado + Hosting/Dominio + Panel admin (DECORYGNACIO)',
                    'description' => "Desarrollo de una página web profesional para {$companyName} ({$companyIndustry}).\n\n" .
                        "Vistas incluidas:\n" .
                        "- Inicio\n" .
                        "- Nosotros\n" .
                        "- Nuestros Proyectos (con filtros personalizados)\n" .
                        "- Contacto\n" .
                        "- Blog (listado con filtros)\n" .
                        "- Post individual\n" .
                        "- Servicios (vista adicional)\n" .
                        "- Testimonios (vista adicional)\n\n" .
                        "Incluye:\n" .
                        "- Panel de administración para editar contenido (secciones, textos e imágenes).\n" .
                        "- Gestión de proyectos y blog (posts) desde el panel.\n" .
                        "- Carga inicial de 3 posts (contenido proporcionado por el cliente).\n" .
                        "- Filtros personalizados para proyectos y posts.\n" .
                        "- SEO avanzado para posicionamiento en buscadores.\n" .
                        "- Compra/gestión de hosting y dominio + configuración, SSL y despliegue.\n" .
                        "- Capacitación y manual de uso.\n\n" .
                        "Tiempo estimado de entrega: máximo 3 semanas (sin imprevistos y con entrega oportuna de accesos/contenido).",
                    'currency_id' => $pen->id,
                    'tax_rate' => 18,
                    'discount_amount' => 0,
                    'status' => 'draft',
                    'valid_until' => now()->addDays(15)->toDateString(),
                    'estimated_start_date' => now()->addDays(1)->toDateString(),
                    'notes' => "Requisitos para iniciar:\n" .
                        "- Definición del nombre de dominio deseado (2-3 opciones) y datos para compra/registro.\n" .
                        "- Contenido base: logo, paleta/estilo (si existe), textos de Inicio/Nosotros/Servicios/Testimonios, datos de contacto.\n" .
                        "- Portafolio: lista de proyectos (títulos, descripciones, fotos) y criterios para filtros (categorías/estilos/ambientes).\n" .
                        "- Blog: 3 posts iniciales (título, contenido, imágenes, etiquetas/categorías) + lineamientos para filtros.\n" .
                        "- Palabras clave objetivo (si existen) y ciudades/zonas (si aplica) para estrategia SEO.\n\n" .
                        "Entregables:\n" .
                        "- Sitio web publicado con certificado SSL, sitemap y robots configurado.\n" .
                        "- Panel admin para actualizar contenido, proyectos y posts.\n" .
                        "- 3 posts cargados y publicados (contenido proporcionado por el cliente).\n" .
                        "- Manual + capacitación.\n",
                    'terms_conditions' => "Condiciones comerciales:\n" .
                        "- Moneda: PEN (S/).\n" .
                        "- IGV: 18% (se calcula automáticamente sobre el subtotal).\n" .
                        "- Forma de pago: 50% al iniciar el proyecto / 50% al culminar el proyecto y previo a la publicación final.\n" .
                        "- Hosting + dominio: se incluye la gestión y el costo estimado dentro de esta cotización; el pago anual de hosting y dominio puede ascender a S/ 350 (según proveedor/plan). Renovaciones anuales posteriores corren por cuenta del cliente.\n" .
                        "- Propiedad del código: 100% del código fuente y entregables serán del cliente.\n" .
                        "- Alcance: incluye panel de administración, capacitación y manual. Cambios mayores o nuevos módulos fuera del alcance se cotizan por separado.\n" .
                        "- Plazo estimado: hasta 3 semanas, sujeto a entrega de contenido y aprobaciones sin demoras.",
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

            // Total items (subtotal) = 1200.00
            $items = [
                [
                    'name' => 'Vista: Inicio (Home)',
                    'description' => "Diseño e implementación de la vista de Inicio orientada a {$companyIndustry}: propuesta de valor, servicios destacados, proyectos, CTA y secciones clave.",
                    'quantity' => 1,
                    'unit' => 'vista',
                    'unit_price' => 150,
                    'tasks' => [
                        ['name' => 'Arquitectura de secciones + UI kit básico', 'description' => 'Definir estructura (hero, servicios, proyectos destacados, CTA, beneficios) y componentes reutilizables (botones, cards, badges).', 'duration_value' => 3, 'duration_unit' => 'hours'],
                        ['name' => 'Maquetación responsive', 'description' => 'Implementar layout responsive (móvil/tablet/desktop) y consistencia de estilos.', 'duration_value' => 6, 'duration_unit' => 'hours'],
                        ['name' => 'Integración con contenido editable (panel)', 'description' => 'Conectar secciones a campos editables: textos, imágenes, enlaces y orden de secciones cuando aplique.', 'duration_value' => 3, 'duration_unit' => 'hours'],
                        ['name' => 'QA + performance básico', 'description' => 'Verificar navegación, enlaces, pesos de imágenes, y pruebas cross-device.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                    ],
                ],
                [
                    'name' => 'Vista: Nosotros',
                    'description' => 'Vista institucional para presentar la marca, equipo/experiencia y diferencial, con secciones editables.',
                    'quantity' => 1,
                    'unit' => 'vista',
                    'unit_price' => 100,
                    'tasks' => [
                        ['name' => 'Estructura de contenido (misión/visión/valores)', 'description' => 'Definir secciones y campos editables (texto e imágenes).', 'duration_value' => 2, 'duration_unit' => 'hours'],
                        ['name' => 'Maquetación + estilos', 'description' => 'Implementación UI coherente con Home y ajustes responsive.', 'duration_value' => 4, 'duration_unit' => 'hours'],
                        ['name' => 'Integración con panel', 'description' => 'Vincular contenido a administración (edición de textos e imágenes).', 'duration_value' => 2, 'duration_unit' => 'hours'],
                        ['name' => 'QA', 'description' => 'Revisión de contenido, enlaces y accesibilidad básica.', 'duration_value' => 1, 'duration_unit' => 'hours'],
                    ],
                ],
                [
                    'name' => 'Vista: Nuestros Proyectos (portafolio) + filtros personalizados',
                    'description' => 'Portafolio profesional de proyectos de decoración de interiores con filtros personalizados (por categoría/estilo/ambiente u otros criterios del cliente) y ficha de proyecto.',
                    'quantity' => 1,
                    'unit' => 'vista',
                    'unit_price' => 170,
                    'tasks' => [
                        ['name' => 'Definición de taxonomías y filtros', 'description' => 'Definir categorías/tags y criterios de filtrado para proyectos (por ejemplo: tipo de ambiente, estilo, ubicación, servicio).', 'duration_value' => 3, 'duration_unit' => 'hours'],
                        ['name' => 'Maquetación listado + UX de filtros', 'description' => 'Implementar grid, buscador/filtros, estados vacíos y responsive.', 'duration_value' => 6, 'duration_unit' => 'hours'],
                        ['name' => 'Vista detalle de proyecto', 'description' => 'Ficha con galería, descripción, “antes/después” si aplica, y CTA a contacto.', 'duration_value' => 6, 'duration_unit' => 'hours'],
                        ['name' => 'Integración con panel (CRUD + asignación de filtros)', 'description' => 'Crear/editar proyectos desde panel, cargar imágenes y asignar categorías/etiquetas para filtros.', 'duration_value' => 4, 'duration_unit' => 'hours'],
                        ['name' => 'QA + optimización de imágenes', 'description' => 'Revisión de peso/tamaños, orden de galería y consistencia visual.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                    ],
                ],
                [
                    'name' => 'Vista: Contacto',
                    'description' => 'Vista de contacto con datos editables, formulario con validaciones y anti-spam básico.',
                    'quantity' => 1,
                    'unit' => 'vista',
                    'unit_price' => 80,
                    'tasks' => [
                        ['name' => 'Maquetación de contacto', 'description' => 'Layout con datos, CTA y consistencia visual.', 'duration_value' => 3, 'duration_unit' => 'hours'],
                        ['name' => 'Formulario (validaciones + anti-spam)', 'description' => 'Campos, validaciones, mensajes y protección básica anti-spam.', 'duration_value' => 4, 'duration_unit' => 'hours'],
                        ['name' => 'Pruebas end-to-end', 'description' => 'Verificar envío/registro del lead y confirmaciones.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                    ],
                ],
                [
                    'name' => 'Vista: Blog (listado) + filtros',
                    'description' => 'Listado de blog con filtros por categoría/etiquetas y buscador (según requerimiento), optimizado para lectura.',
                    'quantity' => 1,
                    'unit' => 'vista',
                    'unit_price' => 120,
                    'tasks' => [
                        ['name' => 'Modelo de contenido (categorías/etiquetas)', 'description' => 'Definir estructura de categorías/tags para filtros y SEO (slugs amigables).', 'duration_value' => 3, 'duration_unit' => 'hours'],
                        ['name' => 'Maquetación listado + cards + paginación', 'description' => 'Implementar tarjetas, extractos, fecha/autor opcional, paginación y responsive.', 'duration_value' => 5, 'duration_unit' => 'hours'],
                        ['name' => 'Implementación de filtros personalizados', 'description' => 'Filtros por categoría/etiquetas, búsqueda y estados vacíos.', 'duration_value' => 4, 'duration_unit' => 'hours'],
                        ['name' => 'QA', 'description' => 'Pruebas de navegación, paginación y filtros.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                    ],
                ],
                [
                    'name' => 'Vista: Post individual (detalle de artículo)',
                    'description' => 'Vista individual del post con lectura optimizada, breadcrumbs (si aplica), y CTAs hacia contacto/servicios.',
                    'quantity' => 1,
                    'unit' => 'vista',
                    'unit_price' => 90,
                    'tasks' => [
                        ['name' => 'Maquetación de post', 'description' => 'Diseño de lectura, encabezados, imagen destacada y contenido.', 'duration_value' => 4, 'duration_unit' => 'hours'],
                        ['name' => 'Relacionados + navegación', 'description' => 'Implementar posts relacionados (por categoría/tag) y navegación anterior/siguiente si aplica.', 'duration_value' => 3, 'duration_unit' => 'hours'],
                        ['name' => 'QA + legibilidad', 'description' => 'Revisión de tipografías, contraste, estilos de listas/citas y responsive.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                    ],
                ],
                [
                    'name' => 'Vista adicional: Servicios',
                    'description' => 'Página para presentar servicios de decoración de interiores (paquetes, proceso, beneficios), con contenido editable.',
                    'quantity' => 1,
                    'unit' => 'vista',
                    'unit_price' => 100,
                    'tasks' => [
                        ['name' => 'Definición de secciones y copy base', 'description' => 'Estructurar secciones (servicios, proceso, entregables, FAQ corta opcional) y campos editables.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                        ['name' => 'Maquetación + CTAs', 'description' => 'Implementar layout y CTAs hacia contacto.', 'duration_value' => 5, 'duration_unit' => 'hours'],
                        ['name' => 'Integración con panel', 'description' => 'Campos editables para textos, cards y/o secciones.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                    ],
                ],
                [
                    'name' => 'Vista adicional: Testimonios',
                    'description' => 'Página para mostrar testimonios/casos de éxito (con posibilidad de edición desde panel).',
                    'quantity' => 1,
                    'unit' => 'vista',
                    'unit_price' => 60,
                    'tasks' => [
                        ['name' => 'Maquetación de testimonios', 'description' => 'Diseño de cards/carrusel simple, nombres, estrellas/opcional y responsive.', 'duration_value' => 4, 'duration_unit' => 'hours'],
                        ['name' => 'Integración con panel (gestión de testimonios)', 'description' => 'Crear/editar testimonios desde el panel (texto, autor, foto opcional, orden).', 'duration_value' => 3, 'duration_unit' => 'hours'],
                        ['name' => 'QA', 'description' => 'Verificar visualización y consistencia con marca.', 'duration_value' => 1, 'duration_unit' => 'hours'],
                    ],
                ],
                [
                    'name' => 'Panel de administración (contenido + proyectos + blog) + capacitación + manual',
                    'description' => 'Panel para editar contenido del sitio, gestionar proyectos y gestionar posts del blog (incluye filtros, categorías/etiquetas y carga inicial).',
                    'quantity' => 1,
                    'unit' => 'módulo',
                    'unit_price' => 180,
                    'tasks' => [
                        ['name' => 'Accesos y seguridad básica del panel', 'description' => 'Creación de usuario(s), credenciales, y protección de rutas/acciones básicas.', 'duration_value' => 3, 'duration_unit' => 'hours'],
                        ['name' => 'Gestión de contenido (Inicio/Nosotros/Servicios/Contacto/Testimonios)', 'description' => 'Campos editables por sección: textos, imágenes, enlaces; validaciones y orden cuando aplique.', 'duration_value' => 6, 'duration_unit' => 'hours'],
                        ['name' => 'Módulo Blog: CRUD de posts + categorías/etiquetas', 'description' => 'Crear/editar/eliminar posts, categorías y tags; slugs amigables; borradores/publicación.', 'duration_value' => 6, 'duration_unit' => 'hours'],
                        ['name' => 'Carga inicial de 3 posts (contenido del cliente)', 'description' => 'Publicación de 3 artículos con imágenes y asignación de categorías/etiquetas según la información proporcionada por el cliente.', 'duration_value' => 4, 'duration_unit' => 'hours'],
                        ['name' => 'Capacitación + manual', 'description' => 'Sesión remota de capacitación y entrega de manual paso a paso (contenido, proyectos, blog, filtros).', 'duration_value' => 3, 'duration_unit' => 'hours'],
                        ['name' => 'QA final del panel', 'description' => 'Pruebas de flujos: editar contenido, crear proyecto, crear post, asignar filtros, publicar.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                    ],
                ],
                [
                    'name' => 'SEO avanzado (posicionamiento en buscadores)',
                    'description' => 'SEO técnico avanzado: estructura, metadatos, schema, indexación y optimización on-page base por vistas y posts.',
                    'quantity' => 1,
                    'unit' => 'servicio',
                    'unit_price' => 80,
                    'tasks' => [
                        ['name' => 'Estrategia SEO base (keywords + arquitectura)', 'description' => 'Definir arquitectura de información, keywords base y jerarquía H1/H2 por vista (con insumos del cliente).', 'duration_value' => 4, 'duration_unit' => 'hours'],
                        ['name' => 'Metadatos avanzados + OpenGraph', 'description' => 'Titles/descriptions por vista y posts, canonical, OG/Twitter cards, imagen social por defecto.', 'duration_value' => 3, 'duration_unit' => 'hours'],
                        ['name' => 'Schema.org (structured data)', 'description' => 'Implementar schema (Organization/LocalBusiness/Article/BreadcrumbList) según aplique.', 'duration_value' => 3, 'duration_unit' => 'hours'],
                        ['name' => 'Sitemap + robots + verificación', 'description' => 'Configurar sitemap.xml/robots.txt y checklist de indexación. (Integración con Search Console si el cliente provee acceso).', 'duration_value' => 2, 'duration_unit' => 'hours'],
                        ['name' => 'Optimización performance enfocada a SEO', 'description' => 'Revisión de Core Web Vitals básicos (imágenes, lazy-load, caché básico si aplica).', 'duration_value' => 2, 'duration_unit' => 'hours'],
                    ],
                ],
                [
                    'name' => 'Hosting y Dominio (compra, configuración, publicación y sincronización)',
                    'description' => 'Gestión de compra/registro y configuración técnica para dejar el sitio publicado con SSL y DNS correctamente apuntado.',
                    'quantity' => 1,
                    'unit' => 'servicio',
                    'unit_price' => 70,
                    'tasks' => [
                        ['name' => 'Selección de proveedor + compra', 'description' => 'Selección de plan acorde a web profesional y compra/registro del dominio.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                        ['name' => 'Configuración inicial de hosting + SSL', 'description' => 'Preparación del hosting, SSL, variables y entorno para deploy.', 'duration_value' => 3, 'duration_unit' => 'hours'],
                        ['name' => 'Sincronización dominio ↔ hosting (DNS)', 'description' => 'DNS A/CNAME, propagación, redirecciones y verificación.', 'duration_value' => 3, 'duration_unit' => 'hours'],
                        ['name' => 'Deploy + smoke tests', 'description' => 'Publicación a producción y pruebas rápidas (formularios, rutas, assets, SSL).', 'duration_value' => 4, 'duration_unit' => 'hours'],
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

