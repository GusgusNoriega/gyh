<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\SmtpSetting;
use App\Services\EmailTemplateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LeadController extends Controller
{
    /**
     * GET /api/leads (admin)
     */
    public function index(Request $request): JsonResponse
    {
        $query = Lead::query();

        if ($request->filled('search')) {
            $search = trim((string)$request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%")
                    ->orWhere('company_ruc', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', (string)$request->input('status'));
        }

        $sort = $request->input('sort', 'desc');
        $order = $request->input('order', 'created_at');
        $validOrders = ['created_at', 'updated_at', 'name', 'email', 'status'];
        if (!in_array($order, $validOrders, true)) {
            $order = 'created_at';
        }
        $sort = $sort === 'asc' ? 'asc' : 'desc';
        $query->orderBy($order, $sort);

        $perPage = (int)$request->input('per_page', 15);
        $perPage = max(1, min(100, $perPage));
        $data = $query->paginate($perPage);

        return $this->apiSuccess('Listado de leads', 'LEADS_LIST', $data);
    }

    /**
     * POST /api/leads (público) - recibe leads desde el formulario de marketing.
     * También permite creación manual (admin) si se usa desde el panel.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|string|max:50',

            'is_company' => 'nullable|boolean',
            'company_name' => 'nullable|string|max:255',
            'company_ruc' => 'nullable|string|max:30|required_if:is_company,1',

            'project_type' => 'nullable|string|max:60',
            'budget_up_to' => 'nullable|integer|min:0|max:2000000',
            'message' => 'nullable|string|max:20000',

            'status' => 'nullable|string|max:30',
            'notes' => 'nullable|string|max:20000',

            'source' => 'nullable|string|max:80',

            // Honeypot anti-spam: debe venir vacío
            'website' => 'nullable|string|max:0',
        ]);

        $validator->after(function ($validator) use ($request) {
            // Teléfono: mínimo 9 dígitos (se permiten símbolos como +, espacios, etc.)
            $phone = (string)($request->input('phone') ?? '');
            $digits = preg_replace('/\D+/', '', $phone);
            if (strlen($digits) < 9) {
                $validator->errors()->add('phone', 'El teléfono debe tener al menos 9 dígitos.');
            }

            // Si es empresa, el RUC debe venir con al menos 1 dígito
            $isCompany = filter_var($request->input('is_company'), FILTER_VALIDATE_BOOLEAN);
            if ($isCompany) {
                $ruc = (string)($request->input('company_ruc') ?? '');
                $rucDigits = preg_replace('/\D+/', '', $ruc);
                if (strlen($rucDigits) < 1) {
                    $validator->errors()->add('company_ruc', 'Si seleccionas empresa, el RUC es obligatorio.');
                }
            }
        });

        if ($validator->fails()) {
            return $this->apiValidationError($validator->errors()->toArray());
        }

        $data = $validator->validated();

        // Normalización básica
        $data['is_company'] = (bool)($data['is_company'] ?? false);
        $data['status'] = $data['status'] ?? 'new';
        $data['source'] = $data['source'] ?? 'marketing_home';

        // Metadatos
        $data['ip'] = $request->ip();
        $data['user_agent'] = substr((string)$request->userAgent(), 0, 5000);

        // Token para página de gracias (lectura única)
        do {
            $token = Str::random(48);
        } while (Lead::where('thank_you_token', $token)->exists());
        $data['thank_you_token'] = $token;

        // Si no es empresa, limpiar campos asociados
        if (!$data['is_company']) {
            $data['company_name'] = null;
            $data['company_ruc'] = null;
        }

        $lead = Lead::create($data);

        // Notificación por correo (no debe bloquear el guardado del lead)
        $this->trySendLeadNotificationEmail($lead);

        return $this->apiCreated('Lead registrado', 'LEAD_CREATED', $lead);
    }

    /**
     * Envía un correo interno con la información del lead usando la plantilla
     * "lead_form_notification".
     *
     * Destinatarios:
     * - Se leen desde smtp_settings.key = leads_notification_emails
     * - Soporta múltiples correos separados por coma/punto y coma/espacios
     */
    protected function trySendLeadNotificationEmail(Lead $lead): void
    {
        try {
            $to = $this->resolveLeadNotificationRecipients();

            if (empty($to)) {
                Log::warning('[LeadController] No se envió notificación de lead: no hay destinatarios configurados', [
                    'lead_id' => $lead->id,
                    'setting_key' => 'leads_notification_emails',
                ]);

                return;
            }

            $service = app(EmailTemplateService::class);

            $service->send(
                templateKey: 'lead_form_notification',
                to: $to,
                data: [
                    'app_name' => config('app.name', 'Mi Aplicación'),
                    'lead_id' => (string) $lead->id,
                    'name' => (string) ($lead->name ?? ''),
                    'email' => (string) ($lead->email ?? ''),
                    'phone' => (string) ($lead->phone ?? ''),
                    'is_company' => $lead->is_company ? 'Sí' : 'No',
                    'company_name' => (string) ($lead->company_name ?? ''),
                    'company_ruc' => (string) ($lead->company_ruc ?? ''),
                    'project_type' => (string) ($lead->project_type ?? ''),
                    'budget_up_to' => $lead->budget_up_to !== null ? (string) $lead->budget_up_to : '',
                    'message' => (string) ($lead->message ?? ''),
                    'source' => (string) ($lead->source ?? ''),
                    'ip' => (string) ($lead->ip ?? ''),
                    'user_agent' => (string) ($lead->user_agent ?? ''),
                    'created_at' => $lead->created_at ? $lead->created_at->format('Y-m-d H:i:s') : now()->format('Y-m-d H:i:s'),
                ],
                replyTo: (filter_var((string) ($lead->email ?? ''), FILTER_VALIDATE_EMAIL) ? (string) $lead->email : null),
                replyToName: (string) ($lead->name ?? null)
            );
        } catch (\Throwable $e) {
            Log::error('[LeadController] Error enviando notificación de lead', [
                'lead_id' => $lead->id,
                'exception_message' => $e->getMessage(),
                'exception_class' => get_class($e),
                'exception_file' => $e->getFile() . ':' . $e->getLine(),
            ]);
        }
    }

    /**
     * @return array<int, string>
     */
    protected function resolveLeadNotificationRecipients(): array
    {
        // 1) Preferir configuración en BD para permitir edición desde el panel
        $raw = (string) (SmtpSetting::getValue('leads_notification_emails', '') ?? '');

        // 2) Fallback a la configuración SMTP From (útil si aún no se configura el campo)
        if (trim($raw) === '') {
            $raw = (string) (SmtpSetting::getValue('smtp_from_address', '') ?? '');
        }

        // 3) Fallback final al .env / config mail
        if (trim($raw) === '') {
            $raw = (string) (config('mail.from.address') ?? '');
        }

        $parts = preg_split('/[;,\s]+/', $raw, -1, PREG_SPLIT_NO_EMPTY) ?: [];

        $emails = [];
        foreach ($parts as $email) {
            $email = trim((string) $email);
            if ($email === '') {
                continue;
            }
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emails[] = $email;
            }
        }

        // unique + reindex
        $emails = array_values(array_unique($emails));

        return $emails;
    }

    /**
     * GET /gracias/{token} (público) - muestra datos del lead una sola vez.
     */
    public function thankYou(Request $request, string $token): View
    {
        $lead = Lead::where('thank_you_token', $token)->first();

        if (!$lead) {
            return view('marketing.thank-you', ['lead' => null, 'expired' => true]);
        }

        // Lectura única: si ya se visualizó, no mostrar datos.
        if ($lead->thank_you_viewed_at) {
            return view('marketing.thank-you', ['lead' => null, 'expired' => true]);
        }

        $lead->forceFill(['thank_you_viewed_at' => now()])->save();

        return view('marketing.thank-you', ['lead' => $lead, 'expired' => false]);
    }

    /**
     * GET /api/leads/{id} (admin)
     */
    public function show(Request $request, $id): JsonResponse
    {
        $lead = Lead::find($id);

        if (!$lead) {
            return $this->apiNotFound('Lead no encontrado', 'LEAD_NOT_FOUND');
        }

        return $this->apiSuccess('Lead obtenido', 'LEAD_SHOWN', $lead);
    }

    /**
     * PATCH /api/leads/{id} (admin)
     */
    public function update(Request $request, $id): JsonResponse
    {
        $lead = Lead::find($id);

        if (!$lead) {
            return $this->apiNotFound('Lead no encontrado', 'LEAD_NOT_FOUND');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|nullable|string|max:255',
            'email' => 'sometimes|nullable|string|email|max:255',
            'phone' => 'sometimes|nullable|string|max:50',

            'is_company' => 'sometimes|nullable|boolean',
            'company_name' => 'sometimes|nullable|string|max:255',
            'company_ruc' => 'sometimes|nullable|string|max:30',

            'project_type' => 'sometimes|nullable|string|max:60',
            'budget_up_to' => 'sometimes|nullable|integer|min:0|max:2000000',
            'message' => 'sometimes|nullable|string|max:20000',

            'status' => 'sometimes|nullable|string|max:30',
            'notes' => 'sometimes|nullable|string|max:20000',

            'source' => 'sometimes|nullable|string|max:80',
        ]);

        if ($validator->fails()) {
            return $this->apiValidationError($validator->errors()->toArray());
        }

        $data = $validator->validated();

        if (array_key_exists('is_company', $data) && !(bool)$data['is_company']) {
            $data['company_name'] = null;
            $data['company_ruc'] = null;
        }

        $lead->update($data);

        return $this->apiSuccess('Lead actualizado', 'LEAD_UPDATED', $lead);
    }

    /**
     * DELETE /api/leads/{id} (admin)
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $lead = Lead::find($id);

        if (!$lead) {
            return $this->apiNotFound('Lead no encontrado', 'LEAD_NOT_FOUND');
        }

        $lead->delete();

        return $this->apiSuccess('Lead eliminado', 'LEAD_DELETED', null);
    }
}

