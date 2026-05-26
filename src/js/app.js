/**
 * src/js/app.js — Módulo principal de interactividad
 *
 * Responsabilidades:
 *   1. Registrar el componente Alpine.js `navController`:
 *      gestiona menú móvil, clase nav-scrolled y sección activa.
 *   2. Registrar el componente Alpine.js `contactForm`:
 *      validación, envío y feedback de formulario de contacto.
 *   3. Inicializar IntersectionObserver para animaciones de entrada
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

  /**
   * Componente Alpine: contactForm
   * Gestiona el estado reactivo del formulario de contacto.
   * Incluye validación client-side, prevención de envío múltiple,
   * y feedback visual (spinner, mensajes de éxito/error).
   */
  Alpine.data('contactForm', () => ({

    /** Datos del formulario capturados en tiempo real */
    formData: {
      name: '',
      email: '',
      message: '',
    },

    /** Errores de validación por campo */
    errors: {
      name: '',
      email: '',
      message: '',
    },

    /** true mientras se envía el formulario (previene envío múltiple) */
    isSubmitting: false,

    /** Estado del envío: 'idle' | 'success' | 'error' */
    submitStatus: 'idle',

    /** Mensaje de error detallado para mostrar al usuario */
    errorMessage: '',

    /**
     * Computed: true si el formulario es válido (todos los campos requeridos llenos).
     * Usado para habilitar/deshabilitar botón de envío.
     */
    get isFormValid() {
      return (
        this.formData.name.length >= 3 &&
        this.isValidEmail(this.formData.email) &&
        this.formData.message.length >= 10
      );
    },

    /**
     * Valida un campo individual y actualiza errors[fieldName].
     * Reglas:
     *   - name: mín 3 caracteres
     *   - email: formato válido
     *   - message: mín 10 caracteres
     *
     * @param {string} fieldName - 'name', 'email', o 'message'
     */
    validateField(fieldName) {
      switch (fieldName) {
        case 'name':
          if (!this.formData.name.trim()) {
            this.errors.name = 'El nombre es requerido.';
          } else if (this.formData.name.length < 3) {
            this.errors.name = 'El nombre debe tener al menos 3 caracteres.';
          } else {
            this.errors.name = '';
          }
          break;

        case 'email':
          if (!this.formData.email.trim()) {
            this.errors.email = 'El email es requerido.';
          } else if (!this.isValidEmail(this.formData.email)) {
            this.errors.email = 'Por favor, ingresa un email válido.';
          } else {
            this.errors.email = '';
          }
          break;

        case 'message':
          if (!this.formData.message.trim()) {
            this.errors.message = 'El mensaje es requerido.';
          } else if (this.formData.message.length < 10) {
            this.errors.message = 'El mensaje debe tener al menos 10 caracteres.';
          } else {
            this.errors.message = '';
          }
          break;
      }
    },

    /**
     * Valida que una cadena sea un email válido usando regex básico.
     * HTML5 también valida en el navegador (type="email").
     *
     * @param {string} email - Email a validar
     * @returns {boolean} true si el formato es válido
     */
    isValidEmail(email) {
      const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return regex.test(email);
    },

    /**
     * Maneja el envío del formulario.
     * Flujo:
     *   1. Previene comportamiento por defecto
     *   2. Valida todos los campos
     *   3. Si hay errores, retorna sin enviar
     *   4. Desabilita botón y muestra spinner
     *   5. Envía a Formspree via fetch
     *   6. Muestra éxito o error según respuesta
     *   7. Auto-reset tras 3 segundos
     *
     * @param {Event} e - Evento submit del formulario
     */
    async handleSubmit(e) {
      e.preventDefault();

      // Validar todos los campos
      this.validateField('name');
      this.validateField('email');
      this.validateField('message');

      if (!this.isFormValid) {
        this.submitStatus = 'idle';
        return;
      }

      this.isSubmitting = true;
      this.submitStatus = 'idle';

      try {
        const formElement = e.target;
        const formDataToSend = new FormData(formElement);

        const response = await fetch(formElement.action, {
          method: 'POST',
          body: formDataToSend,
          headers: {
            'Accept': 'application/json',
          },
        });

        if (response.ok) {
          this.submitStatus = 'success';
          this.resetForm();

          setTimeout(() => {
            this.submitStatus = 'idle';
          }, 3000);
        } else {
          const error = await response.json();
          this.submitStatus = 'error';
          this.errorMessage = error.error || 'No pudimos procesar tu mensaje. Intenta nuevamente.';
        }
      } catch (error) {
        this.submitStatus = 'error';
        this.errorMessage = 'Error de conexión. Verifica tu conexión a internet e intenta nuevamente.';
        console.error('Contact form error:', error);
      } finally {
        this.isSubmitting = false;
      }
    },

    /**
     * Limpia todos los campos del formulario.
     * Llamado automáticamente tras envío exitoso.
     */
    resetForm() {
      this.formData = { name: '', email: '', message: '' };
      this.errors = { name: '', email: '', message: '' };
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
