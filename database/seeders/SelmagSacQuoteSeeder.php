<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\Lead;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SelmagSacQuoteSeeder extends Seeder
{
    /**
     * Crea:
     * - Usuario cliente (SEL MAG SAC) + Lead (RUC: 20601633206)
     * - Cotización en PEN (S/ 2,000.00 sin IGV) para:
     *   - Compra de hosting + dominio
     *   - Página de inicio (gratuita)
     *   - Sistema de cotizaciones y facturación con integración SUNAT (Perú)
     *   - Módulos: usuarios, monedas, cotizaciones, facturas
     *   - Módulo de roles y permisos para administración
     *
     * Nota: El cronograma considera un mínimo de 1 mes (tareas en días).
     */
    public function run(): void
    {
        DB::transaction(function () {
            // =========================
            // Datos del cliente/empresa
            // =========================
            $clientName = 'Selmag SAC';
            $clientEmail = 'sotoatencioc@gmail.com';
            $clientPhone = null;
            $clientAddress = null;

            $companyName = 'Selmag SAC';
            $companyRuc = '20601633206';

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
                    'project_type' => 'Sistema de cotizaciones y facturación (SUNAT Perú) + Roles/Permisos + Usuarios + Monedas + Hosting/Dominio + Página de inicio gratuita',
                    'budget_up_to' => 2000,
                    'message' => "Cotización solicitada para: {$companyName} (RUC: {$companyRuc}). Alcance: hosting+dominio + sistema de cotizaciones y facturación conectado a SUNAT + módulos administrativos.",
                    'status' => 'new',
                    'source' => 'seed',
                    'notes' => 'Lead creado automáticamente desde seeder para generar cotización formal del sistema de cotizaciones/facturación + SUNAT (Perú).',
                ]
            );

            // =========================
            // Usuario creador (interno)
            // =========================
            $createdBy = User::where('email', 'gusgusnoriega@gmail.com')->first()
                ?? User::first()
                ?? $clientUser;

            // =========================
            // Cotización (PEN) - SIN IGV
            // =========================
            $quoteNumber = 'COT-20601633206-SISTEMA-0001';

            $quote = Quote::withTrashed()->updateOrCreate(
                ['quote_number' => $quoteNumber],
                [
                    'user_id' => $clientUser->id,
                    'created_by' => $createdBy?->id,
                    'title' => "Cotización: Sistema de Cotizaciones y Facturación con SUNAT (Perú) + Hosting/Dominio + Admin (Roles/Permisos) - {$companyName}",
                    'description' => "Desarrollo e implementación de un sistema web para {$companyName} orientado a la gestión comercial y administrativa.\n\n" .
                        "Incluye (alcance principal):\n" .
                        "- Módulo de Usuarios (gestión de cuentas y seguridad).\n" .
                        "- Módulo de Monedas (PEN base + soporte multi-moneda si se requiere).\n" .
                        "- Módulo de Cotizaciones (CRUD, PDF, estados, numeración, items, impuestos configurables).\n" .
                        "- Módulo de Facturas (emisión, series/folios, exportaciones, seguimiento, estados).\n" .
                        "- Integración SUNAT (Perú) para comprobantes electrónicos (según alcance pactado y ambiente).\n" .
                        "- Roles y Permisos para administración (RBAC).\n" .
                        "- Compra/configuración de hosting + dominio.\n" .
                        "- Página de inicio de la empresa (gratuita) como landing informativa.\n\n" .
                        "Tiempo estimado de desarrollo: mínimo 1 mes (4-6 semanas), sujeto a disponibilidad de accesos, certificados, credenciales SUNAT (SOL) y validaciones del cliente.",
                    'currency_id' => $pen->id,
                    'tax_rate' => 0,
                    'discount_amount' => 0,
                    'status' => 'draft',
                    'valid_until' => now()->addDays(15)->toDateString(),
                    'estimated_start_date' => now()->addDays(1)->toDateString(),
                    'notes' => "Requisitos para iniciar:\n" .
                        "- Datos fiscales y parámetros de emisión (razón social, RUC, dirección fiscal, ubigeo si aplica).\n" .
                        "- Definición de series/formatos de numeración (cotizaciones y facturas).\n" .
                        "- Accesos y credenciales necesarios para SUNAT (SOL) o proveedor (si aplica).\n" .
                        "- Decisiones funcionales: catálogo de productos/servicios, impuestos, moneda base y reglas de redondeo.\n" .
                        "- Contenido para la página de inicio (logo, texto institucional, contacto).\n\n" .
                        "Entregables:\n" .
                        "- Sistema operativo en ambiente de hosting (staging/producción) con SSL.\n" .
                        "- Roles/Permisos configurados para administración.\n" .
                        "- Módulos de usuarios, monedas, cotizaciones y facturas.\n" .
                        "- Integración SUNAT (según credenciales/ambiente) + pruebas de emisión.\n" .
                        "- Página de inicio informativa (gratuita).\n" .
                        "- Manual básico de uso + capacitación." ,
                    'terms_conditions' => "Condiciones comerciales:\n" .
                        "- Moneda: PEN (S/).\n" .
                        "- Subtotal: S/ 2,000.00\n" .
                        "- IGV: 0% (no incluido).\n" .
                        "- Total: S/ 2,000.00\n" .
                        "- Forma de pago sugerida: 50% al iniciar (S/ 1,000.00) / 50% al finalizar y previo a despliegue final (S/ 1,000.00).\n" .
                        "- Plazo: mínimo 1 mes (4-6 semanas), sujeto a entregables del cliente y validaciones.\n" .
                        "- Hosting + dominio: se incluye gestión de compra/configuración; renovaciones anuales posteriores corren por cuenta del cliente (según proveedor/plan).\n" .
                        "- Alcance SUNAT: la integración depende de credenciales, ambiente (beta/producción) y reglas vigentes. Ajustes por cambios normativos posteriores pueden requerir mantenimiento.\n" .
                        "- Alcance: cualquier módulo adicional no descrito se cotiza por separado.",
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

            // Subtotal items = 2000.00
            // IGV = 0.00
            // Total = 2000.00
            $items = [
                [
                    'name' => 'Página de inicio (Landing) - Gratuita',
                    'description' => "Página de inicio informativa para {$companyName}. Incluye estructura básica, responsive y contenido institucional. (Sin costo)",
                    'quantity' => 1,
                    'unit' => 'vista',
                    'unit_price' => 0,
                    'tasks' => [
                        ['name' => 'Levantamiento de contenido', 'description' => 'Recopilar logo, textos institucionales, enlaces y datos de contacto.', 'duration_value' => 1, 'duration_unit' => 'days'],
                        ['name' => 'Maquetación responsive', 'description' => 'Implementación de layout responsive con secciones: hero, servicios/resumen, CTA y contacto.', 'duration_value' => 1, 'duration_unit' => 'days'],
                        ['name' => 'QA + publicación inicial', 'description' => 'Validación visual y pruebas de navegación, y publicación junto al sistema.', 'duration_value' => 1, 'duration_unit' => 'days'],
                    ],
                ],
                [
                    'name' => 'Hosting y Dominio (compra, configuración y despliegue)',
                    'description' => 'Gestión de compra/registro y configuración técnica de hosting+dominio (DNS, SSL) para publicar el sistema y la landing.',
                    'quantity' => 1,
                    'unit' => 'servicio',
                    'unit_price' => 300,
                    'tasks' => [
                        ['name' => 'Selección de proveedor + compra/registro', 'description' => 'Seleccionar hosting y registrar dominio acorde al sistema (SSL, PHP, BD, backups).', 'duration_value' => 1, 'duration_unit' => 'days'],
                        ['name' => 'Configuración técnica (SSL/DNS/BD)', 'description' => 'Configurar DNS, SSL (Let\'s Encrypt si aplica), base de datos y variables de entorno.', 'duration_value' => 1, 'duration_unit' => 'days'],
                        ['name' => 'Despliegue + smoke tests', 'description' => 'Deploy del sistema, pruebas básicas (login, creación de cotización, PDF, endpoints).', 'duration_value' => 1, 'duration_unit' => 'days'],
                    ],
                ],
                [
                    'name' => 'Módulo de Usuarios (administración y seguridad)',
                    'description' => 'Gestión de usuarios del sistema (alta/baja/edición), políticas de acceso y buenas prácticas de seguridad.',
                    'quantity' => 1,
                    'unit' => 'módulo',
                    'unit_price' => 150,
                    'tasks' => [
                        ['name' => 'Definición de roles operativos y flujos', 'description' => 'Definir perfiles (admin, ventas, contabilidad) y flujos básicos por rol.', 'duration_value' => 1, 'duration_unit' => 'days'],
                        ['name' => 'CRUD de usuarios + validaciones', 'description' => 'Pantallas y endpoints para crear/editar/desactivar usuarios, validaciones y reset de contraseña.', 'duration_value' => 2, 'duration_unit' => 'days'],
                        ['name' => 'Auditoría básica y hardening', 'description' => 'Revisión de permisos, protección de rutas y controles básicos.', 'duration_value' => 1, 'duration_unit' => 'days'],
                    ],
                ],
                [
                    'name' => 'Módulo de Monedas (PEN base + configuración)',
                    'description' => 'Administración de monedas: PEN como base y configuración de tasas/códigos/símbolos para cotizaciones/facturación.',
                    'quantity' => 1,
                    'unit' => 'módulo',
                    'unit_price' => 100,
                    'tasks' => [
                        ['name' => 'Catálogo de monedas + reglas', 'description' => 'Definir moneda base, formato y reglas de redondeo.', 'duration_value' => 1, 'duration_unit' => 'days'],
                        ['name' => 'CRUD de monedas + pruebas', 'description' => 'Gestión de monedas y validación en cotizaciones/facturas.', 'duration_value' => 1, 'duration_unit' => 'days'],
                    ],
                ],
                [
                    'name' => 'Módulo de Roles y Permisos (RBAC) para administración',
                    'description' => 'Configuración de roles/permisos para controlar el acceso a pantallas y acciones del sistema (administración, ventas, contabilidad).',
                    'quantity' => 1,
                    'unit' => 'módulo',
                    'unit_price' => 250,
                    'tasks' => [
                        ['name' => 'Matriz de permisos', 'description' => 'Definir matriz de permisos (ver/crear/editar/anular/exportar) por módulo.', 'duration_value' => 2, 'duration_unit' => 'days'],
                        ['name' => 'Implementación de políticas/guards', 'description' => 'Aplicar permisos a rutas/controladores y UI (menús/acciones).', 'duration_value' => 2, 'duration_unit' => 'days'],
                        ['name' => 'QA de seguridad (pruebas de acceso)', 'description' => 'Pruebas por rol para evitar accesos indebidos.', 'duration_value' => 1, 'duration_unit' => 'days'],
                    ],
                ],
                [
                    'name' => 'Módulo de Cotizaciones (CRUD, PDF, items, estados)',
                    'description' => 'Gestión completa de cotizaciones: creación, edición, numeración, estados, items, descuentos, impuestos configurables y exportación PDF.',
                    'quantity' => 1,
                    'unit' => 'módulo',
                    'unit_price' => 400,
                    'tasks' => [
                        ['name' => 'Relevamiento funcional y reglas de negocio', 'description' => 'Definir flujo: borrador/enviado/aceptado/rechazado, numeración, vigencia y plantillas.', 'duration_value' => 2, 'duration_unit' => 'days'],
                        ['name' => 'CRUD de cotizaciones + items', 'description' => 'Pantallas y endpoints para cotización + items, importes, impuestos y descuentos.', 'duration_value' => 3, 'duration_unit' => 'days'],
                        ['name' => 'Generación PDF + plantillas', 'description' => 'Diseño y generación de PDF con totales y datos del cliente.', 'duration_value' => 2, 'duration_unit' => 'days'],
                        ['name' => 'QA (cálculos, formatos, permisos)', 'description' => 'Pruebas de cálculos, formatos, permisos y escenarios comunes.', 'duration_value' => 2, 'duration_unit' => 'days'],
                    ],
                ],
                [
                    'name' => 'Módulo de Facturación + Integración SUNAT (Perú)',
                    'description' => 'Emisión y administración de facturas/boletas (según alcance) con integración para comprobantes electrónicos (SUNAT). Incluye pruebas en ambiente disponible.',
                    'quantity' => 1,
                    'unit' => 'módulo',
                    'unit_price' => 800,
                    'tasks' => [
                        ['name' => 'Análisis de requerimientos SUNAT', 'description' => 'Definir tipo de comprobantes (factura/boleta/nota), catálogo, reglas y campos obligatorios.', 'duration_value' => 3, 'duration_unit' => 'days'],
                        ['name' => 'Modelado de datos (series, correlativos, estados)', 'description' => 'Definir estructura para series/correlativos, estados (emitida, enviada, aceptada, observada, rechazada, anulada) y trazabilidad.', 'duration_value' => 3, 'duration_unit' => 'days'],
                        ['name' => 'Implementación de facturación (CRUD + reportes)', 'description' => 'Pantallas para emisión, consulta, exportación y reportes básicos.', 'duration_value' => 4, 'duration_unit' => 'days'],
                        ['name' => 'Integración técnica SUNAT (API/servicio)', 'description' => 'Implementación de envío/consulta de estado, manejo de respuestas y errores. Considerar certificados/credenciales.', 'duration_value' => 6, 'duration_unit' => 'days'],
                        ['name' => 'Pruebas (beta/producción) + correcciones', 'description' => 'Pruebas de emisión, validaciones, casos de error, reintentos y correcciones.', 'duration_value' => 5, 'duration_unit' => 'days'],
                        ['name' => 'Documentación + capacitación', 'description' => 'Manual de emisión/flujo y capacitación al personal.', 'duration_value' => 2, 'duration_unit' => 'days'],
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

