<!doctype html>
<html lang="es" data-theme="dark">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>@yield('title', 'Dashboard Base • Tailwind + Dark Mode')</title>

  <!-- SEO: panel administrativo (no indexar) -->
  <meta name="robots" content="noindex, nofollow" />
  <meta name="googlebot" content="noindex, nofollow" />

  @if(session('passport_token'))
    <meta name="api-token" content="{{ session('passport_token') }}">
  @endif

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  {{-- Tailwind compilado (Vite) --}}
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <!-- Alpine.js para tabs y pequeños estados en vistas -->
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

  <!-- Variables de color dinámicas del tema del usuario -->
  <style>
    @php
      $colorThemeService = app(\App\Services\ColorThemeService::class);
      $userTheme = $colorThemeService->getUserTheme();
    @endphp

    :root {
      /* Variables dinámicas del tema del usuario */
      @if($userTheme)
        @foreach($userTheme->colors as $key => $value)
          --c-{{ $key }}: {{ $value }};
        @endforeach
      @else
        /* Fallback por defecto (oscuro) */
        --c-bg: oklch(0.17 0.02 255);
        --c-surface: oklch(0.21 0.02 255);
        --c-elev: oklch(0.25 0.02 255);
        --c-text: oklch(0.93 0.02 255);
        --c-muted: oklch(0.74 0.02 255);
        --c-border: oklch(0.35 0.02 255);
        --c-primary: oklch(0.72 0.14 260);
        --c-primary-ink: oklch(0.12 0.02 260);
        --c-accent: oklch(0.75 0.13 170);
        --c-danger: oklch(0.68 0.21 25);
      @endif

      /* Mantiene el look del `shadow-soft` que antes venía del config del CDN */
      --shadow-soft: 0 1px 2px rgba(0,0,0,.04), 0 2px 12px rgba(0,0,0,.06);

      --radius: 14px;
      color-scheme: dark; /* hint al navegador */
    }

    /* Oculta elementos hasta que cargue Alpine (x-cloak) */
    [x-cloak] { display: none !important; }
 
    /* Scrollbar sutil */
    ::-webkit-scrollbar{width:10px;height:10px}
    ::-webkit-scrollbar-thumb{background:var(--c-border);border-radius:999px}
    ::-webkit-scrollbar-track{background:transparent}

    /* Estilo para enlace activo */
    #dash-accordion a.active {
      background-color: rgba(0, 0, 0, 0.5);
      font-weight: 600;
    }

    /* Evita saltos al aparecer la barra de scroll */
    html { scrollbar-gutter: stable; }

    /* will-change sólo durante animación del sidebar */
    #dash-sidebar.animating { will-change: transform; }

    /* Pintar sólo lo visible dentro de los paneles del acordeón */
    @supports (content-visibility: auto) {
      .cv-auto { content-visibility: auto; contain-intrinsic-size: 1px 400px; }
    }

    /* Animación ligera para abrir/cerrar acordeón (barata) */
    .acc-enter { transition: opacity .18s ease, transform .18s ease; opacity: 0; transform: scaleY(.98); }
    .acc-enter.act { opacity: 1; transform: scaleY(1); }
  </style>
