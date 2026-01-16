<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\Lead;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class JeanPieroLoraTorresQuoteSeeder extends Seeder
{
    /**
     * Crea:
     * - Usuario cliente (Jean Piero Lora Torres)
     * - Lead de persona natural (RUC: 10456413744)
     * - Cotización en PEN (S/ 800 + IGV 18% = S/ 944) para una web personal/portafolio
     *   con ítems (vistas + panel + hosting/dominio) y plan de trabajo detallado (tareas).
     */
    public function run(): void
    {
        DB::transaction(function () {
            // =========================
            // Datos del cliente
            // =========================
            $clientName = 'Jean Piero Lora Torres';
            $clientEmail = 'piero.lora@gmail.com';
            $clientPhone = null;
            $clientAddress = null;

            $companyName = 'Jean Piero Lora Torres';
            $companyRuc = '10456413744';
            $clientIndustry = 'Profesional independiente / Portfolio personal';

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
            // Lead (persona natural)
            // =========================
            Lead::updateOrCreate(
                [
                    'email' => $clientEmail,
                    'company_name' => $companyName,
                ],
                [
                    'name' => $clientName,
                    'phone' => $clientPhone,
                    'is_company' => false,
                    'company_ruc' => $companyRuc,
                    'project_type' => 'Página web personal (portafolio profesional) + Panel de administración + Hosting y Dominio',
                    'budget_up_to' => 800,
                    'message' => "Cotización solicitada para: {$clientName} (RUC: {$companyRuc}). Tipo: {$clientIndustry}.",
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
            $quoteNumber = 'COT-10456413744-WEB-0001';

            $quote = Quote::withTrashed()->updateOrCreate(
                ['quote_number' => $quoteNumber],
                [
                    'user_id' => $clientUser->id,
                    'created_by' => $createdBy?->id,
                    'title' => 'Cotización: Página web personal (portafolio profesional) + Hosting/Dominio + Panel de administración - Jean Piero Lora Torres',
                    'description' => "Desarrollo de una página web personal/portafolio profesional para {$clientName}.\n\n" .
                        "El sitio web incluirá:\n" .
                        "- Presentación personal (Sobre mí)\n" .
                        "- Experiencia profesional y trayectoria laboral\n" .
                        "- Portafolio de trabajos y proyectos realizados\n" .
                        "- Habilidades y competencias\n" .
                        "- Integración de redes sociales\n" .
                        "- Formulario de contacto\n\n" .
                        "Vistas incluidas:\n" .
                        "- Inicio (presentación personal)\n" .
                        "- Sobre mí (experiencia y trayectoria)\n" .
                        "- Portafolio (trabajos y proyectos)\n" .
                        "- Contacto (formulario + redes sociales)\n\n" .
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
                        "- Contenido base: foto profesional, logo personal (si existe), CV/resumen de experiencia.\n" .
                        "- Textos de presentación personal, descripción de experiencia y proyectos destacados.\n" .
                        "- URLs de redes sociales (LinkedIn, GitHub, Twitter, Instagram, etc.).\n" .
                        "- Referencias de diseño (sitios similares de portafolio) y estilo visual deseado.\n\n" .
                        "Entregables:\n" .
                        "- Sitio web publicado con certificado SSL y formulario de contacto funcional.\n" .
                        "- Panel admin para actualizar contenido (texto/imágenes/proyectos) sin depender del desarrollador.\n" .
                        "- Manual (PDF o documento) + capacitación de uso (sesión remota).\n",
                    'terms_conditions' => "Condiciones comerciales:\n" .
                        "- Moneda: PEN (S/).\n" .
                        "- Subtotal: S/ 800.00\n" .
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

            // Total items (subtotal) = 800.00
            // IGV (18%) = 144.00
            // Total con IGV = 944.00
            $items = [
                [
                    'name' => 'Vista: Inicio (Home / Presentación Personal)',
                    'description' => "Diseño e implementación de la vista de Inicio con presentación personal: foto profesional, descripción breve, especialidades/habilidades destacadas, llamada a la acción y enlaces a redes sociales.",
                    'quantity' => 1,
                    'unit' => 'vista',
                    'unit_price' => 180,
                    'tasks' => [
                        [
                            'name' => 'Estructura de secciones + contenido base',
                            'description' => 'Definición de secciones (hero con foto y nombre, especialidades/habilidades, llamada a acción, proyectos destacados preview, redes sociales).',
                            'duration_value' => 2,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Maquetación responsive (móvil/tablet/desktop)',
                            'description' => 'Construcción del layout responsive, diseño moderno orientado a portafolio profesional, tipografías, espaciados y componentes reutilizables.',
                            'duration_value' => 7,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Integración de redes sociales',
                            'description' => 'Implementación de íconos/enlaces a redes sociales (LinkedIn, GitHub, Twitter, Instagram, etc.) con diseño atractivo.',
                            'duration_value' => 2,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Integración con contenido editable (panel admin)',
                            'description' => 'Conectar secciones de Home a campos editables (textos, foto, links de redes sociales) para que el cliente actualice sin soporte técnico.',
                            'duration_value' => 3,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Optimización básica (SEO/Performance) + QA',
                            'description' => 'Metatags básicos (Open Graph para redes), títulos, compresión de imágenes, revisión de links y pruebas de navegación.',
                            'duration_value' => 2,
                            'duration_unit' => 'hours',
                        ],
                    ],
                ],
                [
                    'name' => 'Vista: Sobre Mí (Experiencia y Trayectoria)',
                    'description' => 'Vista para presentar la experiencia profesional, trayectoria laboral, educación, certificaciones y habilidades del profesional.',
                    'quantity' => 1,
                    'unit' => 'vista',
                    'unit_price' => 150,
                    'tasks' => [
                        [
                            'name' => 'Definición de contenido y estructura',
                            'description' => 'Organización del contenido (biografía, experiencia laboral con línea de tiempo, educación, certificaciones, habilidades técnicas y blandas).',
                            'duration_value' => 2,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Maquetación de sección de experiencia profesional',
                            'description' => 'Diseño de línea de tiempo o cards para mostrar experiencia laboral (empresa, cargo, período, logros) de forma atractiva.',
                            'duration_value' => 5,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Sección de habilidades y competencias',
                            'description' => 'Implementación visual de habilidades técnicas (barras de progreso, íconos, tags) y habilidades blandas.',
                            'duration_value' => 3,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Integración con panel admin (textos e imágenes)',
                            'description' => 'Configuración para que el contenido de Sobre Mí sea editable (textos, experiencias, habilidades) desde el panel.',
                            'duration_value' => 3,
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
                    'name' => 'Vista: Portafolio (Trabajos y Proyectos)',
                    'description' => 'Implementación de portafolio para mostrar proyectos realizados, trabajos destacados con imágenes, descripciones y tecnologías utilizadas.',
                    'quantity' => 1,
                    'unit' => 'vista',
                    'unit_price' => 180,
                    'tasks' => [
                        [
                            'name' => 'Estructura de portafolio (listado + ficha de proyecto)',
                            'description' => 'Definir cómo se mostrarán los proyectos: tarjetas con imagen, categorías/filtros (si aplica), y estructura del detalle (galería, descripción, tecnologías, enlaces).',
                            'duration_value' => 2,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Maquetación de listado de proyectos',
                            'description' => 'Diseño del grid de proyectos con efectos hover, filtros por categoría/tecnología (opcional) y responsive.',
                            'duration_value' => 6,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Implementación de detalle de proyecto',
                            'description' => 'Página/modal del proyecto con galería de imágenes, descripción del proyecto, rol desempeñado, tecnologías usadas, enlace al proyecto/demo si aplica.',
                            'duration_value' => 6,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Integración con panel admin (CRUD de proyectos)',
                            'description' => 'Crear/editar/eliminar proyectos desde el panel: título, descripción, imágenes, tecnologías, enlaces, orden y visibilidad.',
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
                    'description' => 'Vista de contacto con datos personales, enlaces a redes sociales prominentes y formulario de contacto con validaciones.',
                    'quantity' => 1,
                    'unit' => 'vista',
                    'unit_price' => 100,
                    'tasks' => [
                        [
                            'name' => 'Maquetación de la vista de Contacto',
                            'description' => 'Diseño de layout (datos de contacto, íconos de redes sociales grandes, horarios de disponibilidad si aplica) y consistencia con el resto del sitio.',
                            'duration_value' => 3,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Sección destacada de redes sociales',
                            'description' => 'Implementación de sección con enlaces grandes y atractivos a todas las redes sociales profesionales (LinkedIn, GitHub, etc.).',
                            'duration_value' => 2,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Formulario de contacto (validaciones + anti-spam básico)',
                            'description' => 'Campos (nombre, correo, asunto, mensaje), validaciones, mensajes de éxito/error y anti-spam básico (honeypot o similar).',
                            'duration_value' => 4,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Notificaciones y pruebas de envío',
                            'description' => 'Configuración de envío por email al propietario del sitio, pruebas end-to-end y verificación de entregabilidad.',
                            'duration_value' => 2,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Contenido editable desde panel',
                            'description' => 'Configurar para que correo, teléfono, links de redes sociales y textos de Contacto sean editables en el panel.',
                            'duration_value' => 1,
                            'duration_unit' => 'hours',
                        ],
                    ],
                ],
                [
                    'name' => 'Panel de administración (edición de contenido) + capacitación + manual',
                    'description' => 'Panel para gestionar contenido del sitio (secciones, textos, imágenes, experiencia, proyectos, redes sociales). Incluye capacitación y manual.',
                    'quantity' => 1,
                    'unit' => 'módulo',
                    'unit_price' => 130,
                    'tasks' => [
                        [
                            'name' => 'Configuración de acceso al panel (usuarios/seguridad básica)',
                            'description' => 'Habilitar acceso seguro, credenciales iniciales, buenas prácticas de contraseñas y protección básica de rutas.',
                            'duration_value' => 3,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Módulo de contenido editable (Inicio/Sobre Mí/Contacto)',
                            'description' => 'Campos editables para textos, imágenes, experiencias y enlaces por sección. Validaciones y previsualización básica cuando aplique.',
                            'duration_value' => 6,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Módulo de gestión de proyectos del portafolio',
                            'description' => 'CRUD completo para proyectos: crear, editar, eliminar, ordenar y gestionar visibilidad de proyectos en el portafolio.',
                            'duration_value' => 4,
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
                    'unit_price' => 60,
                    'tasks' => [
                        [
                            'name' => 'Selección de proveedor (hosting/dominio) + compra',
                            'description' => 'Selección del plan acorde a una web personal/portafolio y compra/registro del dominio. Recolección de datos necesarios para titularidad.',
                            'duration_value' => 2,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Configuración inicial de hosting (cuenta, SSL, variables)',
                            'description' => 'Creación de sitio en hosting, configuración de SSL (Let\'s Encrypt si aplica), y parámetros básicos para despliegue.',
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
