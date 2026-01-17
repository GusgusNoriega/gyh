@extends('layouts.marketing')

@section('title', 'G&H Ingeniería y Construcción | Servicios Generales - Perú')
@section('og_title', 'G&H Ingeniería y Construcción | Servicios Generales - Perú')
@section('canonical', url('/'))
@section('meta_description', 'G&H Ingeniería y Construcción - CREATIVE GUILLEN G & H EJECUTOR S.A.C. (RUC 20604851620). Servicios generales, mantenimiento, ejecución y construcción en Perú.')

{{-- Datos de marca / schema para este sitio (sobrescribe defaults del layout) --}}
@section('site_name', 'G&H Ingeniería y Construcción')
@section('business_name', 'CREATIVE GUILLEN G & H EJECUTOR S.A.C.')
@section('business_telephone', '+51 000 000 000')
@section('business_city', 'Perú')
@section('business_country', 'PE')
@section('service_1', 'Servicios generales')
@section('service_2', 'Ingeniería y construcción')
@section('knows_about_1', 'servicios generales en Perú')
@section('knows_about_2', 'mantenimiento y ejecución de obras')
@section('knows_about_3', 'suministros y logística')
@section('knows_about_4', 'ingeniería y construcción')

@section('wa_phone_display', '+51 000 000 000')

