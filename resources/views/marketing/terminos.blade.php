@extends('layouts.marketing')

@section('title', 'Términos y Condiciones - SystemsGG')
@section('og_title', 'Términos y Condiciones - SystemsGG')
@section('canonical', url('/terminos'))
@section('meta_description', 'Términos y condiciones de uso de SystemsGG. Conoce las condiciones que rigen el uso de nuestro sitio web y servicios de desarrollo de software.')
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
      <h1 class="text-3xl font-semibold tracking-tight sm:text-4xl">Términos y Condiciones</h1>
      <p class="mt-3 text-sm text-[var(--c-muted)]">Última actualización: {{ now()->format('d/m/Y') }}</p>
    </div>

    <!-- Content -->
    <div class="prose prose-invert prose-sm max-w-none">
      <div class="rounded-3xl bg-[var(--c-surface)] p-6 sm:p-8 ring-1 ring-[var(--c-border)] space-y-8">
        
        <!-- Introducción -->
        <div>
          <h2 class="text-xl font-semibold text-[var(--c-text)] mb-4">1. Aceptación de los Términos</h2>
          <p class="text-[var(--c-muted)] leading-relaxed">
            Bienvenido a <strong class="text-[var(--c-text)]">SystemsGG</strong>, operado por <strong class="text-[var(--c-text)]">SISTEMSGG SAC</strong>. Al acceder y utilizar nuestro sitio web (systemsgg.com) y/o contratar nuestros servicios, aceptas cumplir y estar sujeto a estos Términos y Condiciones de uso.
          </p>
          <p class="text-[var(--c-muted)] leading-relaxed mt-3">
            Si no estás de acuerdo con alguno de estos términos, te pedimos que no utilices nuestro sitio web ni contrates nuestros servicios. Te recomendamos leer estos términos detenidamente antes de proceder.
          </p>
        </div>

        <!-- Sobre nosotros -->
        <div>
          <h2 class="text-xl font-semibold text-[var(--c-text)] mb-4">2. Información de la Empresa</h2>
          <div class="bg-[var(--c-elev)] rounded-2xl p-4 ring-1 ring-[var(--c-border)]">
            <ul class="text-[var(--c-muted)] space-y-2">
              <li><strong class="text-[var(--c-text)]">Razón social:</strong> SISTEMSGG SAC</li>
              <li><strong class="text-[var(--c-text)]">Sitio web:</strong> systemsgg.com</li>
              <li><strong class="text-[var(--c-text)]">Correo de contacto:</strong> hola@systemsgg.com</li>
              <li><strong class="text-[var(--c-text)]">Ubicación:</strong> Lima, Perú</li>
            </ul>
          </div>
        </div>

        <!-- Servicios -->
        <div>
          <h2 class="text-xl font-semibold text-[var(--c-text)] mb-4">3. Descripción de Servicios</h2>
          <p class="text-[var(--c-muted)] leading-relaxed mb-4">
            SystemsGG ofrece los siguientes servicios de tecnología:
          </p>
          <ul class="text-[var(--c-muted)] space-y-2 list-disc list-inside">
            <li>Desarrollo de páginas web profesionales y corporativas</li>
            <li>Desarrollo de software a medida (CRM, ERP, sistemas de gestión)</li>
            <li>Desarrollo de landing pages y sitios de conversión</li>
            <li>Integraciones con APIs y servicios de terceros</li>
            <li>Mantenimiento y soporte técnico</li>
            <li>Consultoría tecnológica</li>
          </ul>
          <p class="text-[var(--c-muted)] leading-relaxed mt-4">
            Los servicios específicos, alcance, plazos y precios se detallarán en una propuesta o cotización formal antes de iniciar cualquier proyecto.
          </p>
        </div>

        <!-- Uso del sitio web -->
        <div>
          <h2 class="text-xl font-semibold text-[var(--c-text)] mb-4">4. Uso del Sitio Web</h2>
          <p class="text-[var(--c-muted)] leading-relaxed mb-4">
            Al utilizar nuestro sitio web, te comprometes a:
          </p>
          <ul class="text-[var(--c-muted)] space-y-3">
            <li class="flex items-start gap-3">
              <span class="mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-full bg-emerald-500/20 text-xs text-emerald-400">✓</span>
              <span>Proporcionar información veraz y actualizada en los formularios de contacto</span>
            </li>
            <li class="flex items-start gap-3">
              <span class="mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-full bg-emerald-500/20 text-xs text-emerald-400">✓</span>
              <span>No utilizar el sitio para fines ilegales o no autorizados</span>
            </li>
            <li class="flex items-start gap-3">
              <span class="mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-full bg-emerald-500/20 text-xs text-emerald-400">✓</span>
              <span>No intentar acceder a áreas restringidas del sitio sin autorización</span>
            </li>
            <li class="flex items-start gap-3">
              <span class="mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-full bg-emerald-500/20 text-xs text-emerald-400">✓</span>
              <span>No transmitir virus, malware o código malicioso</span>
            </li>
            <li class="flex items-start gap-3">
              <span class="mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-full bg-emerald-500/20 text-xs text-emerald-400">✓</span>
              <span>Respetar los derechos de propiedad intelectual de SystemsGG</span>
            </li>
          </ul>
        </div>

        <!-- Contratación de servicios -->
        <div>
          <h2 class="text-xl font-semibold text-[var(--c-text)] mb-4">5. Contratación de Servicios</h2>
          
          <h3 class="text-base font-semibold text-[var(--c-text)] mt-6 mb-3">5.1 Proceso de contratación</h3>
          <p class="text-[var(--c-muted)] leading-relaxed">
            El proceso de contratación de nuestros servicios sigue estos pasos:
          </p>
          <ol class="text-[var(--c-muted)] space-y-2 list-decimal list-inside mt-3">
            <li>Solicitud de información o cotización a través de nuestro formulario de contacto</li>
            <li>Reunión de levantamiento de requerimientos (virtual o presencial)</li>
            <li>Envío de propuesta formal con alcance, plazos y precios</li>
            <li>Aceptación de la propuesta por escrito (email o firma de contrato)</li>
            <li>Pago del adelanto acordado para iniciar el proyecto</li>
          </ol>

          <h3 class="text-base font-semibold text-[var(--c-text)] mt-6 mb-3">5.2 Propuestas y cotizaciones</h3>
          <p class="text-[var(--c-muted)] leading-relaxed">
            Las cotizaciones tienen una validez de <strong class="text-[var(--c-text)]">30 días</strong> desde su emisión, salvo que se indique lo contrario. Los precios no incluyen IGV a menos que se especifique expresamente. El alcance del proyecto está limitado a lo descrito en la propuesta aceptada.
          </p>

          <h3 class="text-base font-semibold text-[var(--c-text)] mt-6 mb-3">5.3 Pagos</h3>
          <p class="text-[var(--c-muted)] leading-relaxed">
            Los términos de pago se especifican en cada propuesta. Generalmente trabajamos con:
          </p>
          <ul class="text-[var(--c-muted)] space-y-2 list-disc list-inside mt-3">
            <li>Adelanto del 50% para iniciar el proyecto</li>
            <li>Pago del 50% restante antes de la entrega final</li>
            <li>Para proyectos extensos, se pueden acordar pagos por hitos</li>
          </ul>
        </div>

        <!-- Propiedad intelectual -->
        <div>
          <h2 class="text-xl font-semibold text-[var(--c-text)] mb-4">6. Propiedad Intelectual</h2>
          
          <h3 class="text-base font-semibold text-[var(--c-text)] mt-6 mb-3">6.1 Contenido del sitio web</h3>
          <p class="text-[var(--c-muted)] leading-relaxed">
            Todo el contenido del sitio web de SystemsGG, incluyendo textos, gráficos, logotipos, iconos, imágenes, clips de audio, descargas digitales y software, es propiedad de SISTEMSGG SAC o de sus proveedores de contenido y está protegido por las leyes de propiedad intelectual.
          </p>

          <h3 class="text-base font-semibold text-[var(--c-text)] mt-6 mb-3">6.2 Trabajos desarrollados</h3>
          <p class="text-[var(--c-muted)] leading-relaxed">
            Una vez completado el pago total del proyecto:
          </p>
          <ul class="text-[var(--c-muted)] space-y-2 list-disc list-inside mt-3">
            <li>El cliente recibe la propiedad del código fuente desarrollado específicamente para su proyecto</li>
            <li>SystemsGG mantiene el derecho de utilizar componentes genéricos y librerías propias en otros proyectos</li>
            <li>El cliente puede utilizar el software sin restricciones para su negocio</li>
            <li>SystemsGG puede incluir el proyecto en su portafolio (salvo acuerdo de confidencialidad)</li>
          </ul>
        </div>

        <!-- Garantías y responsabilidades -->
        <div>
          <h2 class="text-xl font-semibold text-[var(--c-text)] mb-4">7. Garantías y Responsabilidades</h2>
          
          <h3 class="text-base font-semibold text-[var(--c-text)] mt-6 mb-3">7.1 Garantía de los trabajos</h3>
          <p class="text-[var(--c-muted)] leading-relaxed">
            SystemsGG ofrece un período de garantía de <strong class="text-[var(--c-text)]">3 meses</strong> después de la entrega final del proyecto, que cubre:
          </p>
          <ul class="text-[var(--c-muted)] space-y-2 list-disc list-inside mt-3">
            <li>Corrección de errores de programación (bugs) en el código entregado</li>
            <li>Ajustes menores que fueron acordados en el alcance original</li>
          </ul>
          <p class="text-[var(--c-muted)] leading-relaxed mt-3">
            La garantía no cubre nuevas funcionalidades, cambios de alcance, problemas causados por modificaciones del cliente o terceros, ni fallas en servicios externos.
          </p>

          <h3 class="text-base font-semibold text-[var(--c-text)] mt-6 mb-3">7.2 Limitación de responsabilidad</h3>
          <p class="text-[var(--c-muted)] leading-relaxed">
            SystemsGG no será responsable por daños indirectos, incidentales, especiales o consecuentes que resulten del uso o la incapacidad de usar nuestros servicios. Nuestra responsabilidad máxima se limita al monto pagado por el cliente por los servicios contratados.
          </p>
        </div>

        <!-- Confidencialidad -->
        <div>
          <h2 class="text-xl font-semibold text-[var(--c-text)] mb-4">8. Confidencialidad</h2>
          <p class="text-[var(--c-muted)] leading-relaxed">
            SystemsGG se compromete a mantener la confidencialidad de toda la información del cliente que sea marcada como confidencial o que razonablemente deba considerarse como tal. Esta obligación incluye:
          </p>
          <ul class="text-[var(--c-muted)] space-y-2 list-disc list-inside mt-3">
            <li>Información de negocio y estratégica del cliente</li>
            <li>Datos de clientes o usuarios del cliente</li>
            <li>Procesos y metodologías propias del cliente</li>
            <li>Información financiera</li>
          </ul>
          <p class="text-[var(--c-muted)] leading-relaxed mt-3">
            Si se requiere un acuerdo de confidencialidad (NDA) formal, este puede ser firmado antes de iniciar las conversaciones del proyecto.
          </p>
        </div>

        <!-- Cancelaciones -->
        <div>
          <h2 class="text-xl font-semibold text-[var(--c-text)] mb-4">9. Cancelaciones y Reembolsos</h2>
          <p class="text-[var(--c-muted)] leading-relaxed mb-4">
            En caso de cancelación del proyecto:
          </p>
          <div class="bg-[var(--c-elev)] rounded-2xl p-4 ring-1 ring-[var(--c-border)]">
            <ul class="text-[var(--c-muted)] space-y-3">
              <li><strong class="text-[var(--c-text)]">Antes de iniciar:</strong> Se reembolsa el 80% del adelanto</li>
              <li><strong class="text-[var(--c-text)]">Durante la ejecución:</strong> Se cobra proporcionalmente al avance realizado</li>
              <li><strong class="text-[var(--c-text)]">Por causa de SystemsGG:</strong> Se reembolsa el 100% de lo pagado menos el valor del trabajo entregado</li>
            </ul>
          </div>
          <p class="text-[var(--c-muted)] leading-relaxed mt-4">
            El cliente recibirá todo el trabajo desarrollado hasta la fecha de cancelación tras el pago correspondiente.
          </p>
        </div>

        <!-- Comunicaciones comerciales -->
        <div>
          <h2 class="text-xl font-semibold text-[var(--c-text)] mb-4">10. Comunicaciones Comerciales</h2>
          <p class="text-[var(--c-muted)] leading-relaxed">
            Al enviar tus datos a través de nuestro formulario de contacto, nos autorizas a:
          </p>
          <ul class="text-[var(--c-muted)] space-y-2 list-disc list-inside mt-3">
            <li>Contactarte para dar seguimiento a tu solicitud</li>
            <li>Enviarte la propuesta o cotización solicitada</li>
            <li>Comunicarnos durante el desarrollo del proyecto (si se contrata)</li>
          </ul>
          <p class="text-[var(--c-muted)] leading-relaxed mt-3">
            No enviamos emails promocionales masivos sin tu consentimiento expreso. Puedes solicitar la eliminación de tus datos en cualquier momento escribiendo a hola@systemsgg.com.
          </p>
        </div>

        <!-- Modificaciones -->
        <div>
          <h2 class="text-xl font-semibold text-[var(--c-text)] mb-4">11. Modificaciones de los Términos</h2>
          <p class="text-[var(--c-muted)] leading-relaxed">
            SystemsGG se reserva el derecho de modificar estos Términos y Condiciones en cualquier momento. Las modificaciones entrarán en vigor inmediatamente después de su publicación en el sitio web. El uso continuado del sitio web después de la publicación de cambios constituye la aceptación de dichos cambios.
          </p>
        </div>

        <!-- Ley aplicable -->
        <div>
          <h2 class="text-xl font-semibold text-[var(--c-text)] mb-4">12. Ley Aplicable y Jurisdicción</h2>
          <p class="text-[var(--c-muted)] leading-relaxed">
            Estos Términos y Condiciones se rigen por las leyes de la República del Perú. Cualquier controversia derivada de estos términos o de la prestación de nuestros servicios se someterá a la jurisdicción de los tribunales de Lima, Perú, renunciando las partes a cualquier otro fuero que pudiera corresponderles.
          </p>
        </div>

        <!-- Divisibilidad -->
        <div>
          <h2 class="text-xl font-semibold text-[var(--c-text)] mb-4">13. Divisibilidad</h2>
          <p class="text-[var(--c-muted)] leading-relaxed">
            Si alguna disposición de estos Términos y Condiciones se considera inválida o inaplicable, dicha disposición se modificará e interpretará para lograr los objetivos de dicha disposición en la mayor medida posible según la ley aplicable, y las disposiciones restantes continuarán en pleno vigor y efecto.
          </p>
        </div>

        <!-- Contacto -->
        <div>
          <h2 class="text-xl font-semibold text-[var(--c-text)] mb-4">14. Contacto</h2>
          <p class="text-[var(--c-muted)] leading-relaxed mb-4">
            Si tienes preguntas sobre estos Términos y Condiciones, puedes contactarnos:
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
      <a href="{{ route('cookies') }}" class="hover:text-[var(--c-text)] transition">Política de Cookies</a>
    </div>
  </div>
</section>
@endsection
