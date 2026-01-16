<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\Lead;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class JenniferReichertAnguloRealEstateSystemQuoteSeeder extends Seeder
{
    /**
     * Crea una NUEVA cotización para:
     * - Cliente: Jennifer Reichert Angulo
     * - Empresa: CONTROL DE CIERRES / RFC: CCI171201CX4
     *
     * Alcance (USD):
     * 1) App web interna para administración de propiedades (400 USD)
     * 2) Sitio web público (vistas) para usuarios finales (200 USD)
     * 3) Integración EasyBroker (sincronización de propiedades) (100 USD)
     * 4) Integración CRM (sincronización de leads/contactos) (100 USD)
     */
    public function run(): void
    {
        DB::transaction(function () {
            // =========================
            // Datos del cliente/empresa
            // =========================
            $clientName = 'Jennifer Reichert Angulo';

            // Nota: se conserva el mismo email del seeder anterior para evitar duplicados.
            // Ajusta si ya tienes el email real.
            $clientEmail = 'jennifer.reichert.angulo@example.com';
            $clientPhone = null;
            $clientAddress = null;

            $companyName = 'CONTROL DE CIERRES';
            $companyRfc = 'CCI171201CX4';
            $companyExtraId = 'idCIF: 18010555966';

            // =========================
            // Moneda: USD
            // =========================
            $usd = Currency::firstOrCreate(
                ['code' => 'USD'],
                [
                    'name' => 'Dólar Estadounidense',
                    'symbol' => '$',
                    'exchange_rate' => 1.000000,
                    'is_base' => false,
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
                    'company_ruc' => $companyRfc,
                    'project_type' => 'Desarrollo sitio web + sistema interno inmobiliario + integraciones (EasyBroker/CRM)',
                    'budget_up_to' => 800,
                    'message' => "Cotización solicitada para: {$companyName} ({$companyRfc}). {$companyExtraId}.",
                    'status' => 'new',
                    'source' => 'seed',
                    'notes' => 'Lead actualizado automáticamente desde seeder para generar una nueva cotización.',
                ]
            );

            // =========================
            // Usuario creador (interno)
            // =========================
            $createdBy = User::where('email', 'gusgusnoriega@gmail.com')->first()
                ?? User::first()
                ?? $clientUser;

            // =========================
            // Cotización (USD)
            // =========================
            $quoteNumber = 'COT-CCI171201CX4-SYS-0002';

            $quote = Quote::withTrashed()->updateOrCreate(
                ['quote_number' => $quoteNumber],
                [
                    'user_id' => $clientUser->id,
                    'created_by' => $createdBy?->id,
                    'title' => 'Cotización: Sitio web + App interna de administración inmobiliaria + EasyBroker + CRM',
                    'description' => "Desarrollo de una solución web completa para gestión y publicación de propiedades inmobiliarias.\n\n" .
                        "Incluye:\n" .
                        "1) App web interna para administración (módulos + seguridad + panel).\n" .
                        "2) Sitio web público (vistas) para visitantes (propiedades, detalle, contacto, etc.).\n" .
                        "3) Integración con EasyBroker para sincronizar propiedades.\n" .
                        "4) Integración con CRM externo para sincronizar leads/contactos y seguimiento.",
                    'currency_id' => $usd->id,
                    'tax_rate' => 0,
                    'discount_amount' => 0,
                    'status' => 'draft',
                    'valid_until' => now()->addDays(15)->toDateString(),
                    'estimated_start_date' => now()->addDays(3)->toDateString(),
                    'notes' => "Requisitos de inicio:\n" .
                        "- Definición de dominios/ambientes (staging/producción) y acceso a hosting/servidor.\n" .
                        "- Acceso a EasyBroker (API Key/credenciales) y reglas de sincronización (qué propiedades, qué campos).\n" .
                        "- Acceso al CRM (documentación API, credenciales, endpoints, flujos de lead y estados).\n" .
                        "- Manual de marca (logo, paleta, tipografías) y referencias de UI.\n\n" .
                        "Entregables:\n" .
                        "- App interna (admin) operativa con roles/permisos y CRUD de propiedades.\n" .
                        "- Sitio web público con vistas principales y formularios.\n" .
                        "- Sincronización EasyBroker y CRM documentadas (configuración + troubleshooting).\n" .
                        "- Manual básico de operación + checklist de QA.",
                    'terms_conditions' => "Condiciones comerciales:\n" .
                        "- Moneda: USD.\n" .
                        "- Forma de pago sugerida: 50% anticipo / 50% contra entrega.\n" .
                        "- Alcance sujeto a aprobación de UI, contenido y entrega de accesos.\n" .
                        "- No incluye: costos de hosting/CDN, licencias premium, compra de plantillas, carga masiva de contenido fuera del alcance ni integraciones adicionales no descritas.\n" .
                        "- Cambios mayores fuera del alcance se cotizan por separado.",
                    'client_ruc' => $companyRfc,
                    'client_phone' => $clientPhone,
                    'client_address' => $clientAddress,
                ]
            );

            if (method_exists($quote, 'trashed') && $quote->trashed()) {
                $quote->restore();
            }

            // Reemplazar items y tareas (idempotente)
            $quote->items()->delete();

            $items = [
                [
                    'name' => '1) Desarrollo de App Web interna (Administración de propiedades inmobiliarias)',
                    'description' => "Construcción de un panel de administración (backoffice) para gestionar usuarios, permisos, catálogo, propiedades, contenido y operaciones del negocio inmobiliario.\n\n" .
                        "Incluye arquitectura base, autenticación/seguridad, y módulos funcionales como tareas detalladas.",
                    'quantity' => 1,
                    'unit' => 'servicio',
                    'unit_price' => 400,
                    'tasks' => [
                        [
                            'name' => 'Base del proyecto + arquitectura + autenticación',
                            'description' => "Estructura inicial del proyecto, configuración de ambientes, autenticación (login/logout/recuperación), layout del panel, navegación, políticas de seguridad y estándares de desarrollo.",
                            'duration_value' => 12,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Módulo de Usuarios',
                            'description' => "CRUD de usuarios internos (alta/edición/baja), activación/desactivación, restablecimiento de contraseña, validaciones y gestión de perfil.\nIncluye búsqueda, paginación y filtros básicos.",
                            'duration_value' => 10,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Módulo de Roles, Permisos y RBAC',
                            'description' => "Implementación de control de acceso basado en roles/permisos (RBAC): creación de roles, asignación de permisos, y protección de rutas/acciones (crear/editar/eliminar/ver).\nIncluye matriz de permisos y pruebas de acceso.",
                            'duration_value' => 12,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Módulo de Moneda y Formateo de precios',
                            'description' => "Gestión de monedas y configuración de formato de precio (símbolo, separadores, decimales) para mostrar/importar valores de propiedades.\nIncluye reglas para venta/renta y consistencia en reportes.",
                            'duration_value' => 6,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Módulo de Categorías / Tipos de propiedad',
                            'description' => "CRUD de categorías/tipos (casa, departamento, terreno, etc.), etiquetas y clasificaciones necesarias para organizar y filtrar propiedades.\nIncluye orden, estado (activo/inactivo) y validaciones.",
                            'duration_value' => 6,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Módulo de Propiedades (core)',
                            'description' => "CRUD completo de propiedades: datos generales (título, descripción), precio y operación (venta/renta), ubicación, características (recámaras/baños/metros), amenidades, estado de publicación (borrador/publicado), y manejo de galerías.\nIncluye búsqueda avanzada, filtros y ordenamientos para operación interna.",
                            'duration_value' => 22,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Módulo de Media / Galería (imágenes y archivos)',
                            'description' => "Gestión de imágenes: subida, recorte/optimización básica, asignación de imágenes a propiedades, imagen destacada, orden de galería y limpieza de recursos.\nIncluye integración con el administrador de medios del sistema.",
                            'duration_value' => 10,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Módulo de Leads/Contactos (captura interna)',
                            'description' => "Registro y administración de leads generados por formularios del sitio: listado, detalle, estado, etiquetas/notas, asignación a usuario y exportación básica.\nPreparación para sincronización con CRM.",
                            'duration_value' => 8,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Módulo de Configuración del sistema',
                            'description' => "Parámetros globales: datos de la empresa, información de contacto, textos generales, configuración de integraciones (credenciales), y ajustes de publicación (por ejemplo: propiedades a mostrar).",
                            'duration_value' => 8,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Auditoría, logs, QA y documentación del panel',
                            'description' => "Registro de acciones relevantes (creación/edición/eliminación), bitácora básica de sincronizaciones, pruebas funcionales del panel y documentación de uso (roles, pantallas, flujos).",
                            'duration_value' => 6,
                            'duration_unit' => 'hours',
                        ],
                    ],
                ],
                [
                    'name' => '2) Sitio web público (vistas para usuarios finales)',
                    'description' => "Diseño e implementación de las vistas públicas del sitio: navegación, secciones informativas y catálogo de propiedades consumiendo la información del sistema interno.\nCada vista se entrega responsive (móvil/tablet/desktop) y con SEO básico.",
                    'quantity' => 1,
                    'unit' => 'servicio',
                    'unit_price' => 200,
                    'tasks' => [
                        [
                            'name' => 'Componentes globales (Header, Footer, Menú, CTAs)',
                            'description' => 'Estructura reutilizable del sitio: navegación principal, pie de página, enlaces legales/redes, estilos y componentes base para consistencia visual.',
                            'duration_value' => 6,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Página Inicio',
                            'description' => 'Home con secciones clave (hero, buscador/CTA, propiedades destacadas, beneficios, testimonios o similar).',
                            'duration_value' => 8,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Página Quiénes Somos',
                            'description' => 'Vista institucional con misión/visión, equipo/experiencia, valores y secciones informativas editables.',
                            'duration_value' => 5,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Página Propiedades (listado + filtros)',
                            'description' => 'Listado con paginación, ordenamiento y filtros (tipo, operación, rango de precio, recámaras/baños, zona). Incluye estado vacío y loading.',
                            'duration_value' => 10,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Página Propiedad individual (detalle)',
                            'description' => 'Detalle con galería, descripción, amenidades, mapa/ubicación (si aplica), fichas técnicas, CTA de contacto y propiedades relacionadas.',
                            'duration_value' => 10,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Página Contacto + formulario',
                            'description' => 'Formulario de contacto con validaciones, anti-spam básico, envío de lead al sistema y mensajes de confirmación.',
                            'duration_value' => 6,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Páginas legales y utilitarias (Privacidad, Términos, 404)',
                            'description' => 'Implementación de páginas requeridas para cumplimiento y UX: aviso de privacidad, términos y condiciones, y página 404 con navegación de regreso.',
                            'duration_value' => 4,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'SEO técnico básico + performance + responsive QA',
                            'description' => 'Metadatos por vista, títulos H1/H2, URL amigables, sitemap/robots si aplica, optimizaciones básicas y validación responsive/cross-browser.',
                            'duration_value' => 6,
                            'duration_unit' => 'hours',
                        ],
                    ],
                ],
                [
                    'name' => '3) Integración EasyBroker (sincronización de propiedades)',
                    'description' => 'Integración del sistema con EasyBroker para sincronizar propiedades (altas/actualizaciones), recursos multimedia y estados de publicación.',
                    'quantity' => 1,
                    'unit' => 'integración',
                    'unit_price' => 100,
                    'tasks' => [
                        [
                            'name' => 'Credenciales, conexión y configuración inicial (API Key)',
                            'description' => 'Alta de credenciales en el sistema, pruebas de conectividad, definición de límites y reglas de sincronización (propiedades a importar/publicar).',
                            'duration_value' => 4,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Mapeo de datos (EasyBroker → Sistema)',
                            'description' => 'Correspondencia de campos: tipo, operación, precio/moneda, ubicación, amenidades, galerías, estatus, y normalización de datos.',
                            'duration_value' => 6,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Job de sincronización (inicial + programado)',
                            'description' => 'Implementación de sincronización inicial y sincronización periódica (cron/queue), control de errores, reintentos y registro de resultados.',
                            'duration_value' => 8,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Sincronización de imágenes y medios',
                            'description' => 'Descarga/almacenamiento de imágenes, prevención de duplicados, orden de galería, imagen principal y políticas de limpieza.',
                            'duration_value' => 6,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Panel de control de sincronización + QA',
                            'description' => 'Pantalla/acciones para ejecutar sync manual, ver logs/errores, métricas básicas (cuántas importadas/actualizadas) y pruebas end-to-end.',
                            'duration_value' => 6,
                            'duration_unit' => 'hours',
                        ],
                    ],
                ],
                [
                    'name' => '4) Integración CRM (sincronización de leads/contactos)',
                    'description' => 'Sincronización con CRM externo para que los leads generados desde el sitio y el sistema interno se envíen al CRM y puedan consultarse/seguirse centralizadamente.',
                    'quantity' => 1,
                    'unit' => 'integración',
                    'unit_price' => 100,
                    'tasks' => [
                        [
                            'name' => 'Levantamiento de requerimientos + análisis de API',
                            'description' => 'Definir pipeline/estados, campos obligatorios, reglas anti-duplicado, autenticación (OAuth/API Key), rate limits y estrategia de errores.',
                            'duration_value' => 4,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Mapeo y normalización de datos (Web/Sistema → CRM)',
                            'description' => 'Construcción del payload (nombre, email, teléfono, interés, propiedad de origen, URL/UTMs), validaciones y transformaciones necesarias.',
                            'duration_value' => 6,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Implementación de envío de leads (colas/reintentos)',
                            'description' => 'Envío automático al CRM desde formularios y desde el panel, con colas, reintentos, logging y trazabilidad por lead.',
                            'duration_value' => 8,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Webhooks/actualización de estados (si aplica)',
                            'description' => 'Recepción de eventos del CRM para reflejar estados/propietario/seguimiento en el sistema (según capacidades del CRM).',
                            'duration_value' => 6,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'QA, despliegue y documentación',
                            'description' => 'Pruebas end-to-end, checklist de errores comunes, parámetros de configuración, y guía de operación (logs, credenciales, troubleshooting).',
                            'duration_value' => 6,
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

