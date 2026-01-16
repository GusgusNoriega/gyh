/** @type {import('tailwindcss').Config} */
export default {
  // En este proyecto el modo oscuro se controla por clase (p. ej. <html class="dark">)
  darkMode: 'class',

  theme: {
    extend: {
      // Mantiene el mismo stack usado antes en el CDN-config embebido
      fontFamily: {
        sans: [
          'Inter var',
          'Inter',
          'system-ui',
          '-apple-system',
          'Segoe UI',
          'Roboto',
          'Ubuntu',
          'Cantarell',
          'Noto Sans',
          'Helvetica Neue',
          'Arial',
          'Apple Color Emoji',
          'Segoe UI Emoji',
          'Segoe UI Symbol',
        ],
      },

      // `shadow-soft` se adapta por layout usando CSS var --shadow-soft
      boxShadow: {
        soft: 'var(--shadow-soft)',
      },
    },
  },
};

