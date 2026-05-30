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
   *
   * ═══════════════════════════════════════════════════════════════════════════
   * GESTIÓN PROFESIONAL DEL FORMULARIO DE CONTACTO
   * ═══════════════════════════════════════════════════════════════════════════
   *
   * RESPONSABILIDADES:
   *   1. Validación client-side (HTML5 + reglas personalizadas)
   *   2. Gestión de estado reactivo (formData, errors, states)
   *   3. Manejo de eventos (@submit, @blur)
   *   4. Feedback visual (loading, success, error)
   *   5. Comunicación con backend (aislada en submitToBackend)
   *
   * ARQUITECTURA:
   *   • Estado: formData (inputs), errors (validación), submitStatus
   *   • Métodos de validación: validateField, isValidEmail
   *   • Handlers de eventos: handleSubmit
   *   • Backend aislado: submitToBackend() — fácil cambiar proveedor
   *   • Utilidades: resetForm
   *
   * FLUJO:
   *   1. Usuario completa campo → @blur → validateField()
   *   2. Errores: mostrados en tiempo real (x-show + x-text)
   *   3. Form válido: botón habilitado (computed property isFormValid)
   *   4. Usuario submit → handleSubmit() →
   *      a) Revalida
   *      b) Delega a submitToBackend() (aisla lógica externa)
   *      c) Muestra spinner, desabilita botón
   *      d) Maneja respuesta: éxito o error
   *      e) Auto-reset o reintentos
   *
   * ESCALABILIDAD:
   *   🔧 Cambiar backend (Formspree → Supabase/Slack/Email):
   *      1. Editar solo submitToBackend()
   *      2. URL, método POST/PUT, headers
   *      3. Parsing de respuesta
   *      4. El resto del componente sigue igual ✓
   *
   *   📋 Agregar campo:
   *      1. formData: agregar propiedad
   *      2. errors: agregar propiedad
   *      3. validateField: agregar case
   *      4. HTML: agregar div.form-group
   *
   *   🎨 Cambiar estilos:
   *      1. CSS en src/css/input.css
   *      2. Clases Tailwind: .form-container, .form-input, .form-submit-btn
   *
   * REFERENCIAS:
   *   • Componente HTML: includes/components/contact-form.php
   *   • Estilos: src/css/input.css (.form-* classes)
   *   • Backend actual: Formspree (https://formspree.io/)
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
     * Valida un campo individual según reglas específicas.
     *
     * REGLAS DE VALIDACIÓN:
     *   - name:
     *       ✓ Requerido (no vacío)
     *       ✓ Mínimo 3 caracteres (nombre completo realista)
     *       ✓ Máximo 100 caracteres (evitar spam)
     *
     *   - email:
     *       ✓ Requerido (no vacío)
     *       ✓ Formato válido (regex básico + HTML5 type="email")
     *       ✓ Permite reply (contacto es bidireccional)
     *
     *   - message:
     *       ✓ Requerido (no vacío)
     *       ✓ Mínimo 10 caracteres (evitar mensajes triviales)
     *       ✓ Máximo 1000 caracteres (mantener conciso)
     *
     * COMPORTAMIENTO:
     *   • Llamado en @blur (cuando usuario sale del input)
     *   • Actualiza errors[fieldName] (muestra/oculta mensaje rojo)
     *   • Recalcula isFormValid (habilita/desabilita botón)
     *   • NO previene envío (validación en handleSubmit() es "guardia")
     *
     * @param {string} fieldName - Campo a validar: 'name' | 'email' | 'message'
     *
     * @example
     *   this.validateField('email')
     *   // Si inválido: this.errors.email = "Por favor, ingresa un email válido."
     *   // Si válido: this.errors.email = ""
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
     * Valida formato de email usando regex.
     *
     * WHY: Double-check client-side. HTML5 type="email" también valida,
     *      pero regex nos permite mensaje de error personalizado.
     *
     * REGEX: /^[^\s@]+@[^\s@]+\.[^\s@]+$/
     *   - [^\s@]+    : mín 1 carácter (no espacios, no @)
     *   - @          : separador requerido
     *   - [^\s@]+    : mín 1 carácter (no espacios, no @)
     *   - \.         : punto literal (requerido para TLD)
     *   - [^\s@]+    : mín 1 carácter (no espacios, no @)
     *
     *   Ejemplos válidos: user@example.com, john+tag@domain.co.uk
     *   Ejemplos inválidos: user@, @domain.com, user.domain.com
     *
     * @param {string} email - String a validar
     * @returns {boolean} true si cumple patrón regex
     */
    isValidEmail(email) {
      const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return regex.test(email);
    },

    /**
     * Maneja el envío del formulario.
     *
     * FLUJO:
     *   1. Previene comportamiento por defecto (e.preventDefault)
     *   2. Valida todos los campos (validateField para cada uno)
     *   3. Si hay errores, retorna sin procesar
     *   4. Marca como enviando (isSubmitting = true, muestra spinner)
     *   5. DELEGA a submitToBackend() (separación de responsabilidades)
     *   6. Maneja respuesta: éxito (resetForm + auto-reset) o error
     *   7. SIEMPRE limpia estado de envío en finally
     *
     * @param {Event} e - Evento submit del formulario
     *
     * NOTA: La lógica de fetch está en submitToBackend() para facilitar
     *       cambios de backend sin tocar esta función.
     */
    async handleSubmit(e) {
      e.preventDefault();

      // Validar todos los campos
      this.validateField('name');
      this.validateField('email');
      this.validateField('message');

      // Abortar si hay errores de validación
      if (!this.isFormValid) {
        this.submitStatus = 'idle';
        return;
      }

      // Indicar que se está enviando (spinner visible, botón disabled)
      this.isSubmitting = true;
      this.submitStatus = 'idle';

      try {
        // RESPONSABILIDAD: submitToBackend maneja SOLO la comunicación HTTP
        // Esta función se encarga de la lógica de flujo y feedback
        const formElement = e.target;
        const result = await this.submitToBackend(formElement);

        if (result.ok) {
          // Éxito: limpiar form y mostrar mensaje
          this.submitStatus = 'success';
          this.resetForm();

          // Auto-reset de mensaje tras 3 segundos
          setTimeout(() => {
            this.submitStatus = 'idle';
          }, 3000);
        } else {
          // Error: permitir reintentos
          this.submitStatus = 'error';
          this.errorMessage = result.error || 'No pudimos procesar tu mensaje. Intenta nuevamente.';
        }
      } catch (error) {
        // Error inesperado (no capturado en submitToBackend)
        this.submitStatus = 'error';
        this.errorMessage = 'Error inesperado. Por favor, intenta nuevamente.';
        console.error('Contact form error:', error);
      } finally {
        // SIEMPRE limpiar estado de envío (previene spinner pegado)
        this.isSubmitting = false;
      }
    },

    /**
     * Envía datos al backend (Formspree actualmente).
     *
     * RESPONSABILIDAD ÚNICA: Comunicación HTTP con proveedor externo
     *
     * PARA CAMBIAR BACKEND:
     *   1. Actualizar endpoint (URL/método)
     *   2. Cambiar formato de datos (FormData vs JSON vs GraphQL)
     *   3. Ajustar parsing de respuesta
     *   4. SIN tocar handleSubmit() ni validación ✓
     *
     * @param {HTMLFormElement} formElement - El elemento <form> del DOM
     * @returns {Promise<{ok: boolean, error?: string}>}
     *   - ok: true si envío exitoso
     *   - error: mensaje si falla, undefined si ok=true
     *
     * @example
     *   const result = await this.submitToBackend(formElement);
     *   if (result.ok) { ... } else { console.log(result.error); }
     */
    async submitToBackend(formElement) {
      try {
        // Preparar datos: FormData captura name, email, message del HTML
        const formDataToSend = new FormData(formElement);

        // Endpoint: Formspree (email directo sin form ID previo)
        const endpoint = formElement.action; // https://formspree.io/f/email@example.com

        // POST a Formspree con Accept JSON (para respuesta JSON)
        const response = await fetch(endpoint, {
          method: 'POST',
          body: formDataToSend,
          headers: {
            'Accept': 'application/json',
          },
        });

        // Evaluar respuesta
        if (response.ok) {
          return { ok: true };
        }

        // Error del servidor o Formspree
        let errorMessage = 'Error del servidor';
        try {
          const error = await response.json();
          errorMessage = error.error || errorMessage;
        } catch (e) {
          // Si response.json() falla, usar mensaje genérico
          errorMessage = `Error HTTP ${response.status}`;
        }

        return { ok: false, error: errorMessage };
      } catch (error) {
        // Error de red o parsing
        return {
          ok: false,
          error: 'Error de conexión. Verifica tu internet e intenta nuevamente.',
        };
      }
    },

    /**
     * Limpia todos los campos del formulario.
     *
     * CUANDO SE LLAMA:
     *   • Automáticamente en handleSubmit() tras éxito
     *   • Solo después de submitToBackend() exitoso
     *   • Antes de mostrar mensaje de éxito
     *
     * QUÉ HACE:
     *   • Vacía formData (name, email, message)
     *   • Vacía errors (mensaje rojos desaparecen)
     *   • Inputs vuelven a estar en blanco (bind x-model)
     *   • Usuario puede enviar otro mensaje inmediatamente
     *
     * WHY: UX clara: "gracias, ahora puedes enviar otro mensaje"
     *      Previene re-envíos accidentales del mismo mensaje
     */
    resetForm() {
      this.formData = { name: '', email: '', message: '' };
      this.errors = { name: '', email: '', message: '' };
    },
  }));
});


/* ════════════════════════════════════════════════════════════════
   2. SMOOTH SCROLL SIN HASH — Mantiene la URL limpia
   Delegación en document: intercepta cualquier click en links #ancla,
   hace el scroll manualmente y resetea la URL con replaceState.
════════════════════════════════════════════════════════════════ */
document.addEventListener('click', (e) => {
  const link = e.target.closest('a[href^="#"]');
  if (!link) return;

  const targetId = link.getAttribute('href').slice(1);

  if (!targetId) {
    e.preventDefault();
    window.scrollTo({ top: 0, behavior: 'smooth' });
    history.replaceState(null, '', location.pathname);
    return;
  }

  const target = document.getElementById(targetId);
  if (!target) return;

  e.preventDefault();
  const navHeight = document.querySelector('nav')?.offsetHeight ?? 64;
  const top = target.getBoundingClientRect().top + window.scrollY - navHeight;
  window.scrollTo({ top, behavior: 'smooth' });
  history.replaceState(null, '', location.pathname);
});


/* ════════════════════════════════════════════════════════════════
   3. INTERSECTION OBSERVER — Animaciones de entrada al scroll
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
