<header class="sticky top-0 z-50 border-b border-white/10 bg-[var(--c-bg)]/70 backdrop-blur supports-[backdrop-filter]:bg-[var(--c-bg)]/55">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div x-data="{ open: false }" class="flex h-16 items-center justify-between">
      <!-- Brand -->
      <a href="{{ route('home') }}" class="group inline-flex items-center gap-3">
        <span class="inline-flex size-10 items-center justify-center rounded-2xl bg-gradient-to-br from-[var(--c-primary)] to-[var(--c-primary-2)] shadow-soft ring-1 ring-white/10">
          <img src="{{ asset('img/logo-systems-gg.png') }}" alt="SystemsGG" width="28" height="28" decoding="async" class="size-7 object-contain" />
        </span>
        <span class="leading-tight">
          <span class="block text-sm font-semibold tracking-tight">SystemsGG</span>
          <span class="block text-xs text-[var(--c-muted)]">Software a medida</span>
        </span>
      </a>

      <!-- Desktop nav -->
      <nav class="hidden md:flex items-center gap-1">
        <a href="{{ route('home') }}#inicio" class="rounded-xl px-3 py-2 text-sm text-[var(--c-muted)] hover:text-[var(--c-text)] hover:bg-white/5 transition">Inicio</a>
        <a href="{{ route('home') }}#servicios" class="rounded-xl px-3 py-2 text-sm text-[var(--c-muted)] hover:text-[var(--c-text)] hover:bg-white/5 transition">Servicios</a>
        <a href="{{ route('home') }}#planes" class="rounded-xl px-3 py-2 text-sm text-[var(--c-muted)] hover:text-[var(--c-text)] hover:bg-white/5 transition">Planes</a>
        <a href="{{ route('home') }}#proceso" class="rounded-xl px-3 py-2 text-sm text-[var(--c-muted)] hover:text-[var(--c-text)] hover:bg-white/5 transition">Proceso</a>
        <a href="{{ route('home') }}#proyectos" class="rounded-xl px-3 py-2 text-sm text-[var(--c-muted)] hover:text-[var(--c-text)] hover:bg-white/5 transition">Proyectos</a>
        <a href="{{ route('home') }}#contacto" class="rounded-xl px-3 py-2 text-sm text-[var(--c-muted)] hover:text-[var(--c-text)] hover:bg-white/5 transition">Contacto</a>
      </nav>

      <!-- Desktop CTA -->
      <div class="hidden md:flex items-center gap-2">
        <a href="{{ route('home') }}#contacto" class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-[var(--c-primary)] to-[var(--c-primary-2)] px-4 py-2 text-sm font-semibold text-white shadow-soft ring-1 ring-white/10 hover:opacity-95 transition">
          Cotizar proyecto
        </a>
      </div>

      <!-- Mobile menu button -->
      <button
        type="button"
        class="md:hidden inline-flex items-center justify-center rounded-xl p-2 text-[var(--c-text)] ring-1 ring-white/10 hover:bg-white/5 transition"
        :aria-expanded="open.toString()"
        aria-controls="mkt-mobile-menu"
        @click="open = !open"
      >
        <span class="sr-only">Abrir menú</span>
        <svg x-show="!open" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M4 6h16" />
          <path d="M4 12h16" />
          <path d="M4 18h16" />
        </svg>
        <svg x-show="open" x-cloak class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M18 6 6 18" />
          <path d="M6 6l12 12" />
        </svg>
      </button>

      <!-- Mobile menu panel -->
      <div
        id="mkt-mobile-menu"
        x-show="open"
        x-cloak
        @click.outside="open = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="md:hidden absolute left-4 right-4 top-[4.25rem] rounded-2xl bg-[var(--c-surface)] shadow-soft ring-1 ring-[var(--c-border)]"
      >
        <div class="p-2">
          <a href="{{ route('home') }}#inicio" @click="open=false" class="block rounded-xl px-3 py-2 text-sm text-[var(--c-text)] hover:bg-white/5 transition">Inicio</a>
          <a href="{{ route('home') }}#servicios" @click="open=false" class="block rounded-xl px-3 py-2 text-sm text-[var(--c-text)] hover:bg-white/5 transition">Servicios</a>
          <a href="{{ route('home') }}#planes" @click="open=false" class="block rounded-xl px-3 py-2 text-sm text-[var(--c-text)] hover:bg-white/5 transition">Planes</a>
          <a href="{{ route('home') }}#proceso" @click="open=false" class="block rounded-xl px-3 py-2 text-sm text-[var(--c-text)] hover:bg-white/5 transition">Proceso</a>
          <a href="{{ route('home') }}#proyectos" @click="open=false" class="block rounded-xl px-3 py-2 text-sm text-[var(--c-text)] hover:bg-white/5 transition">Proyectos</a>
          <a href="{{ route('home') }}#contacto" @click="open=false" class="block rounded-xl px-3 py-2 text-sm text-[var(--c-text)] hover:bg-white/5 transition">Contacto</a>

          <div class="my-2 h-px bg-white/10"></div>
          <a href="{{ route('home') }}#contacto" @click="open=false" class="mt-2 inline-flex w-full items-center justify-center rounded-xl bg-gradient-to-r from-[var(--c-primary)] to-[var(--c-primary-2)] px-4 py-2 text-sm font-semibold text-white shadow-soft ring-1 ring-white/10 hover:opacity-95 transition">
            Cotizar proyecto
          </a>
        </div>
      </div>
    </div>
  </div>
</header>

