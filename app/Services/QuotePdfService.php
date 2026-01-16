<?php

namespace App\Services;

use App\Models\Quote;
use App\Models\QuoteSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

class QuotePdfService
{
    protected QuoteSetting $settings;

    public function __construct()
    {
        $this->settings = QuoteSetting::getSettings();
    }

    /**
     * Generate PDF for a quote
     */
    public function generatePdf(Quote $quote): \Barryvdh\DomPDF\PDF
    {
        $quote->load(['items.tasks', 'user', 'creator', 'currency', 'customBackgroundImage', 'customLastPageImage']);
        
        $data = $this->prepareData($quote);
        
        $pdf = Pdf::loadView('pdf.quote', $data);
        
        $pdf->setPaper('letter', 'portrait');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'sans-serif',
            'dpi' => 150,
            'defaultMediaType' => 'print',
            'isFontSubsettingEnabled' => true,
        ]);

        return $pdf;
    }

    /**
     * Generate and download PDF
     */
    public function download(Quote $quote): \Symfony\Component\HttpFoundation\Response
    {
        $pdf = $this->generatePdf($quote);
        $filename = $this->generateFilename($quote);
        
        return $pdf->download($filename);
    }

    /**
     * Generate and stream PDF (for preview)
     */
    public function stream(Quote $quote): \Symfony\Component\HttpFoundation\Response
    {
        $pdf = $this->generatePdf($quote);
        $filename = $this->generateFilename($quote);
        
        return $pdf->stream($filename);
    }

    /**
     * Save PDF to storage and return path
     */
    public function save(Quote $quote, string $disk = 'public'): string
    {
        $pdf = $this->generatePdf($quote);
        $filename = $this->generateFilename($quote);
        $path = 'quotes/' . $filename;
        
        Storage::disk($disk)->put($path, $pdf->output());
        
        return $path;
    }

    /**
     * Get PDF as base64 string
     */
    public function toBase64(Quote $quote): string
    {
        $pdf = $this->generatePdf($quote);
        return base64_encode($pdf->output());
    }

    /**
     * Prepare data for the PDF view
     */
    protected function prepareData(Quote $quote): array
    {
        // Determinar imagen de fondo (la personalizada de la cotización o la de configuración global)
        $backgroundImageUrl = null;
        if ($quote->customBackgroundImage) {
            $backgroundImageUrl = $this->getImagePath($quote->customBackgroundImage);
        } elseif ($this->settings->backgroundImage) {
            $backgroundImageUrl = $this->getImagePath($this->settings->backgroundImage);
        }

        // Determinar imagen de última página
        $lastPageImageUrl = null;
        if ($quote->customLastPageImage) {
            $lastPageImageUrl = $this->getImagePath($quote->customLastPageImage);
        } elseif ($this->settings->lastPageImage) {
            $lastPageImageUrl = $this->getImagePath($this->settings->lastPageImage);
        }

        // Logo de la empresa
        $companyLogoUrl = null;
        if ($this->settings->companyLogo) {
            $companyLogoUrl = $this->getImagePath($this->settings->companyLogo);
        }

        $workHoursPerDay = (float) ($this->settings->work_hours_per_day ?? 8);
        if ($workHoursPerDay <= 0) {
            $workHoursPerDay = 8;
        }

        $timeline = $this->calculateTimeline($quote, $workHoursPerDay);

        return [
            'quote' => $quote,
            'items' => $quote->items,
            'settings' => $this->settings,
            'backgroundImageUrl' => $backgroundImageUrl,
            'lastPageImageUrl' => $lastPageImageUrl,
            'companyLogoUrl' => $companyLogoUrl,
            'currencySymbol' => $quote->currency?->symbol ?? '$',
            'hasLastPage' => !empty($lastPageImageUrl),
            'workHoursPerDay' => $workHoursPerDay,
            'timeline' => $timeline,
        ];
    }

    /**
     * Calculate timeline totals and estimated delivery date.
     */
    protected function calculateTimeline(Quote $quote, float $workHoursPerDay): array
    {
        $totalHours = 0.0;
        $totalTasks = 0;

        foreach ($quote->items as $item) {
            foreach ($item->tasks as $task) {
                $value = (float) $task->duration_value;
                if ($value <= 0) continue;

                $totalTasks++;
                if ($task->duration_unit === 'days') {
                    $totalHours += $value * $workHoursPerDay;
                } else {
                    $totalHours += $value;
                }
            }
        }

        $rawDays = $workHoursPerDay > 0 ? ($totalHours / $workHoursPerDay) : 0.0;
        $businessDays = (int) ceil($rawDays);

        $start = $quote->estimated_start_date
            ? Carbon::parse($quote->estimated_start_date)
            : Carbon::parse($quote->created_at)->startOfDay();

        $estimatedDelivery = $businessDays > 0
            ? $this->addBusinessDays($start->copy(), $businessDays)
            : $start->copy();

        return [
            'total_tasks' => $totalTasks,
            'total_hours' => round($totalHours, 2),
            'total_days' => round($rawDays, 2),
            'business_days' => $businessDays,
            'estimated_start_date' => $start->toDateString(),
            'estimated_delivery_date' => $estimatedDelivery->toDateString(),
        ];
    }

    /**
     * Add business days (Mon-Fri) to a date.
     */
    protected function addBusinessDays(Carbon $date, int $days): Carbon
    {
        $remaining = $days;
        while ($remaining > 0) {
            $date->addDay();
            if ($date->isWeekend()) {
                continue;
            }
            $remaining--;
        }
        return $date;
    }

    /**
     * Get image path for PDF (base64 or absolute path)
     */
    protected function getImagePath($mediaAsset): ?string
    {
        if (!$mediaAsset) {
            return null;
        }

        // Si es una URL externa
        if (filter_var($mediaAsset->url, FILTER_VALIDATE_URL)) {
            // Para imágenes remotas, intentamos convertirlas a base64
            try {
                $imageContent = @file_get_contents($mediaAsset->url);
                if ($imageContent) {
                    $mimeType = $mediaAsset->mime_type ?? 'image/png';
                    return 'data:' . $mimeType . ';base64,' . base64_encode($imageContent);
                }
            } catch (\Exception $e) {
                return $mediaAsset->url;
            }
        }

        // Si tiene storage_path (archivo local)
        if ($mediaAsset->storage_path) {
            $fullPath = storage_path('app/public/' . $mediaAsset->storage_path);
            if (file_exists($fullPath)) {
                $mimeType = $mediaAsset->mime_type ?? mime_content_type($fullPath);
                $imageContent = file_get_contents($fullPath);
                return 'data:' . $mimeType . ';base64,' . base64_encode($imageContent);
            }
        }

        // Intentar desde la URL relativa
        $publicPath = public_path(ltrim($mediaAsset->url, '/'));
        if (file_exists($publicPath)) {
            $mimeType = $mediaAsset->mime_type ?? mime_content_type($publicPath);
            $imageContent = file_get_contents($publicPath);
            return 'data:' . $mimeType . ';base64,' . base64_encode($imageContent);
        }

        return $mediaAsset->url;
    }

    /**
     * Generate filename for the PDF
     */
    protected function generateFilename(Quote $quote): string
    {
        $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $quote->title);
        return sprintf('Cotizacion_%s_%s.pdf', $quote->quote_number, $safeName);
    }
}
