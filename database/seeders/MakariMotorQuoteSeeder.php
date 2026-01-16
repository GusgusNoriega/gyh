<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\Lead;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MakariMotorQuoteSeeder extends Seeder
{
    /**
     * Crea:
     * - Usuario cliente (Ever)
     * - Lead de empresa (MAKARI MOTOR / RUC: 20609813254)
     * - Cotización en PEN del Plan Estándar (S/ 800 + IGV 18% = S/ 944)
     *   con ítems (4 vistas + panel + hosting/dominio) y plan de trabajo (tareas).
     */
    public function run(): void
    {
        DB::transaction(function () {
            // =========================
            // Datos del cliente/empresa
            // =========================
            $clientName = 'Ever';
            $clientEmail = 'ever984@gmail.com';
            $clientPhone = null;
            $clientAddress = null;

            $companyName = 'MAKARI MOTOR';
            $companyRuc = '20609813254';
            $companyIndustry = 'Automotriz (motores / servicios técnicos)';

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
                    'project_type' => 'Página web (Plan Estándar) + Panel de administración + Hosting y Dominio',
                    'budget_up_to' => 800,
                    'message' => "Cotización solicitada para: {$companyName} (RUC: {$companyRuc}). Rubro: {$companyIndustry}.",
                    'status' => 'new',
                    'source' => 'seed',
                    'notes' => 'Lead creado automáticamente desde seeder para generar cotización formal (Plan Estándar).',
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
            $quoteNumber = 'COT-20609813254-WEB-0001';

            $quote = Quote::withTrashed()->updateOrCreate(
                ['quote_number' => $quoteNumber],
                [
                    'user_id' => $clientUser->id,
                    'created_by' => $createdBy?->id,
                    'title' => "Cotización: Plan Estándar (página web) + Hosting/Dominio + Panel de administración ({$companyName})",
                    'description' => "Desarrollo de una página web informativa (Plan Estándar) para {$companyName} ({$companyIndustry}).\n\n" .
                        "Vistas incluidas (4):\n" .
                        "- Inicio\n" .
                        "- Nosotros\n" .
                        "- Servicios / Proyectos\n" .
                        "- Contacto\n\n" .
                        "Incluye:\n" .
                        "- Diseño responsive (móvil/tablet/desktop).\n" .
                        "- Panel de administración para editar contenido (textos e imágenes).\n" .
                        "- Hosting + dominio (configuración inicial) + certificado SSL.\n" .
                        "- Formulario de contacto funcional.\n" .
                        "- Capacitación + manual de uso.\n" .
                        "- SEO básico (metatags, títulos y descripciones).\n\n" .
                        "Tiempo estimado de entrega: máximo 2 semanas (sin imprevistos y con entrega oportuna de accesos/contenido).",
                    'currency_id' => $pen->id,
                    'tax_rate' => 18,
                    'discount_amount' => 0,
                    'status' => 'draft',
                    'valid_until' => now()->addDays(15)->toDateString(),
                    'estimated_start_date' => now()->addDays(1)->toDateString(),
                    'notes' => "Requisitos para iniciar:\n" .
                        "- Definición del nombre de dominio deseado (2-3 opciones) y datos para compra/registro.\n" .
                        "- Contenido base: logo (si existe), paleta/estilo, textos de Inicio/Nosotros/Servicios, y datos de contacto.\n" .
                        "- Fotos del negocio (taller, productos, equipo) en buena calidad.\n" .
                        "- Referencias de diseño (sitios similares) y estructura de secciones deseada.\n\n" .
                        "Entregables:\n" .
                        "- Sitio web publicado con SSL y formulario de contacto funcional.\n" .
                        "- Panel admin para actualizar contenido sin depender del desarrollador.\n" .
                        "- Manual (PDF o documento) + capacitación de uso (sesión remota).\n",
                    'terms_conditions' => "Condiciones comerciales:\n" .
                        "- Moneda: PEN (S/).\n" .
                        "- Subtotal (Plan Estándar): S/ 800.00\n" .
                        "- IGV (18%): S/ 144.00\n" .
                        "- Total: S/ 944.00\n" .
                        "- Forma de pago: 50% al iniciar el proyecto (S/ 472.00) / 50% al culminar el proyecto y previo a la publicación final (S/ 472.00).\n" .
                        "- Hosting + dominio: se incluye la gestión y el costo estimado dentro de esta cotización; el pago anual de hosting y dominio puede ascender a S/ 350 (según proveedor/plan). Renovaciones anuales posteriores corren por cuenta del cliente.\n" .
                        "- Propiedad del código: 100% del código fuente y entregables serán del cliente.\n" .
                        "- Alcance: incluye panel de administración, capacitación y manual. Cambios mayores o nuevas secciones fuera del alcance se cotizan por separado.\n" .
                        "- Plazo estimado: hasta 2 semanas, sujeto a entrega de contenido y aprobaciones sin demoras.",
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

            // Subtotal items = 800.00
            // IGV (18%) = 144.00
            // Total con IGV = 944.00
            $items = [
                [
                    'name' => 'Vista: Inicio (Home)',
                    'description' => "Diseño e implementación de la vista de Inicio para {$companyName}: presentación del negocio, propuesta de valor, servicios destacados, llamados a la acción y secciones clave.",
                    'quantity' => 1,
                    'unit' => 'vista',
                    'unit_price' => 190,
                    'tasks' => [
                        ['name' => 'Estructura de secciones + contenido base', 'description' => 'Definir secciones (hero, servicios destacados, beneficios, CTA, sección de confianza/experiencia).', 'duration_value' => 2, 'duration_unit' => 'hours'],
                        ['name' => 'Maquetación responsive (móvil/tablet/desktop)', 'description' => 'Construcción del layout responsive con estilos modernos y jerarquía visual clara.', 'duration_value' => 6, 'duration_unit' => 'hours'],
                        ['name' => 'Integración con contenido editable (panel admin)', 'description' => 'Conectar textos, imágenes y botones a campos editables desde el panel.', 'duration_value' => 3, 'duration_unit' => 'hours'],
                        ['name' => 'SEO básico + QA', 'description' => 'Metatags básicos, títulos, compresión de imágenes, revisión de links y pruebas de navegación.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                    ],
                ],
                [
                    'name' => 'Vista: Nosotros',
                    'description' => 'Vista institucional para presentar la empresa, historia, misión/visión (si aplica) y fortalezas del equipo.',
                    'quantity' => 1,
                    'unit' => 'vista',
                    'unit_price' => 140,
                    'tasks' => [
                        ['name' => 'Definición de contenido y estructura', 'description' => 'Organización del contenido: historia, valores, experiencia, equipo, certificaciones (si aplica).', 'duration_value' => 2, 'duration_unit' => 'hours'],
                        ['name' => 'Maquetación + estilos coherentes con marca', 'description' => 'Implementación de layout y estilos, manteniendo consistencia con Home.', 'duration_value' => 4, 'duration_unit' => 'hours'],
                        ['name' => 'Integración con panel (textos e imágenes)', 'description' => 'Habilitar edición de bloques, imágenes y secciones desde el panel.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                        ['name' => 'QA (responsive y accesibilidad básica)', 'description' => 'Revisión de contraste, estados hover/focus y consistencia visual.', 'duration_value' => 1, 'duration_unit' => 'hours'],
                    ],
                ],
                [
                    'name' => 'Vista: Servicios / Proyectos',
                    'description' => 'Página para listar servicios principales y/o trabajos realizados (proyectos), con tarjetas, descripciones y CTA a contacto.',
                    'quantity' => 1,
                    'unit' => 'vista',
                    'unit_price' => 180,
                    'tasks' => [
                        ['name' => 'Definición de secciones (servicios / proyectos)', 'description' => 'Estructurar cards de servicios/proyectos, descripciones, imágenes y jerarquía de contenido.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                        ['name' => 'Maquetación del listado (grid) + responsive', 'description' => 'Diseño del grid, estados vacíos, ordenamiento simple y responsive.', 'duration_value' => 5, 'duration_unit' => 'hours'],
                        ['name' => 'Integración con panel (contenido editable)', 'description' => 'Configurar edición de textos e imágenes (y opcionalmente cards) desde el panel.', 'duration_value' => 3, 'duration_unit' => 'hours'],
                        ['name' => 'QA + optimización de imágenes', 'description' => 'Revisión de tamaños/peso de imágenes, enlaces y navegación.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                    ],
                ],
                [
                    'name' => 'Vista: Contacto',
                    'description' => 'Vista de contacto con datos de la empresa y formulario con validaciones.',
                    'quantity' => 1,
                    'unit' => 'vista',
                    'unit_price' => 100,
                    'tasks' => [
                        ['name' => 'Maquetación de la vista de Contacto', 'description' => 'Diseño de layout (datos, horarios, redes si aplica) y consistencia con el resto del sitio.', 'duration_value' => 3, 'duration_unit' => 'hours'],
                        ['name' => 'Formulario de contacto (validaciones + anti-spam básico)', 'description' => 'Campos (nombre, correo, teléfono, mensaje), validaciones, mensajes de éxito/error y honeypot.', 'duration_value' => 4, 'duration_unit' => 'hours'],
                        ['name' => 'Notificaciones y pruebas de envío', 'description' => 'Configuración de envío/registro de lead y pruebas end-to-end.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                        ['name' => 'Contenido editable desde panel', 'description' => 'Configurar teléfonos, correo, dirección y textos como editables.', 'duration_value' => 1, 'duration_unit' => 'hours'],
                    ],
                ],
                [
                    'name' => 'Panel de administración (edición de contenido) + capacitación + manual',
                    'description' => 'Panel para gestionar contenido del sitio (secciones, textos e imágenes). Incluye capacitación y manual.',
                    'quantity' => 1,
                    'unit' => 'módulo',
                    'unit_price' => 130,
                    'tasks' => [
                        ['name' => 'Configuración de acceso al panel (usuarios/seguridad básica)', 'description' => 'Habilitar acceso seguro, credenciales iniciales y protección básica de rutas.', 'duration_value' => 3, 'duration_unit' => 'hours'],
                        ['name' => 'Módulo de contenido editable (Inicio/Nosotros/Servicios/Contacto)', 'description' => 'Campos editables para textos, imágenes y enlaces por sección. Validaciones básicas.', 'duration_value' => 6, 'duration_unit' => 'hours'],
                        ['name' => 'Gestión de media (imágenes) y recomendaciones', 'description' => 'Carga/selección de imágenes, recomendaciones de tamaños/peso y soporte de formatos comunes.', 'duration_value' => 3, 'duration_unit' => 'hours'],
                        ['name' => 'Capacitación (sesión remota) + manual', 'description' => 'Sesión de capacitación sobre uso del panel y entrega de manual paso a paso.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                        ['name' => 'QA final del panel y ajustes menores', 'description' => 'Pruebas de flujos comunes (editar texto, subir imagen) y ajustes de usabilidad.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                    ],
                ],
                [
                    'name' => 'Hosting y Dominio (compra, configuración, publicación y sincronización)',
                    'description' => 'Gestión de compra/registro y configuración técnica para dejar el sitio publicado con SSL y DNS correctamente apuntado.',
                    'quantity' => 1,
                    'unit' => 'servicio',
                    'unit_price' => 60,
                    'tasks' => [
                        ['name' => 'Selección de proveedor (hosting/dominio) + compra', 'description' => 'Selección del plan acorde a una web estándar y compra/registro del dominio. Recolección de datos para titularidad.', 'duration_value' => 2, 'duration_unit' => 'hours'],
                        ['name' => 'Configuración inicial de hosting (cuenta, SSL, variables)', 'description' => 'Creación de sitio en hosting, configuración de SSL y parámetros básicos para despliegue.', 'duration_value' => 3, 'duration_unit' => 'hours'],
                        ['name' => 'Sincronización dominio ↔ hosting (DNS)', 'description' => 'Configuración de DNS (A/CNAME), verificación de propagación y redirecciones www/no-www.', 'duration_value' => 3, 'duration_unit' => 'hours'],
                        ['name' => 'Publicación (deploy) + verificación final', 'description' => 'Despliegue a producción, revisión de rutas, formulario de contacto, SSL activo y smoke tests.', 'duration_value' => 4, 'duration_unit' => 'hours'],
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

