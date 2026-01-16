@extends('layouts.marketing')

@section('title', 'Política de Privacidad - SystemsGG')
@section('og_title', 'Política de Privacidad - SystemsGG')
@section('canonical', url('/privacidad'))
@section('meta_description', 'Política de privacidad de SystemsGG. Conoce cómo recopilamos, usamos y protegemos tu información personal.')
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
      <h1 class="text-3xl font-semibold tracking-tight sm:text-4xl">Política de Privacidad</h1>
      <p class="mt-3 text-sm text-[var(--c-muted)]">Última actualización: {{ now()->format('d/m/Y') }}</p>
    </div>

    <!-- Content -->
    <div class="prose prose-invert prose-sm max-w-none">
      <div class="rounded-3xl bg-[var(--c-surface)] p-6 sm:p-8 ring-1 ring-[var(--c-border)] space-y-8">
        
        <!-- Introducción -->
        <div>
          <h2 class="text-xl font-semibold text-[var(--c-text)] mb-4">1. Introducción</h2>
          <p class="text-[var(--c-muted)] leading-relaxed">
            En <strong class="text-[var(--c-text)]">SISTEMSGG SAC</strong> (en adelante "SystemsGG", "nosotros" o "la empresa"), nos comprometemos a proteger la privacidad y los datos personales de nuestros usuarios y clientes. Esta Política de Privacidad describe cómo recopilamos, utilizamos, almacenamos y protegemos tu información personal cuando visitas nuestro sitio web, utilizas nuestros servicios o te comunicas con nosotros.
          </p>
          <p class="text-[var(--c-muted)] leading-relaxed mt-3">
            Al utilizar nuestro sitio web o servicios, aceptas las prácticas descritas en esta política. Te recomendamos leer este documento detenidamente.
          </p>
        </div>

        <!-- Responsable del tratamiento -->
        <div>
          <h2 class="text-xl font-semibold text-[var(--c-text)] mb-4">2. Responsable del Tratamiento de Datos</h2>
          <div class="bg-[var(--c-elev)] rounded-2xl p-4 ring-1 ring-[var(--c-border)]">
            <ul class="text-[var(--c-muted)] space-y-2">
              <li><strong class="text-[var(--c-text)]">Razón social:</strong> SISTEMSGG SAC</li>
              <li><strong class="text-[var(--c-text)]">Sitio web:</strong> systemsgg.com</li>
              <li><strong class="text-[var(--c-text)]">Correo de contacto:</strong> hola@systemsgg.com</li>
              <li><strong class="text-[var(--c-text)]">Ubicación:</strong> Lima, Perú</li>
            </ul>
          </div>
        </div>

        <!-- Datos que recopilamos -->
        <div>
          <h2 class="text-xl font-semibold text-[var(--c-text)] mb-4">3. Información que Recopilamos</h2>
          <p class="text-[var(--c-muted)] leading-relaxed mb-4">
            Recopilamos diferentes tipos de información dependiendo de cómo interactúas con nosotros:
          </p>
          
          <h3 class="text-base font-semibold text-[var(--c-text)] mt-6 mb-3">3.1 Información que proporcionas directamente</h3>
          <ul class="text-[var(--c-muted)] space-y-2 list-disc list-inside">
            <li><strong class="text-[var(--c-text)]">Datos de contacto:</strong> nombre, correo electrónico, número de teléfono</li>
            <li><strong class="text-[var(--c-text)]">Datos empresariales:</strong> nombre de la empresa, RUC (si aplica)</li>
            <li><strong class="text-[var(--c-text)]">Información del proyecto:</strong> tipo de proyecto, descripción, presupuesto aproximado</li>
            <li><strong class="text-[var(--c-text)]">Comunicaciones:</strong> mensajes enviados a través de formularios de contacto</li>
          </ul>

          <h3 class="text-base font-semibold text-[var(--c-text)] mt-6 mb-3">3.2 Información recopilada automáticamente</h3>
          <ul class="text-[var(--c-muted)] space-y-2 list-disc list-inside">
            <li><strong class="text-[var(--c-text)]">Datos de navegación:</strong> dirección IP, tipo de navegador, sistema operativo, páginas visitadas, tiempo de permanencia</li>
            <li><strong class="text-[var(--c-text)]">Cookies y tecnologías similares:</strong> información recopilada mediante cookies para mejorar la experiencia de usuario y analizar el tráfico del sitio</li>
            <li><strong class="text-[var(--c-text)]">Datos de dispositivo:</strong> tipo de dispositivo, resolución de pantalla, idioma preferido</li>
          </ul>

          <h3 class="text-base font-semibold text-[var(--c-text)] mt-6 mb-3">3.3 Información de terceros</h3>
          <ul class="text-[var(--c-muted)] space-y-2 list-disc list-inside">
            <li><strong class="text-[var(--c-text)]">Google Analytics:</strong> estadísticas de uso del sitio web</li>
            <li><strong class="text-[var(--c-text)]">Google Ads:</strong> datos de conversión para medir la efectividad de nuestras campañas publicitarias</li>
          </ul>
        </div>

        <!-- Finalidad del tratamiento -->
        <div>
          <h2 class="text-xl font-semibold text-[var(--c-text)] mb-4">4. Finalidad del Tratamiento de Datos</h2>
          <p class="text-[var(--c-muted)] leading-relaxed mb-4">
            Utilizamos tu información personal para los siguientes propósitos:
          </p>
          <ul class="text-[var(--c-muted)] space-y-3">
            <li class="flex items-start gap-3">
              <span class="mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-full bg-emerald-500/20 text-xs text-emerald-400">✓</span>
              <span><strong class="text-[var(--c-text)]">Responder consultas:</strong> gestionar y responder a las solicitudes de cotización e información que nos envíes.</span>
            </li>
            <li class="flex items-start gap-3">
              <span class="mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-full bg-emerald-500/20 text-xs text-emerald-400">✓</span>
              <span><strong class="text-[var(--c-text)]">Prestación de servicios:</strong> desarrollar y entregar los proyectos contratados.</span>
            </li>
            <li class="flex items-start gap-3">
              <span class="mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-full bg-emerald-500/20 text-xs text-emerald-400">✓</span>
              <span><strong class="text-[var(--c-text)]">Comunicaciones comerciales:</strong> enviarte información sobre nuestros servicios, promociones u ofertas (solo si has dado tu consentimiento).</span>
            </li>
            <li class="flex items-start gap-3">
              <span class="mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-full bg-emerald-500/20 text-xs text-emerald-400">✓</span>
              <span><strong class="text-[var(--c-text)]">Mejora del sitio web:</strong> analizar el uso del sitio para mejorar la experiencia de usuario y el contenido.</span>
            </li>
            <li class="flex items-start gap-3">
              <span class="mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-full bg-emerald-500/20 text-xs text-emerald-400">✓</span>
              <span><strong class="text-[var(--c-text)]">Publicidad personalizada:</strong> mostrar anuncios relevantes en plataformas de terceros como Google Ads.</span>
            </li>
            <li class="flex items-start gap-3">
              <span class="mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-full bg-emerald-500/20 text-xs text-emerald-400">✓</span>
              <span><strong class="text-[var(--c-text)]">Obligaciones legales:</strong> cumplir con requerimientos legales, fiscales o regulatorios aplicables.</span>
            </li>
          </ul>
        </div>

        <!-- Base legal -->
        <div>
          <h2 class="text-xl font-semibold text-[var(--c-text)] mb-4">5. Base Legal para el Tratamiento</h2>
          <p class="text-[var(--c-muted)] leading-relaxed mb-4">
            El tratamiento de tus datos personales se realiza bajo las siguientes bases legales:
          </p>
          <ul class="text-[var(--c-muted)] space-y-2 list-disc list-inside">
            <li><strong class="text-[var(--c-text)]">Consentimiento:</strong> cuando nos proporcionas tus datos a través de nuestros formularios</li>
            <li><strong class="text-[var(--c-text)]">Ejecución contractual:</strong> cuando el tratamiento es necesario para cumplir con un contrato de servicios</li>
            <li><strong class="text-[var(--c-text)]">Interés legítimo:</strong> para mejorar nuestros servicios y comunicarnos contigo</li>
            <li><strong class="text-[var(--c-text)]">Obligación legal:</strong> para cumplir con obligaciones fiscales y regulatorias</li>
          </ul>
        </div>

        <!-- Compartir datos -->
        <div>
          <h2 class="text-xl font-semibold text-[var(--c-text)] mb-4">6. Compartición de Datos con Terceros</h2>
          <p class="text-[var(--c-muted)] leading-relaxed mb-4">
            No vendemos ni alquilamos tu información personal. Podemos compartir datos con:
          </p>
          <ul class="text-[var(--c-muted)] space-y-2 list-disc list-inside">
            <li><strong class="text-[var(--c-text)]">Proveedores de servicios:</strong> empresas que nos ayudan a operar el sitio web (hosting, email, análisis)</li>
            <li><strong class="text-[var(--c-text)]">Plataformas publicitarias:</strong> Google Ads para medir conversiones y mostrar anuncios relevantes</li>
            <li><strong class="text-[var(--c-text)]">Autoridades competentes:</strong> cuando sea requerido por ley o para proteger nuestros derechos legales</li>
          </ul>
          <p class="text-[var(--c-muted)] leading-relaxed mt-4">
            Todos nuestros proveedores están obligados a proteger tu información de acuerdo con estándares de seguridad adecuados.
          </p>
        </div>

        <!-- Retención de datos -->
        <div>
          <h2 class="text-xl font-semibold text-[var(--c-text)] mb-4">7. Conservación de Datos</h2>
          <p class="text-[var(--c-muted)] leading-relaxed">
            Conservamos tu información personal solo durante el tiempo necesario para cumplir con los fines descritos en esta política, a menos que la ley requiera o permita un período de retención más largo. Los criterios utilizados para determinar los períodos de retención incluyen:
          </p>
          <ul class="text-[var(--c-muted)] space-y-2 list-disc list-inside mt-4">
            <li>Duración de la relación comercial</li>
            <li>Obligaciones legales de conservación de datos</li>
            <li>Períodos de prescripción para posibles reclamaciones</li>
          </ul>
        </div>

        <!-- Derechos del usuario -->
        <div>
          <h2 class="text-xl font-semibold text-[var(--c-text)] mb-4">8. Tus Derechos</h2>
          <p class="text-[var(--c-muted)] leading-relaxed mb-4">
            De acuerdo con la legislación aplicable, tienes los siguientes derechos sobre tus datos personales:
          </p>
          <div class="grid gap-3 sm:grid-cols-2">
            <div class="bg-[var(--c-elev)] rounded-2xl p-4 ring-1 ring-[var(--c-border)]">
              <p class="text-sm font-semibold text-[var(--c-text)]">Acceso</p>
              <p class="mt-1 text-xs text-[var(--c-muted)]">Conocer qué datos tenemos sobre ti</p>
            </div>
            <div class="bg-[var(--c-elev)] rounded-2xl p-4 ring-1 ring-[var(--c-border)]">
              <p class="text-sm font-semibold text-[var(--c-text)]">Rectificación</p>
              <p class="mt-1 text-xs text-[var(--c-muted)]">Corregir datos inexactos o incompletos</p>
            </div>
            <div class="bg-[var(--c-elev)] rounded-2xl p-4 ring-1 ring-[var(--c-border)]">
              <p class="text-sm font-semibold text-[var(--c-text)]">Supresión</p>
              <p class="mt-1 text-xs text-[var(--c-muted)]">Solicitar la eliminación de tus datos</p>
            </div>
            <div class="bg-[var(--c-elev)] rounded-2xl p-4 ring-1 ring-[var(--c-border)]">
              <p class="text-sm font-semibold text-[var(--c-text)]">Oposición</p>
              <p class="mt-1 text-xs text-[var(--c-muted)]">Oponerte al tratamiento de tus datos</p>
            </div>
            <div class="bg-[var(--c-elev)] rounded-2xl p-4 ring-1 ring-[var(--c-border)]">
              <p class="text-sm font-semibold text-[var(--c-text)]">Portabilidad</p>
              <p class="mt-1 text-xs text-[var(--c-muted)]">Recibir tus datos en formato estructurado</p>
            </div>
            <div class="bg-[var(--c-elev)] rounded-2xl p-4 ring-1 ring-[var(--c-border)]">
              <p class="text-sm font-semibold text-[var(--c-text)]">Limitación</p>
              <p class="mt-1 text-xs text-[var(--c-muted)]">Limitar el tratamiento en ciertos casos</p>
            </div>
          </div>
          <p class="text-[var(--c-muted)] leading-relaxed mt-4">
            Para ejercer cualquiera de estos derechos, puedes contactarnos a través de <a href="mailto:hola@systemsgg.com" class="text-[var(--c-primary)] hover:underline">hola@systemsgg.com</a>.
          </p>
        </div>

        <!-- Seguridad -->
        <div>
          <h2 class="text-xl font-semibold text-[var(--c-text)] mb-4">9. Seguridad de los Datos</h2>
          <p class="text-[var(--c-muted)] leading-relaxed">
            Implementamos medidas técnicas y organizativas apropiadas para proteger tu información personal contra acceso no autorizado, pérdida, alteración o divulgación. Estas medidas incluyen:
          </p>
          <ul class="text-[var(--c-muted)] space-y-2 list-disc list-inside mt-4">
            <li>Cifrado SSL/TLS en todas las comunicaciones</li>
            <li>Acceso restringido a datos personales solo al personal autorizado</li>
            <li>Copias de seguridad periódicas</li>
            <li>Monitoreo continuo de sistemas</li>
          </ul>
        </div>

        <!-- Menores -->
        <div>
          <h2 class="text-xl font-semibold text-[var(--c-text)] mb-4">10. Menores de Edad</h2>
          <p class="text-[var(--c-muted)] leading-relaxed">
            Nuestros servicios no están dirigidos a menores de 18 años. No recopilamos intencionalmente información personal de menores. Si eres padre o tutor y crees que tu hijo nos ha proporcionado información personal, contáctanos para que podamos eliminarla.
          </p>
        </div>

        <!-- Cambios -->
        <div>
          <h2 class="text-xl font-semibold text-[var(--c-text)] mb-4">11. Cambios en esta Política</h2>
          <p class="text-[var(--c-muted)] leading-relaxed">
            Podemos actualizar esta Política de Privacidad periódicamente. Publicaremos cualquier cambio en esta página con una nueva fecha de actualización. Te recomendamos revisar esta política regularmente para mantenerte informado sobre cómo protegemos tu información.
          </p>
        </div>

        <!-- Contacto -->
        <div>
          <h2 class="text-xl font-semibold text-[var(--c-text)] mb-4">12. Contacto</h2>
          <p class="text-[var(--c-muted)] leading-relaxed mb-4">
            Si tienes preguntas, comentarios o solicitudes relacionadas con esta Política de Privacidad o el tratamiento de tus datos personales, puedes contactarnos:
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
      <a href="{{ route('terminos') }}" class="hover:text-[var(--c-text)] transition">Términos y Condiciones</a>
      <span>•</span>
      <a href="{{ route('cookies') }}" class="hover:text-[var(--c-text)] transition">Política de Cookies</a>
    </div>
  </div>
</section>
@endsection
