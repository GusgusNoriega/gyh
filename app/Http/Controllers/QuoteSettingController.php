<?php

namespace App\Http\Controllers;

use App\Models\QuoteSetting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class QuoteSettingController extends Controller
{
    /**
     * Get quote settings
     */
    public function show(): JsonResponse
    {
        $settings = QuoteSetting::getSettings();
        
        $settings->load(['companyLogo', 'backgroundImage', 'lastPageImage']);

        return response()->json([
            'success' => true,
            'data' => $settings,
        ]);
    }

    /**
     * Update quote settings
     */
    public function update(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'nullable|string|max:255',
            'company_ruc' => 'nullable|string|max:30',
            'company_address' => 'nullable|string|max:500',
            'company_phone' => 'nullable|string|max:50',
            'company_email' => 'nullable|email|max:255',
            'company_logo_id' => 'nullable|exists:media_assets,id',
            'background_image_id' => 'nullable|exists:media_assets,id',
            'last_page_image_id' => 'nullable|exists:media_assets,id',
            'default_terms_conditions' => 'nullable|string',
            'default_notes' => 'nullable|string',
            'default_tax_rate' => 'nullable|numeric|min:0|max:100',
            'work_hours_per_day' => 'nullable|numeric|min:0.25|max:24',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $settings = QuoteSetting::updateSettings($request->only([
                'company_name',
                'company_ruc',
                'company_address',
                'company_phone',
                'company_email',
                'company_logo_id',
                'background_image_id',
                'last_page_image_id',
                'default_terms_conditions',
                'default_notes',
                'default_tax_rate',
                'work_hours_per_day',
            ]));

            $settings->load(['companyLogo', 'backgroundImage', 'lastPageImage']);

            return response()->json([
                'success' => true,
                'message' => 'Configuración actualizada exitosamente',
                'data' => $settings,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la configuración',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update company logo
     */
    public function updateCompanyLogo(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'company_logo_id' => 'required|exists:media_assets,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $settings = QuoteSetting::updateSettings([
                'company_logo_id' => $request->company_logo_id,
            ]);

            $settings->load('companyLogo');

            return response()->json([
                'success' => true,
                'message' => 'Logo actualizado exitosamente',
                'data' => [
                    'company_logo_id' => $settings->company_logo_id,
                    'company_logo' => $settings->companyLogo,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el logo',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove company logo
     */
    public function removeCompanyLogo(): JsonResponse
    {
        try {
            $settings = QuoteSetting::updateSettings([
                'company_logo_id' => null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Logo eliminado exitosamente',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el logo',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update background image for PDF pages
     */
    public function updateBackgroundImage(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'background_image_id' => 'required|exists:media_assets,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $settings = QuoteSetting::updateSettings([
                'background_image_id' => $request->background_image_id,
            ]);

            $settings->load('backgroundImage');

            return response()->json([
                'success' => true,
                'message' => 'Imagen de fondo actualizada exitosamente',
                'data' => [
                    'background_image_id' => $settings->background_image_id,
                    'background_image' => $settings->backgroundImage,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la imagen de fondo',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove background image
     */
    public function removeBackgroundImage(): JsonResponse
    {
        try {
            $settings = QuoteSetting::updateSettings([
                'background_image_id' => null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Imagen de fondo eliminada exitosamente',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la imagen de fondo',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update last page image (for signatures, etc.)
     */
    public function updateLastPageImage(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'last_page_image_id' => 'required|exists:media_assets,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $settings = QuoteSetting::updateSettings([
                'last_page_image_id' => $request->last_page_image_id,
            ]);

            $settings->load('lastPageImage');

            return response()->json([
                'success' => true,
                'message' => 'Imagen de última página actualizada exitosamente',
                'data' => [
                    'last_page_image_id' => $settings->last_page_image_id,
                    'last_page_image' => $settings->lastPageImage,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la imagen de última página',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove last page image
     */
    public function removeLastPageImage(): JsonResponse
    {
        try {
            $settings = QuoteSetting::updateSettings([
                'last_page_image_id' => null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Imagen de última página eliminada exitosamente',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la imagen de última página',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update default terms and conditions
     */
    public function updateTermsConditions(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'default_terms_conditions' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $settings = QuoteSetting::updateSettings([
                'default_terms_conditions' => $request->default_terms_conditions,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Términos y condiciones actualizados exitosamente',
                'data' => [
                    'default_terms_conditions' => $settings->default_terms_conditions,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar términos y condiciones',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update default notes
     */
    public function updateNotes(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'default_notes' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $settings = QuoteSetting::updateSettings([
                'default_notes' => $request->default_notes,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Notas por defecto actualizadas exitosamente',
                'data' => [
                    'default_notes' => $settings->default_notes,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar las notas',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
