<!doctype html>
<html lang="es-PE" class="scroll-smooth">
<head>
  <!-- Script para aplicar tema oscuro antes del render (evita flash) -->
  <script>
    (function() {
      var theme = localStorage.getItem('theme');
      if (theme === 'dark') {
        document.documentElement.classList.add('dark');
      }
    })();
  </script>
  <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-QQL7KN3BHN"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-QQL7KN3BHN');
</script>
  <!-- Google tag (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=AW-17511185603"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'AW-17511185603');
  </script>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="google-site-verification" content="Qgf4GhHVtZlm6Q0PDuc9p5ReOMWcv1x8GbVbkqbftYg" />
  <title>@yield('title', 'SystemsGG • Desarrollo de software a medida y páginas web en Lima')</title>

  @php
    // Permite override por vista (landing / campañas)
    $siteName = trim((string)($__env->yieldContent('site_name') ?: 'SystemsGG'));
    // Canonical consistente PERO basado en el host real del request.
    // Evita que un APP_URL mal configurado (ej. "https://systemsgg.com")
    // genere URLs canónicas hacia otro dominio.
    $siteUrl = rtrim(url('/'), '/');

    // URL actual sin querystring (solo path) sobre el host canónico
    $currentPath = (string) request()->getPathInfo();
    $defaultCanonical = $siteUrl . rtrim($currentPath, '/');
    if ($defaultCanonical === $siteUrl) {
        // root: deja la URL base sin slash final
        $defaultCanonical = $siteUrl;
    }

    // Permite sobreescribir canonical por página, pero normaliza para que siempre use el host canónico
    $canonicalRaw = trim((string)($__env->yieldContent('canonical') ?: ''));
    if ($canonicalRaw !== '') {
        $parsed = @parse_url($canonicalRaw);
        if (is_array($parsed) && isset($parsed['scheme'], $parsed['host'])) {
            $canonical = $siteUrl . rtrim((string)($parsed['path'] ?? ''), '/');
            if ($canonical === $siteUrl) $canonical = $siteUrl;
        } else {
            $canonical = $siteUrl . '/' . ltrim($canonicalRaw, '/');
            $canonical = rtrim($canonical, '/');
            if ($canonical === '') $canonical = $siteUrl;
        }
    } else {
        $canonical = $defaultCanonical;
    }
    $metaDescription = trim((string)($__env->yieldContent('meta_description') ?: 'Desarrollo de páginas web en Lima y desarrollo de software a medida. +11 años creando soluciones para empresas: web, APIs, automatización e integraciones.'));
    $ogImage = url(asset('img/logo-systems-gg.png'));

    // Contacto (WhatsApp) reutilizado en footer + botón flotante
    $waPhoneDisplay = trim((string)($__env->yieldContent('wa_phone_display') ?: '+51 949 421 023'));
    $waPhoneDigits = preg_replace('/\D+/', '', $waPhoneDisplay);
    $waText = 'Hola, vengo desde la web de SystemsGG. Quisiera una cotización.';
    $waUrl = 'https://wa.me/' . $waPhoneDigits . '?text=' . rawurlencode($waText);
  @endphp

  <meta name="description" content="{{ $metaDescription }}" />
  <link rel="canonical" href="{{ $canonical }}" />
  <link rel="alternate" hreflang="es-PE" href="{{ $siteUrl }}" />

  <!-- Indexación (por defecto indexable; páginas específicas pueden sobreescribirlo) -->
  <meta name="robots" content="@yield('robots', 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1')" />
  <meta name="googlebot" content="@yield('googlebot', 'index, follow')" />

  <!-- Open Graph / Social -->
  <meta property="og:type" content="website" />
  <meta property="og:site_name" content="{{ $siteName }}" />
  <meta property="og:title" content="@yield('og_title', $__env->yieldContent('title', 'SystemsGG'))" />
  <meta property="og:description" content="{{ $metaDescription }}" />
  <meta property="og:url" content="{{ $canonical }}" />
  <meta property="og:locale" content="es_PE" />
  <meta property="og:image" content="{{ $ogImage }}" />

  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="@yield('og_title', $__env->yieldContent('title', 'SystemsGG'))" />
  <meta name="twitter:description" content="{{ $metaDescription }}" />
  <meta name="twitter:image" content="{{ $ogImage }}" />

  <meta name="color-scheme" content="light" />
  <meta name="theme-color" content="#ffffff" />

  <!-- ============================================================= -->
  <!-- OPTIMIZACIONES PARA PAGESPEED / GTMETRIX -->
  <!-- ============================================================= -->

  <!-- Preload CSS crítico -->
  @production
  @php
    $manifest = null;
    $cssPath = null;
    $manifestPath = public_path('build/.vite/manifest.json');
    if (!file_exists($manifestPath)) {
        $manifestPath = public_path('build/manifest.json');
    }
    if (file_exists($manifestPath)) {
        $manifest = json_decode(file_get_contents($manifestPath), true);
        if (isset($manifest['resources/css/app.css']['file'])) {
            $cssPath = asset('build/' . $manifest['resources/css/app.css']['file']);
        }
    }
  @endphp
  @if($cssPath)
  <link rel="preload" href="{{ $cssPath }}" as="style" />
  @endif
  @endproduction

  {{-- Assets (Vite) --}}
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <!-- CSS crítico inline para evitar FOUC y mejorar FCP -->
  <style>
    /* CSS Crítico - Above the fold */
    :root {
      /* Tema claro (fondo blanco) */
      /* Nota: usamos HEX/RGB (no OKLCH) para máxima compatibilidad */
      --c-bg: #ffffff;
      --c-surface: #f8fafc; /* slate-50 */
      --c-elev: #ffffff;
      --c-text: #0f172a; /* slate-900 */
      --c-muted: #475569; /* slate-600 */
      --c-border: #e2e8f0; /* slate-200 */

      /* Paleta (marca) */
      --c-primary: #2563eb; /* blue-600 */
      --c-primary-2: #7c3aed; /* violet-600 */
      --c-accent: #10b981; /* emerald-500 */

      --shadow-soft: 0 1px 2px rgba(0,0,0,.06), 0 14px 30px rgba(2,6,23,.10);
      --radius: 18px;
      color-scheme: light;

      /* =============================================================
         Landing theme tokens (modificar aquí para cambiar toda la landing)
         ============================================================= */
      --lp-bg: var(--c-bg);
      --lp-surface: var(--c-elev);
      --lp-elev: var(--c-elev);
      --lp-border: var(--c-border);

      --lp-primary: var(--c-primary);
      --lp-primary-2: var(--c-primary-2);
      --lp-accent: var(--c-accent);
      --lp-on-primary: #ffffff;

      --lp-glow-1: rgba(37,99,235,.18);
      --lp-glow-2: rgba(16,185,129,.14);
    }

    /* Modo oscuro - Variables CSS (igual que inicio.html) */
    :root.dark {
      --c-bg: #0b0f19; /* coal-900 */
      --c-surface: #0b0f19;
      --c-elev: #1f2937; /* coal-800 */
      --c-text: #f8fafc; /* coal-50 */
      --c-muted: #cbd5e1; /* coal-300 */
      --c-border: rgba(255, 255, 255, 0.1);
      color-scheme: dark;

      --lp-bg: var(--c-bg);
      --lp-surface: var(--c-elev);
      --lp-elev: var(--c-elev);
      --lp-border: var(--c-border);
    }
    
    /* Evitar CLS (Cumulative Layout Shift) */
    html { 
      scrollbar-gutter: stable; 
    }
    
    /* Alpine.js cloak */
    [x-cloak] { display: none !important; }
    
    /* Optimización de fuentes - evitar FOIT */
    @font-face {
      font-family: 'Inter';
      font-style: normal;
      font-weight: 400 700;
      font-display: swap;
      src: local('Inter');
    }
    
    /* Placeholder para contenido mientras carga */
    .skeleton {
      background: linear-gradient(90deg, var(--c-surface) 25%, var(--c-elev) 50%, var(--c-surface) 75%);
      background-size: 200% 100%;
      animation: skeleton-loading 1.5s infinite;
    }
    
    @keyframes skeleton-loading {
      0% { background-position: 200% 0; }
      100% { background-position: -200% 0; }
    }
    
    /* Reducir layout shifts en imágenes */
    img, video {
      max-width: 100%;
      height: auto;
    }
    
    /* Contener tamaño del header para evitar CLS */
    header {
      min-height: 64px;
    }
  </style>

  <!-- Datos estructurados (SEO) -->
  @php
    $businessName = trim((string)($__env->yieldContent('business_name') ?: $siteName));
    $businessTelephone = trim((string)($__env->yieldContent('business_telephone') ?: '+57 300 000 0000'));
    $businessCity = trim((string)($__env->yieldContent('business_city') ?: 'Lima'));
    $businessCountry = trim((string)($__env->yieldContent('business_country') ?: 'PE'));

    $jsonLd = [
      '@context' => 'https://schema.org',
      '@graph' => [
        [
          '@type' => 'Organization',
          '@id' => $siteUrl.'#organization',
          'name' => $businessName,
          'url' => $siteUrl,
          'logo' => $ogImage,
          'sameAs' => [],
        ],
        [
          '@type' => 'WebSite',
          '@id' => $siteUrl.'#website',
          'url' => $siteUrl,
          'name' => $siteName,
          'publisher' => ['@id' => $siteUrl.'#organization'],
          'inLanguage' => 'es-PE',
        ],
        [
          '@type' => 'LocalBusiness',
          '@id' => $siteUrl.'#localbusiness',
          'name' => $siteName,
          'image' => $ogImage,
          'url' => $siteUrl,
          'telephone' => $businessTelephone,
          'address' => [
            '@type' => 'PostalAddress',
            'addressLocality' => $businessCity,
            'addressCountry' => $businessCountry,
          ],
          'areaServed' => [
            ['@type' => 'City', 'name' => $businessCity],
            ['@type' => 'Country', 'name' => 'Perú'],
          ],
          'knowsAbout' => array_values(array_filter([
            trim((string)($__env->yieldContent('knows_about_1') ?: 'desarrollo de páginas web en Lima')),
            trim((string)($__env->yieldContent('knows_about_2') ?: 'desarrollo web')),
            trim((string)($__env->yieldContent('knows_about_3') ?: 'desarrollo de software a medida')),
            trim((string)($__env->yieldContent('knows_about_4') ?: 'integraciones y APIs')),
          ])),
        ],
        [
          '@type' => 'Service',
          '@id' => $siteUrl.'#service-1',
          'serviceType' => trim((string)($__env->yieldContent('service_1') ?: 'Desarrollo de páginas web en Lima')),
          'provider' => ['@id' => $siteUrl.'#localbusiness'],
          'areaServed' => ['@type' => 'City', 'name' => $businessCity],
        ],
        [
          '@type' => 'Service',
          '@id' => $siteUrl.'#service-2',
          'serviceType' => trim((string)($__env->yieldContent('service_2') ?: 'Desarrollo de software a medida')),
          'provider' => ['@id' => $siteUrl.'#localbusiness'],
          'areaServed' => ['@type' => 'Country', 'name' => 'Perú'],
        ],
      ],
    ];
  @endphp
  <script type="application/ld+json">{!! json_encode($jsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>

  <!-- Slot para recursos adicionales específicos de la página -->
  @yield('head')
</head>
<body class="min-h-screen bg-white text-coal-900 dark:bg-coal-900 dark:text-coal-50 font-sans">
  {{-- Preloader reutilizable (en marketing inicia oculto; se usa al enviar el formulario) --}}
  @include('components.preloader', ['startVisible' => false])

  <main>
    @yield('content')
  </main>

  <!-- Scripts no críticos cargados al final -->
  @yield('scripts')
</body>
</html>
