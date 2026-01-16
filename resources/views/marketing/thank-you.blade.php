@extends('layouts.marketing')

@section('title', 'Gracias | SystemsGG')
@section('og_title', 'Gracias | SystemsGG')
@section('canonical', url('/'))

{{-- Evita indexación de la página de gracias --}}
@section('robots', 'noindex, nofollow')
@section('googlebot', 'noindex, nofollow')

@section('content')
  <section class="border-t border-white/10">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
      <div class="py-14 lg:py-20">
        <div class="rounded-3xl bg-[var(--c-surface)] p-6 ring-1 ring-[var(--c-border)] shadow-soft">
          <h1 class="text-2xl font-semibold tracking-tight sm:text-3xl">Gracias</h1>

          @if(!empty($expired) || empty($lead))
            <p class="mt-3 text-sm text-[var(--c-muted)] leading-relaxed">
              Esta página ya fue utilizada o el enlace no es válido. Por seguridad, los datos del formulario sólo se pueden ver una vez.
            </p>

            <div class="mt-6">
              <a href="{{ route('home') }}" class="inline-flex items-center justify-center rounded-xl bg-white/5 px-5 py-3 text-sm font-semibold text-[var(--c-text)] ring-1 ring-white/10 hover:bg-white/10 transition">
                Volver al inicio
              </a>
            </div>
          @else
            <p class="mt-3 text-sm text-[var(--c-muted)] leading-relaxed">
              Hemos recibido tu información. A continuación te mostramos un resumen (sólo disponible en esta visita).
            </p>

            <div class="mt-6 grid gap-4 sm:grid-cols-2">
              <div class="rounded-2xl bg-[var(--c-elev)] p-4 ring-1 ring-[var(--c-border)]">
                <p class="text-xs text-[var(--c-muted)]">Nombre</p>
                <p class="mt-1 text-sm font-semibold break-words">{{ $lead->name ?? '—' }}</p>
              </div>
              <div class="rounded-2xl bg-[var(--c-elev)] p-4 ring-1 ring-[var(--c-border)]">
                <p class="text-xs text-[var(--c-muted)]">Email</p>
                <p class="mt-1 text-sm font-semibold break-words">{{ $lead->email ?? '—' }}</p>
              </div>
              <div class="rounded-2xl bg-[var(--c-elev)] p-4 ring-1 ring-[var(--c-border)]">
                <p class="text-xs text-[var(--c-muted)]">Teléfono</p>
                <p class="mt-1 text-sm font-semibold break-words">{{ $lead->phone ?? '—' }}</p>
              </div>
              <div class="rounded-2xl bg-[var(--c-elev)] p-4 ring-1 ring-[var(--c-border)]">
                <p class="text-xs text-[var(--c-muted)]">Tipo de proyecto</p>
                <p class="mt-1 text-sm font-semibold break-words">{{ $lead->project_type ?? '—' }}</p>
              </div>

              @if($lead->is_company)
                <div class="rounded-2xl bg-[var(--c-elev)] p-4 ring-1 ring-[var(--c-border)]">
                  <p class="text-xs text-[var(--c-muted)]">Empresa</p>
                  <p class="mt-1 text-sm font-semibold break-words">{{ $lead->company_name ?? '—' }}</p>
                </div>
                <div class="rounded-2xl bg-[var(--c-elev)] p-4 ring-1 ring-[var(--c-border)]">
                  <p class="text-xs text-[var(--c-muted)]">RUC</p>
                  <p class="mt-1 text-sm font-semibold break-words">{{ $lead->company_ruc ?? '—' }}</p>
                </div>
              @endif

              <div class="sm:col-span-2 rounded-2xl bg-[var(--c-elev)] p-4 ring-1 ring-[var(--c-border)]">
                <p class="text-xs text-[var(--c-muted)]">Mensaje</p>
                <p class="mt-1 text-sm font-semibold whitespace-pre-wrap break-words">{{ $lead->message ?? '—' }}</p>
              </div>
            </div>

            <div class="mt-7">
              <a href="{{ route('home') }}" class="inline-flex items-center justify-center rounded-xl bg-white/5 px-5 py-3 text-sm font-semibold text-[var(--c-text)] ring-1 ring-white/10 hover:bg-white/10 transition">
                Volver al inicio
              </a>
            </div>
          @endif
        </div>
      </div>
    </div>
  </section>
@endsection

@section('scripts')
  @if(empty($expired) && !empty($lead))
    <!-- Event snippet for Enviar formulario de clientes potenciales (1) conversion page -->
    <script>
      // Dispara la conversión SOLO cuando existe un lead válido (formulario enviado con éxito)
      // y esta vista no está expirada.
      if (typeof gtag === 'function') {
        gtag('event', 'conversion', {
          'send_to': 'AW-17511185603/158bCOyJ2NsbEMP5_Z1B',
          'value': 1.0,
          'currency': 'PEN'
        });
      }
    </script>
  @endif
@endsection

