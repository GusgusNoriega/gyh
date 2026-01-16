<?php

use Illuminate\Support\Facades\Route;
use App\Models\Project;
use App\Http\Controllers\QuoteWebController;
use App\Http\Controllers\LeadController;

/*
|--------------------------------------------------------------------------
| View Routes
|--------------------------------------------------------------------------
|
| Aquí se definen todas las rutas relacionadas con las vistas web.
| Estas rutas están separadas de las rutas API para mantener el orden.
|
*/

// Ruta principal
Route::get('/', function () {
    return view('marketing.home');
})->name('home')->middleware('guest');

// Página de gracias (lectura única por token)
Route::get('/gracias/{token}', [LeadController::class, 'thankYou'])
    ->name('leads.thankyou')
    ->middleware('guest');

// Páginas legales (requeridas para Google Ads)
Route::get('/privacidad', function () {
    return view('marketing.privacidad');
})->name('privacidad')->middleware('guest');

Route::get('/terminos', function () {
    return view('marketing.terminos');
})->name('terminos')->middleware('guest');

Route::get('/cookies', function () {
    return view('marketing.cookies');
})->name('cookies')->middleware('guest');

// Sitemap (páginas públicas)
Route::get('/sitemap.xml', function () {
    $baseUrl = url('/');
    $lastmod = now()->toDateString();

    $urls = [
        ['loc' => $baseUrl, 'changefreq' => 'weekly', 'priority' => '1.0'],
        ['loc' => url('/privacidad'), 'changefreq' => 'monthly', 'priority' => '0.3'],
        ['loc' => url('/terminos'), 'changefreq' => 'monthly', 'priority' => '0.3'],
        ['loc' => url('/cookies'), 'changefreq' => 'monthly', 'priority' => '0.3'],
    ];

    $urlsXml = '';
    foreach ($urls as $url) {
        $urlsXml .= "  <url>\n";
        $urlsXml .= "    <loc>{$url['loc']}</loc>\n";
        $urlsXml .= "    <lastmod>{$lastmod}</lastmod>\n";
        $urlsXml .= "    <changefreq>{$url['changefreq']}</changefreq>\n";
        $urlsXml .= "    <priority>{$url['priority']}</priority>\n";
        $urlsXml .= "  </url>\n";
    }

    $xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
{$urlsXml}</urlset>
XML;

    return response($xml, 200)
        ->header('Content-Type', 'application/xml; charset=UTF-8')
        ->header('X-Robots-Tag', 'noindex, follow');
})->name('sitemap');

// Rutas de autenticación (vistas)
Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLogin'])->name('login')->middleware('guest');

// Rutas protegidas por autenticación (vistas)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::prefix('admin')->middleware('admin')->group(function () {
        Route::get('/funnel', function () {
            return view('funnel');
        })->name('funnel');

        Route::get('/leads', function () {
            return view('leads.manage');
        })->name('leads');

        Route::get('/users', function () {
            return view('users.manage');
        })->name('users');

        Route::get('/rbac', function () {
            return view('rbac.manage');
        })->name('rbac');

        Route::get('/currencies', function () {
            return view('currencies.manage');
        })->name('currencies');

        Route::get('/color-themes', function () {
            return view('color-themes.manage');
        })->name('color-themes');

        // ========================= SMTP Settings (Vista) =========================
        Route::get('/smtp-settings', function () {
            return view('smtp.settings');
        })->name('smtp-settings');

        // ========================= Email Templates (Vista) =========================
        Route::get('/email-templates', function () {
            return view('email-templates.manage');
        })->name('email-templates');

        // ========================= Projects (Vistas) =========================
        Route::get('/projects', function () {
            return view('projects.index');
        })->name('projects');

        // Show: sólo Overview
        Route::get('/projects/{id}', function ($id) {
            $project = Project::find($id);
            if (!$project) abort(404);
            return view('projects.show', ['projectId' => (int)$id, 'project' => $project]);
        })->name('projects.show');

        // Vistas dedicadas
        Route::get('/projects/{id}/backlog', function ($id) {
            $project = Project::find($id);
            if (!$project) abort(404);
            return view('projects.backlog', ['projectId' => (int)$id, 'project' => $project]);
        })->name('projects.backlog');

        Route::get('/projects/{id}/gantt', function ($id) {
            $project = Project::find($id);
            if (!$project) abort(404);
            return view('projects.gantt', ['projectId' => (int)$id, 'project' => $project]);
        })->name('projects.gantt');

        Route::get('/projects/{id}/files', function ($id) {
            $project = Project::find($id);
            if (!$project) abort(404);
            return view('projects.files', ['projectId' => (int)$id, 'project' => $project]);
        })->name('projects.files');

        // ========================= Catálogos (Vistas) =========================
        Route::prefix('catalogs')->group(function () {
            Route::get('/task-status', function () {
                return view('catalogs.task-status');
            })->name('catalogs.task-status');

            Route::get('/file-categories', function () {
                return view('catalogs.file-categories');
            })->name('catalogs.file-categories');
        });

        // ========================= Cotizaciones (Vistas) =========================
        Route::get('/quotes', function () {
            return view('quotes.index');
        })->name('quotes');

        Route::get('/quotes/settings', function () {
            return view('quotes.settings');
        })->name('quotes.settings');

        // PDF Downloads (using web session auth)
        Route::get('/quotes/{id}/pdf/download', [QuoteWebController::class, 'downloadPdf'])->name('quotes.pdf.download');
        Route::get('/quotes/{id}/pdf/preview', [QuoteWebController::class, 'previewPdf'])->name('quotes.pdf.preview');
    });
});
