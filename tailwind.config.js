/** @type {import('tailwindcss').Config} */

/**
 * tailwind.config.js — Design Tokens del Portafolio
 *
 * Paleta:     Noir & Violet  (#0C0C0F base, #7C65F6 acento)
 * Tipografía: Syne (display) + Inter (body)
 * Cards:      Elevated — sombra multi-capa + hover translateY
 * Espaciado:  Base 8px
 *
 * IMPORTANTE: no editar los valores de color directamente aquí
 * sin actualizar también las CSS custom properties en input.css.
 */
module.exports = {
  content: [
    './index.php',
    './includes/**/*.php',
    './src/**/*.{js,ts}',
  ],

  theme: {
    // ── Sobreescribimos la fuente sans global ──────────────────────────────
    fontFamily: {
      sans:    ['Inter', 'sans-serif'],
      display: ['Syne', 'sans-serif'],
      body:    ['Inter', 'sans-serif'],
      mono:    ['ui-monospace', 'SFMono-Regular', 'Menlo', 'monospace'],
    },

    extend: {

      // ── PALETA A: Noir & Violet ─────────────────────────────────────────
      colors: {
        noir:     '#0C0C0F',   // Negro principal (hero bg, títulos)
        graphite: '#1A1A24',   // Superficies oscuras (cards modo dark)
        cream:    '#F2F1ED',   // Fondo principal de página (cálido, no puro blanco)
        ink:      '#111118',   // Texto principal (más cálido que negro puro)

        violet: {
          DEFAULT: '#7C65F6',  // Acento principal
          light:   '#EDE9FE',  // Fondo de badges, hover suave
          muted:   '#A78BFA',  // Acento secundario (textos sobre oscuro)
          dark:    '#5B45D4',  // Hover de botones primarios
        },

        // Tokens de superficie e interfaz
        surface:   '#FFFFFF',   // Fondo de cards (contrasta con cream)
        'ui-border': '#E4E3DF', // Bordes sutiles (levemente cálido)

        muted: {
          DEFAULT: '#6B6B78',  // Texto secundario
          bg:      '#F7F6F2',  // Fondo muted (secciones alternadas)
        },
      },

      // ── TIPOGRAFÍA (refuerzo explícito) ────────────────────────────────
      fontFamily: {
        display: ['Syne', 'sans-serif'],
        body:    ['Inter', 'sans-serif'],
      },

      // ── ESPACIADO: Escala 8px ────────────────────────────────────────────
      // Tailwind usa base de 4px; aquí añadimos los valores que faltan
      spacing: {
        '18':  '4.5rem',    // 72px
        '22':  '5.5rem',    // 88px
        '26':  '6.5rem',    // 104px
        '30':  '7.5rem',    // 120px
        '34':  '8.5rem',    // 136px
        '128': '32rem',     // 512px
        '144': '36rem',     // 576px
      },

      // ── ANCHO MÁXIMO ───────────────────────────────────────────────────
      maxWidth: {
        content: '1120px',
      },

      // ── RADIOS DE BORDE ────────────────────────────────────────────────
      borderRadius: {
        card:   '12px',
        button: '8px',
        badge:  '6px',
      },

      // ── SOMBRAS: Card Elevated (Propuesta A) ───────────────────────────
      boxShadow: {
        card:          '0 2px 8px rgba(0,0,0,0.06), 0 8px 32px rgba(0,0,0,0.04)',
        'card-hover':  '0 8px 24px rgba(0,0,0,0.10), 0 16px 48px rgba(0,0,0,0.08)',
        violet:        '0 4px 16px rgba(124, 101, 246, 0.25)',
        'violet-lg':   '0 8px 32px rgba(124, 101, 246, 0.35)',
        'inner-sm':    'inset 0 1px 3px rgba(0,0,0,0.08)',
      },

      // ── CURVAS DE ANIMACIÓN ────────────────────────────────────────────
      transitionTimingFunction: {
        spring: 'cubic-bezier(0.34, 1.56, 0.64, 1)',  // Rebote suave
        smooth: 'cubic-bezier(0.4, 0, 0.2, 1)',        // Material ease
        out:    'cubic-bezier(0.0, 0, 0.2, 1)',         // Deceleration
      },

      transitionDuration: {
        '250': '250ms',
        '350': '350ms',
        '400': '400ms',
      },

      // ── KEYFRAMES DE ANIMACIÓN ─────────────────────────────────────────
      keyframes: {
        'fade-up': {
          from: { opacity: '0', transform: 'translateY(20px)' },
          to:   { opacity: '1', transform: 'translateY(0)' },
        },
        'fade-in': {
          from: { opacity: '0' },
          to:   { opacity: '1' },
        },
        'scale-in': {
          from: { opacity: '0', transform: 'scale(0.96)' },
          to:   { opacity: '1', transform: 'scale(1)' },
        },
        'slide-in-left': {
          from: { opacity: '0', transform: 'translateX(-24px)' },
          to:   { opacity: '1', transform: 'translateX(0)' },
        },
        shimmer: {
          '0%':   { backgroundPosition: '-200% 0' },
          '100%': { backgroundPosition: '200% 0' },
        },
        'pulse-dot': {
          '0%, 100%': { opacity: '1', transform: 'scale(1)' },
          '50%':      { opacity: '0.6', transform: 'scale(0.8)' },
        },
        float: {
          '0%, 100%': { transform: 'translateY(0)' },
          '50%':      { transform: 'translateY(-8px)' },
        },
      },

      // ── CLASES DE ANIMACIÓN ────────────────────────────────────────────
      animation: {
        'fade-up':       'fade-up 0.6s cubic-bezier(0.4, 0, 0.2, 1) both',
        'fade-in':       'fade-in 0.4s ease both',
        'scale-in':      'scale-in 0.3s cubic-bezier(0.4, 0, 0.2, 1) both',
        'slide-in-left': 'slide-in-left 0.5s cubic-bezier(0.4, 0, 0.2, 1) both',
        shimmer:         'shimmer 2.5s linear infinite',
        'pulse-dot':     'pulse-dot 2s ease infinite',
        float:           'float 3s ease-in-out infinite',
      },
    },
  },

  plugins: [],
};
