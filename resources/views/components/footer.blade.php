<!-- ===== FOOTER ===== -->
<footer id="dash-footer" class="px-4 sm:px-6 py-6 border-t border-[var(--c-border)] text-sm text-[var(--c-muted)] flex items-center">
  <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 justify-between">
    <p>© <span id="dash-year"></span> Tu Empresa. Todos los derechos reservados.</p>
    <div class="flex items-center gap-3">
      <a href="#" class="hover:underline">Privacidad</a>
      <a href="#" class="hover:underline">Términos</a>
      <a href="#" class="hover:underline">Contacto</a>
    </div>
  </div>

  @php
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
