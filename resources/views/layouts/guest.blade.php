<!doctype html>
<html lang="es" class="dark">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>@yield('title', 'Iniciar Sesión')</title>

  <!-- SEO: vistas guest (login, etc.) no deben indexarse -->
  <meta name="robots" content="noindex, nofollow" />
  <meta name="googlebot" content="noindex, nofollow" />

  <meta name="color-scheme" content="dark" />
  <meta name="theme-color" content="#1a1f35" />

  {{-- Assets (Vite) --}}
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <!-- CSS Crítico inline para evitar FOUC -->
  <style>
    :root {
      /* Fallback por defecto (oscuro) */
      --c-bg: oklch(0.17 0.02 255);
      --c-surface: oklch(0.21 0.02 255);
      --c-elev: oklch(0.25 0.02 255);
      --c-text: oklch(0.93 0.02 255);
      --c-muted: oklch(0.74 0.02 255);
      --c-border: oklch(0.35 0.02 255);
      --c-primary: oklch(0.72 0.14 260);
      --c-primary-2: oklch(0.66 0.16 285);
      --c-danger: oklch(0.68 0.21 25);

      /* Mantiene el look del `shadow-soft` que antes venía del config del CDN */
      --shadow-soft: 0 1px 2px rgba(0,0,0,.10), 0 12px 40px rgba(0,0,0,.35);

      --radius: 18px;
      color-scheme: dark;
    }
    
    [x-cloak] { display: none !important; }
    
    /* Evitar CLS */
    html { scrollbar-gutter: stable; }
    
    body {
      background-color: var(--c-bg);
      color: var(--c-text);
      min-height: 100vh;
    }
    
    /* Optimización de fuentes */
    @font-face {
      font-family: 'Inter';
      font-style: normal;
      font-weight: 400 700;
      font-display: swap;
      src: local('Inter');
    }
  </style>

</head>
<body class="min-h-screen bg-[var(--c-bg)] text-[var(--c-text)] font-sans">
  @yield('content')
</body>
</html>