</head>
<body class="min-h-screen bg-[var(--c-bg)] text-[var(--c-text)] font-sans">
   <!-- Preloader -->
  @include('components.preloader')
  <!--
    -----------------------------------------------------------
    LAYOUT PRINCIPAL
    - Aside (Barra lateral)
    - Header (Barra superior)
    - Main (Contenido del dashboard)
    - Footer (Pie de página)
    -----------------------------------------------------------
  -->

  <!-- Backdrop móvil para el sidebar -->
  <div id="dash-backdrop" class="lg:hidden fixed inset-0 bg-black/40 hidden"></div>

  <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] h-screen">

    <!-- ========================= ASIDE / SIDEBAR ========================= -->
    <aside id="dash-sidebar" class="fixed lg:static inset-y-0 left-0 z-40 w-[80%] max-w-[320px] lg:w-auto translate-x-[-100%] lg:translate-x-0 transition-transform duration-300 ease-out bg-[var(--c-surface)] border-r border-[var(--c-border)]">
      <div class="h-full flex flex-col">
        <!-- Branding -->
        <div class="flex items-center gap-3 px-5 py-4 border-b border-[var(--c-border)]">
          <div class="size-9 rounded-xl grid place-items-center bg-[var(--c-primary)] text-white font-bold shadow-soft">D</div>
          <div>
            <h1 class="text-base font-semibold leading-tight">Dashboard Base</h1>
            <p class="text-xs text-[var(--c-muted)] leading-tight">Layout modular</p>
          </div>
        </div>

        <!-- Buscador en el sidebar -->
        <div class="px-4 py-3 border-b border-[var(--c-border)]">
          <label for="dash-sidebar-search" class="sr-only">Buscar</label>
          <div class="flex items-center gap-2 rounded-2xl bg-[var(--c-elev)] px-3 py-2 ring-1 ring-[var(--c-border)] focus-within:ring-[var(--c-primary)]">
            <svg class="size-5 opacity-70" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
            <input id="dash-sidebar-search" type="search" placeholder="Buscar…" class="bg-transparent outline-none w-full text-sm placeholder:text-[var(--c-muted)]" />
          </div>
        </div>

        <!-- Menú (Acordeón modular) -->
        <nav id="dash-accordion" class="flex-1 overflow-y-auto p-2 space-y-2">
          <!-- Grupo 1: Dashboard -->
          <section class="rounded-2xl overflow-hidden ring-1 ring-[var(--c-border)]">
            <button id="dash-acc-btn-1" class="w-full flex items-center justify-between gap-3 px-4 py-3 bg-[var(--c-elev)] hover:bg-[var(--c-elev)]/80 transition" aria-controls="dash-acc-panel-1" aria-expanded="true">
              <span class="flex items-center gap-3">
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                <span class="text-sm font-medium">Dashboard</span>
              </span>
              <svg class="size-4 rotate-0 transition-transform" id="dash-acc-icon-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
            </button>
            <div id="dash-acc-panel-1" class="hidden cv-auto">
              <div class="p-2 bg-[var(--c-surface)] min-h-0">
                <a href="{{ route('dashboard') }}" data-route="dashboard" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-[var(--c-elev)] transition text-sm"><span class="size-6 grid place-items-center rounded-lg ring-1 ring-[var(--c-border)]">🏠</span>Inicio</a>
                <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-[var(--c-elev)] transition text-sm opacity-50"><span class="size-6 grid place-items-center rounded-lg ring-1 ring-[var(--c-border)]">📊</span>Analíticas</a>
                <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-[var(--c-elev)] transition text-sm opacity-50"><span class="size-6 grid place-items-center rounded-lg ring-1 ring-[var(--c-border)]">📈</span>Reportes</a>
              </div>
            </div>
          </section>

          <!-- Grupo 2: Proyectos -->
          <section class="rounded-2xl overflow-hidden ring-1 ring-[var(--c-border)]">
            <button id="dash-acc-btn-2" class="w-full flex items-center justify-between gap-3 px-4 py-3 bg-[var(--c-elev)] hover:bg-[var(--c-elev)]/80 transition" aria-controls="dash-acc-panel-2" aria-expanded="false">
              <span class="flex items-center gap-3">
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 20a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.9a2 2 0 0 1-1.69-.9L9.6 3.9A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2Z"/></svg>
                <span class="text-sm font-medium">Proyectos</span>
              </span>
              <svg class="size-4 -rotate-90 transition-transform" id="dash-acc-icon-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
            </button>
            <div id="dash-acc-panel-2" class="hidden cv-auto">
              <div class="p-2 bg-[var(--c-surface)] min-h-0">
                <a href="{{ route('projects') }}" data-route="projects" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-[var(--c-elev)] transition text-sm"><span class="size-6 grid place-items-center rounded-lg ring-1 ring-[var(--c-border)]">📁</span>Todos los Proyectos</a>
                <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-[var(--c-elev)] transition text-sm opacity-50"><span class="size-6 grid place-items-center rounded-lg ring-1 ring-[var(--c-border)]">✅</span>Mis Tareas</a>
                <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-[var(--c-elev)] transition text-sm opacity-50"><span class="size-6 grid place-items-center rounded-lg ring-1 ring-[var(--c-border)]">📅</span>Calendario</a>
                <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-[var(--c-elev)] transition text-sm opacity-50"><span class="size-6 grid place-items-center rounded-lg ring-1 ring-[var(--c-border)]">⏱️</span>Tiempo Registrado</a>
              </div>
            </div>
          </section>

          <!-- Grupo 3: Administración -->
          <section class="rounded-2xl overflow-hidden ring-1 ring-[var(--c-border)]">
            <button id="dash-acc-btn-3" class="w-full flex items-center justify-between gap-3 px-4 py-3 bg-[var(--c-elev)] hover:bg-[var(--c-elev)]/80 transition" aria-controls="dash-acc-panel-3" aria-expanded="false">
              <span class="flex items-center gap-3">
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                <span class="text-sm font-medium">Administración</span>
              </span>
              <svg class="size-4 -rotate-90 transition-transform" id="dash-acc-icon-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
            </button>
            <div id="dash-acc-panel-3" class="hidden cv-auto">
              <div class="p-2 bg-[var(--c-surface)] min-h-0">
                <a href="{{ route('users') }}" data-route="users" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-[var(--c-elev)] transition text-sm"><span class="size-6 grid place-items-center rounded-lg ring-1 ring-[var(--c-border)]">👥</span>Usuarios</a>
                <a href="{{ route('rbac') }}" data-route="rbac" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-[var(--c-elev)] transition text-sm"><span class="size-6 grid place-items-center rounded-lg ring-1 ring-[var(--c-border)]">🛡️</span>Roles & Permisos</a>
                <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-[var(--c-elev)] transition text-sm opacity-50"><span class="size-6 grid place-items-center rounded-lg ring-1 ring-[var(--c-border)]">📋</span>Registro de Actividad</a>
              </div>
            </div>
          </section>

          <!-- Grupo 4: Catálogos -->
          <section class="rounded-2xl overflow-hidden ring-1 ring-[var(--c-border)]">
            <button id="dash-acc-btn-4" class="w-full flex items-center justify-between gap-3 px-4 py-3 bg-[var(--c-elev)] hover:bg-[var(--c-elev)]/80 transition" aria-controls="dash-acc-panel-4" aria-expanded="false">
              <span class="flex items-center gap-3">
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20"/></svg>
                <span class="text-sm font-medium">Catálogos</span>
              </span>
              <svg class="size-4 -rotate-90 transition-transform" id="dash-acc-icon-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
            </button>
            <div id="dash-acc-panel-4" class="hidden cv-auto">
              <div class="p-2 bg-[var(--c-surface)] min-h-0">
                <a href="{{ route('catalogs.task-status') }}" data-route="catalogs.task-status" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-[var(--c-elev)] transition text-sm"><span class="size-6 grid place-items-center rounded-lg ring-1 ring-[var(--c-border)]">📊</span>Estados de Tarea</a>
                <a href="{{ route('catalogs.file-categories') }}" data-route="catalogs.file-categories" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-[var(--c-elev)] transition text-sm"><span class="size-6 grid place-items-center rounded-lg ring-1 ring-[var(--c-border)]">📂</span>Categorías de Archivo</a>
                <a href="{{ route('currencies') }}" data-route="currencies" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-[var(--c-elev)] transition text-sm"><span class="size-6 grid place-items-center rounded-lg ring-1 ring-[var(--c-border)]">💰</span>Monedas</a>
                <a href="{{ route('color-themes') }}" data-route="color-themes" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-[var(--c-elev)] transition text-sm"><span class="size-6 grid place-items-center rounded-lg ring-1 ring-[var(--c-border)]">🎨</span>Temas de Color</a>
              </div>
            </div>
          </section>

          <!-- Grupo 5: Configuración -->
          <section class="rounded-2xl overflow-hidden ring-1 ring-[var(--c-border)]">
            <button id="dash-acc-btn-5" class="w-full flex items-center justify-between gap-3 px-4 py-3 bg-[var(--c-elev)] hover:bg-[var(--c-elev)]/80 transition" aria-controls="dash-acc-panel-5" aria-expanded="false">
              <span class="flex items-center gap-3">
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                <span class="text-sm font-medium">Configuración</span>
              </span>
              <svg class="size-4 -rotate-90 transition-transform" id="dash-acc-icon-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
            </button>
            <div id="dash-acc-panel-5" class="hidden cv-auto">
              <div class="p-2 bg-[var(--c-surface)] min-h-0">
                <a href="{{ route('smtp-settings') }}" data-route="smtp-settings" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-[var(--c-elev)] transition text-sm"><span class="size-6 grid place-items-center rounded-lg ring-1 ring-[var(--c-border)]">📧</span>Configuración SMTP</a>
                <a href="{{ route('email-templates') }}" data-route="email-templates" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-[var(--c-elev)] transition text-sm"><span class="size-6 grid place-items-center rounded-lg ring-1 ring-[var(--c-border)]">📝</span>Plantillas de Email</a>
                <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-[var(--c-elev)] transition text-sm opacity-50"><span class="size-6 grid place-items-center rounded-lg ring-1 ring-[var(--c-border)]">🔔</span>Notificaciones</a>
                <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-[var(--c-elev)] transition text-sm opacity-50"><span class="size-6 grid place-items-center rounded-lg ring-1 ring-[var(--c-border)]">🔐</span>Seguridad</a>
                <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-[var(--c-elev)] transition text-sm opacity-50"><span class="size-6 grid place-items-center rounded-lg ring-1 ring-[var(--c-border)]">💾</span>Respaldos</a>
              </div>
            </div>
          </section>

          <!-- Grupo 6: Cotizaciones -->
          <section class="rounded-2xl overflow-hidden ring-1 ring-[var(--c-border)]">
            <button id="dash-acc-btn-6" class="w-full flex items-center justify-between gap-3 px-4 py-3 bg-[var(--c-elev)] hover:bg-[var(--c-elev)]/80 transition" aria-controls="dash-acc-panel-6" aria-expanded="false">
              <span class="flex items-center gap-3">
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                <span class="text-sm font-medium">Cotizaciones</span>
              </span>
              <svg class="size-4 -rotate-90 transition-transform" id="dash-acc-icon-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
            </button>
            <div id="dash-acc-panel-6" class="hidden cv-auto">
              <div class="p-2 bg-[var(--c-surface)] min-h-0">
                <a href="{{ route('quotes') }}" data-route="quotes" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-[var(--c-elev)] transition text-sm"><span class="size-6 grid place-items-center rounded-lg ring-1 ring-[var(--c-border)]">📋</span>Todas las Cotizaciones</a>
                <a href="{{ route('quotes.settings') }}" data-route="quotes.settings" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-[var(--c-elev)] transition text-sm"><span class="size-6 grid place-items-center rounded-lg ring-1 ring-[var(--c-border)]">⚙️</span>Configuración</a>
              </div>
            </div>
          </section>

          <!-- Grupo 7: Herramientas -->
          <section class="rounded-2xl overflow-hidden ring-1 ring-[var(--c-border)]">
            <button id="dash-acc-btn-7" class="w-full flex items-center justify-between gap-3 px-4 py-3 bg-[var(--c-elev)] hover:bg-[var(--c-elev)]/80 transition" aria-controls="dash-acc-panel-7" aria-expanded="false">
              <span class="flex items-center gap-3">
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                <span class="text-sm font-medium">Herramientas</span>
              </span>
              <svg class="size-4 -rotate-90 transition-transform" id="dash-acc-icon-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
            </button>
            <div id="dash-acc-panel-7" class="hidden cv-auto">
              <div class="p-2 bg-[var(--c-surface)] min-h-0">
                <a href="{{ route('funnel') }}" data-route="funnel" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-[var(--c-elev)] transition text-sm"><span class="size-6 grid place-items-center rounded-lg ring-1 ring-[var(--c-border)]">🎯</span>Funnel</a>
                <a href="{{ route('leads') }}" data-route="leads" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-[var(--c-elev)] transition text-sm"><span class="size-6 grid place-items-center rounded-lg ring-1 ring-[var(--c-border)]">📨</span>Leads</a>
                <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-[var(--c-elev)] transition text-sm opacity-50"><span class="size-6 grid place-items-center rounded-lg ring-1 ring-[var(--c-border)]">🖼️</span>Gestor de Media</a>
                <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-[var(--c-elev)] transition text-sm opacity-50"><span class="size-6 grid place-items-center rounded-lg ring-1 ring-[var(--c-border)]">📤</span>Importar/Exportar</a>
                <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-[var(--c-elev)] transition text-sm opacity-50"><span class="size-6 grid place-items-center rounded-lg ring-1 ring-[var(--c-border)]">🔧</span>API & Webhooks</a>
              </div>
            </div>
          </section>
        </nav>

        <!-- Footer del sidebar -->
        <div class="mt-auto px-4 py-3 border-t border-[var(--c-border)] flex items-center justify-between">
          <a href="#" class="text-xs text-[var(--c-muted)] hover:underline">v1.0</a>
          <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button type="submit" class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-transparent border border-[var(--c-danger)] text-[var(--c-danger)] hover:bg-[var(--c-danger)] hover:text-white transition">
              <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                <polyline points="16,17 21,12 16,7"/>
                <line x1="21" y1="12" x2="9" y2="12"/>
              </svg>
              <span class="text-sm">Salir</span>
            </button>
          </form>
        </div>
      </div>
    </aside>

    <!-- ========================= CONTENIDO (Header + Main + Footer) ========================= -->
    <div class="lg:col-start-2 flex flex-col h-screen">
      <!-- ===== HEADER / TOPBAR ===== -->
      <header class="h-[10vh]">
        @include('components.header')
      </header>

      <!-- ===== MAIN ===== -->
      <main id="dash-main" class="h-[80vh] overflow-auto px-4 sm:px-6 py-6">
        @yield('content')
      </main>

      <!-- ===== FOOTER ===== -->
      <footer class="h-[10vh]">
        @include('components.footer')
      </footer>
    </div>
  <!-- JSON Response Modal -->
  @include('components.json-response-modal')

  <!-- Media Picker Modal -->
  <x-media-picker />

  </div>


  <!-- ========================= SCRIPTS (IDs únicos prefijados con dash-) ========================= -->
  <script src="{{ asset('js/media-picker.js') }}"></script>
  <script>
    (function(){
      // --- Ruta actual ---
      const currentRoute = '{{ request()->route()->getName() }}';

      // --- Mapa de rutas a grupos ---
      // Grupo 1: Dashboard | Grupo 2: Proyectos | Grupo 3: Administración
      // Grupo 4: Catálogos | Grupo 5: Configuración | Grupo 6: Cotizaciones | Grupo 7: Herramientas
      const routeToGroup = {
        'dashboard': 1,
        'projects': 2,
        'projects.show': 2,
        'projects.backlog': 2,
        'projects.gantt': 2,
        'projects.files': 2,
        'users': 3,
        'rbac': 3,
        'catalogs.task-status': 4,
        'catalogs.file-categories': 4,
        'currencies': 4,
        'color-themes': 4,
        'smtp-settings': 5,
        'email-templates': 5,
        'quotes': 6,
        'quotes.settings': 6,
        'funnel': 7,
        'leads': 7,
      };

      // --- Año del footer ---
      const y = document.getElementById('dash-year');
      if (y) y.textContent = new Date().getFullYear();

      // --- Sidebar responsive ---
      const sidebar  = document.getElementById('dash-sidebar');
      const menuBtn  = document.getElementById('dash-menu-btn');
      const backdrop = document.getElementById('dash-backdrop');

      // Backdrop blur condicional (solo si el navegador soporta)
      const enableBlur = CSS.supports && CSS.supports('backdrop-filter: blur(4px)');
      if (enableBlur) {
        const s = document.createElement('style');
        s.textContent = '#dash-backdrop.blur{backdrop-filter:blur(4px)}';
        document.head.appendChild(s);
      }

      const openSidebar = () => {
        sidebar.classList.add('animating');
        if (enableBlur) backdrop.classList.add('blur');
        sidebar.style.transform = 'translateX(0)';
        backdrop.classList.remove('hidden');
        sidebar.addEventListener('transitionend', () => sidebar.classList.remove('animating'), { once: true });
      };
      const closeSidebar = () => {
        sidebar.classList.add('animating');
        sidebar.style.transform = '';
        sidebar.addEventListener('transitionend', () => sidebar.classList.remove('animating'), { once: true });
        if (enableBlur) backdrop.classList.remove('blur');
        backdrop.classList.add('hidden');
      };
      menuBtn?.addEventListener('click', openSidebar);
      backdrop?.addEventListener('click', closeSidebar);
      window.addEventListener('keydown', (e)=>{ if(e.key==='Escape') closeSidebar(); });

      // --- Acordeón modular optimizado (sin max-height) ---
      const ACCORDION_SINGLE_OPEN = true; // solo un módulo abierto a la vez
      const currentGroup = routeToGroup[currentRoute] || 1;
      const accordionItems = [
        { btn: 'dash-acc-btn-1', panel: 'dash-acc-panel-1', icon: 'dash-acc-icon-1', defaultOpen: currentGroup === 1 },
        { btn: 'dash-acc-btn-2', panel: 'dash-acc-panel-2', icon: 'dash-acc-icon-2', defaultOpen: currentGroup === 2 },
        { btn: 'dash-acc-btn-3', panel: 'dash-acc-panel-3', icon: 'dash-acc-icon-3', defaultOpen: currentGroup === 3 },
        { btn: 'dash-acc-btn-4', panel: 'dash-acc-panel-4', icon: 'dash-acc-icon-4', defaultOpen: currentGroup === 4 },
        { btn: 'dash-acc-btn-5', panel: 'dash-acc-panel-5', icon: 'dash-acc-icon-5', defaultOpen: currentGroup === 5 },
        { btn: 'dash-acc-btn-6', panel: 'dash-acc-panel-6', icon: 'dash-acc-icon-6', defaultOpen: currentGroup === 6 },
        { btn: 'dash-acc-btn-7', panel: 'dash-acc-panel-7', icon: 'dash-acc-icon-7', defaultOpen: currentGroup === 7 },
      ];

      const openPanel = (panelEl, btnEl, iconEl) => {
        panelEl.classList.remove('hidden');
        panelEl.classList.add('acc-enter');
        // sube a estado visible en el siguiente frame (transición 0 -> 1)
        requestAnimationFrame(() => {
          panelEl.classList.add('act');
        });
        btnEl.setAttribute('aria-expanded', 'true');
        if (iconEl) iconEl.style.transform = 'rotate(0deg)';
      };

      const closePanel = (panelEl, btnEl, iconEl) => {
        // garantiza transición también al cerrar (1 -> 0)
        panelEl.classList.add('acc-enter');
        panelEl.classList.add('act');
        requestAnimationFrame(() => {
          panelEl.classList.remove('act');
        });
        const onEnd = () => {
          panelEl.classList.add('hidden');
          panelEl.classList.remove('acc-enter');
          panelEl.removeEventListener('transitionend', onEnd);
        };
        panelEl.addEventListener('transitionend', onEnd);
        btnEl.setAttribute('aria-expanded', 'false');
        if (iconEl) iconEl.style.transform = 'rotate(-90deg)';
      };

      // Inicialización: abre el grupo correspondiente y cierra otros
      const instances = accordionItems.map(({btn, panel, icon, defaultOpen}) => {
        const btnEl   = document.getElementById(btn);
        const panelEl = document.getElementById(panel);
        const iconEl  = icon ? document.getElementById(icon) : null;
        if(!btnEl || !panelEl) return null;

        if(defaultOpen) {
          panelEl.classList.remove('hidden'); // abierto de inicio (sin animar)
          btnEl.setAttribute('aria-expanded','true');
          if(iconEl) iconEl.style.transform = 'rotate(0deg)';
        } else {
          panelEl.classList.add('hidden');
          btnEl.setAttribute('aria-expanded','false');
          if(iconEl) iconEl.style.transform = 'rotate(-90deg)';
        }

        btnEl.addEventListener('click', () => {
          const isOpen = btnEl.getAttribute('aria-expanded') === 'true';
          if(isOpen){
            closePanel(panelEl, btnEl, iconEl);
          } else {
            if (ACCORDION_SINGLE_OPEN) {
              accordionItems.forEach(({btn, panel, icon}) => {
                const otherBtn   = document.getElementById(btn);
                const otherPanel = document.getElementById(panel);
                const otherIcon  = icon ? document.getElementById(icon) : null;
                if(otherPanel && otherPanel !== panelEl && !otherPanel.classList.contains('hidden')) {
                  closePanel(otherPanel, otherBtn, otherIcon);
                }
              });
            }
            openPanel(panelEl, btnEl, iconEl);
          }
        });

        return { btnEl, panelEl, iconEl };
      }).filter(Boolean);

      // --- Marcar enlace activo ---
      const links = document.querySelectorAll('#dash-accordion a[data-route]');
      links.forEach(link => {
        const route = link.getAttribute('data-route');
        if(route === currentRoute){
          link.classList.add('active');
        }
      });

      // --- Acciones simuladas ---
      document.getElementById('dash-action-new')?.addEventListener('click', ()=>{
        alert('Acción: Crear nuevo registro');
      });
    })();
  </script>
</body>
</html>
