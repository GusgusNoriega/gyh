<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\Lead;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DecorygnacioStandardWebsiteQuoteSeeder extends Seeder
{
    /**
     * Crea:
     * - Usuario cliente (Ygnacio)
     * - Lead de empresa (DECORYGNACIO / RUC: 10453442755)
     * - Cotización en PEN (S/ 700 + IGV 18%) para una web estándar de "decoración de interiores"
     *   con ítems (vistas + panel + hosting/dominio) y plan de trabajo detallado (tareas).
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
                    'project_type' => 'Página web estándar (informativa) + Panel de administración + Hosting y Dominio',
                    'budget_up_to' => 700,
                    'message' => "Cotización solicitada para: {$companyName} (RUC: {$companyRuc}). Rubro: {$companyIndustry}.",
                    'status' => 'new',
                    'source' => 'seed',
                    'notes' => 'Lead creado automáticamente desde seeder para generar cotización formal.',
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
            $quoteNumber = 'COT-10453442755-WEB-0001';

            $quote = Quote::withTrashed()->updateOrCreate(
                ['quote_number' => $quoteNumber],
                [
                    'user_id' => $clientUser->id,
                    'created_by' => $createdBy?->id,
                    'title' => 'Cotización: Página web estándar + Hosting/Dominio + Panel de administración (DECORYGNACIO)',
                    'description' => "Desarrollo de una página web estándar (informativa) para {$companyName} ({$companyIndustry}).\n\n" .
                        "Vistas incluidas:\n" .
                        "- Inicio\n" .
                        "- Nosotros\n" .
                        "- Nuestros Proyectos\n" .
                        "- Contacto\n\n" .
                        "Incluye:\n" .
                        "- Panel de administración para editar contenido (secciones, textos e imágenes).\n" .
                        "- Compra/gestión de hosting y dominio + configuración y despliegue.\n" .
                        "- Capacitación y manual de uso.\n\n" .
                        "Tiempo estimado de entrega: máximo 2 semanas (sin imprevistos y con entrega oportuna de accesos/contenido).",
                    'currency_id' => $pen->id,
                    'tax_rate' => 18,
                    'discount_amount' => 0,
                    'status' => 'draft',
                    'valid_until' => now()->addDays(15)->toDateString(),
                    'estimated_start_date' => now()->addDays(1)->toDateString(),
                    'notes' => "Requisitos para iniciar:\n" .
                        "- Definición del nombre de dominio deseado (2-3 opciones) y datos para compra/registro.\n" .
                        "- Contenido base: logo, paleta/estilo (si existe), textos de Inicio/Nosotros/Proyectos y datos de contacto.\n" .
                        "- Referencias de diseño (sitios similares) y estructura de secciones deseada.\n\n" .
                        "Entregables:\n" .
                        "- Sitio web publicado con certificado SSL y formulario de contacto funcional.\n" .
                        "- Panel admin para actualizar contenido (texto/imagenes) sin depender del desarrollador.\n" .
                        "- Manual (PDF o documento) + capacitación de uso (sesión remota).\n",
                    'terms_conditions' => "Condiciones comerciales:\n" .
                        "- Moneda: PEN (S/).\n" .
                        "- IGV: 18% (se calcula automáticamente sobre el subtotal).\n" .
                        "- Forma de pago: 50% al iniciar el proyecto / 50% al culminar el proyecto y previo a la publicación final.\n" .
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

            // Total items (subtotal) = 700.00
            $items = [
                [
                    'name' => 'Vista: Inicio (Home)',
                    'description' => "Diseño e implementación de la vista de Inicio con secciones orientadas a {$companyIndustry}: presentación, beneficios, llamada a la acción y secciones destacadas.",
                    'quantity' => 1,
                    'unit' => 'vista',
                    'unit_price' => 170,
                    'tasks' => [
                        [
                            'name' => 'Estructura de secciones + contenido base',
                            'description' => 'Definición de secciones (hero, servicios/beneficios, proyectos destacados, CTA) y adaptación del contenido del rubro (decoración de interiores).',
                            'duration_value' => 2,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Maquetación responsive (móvil/tablet/desktop)',
                            'description' => 'Construcción del layout responsive, jerarquía visual, espaciados, tipografías y componentes reutilizables para Home.',
                            'duration_value' => 6,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Integración con contenido editable (panel admin)',
                            'description' => 'Conectar secciones de Home a campos editables (textos, botones/links e imágenes) para que el cliente actualice sin soporte técnico.',
                            'duration_value' => 3,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Optimización básica (SEO/Performance) + QA',
                            'description' => 'Metatags básicos, títulos, compresión de imágenes, revisión de links y pruebas de navegación en distintos tamaños de pantalla.',
                            'duration_value' => 2,
                            'duration_unit' => 'hours',
                        ],
                    ],
                ],
                [
                    'name' => 'Vista: Nosotros',
                    'description' => 'Vista institucional para presentar la empresa, propuesta de valor, experiencia y confianza de marca.',
                    'quantity' => 1,
                    'unit' => 'vista',
                    'unit_price' => 120,
                    'tasks' => [
                        [
                            'name' => 'Definición de contenido y estructura (misión/visión/valores)',
                            'description' => 'Organización del contenido (historia, misión/visión, valores, equipo si aplica) y definición de bloques editables.',
                            'duration_value' => 2,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Maquetación + estilos coherentes con la marca',
                            'description' => 'Implementación de layout y estilos, manteniendo consistencia con Home y cuidando legibilidad en móvil/desktop.',
                            'duration_value' => 4,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Integración con panel admin (textos e imágenes)',
                            'description' => 'Configuración para que el contenido de Nosotros sea editable (textos, imágenes, secciones) desde el panel.',
                            'duration_value' => 2,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'QA (contenido, responsive y accesibilidad básica)',
                            'description' => 'Revisión de ortografía, enlaces, contraste, estados hover/focus y consistencia visual.',
                            'duration_value' => 1,
                            'duration_unit' => 'hours',
                        ],
                    ],
                ],
                [
                    'name' => 'Vista: Nuestros Proyectos (portafolio)',
                    'description' => 'Implementación de portafolio para mostrar proyectos de decoración de interiores con imágenes y descripciones (listado y detalle básico).',
                    'quantity' => 1,
                    'unit' => 'vista',
                    'unit_price' => 160,
                    'tasks' => [
                        [
                            'name' => 'Estructura de portafolio (listado + ficha)',
                            'description' => 'Definir cómo se mostrarán los proyectos: tarjetas, categorías (si aplica), y estructura del detalle (galería, descripción, ubicación/alcance).',
                            'duration_value' => 2,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Maquetación de listado de proyectos',
                            'description' => 'Diseño del grid, estados vacíos, paginación o carga simple (según necesidad) y responsive.',
                            'duration_value' => 5,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Implementación de detalle de proyecto (galería + contenido)',
                            'description' => 'Página/ficha del proyecto con galería, descripción, secciones de antes/después si aplica y CTA a contacto.',
                            'duration_value' => 6,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Integración con panel admin (CRUD de proyectos)',
                            'description' => 'Crear/editar/eliminar proyectos desde el panel: título, descripción, imágenes, orden y visibilidad.',
                            'duration_value' => 4,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'QA + optimización de imágenes',
                            'description' => 'Revisión de tamaños/peso de imágenes, consistencia de tarjetas, enlaces y pruebas de navegación.',
                            'duration_value' => 2,
                            'duration_unit' => 'hours',
                        ],
                    ],
                ],
                [
                    'name' => 'Vista: Contacto',
                    'description' => 'Vista de contacto con datos de la empresa, mapa/enlaces (si aplica) y formulario con validaciones.',
                    'quantity' => 1,
                    'unit' => 'vista',
                    'unit_price' => 90,
                    'tasks' => [
                        [
                            'name' => 'Maquetación de la vista de Contacto',
                            'description' => 'Diseño de layout (datos, horarios, redes), CTA y consistencia con el resto del sitio.',
                            'duration_value' => 3,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Formulario de contacto (validaciones + anti-spam básico)',
                            'description' => 'Campos (nombre, correo, teléfono, mensaje), validaciones, mensajes de éxito/error y anti-spam básico (honeypot o similar).',
                            'duration_value' => 4,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Notificaciones y pruebas de envío',
                            'description' => 'Configuración de envío/registro de lead (según el sistema), pruebas end-to-end y verificación de entregabilidad.',
                            'duration_value' => 2,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Contenido editable desde panel',
                            'description' => 'Configurar para que teléfonos, correo, dirección, links y textos de Contacto sean editables en el panel.',
                            'duration_value' => 1,
                            'duration_unit' => 'hours',
                        ],
                    ],
                ],
                [
                    'name' => 'Panel de administración (edición de contenido) + capacitación + manual',
                    'description' => 'Panel para gestionar contenido del sitio (secciones, textos, imágenes) y módulo de proyectos. Incluye capacitación y manual.',
                    'quantity' => 1,
                    'unit' => 'módulo',
                    'unit_price' => 110,
                    'tasks' => [
                        [
                            'name' => 'Configuración de acceso al panel (usuarios/seguridad básica)',
                            'description' => 'Habilitar acceso seguro, credenciales iniciales, buenas prácticas de contraseñas y protección básica de rutas.',
                            'duration_value' => 3,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Módulo de contenido editable (Inicio/Nosotros/Contacto)',
                            'description' => 'Campos editables para textos, imágenes y enlaces por sección. Validaciones y previsualización básica cuando aplique.',
                            'duration_value' => 6,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Gestión de media (imágenes) y recomendaciones',
                            'description' => 'Carga/selección de imágenes, recomendaciones de tamaños/peso y soporte de formatos comunes (JPG/PNG/WebP).',
                            'duration_value' => 3,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Capacitación (sesión remota) + manual',
                            'description' => 'Sesión de capacitación sobre uso del panel (edición de contenido/proyectos) y entrega de manual paso a paso.',
                            'duration_value' => 2,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'QA final del panel y ajuste de permisos',
                            'description' => 'Pruebas de flujos comunes (editar texto, subir imagen, crear proyecto) y ajustes menores de usabilidad.',
                            'duration_value' => 2,
                            'duration_unit' => 'hours',
                        ],
                    ],
                ],
                [
                    'name' => 'Hosting y Dominio (compra, configuración, publicación y sincronización)',
                    'description' => 'Gestión de compra/registro y configuración técnica para dejar el sitio publicado con SSL y DNS correctamente apuntado.',
                    'quantity' => 1,
                    'unit' => 'servicio',
                    'unit_price' => 50,
                    'tasks' => [
                        [
                            'name' => 'Selección de proveedor (hosting/dominio) + compra',
                            'description' => 'Selección del plan acorde a una web estándar y compra/registro del dominio. Recolección de datos necesarios para titularidad.',
                            'duration_value' => 2,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Configuración inicial de hosting (cuenta, SSL, variables)',
                            'description' => 'Creación de sitio en hosting, configuración de SSL (Let’s Encrypt si aplica), y parámetros básicos para despliegue.',
                            'duration_value' => 3,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Sincronización dominio ↔ hosting (DNS)',
                            'description' => 'Configuración de DNS (A/CNAME), validación de propagación, redirecciones con www/no-www y pruebas de resolución.',
                            'duration_value' => 3,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Publicación (deploy) + verificación final',
                            'description' => 'Despliegue a producción, revisión de rutas, formulario de contacto, SSL activo, enlaces y performance básica.',
                            'duration_value' => 4,
                            'duration_unit' => 'hours',
                        ],
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

