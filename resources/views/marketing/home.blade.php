@extends('layouts.marketing')

@section('title', 'Desarrollo de páginas web en Lima | Desarrollo de software a medida - SystemsGG')
@section('og_title', 'Desarrollo de páginas web en Lima y software a medida | SystemsGG')
@section('canonical', url('/'))
@section('meta_description', 'Empresa de desarrollo de páginas web en Lima y desarrollo de software a medida. +11 años de experiencia creando soluciones para empresas: web, APIs, automatización e integraciones.')

@section('content')
  <!-- HERO -->
  <section id="inicio" class="relative overflow-hidden">
    <div class="absolute inset-0 -z-10">
      <div class="absolute -top-32 left-1/2 h-[520px] w-[520px] -translate-x-1/2 rounded-full bg-[radial-gradient(circle_at_center,rgba(99,102,241,0.35),transparent_60%)] blur-2xl"></div>
      <div class="absolute -bottom-40 left-10 h-[520px] w-[520px] rounded-full bg-[radial-gradient(circle_at_center,rgba(168,85,247,0.28),transparent_60%)] blur-2xl"></div>
    </div>

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="py-14 sm:py-18 lg:py-24">
        <div class="grid items-center gap-10 lg:grid-cols-12">
          <div class="lg:col-span-7">
            <div class="inline-flex items-center gap-2 rounded-full bg-white/5 px-3 py-1 text-xs text-[var(--c-muted)] ring-1 ring-white/10">
              <span class="inline-flex size-2 rounded-full bg-[var(--c-accent)]"></span>
              +11 años de experiencia · +50 proyectos entregados
            </div>

            <h1 class="mt-5 text-3xl font-semibold tracking-tight sm:text-4xl lg:text-5xl">
              <span class="bg-gradient-to-r from-[var(--c-primary)] to-[var(--c-primary-2)] bg-clip-text text-transparent">Desarrollo de páginas web profesionales</span>
              y <span class="text-white">software a medida</span> que impulsa resultados
            </h1>

            <p class="mt-4 text-base text-[var(--c-muted)] leading-relaxed sm:text-lg">
              Transformamos tus ideas en soluciones digitales rentables. Creamos páginas web, sistemas personalizados, APIs e integraciones con tecnología moderna, diseño atractivo y código optimizado para posicionamiento en Google.
            </p>

            <p class="mt-3 text-base text-[var(--c-muted)] leading-relaxed sm:text-lg">
              Trabajamos con empresas en <span class="text-[var(--c-text)] font-medium">Lima, Perú</span> y de forma 100% remota. Comunicación directa, entregas puntuales y soporte continuo. <span class="text-[var(--c-text)]">Sin sorpresas, sin letra pequeña.</span>
            </p>

            <div class="mt-7 flex flex-col gap-3 sm:flex-row sm:items-center">
              <a href="#contacto" class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-[var(--c-primary)] to-[var(--c-primary-2)] px-5 py-3 text-sm font-semibold text-white shadow-soft ring-1 ring-white/10 hover:opacity-95 transition">
                Solicitar cotización
              </a>
              <a href="#servicios" class="inline-flex items-center justify-center rounded-xl bg-white/5 px-5 py-3 text-sm font-semibold text-[var(--c-text)] ring-1 ring-white/10 hover:bg-white/10 transition">
                Ver servicios
              </a>
            </div>

            <div class="mt-8 grid grid-cols-2 gap-4 sm:grid-cols-4">
              <div class="rounded-2xl bg-white/5 p-4 ring-1 ring-white/10">
                <p class="text-sm font-semibold">✓ Entregas puntuales</p>
                <p class="mt-1 text-xs text-[var(--c-muted)]">Avances semanales verificables</p>
              </div>
              <div class="rounded-2xl bg-white/5 p-4 ring-1 ring-white/10">
                <p class="text-sm font-semibold">✓ Código propio</p>
                <p class="mt-1 text-xs text-[var(--c-muted)]">100% tuyo, sin dependencias</p>
              </div>
              <div class="rounded-2xl bg-white/5 p-4 ring-1 ring-white/10">
                <p class="text-sm font-semibold">✓ Seguridad</p>
                <p class="mt-1 text-xs text-[var(--c-muted)]">SSL, backups y monitoreo</p>
              </div>
              <div class="rounded-2xl bg-white/5 p-4 ring-1 ring-white/10">
                <p class="text-sm font-semibold">✓ Soporte incluido</p>
                <p class="mt-1 text-xs text-[var(--c-muted)]">Garantía post-lanzamiento</p>
              </div>
            </div>
          </div>

          <!-- Panel visual -->
          <div class="lg:col-span-5">
            <div class="relative overflow-hidden rounded-3xl bg-[var(--c-surface)] p-6 ring-1 ring-[var(--c-border)] shadow-soft">
              <div class="absolute -right-24 -top-24 size-64 rounded-full bg-[radial-gradient(circle_at_center,rgba(99,102,241,0.35),transparent_60%)] blur-2xl"></div>
              <div class="absolute -left-28 -bottom-28 size-64 rounded-full bg-[radial-gradient(circle_at_center,rgba(20,184,166,0.24),transparent_60%)] blur-2xl"></div>

              <div class="relative">
                <p class="text-xs text-[var(--c-muted)]">Ejemplo de solución</p>
                <h2 class="mt-1 text-lg font-semibold">Sistema de gestión empresarial</h2>
                <p class="mt-2 text-sm text-[var(--c-muted)]">
                  Centralizamos tus operaciones en una plataforma única, con diseño intuitivo y acceso desde cualquier dispositivo.
                </p>

                <div class="mt-6 grid gap-3">
                  <div class="rounded-2xl bg-[var(--c-elev)] p-4 ring-1 ring-[var(--c-border)]">
                    <div class="flex items-center justify-between">
                      <p class="text-sm font-semibold">Gestión comercial</p>
                      <span class="rounded-full bg-emerald-500/20 px-2 py-1 text-[11px] text-emerald-400 ring-1 ring-emerald-500/30">Incluido</span>
                    </div>
                    <p class="mt-2 text-xs text-[var(--c-muted)]">Clientes, cotizaciones, seguimiento de ventas y reportes en tiempo real.</p>
                  </div>
                  <div class="rounded-2xl bg-[var(--c-elev)] p-4 ring-1 ring-[var(--c-border)]">
                    <div class="flex items-center justify-between">
                      <p class="text-sm font-semibold">Control operativo</p>
                      <span class="rounded-full bg-emerald-500/20 px-2 py-1 text-[11px] text-emerald-400 ring-1 ring-emerald-500/30">Incluido</span>
                    </div>
                    <p class="mt-2 text-xs text-[var(--c-muted)]">Tareas, proyectos, responsables y trazabilidad completa de procesos.</p>
                  </div>
                  <div class="rounded-2xl bg-[var(--c-elev)] p-4 ring-1 ring-[var(--c-border)]">
                    <div class="flex items-center justify-between">
                      <p class="text-sm font-semibold">Integraciones</p>
                      <span class="rounded-full bg-emerald-500/20 px-2 py-1 text-[11px] text-emerald-400 ring-1 ring-emerald-500/30">Disponible</span>
                    </div>
                    <p class="mt-2 text-xs text-[var(--c-muted)]">WhatsApp, email, pasarelas de pago, SUNAT, ERPs y más.</p>
                  </div>
                </div>

                <div class="mt-6 rounded-2xl bg-gradient-to-r from-[var(--c-primary)]/20 to-[var(--c-primary-2)]/20 p-4 ring-1 ring-[var(--c-primary)]/30">
                  <p class="text-xs text-[var(--c-muted)]">Tiempo promedio de entrega</p>
                  <p class="mt-1 text-sm font-semibold">Tu proyecto listo en 4–8 semanas</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- SERVICIOS -->
  <section id="servicios" class="border-t border-white/10">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="py-14 lg:py-20">
        <div class="max-w-2xl">
          <h2 class="text-2xl font-semibold tracking-tight sm:text-3xl">Soluciones que generan resultados</h2>
          <p class="mt-3 text-sm text-[var(--c-muted)] leading-relaxed">
            No solo desarrollamos software, creamos herramientas que optimizan tu operación, aumentan tus ventas y reducen costos. Cada proyecto incluye análisis, diseño, desarrollo, pruebas y acompañamiento post-lanzamiento.
          </p>
        </div>

        <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
          <div class="rounded-3xl bg-[var(--c-surface)] p-6 ring-1 ring-[var(--c-border)] shadow-soft">
            <div class="flex items-center gap-3">
              <span class="inline-flex size-11 items-center justify-center rounded-2xl bg-white/5 ring-1 ring-white/10">
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 7h-9" /><path d="M14 17H5" /><circle cx="17" cy="17" r="3" /><circle cx="7" cy="7" r="3" /></svg>
              </span>
              <h3 class="text-base font-semibold">Software a medida</h3>
            </div>
            <p class="mt-3 text-sm text-[var(--c-muted)]">
              Sistemas diseñados específicamente para tu negocio: CRMs, ERPs, gestión de inventarios, facturación electrónica, control de producción y más. Automatiza procesos y elimina tareas repetitivas.
            </p>
            <ul class="mt-4 space-y-1.5 text-xs text-[var(--c-muted)]">
              <li class="flex items-center gap-2"><span class="text-emerald-400">✓</span> Paneles administrativos intuitivos</li>
              <li class="flex items-center gap-2"><span class="text-emerald-400">✓</span> Control de usuarios y permisos</li>
              <li class="flex items-center gap-2"><span class="text-emerald-400">✓</span> Reportes y dashboards en tiempo real</li>
            </ul>
          </div>

          <div class="rounded-3xl bg-[var(--c-surface)] p-6 ring-1 ring-[var(--c-border)] shadow-soft">
            <div class="flex items-center gap-3">
              <span class="inline-flex size-11 items-center justify-center rounded-2xl bg-white/5 ring-1 ring-white/10">
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16v16H4z" /><path d="M4 9h16" /><path d="M9 20V9" /></svg>
              </span>
              <h3 class="text-base font-semibold">Páginas web profesionales</h3>
            </div>
            <p class="mt-3 text-sm text-[var(--c-muted)]">
              Sitios web que destacan tu marca y convierten visitantes en clientes. Diseño moderno, velocidad de carga optimizada, SEO técnico y adaptación perfecta a móviles y tablets.
            </p>
            <ul class="mt-4 space-y-1.5 text-xs text-[var(--c-muted)]">
              <li class="flex items-center gap-2"><span class="text-emerald-400">✓</span> Landing pages de alta conversión</li>
              <li class="flex items-center gap-2"><span class="text-emerald-400">✓</span> Sitios corporativos y catálogos</li>
              <li class="flex items-center gap-2"><span class="text-emerald-400">✓</span> Optimización para Google (SEO)</li>
            </ul>
          </div>

          <div class="rounded-3xl bg-[var(--c-surface)] p-6 ring-1 ring-[var(--c-border)] shadow-soft">
            <div class="flex items-center gap-3">
              <span class="inline-flex size-11 items-center justify-center rounded-2xl bg-white/5 ring-1 ring-white/10">
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 18a4 4 0 0 0 0-8H5" /><path d="M8 6h11a4 4 0 0 1 0 8" /><path d="M12 14v7" /><path d="M9 18h6" /></svg>
              </span>
              <h3 class="text-base font-semibold">Integraciones & APIs</h3>
            </div>
            <p class="mt-3 text-sm text-[var(--c-muted)]">
              Conectamos tus sistemas con las herramientas que ya usas. Unificamos datos, automatizamos flujos de trabajo y eliminamos la duplicación de información entre plataformas.
            </p>
            <ul class="mt-4 space-y-1.5 text-xs text-[var(--c-muted)]">
              <li class="flex items-center gap-2"><span class="text-emerald-400">✓</span> WhatsApp Business, email y SMS</li>
              <li class="flex items-center gap-2"><span class="text-emerald-400">✓</span> Pasarelas de pago (Culqi, Niubiz)</li>
              <li class="flex items-center gap-2"><span class="text-emerald-400">✓</span> SUNAT, facturación electrónica</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- PLANES DE DESARROLLO WEB -->
  <section id="planes" class="border-t border-white/10">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="py-14 lg:py-20">
        <div class="text-center max-w-3xl mx-auto">
          <h2 class="text-2xl font-semibold tracking-tight sm:text-3xl">Planes de Desarrollo Web</h2>
          <p class="mt-3 text-sm text-[var(--c-muted)] leading-relaxed">
            Elige el plan que mejor se adapte a las necesidades de tu negocio. Todos incluyen diseño profesional, código optimizado y soporte post-lanzamiento.
          </p>
        </div>

        <div class="mt-10 grid gap-6 lg:grid-cols-3">
          <!-- Plan Estándar -->
          <div class="relative rounded-3xl bg-[var(--c-surface)] p-6 ring-1 ring-[var(--c-border)] shadow-soft flex flex-col">
            <div class="flex-1">
              <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold">Estándar</h3>
                <span class="rounded-full bg-white/5 px-3 py-1 text-xs text-[var(--c-muted)] ring-1 ring-white/10">Ideal para empezar</span>
              </div>
              <p class="mt-2 text-sm text-[var(--c-muted)]">Página web informativa perfecta para pequeños negocios y emprendedores que buscan presencia digital.</p>
              
              <div class="mt-4">
                <p class="text-3xl font-bold">S/ 800</p>
                <p class="text-xs text-[var(--c-muted)]">Sin IGV • Pago único</p>
              </div>

              <ul class="mt-6 space-y-3">
                <li class="flex items-start gap-2 text-sm">
                  <span class="mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-full bg-emerald-500/20 text-xs text-emerald-400">✓</span>
                  <span class="text-[var(--c-muted)]"><span class="text-[var(--c-text)]">4 vistas:</span> Inicio, Nosotros, Proyectos, Contacto</span>
                </li>
                <li class="flex items-start gap-2 text-sm">
                  <span class="mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-full bg-emerald-500/20 text-xs text-emerald-400">✓</span>
                  <span class="text-[var(--c-muted)]"><span class="text-[var(--c-text)]">Diseño responsive</span> (móvil/tablet/desktop)</span>
                </li>
                <li class="flex items-start gap-2 text-sm">
                  <span class="mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-full bg-emerald-500/20 text-xs text-emerald-400">✓</span>
                  <span class="text-[var(--c-muted)]"><span class="text-[var(--c-text)]">Panel de administración</span> para editar contenido</span>
                </li>
                <li class="flex items-start gap-2 text-sm">
                  <span class="mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-full bg-emerald-500/20 text-xs text-emerald-400">✓</span>
                  <span class="text-[var(--c-muted)]"><span class="text-[var(--c-text)]">Hosting + Dominio</span> (configuración inicial)</span>
                </li>
                <li class="flex items-start gap-2 text-sm">
                  <span class="mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-full bg-emerald-500/20 text-xs text-emerald-400">✓</span>
                  <span class="text-[var(--c-muted)]"><span class="text-[var(--c-text)]">Certificado SSL</span> gratuito</span>
                </li>
                <li class="flex items-start gap-2 text-sm">
                  <span class="mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-full bg-emerald-500/20 text-xs text-emerald-400">✓</span>
                  <span class="text-[var(--c-muted)]"><span class="text-[var(--c-text)]">Formulario de contacto</span> funcional</span>
                </li>
                <li class="flex items-start gap-2 text-sm">
                  <span class="mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-full bg-emerald-500/20 text-xs text-emerald-400">✓</span>
                  <span class="text-[var(--c-muted)]"><span class="text-[var(--c-text)]">Capacitación + Manual</span> de uso</span>
                </li>
                <li class="flex items-start gap-2 text-sm">
                  <span class="mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-full bg-emerald-500/20 text-xs text-emerald-400">✓</span>
                  <span class="text-[var(--c-muted)]"><span class="text-[var(--c-text)]">SEO básico</span> (metatags, títulos, descripciones)</span>
                </li>
                <li class="flex items-start gap-2 text-sm">
                  <span class="mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-full bg-emerald-500/20 text-xs text-emerald-400">✓</span>
                  <span class="text-[var(--c-muted)]"><span class="text-[var(--c-text)]">Entrega:</span> 2 semanas</span>
                </li>
              </ul>
            </div>

            <a href="#contacto" class="mt-6 inline-flex w-full items-center justify-center rounded-xl bg-white/5 px-5 py-3 text-sm font-semibold text-[var(--c-text)] ring-1 ring-white/10 hover:bg-white/10 transition">
              Solicitar cotización
            </a>
          </div>

          <!-- Plan Empresarial -->
          <div class="relative rounded-3xl bg-[var(--c-surface)] p-6 ring-2 ring-[var(--c-primary)] shadow-soft flex flex-col">
            <div class="absolute -top-3 left-1/2 -translate-x-1/2">
              <span class="rounded-full bg-gradient-to-r from-[var(--c-primary)] to-[var(--c-primary-2)] px-4 py-1 text-xs font-semibold text-white">Más popular</span>
            </div>
            <div class="flex-1">
              <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold">Empresarial</h3>
                <span class="rounded-full bg-[var(--c-primary)]/10 px-3 py-1 text-xs text-[var(--c-primary)] ring-1 ring-[var(--c-primary)]/30">Recomendado</span>
              </div>
              <p class="mt-2 text-sm text-[var(--c-muted)]">Página web completa para empresas que necesitan mayor presencia digital y funcionalidades avanzadas.</p>
              
              <div class="mt-4">
                <p class="text-3xl font-bold">S/ 1,400</p>
                <p class="text-xs text-[var(--c-muted)]">Sin IGV • Pago único</p>
              </div>

              <ul class="mt-6 space-y-3">
                <li class="flex items-start gap-2 text-sm">
                  <span class="mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-full bg-emerald-500/20 text-xs text-emerald-400">✓</span>
                  <span class="text-[var(--c-muted)]"><span class="text-[var(--c-text)]">Todo lo del plan Estándar</span></span>
                </li>
                <li class="flex items-start gap-2 text-sm">
                  <span class="mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-full bg-[var(--c-primary)]/20 text-xs text-[var(--c-primary)]">+</span>
                  <span class="text-[var(--c-muted)]"><span class="text-[var(--c-text)]">6-8 vistas adicionales</span> (Servicios, Blog, FAQ, etc.)</span>
                </li>
                <li class="flex items-start gap-2 text-sm">
                  <span class="mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-full bg-[var(--c-primary)]/20 text-xs text-[var(--c-primary)]">+</span>
                  <span class="text-[var(--c-muted)]"><span class="text-[var(--c-text)]">Galería avanzada</span> de imágenes/proyectos</span>
                </li>
                <li class="flex items-start gap-2 text-sm">
                  <span class="mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-full bg-[var(--c-primary)]/20 text-xs text-[var(--c-primary)]">+</span>
                  <span class="text-[var(--c-muted)]"><span class="text-[var(--c-text)]">Integración redes sociales</span></span>
                </li>
                <li class="flex items-start gap-2 text-sm">
                  <span class="mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-full bg-[var(--c-primary)]/20 text-xs text-[var(--c-primary)]">+</span>
                  <span class="text-[var(--c-muted)]"><span class="text-[var(--c-text)]">Formularios personalizados</span></span>
                </li>
                <li class="flex items-start gap-2 text-sm">
                  <span class="mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-full bg-[var(--c-primary)]/20 text-xs text-[var(--c-primary)]">+</span>
                  <span class="text-[var(--c-muted)]"><span class="text-[var(--c-text)]">SEO avanzado</span> + Google Analytics</span>
                </li>
                <li class="flex items-start gap-2 text-sm">
                  <span class="mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-full bg-[var(--c-primary)]/20 text-xs text-[var(--c-primary)]">+</span>
                  <span class="text-[var(--c-muted)]"><span class="text-[var(--c-text)]">Optimización de velocidad</span></span>
                </li>
                <li class="flex items-start gap-2 text-sm">
                  <span class="mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-full bg-[var(--c-primary)]/20 text-xs text-[var(--c-primary)]">+</span>
                  <span class="text-[var(--c-muted)]"><span class="text-[var(--c-text)]">Soporte prioritario</span> 30 días</span>
                </li>
                <li class="flex items-start gap-2 text-sm">
                  <span class="mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-full bg-[var(--c-primary)]/20 text-xs text-[var(--c-primary)]">+</span>
                  <span class="text-[var(--c-muted)]"><span class="text-[var(--c-text)]">Entrega:</span> 3-4 semanas</span>
                </li>
              </ul>
            </div>

            <a href="#contacto" class="mt-6 inline-flex w-full items-center justify-center rounded-xl bg-gradient-to-r from-[var(--c-primary)] to-[var(--c-primary-2)] px-5 py-3 text-sm font-semibold text-white shadow-soft ring-1 ring-white/10 hover:opacity-95 transition">
              Solicitar cotización
            </a>
          </div>

          <!-- Plan Premium -->
          <div class="relative rounded-3xl bg-[var(--c-surface)] p-6 ring-1 ring-[var(--c-border)] shadow-soft flex flex-col">
            <div class="flex-1">
              <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold">Premium</h3>
                <span class="rounded-full bg-amber-500/10 px-3 py-1 text-xs text-amber-400 ring-1 ring-amber-500/30">Personalizado</span>
              </div>
              <p class="mt-2 text-sm text-[var(--c-muted)]">Solución web a medida para empresas que requieren funcionalidades específicas e integraciones avanzadas.</p>
              
              <div class="mt-4">
                <p class="text-3xl font-bold">Desde S/ 2,000</p>
                <p class="text-xs text-[var(--c-muted)]">Sin IGV • Precio según requerimientos</p>
              </div>

              <ul class="mt-6 space-y-3">
                <li class="flex items-start gap-2 text-sm">
                  <span class="mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-full bg-emerald-500/20 text-xs text-emerald-400">✓</span>
                  <span class="text-[var(--c-muted)]"><span class="text-[var(--c-text)]">Todo lo del plan Empresarial</span></span>
                </li>
                <li class="flex items-start gap-2 text-sm">
                  <span class="mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-full bg-amber-500/20 text-xs text-amber-400">★</span>
                  <span class="text-[var(--c-muted)]"><span class="text-[var(--c-text)]">Vistas ilimitadas</span> según requerimiento</span>
                </li>
                <li class="flex items-start gap-2 text-sm">
                  <span class="mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-full bg-amber-500/20 text-xs text-amber-400">★</span>
                  <span class="text-[var(--c-muted)]"><span class="text-[var(--c-text)]">Integraciones</span> (WhatsApp API, pagos, etc.)</span>
                </li>
                <li class="flex items-start gap-2 text-sm">
                  <span class="mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-full bg-amber-500/20 text-xs text-amber-400">★</span>
                  <span class="text-[var(--c-muted)]"><span class="text-[var(--c-text)]">Sistema de reservas/citas</span> o catálogo</span>
                </li>
                <li class="flex items-start gap-2 text-sm">
                  <span class="mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-full bg-amber-500/20 text-xs text-amber-400">★</span>
                  <span class="text-[var(--c-muted)]"><span class="text-[var(--c-text)]">Multi-idioma</span> (si aplica)</span>
                </li>
                <li class="flex items-start gap-2 text-sm">
                  <span class="mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-full bg-amber-500/20 text-xs text-amber-400">★</span>
                  <span class="text-[var(--c-muted)]"><span class="text-[var(--c-text)]">Chatbot básico</span> o WhatsApp widget</span>
                </li>
                <li class="flex items-start gap-2 text-sm">
                  <span class="mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-full bg-amber-500/20 text-xs text-amber-400">★</span>
                  <span class="text-[var(--c-muted)]"><span class="text-[var(--c-text)]">Consultoría UX/UI</span> personalizada</span>
                </li>
                <li class="flex items-start gap-2 text-sm">
                  <span class="mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-full bg-amber-500/20 text-xs text-amber-400">★</span>
                  <span class="text-[var(--c-muted)]"><span class="text-[var(--c-text)]">Soporte extendido</span> 60 días</span>
                </li>
                <li class="flex items-start gap-2 text-sm">
                  <span class="mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-full bg-amber-500/20 text-xs text-amber-400">★</span>
                  <span class="text-[var(--c-muted)]"><span class="text-[var(--c-text)]">Entrega:</span> según alcance</span>
                </li>
              </ul>
            </div>

            <a href="#contacto" class="mt-6 inline-flex w-full items-center justify-center rounded-xl bg-white/5 px-5 py-3 text-sm font-semibold text-[var(--c-text)] ring-1 ring-white/10 hover:bg-white/10 transition">
              Solicitar cotización
            </a>
          </div>
        </div>

        <!-- Nota informativa -->
        <div class="mt-8 rounded-2xl bg-gradient-to-r from-[var(--c-primary)]/10 to-[var(--c-primary-2)]/10 p-4 ring-1 ring-[var(--c-primary)]/20 text-center">
          <p class="text-sm text-[var(--c-muted)]">
            <span class="text-[var(--c-text)] font-medium">¿Necesitas algo diferente?</span> Todos los planes son personalizables. Contáctanos para una cotización a medida según tus necesidades específicas.
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- PROCESO -->
  <section id="proceso" class="border-t border-white/10">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="py-14 lg:py-20">
        <div class="grid gap-10 lg:grid-cols-12">
          <div class="lg:col-span-5">
            <h2 class="text-2xl font-semibold tracking-tight sm:text-3xl">Trabajamos contigo, no solo para ti</h2>
            <p class="mt-3 text-sm text-[var(--c-muted)] leading-relaxed">
              Nuestro proceso está diseñado para que siempre sepas cómo avanza tu proyecto. Reuniones de seguimiento, avances verificables cada semana y acceso directo al equipo de desarrollo. Sin intermediarios, sin sorpresas en la factura final.
            </p>
            <div class="mt-6 rounded-2xl bg-gradient-to-r from-[var(--c-primary)]/10 to-[var(--c-primary-2)]/10 p-4 ring-1 ring-[var(--c-primary)]/20">
              <p class="text-sm font-medium text-[var(--c-text)]">💬 Comunicación directa</p>
              <p class="mt-1 text-xs text-[var(--c-muted)]">Hablas directo con quien desarrolla tu proyecto, no con un intermediario.</p>
            </div>
          </div>
          <div class="lg:col-span-7">
            <div class="grid gap-4">
              <div class="rounded-3xl bg-[var(--c-surface)] p-6 ring-1 ring-[var(--c-border)]">
                <p class="text-sm font-semibold">1. Conversamos sobre tu proyecto</p>
                <p class="mt-2 text-sm text-[var(--c-muted)]">Entendemos tu negocio, tus objetivos y el problema que quieres resolver. Definimos juntos el alcance, funcionalidades y tiempos realistas.</p>
              </div>
              <div class="rounded-3xl bg-[var(--c-surface)] p-6 ring-1 ring-[var(--c-border)]">
                <p class="text-sm font-semibold">2. Diseñamos la solución</p>
                <p class="mt-2 text-sm text-[var(--c-muted)]">Creamos prototipos visuales de tu sistema o página web. Ves cómo quedará antes de escribir una línea de código. Ajustamos hasta que estés conforme.</p>
              </div>
              <div class="rounded-3xl bg-[var(--c-surface)] p-6 ring-1 ring-[var(--c-border)]">
                <p class="text-sm font-semibold">3. Desarrollamos con entregas semanales</p>
                <p class="mt-2 text-sm text-[var(--c-muted)]">Avanzamos por etapas cortas. Cada semana recibes una versión funcional que puedes revisar y probar. Correcciones incluidas.</p>
              </div>
              <div class="rounded-3xl bg-[var(--c-surface)] p-6 ring-1 ring-[var(--c-border)]">
                <p class="text-sm font-semibold">4. Lanzamos y te acompañamos</p>
                <p class="mt-2 text-sm text-[var(--c-muted)]">Desplegamos tu proyecto en producción, configuramos dominios, certificados SSL y realizamos las pruebas finales. Soporte incluido durante los primeros meses.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- PROYECTOS RECIENTES -->
  <section id="proyectos" class="border-t border-white/10">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="py-14 lg:py-20">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
          <div class="max-w-2xl">
            <h2 class="text-2xl font-semibold tracking-tight sm:text-3xl">Proyectos que hablan por sí solos</h2>
            <p class="mt-2 text-sm text-[var(--c-muted)]">Empresas de diversos sectores ya confían en nosotros. Estos son algunos de los proyectos que hemos entregado recientemente.</p>
          </div>
          <a href="#contacto" class="text-sm font-semibold text-[var(--c-text)] hover:opacity-90">Hablemos →</a>
        </div>

        <div class="mt-10 grid gap-6 lg:grid-cols-3">
          <!-- Proyecto 1: IBT -->
          <div class="overflow-hidden rounded-3xl bg-[var(--c-surface)] ring-1 ring-[var(--c-border)]">
            <div class="aspect-video overflow-hidden bg-gradient-to-br from-blue-600 to-blue-400">
              <img src="{{ asset('img/proyectos/ibt.png') }}" alt="IBT - Internet de fibra óptica" class="h-full w-full object-cover" />
            </div>
            <div class="p-6">
              <p class="text-xs text-[var(--c-muted)]">Telecomunicaciones · EE.UU.</p>
              <p class="mt-1 text-base font-semibold">IBT - Internet de Fibra Óptica</p>
              <p class="mt-2 text-sm text-[var(--c-muted)]">Sitio web corporativo para proveedor de fibra óptica con planes residenciales y empresariales, verificador de cobertura y portal de clientes.</p>
            </div>
          </div>

          <!-- Proyecto 2: Catedral de Lima -->
          <div class="overflow-hidden rounded-3xl bg-[var(--c-surface)] ring-1 ring-[var(--c-border)]">
            <div class="aspect-video overflow-hidden bg-gradient-to-br from-amber-700 to-amber-500">
              <img src="{{ asset('img/proyectos/catedral.png') }}" alt="Basílica Catedral de Lima" class="h-full w-full object-cover" />
            </div>
            <div class="p-6">
              <p class="text-xs text-[var(--c-muted)]">Turismo & Cultura · Perú</p>
              <p class="mt-1 text-base font-semibold">Basílica Catedral de Lima</p>
              <p class="mt-2 text-sm text-[var(--c-muted)]">Sitio institucional del templo más importante del Perú: horarios de visita, venta de entradas al museo, información de misas y eventos culturales.</p>
            </div>
          </div>

          <!-- Proyecto 3: MTOP Vínculo -->
          <div class="overflow-hidden rounded-3xl bg-[var(--c-surface)] ring-1 ring-[var(--c-border)]">
            <div class="aspect-video overflow-hidden bg-gradient-to-br from-indigo-700 to-indigo-500">
              <img src="{{ asset('img/proyectos/mtvinculo.png') }}" alt="MTOP Vínculo Inmobiliario" class="h-full w-full object-cover" />
            </div>
            <div class="p-6">
              <p class="text-xs text-[var(--c-muted)]">Inmobiliaria · México</p>
              <p class="mt-1 text-base font-semibold">MTOP Vínculo Inmobiliario</p>
              <p class="mt-2 text-sm text-[var(--c-muted)]">Plataforma inmobiliaria con buscador avanzado de propiedades para compra y renta de casas, departamentos y oficinas.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CONTACTO -->
  <section id="contacto" class="border-t border-white/10">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="py-14 lg:py-20">
        <div class="grid gap-10 lg:grid-cols-12">
          <div class="lg:col-span-5">
            <h2 class="text-2xl font-semibold tracking-tight sm:text-3xl">Solicita tu cotización sin compromiso</h2>
            <p class="mt-3 text-sm text-[var(--c-muted)] leading-relaxed">
              Cuéntanos sobre tu proyecto y recibirás una propuesta personalizada en menos de 48 horas. Sin letra pequeña, sin costos ocultos.
            </p>
            <div class="mt-6 space-y-3">
              <div class="flex items-start gap-3">
                <span class="mt-0.5 flex size-5 items-center justify-center rounded-full bg-emerald-500/20 text-xs text-emerald-400">✓</span>
                <p class="text-sm text-[var(--c-muted)]"><span class="text-[var(--c-text)]">Respuesta en menos de 48h</span> con propuesta detallada</p>
              </div>
              <div class="flex items-start gap-3">
                <span class="mt-0.5 flex size-5 items-center justify-center rounded-full bg-emerald-500/20 text-xs text-emerald-400">✓</span>
                <p class="text-sm text-[var(--c-muted)]"><span class="text-[var(--c-text)]">Precio cerrado</span> sin sorpresas ni costos adicionales</p>
              </div>
              <div class="flex items-start gap-3">
                <span class="mt-0.5 flex size-5 items-center justify-center rounded-full bg-emerald-500/20 text-xs text-emerald-400">✓</span>
                <p class="text-sm text-[var(--c-muted)]"><span class="text-[var(--c-text)]">Consultoría gratuita</span> para entender tus necesidades</p>
              </div>
            </div>
          </div>
          <div class="lg:col-span-7">
            <form id="lead-capture-form" x-data="{ isCompany: false, sending: false, sent: false, ok: false, err: '', fieldErrors: {} }" class="rounded-3xl bg-[var(--c-surface)] p-6 ring-1 ring-[var(--c-border)]">
              <div class="grid gap-4 sm:grid-cols-2">
                <!-- Honeypot anti-spam -->
                <input type="text" name="website" tabindex="-1" autocomplete="off" class="hidden" aria-hidden="true" />
                <div>
                  <label for="contact_name" class="text-xs text-[var(--c-muted)]">Nombre</label>
                  <input id="contact_name" name="name" type="text" autocomplete="name"
                    :class="fieldErrors.name ? 'ring-red-500/50 ring-2' : 'ring-[var(--c-border)]'"
                    class="mt-1 w-full rounded-2xl bg-[var(--c-elev)] px-4 py-3 text-sm outline-none ring-1 focus:ring-2 focus:ring-[var(--c-primary)] transition-all"
                    placeholder="Tu nombre"
                    @input="fieldErrors.name = null" />
                  <p x-cloak x-show="fieldErrors.name" class="mt-1 text-xs text-red-400" x-text="fieldErrors.name"></p>
                </div>
                <div>
                  <label for="contact_email" class="text-xs text-[var(--c-muted)]">Email <span class="text-red-400">*</span></label>
                  <input id="contact_email" name="email" type="email" autocomplete="email" required
                    :class="fieldErrors.email ? 'ring-red-500/50 ring-2' : 'ring-[var(--c-border)]'"
                    class="mt-1 w-full rounded-2xl bg-[var(--c-elev)] px-4 py-3 text-sm outline-none ring-1 focus:ring-2 focus:ring-[var(--c-primary)] transition-all"
                    placeholder="nombre@empresa.com"
                    @input="fieldErrors.email = null" />
                  <p x-cloak x-show="fieldErrors.email" class="mt-1 text-xs text-red-400" x-text="fieldErrors.email"></p>
                </div>

                <div>
                  <label for="contact_phone" class="text-xs text-[var(--c-muted)]">Teléfono <span class="text-red-400">*</span></label>
                  <input id="contact_phone" name="phone" type="tel" inputmode="tel" autocomplete="tel" required
                    :class="fieldErrors.phone ? 'ring-red-500/50 ring-2' : 'ring-[var(--c-border)]'"
                    class="mt-1 w-full rounded-2xl bg-[var(--c-elev)] px-4 py-3 text-sm outline-none ring-1 focus:ring-2 focus:ring-[var(--c-primary)] transition-all"
                    placeholder="+51 999 999 999"
                    @input="fieldErrors.phone = null" />
                  <p x-cloak x-show="fieldErrors.phone" class="mt-1 text-xs text-red-400" x-text="fieldErrors.phone"></p>
                </div>

                <div class="flex items-end">
                  <label for="contact_is_company" class="flex w-full cursor-pointer items-center gap-3 rounded-2xl bg-[var(--c-elev)] px-4 py-3 text-sm ring-1 ring-[var(--c-border)]">
                    <input id="contact_is_company" name="is_company" type="checkbox" x-model="isCompany" @change="fieldErrors.company_ruc = null" class="size-4 rounded border-white/20 bg-transparent text-[var(--c-primary)] focus:ring-[var(--c-primary)]" />
                    <span class="text-[var(--c-text)]">Es una empresa</span>
                  </label>
                </div>

                <div x-cloak x-show="isCompany" class="sm:col-span-1">
                  <label for="contact_company_name" class="text-xs text-[var(--c-muted)]">Nombre de la empresa</label>
                  <input id="contact_company_name" name="company_name" type="text" autocomplete="organization"
                    :class="fieldErrors.company_name ? 'ring-red-500/50 ring-2' : 'ring-[var(--c-border)]'"
                    class="mt-1 w-full rounded-2xl bg-[var(--c-elev)] px-4 py-3 text-sm outline-none ring-1 focus:ring-2 focus:ring-[var(--c-primary)] transition-all"
                    placeholder="Razón social"
                    @input="fieldErrors.company_name = null" />
                  <p x-cloak x-show="fieldErrors.company_name" class="mt-1 text-xs text-red-400" x-text="fieldErrors.company_name"></p>
                </div>
                <div x-cloak x-show="isCompany" class="sm:col-span-1">
                  <label for="contact_company_ruc" class="text-xs text-[var(--c-muted)]">RUC <span class="text-red-400">*</span></label>
                  <input id="contact_company_ruc" name="company_ruc" type="text" inputmode="numeric" autocomplete="off" x-bind:required="isCompany"
                    :class="fieldErrors.company_ruc ? 'ring-red-500/50 ring-2' : 'ring-[var(--c-border)]'"
                    class="mt-1 w-full rounded-2xl bg-[var(--c-elev)] px-4 py-3 text-sm outline-none ring-1 focus:ring-2 focus:ring-[var(--c-primary)] transition-all"
                    placeholder="20123456789"
                    @input="fieldErrors.company_ruc = null" />
                  <p x-cloak x-show="fieldErrors.company_ruc" class="mt-1 text-xs text-red-400" x-text="fieldErrors.company_ruc"></p>
                </div>

                <div class="sm:col-span-2">
                  <label for="contact_project_type" class="text-xs text-[var(--c-muted)]">Tipo de proyecto</label>
                  <select id="contact_project_type" name="project_type"
                    :class="fieldErrors.project_type ? 'ring-red-500/50 ring-2' : 'ring-[var(--c-border)]'"
                    class="mt-1 w-full rounded-2xl bg-[var(--c-elev)] px-4 py-3 text-sm outline-none ring-1 focus:ring-2 focus:ring-[var(--c-primary)] transition-all"
                    @change="fieldErrors.project_type = null">
                    <option value="" selected disabled>Selecciona una opción</option>
                    <option value="pagina_web">Página web</option>
                    <option value="pagina_web_corporativa">Página web corporativa</option>
                    <option value="landing_page">Landing page</option>
                    <option value="crm">CRM</option>
                    <option value="erp">ERP</option>
                    <option value="software_a_medida">Software a medida</option>
                    <option value="otros">Otros</option>
                  </select>
                  <p x-cloak x-show="fieldErrors.project_type" class="mt-1 text-xs text-red-400" x-text="fieldErrors.project_type"></p>
                </div>

                <div class="sm:col-span-2">
                  <label for="contact_message" class="text-xs text-[var(--c-muted)]">¿Qué necesitas?</label>
                  <textarea id="contact_message" name="message" rows="4"
                    :class="fieldErrors.message ? 'ring-red-500/50 ring-2' : 'ring-[var(--c-border)]'"
                    class="mt-1 w-full rounded-2xl bg-[var(--c-elev)] px-4 py-3 text-sm outline-none ring-1 focus:ring-2 focus:ring-[var(--c-primary)] transition-all"
                    placeholder="Describe el objetivo, tiempos y alcance aproximado..."
                    @input="fieldErrors.message = null"></textarea>
                  <p x-cloak x-show="fieldErrors.message" class="mt-1 text-xs text-red-400" x-text="fieldErrors.message"></p>
                </div>
              </div>

              <!-- Alerta de error general -->
              <div x-cloak x-show="err" class="mt-4 rounded-xl bg-red-500/10 border border-red-500/30 p-4">
                <div class="flex items-start gap-3">
                  <svg class="size-5 text-red-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                  </svg>
                  <div>
                    <p class="text-sm font-medium text-red-300">No se pudo enviar el formulario</p>
                    <p class="mt-1 text-xs text-red-400" x-text="err"></p>
                  </div>
                </div>
              </div>

              <!-- Alerta de éxito -->
              <div x-cloak x-show="ok" class="mt-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 p-4">
                <div class="flex items-start gap-3">
                  <svg class="size-5 text-emerald-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <div>
                    <p class="text-sm font-medium text-emerald-300">¡Mensaje enviado correctamente!</p>
                    <p class="mt-1 text-xs text-emerald-400">Te contactaremos pronto. Revisa tu correo electrónico.</p>
                  </div>
                </div>
              </div>

              <div class="mt-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-xs text-[var(--c-muted)]">Al enviar aceptas <a href="/terminos" class="underline hover:text-[var(--c-text)]">términos</a> y <a href="/privacidad" class="underline hover:text-[var(--c-text)]">privacidad</a>.</p>
                <button type="submit" :disabled="sending || sent" class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-[var(--c-primary)] to-[var(--c-primary-2)] px-5 py-3 text-sm font-semibold text-white shadow-soft ring-1 ring-white/10 hover:opacity-95 transition disabled:opacity-60">
                  <span x-text="sent ? 'Enviado ✓' : (sending ? 'Enviando…' : 'Enviar solicitud')"></span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const form = document.getElementById('lead-capture-form');
      if (!form) return;

      let locked = false; // evita doble envío incluso si Alpine tarda en actualizar

      // Mensajes de error personalizados para validaciones del servidor
      const errorMessages = {
        email: {
          required: 'El correo electrónico es obligatorio.',
          email: 'Ingresa un correo electrónico válido (ej: nombre@empresa.com).',
          max: 'El correo electrónico es demasiado largo.',
        },
        phone: {
          required: 'El número de teléfono es obligatorio.',
          max: 'El teléfono es demasiado largo.',
          min_digits: 'El teléfono debe tener al menos 9 dígitos.',
        },
        name: {
          max: 'El nombre es demasiado largo (máximo 255 caracteres).',
        },
        company_ruc: {
          required_if: 'Si seleccionas empresa, el RUC es obligatorio.',
          max: 'El RUC es demasiado largo (máximo 30 caracteres).',
        },
        company_name: {
          max: 'El nombre de la empresa es demasiado largo (máximo 255 caracteres).',
        },
        project_type: {
          max: 'El tipo de proyecto es demasiado largo.',
        },
        message: {
          max: 'El mensaje es demasiado largo (máximo 20,000 caracteres).',
        },
        budget_up_to: {
          integer: 'El presupuesto debe ser un número entero.',
          min: 'El presupuesto no puede ser negativo.',
          max: 'El presupuesto excede el máximo permitido.',
        },
        website: {
          max: 'El formulario fue enviado de forma incorrecta. Intenta de nuevo.',
        },
      };

      // Función para obtener mensaje de error amigable
      const getErrorMessage = (field, serverMessage) => {
        // Intenta encontrar un mensaje personalizado basado en palabras clave
        const fieldMessages = errorMessages[field] || {};
        
        const lowerMsg = (serverMessage || '').toLowerCase();
        
        if (lowerMsg.includes('required') || lowerMsg.includes('obligatorio')) {
          return fieldMessages.required || fieldMessages.required_if || serverMessage;
        }
        if (lowerMsg.includes('email') || lowerMsg.includes('válido')) {
          return fieldMessages.email || serverMessage;
        }
        if (lowerMsg.includes('max') || lowerMsg.includes('largo')) {
          return fieldMessages.max || serverMessage;
        }
        if (lowerMsg.includes('min') || lowerMsg.includes('dígitos')) {
          return fieldMessages.min_digits || serverMessage;
        }
        if (lowerMsg.includes('integer') || lowerMsg.includes('entero')) {
          return fieldMessages.integer || serverMessage;
        }
        
        return serverMessage || 'Este campo tiene un error.';
      };

      form.addEventListener('submit', async (e) => {
        e.preventDefault();

        if (locked) return;
        locked = true;

        let navigating = false;

        // Alpine state helper (NO usar propiedades internas como form.__x; puede variar por versión)
        const alpineData = (() => {
          try {
            if (window.Alpine && typeof window.Alpine.$data === 'function') {
              return window.Alpine.$data(form);
            }
          } catch (_) {}
          return null;
        })();

        const setState = (k, v) => {
          try { alpineData && (alpineData[k] = v); } catch (_) {}
        };
        const setFieldError = (field, msg) => {
          try {
            if (alpineData) {
              alpineData.fieldErrors = { ...(alpineData.fieldErrors || {}), [field]: msg };
            }
          } catch (_) {}
        };
        const clearFieldErrors = () => {
          try { alpineData && (alpineData.fieldErrors = {}); } catch (_) {}
        };

        setState('sending', true);
        setState('ok', false);
        setState('err', '');
        setState('sent', false);
        clearFieldErrors();

        // Validación HTML5: NO usar reportValidity() aquí porque muestra los tooltips nativos
        // y además corta el flujo antes de que nuestros alerts/fieldErrors se pinten.
        // checkValidity() solo devuelve true/false sin UI.
        const html5IsValid = typeof form.checkValidity === 'function' ? form.checkValidity() : true;

        // Preloader global (reutilizado del admin)
        try { window.showPreloader && window.showPreloader(); } catch (_) {}

        const fd = new FormData(form);

        // =====================================
        // Validaciones del lado del cliente
        // =====================================
        let hasClientErrors = false;

        // Validar email
        const emailValue = String(fd.get('email') || '').trim();
        if (!emailValue) {
          setFieldError('email', errorMessages.email.required);
          hasClientErrors = true;
        } else {
          const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
          if (!emailRegex.test(emailValue)) {
            setFieldError('email', errorMessages.email.email);
            hasClientErrors = true;
          }
        }

        // Validar teléfono: mínimo 9 dígitos (se permiten +, espacios, etc.)
        const phoneRaw = String(fd.get('phone') || '');
        const phoneDigits = phoneRaw.replace(/\D/g, '');
        if (!phoneRaw.trim()) {
          setFieldError('phone', errorMessages.phone.required);
          hasClientErrors = true;
        } else if (phoneDigits.length < 9) {
          setFieldError('phone', errorMessages.phone.min_digits);
          hasClientErrors = true;
        }

        // Validar RUC si es empresa
        const isCompany = !!fd.get('is_company');
        const companyRuc = String(fd.get('company_ruc') || '').trim();
        if (isCompany && !companyRuc) {
          setFieldError('company_ruc', errorMessages.company_ruc.required_if);
          hasClientErrors = true;
        }

        // Si HTML5 no es válido pero nuestras reglas no capturaron el caso (raro),
        // disparar un error genérico y scrollear al primer :invalid.
        if (!html5IsValid && !hasClientErrors) {
          hasClientErrors = true;
        }

        // Si hay errores de cliente, mostrar alerta general y detener
        if (hasClientErrors) {
          setState('sending', false);
          setState('err', 'Por favor corrige los errores señalados en el formulario.');
          locked = false;
          try { window.hidePreloader && window.hidePreloader(); } catch (_) {}
          // Hacer scroll al primer error
          const firstInvalid = form.querySelector(':invalid');
          if (firstInvalid && typeof firstInvalid.scrollIntoView === 'function') {
            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
          } else {
            const firstError = form.querySelector('.text-red-400:not([x-cloak])');
            if (firstError && typeof firstError.scrollIntoView === 'function') {
              firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
          }
          return;
        }

        const payload = {
          name: fd.get('name') || null,
          email: fd.get('email') || null,
          phone: fd.get('phone') || null,
          is_company: isCompany,
          company_name: fd.get('company_name') || null,
          company_ruc: fd.get('company_ruc') || null,
          project_type: fd.get('project_type') || null,
          budget_up_to: fd.get('budget_up_to') ? parseInt(fd.get('budget_up_to'), 10) : null,
          message: fd.get('message') || null,
          source: 'marketing_home',
          website: fd.get('website') || null,
        };

        try {
          const res = await fetch('/api/leads', {
            method: 'POST',
            headers: {
              'Accept': 'application/json',
              'Content-Type': 'application/json',
            },
            body: JSON.stringify(payload)
          });

          const data = await res.json().catch(() => null);

          if (res.ok && data && data.success) {
            // Bloquea re-envío en esta sesión de página
            setState('sent', true);

            const token = data && data.data ? data.data.thank_you_token : null;
            if (token) {
              // Mantén preloader activo durante la navegación
              navigating = true;
              window.location.href = `/gracias/${encodeURIComponent(token)}`;
              return;
            }

            // Fallback: si por alguna razón no viene token
            form.reset();
            setState('ok', true);
          } else {
            // =====================================
            // Manejo de errores del servidor
            // =====================================
            
            // Verificar si hay errores de validación por campo
            if (data && data.errors && typeof data.errors === 'object') {
              const serverErrors = data.errors;
              let errorCount = 0;
              
              // Procesar cada error de campo
              Object.keys(serverErrors).forEach(field => {
                const fieldError = Array.isArray(serverErrors[field])
                  ? serverErrors[field][0]
                  : serverErrors[field];
                
                if (fieldError) {
                  const friendlyMessage = getErrorMessage(field, fieldError);
                  setFieldError(field, friendlyMessage);
                  errorCount++;
                }
              });
              
              // Mensaje general según cantidad de errores
              if (errorCount === 1) {
                setState('err', 'Hay un error en el formulario. Revisa el campo señalado.');
              } else if (errorCount > 1) {
                setState('err', `Hay ${errorCount} errores en el formulario. Revisa los campos señalados.`);
              } else {
                setState('err', data.message || 'No se pudo enviar el formulario. Intenta nuevamente.');
              }
            } else {
              // Error genérico sin detalles de campo
              let errorMsg = 'No se pudo enviar el formulario.';
              
              if (data && data.message) {
                errorMsg = data.message;
              } else if (res.status === 422) {
                errorMsg = 'Los datos ingresados no son válidos. Verifica la información.';
              } else if (res.status === 429) {
                errorMsg = 'Has enviado muchas solicitudes. Espera un momento e intenta de nuevo.';
              } else if (res.status >= 500) {
                errorMsg = 'Error del servidor. Intenta nuevamente en unos minutos.';
              }
              
              setState('err', errorMsg);
            }
          }
        } catch (err) {
          // Error de red o conexión
          console.error('Error de conexión:', err);
          setState('err', 'Error de conexión. Verifica tu internet e intenta nuevamente.');
        } finally {
          setState('sending', false);
          // Si vamos a redirigir, no ocultamos el preloader (lo manejará la siguiente página)
          if (!navigating) {
            locked = false;
            try { window.hidePreloader && window.hidePreloader(); } catch (_) {}
          }
        }
      });
    });
  </script>
@endsection

