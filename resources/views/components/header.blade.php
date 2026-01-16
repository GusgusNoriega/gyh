<!-- ===== HEADER / TOPBAR ===== -->
<header id="dash-header" class="bg-[var(--c-bg)]/80 backdrop-blur border-b border-[var(--c-border)] flex items-center">
  <div class="flex items-center justify-between gap-3 px-4 sm:px-6 py-3 w-full">
    <!-- Botón hamburguesa (móvil) -->
    <button id="dash-menu-btn" class="lg:hidden inline-flex items-center gap-2 px-3 py-2 rounded-xl ring-1 ring-[var(--c-border)]">
      <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M3 12h18"/><path d="M3 18h18"/></svg>
      <span class="text-sm">Menú</span>
    </button>

    <!-- Migas / Título -->
    <div class="hidden sm:flex items-center gap-2 text-sm w-full">
      @php
        $currentRoute = Route::currentRouteName();
        $breadcrumbs = [
          // Dashboard
          'dashboard' => [['Inicio', route('dashboard')], ['Dashboard', '#']],
          
          // Proyectos
          'projects' => [['Inicio', route('dashboard')], ['Proyectos', '#'], ['Todos los Proyectos', '#']],
          'projects.show' => [['Inicio', route('dashboard')], ['Proyectos', route('projects')], ['Detalle', '#']],
          'projects.backlog' => [['Inicio', route('dashboard')], ['Proyectos', route('projects')], ['Detalle', '#'], ['Backlog', '#']],
          'projects.gantt' => [['Inicio', route('dashboard')], ['Proyectos', route('projects')], ['Detalle', '#'], ['Gantt', '#']],
          'projects.files' => [['Inicio', route('dashboard')], ['Proyectos', route('projects')], ['Detalle', '#'], ['Archivos', '#']],
          
          // Administración
          'users' => [['Inicio', route('dashboard')], ['Administración', '#'], ['Usuarios', '#']],
          'rbac' => [['Inicio', route('dashboard')], ['Administración', '#'], ['Roles & Permisos', '#']],
          
          // Catálogos
          'catalogs.task-status' => [['Inicio', route('dashboard')], ['Catálogos', '#'], ['Estados de Tarea', '#']],
          'catalogs.file-categories' => [['Inicio', route('dashboard')], ['Catálogos', '#'], ['Categorías de Archivo', '#']],
          'currencies' => [['Inicio', route('dashboard')], ['Catálogos', '#'], ['Monedas', '#']],
          'color-themes' => [['Inicio', route('dashboard')], ['Catálogos', '#'], ['Temas de Color', '#']],
          
          // Configuración
          'smtp-settings' => [['Inicio', route('dashboard')], ['Configuración', '#'], ['Configuración SMTP', '#']],
          'email-templates' => [['Inicio', route('dashboard')], ['Configuración', '#'], ['Plantillas de Email', '#']],
          
          // Cotizaciones
          'quotes' => [['Inicio', route('dashboard')], ['Cotizaciones', '#'], ['Todas las Cotizaciones', '#']],
          'quotes.settings' => [['Inicio', route('dashboard')], ['Cotizaciones', route('quotes')], ['Configuración', '#']],
          
          // Herramientas
          'funnel' => [['Inicio', route('dashboard')], ['Herramientas', '#'], ['Funnel', '#']],
          'leads' => [['Inicio', route('dashboard')], ['Herramientas', '#'], ['Leads', '#']],
        ];
        $crumbs = $breadcrumbs[$currentRoute] ?? [['Inicio', '/'], ['Página', '#']];
      @endphp
      @foreach($crumbs as $index => $crumb)
        @if($index > 0)
          <span class="opacity-50">/</span>
        @endif
        @if($crumb[1] !== '#')
          <a href="{{ $crumb[1] }}" class="text-[var(--c-muted)] hover:text-[var(--c-text)]">{{ $crumb[0] }}</a>
        @else
          <span class="font-medium">{{ $crumb[0] }}</span>
        @endif
      @endforeach
    </div>

    <!-- Acciones derechas -->
    <div class="flex items-center justify-end gap-2 sm:gap-3 ml-auto w-full">
      <div class="hidden md:flex items-center gap-2 rounded-2xl bg-[var(--c-elev)] px-3 py-2 ring-1 ring-[var(--c-border)] focus-within:ring-[var(--c-primary)]">
        <svg class="size-5 opacity-70" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
        <input id="dash-top-search" type="search" placeholder="Buscar en todo…" class="bg-transparent outline-none w-64 text-sm placeholder:text-[var(--c-muted)]" />
      </div>
      <button id="dash-action-new" class="hidden sm:inline-flex items-center gap-2 text-sm px-3 py-2 rounded-xl bg-[var(--c-primary)] text-white hover:opacity-95 shadow-soft">
        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
        Nuevo
      </button>
      <button id="dash-bell" class="inline-flex size-10 items-center justify-center rounded-xl ring-1 ring-[var(--c-border)] hover:ring-[var(--c-primary)]">
        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.7 1.7 0 0 0 3.4 0"/></svg>
      </button>
      <button id="dash-avatar" class="inline-flex items-center gap-3 px-2 py-1 rounded-xl ring-1 ring-[var(--c-border)] hover:ring-[var(--c-primary)]">
        @php
          $user = auth()->user()->load('profileImage');
        @endphp
        @if($user && $user->profileImage)
          <img alt="avatar" src="{{ $user->profileImage->url }}" class="size-8 rounded-lg"/>
        @else
          <svg class="size-8 text-[var(--c-muted)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
            <circle cx="12" cy="7" r="4"/>
          </svg>
        @endif
        <span class="hidden sm:block text-sm">{{ $user ? $user->name : 'Usuario' }}</span>
      </button>
    </div>
  </div>
</header>
