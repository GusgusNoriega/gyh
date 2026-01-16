<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\Lead;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class JenniferReichertAnguloQuoteSeeder extends Seeder
{
    /**
     * Crea:
     * - Usuario cliente (Jennifer Reichert Angulo)
     * - Lead de empresa (CONTROL DE CIERRES / RFC: CCI171201CX4)
     * - Cotización en USD con 3 ítems + plan de trabajo detallado (tareas)
     */
    public function run(): void
    {
        DB::transaction(function () {
            // =========================
            // Datos del cliente/empresa
            // =========================
            $clientName = 'Jennifer Reichert Angulo';

            // TODO: ajusta estos datos si ya tienes el email/teléfono real.
            $clientEmail = 'jennifer.reichert.angulo@example.com';
            $clientPhone = null;
            $clientAddress = null;

            // Datos de empresa (obtenidos del documento compartido)
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
                    'project_type' => 'Optimización + Integración EasyBroker + Actualización de diseño (WordPress)',
                    'budget_up_to' => 500,
                    'message' => "Cotización solicitada para: {$companyName} ({$companyRfc}). {$companyExtraId}.",
                    'status' => 'new',
                    'source' => 'seed',
                    'notes' => 'Lead creado automáticamente desde seeder para generar cotización.',
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
            $quoteNumber = 'COT-CCI171201CX4-WP-0001';

            // Importante: Quote usa SoftDeletes, por lo que si ya existía una cotización
            // con ese quote_number pero quedó "borrada", un updateOrCreate normal
            // intentará insertar y fallará por el índice unique.
            $quote = Quote::withTrashed()->updateOrCreate(
                ['quote_number' => $quoteNumber],
                [
                    'user_id' => $clientUser->id,
                    'created_by' => $createdBy?->id,
                    'title' => 'Cotización: Optimización + EasyBroker + CRM + Actualización de diseño (WordPress)',
                    'description' => "Sitio web existente en WordPress. Alcance del servicio:\n" .
                        "1) Optimización de rendimiento para mejorar velocidad y métricas Core Web Vitals.\n" .
                        "2) Integración del sitio con EasyBroker para sincronización y publicación de propiedades.\n" .
                        "3) Integración del sitio con un CRM externo para envío/gestión de leads y seguimiento.\n" .
                        "4) Actualización de vistas y UI según identidad de marca (colores/tipografías/componentes).",
                    'currency_id' => $usd->id,
                    'tax_rate' => 0,
                    'discount_amount' => 0,
                    'status' => 'draft',
                    'valid_until' => now()->addDays(15)->toDateString(),
                    'estimated_start_date' => now()->addDays(3)->toDateString(),
                    'notes' => "Requisitos de inicio:\n" .
                        "- Accesos a WordPress (admin) y hosting (cPanel/FTP/SSH si aplica).\n" .
                        "- Acceso a EasyBroker (API Key/credenciales) y confirmación de qué propiedades sincronizar.\n" .
                        "- Acceso al CRM externo (documentación API, credenciales, ambiente sandbox si existe) y definición de flujo de leads.\n" .
                        "- Manual de marca (logo, paleta de color, tipografías) o referencias visuales.\n\n" .
                        "Entregables:\n" .
                        "- Reporte de rendimiento (antes/después) y checklist de cambios.\n" .
                        "- Integración EasyBroker funcionando y documentada.\n" .
                        "- Integración CRM funcionando (envío/actualización de leads) y documentada.\n" .
                        "- Vistas actualizadas y consistentes con identidad visual.\n",
                    'terms_conditions' => "Condiciones comerciales:\n" .
                        "- Moneda: USD.\n" .
                        "- Forma de pago sugerida: 50% anticipo / 50% contra entrega.\n" .
                        "- Tiempos estimados sujetos a entrega de accesos y aprobación de diseño.\n" .
                        "- No incluye: compra de licencias premium, costos de hosting/CDN, contenido (copy/fotografía) ni desarrollos fuera del alcance descrito.\n" .
                        "- Se trabaja sobre un entorno staging cuando sea posible; publicación en producción al finalizar QA.",
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
                    'name' => 'Optimización de rendimiento (WordPress)',
                    'description' => 'Mejoras para que el sitio cargue más rápido: optimización de recursos, imágenes, caché y ajustes técnicos (sin cambiar contenido).',
                    'quantity' => 1,
                    'unit' => 'servicio',
                    'unit_price' => 100,
                    'tasks' => [
                        [
                            'name' => 'Auditoría inicial (Lighthouse / PageSpeed / GTmetrix)',
                            'description' => 'Medición de métricas actuales (LCP/INP/CLS), identificación de cuellos de botella, revisión de plugins/tema y definición de prioridades.',
                            'duration_value' => 2,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Backup y preparación de staging',
                            'description' => 'Respaldo completo y creación/validación de entorno de pruebas para aplicar cambios sin afectar producción.',
                            'duration_value' => 2,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Optimización de imágenes y media',
                            'description' => 'Compresión, conversión a WebP cuando aplique, lazy-load, tamaños responsivos y revisión de sliders/banners pesados.',
                            'duration_value' => 6,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Caché, minificación y optimización de assets',
                            'description' => 'Configuración de caché (página/objeto si aplica), minificación/combinar CSS/JS, defer/async y eliminación de recursos bloqueantes.',
                            'duration_value' => 6,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Optimización de base de datos',
                            'description' => 'Limpieza de revisiones/transients, optimización tablas y revisión de consultas generadas por plugins comunes.',
                            'duration_value' => 2,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'QA + medición final + reporte',
                            'description' => 'Pruebas de navegación, verificación de compatibilidad, medición final (antes/después) y entrega de reporte con recomendaciones.',
                            'duration_value' => 2,
                            'duration_unit' => 'hours',
                        ],
                    ],
                ],
                [
                    'name' => 'Integración WordPress + EasyBroker',
                    'description' => 'Conectar el sitio a EasyBroker para importar/sincronizar propiedades, publicar listados y habilitar navegación/búsqueda.',
                    'quantity' => 1,
                    'unit' => 'integración',
                    'unit_price' => 200,
                    'tasks' => [
                        [
                            'name' => 'Levantamiento de requerimientos y accesos',
                            'description' => 'Confirmar URLs, estructura de listados, criterios de sincronización, obtención de API Key y permisos necesarios.',
                            'duration_value' => 2,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Instalación/configuración del conector',
                            'description' => 'Instalar plugin/conector compatible, configurar credenciales, validar conectividad con API y ajustar settings básicos.',
                            'duration_value' => 3,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Mapeo de datos (campos y taxonomías)',
                            'description' => 'Definir correspondencia de campos (precio, ubicación, tipo, amenidades), categorías, estados y formatos de moneda/medidas.',
                            'duration_value' => 6,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Sincronización y pruebas de importación',
                            'description' => 'Ejecución de sync inicial, validación de propiedades importadas, imágenes, galerías, estados (venta/renta) y consistencia de datos.',
                            'duration_value' => 6,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Ajuste de vistas de propiedades (listado y detalle)',
                            'description' => 'Ajustar plantillas/shortcodes para mostrar fichas, galerías, mapa, contacto, y estructurar la información de forma clara.',
                            'duration_value' => 8,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Buscador y filtros',
                            'description' => 'Configurar filtros (tipo, ciudad/zona, recámaras, precio), paginación y ordenamientos, cuidando performance.',
                            'duration_value' => 4,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'QA + documentación',
                            'description' => 'Pruebas funcionales, validación en móvil/desktop, checklist de edge cases y documentación básica de operación.',
                            'duration_value' => 3,
                            'duration_unit' => 'hours',
                        ],
                    ],
                ],
                [
                    'name' => 'Integración WordPress + CRM externo',
                    'description' => 'Integración nueva con un CRM externo para capturar/enviar leads desde formularios del sitio, gestionar estados y asegurar trazabilidad. Duración estimada: 1.5 semanas (7.5 días hábiles).',
                    'quantity' => 1,
                    'unit' => 'integración',
                    'unit_price' => 200,
                    'tasks' => [
                        [
                            'name' => 'Kickoff + requerimientos + accesos (CRM/WordPress)',
                            'description' => 'Definir el flujo de lead (fuentes, campos obligatorios, estados), confirmar formularios existentes, obtener credenciales API, sandbox, y restricciones (rate limits, IPs, whitelists).',
                            'duration_value' => 0.5,
                            'duration_unit' => 'days',
                        ],
                        [
                            'name' => 'Análisis de API del CRM + diseño técnico',
                            'description' => 'Revisión de documentación, endpoints, modelo de datos, autenticación (OAuth/API Key), estrategia de reintentos, manejo de errores y diseño de mapeo WordPress → CRM.',
                            'duration_value' => 1,
                            'duration_unit' => 'days',
                        ],
                        [
                            'name' => 'Implementación base en WordPress (plugin/mu-plugin) + settings + auth',
                            'description' => 'Crear estructura de integración (plugin), pantalla de configuración, almacenamiento seguro de credenciales, cliente HTTP, logging básico y validación de conectividad.',
                            'duration_value' => 1.5,
                            'duration_unit' => 'days',
                        ],
                        [
                            'name' => 'Mapeo de datos + normalización + validaciones',
                            'description' => 'Definir y construir el payload (nombre, email, teléfono, interés, URL origen, UTMs), sanitización, campos custom del CRM y validaciones para evitar registros inválidos/duplicados.',
                            'duration_value' => 1,
                            'duration_unit' => 'days',
                        ],
                        [
                            'name' => 'Sincronización (envío de leads) + colas/cron + reintentos',
                            'description' => 'Conectar formularios (CF7/Elementor/Gravity u otro) para envío a CRM, implementar cola/cron si aplica, reintentos con backoff y almacenamiento de estado local para trazabilidad.',
                            'duration_value' => 1.5,
                            'duration_unit' => 'days',
                        ],
                        [
                            'name' => 'Webhooks/eventos (si aplica) + actualización de estados',
                            'description' => 'Configurar recepción de webhooks (cambios de estado/propietario), validación de firma, endpoint seguro, y actualización interna (o registro) para seguimiento.',
                            'duration_value' => 1,
                            'duration_unit' => 'days',
                        ],
                        [
                            'name' => 'QA, UAT, deploy y documentación',
                            'description' => 'Pruebas end-to-end (creación de lead, errores, rate limit), UAT con el cliente, despliegue a producción, guía de operación (credenciales, troubleshooting, logs) y checklist final.',
                            'duration_value' => 1,
                            'duration_unit' => 'days',
                        ],
                    ],
                ],
                [
                    'name' => 'Actualización de diseño / UI según identidad de marca',
                    'description' => 'Actualizar colores, tipografías y estilos visuales del tema para alinear el sitio con la identidad de marca (sin reconstruir desde cero).',
                    'quantity' => 1,
                    'unit' => 'diseño',
                    'unit_price' => 100,
                    'tasks' => [
                        [
                            'name' => 'Recolección de identidad (brand assets)',
                            'description' => 'Recibir paleta de colores, tipografías, logotipos, referencias; definir páginas/plantillas prioritarias.',
                            'duration_value' => 2,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Definición de estilos globales',
                            'description' => 'Configurar variables de color, tipografía, botones, links, formularios; ajustar theme settings/CSS para consistencia.',
                            'duration_value' => 6,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Actualización de componentes clave (header/footer)',
                            'description' => 'Actualizar menú, CTA, pie de página, iconografía, espaciados y jerarquía visual.',
                            'duration_value' => 8,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Ajuste de vistas principales (Home / Contacto / Listados)',
                            'description' => 'Aplicar la identidad a secciones clave, asegurar legibilidad, consistencia visual y mejor UX.',
                            'duration_value' => 10,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Responsive + accesibilidad básica',
                            'description' => 'Revisión móvil/tablet/desktop, contraste, tamaños de fuente, estados hover/focus y correcciones de layout.',
                            'duration_value' => 6,
                            'duration_unit' => 'hours',
                        ],
                        [
                            'name' => 'Ronda de feedback + ajustes finales',
                            'description' => 'Una ronda de cambios sobre lo implementado (ajustes menores) y publicación final tras aprobación.',
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

