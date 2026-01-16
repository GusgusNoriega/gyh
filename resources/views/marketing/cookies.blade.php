@extends('layouts.marketing')

@section('title', 'Política de Cookies - SystemsGG')
@section('og_title', 'Política de Cookies - SystemsGG')
@section('canonical', url('/cookies'))
@section('meta_description', 'Política de cookies de SystemsGG. Conoce qué cookies utilizamos, para qué sirven y cómo puedes gestionarlas.')
@section('robots', 'index, follow')

@section('content')
<section class="py-14 lg:py-20">
  <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-12">
      <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm text-[var(--c-muted)] hover:text-[var(--c-text)] transition mb-6">
        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        Volver al inicio
      </a>
      <h1 class="text-3xl font-semibold tracking-tight sm:text-4xl">Política de Cookies</h1>
      <p class="mt-3 text-sm text-[var(--c-muted)]">Última actualización: {{ now()->format('d/m/Y') }}</p>
    </div>

    <!-- Content -->
    <div class="prose prose-invert prose-sm max-w-none">
      <div class="rounded-3xl bg-[var(--c-surface)] p-6 sm:p-8 ring-1 ring-[var(--c-border)] space-y-8">
        
        <!-- Introducción -->
        <div>
          <h2 class="text-xl font-semibold text-[var(--c-text)] mb-4">1. ¿Qué son las Cookies?</h2>
          <p class="text-[var(--c-muted)] leading-relaxed">
            Las cookies son pequeños archivos de texto que se almacenan en tu dispositivo (ordenador, tablet o móvil) cuando visitas un sitio web. Estas cookies permiten que el sitio recuerde tus acciones y preferencias (como idioma, tamaño de fuente y otras preferencias de visualización) durante un período de tiempo, para que no tengas que volver a configurarlas cada vez que regreses al sitio o navegues de una página a otra.
          </p>
          <p class="text-[var(--c-muted)] leading-relaxed mt-3">
            En <strong class="text-[var(--c-text)]">SystemsGG</strong> (systemsgg.com), operado por <strong class="text-[var(--c-text)]">SISTEMSGG SAC</strong>, utilizamos cookies y tecnologías similares para mejorar tu experiencia de navegación, analizar el tráfico del sitio y mostrar publicidad relevante.
          </p>
        </div>

        <!-- Tipos de cookies -->
        <div>
          <h2 class="text-xl font-semibold text-[var(--c-text)] mb-4">2. Tipos de Cookies que Utilizamos</h2>
          <p class="text-[var(--c-muted)] leading-relaxed mb-6">
            Nuestro sitio web utiliza los siguientes tipos de cookies:
          </p>

          <!-- Cookies técnicas -->
          <div class="bg-[var(--c-elev)] rounded-2xl p-5 ring-1 ring-[var(--c-border)] mb-4">
            <div class="flex items-start gap-3">
              <span class="mt-0.5 flex size-8 shrink-0 items-center justify-center rounded-xl bg-blue-500/20 text-blue-400">
                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v4"/><path d="M12 18v4"/><path d="m4.93 4.93 2.83 2.83"/><path d="m16.24 16.24 2.83 2.83"/><path d="M2 12h4"/><path d="M18 12h4"/><path d="m4.93 19.07 2.83-2.83"/><path d="m16.24 7.76 2.83-2.83"/></svg>
              </span>
              <div>
                <h3 class="text-base font-semibold text-[var(--c-text)]">Cookies Técnicas (Necesarias)</h3>
                <p class="mt-2 text-sm text-[var(--c-muted)]">
                  Son esenciales para el funcionamiento del sitio web. Permiten la navegación y el uso de funciones básicas como acceder a áreas seguras. Sin estas cookies, el sitio web no puede funcionar correctamente.
                </p>
                <div class="mt-3 text-xs">
                  <span class="inline-flex items-center rounded-full bg-emerald-500/20 px-2 py-1 text-emerald-400 ring-1 ring-emerald-500/30">Siempre activas</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Cookies de análisis -->
          <div class="bg-[var(--c-elev)] rounded-2xl p-5 ring-1 ring-[var(--c-border)] mb-4">
            <div class="flex items-start gap-3">
              <span class="mt-0.5 flex size-8 shrink-0 items-center justify-center rounded-xl bg-purple-500/20 text-purple-400">
                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="m18.7 8-5.1 5.2-2.8-2.7L7 14.3"/></svg>
              </span>
              <div>
                <h3 class="text-base font-semibold text-[var(--c-text)]">Cookies de Análisis (Analytics)</h3>
                <p class="mt-2 text-sm text-[var(--c-muted)]">
                  Nos permiten reconocer y contar el número de visitantes, ver cómo navegan por el sitio y qué páginas visitan. Esto nos ayuda a mejorar el funcionamiento del sitio web, por ejemplo, asegurándonos de que los usuarios encuentren fácilmente lo que buscan.
                </p>
                <div class="mt-3 text-xs">
                  <span class="inline-flex items-center rounded-full bg-amber-500/20 px-2 py-1 text-amber-400 ring-1 ring-amber-500/30">Con tu consentimiento</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Cookies de publicidad -->
          <div class="bg-[var(--c-elev)] rounded-2xl p-5 ring-1 ring-[var(--c-border)] mb-4">
            <div class="flex items-start gap-3">
              <span class="mt-0.5 flex size-8 shrink-0 items-center justify-center rounded-xl bg-orange-500/20 text-orange-400">
                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
              </span>
              <div>
                <h3 class="text-base font-semibold text-[var(--c-text)]">Cookies de Publicidad y Marketing</h3>
                <p class="mt-2 text-sm text-[var(--c-muted)]">
                  Estas cookies se utilizan para mostrar anuncios más relevantes para ti y tus intereses. También se utilizan para limitar el número de veces que ves un anuncio y para medir la efectividad de las campañas publicitarias. Nos ayudan a medir el rendimiento de Google Ads y otras plataformas.
                </p>
                <div class="mt-3 text-xs">
                  <span class="inline-flex items-center rounded-full bg-amber-500/20 px-2 py-1 text-amber-400 ring-1 ring-amber-500/30">Con tu consentimiento</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Cookies de funcionalidad -->
          <div class="bg-[var(--c-elev)] rounded-2xl p-5 ring-1 ring-[var(--c-border)]">
            <div class="flex items-start gap-3">
              <span class="mt-0.5 flex size-8 shrink-0 items-center justify-center rounded-xl bg-teal-500/20 text-teal-400">
                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
              </span>
              <div>
                <h3 class="text-base font-semibold text-[var(--c-text)]">Cookies de Funcionalidad</h3>
                <p class="mt-2 text-sm text-[var(--c-muted)]">
                  Permiten recordar tus preferencias y personalizar la experiencia del sitio, como el idioma preferido o la región desde la que accedes. Sin estas cookies, algunos servicios podrían no funcionar correctamente.
                </p>
                <div class="mt-3 text-xs">
                  <span class="inline-flex items-center rounded-full bg-amber-500/20 px-2 py-1 text-amber-400 ring-1 ring-amber-500/30">Con tu consentimiento</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Cookies específicas -->
        <div>
          <h2 class="text-xl font-semibold text-[var(--c-text)] mb-4">3. Cookies Específicas del Sitio</h2>
          <p class="text-[var(--c-muted)] leading-relaxed mb-4">
            A continuación detallamos las cookies específicas que utilizamos:
          </p>
          
          <div class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead>
                <tr class="border-b border-[var(--c-border)]">
                  <th class="text-left py-3 px-4 text-[var(--c-text)] font-semibold">Cookie</th>
                  <th class="text-left py-3 px-4 text-[var(--c-text)] font-semibold">Proveedor</th>
                  <th class="text-left py-3 px-4 text-[var(--c-text)] font-semibold">Finalidad</th>
                  <th class="text-left py-3 px-4 text-[var(--c-text)] font-semibold">Duración</th>
                </tr>
              </thead>
              <tbody class="text-[var(--c-muted)]">
                <tr class="border-b border-[var(--c-border)]/50">
                  <td class="py-3 px-4 font-mono text-xs">_ga</td>
                  <td class="py-3 px-4">Google Analytics</td>
                  <td class="py-3 px-4">Distinguir usuarios únicos</td>
                  <td class="py-3 px-4">2 años</td>
                </tr>
                <tr class="border-b border-[var(--c-border)]/50">
                  <td class="py-3 px-4 font-mono text-xs">_ga_*</td>
                  <td class="py-3 px-4">Google Analytics</td>
                  <td class="py-3 px-4">Mantener estado de sesión</td>
                  <td class="py-3 px-4">2 años</td>
                </tr>
                <tr class="border-b border-[var(--c-border)]/50">
                  <td class="py-3 px-4 font-mono text-xs">_gid</td>
                  <td class="py-3 px-4">Google Analytics</td>
                  <td class="py-3 px-4">Distinguir usuarios</td>
                  <td class="py-3 px-4">24 horas</td>
                </tr>
                <tr class="border-b border-[var(--c-border)]/50">
                  <td class="py-3 px-4 font-mono text-xs">_gcl_au</td>
                  <td class="py-3 px-4">Google Ads</td>
                  <td class="py-3 px-4">Seguimiento de conversiones</td>
                  <td class="py-3 px-4">90 días</td>
                </tr>
                <tr class="border-b border-[var(--c-border)]/50">
                  <td class="py-3 px-4 font-mono text-xs">_gac_*</td>
                  <td class="py-3 px-4">Google Ads</td>
                  <td class="py-3 px-4">Información de campaña</td>
                  <td class="py-3 px-4">90 días</td>
                </tr>
                <tr class="border-b border-[var(--c-border)]/50">
                  <td class="py-3 px-4 font-mono text-xs">XSRF-TOKEN</td>
                  <td class="py-3 px-4">SystemsGG</td>
                  <td class="py-3 px-4">Seguridad (CSRF)</td>
                  <td class="py-3 px-4">2 horas</td>
                </tr>
                <tr>
                  <td class="py-3 px-4 font-mono text-xs">session</td>
                  <td class="py-3 px-4">SystemsGG</td>
                  <td class="py-3 px-4">Sesión del usuario</td>
                  <td class="py-3 px-4">2 horas</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Cookies de terceros -->
        <div>
          <h2 class="text-xl font-semibold text-[var(--c-text)] mb-4">4. Cookies de Terceros</h2>
          <p class="text-[var(--c-muted)] leading-relaxed mb-4">
            Utilizamos servicios de terceros que pueden establecer sus propias cookies:
          </p>

          <div class="space-y-4">
            <!-- Google Analytics -->
            <div class="bg-[var(--c-elev)] rounded-2xl p-4 ring-1 ring-[var(--c-border)]">
              <h3 class="text-sm font-semibold text-[var(--c-text)]">Google Analytics</h3>
              <p class="mt-2 text-sm text-[var(--c-muted)]">
                Utilizamos Google Analytics para entender cómo los visitantes usan nuestro sitio web. Google Analytics recopila información de forma anónima e informa de las tendencias del sitio web sin identificar a visitantes individuales.
              </p>
              <p class="mt-2 text-xs text-[var(--c-muted)]">
                Más información: <a href="https://policies.google.com/privacy" target="_blank" rel="noopener noreferrer" class="text-[var(--c-primary)] hover:underline">Política de Privacidad de Google</a>
              </p>
            </div>

            <!-- Google Ads -->
            <div class="bg-[var(--c-elev)] rounded-2xl p-4 ring-1 ring-[var(--c-border)]">
              <h3 class="text-sm font-semibold text-[var(--c-text)]">Google Ads (AdWords)</h3>
              <p class="mt-2 text-sm text-[var(--c-muted)]">
                Utilizamos Google Ads para mostrar anuncios en la red de Google y medir la efectividad de nuestras campañas publicitarias. Las cookies de conversión nos ayudan a entender qué anuncios generan consultas y ventas.
              </p>
              <p class="mt-2 text-xs text-[var(--c-muted)]">
                Más información: <a href="https://policies.google.com/technologies/ads" target="_blank" rel="noopener noreferrer" class="text-[var(--c-primary)] hover:underline">Cómo usa Google las cookies en publicidad</a>
              </p>
            </div>

            <!-- Google Tag Manager -->
            <div class="bg-[var(--c-elev)] rounded-2xl p-4 ring-1 ring-[var(--c-border)]">
              <h3 class="text-sm font-semibold text-[var(--c-text)]">Google Tag Manager</h3>
              <p class="mt-2 text-sm text-[var(--c-muted)]">
                Utilizamos Google Tag Manager para gestionar las etiquetas de seguimiento y análisis del sitio web. GTM en sí mismo no recopila datos personales, pero facilita la implementación de otros servicios que sí lo hacen.
              </p>
            </div>
          </div>
        </div>

        <!-- Gestión de cookies -->
        <div>
          <h2 class="text-xl font-semibold text-[var(--c-text)] mb-4">5. Cómo Gestionar las Cookies</h2>
          <p class="text-[var(--c-muted)] leading-relaxed mb-4">
            Puedes configurar tu navegador para rechazar cookies o para que te avise cuando se envíen cookies. Ten en cuenta que si deshabilitas las cookies, es posible que algunas partes del sitio no funcionen correctamente.
          </p>

          <h3 class="text-base font-semibold text-[var(--c-text)] mt-6 mb-3">5.1 Configuración en navegadores</h3>
          <div class="grid gap-3 sm:grid-cols-2">
            <a href="https://support.google.com/chrome/answer/95647" target="_blank" rel="noopener noreferrer" class="bg-[var(--c-elev)] rounded-2xl p-4 ring-1 ring-[var(--c-border)] hover:ring-[var(--c-primary)]/50 transition group">
              <p class="text-sm font-semibold text-[var(--c-text)] group-hover:text-[var(--c-primary)]">Google Chrome</p>
              <p class="mt-1 text-xs text-[var(--c-muted)]">Configuración → Privacidad y seguridad</p>
            </a>
            <a href="https://support.mozilla.org/es/kb/habilitar-y-deshabilitar-cookies-sitios-web-rastrear-preferencias" target="_blank" rel="noopener noreferrer" class="bg-[var(--c-elev)] rounded-2xl p-4 ring-1 ring-[var(--c-border)] hover:ring-[var(--c-primary)]/50 transition group">
              <p class="text-sm font-semibold text-[var(--c-text)] group-hover:text-[var(--c-primary)]">Mozilla Firefox</p>
              <p class="mt-1 text-xs text-[var(--c-muted)]">Opciones → Privacidad y seguridad</p>
            </a>
            <a href="https://support.apple.com/es-es/guide/safari/sfri11471/mac" target="_blank" rel="noopener noreferrer" class="bg-[var(--c-elev)] rounded-2xl p-4 ring-1 ring-[var(--c-border)] hover:ring-[var(--c-primary)]/50 transition group">
              <p class="text-sm font-semibold text-[var(--c-text)] group-hover:text-[var(--c-primary)]">Safari</p>
              <p class="mt-1 text-xs text-[var(--c-muted)]">Preferencias → Privacidad</p>
            </a>
            <a href="https://support.microsoft.com/es-es/microsoft-edge/eliminar-las-cookies-en-microsoft-edge-63947406-40ac-c3b8-57b9-2a946a29ae09" target="_blank" rel="noopener noreferrer" class="bg-[var(--c-elev)] rounded-2xl p-4 ring-1 ring-[var(--c-border)] hover:ring-[var(--c-primary)]/50 transition group">
              <p class="text-sm font-semibold text-[var(--c-text)] group-hover:text-[var(--c-primary)]">Microsoft Edge</p>
              <p class="mt-1 text-xs text-[var(--c-muted)]">Configuración → Cookies y permisos</p>
            </a>
          </div>

          <h3 class="text-base font-semibold text-[var(--c-text)] mt-6 mb-3">5.2 Opt-out de publicidad personalizada</h3>
          <p class="text-[var(--c-muted)] leading-relaxed mb-3">
            Para optar por no recibir publicidad personalizada de Google:
          </p>
          <ul class="text-[var(--c-muted)] space-y-2 list-disc list-inside">
            <li><a href="https://adssettings.google.com/" target="_blank" rel="noopener noreferrer" class="text-[var(--c-primary)] hover:underline">Configuración de anuncios de Google</a></li>
            <li><a href="https://optout.networkadvertising.org/" target="_blank" rel="noopener noreferrer" class="text-[var(--c-primary)] hover:underline">Network Advertising Initiative opt-out</a></li>
            <li><a href="https://optout.aboutads.info/" target="_blank" rel="noopener noreferrer" class="text-[var(--c-primary)] hover:underline">Digital Advertising Alliance opt-out</a></li>
          </ul>
        </div>

        <!-- Consentimiento -->
        <div>
          <h2 class="text-xl font-semibold text-[var(--c-text)] mb-4">6. Consentimiento</h2>
          <p class="text-[var(--c-muted)] leading-relaxed">
            Al continuar navegando por nuestro sitio web después de haber sido informado de nuestra política de cookies, aceptas el uso de las mismas. No obstante, puedes cambiar tus preferencias en cualquier momento siguiendo las instrucciones indicadas anteriormente.
          </p>
          <p class="text-[var(--c-muted)] leading-relaxed mt-3">
            Si has dado tu consentimiento para el uso de cookies y deseas retirarlo, puedes eliminar las cookies instaladas en tu navegador y modificar la configuración para rechazar nuevas cookies.
          </p>
        </div>

        <!-- Actualizaciones -->
        <div>
          <h2 class="text-xl font-semibold text-[var(--c-text)] mb-4">7. Actualizaciones de esta Política</h2>
          <p class="text-[var(--c-muted)] leading-relaxed">
            Podemos actualizar esta Política de Cookies periódicamente para reflejar cambios en las cookies que utilizamos o por otras razones operativas, legales o regulatorias. Te recomendamos que consultes esta página regularmente para estar informado sobre el uso de cookies.
          </p>
        </div>

        <!-- Contacto -->
        <div>
          <h2 class="text-xl font-semibold text-[var(--c-text)] mb-4">8. Contacto</h2>
          <p class="text-[var(--c-muted)] leading-relaxed mb-4">
            Si tienes preguntas sobre nuestra Política de Cookies, puedes contactarnos:
          </p>
          <div class="bg-[var(--c-elev)] rounded-2xl p-4 ring-1 ring-[var(--c-border)]">
            <ul class="text-[var(--c-muted)] space-y-2">
              <li><strong class="text-[var(--c-text)]">Email:</strong> <a href="mailto:hola@systemsgg.com" class="text-[var(--c-primary)] hover:underline">hola@systemsgg.com</a></li>
              <li><strong class="text-[var(--c-text)]">WhatsApp:</strong> +51 949 421 023</li>
              <li><strong class="text-[var(--c-text)]">Formulario:</strong> <a href="{{ route('home') }}#contacto" class="text-[var(--c-primary)] hover:underline">systemsgg.com/contacto</a></li>
            </ul>
          </div>
        </div>

      </div>
    </div>

    <!-- Links relacionados -->
    <div class="mt-8 flex flex-wrap items-center justify-center gap-4 text-sm text-[var(--c-muted)]">
      <a href="{{ route('privacidad') }}" class="hover:text-[var(--c-text)] transition">Política de Privacidad</a>
      <span>•</span>
      <a href="{{ route('terminos') }}" class="hover:text-[var(--c-text)] transition">Términos y Condiciones</a>
    </div>
  </div>
</section>
@endsection
