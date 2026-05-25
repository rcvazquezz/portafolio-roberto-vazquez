/**
 * src/js/app.js — Módulo principal de interactividad
 *
 * Responsabilidades:
 *   1. Registrar el componente Alpine.js `navController`:
 *      gestiona menú móvil, clase nav-scrolled y sección activa.
 *   2. Inicializar IntersectionObserver para animaciones de entrada
 *      (.reveal → .visible) al hacer scroll.
 *
 * Dependencia: Alpine.js (cargado vía CDN en footer.php con defer).
 * Este script debe cargarse DESPUÉS de Alpine (orden en footer.php).
 */


/* ════════════════════════════════════════════════════════════════
   1. COMPONENTE ALPINE: navController
   Gestiona todo el estado reactivo de la barra de navegación.
════════════════════════════════════════════════════════════════ */
document.addEventListener('alpine:init', () => {
  Alpine.data('navController', () => ({

    /** true cuando el menú móvil está desplegado */
    open: false,

    /** true cuando el usuario hizo scroll > 20px (activa nav-scrolled) */
    scrolled: false,

    /** ID de la sección actualmente en viewport */
    activeSection: 'inicio',

    /**
     * Alpine llama a init() automáticamente al montar el componente (x-init).
     * Aquí adjuntamos el listener de scroll con { passive: true } para no
     * bloquear el hilo principal durante el scroll.
     */
    init() {
      window.addEventListener('scroll', () => {
        this.scrolled = window.scrollY > 20;
        this.updateActiveSection();
      }, { passive: true });

      // Estado inicial correcto sin esperar al primer scroll
      this.updateActiveSection();
    },

    /**
     * Detecta qué sección ocupa el tercio superior del viewport
     * y actualiza activeSection para resaltar el nav-link correcto.
     * Se recorre en orden inverso: la última sección que supera el
     * umbral "gana" (comportamiento natural top-to-bottom).
     */
    updateActiveSection() {
      const secciones = [
        'inicio', 'sobre-mi', 'experiencia',
        'proyectos', 'educacion', 'toolkit', 'contacto',
      ];

      for (let i = secciones.length - 1; i >= 0; i--) {
        const el = document.getElementById(secciones[i]);
        if (!el) continue;

        const { top } = el.getBoundingClientRect();
        if (top <= window.innerHeight * 0.4) {
          this.activeSection = secciones[i];
          break;
        }
      }
    },
  }));
});


/* ════════════════════════════════════════════════════════════════
   2. INTERSECTION OBSERVER — Animaciones de entrada al scroll
   Observa todos los .reveal y añade .visible cuando el elemento
   entra en el viewport, disparando la transición CSS de input.css.
════════════════════════════════════════════════════════════════ */
(function initRevealObserver() {
  const observer = new IntersectionObserver(
    (entradas) => {
      entradas.forEach((entrada) => {
        if (!entrada.isIntersecting) return;

        // Aplicar delay escalonado si el elemento tiene data-delay
        const delay = entrada.target.dataset.delay ?? 0;
        setTimeout(() => {
          entrada.target.classList.add('visible');
        }, Number(delay));

        // Una vez animado, dejar de observar para no repetir
        observer.unobserve(entrada.target);
      });
    },
    {
      root:       null,
      rootMargin: '0px 0px -60px 0px', // inicia antes de que sea completamente visible
      threshold:  0.08,
    }
  );

  // Registrar todos los elementos marcados como reveal
  document.querySelectorAll('.reveal').forEach((el) => observer.observe(el));
})();
