<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\QuoteItemTask;
use App\Models\QuoteSetting;
use App\Services\QuotePdfService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class QuoteController extends Controller
{
    protected QuotePdfService $pdfService;

    public function __construct(QuotePdfService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    /**
     * Display a listing of quotes
     */
    public function index(Request $request): JsonResponse
    {
        $query = Quote::with(['user', 'creator', 'currency', 'items.tasks'])
            ->withCount('items');

        // Filtros
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('created_by')) {
            $query->where('created_by', $request->created_by);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('quote_number', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('client_name', 'like', "%{$search}%")
                    ->orWhere('client_email', 'like', "%{$search}%");
            });
        }

        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Ordenamiento
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Paginación
        $perPage = $request->get('per_page', 15);
        $quotes = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $quotes,
        ]);
    }

    /**
     * Store a newly created quote
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id',
            'currency_id' => 'nullable|exists:currencies,id',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'status' => ['nullable', Rule::in(Quote::STATUSES)],
            'valid_until' => 'nullable|date|after:today',
            'estimated_start_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'terms_conditions' => 'nullable|string',
            'client_name' => 'nullable|string|max:255',
            'client_ruc' => 'nullable|string|max:30',
            'client_email' => 'nullable|email|max:255',
            'client_phone' => 'nullable|string|max:50',
            'client_address' => 'nullable|string',
            'custom_background_image_id' => 'nullable|exists:media_assets,id',
            'custom_last_page_image_id' => 'nullable|exists:media_assets,id',
            'items' => 'nullable|array',
            'items.*.name' => 'required_with:items|string|max:255',
            'items.*.description' => 'nullable|string',
            'items.*.quantity' => 'required_with:items|numeric|min:0.01',
            'items.*.unit' => 'nullable|string|max:50',
            'items.*.unit_price' => 'required_with:items|numeric|min:0',
            'items.*.discount_percent' => 'nullable|numeric|min:0|max:100',
            'items.*.sort_order' => 'nullable|integer|min:0',

            // Tasks per item
            'items.*.tasks' => 'nullable|array',
            'items.*.tasks.*.name' => 'required_with:items.*.tasks|string|max:255',
            'items.*.tasks.*.description' => 'nullable|string',
            'items.*.tasks.*.duration_value' => 'required_with:items.*.tasks|integer|min:1',
            'items.*.tasks.*.duration_unit' => 'required_with:items.*.tasks|in:hours,days',
            'items.*.tasks.*.sort_order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Obtener configuración por defecto
            $settings = QuoteSetting::getSettings();

            // Crear cotización
            $quote = Quote::create([
                'title' => $request->title,
                'description' => $request->description,
                'user_id' => $request->user_id,
                'created_by' => auth()->id(),
                'currency_id' => $request->currency_id,
                'tax_rate' => $request->tax_rate ?? $settings->default_tax_rate ?? 0,
                'discount_amount' => $request->discount_amount ?? 0,
                'valid_until' => $request->valid_until,
                'estimated_start_date' => $request->estimated_start_date,
                'notes' => $request->notes ?? $settings->default_notes,
                'terms_conditions' => $request->terms_conditions ?? $settings->default_terms_conditions,
                'client_name' => $request->client_name,
                'client_ruc' => $request->client_ruc,
                'client_email' => $request->client_email,
                'client_phone' => $request->client_phone,
                'client_address' => $request->client_address,
                'custom_background_image_id' => $request->custom_background_image_id,
                'custom_last_page_image_id' => $request->custom_last_page_image_id,
                'status' => $request->status ?? 'draft',
            ]);

            // Crear items si se proporcionaron
            if ($request->has('items') && is_array($request->items)) {
                foreach ($request->items as $index => $itemData) {
                    $item = $quote->items()->create([
                        'name' => $itemData['name'],
                        'description' => $itemData['description'] ?? null,
                        'quantity' => $itemData['quantity'],
                        'unit' => $itemData['unit'] ?? null,
                        'unit_price' => $itemData['unit_price'],
                        'discount_percent' => $itemData['discount_percent'] ?? 0,
                        'sort_order' => $itemData['sort_order'] ?? $index,
                    ]);

                    if (!empty($itemData['tasks']) && is_array($itemData['tasks'])) {
                        foreach ($itemData['tasks'] as $tIndex => $taskData) {
                            $item->tasks()->create([
                                'name' => $taskData['name'],
                                'description' => $taskData['description'] ?? null,
                                'duration_value' => $taskData['duration_value'],
                                'duration_unit' => $taskData['duration_unit'] ?? 'hours',
                                'sort_order' => $taskData['sort_order'] ?? $tIndex,
                            ]);
                        }
                    }
                }
            }

            // Recalcular totales
            $quote->calculateTotals();
            $quote->save();

            DB::commit();

            $quote->load(['user', 'creator', 'currency', 'items.tasks']);

            return response()->json([
                'success' => true,
                'message' => 'Cotización creada exitosamente',
                'data' => $quote,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la cotización',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified quote
     */
    public function show(int $id): JsonResponse
    {
        $quote = Quote::with([
            'user',
            'creator',
            'currency',
            'items.tasks',
            'customBackgroundImage',
            'customLastPageImage',
        ])->find($id);

        if (!$quote) {
            return response()->json([
                'success' => false,
                'message' => 'Cotización no encontrada',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $quote,
        ]);
    }

    /**
     * Update the specified quote
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $quote = Quote::find($id);

        if (!$quote) {
            return response()->json([
                'success' => false,
                'message' => 'Cotización no encontrada',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id',
            'currency_id' => 'nullable|exists:currencies,id',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'status' => ['nullable', Rule::in(Quote::STATUSES)],
            'valid_until' => 'nullable|date',
            'estimated_start_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'terms_conditions' => 'nullable|string',
            'client_name' => 'nullable|string|max:255',
            'client_ruc' => 'nullable|string|max:30',
            'client_email' => 'nullable|email|max:255',
            'client_phone' => 'nullable|string|max:50',
            'client_address' => 'nullable|string',
            'custom_background_image_id' => 'nullable|exists:media_assets,id',
            'custom_last_page_image_id' => 'nullable|exists:media_assets,id',

            // Items (tu vista envía items en el PUT; aquí se procesan)
            'items' => 'sometimes|array',
            'items.*.name' => 'required_with:items|string|max:255',
            'items.*.description' => 'nullable|string',
            'items.*.quantity' => 'required_with:items|numeric|min:0.01',
            'items.*.unit' => 'nullable|string|max:50',
            'items.*.unit_price' => 'required_with:items|numeric|min:0',
            'items.*.discount_percent' => 'nullable|numeric|min:0|max:100',
            'items.*.sort_order' => 'nullable|integer|min:0',

            // Tasks per item
            'items.*.tasks' => 'nullable|array',
            'items.*.tasks.*.name' => 'required_with:items.*.tasks|string|max:255',
            'items.*.tasks.*.description' => 'nullable|string',
            'items.*.tasks.*.duration_value' => 'required_with:items.*.tasks|integer|min:1',
            'items.*.tasks.*.duration_unit' => 'required_with:items.*.tasks|in:hours,days',
            'items.*.tasks.*.sort_order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            DB::beginTransaction();

            $quote->update($request->only([
                'title',
                'description',
                'user_id',
                'currency_id',
                'tax_rate',
                'discount_amount',
                'status',
                'valid_until',
                'estimated_start_date',
                'notes',
                'terms_conditions',
                'client_name',
                'client_ruc',
                'client_email',
                'client_phone',
                'client_address',
                'custom_background_image_id',
                'custom_last_page_image_id',
            ]));

            // Si llega "items" en el request, sincronizamos reemplazando todos los items.
            // Nota: el frontend actual NO envía id por item, por eso esta estrategia es la más segura.
            if ($request->has('items')) {
                $quote->items()->delete();

                if (is_array($request->items)) {
                    foreach ($request->items as $index => $itemData) {
                        $item = $quote->items()->create([
                            'name' => $itemData['name'],
                            'description' => $itemData['description'] ?? null,
                            'quantity' => $itemData['quantity'],
                            'unit' => $itemData['unit'] ?? null,
                            'unit_price' => $itemData['unit_price'],
                            'discount_percent' => $itemData['discount_percent'] ?? 0,
                            'sort_order' => $itemData['sort_order'] ?? $index,
                        ]);

                        if (!empty($itemData['tasks']) && is_array($itemData['tasks'])) {
                            foreach ($itemData['tasks'] as $tIndex => $taskData) {
                                $item->tasks()->create([
                                    'name' => $taskData['name'],
                                    'description' => $taskData['description'] ?? null,
                                    'duration_value' => $taskData['duration_value'],
                                    'duration_unit' => $taskData['duration_unit'] ?? 'hours',
                                    'sort_order' => $taskData['sort_order'] ?? $tIndex,
                                ]);
                            }
                        }
                    }
                }
            }

            // Recalcular totales (asegurando que items estén cargados)
            $quote->load('items');
            $quote->calculateTotals();
            $quote->save();

            DB::commit();

            $quote->load(['user', 'creator', 'currency', 'items.tasks']);

            return response()->json([
                'success' => true,
                'message' => 'Cotización actualizada exitosamente',
                'data' => $quote,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la cotización',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified quote
     */
    public function destroy(int $id): JsonResponse
    {
        $quote = Quote::find($id);

        if (!$quote) {
            return response()->json([
                'success' => false,
                'message' => 'Cotización no encontrada',
            ], 404);
        }

        try {
            $quote->delete();

            return response()->json([
                'success' => true,
                'message' => 'Cotización eliminada exitosamente',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la cotización',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Restore a soft-deleted quote
     */
    public function restore(int $id): JsonResponse
    {
        $quote = Quote::withTrashed()->find($id);

        if (!$quote) {
            return response()->json([
                'success' => false,
                'message' => 'Cotización no encontrada',
            ], 404);
        }

        if (!$quote->trashed()) {
            return response()->json([
                'success' => false,
                'message' => 'La cotización no está eliminada',
            ], 400);
        }

        $quote->restore();

        return response()->json([
            'success' => true,
            'message' => 'Cotización restaurada exitosamente',
            'data' => $quote,
        ]);
    }

    /**
     * Duplicate a quote
     */
    public function duplicate(int $id): JsonResponse
    {
        $quote = Quote::with('items.tasks')->find($id);

        if (!$quote) {
            return response()->json([
                'success' => false,
                'message' => 'Cotización no encontrada',
            ], 404);
        }

        try {
            DB::beginTransaction();

            // Crear nueva cotización
            $newQuote = $quote->replicate();
            $newQuote->quote_number = Quote::generateQuoteNumber();
            $newQuote->status = 'draft';
            $newQuote->created_by = auth()->id();
            $newQuote->created_at = now();
            $newQuote->save();

            // Duplicar items
            foreach ($quote->items as $item) {
                $newItem = $item->replicate();
                $newItem->quote_id = $newQuote->id;
                $newItem->save();

                // Duplicate tasks for each item
                foreach ($item->tasks as $task) {
                    $newTask = $task->replicate();
                    $newTask->quote_item_id = $newItem->id;
                    $newTask->save();
                }
            }

            DB::commit();

            $newQuote->load(['user', 'creator', 'currency', 'items.tasks']);

            return response()->json([
                'success' => true,
                'message' => 'Cotización duplicada exitosamente',
                'data' => $newQuote,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al duplicar la cotización',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update quote status
     */
    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $quote = Quote::find($id);

        if (!$quote) {
            return response()->json([
                'success' => false,
                'message' => 'Cotización no encontrada',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'status' => ['required', Rule::in(Quote::STATUSES)],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
            ], 422);
        }

        $quote->status = $request->status;
        $quote->save();

        return response()->json([
            'success' => true,
            'message' => 'Estado de cotización actualizado',
            'data' => $quote,
        ]);
    }

    // ==================== ITEMS MANAGEMENT ====================

    /**
     * Add item to quote
     */
    public function addItem(Request $request, int $quoteId): JsonResponse
    {
        $quote = Quote::find($quoteId);

        if (!$quote) {
            return response()->json([
                'success' => false,
                'message' => 'Cotización no encontrada',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|numeric|min:0.01',
            'unit' => 'nullable|string|max:50',
            'unit_price' => 'required|numeric|min:0',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $maxOrder = $quote->items()->max('sort_order') ?? -1;

            $item = $quote->items()->create([
                'name' => $request->name,
                'description' => $request->description,
                'quantity' => $request->quantity,
                'unit' => $request->unit,
                'unit_price' => $request->unit_price,
                'discount_percent' => $request->discount_percent ?? 0,
                'sort_order' => $request->sort_order ?? ($maxOrder + 1),
            ]);

            $quote->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Item agregado exitosamente',
                'data' => [
                    'item' => $item,
                    'quote_totals' => [
                        'subtotal' => $quote->subtotal,
                        'tax_amount' => $quote->tax_amount,
                        'total' => $quote->total,
                    ],
                ],
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al agregar el item',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update item in quote
     */
    public function updateItem(Request $request, int $quoteId, int $itemId): JsonResponse
    {
        $quote = Quote::find($quoteId);

        if (!$quote) {
            return response()->json([
                'success' => false,
                'message' => 'Cotización no encontrada',
            ], 404);
        }

        $item = $quote->items()->find($itemId);

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Item no encontrado',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'sometimes|required|numeric|min:0.01',
            'unit' => 'nullable|string|max:50',
            'unit_price' => 'sometimes|required|numeric|min:0',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $item->update($request->only([
                'name',
                'description',
                'quantity',
                'unit',
                'unit_price',
                'discount_percent',
                'sort_order',
            ]));

            $quote->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Item actualizado exitosamente',
                'data' => [
                    'item' => $item,
                    'quote_totals' => [
                        'subtotal' => $quote->subtotal,
                        'tax_amount' => $quote->tax_amount,
                        'total' => $quote->total,
                    ],
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el item',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove item from quote
     */
    public function removeItem(int $quoteId, int $itemId): JsonResponse
    {
        $quote = Quote::find($quoteId);

        if (!$quote) {
            return response()->json([
                'success' => false,
                'message' => 'Cotización no encontrada',
            ], 404);
        }

        $item = $quote->items()->find($itemId);

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Item no encontrado',
            ], 404);
        }

        try {
            $item->delete();
            $quote->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Item eliminado exitosamente',
                'data' => [
                    'quote_totals' => [
                        'subtotal' => $quote->subtotal,
                        'tax_amount' => $quote->tax_amount,
                        'total' => $quote->total,
                    ],
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el item',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Reorder items in quote
     */
    public function reorderItems(Request $request, int $quoteId): JsonResponse
    {
        $quote = Quote::find($quoteId);

        if (!$quote) {
            return response()->json([
                'success' => false,
                'message' => 'Cotización no encontrada',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'items' => 'required|array',
            'items.*.id' => 'required|exists:quote_items,id',
            'items.*.sort_order' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            foreach ($request->items as $itemData) {
                QuoteItem::where('id', $itemData['id'])
                    ->where('quote_id', $quoteId)
                    ->update(['sort_order' => $itemData['sort_order']]);
            }

            $quote->load('items');

            return response()->json([
                'success' => true,
                'message' => 'Items reordenados exitosamente',
                'data' => $quote->items,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al reordenar los items',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // ==================== PDF GENERATION ====================

    /**
     * Download quote as PDF
     */
    public function downloadPdf(int $id)
    {
        $quote = Quote::find($id);

        if (!$quote) {
            return response()->json([
                'success' => false,
                'message' => 'Cotización no encontrada',
            ], 404);
        }

        return $this->pdfService->download($quote);
    }

    /**
     * Preview/Stream quote as PDF
     */
    public function previewPdf(int $id)
    {
        $quote = Quote::find($id);

        if (!$quote) {
            return response()->json([
                'success' => false,
                'message' => 'Cotización no encontrada',
            ], 404);
        }

        return $this->pdfService->stream($quote);
    }

    /**
     * Get PDF as Base64
     */
    public function getPdfBase64(int $id): JsonResponse
    {
        $quote = Quote::find($id);

        if (!$quote) {
            return response()->json([
                'success' => false,
                'message' => 'Cotización no encontrada',
            ], 404);
        }

        try {
            $base64 = $this->pdfService->toBase64($quote);

            return response()->json([
                'success' => true,
                'data' => [
                    'filename' => "Cotizacion_{$quote->quote_number}.pdf",
                    'mime_type' => 'application/pdf',
                    'base64' => $base64,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al generar el PDF',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
