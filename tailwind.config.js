/** @type {import('tailwindcss').Config} */
export default {
  // En este proyecto el modo oscuro se controla por clase (p. ej. <html class="dark">)
  darkMode: 'class',

  theme: {
    extend: {
      colors: {
        // Paleta usada por la plantilla `inicio.html`
        brand: {
          50: '#fff1f1',
          100: '#ffe1e1',
          200: '#ffc7c7',
          300: '#ff9a9a',
          400: '#ff6b6b',
          500: '#d84a4a',
          600: '#B63232',
          700: '#8f2424',
          800: '#6f1d1d',
          900: '#4d1414',
        },
        coal: {
          50: '#f8fafc',
          100: '#f1f5f9',
          200: '#e2e8f0',
          300: '#cbd5e1',
          400: '#94a3b8',
          500: '#64748b',
          600: '#475569',
          700: '#334155',
          800: '#1f2937',
          900: '#0b0f19',
        },
      },

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
