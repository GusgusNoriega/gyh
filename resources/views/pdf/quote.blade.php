<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Cotización {{ $quote->quote_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /*
         * Queremos fondo a "full bleed" (pegado a los bordes),
         * pero contenido con un margen interno seguro.
         * DomPDF suele respetar mejor padding del contenedor que @page margin.
         */
        @page {
            margin: 0;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 18px;
            line-height: 1.4;
            color: #333;
            position: relative;
        }

        /* Background watermark - full bleed behind content */
        .watermark {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: -1000;
            opacity: 1;
        }

        .watermark img {
            width: 100%;
            height: 100%;
        }

        /* Safe area for content (so it doesn't stick to page edges) */
        .content {
            position: relative;
            z-index: 1;
            padding: 10mm 18mm 18mm 18mm; /* top right bottom left */
        }

        /* Header section */
        .header {
            margin-bottom: 12px;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 10px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: top;
        }

        .company-info {
            width: 55%;
        }

        .quote-info {
            width: 45%;
            text-align: right;
        }

        .company-logo {
            max-width: 100px;
            max-height: 40px;
            margin-bottom: 6px;
        }

        .company-name {
            font-size: 16px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 3px;
        }

        .company-details {
            font-size: 12px;
            color: #666;
            line-height: 1.45;
        }

        .quote-title {
            font-size: 20px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 4px;
        }

        .quote-number {
            font-size: 16px;
            font-weight: bold;
            color: #374151;
            margin-bottom: 3px;
        }

        .quote-date {
            font-size: 14px;
            color: #666;
        }

        .quote-status {
            display: inline-block;
            padding: 4px 11px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 6px;
        }

        .status-draft { background: #fef3c7; color: #92400e; }
        .status-sent { background: #dbeafe; color: #1e40af; }
        .status-accepted { background: #d1fae5; color: #065f46; }
        .status-rejected { background: #fee2e2; color: #991b1b; }
        .status-expired { background: #e5e7eb; color: #374151; }

        /* Client section */
        .client-section {
            margin-bottom: 10px;
            background: rgba(248, 250, 252, 0.95);
            border-radius: 5px;
            padding: 8px 10px;
            border-left: 3px solid #2563eb;
        }

        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .client-name {
            font-size: 14px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 2px;
        }

        .client-details {
            font-size: 14px;
            color: #6b7280;
            line-height: 1.45;
        }

        /* Quote description */
        .quote-description {
            margin-bottom: 10px;
            padding: 8px 10px;
            background: rgba(239, 246, 255, 0.95);
            border-radius: 5px;
        }

        .quote-description-title {
            font-size: 18px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 3px;
        }

        .quote-description-text {
            font-size: 14px;
            color: #374151;
        }

        /* Items table */
        .items-section {
            margin-bottom: 10px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }

        .items-table thead th {
            background: #1e40af;
            color: white;
            padding: 7px 6px;
            font-size: 10px;
            font-weight: bold;
            text-align: left;
            text-transform: uppercase;
            letter-spacing: 0.2px;
        }

        .items-table thead th:first-child {
            border-radius: 3px 0 0 0;
        }

        .items-table thead th:last-child {
            border-radius: 0 3px 0 0;
            text-align: right;
        }

        .items-table tbody tr:nth-child(even) {
            background: rgba(248, 250, 252, 0.9);
        }

        .items-table tbody td {
            padding: 7px 6px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 15px;
            vertical-align: top;
        }

        /* Tasks section */
        .tasks-section {
            margin-top: 10px;
            page-break-inside: auto;
        }

        .tasks-box {
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 10px;
        }

        .tasks-summary {
            display: block;
            margin-top: 6px;
            font-size: 12px;
            color: #374151;
        }

        .tasks-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
            table-layout: fixed; /* DomPDF: hace que los % de columnas se respeten mejor */
        }

        .tasks-table thead th {
            background: #f3f4f6;
            color: #111827;
            padding: 6px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.2px;
            text-align: left;
        }

        .tasks-table tbody td {
            padding: 6px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 12px;
            vertical-align: top;
        }

        /* Hacer que descripciones largas no rompan el layout */
        .tasks-table th,
        .tasks-table td {
            word-wrap: break-word;
            overflow-wrap: break-word;
            white-space: normal;
        }

        .task-item-title {
            font-size: 14px;
            font-weight: bold;
            color: #1e40af;
            margin-top: 8px;
        }

        .items-table tbody td:last-child {
            text-align: right;
            font-weight: 600;
        }

        .item-name {
            font-weight: 600;
            color: #111827;
        }

        .item-description {
            font-size: 14px;
            color: #6b7280;
            margin-top: 2px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* Totals section */
        .totals-section {
            width: 100%;
            margin-top: 8px;
            page-break-inside: avoid;
        }
        
        .detalle {
            page-break-inside: avoid;
        }

        .totals-table {
            width: 180px;
            margin-left: auto;
            border-collapse: collapse;
        }

        .totals-table tr td {
            padding: 6px 8px;
            font-size: 15px;
        }

        .totals-table tr td:first-child {
            text-align: right;
            color: #6b7280;
        }

        .totals-table tr td:last-child {
            text-align: right;
            font-weight: 600;
            color: #111827;
        }

        .totals-table tr.total-row {
            background: #1e40af;
        }

        .totals-table tr.total-row td {
            color: white;
            font-size: 16px;
            font-weight: bold;
            padding: 8px;
        }

        .totals-table tr.total-row td:first-child {
            border-radius: 3px 0 0 3px;
        }

        .totals-table tr.total-row td:last-child {
            border-radius: 0 3px 3px 0;
        }

        /* Notes and Terms */
        .notes-section {
            margin-top: 10px;
            page-break-inside: avoid;
        }

        .notes-box {
            background: rgba(254, 252, 232, 0.95);
            border: 1px solid #fde047;
            border-radius: 5px;
            padding: 8px;
            margin-bottom: 8px;
        }

        .notes-box .section-title {
            color: #854d0e;
        }

        .terms-box {
            background: rgba(240, 253, 244, 0.95);
            border: 1px solid #86efac;
            border-radius: 5px;
            padding: 8px;
        }

        .terms-box .section-title {
            color: #166534;
        }

        .notes-text {
            font-size: 16px;
            color: #374151;
            white-space: pre-wrap;
        }

        /* Validity section */
        .validity-section {
            margin-top: 10px;
            text-align: center;
            padding: 8px;
            background: rgba(254, 242, 242, 0.95);
            border-radius: 5px;
            border: 1px solid #fecaca;
        }

        .validity-text {
            font-size: 16px;
            color: #991b1b;
            font-weight: 600;
        }

        /* Footer */
        .footer {
            margin-top: 15px;
            text-align: center;
            font-size: 16px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 7px;
        }

        /* Last page (signature page) */
        .last-page {
            page-break-before: always;
            text-align: center;
        }

        .last-page-image img {
            width: 100%;
            max-width: 100%;
            height: auto;
        }

        /* Page break */
        .page-break {
            page-break-after: always;
        }

        /* Currency formatting */
        .currency {
            font-family: 'DejaVu Sans', monospace;
        }

        /* Two column layout for notes */
        .two-columns {
            width: 100%;
        }

        .two-columns td {
            width: 50%;
            vertical-align: top;
            padding: 0 4px;
        }

        .two-columns td:first-child {
            padding-left: 0;
        }

        .two-columns td:last-child {
            padding-right: 0;
        }
    </style>
</head>
<body>
    {{-- Background watermark --}}
    @if($backgroundImageUrl)
    <div class="watermark">
        <img src="{{ $backgroundImageUrl }}" alt="">
    </div>
    @endif

    <div class="content">
        {{-- Header --}}
        <div class="header">
            <table class="header-table">
                <tr>
                    <td class="company-info">
                        @if($companyLogoUrl)
                            <img src="{{ $companyLogoUrl }}" alt="Logo" class="company-logo">
                        @endif
                        <div class="company-name">{{ $settings->company_name ?? config('app.name') }}</div>
                        <div class="company-details">
                            @if(!empty($settings->company_ruc))
                                RUC: {{ $settings->company_ruc }}<br>
                            @endif
                            @if($settings->company_address)
                                {{ $settings->company_address }}<br>
                            @endif
                            @if($settings->company_phone)
                                Tel: {{ $settings->company_phone }}<br>
                            @endif
                            @if($settings->company_email)
                                Email: {{ $settings->company_email }}
                            @endif
                        </div>
                    </td>
                    <td class="quote-info">
                        <div class="quote-title">COTIZACIÓN</div>
                        <div class="quote-number">{{ $quote->quote_number }}</div>
                        <div class="quote-date">
                            Fecha: {{ $quote->created_at->format('d/m/Y') }}<br>
                            @if($quote->valid_until)
                                Válida hasta: {{ $quote->valid_until->format('d/m/Y') }}
                            @endif
                        </div>
                        <span class="quote-status status-{{ $quote->status }}">
                            @switch($quote->status)
                                @case('draft') Borrador @break
                                @case('sent') Enviada @break
                                @case('accepted') Aceptada @break
                                @case('rejected') Rechazada @break
                                @case('expired') Expirada @break
                            @endswitch
                        </span>
                    </td>
                </tr>
            </table>
        </div>

        {{-- Client Information --}}
        <div class="client-section">
            <div class="section-title">Información del Cliente</div>
            <div class="client-name">{{ $quote->client_display_name }}</div>
            <div class="client-details">
                @if(!empty($quote->client_ruc))
                    RUC: {{ $quote->client_ruc }}<br>
                @endif
                @if($quote->user)
                    Email: {{ $quote->user->email }}<br>
                @elseif($quote->client_email)
                    Email: {{ $quote->client_email }}<br>
                @endif
                @if($quote->client_phone)
                    Teléfono: {{ $quote->client_phone }}<br>
                @endif
                @if($quote->client_address)
                    Dirección: {{ $quote->client_address }}
                @endif
            </div>
        </div>

        {{-- Quote Title and Description --}}
        @if($quote->title || $quote->description)
        <div class="quote-description">
            @if($quote->title)
            <div class="quote-description-title">{{ $quote->title }}</div>
            @endif
            @if($quote->description)
            <div class="quote-description-text">{{ $quote->description }}</div>
            @endif
        </div>
        @endif

        {{-- Tasks / Work plan --}}
        @php
            $hasTasks = false;
            foreach ($items as $it) {
                if ($it->tasks && $it->tasks->count() > 0) { $hasTasks = true; break; }
            }
        @endphp
        @if($hasTasks)
        <div class="tasks-section">
            <div class="section-title">Plan de trabajo (tareas)</div>
            <div class="tasks-box">
                <div class="tasks-summary">
                    Inicio estimado: <strong>{{ \Carbon\Carbon::parse($timeline['estimated_start_date'])->format('d/m/Y') }}</strong> |
                    Entrega estimada: <strong>{{ \Carbon\Carbon::parse($timeline['estimated_delivery_date'])->format('d/m/Y') }}</strong> |
                    Duración: <strong>{{ number_format($timeline['total_hours'], 2) }}</strong> h (~ <strong>{{ number_format($timeline['total_days'], 2) }}</strong> días @ {{ number_format($workHoursPerDay, 0) }}h/día)
                </div>

                @foreach($items as $item)
                    @if($item->tasks && $item->tasks->count() > 0)
                        <div class="task-item-title">{{ $item->name }}</div>
                        <table class="tasks-table">
                            <thead>
                                <tr>
                                    <th style="width: 30%">Tarea</th>
                                    <th style="width: 55%">Descripción</th>
                                    <th style="width: 15%" class="text-right">Tiempo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($item->tasks as $task)
                                    <tr>
                                        <td><strong>{{ $task->name }}</strong></td>
                                        <td>{{ $task->description ?? '-' }}</td>
                                        <td class="text-right">
                                            {{ number_format($task->duration_value, 0) }} {{ $task->duration_unit === 'days' ? 'días' : 'horas' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                @endforeach
            </div>
        </div>
        @endif

        {{-- Items --}}
        <div class="items-section detalle">
            <div class="section-title">Detalle de la Cotización</div>
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 5%">#</th>
                        <th style="width: 42%">Descripción</th>
                        <th style="width: 9%" class="text-center">Cant.</th>
                        <th style="width: 8%" class="text-center">Unid.</th>
                        <th style="width: 13%" class="text-right">P. Unit.</th>
                        <th style="width: 8%" class="text-center">Desc.</th>
                        <th style="width: 15%" class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <div class="item-name">{{ $item->name }}</div>
                            @if($item->description)
                                <div class="item-description">{{ $item->description }}</div>
                            @endif
                        </td>
                        <td class="text-center">{{ number_format($item->quantity, 2) }}</td>
                        <td class="text-center">{{ $item->unit ?? '-' }}</td>
                        <td class="text-right currency">{{ $currencySymbol }}{{ number_format($item->unit_price, 2) }}</td>
                        <td class="text-center">
                            @if($item->discount_percent > 0)
                                {{ number_format($item->discount_percent, 0) }}%
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-right currency">{{ $currencySymbol }}{{ number_format($item->total, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Totals --}}
        <div class="totals-section">
            <table class="totals-table">
                <tr>
                    <td>Subtotal:</td>
                    <td class="currency">{{ $currencySymbol }}{{ number_format($quote->subtotal, 2) }}</td>
                </tr>
                @if($quote->discount_amount > 0)
                <tr>
                    <td>Descuento:</td>
                    <td class="currency">-{{ $currencySymbol }}{{ number_format($quote->discount_amount, 2) }}</td>
                </tr>
                @endif
                @if($quote->tax_rate > 0)
                <tr>
                    <td>IGV ({{ number_format($quote->tax_rate, 0) }}%):</td>
                    <td class="currency">{{ $currencySymbol }}{{ number_format($quote->tax_amount, 2) }}</td>
                </tr>
                @endif
                <tr class="total-row">
                    <td>TOTAL:</td>
                    <td class="currency">{{ $currencySymbol }}{{ number_format($quote->total, 2) }}</td>
                </tr>
            </table>
        </div>

        {{-- Notes and Terms in two columns if both exist --}}
        @if($quote->notes && $quote->terms_conditions)
        <div class="notes-section">
            <table class="two-columns">
                <tr>
                    <td>
                        <div class="notes-box">
                            <div class="section-title">Notas</div>
                            <div class="notes-text">{{ $quote->notes }}</div>
                        </div>
                    </td>
                    <td>
                        <div class="terms-box">
                            <div class="section-title">Términos y Condiciones</div>
                            <div class="notes-text">{{ $quote->terms_conditions }}</div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        @elseif($quote->notes)
        <div class="notes-section">
            <div class="notes-box">
                <div class="section-title">Notas</div>
                <div class="notes-text">{{ $quote->notes }}</div>
            </div>
        </div>
        @elseif($quote->terms_conditions)
        <div class="notes-section">
            <div class="terms-box">
                <div class="section-title">Términos y Condiciones</div>
                <div class="notes-text">{{ $quote->terms_conditions }}</div>
            </div>
        </div>
        @endif

        {{-- Validity Notice --}}
        @if($quote->valid_until)
        <div class="validity-section">
            <div class="validity-text">
                Esta cotización es válida hasta el {{ $quote->valid_until->format('d/m/Y') }}
            </div>
        </div>
        @endif

        {{-- Footer --}}
        <div class="footer">
            {{ $settings->company_name ?? config('app.name') }} | 
            Cotización {{ $quote->quote_number }} | 
            Generada el {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>

    {{-- Last Page with Signature Image --}}
    @if($hasLastPage && $lastPageImageUrl)
    <div class="last-page">
        <div class="last-page-image">
            <img src="{{ $lastPageImageUrl }}" alt="Página de firmas">
        </div>
    </div>
    @endif
</body>
</html>
