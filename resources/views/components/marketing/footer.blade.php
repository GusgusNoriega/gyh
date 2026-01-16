<footer class="border-t border-white/10 bg-[var(--c-bg)]">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="py-12 lg:py-16">
      <div class="grid gap-10 lg:grid-cols-12">
        <!-- Brand / resumen -->
        <div class="lg:col-span-4">
          <div class="flex items-center gap-3">
            <span class="inline-flex size-10 items-center justify-center rounded-2xl bg-gradient-to-br from-[var(--c-primary)] to-[var(--c-primary-2)] shadow-soft ring-1 ring-white/10">
              <img src="{{ asset('img/logo-systems-gg.png') }}" alt="SystemsGG" width="28" height="28" decoding="async" loading="lazy" class="size-7 object-contain" />
            </span>
            <div>
              <p class="text-sm font-semibold">SystemsGG</p>
              <p class="text-xs text-[var(--c-muted)]">+11 años creando software a medida</p>
            </div>
          </div>

          <p class="mt-4 text-sm text-[var(--c-muted)] leading-relaxed">
            Desarrollo de páginas web en Lima y software a medida para empresas: plataformas internas, automatización de procesos, integraciones y productos web listos para escalar.
            (Contenido demostrativo.)
          </p>

          <div class="mt-6 flex items-center gap-2">
            <a href="#" class="inline-flex size-10 items-center justify-center rounded-xl bg-white/5 ring-1 ring-white/10 hover:bg-white/10 transition" aria-label="LinkedIn">
              <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z" />
                <rect x="2" y="9" width="4" height="12" />
                <circle cx="4" cy="4" r="2" />
              </svg>
            </a>
            <a href="#" class="inline-flex size-10 items-center justify-center rounded-xl bg-white/5 ring-1 ring-white/10 hover:bg-white/10 transition" aria-label="GitHub">
              <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22" />
              </svg>
            </a>
            <a href="#" class="inline-flex size-10 items-center justify-center rounded-xl bg-white/5 ring-1 ring-white/10 hover:bg-white/10 transition" aria-label="Correo">
              <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="5" width="18" height="14" rx="2" />
                <path d="m3 7 9 6 9-6" />
              </svg>
            </a>
          </div>
        </div>

        <!-- Links -->
        <div class="grid gap-10 sm:grid-cols-2 lg:col-span-8 lg:grid-cols-3">
          <div>
            <p class="text-sm font-semibold">Servicios</p>
            <ul class="mt-4 space-y-2 text-sm text-[var(--c-muted)]">
              <li><a href="{{ route('home') }}#servicios" class="hover:text-[var(--c-text)] transition">Desarrollo de software a medida</a></li>
              <li><a href="{{ route('home') }}#servicios" class="hover:text-[var(--c-text)] transition">Desarrollo web</a></li>
              <li><a href="{{ route('home') }}#servicios" class="hover:text-[var(--c-text)] transition">Integraciones & APIs</a></li>
              <li><a href="{{ route('home') }}#servicios" class="hover:text-[var(--c-text)] transition">Mantenimiento & soporte</a></li>
            </ul>
          </div>

          <div>
            <p class="text-sm font-semibold">Empresa</p>
            <ul class="mt-4 space-y-2 text-sm text-[var(--c-muted)]">
              <li><a href="{{ route('home') }}#proceso" class="hover:text-[var(--c-text)] transition">Cómo trabajamos</a></li>
              <li><a href="{{ route('home') }}#proyectos" class="hover:text-[var(--c-text)] transition">Casos de éxito</a></li>
              <li><a href="#" class="hover:text-[var(--c-text)] transition">Blog</a></li>
            </ul>
          </div>

          <div>
            <p class="text-sm font-semibold">Contacto</p>
            <ul class="mt-4 space-y-2 text-sm text-[var(--c-muted)]">
              <li><span class="text-[var(--c-text)]">Email:</span> hola@systemsgg.com</li>
              <li>
                <span class="text-[var(--c-text)]">WhatsApp:</span>
                <a href="{{ $waUrl ?? ('https://wa.me/51949421023?text=' . rawurlencode('Hola, vengo desde la web de SystemsGG. Quisiera una cotización.')) }}" target="_blank" rel="noopener noreferrer" class="underline hover:text-[var(--c-text)] transition">
                  {{ $waPhoneDisplay ?? '+51 949 421 023' }}
                </a>
              </li>
              <li><span class="text-[var(--c-text)]">Horario:</span> Lun–Vie 9:00am – 8:00pm</li>
              <li><a href="{{ route('home') }}#contacto" class="hover:text-[var(--c-text)] transition">Formulario de contacto</a></li>
            </ul>
          </div>
        </div>
      </div>

      <div class="mt-12 flex flex-col gap-3 border-t border-white/10 pt-8 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-xs text-[var(--c-muted)]">© {{ date('Y') }} SystemsGG. Todos los derechos reservados.</p>
        <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-xs text-[var(--c-muted)]">
          <a href="{{ route('privacidad') }}" class="hover:text-[var(--c-text)] transition">Privacidad</a>
          <a href="{{ route('terminos') }}" class="hover:text-[var(--c-text)] transition">Términos</a>
          <a href="{{ route('cookies') }}" class="hover:text-[var(--c-text)] transition">Cookies</a>
        </div>
      </div>
    </div>
  </div>

  @php
    // Botón flotante (fallback si no viene desde el layout)
    $waPhoneDisplayLocal = $waPhoneDisplay ?? '+51 949 421 023';
    $waPhoneDigitsLocal = $waPhoneDigits ?? preg_replace('/\D+/', '', $waPhoneDisplayLocal);
    $waTextLocal = $waText ?? 'Hola, vengo desde la web de SystemsGG. Quisiera una cotización.';
    $waUrlLocal = $waUrl ?? ('https://wa.me/' . $waPhoneDigitsLocal . '?text=' . rawurlencode($waTextLocal));
  @endphp

  <a
    href="{{ $waUrlLocal }}"
    target="_blank"
    rel="noopener noreferrer"
    class="fixed bottom-6 right-6 z-[9999] inline-flex h-14 w-14 items-center justify-center rounded-full bg-emerald-500 text-white shadow-soft ring-1 ring-emerald-300/30 hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-300"
    aria-label="Abrir chat de WhatsApp"
  >
    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
      <path d="M20.52 3.48A11.91 11.91 0 0 0 12.07 0C5.44 0 .05 5.39.05 12.02c0 2.12.56 4.19 1.62 6.01L0 24l6.15-1.62a11.94 11.94 0 0 0 5.92 1.5h.01c6.63 0 12.02-5.39 12.02-12.02 0-3.2-1.25-6.22-3.58-8.38Zm-8.45 18.36h-.01a9.9 9.9 0 0 1-5.05-1.39l-.36-.21-3.65.96.98-3.56-.23-.37a9.94 9.94 0 0 1-1.53-5.25c0-5.5 4.48-9.98 9.99-9.98 2.67 0 5.17 1.04 7.06 2.92a9.92 9.92 0 0 1 2.93 7.06c0 5.5-4.48 9.98-9.13 9.82Zm5.76-7.87c-.31-.16-1.86-.92-2.15-1.02-.29-.11-.5-.16-.71.16-.21.31-.82 1.02-1.01 1.23-.18.21-.37.23-.68.08-.31-.16-1.31-.48-2.49-1.53-.92-.82-1.54-1.83-1.72-2.14-.18-.31-.02-.48.14-.64.14-.14.31-.37.47-.55.16-.18.21-.31.31-.52.1-.21.05-.39-.03-.55-.08-.16-.71-1.71-.97-2.34-.26-.62-.53-.54-.71-.55h-.61c-.21 0-.55.08-.84.39-.29.31-1.1 1.08-1.1 2.63 0 1.55 1.13 3.05 1.29 3.26.16.21 2.22 3.39 5.38 4.75.75.32 1.33.51 1.78.65.75.24 1.43.21 1.97.13.6-.09 1.86-.76 2.12-1.49.26-.73.26-1.36.18-1.49-.08-.13-.29-.21-.6-.37Z"/>
    </svg>
  </a>
</footer>

