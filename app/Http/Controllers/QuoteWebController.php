<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Services\QuotePdfService;
use Illuminate\Http\Request;

class QuoteWebController extends Controller
{
    protected QuotePdfService $pdfService;

    public function __construct(QuotePdfService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    /**
     * Download quote as PDF (using web session auth)
     */
    public function downloadPdf(int $id)
    {
        $quote = Quote::find($id);

        if (!$quote) {
            abort(404, 'Cotización no encontrada');
        }

        return $this->pdfService->download($quote);
    }

    /**
     * Preview/Stream quote as PDF (using web session auth)
     */
    public function previewPdf(int $id)
    {
        $quote = Quote::find($id);

        if (!$quote) {
            abort(404, 'Cotización no encontrada');
        }

        return $this->pdfService->stream($quote);
    }
}