@section('content')
  <!-- Top bar -->
  <div class="w-full bg-coal-900 text-white">
    <div class="mx-auto max-w-7xl px-4 py-2 flex flex-col sm:flex-row gap-2 sm:items-center sm:justify-between text-sm">
      <div class="flex items-center gap-2">
        <span class="inline-flex h-2 w-2 rounded-full bg-emerald-400"></span>
        <span>Atención en Perú • Cotizaciones rápidas</span>
      </div>
      <div class="flex flex-wrap items-center gap-x-4 gap-y-1">
        <a class="hover:underline" href="#servicios">Servicios</a>
        <a class="hover:underline" href="#galeria">Galería</a>
        <a class="hover:underline" href="#contacto">Contacto</a>
      </div>
    </div>
  </div>

  <!-- Header -->
  <header class="sticky top-0 z-40 bg-white/85 dark:bg-coal-900/75 backdrop-blur border-b border-coal-200/70 dark:border-white/10">
    <div class="mx-auto max-w-7xl px-4 py-4 flex items-center justify-between gap-4">
      <a href="#" class="flex items-center gap-3">
        <img
          class="h-11 w-11 rounded-2xl bg-white ring-soft object-contain p-1"
          alt="Logo G&H"
          src="{{ asset('img/logo2.png') }}"
        />
        <div class="leading-tight">
          <p class="font-semibold">G&amp;H Ingeniería y Construcción</p>
          <p class="text-xs text-coal-500 dark:text-coal-300">CREATIVE GUILLEN G &amp; H EJECUTOR S.A.C. • RUC: 20604851620</p>
        </div>
      </a>

      <nav class="hidden md:flex items-center gap-6 text-sm">
        <a class="hover:text-brand-700" href="#servicios">Servicios</a>
        <a class="hover:text-brand-700" href="#proceso">Proceso</a>
        <a class="hover:text-brand-700" href="#galeria">Galería</a>
        <a class="hover:text-brand-700" href="#contacto">Contacto</a>
      </nav>

      <div class="flex items-center gap-2">
        <button id="themeBtn" class="hidden sm:inline-flex items-center gap-2 px-3 py-2 rounded-xl border border-coal-200 dark:border-white/10 hover:bg-coal-50 dark:hover:bg-white/5 text-sm" type="button">
          <span id="themeIcon" aria-hidden="true">🌙</span>
          <span class="hidden lg:inline">Modo</span>
        </button>

        <a href="#contacto" class="hidden sm:inline-flex items-center justify-center px-4 py-2 rounded-xl bg-brand-600 text-white hover:bg-brand-700 shadow-soft text-sm">
          Cotizar ahora
        </a>

        <a href="{{ route('login') }}" class="hidden sm:inline-flex items-center justify-center px-4 py-2 rounded-xl border border-coal-200 dark:border-white/10 hover:bg-coal-50 dark:hover:bg-white/5 text-sm">
          Ingresar
        </a>

        <button id="menuBtn" class="md:hidden inline-flex items-center justify-center h-10 w-10 rounded-xl border border-coal-200 dark:border-white/10 hover:bg-coal-50 dark:hover:bg-white/5" aria-label="Abrir menú" type="button">
          ☰
        </button>
      </div>
    </div>

    <!-- Mobile menu -->
    <div id="mobileMenu" class="md:hidden hidden border-t border-coal-200/80 dark:border-white/10 bg-white dark:bg-coal-900">
      <div class="mx-auto max-w-7xl px-4 py-4 flex flex-col gap-2 text-sm">
        <a class="py-2" href="#servicios">Servicios</a>
        <a class="py-2" href="#proceso">Proceso</a>
        <a class="py-2" href="#galeria">Galería</a>
        <a class="py-2" href="#contacto">Contacto</a>
        <a class="py-2" href="{{ route('login') }}">Ingresar</a>
        <a class="mt-2 inline-flex items-center justify-center px-4 py-2 rounded-xl bg-brand-600 text-white hover:bg-brand-700 shadow-soft" href="#contacto">
          Cotizar ahora
        </a>
      </div>
    </div>
  </header>

  <!-- HERO -->
  <section class="hero-bg">
    <div class="blob b1"></div>
    <div class="blob b2"></div>
    <div class="blob b3"></div>

    <div class="mx-auto max-w-7xl px-4 py-14 sm:py-20 grid lg:grid-cols-2 gap-10 items-center relative">
      <div class="reveal">
        <p class="inline-flex items-center gap-2 text-xs font-semibold px-3 py-1 rounded-full bg-brand-100 text-brand-900 ring-soft dark:bg-white/10 dark:text-white">
          <span class="h-2 w-2 rounded-full bg-brand-600"></span>
          Servicios generales • Ingeniería y construcción • Perú
        </p>

        <h1 class="mt-4 text-3xl sm:text-5xl font-semibold tracking-tight">
          Construimos, mantenemos y resolvemos con rapidez y calidad
        </h1>

        <p class="mt-4 text-coal-600 dark:text-coal-200 text-base sm:text-lg max-w-xl">
          En <span class="font-semibold">CREATIVE GUILLEN G &amp; H EJECUTOR S.A.C.</span> (RUC 20604851620)
          brindamos <span class="font-semibold">servicios generales</span> para hogares y empresas: mantenimiento,
          ejecución, mejoras, soporte y suministros.
        </p>

        <div class="mt-7 flex flex-col sm:flex-row gap-3">
          <a href="#contacto" class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-brand-600 text-white hover:bg-brand-700 shadow-soft">
            Solicitar cotización
          </a>
          <a href="#servicios" class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-white/80 dark:bg-white/10 border border-coal-200 dark:border-white/10 hover:bg-white dark:hover:bg-white/15">
            Ver servicios
          </a>
        </div>

        <div class="mt-9 grid grid-cols-3 gap-3 max-w-xl">
          <div class="p-4 rounded-2xl bg-white/80 dark:bg-white/10 border border-coal-200 dark:border-white/10 ring-soft">
            <p class="text-xs text-coal-500 dark:text-coal-200">Respuesta</p>
            <p class="text-lg font-semibold"><span class="count" data-to="24">0</span>h</p>
          </div>
          <div class="p-4 rounded-2xl bg-white/80 dark:bg-white/10 border border-coal-200 dark:border-white/10 ring-soft">
            <p class="text-xs text-coal-500 dark:text-coal-200">Servicios</p>
            <p class="text-lg font-semibold"><span class="count" data-to="12">0</span>+</p>
          </div>
          <div class="p-4 rounded-2xl bg-white/80 dark:bg-white/10 border border-coal-200 dark:border-white/10 ring-soft">
            <p class="text-xs text-coal-500 dark:text-coal-200">Cobertura</p>
            <p class="text-lg font-semibold">Perú</p>
          </div>
        </div>
      </div>

      <div class="reveal">
        <div class="glass dark:bg-white/10 shadow-soft rounded-3xl p-6 sm:p-8">
          <div class="flex items-start justify-between gap-4">
            <div>
              <p class="text-sm font-semibold">Servicios con estándar</p>
              <p class="text-sm text-coal-600 dark:text-coal-200">Orden • Seguridad • Entrega</p>
            </div>
            <span class="text-xs px-3 py-1 rounded-full bg-amber-100 text-amber-900 ring-soft dark:bg-amber-400/15 dark:text-amber-200">Prioridad</span>
          </div>

          <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="tilt p-4 rounded-2xl bg-white dark:bg-coal-900 border border-coal-200 dark:border-white/10">
              <div class="svcImg h-28 rounded-xl bg-coal-50 dark:bg-white/5 border border-coal-200/60 dark:border-white/10 overflow-hidden"></div>
              <p class="mt-3 text-sm font-semibold">Mantenimiento</p>
              <p class="text-xs text-coal-600 dark:text-coal-200 mt-1">Preventivo y correctivo.</p>
            </div>
            <div class="tilt p-4 rounded-2xl bg-white dark:bg-coal-900 border border-coal-200 dark:border-white/10">
              <div class="svcImg h-28 rounded-xl bg-coal-50 dark:bg-white/5 border border-coal-200/60 dark:border-white/10 overflow-hidden"></div>
              <p class="mt-3 text-sm font-semibold">Ejecución</p>
              <p class="text-xs text-coal-600 dark:text-coal-200 mt-1">Trabajos y mejoras.</p>
            </div>
            <div class="tilt p-4 rounded-2xl bg-white dark:bg-coal-900 border border-coal-200 dark:border-white/10">
              <div class="svcImg h-28 rounded-xl bg-coal-50 dark:bg-white/5 border border-coal-200/60 dark:border-white/10 overflow-hidden"></div>
              <p class="mt-3 text-sm font-semibold">Suministros</p>
              <p class="text-xs text-coal-600 dark:text-coal-200 mt-1">Logística y abastecimiento.</p>
            </div>
            <div class="tilt p-4 rounded-2xl bg-white dark:bg-coal-900 border border-coal-200 dark:border-white/10">
              <div class="svcImg h-28 rounded-xl bg-coal-50 dark:bg-white/5 border border-coal-200/60 dark:border-white/10 overflow-hidden"></div>
              <p class="mt-3 text-sm font-semibold">Soporte</p>
              <p class="text-xs text-coal-600 dark:text-coal-200 mt-1">Atención y seguimiento.</p>
            </div>
          </div>

          <div class="mt-6 p-4 rounded-2xl bg-coal-900 text-white">
            <p class="text-xs text-white/70">Razón social</p>
            <p class="text-sm font-semibold">CREATIVE GUILLEN G &amp; H EJECUTOR S.A.C.</p>
            <p class="text-xs text-white/70 mt-1">RUC: <span class="text-white font-semibold">20604851620</span></p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA band #2 -->
  <section class="py-10">
    <div class="mx-auto max-w-7xl px-4">
      <div class="reveal rounded-3xl p-6 sm:p-8 bg-gradient-to-r from-brand-600 via-coal-900 to-amber-500 text-white shadow-soft">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
          <div>
            <h2 class="text-xl sm:text-2xl font-semibold">¿Necesitas atención rápida?</h2>
            <p class="mt-1 text-white/80">Envíanos tu requerimiento y te respondemos con una propuesta clara.</p>
          </div>
          <div class="flex flex-col sm:flex-row gap-3">
            <a href="#contacto" class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-white text-coal-900 hover:bg-white/90 font-semibold">
              Cotizar ahora
            </a>
            <a href="#galeria" class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-white/10 border border-white/20 hover:bg-white/15">
              Ver trabajos
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Servicios -->
  <section id="servicios" class="py-16 sm:py-20">
    <div class="mx-auto max-w-7xl px-4">
      <div class="reveal">
        <h2 class="text-2xl sm:text-3xl font-semibold tracking-tight">Servicios</h2>
        <p class="mt-3 text-coal-600 dark:text-coal-200 max-w-2xl">
          Servicios generales orientados a hogares y empresas. Puedes reemplazar los textos por tu catálogo exacto.
        </p>
      </div>

      <div class="mt-10 grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
        <article class="reveal tilt p-6 rounded-3xl border border-coal-200 dark:border-white/10 bg-white dark:bg-coal-900 shadow-sm">
          <div class="serviceImage h-36 rounded-2xl overflow-hidden border border-coal-200/60 dark:border-white/10 bg-coal-50 dark:bg-white/5"></div>
          <h3 class="mt-4 font-semibold">Mantenimiento integral</h3>
          <p class="mt-2 text-sm text-coal-600 dark:text-coal-200">Preventivo/correctivo para instalaciones y espacios.</p>
          <div class="mt-4 flex flex-wrap gap-2 text-xs">
            <span class="px-3 py-1 rounded-full bg-brand-100 text-brand-900 dark:bg-white/10 dark:text-white">Diagnóstico</span>
            <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-900 dark:bg-amber-400/15 dark:text-amber-200">Urgencias</span>
          </div>
        </article>

        <article class="reveal tilt p-6 rounded-3xl border border-coal-200 dark:border-white/10 bg-white dark:bg-coal-900 shadow-sm">
          <div class="serviceImage h-36 rounded-2xl overflow-hidden border border-coal-200/60 dark:border-white/10 bg-coal-50 dark:bg-white/5"></div>
          <h3 class="mt-4 font-semibold">Ejecución y mejoras</h3>
          <p class="mt-2 text-sm text-coal-600 dark:text-coal-200">Trabajos generales con control y entrega.</p>
          <div class="mt-4 flex flex-wrap gap-2 text-xs">
            <span class="px-3 py-1 rounded-full bg-brand-100 text-brand-900 dark:bg-white/10 dark:text-white">Calidad</span>
            <span class="px-3 py-1 rounded-full bg-coal-100 text-coal-700 dark:bg-white/10 dark:text-white">Orden</span>
          </div>
        </article>

        <article class="reveal tilt p-6 rounded-3xl border border-coal-200 dark:border-white/10 bg-white dark:bg-coal-900 shadow-sm">
          <div class="serviceImage h-36 rounded-2xl overflow-hidden border border-coal-200/60 dark:border-white/10 bg-coal-50 dark:bg-white/5"></div>
          <h3 class="mt-4 font-semibold">Construcción / obras</h3>
          <p class="mt-2 text-sm text-coal-600 dark:text-coal-200">Soporte para proyectos, adecuaciones y obra.</p>
          <div class="mt-4 flex flex-wrap gap-2 text-xs">
            <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-900 dark:bg-amber-400/15 dark:text-amber-200">Seguridad</span>
            <span class="px-3 py-1 rounded-full bg-brand-100 text-brand-900 dark:bg-white/10 dark:text-white">Supervisión</span>
          </div>
        </article>

        <article class="reveal tilt p-6 rounded-3xl border border-coal-200 dark:border-white/10 bg-white dark:bg-coal-900 shadow-sm">
          <div class="serviceImage h-36 rounded-2xl overflow-hidden border border-coal-200/60 dark:border-white/10 bg-coal-50 dark:bg-white/5"></div>
          <h3 class="mt-4 font-semibold">Suministros y logística</h3>
          <p class="mt-2 text-sm text-coal-600 dark:text-coal-200">Compra, abastecimiento y coordinación de entregas.</p>
          <div class="mt-4 flex flex-wrap gap-2 text-xs">
            <span class="px-3 py-1 rounded-full bg-coal-100 text-coal-700 dark:bg-white/10 dark:text-white">Gestión</span>
            <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-900 dark:bg-amber-400/15 dark:text-amber-200">Entrega</span>
          </div>
        </article>

        <article class="reveal tilt p-6 rounded-3xl border border-coal-200 dark:border-white/10 bg-white dark:bg-coal-900 shadow-sm">
          <div class="serviceImage h-36 rounded-2xl overflow-hidden border border-coal-200/60 dark:border-white/10 bg-coal-50 dark:bg-white/5"></div>
          <h3 class="mt-4 font-semibold">Servicios operativos</h3>
          <p class="mt-2 text-sm text-coal-600 dark:text-coal-200">Apoyo a operación: coordinaciones, soporte y control.</p>
          <div class="mt-4 flex flex-wrap gap-2 text-xs">
            <span class="px-3 py-1 rounded-full bg-brand-100 text-brand-900 dark:bg-white/10 dark:text-white">Soporte</span>
            <span class="px-3 py-1 rounded-full bg-coal-100 text-coal-700 dark:bg-white/10 dark:text-white">Seguimiento</span>
          </div>
        </article>

        <article class="reveal tilt p-6 rounded-3xl border border-coal-200 dark:border-white/10 bg-white dark:bg-coal-900 shadow-sm">
          <div class="serviceImage h-36 rounded-2xl overflow-hidden border border-coal-200/60 dark:border-white/10 bg-coal-50 dark:bg-white/5"></div>
          <h3 class="mt-4 font-semibold">Atención personalizada</h3>
          <p class="mt-2 text-sm text-coal-600 dark:text-coal-200">Evaluamos tu caso y proponemos una solución concreta.</p>
          <div class="mt-4 flex flex-wrap gap-2 text-xs">
            <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-900 dark:bg-amber-400/15 dark:text-amber-200">Visita</span>
            <span class="px-3 py-1 rounded-full bg-brand-100 text-brand-900 dark:bg-white/10 dark:text-white">Propuesta</span>
          </div>
        </article>
      </div>
    </div>
  </section>

  <div class="divider mx-auto max-w-7xl"></div>

  <!-- Proceso -->
  <section id="proceso" class="py-16 sm:py-20 bg-coal-50 dark:bg-white/5">
    <div class="mx-auto max-w-7xl px-4">
      <div class="reveal">
        <h2 class="text-2xl sm:text-3xl font-semibold tracking-tight">Cómo trabajamos</h2>
        <p class="mt-3 text-coal-600 dark:text-coal-200 max-w-2xl">
          Un flujo simple para que tengas claridad: alcance, tiempos y entrega.
        </p>
      </div>

      <div class="mt-10 grid lg:grid-cols-4 gap-4">
        <div class="reveal tilt p-6 rounded-3xl bg-white dark:bg-coal-900 border border-coal-200 dark:border-white/10">
          <p class="text-xs font-semibold text-brand-700 dark:text-brand-300">Paso 01</p>
          <h3 class="mt-2 font-semibold">Evaluación</h3>
          <p class="mt-2 text-sm text-coal-600 dark:text-coal-200">Revisamos tu requerimiento y condiciones.</p>
        </div>
        <div class="reveal tilt p-6 rounded-3xl bg-white dark:bg-coal-900 border border-coal-200 dark:border-white/10">
          <p class="text-xs font-semibold text-amber-700 dark:text-amber-200">Paso 02</p>
          <h3 class="mt-2 font-semibold">Propuesta</h3>
          <p class="mt-2 text-sm text-coal-600 dark:text-coal-200">Enviamos alcance, tiempos y costo estimado.</p>
        </div>
        <div class="reveal tilt p-6 rounded-3xl bg-white dark:bg-coal-900 border border-coal-200 dark:border-white/10">
          <p class="text-xs font-semibold text-sky-700 dark:text-sky-200">Paso 03</p>
          <h3 class="mt-2 font-semibold">Ejecución</h3>
          <p class="mt-2 text-sm text-coal-600 dark:text-coal-200">Realizamos el trabajo con control y orden.</p>
        </div>
        <div class="reveal tilt p-6 rounded-3xl bg-white dark:bg-coal-900 border border-coal-200 dark:border-white/10">
          <p class="text-xs font-semibold text-emerald-700 dark:text-emerald-200">Paso 04</p>
          <h3 class="mt-2 font-semibold">Entrega</h3>
          <p class="mt-2 text-sm text-coal-600 dark:text-coal-200">Cierre y verificación final con el cliente.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Galería -->
  <section id="galeria" class="py-16 sm:py-20">
    <div class="mx-auto max-w-7xl px-4">
      <div class="reveal flex flex-col sm:flex-row sm:items-end sm:justify-between gap-5">
        <div>
          <h2 class="text-2xl sm:text-3xl font-semibold tracking-tight">Galería</h2>
          <p class="mt-3 text-coal-600 dark:text-coal-200 max-w-2xl">
            Imágenes públicas de prueba (placeholders) listas para reemplazar por fotos reales.
          </p>
        </div>
        <a href="#contacto" class="reveal inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-coal-900 text-white hover:bg-coal-800 dark:bg-white dark:text-coal-900 dark:hover:bg-white/90 shadow-soft">
          Quiero una cotización
        </a>
      </div>

      <div class="mt-10 grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="reveal tilt galleryImg h-44 rounded-3xl overflow-hidden border border-coal-200 dark:border-white/10 bg-coal-50 dark:bg-white/5"></div>
        <div class="reveal tilt galleryImg h-44 rounded-3xl overflow-hidden border border-coal-200 dark:border-white/10 bg-coal-50 dark:bg-white/5"></div>
        <div class="reveal tilt galleryImg h-44 rounded-3xl overflow-hidden border border-coal-200 dark:border-white/10 bg-coal-50 dark:bg-white/5"></div>
        <div class="reveal tilt galleryImg h-44 rounded-3xl overflow-hidden border border-coal-200 dark:border-white/10 bg-coal-50 dark:bg-white/5"></div>
        <div class="reveal tilt galleryImg h-44 rounded-3xl overflow-hidden border border-coal-200 dark:border-white/10 bg-coal-50 dark:bg-white/5"></div>
        <div class="reveal tilt galleryImg h-44 rounded-3xl overflow-hidden border border-coal-200 dark:border-white/10 bg-coal-50 dark:bg-white/5"></div>
        <div class="reveal tilt galleryImg h-44 rounded-3xl overflow-hidden border border-coal-200 dark:border-white/10 bg-coal-50 dark:bg-white/5"></div>
        <div class="reveal tilt galleryImg h-44 rounded-3xl overflow-hidden border border-coal-200 dark:border-white/10 bg-coal-50 dark:bg-white/5"></div>
      </div>
    </div>
  </section>

  <!-- CTA band #3 -->
  <section class="py-12 bg-coal-900">
    <div class="mx-auto max-w-7xl px-4">
      <div class="reveal rounded-3xl p-7 sm:p-10 bg-gradient-to-br from-white/10 via-white/5 to-white/10 border border-white/10">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
          <div class="text-white">
            <h2 class="text-xl sm:text-2xl font-semibold">Agenda una evaluación y cotiza hoy</h2>
            <p class="mt-2 text-white/75 max-w-2xl">
              Te ayudamos a resolver mantenimiento, ejecución, mejoras y servicios generales con una propuesta clara.
            </p>
          </div>
          <div class="flex flex-col sm:flex-row gap-3">
            <a href="#contacto" class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-brand-600 text-white hover:bg-brand-700 shadow-soft">
              Solicitar cotización
            </a>
            <a href="#servicios" class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-white/10 text-white border border-white/15 hover:bg-white/15">
              Ver servicios
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Contacto -->
  <section id="contacto" class="py-16 sm:py-20">
    <div class="mx-auto max-w-7xl px-4 grid lg:grid-cols-2 gap-10 items-start">
      <div class="reveal">
        <h2 class="text-2xl sm:text-3xl font-semibold tracking-tight">Contacto</h2>
        <p class="mt-3 text-coal-600 dark:text-coal-200">
          Completa el formulario y generaremos un mensaje listo para enviar por WhatsApp o correo.
        </p>

        <div class="mt-6 p-6 rounded-3xl border border-coal-200 dark:border-white/10 bg-coal-50 dark:bg-white/5">
          <p class="text-sm"><span class="font-semibold">Razón social:</span> CREATIVE GUILLEN G &amp; H EJECUTOR S.A.C.</p>
          <p class="text-sm mt-2"><span class="font-semibold">RUC:</span> 20604851620</p>
          <p class="text-sm mt-2"><span class="font-semibold">País:</span> Perú</p>

          <p class="text-xs text-coal-500 dark:text-coal-300 mt-4">
            Reemplaza aquí: dirección, ciudad y WhatsApp (si deseas).
          </p>
        </div>
      </div>

      <div class="reveal">
        <form id="quoteForm" class="p-6 sm:p-8 rounded-3xl border border-coal-200 dark:border-white/10 shadow-soft bg-white dark:bg-coal-900">
          <h3 class="font-semibold text-lg">Solicitar cotización</h3>
          <p class="text-sm text-coal-600 dark:text-coal-200 mt-1">Campos mínimos para atenderte rápido.</p>

          <div class="mt-6 grid sm:grid-cols-2 gap-4">
            <div>
              <label class="text-sm font-medium" for="name">Nombre</label>
              <input id="name" required class="mt-2 w-full px-4 py-3 rounded-2xl border border-coal-200 dark:border-white/10 bg-white dark:bg-coal-900 focus:outline-none focus:ring-2 focus:ring-brand-300" placeholder="Tu nombre" />
            </div>
            <div>
              <label class="text-sm font-medium" for="phone">Teléfono / WhatsApp</label>
              <input id="phone" class="mt-2 w-full px-4 py-3 rounded-2xl border border-coal-200 dark:border-white/10 bg-white dark:bg-coal-900 focus:outline-none focus:ring-2 focus:ring-brand-300" placeholder="+51 ..." />
            </div>
            <div class="sm:col-span-2">
              <label class="text-sm font-medium" for="service">Servicio requerido</label>
              <select id="service" class="mt-2 w-full px-4 py-3 rounded-2xl border border-coal-200 dark:border-white/10 bg-white dark:bg-coal-900 focus:outline-none focus:ring-2 focus:ring-brand-300">
                <option>Mantenimiento integral</option>
                <option>Ejecución y mejoras</option>
                <option>Construcción / obras</option>
                <option>Suministros y logística</option>
                <option>Servicios operativos</option>
                <option>Atención personalizada</option>
                <option>Otro</option>
              </select>
            </div>
            <div class="sm:col-span-2">
              <label class="text-sm font-medium" for="details">Detalle</label>
              <textarea id="details" rows="4" required class="mt-2 w-full px-4 py-3 rounded-2xl border border-coal-200 dark:border-white/10 bg-white dark:bg-coal-900 focus:outline-none focus:ring-2 focus:ring-brand-300" placeholder="Describe lo que necesitas (ciudad/zona, urgencia, metraje, etc.)"></textarea>
            </div>
          </div>

          <div class="mt-6 flex flex-col sm:flex-row gap-3">
            <button id="submitBtn" type="submit" class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-brand-600 text-white hover:bg-brand-700 shadow-soft">
              Generar mensaje
            </button>
            <button id="copyBtn" type="button" class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-white dark:bg-white/10 border border-coal-200 dark:border-white/10 hover:bg-coal-50 dark:hover:bg-white/15">
              Copiar
            </button>
          </div>

          <div class="mt-6">
            <label class="text-sm font-medium" for="output">Mensaje generado</label>
            <textarea id="output" rows="6" readonly class="mt-2 w-full px-4 py-3 rounded-2xl border border-coal-200 dark:border-white/10 bg-coal-50 dark:bg-white/5 text-sm focus:outline-none"></textarea>
            <p class="mt-2 text-xs text-coal-500 dark:text-coal-300">Listo para pegar en WhatsApp o correo.</p>
          </div>
        </form>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="border-t border-coal-200 dark:border-white/10 py-10">
    <div class="mx-auto max-w-7xl px-4 flex flex-col md:flex-row gap-6 md:items-center md:justify-between">
      <div class="space-y-1">
        <p class="font-semibold">CREATIVE GUILLEN G &amp; H EJECUTOR S.A.C.</p>
        <p class="text-sm text-coal-600 dark:text-coal-200">RUC: 20604851620 • Perú</p>
      </div>
      <div class="text-sm text-coal-600 dark:text-coal-200 flex flex-wrap gap-x-5 gap-y-2">
        <a class="hover:text-brand-700" href="#servicios">Servicios</a>
        <a class="hover:text-brand-700" href="#proceso">Proceso</a>
        <a class="hover:text-brand-700" href="#galeria">Galería</a>
        <a class="hover:text-brand-700" href="#contacto">Contacto</a>
        <a class="hover:text-brand-700" href="{{ route('terminos') }}">Términos</a>
      </div>
      <p class="text-xs text-coal-500 dark:text-coal-300">
        © <span id="year"></span> • Todos los derechos reservados.
      </p>
    </div>
  </footer>

  <!-- Toast -->
  <div id="toast" class="fixed bottom-4 left-1/2 -translate-x-1/2 hidden z-50">
    <div class="toast px-4 py-3 rounded-2xl bg-coal-900 text-white text-sm shadow-soft">
      <span id="toastMsg">Listo</span>
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    // ===== Helpers =====
    const $ = (s) => document.querySelector(s);
    const $$ = (s) => document.querySelectorAll(s);

    // Año
    const yearEl = $("#year");
    if (yearEl) yearEl.textContent = new Date().getFullYear();

    // Menú móvil
    $("#menuBtn")?.addEventListener("click", () => $("#mobileMenu")?.classList.toggle("hidden"));

    // Reveal on scroll
    const io = new IntersectionObserver((entries) => {
      entries.forEach(ent => { if (ent.isIntersecting) ent.target.classList.add("show"); });
    }, { threshold: 0.12 });
    $$(".reveal").forEach(el => io.observe(el));

    // Toast
    const toast = $("#toast");
    const toastMsg = $("#toastMsg");
    let toastTimer = null;
    function showToast(msg) {
      if (!toast || !toastMsg) return;
      toastMsg.textContent = msg;
      toast.classList.remove("hidden");
      clearTimeout(toastTimer);
      toastTimer = setTimeout(() => toast.classList.add("hidden"), 2200);
    }

    // Dark mode (clase en <html>) - Por defecto modo oscuro
    const themeBtn = $("#themeBtn");
    const themeIcon = $("#themeIcon");
    const root = document.documentElement;
    function applyTheme(mode) {
      if (mode === "dark") {
        root.classList.add("dark");
        if (themeIcon) themeIcon.textContent = "☀️";
      } else {
        root.classList.remove("dark");
        if (themeIcon) themeIcon.textContent = "🌙";
      }
      localStorage.setItem("theme", mode);
    }
    // Por defecto modo oscuro si no hay preferencia guardada
    applyTheme(localStorage.getItem("theme") || "dark");
    themeBtn?.addEventListener("click", () => {
      const cur = localStorage.getItem("theme") || "dark";
      applyTheme(cur === "dark" ? "light" : "dark");
      showToast(`Modo ${localStorage.getItem("theme") === "dark" ? "oscuro" : "claro"}`);
    });

    // ===== Formulario: generar + copiar =====
    const form = $("#quoteForm");
    const output = $("#output");
    const copyBtn = $("#copyBtn");
    const submitBtn = $("#submitBtn");

    function buildMessage() {
      const name = $("#name")?.value?.trim() || '';
      const phone = $("#phone")?.value?.trim() || '';
      const service = $("#service")?.value?.trim() || '';
      const details = $("#details")?.value?.trim() || '';

      return (
`Hola, soy ${name}${phone ? " (" + phone + ")" : ""}.
Quisiera una cotización para: ${service}.

Detalle:
${details}

Empresa: CREATIVE GUILLEN G & H EJECUTOR S.A.C.
RUC: 20604851620
País: Perú`
      );
    }

    form?.addEventListener("submit", (e) => {
      e.preventDefault();
      if (submitBtn) {
        submitBtn.classList.add("opacity-75");
        submitBtn.textContent = "Generando...";
      }
      setTimeout(() => {
        if (output) output.value = buildMessage();
        if (submitBtn) {
          submitBtn.classList.remove("opacity-75");
          submitBtn.textContent = "Generar mensaje";
        }
        showToast("Mensaje generado");
      }, 300);
    });

    copyBtn?.addEventListener("click", async () => {
      if (!output || !output.value.trim()) {
        showToast("Primero genera el mensaje");
        return;
      }
      try {
        await navigator.clipboard.writeText(output.value);
        showToast("Copiado");
      } catch {
        output.select();
        document.execCommand("copy");
        showToast("Copiado");
      }
    });

    // ===== Counters (animación) =====
    function animateCount(el, to) {
      const dur = 900;
      const start = performance.now();
      const from = 0;

      function tick(now) {
        const t = Math.min(1, (now - start) / dur);
        const val = Math.floor(from + (to - from) * (t));
        el.textContent = val.toString();
        if (t < 1) requestAnimationFrame(tick);
      }
      requestAnimationFrame(tick);
    }

    const countObserver = new IntersectionObserver((entries) => {
      entries.forEach(ent => {
        if (ent.isIntersecting) {
          const el = ent.target;
          if (el.dataset.done) return;
          el.dataset.done = "1";
          animateCount(el, parseInt(el.dataset.to, 10));
        }
      });
    }, { threshold: 0.4 });

    $$(".count").forEach(el => countObserver.observe(el));

    // ===== Imágenes públicas (URLs) =====
    // Imágenes de servicios (en orden: Mantenimiento integral, Ejecución y mejoras, Construcción/obras, Suministros y logística, Servicios operativos, Atención personalizada)
    const serviceImageURLs = [
      "{{ asset('img/mantenimiento-integral.png') }}",
      "{{ asset('img/ejecucionymejoras.png') }}",
      "{{ asset('img/construccion-y-hobras.png') }}",
      "{{ asset('img/suministro-y-logistica.png') }}",
      "{{ asset('img/servicios-operativos.png') }}",
      "{{ asset('img/atencion-perzonalizada.png') }}",
    ];

    const galleryImageURLs = [
      "{{ asset('img/imagen11.png') }}",
      "{{ asset('img/imagen12.png') }}",
      "{{ asset('img/imagen13.png') }}",
      "{{ asset('img/imagen14.png') }}",
      "{{ asset('img/imagen15.png') }}",
      "{{ asset('img/imagen5.png') }}",
      "{{ asset('img/imagen6.png') }}",
      "{{ asset('img/imagen7.png') }}",
    ];

    // Imágenes del hero mini (en orden: Mantenimiento, Ejecución, Suministros, Soporte)
    const heroMiniURLs = [
      "{{ asset('img/mantenimiento-integral.png') }}",
      "{{ asset('img/ejecucionymejoras.png') }}",
      "{{ asset('img/suministro-y-logistica.png') }}",
      "{{ asset('img/atencion-perzonalizada.png') }}",
    ];

    // Rellena imágenes de servicios (6)
    const serviceImgs = $$(".serviceImage");
    serviceImgs.forEach((box, i) => {
      const url = serviceImageURLs[i % serviceImageURLs.length];
      box.style.backgroundImage = `url("${url}")`;
      box.style.backgroundSize = "cover";
      box.style.backgroundPosition = "center";
    });

    // Rellena galería (8)
    const galleryImgs = $$(".galleryImg");
    galleryImgs.forEach((box, i) => {
      const url = galleryImageURLs[i % galleryImageURLs.length];
      box.style.backgroundImage = `url("${url}")`;
      box.style.backgroundSize = "cover";
      box.style.backgroundPosition = "center";
    });

    // Rellena collage del hero (4 mini)
    const heroMini = $$(".svcImg");
    heroMini.forEach((box, i) => {
      const url = heroMiniURLs[i % heroMiniURLs.length];
      box.style.backgroundImage = `url("${url}")`;
      box.style.backgroundSize = "cover";
      box.style.backgroundPosition = "center";
    });
  </script>
@endsection

