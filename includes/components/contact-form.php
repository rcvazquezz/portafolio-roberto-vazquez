<?php
/**
 * includes/components/contact-form.php — Formulario de Contacto Minimalista
 *
 * Componente reutilizable que integra:
 *   - HTML5 semántico con validación nativa del navegador
 *   - Formspree como backend (sin servidor propio requerido)
 *   - Alpine.js para UX mejorada: spinner, mensajes de éxito/error
 *   - Estilos coherentes con el resto del portafolio
 *
 * Campos capturados:
 *   - Nombre (requerido, máx 100 caracteres)
 *   - Email (requerido, validación HTML5)
 *   - Mensaje (requerido, mín 10 caracteres, máx 1000)
 *
 * UX Feedback:
 *   - Spinner durante envío (previene envío múltiple)
 *   - Mensaje de éxito con auto-reset tras 3 segundos
 *   - Manejo de errores de red/servidor
 *   - Fallback funcional sin JavaScript (envío directo a Formspree)
 *
 * Escalabilidad:
 *   - Fácil agregar más campos (mantener estructura form-group)
 *   - Validación client-side extensible en Alpine
 *   - Endpoint configurable (actualmente: email directo en Formspree)
 */
?>

<form
  id="contact-form"
  x-data="contactForm()"
  @submit="handleSubmit($event)"
  action="https://formspree.io/f/rcvazquezantelo2006@gmail.com"
  method="POST"
  class="card p-8 max-w-2xl mx-auto border-violet/50 hover:border-violet transition-colors duration-250"
  novalidate
  aria-label="Formulario de contacto"
>

  <!-- ── Campo: Nombre ────────────────────────────────────────── -->
  <div class="form-group mb-6">
    <label for="contact-name" class="form-label">
      Nombre
      <span class="text-violet" aria-label="requerido">*</span>
    </label>
    <input
      id="contact-name"
      type="text"
      name="name"
      placeholder="Tu nombre completo"
      required
      minlength="3"
      maxlength="100"
      class="form-input w-full"
      x-model="formData.name"
      @blur="validateField('name')"
      aria-required="true"
      aria-describedby="error-name"
    >
    <span
      id="error-name"
      class="form-feedback text-red-500 text-sm mt-1 block"
      x-show="errors.name"
      x-text="errors.name"
      role="alert"
    ></span>
  </div>

  <!-- ── Campo: Email ─────────────────────────────────────────── -->
  <div class="form-group mb-6">
    <label for="contact-email" class="form-label">
      Email
      <span class="text-violet" aria-label="requerido">*</span>
    </label>
    <input
      id="contact-email"
      type="email"
      name="email"
      placeholder="tu@email.com"
      required
      class="form-input w-full"
      x-model="formData.email"
      @blur="validateField('email')"
      aria-required="true"
      aria-describedby="error-email"
    >
    <span
      id="error-email"
      class="form-feedback text-red-500 text-sm mt-1 block"
      x-show="errors.email"
      x-text="errors.email"
      role="alert"
    ></span>
  </div>

  <!-- ── Campo: Mensaje ───────────────────────────────────────── -->
  <div class="form-group mb-8">
    <label for="contact-message" class="form-label">
      Cuéntame tu proyecto
      <span class="text-violet" aria-label="requerido">*</span>
    </label>
    <textarea
      id="contact-message"
      name="message"
      placeholder="Describe brevemente tu proyecto, necesidades, o preguntas..."
      required
      minlength="10"
      maxlength="1000"
      rows="5"
      class="form-input w-full resize-none"
      x-model="formData.message"
      @blur="validateField('message')"
      aria-required="true"
      aria-describedby="error-message counter-message"
    ></textarea>

    <!-- Contador de caracteres (accesibilidad) -->
    <div class="flex items-center justify-between mt-2">
      <span
        id="error-message"
        class="form-feedback text-red-500 text-sm"
        x-show="errors.message"
        x-text="errors.message"
        role="alert"
      ></span>
      <span
        id="counter-message"
        class="form-feedback text-muted text-xs"
        x-text="`${formData.message.length} / 1000 caracteres`"
      ></span>
    </div>
  </div>

  <!-- ── Área de Feedback: Éxito ──────────────────────────────── -->
  <div
    x-show="submitStatus === 'success'"
    x-transition:enter="transition ease-out duration-300"
    x-transition:leave="transition ease-in duration-200"
    class="mb-6 p-4 rounded-card bg-green-50 border border-green-300 text-green-800"
    role="alert"
    aria-live="polite"
  >
    <div class="flex items-start gap-3">
      <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
      </svg>
      <div>
        <p class="font-semibold">¡Mensaje enviado con éxito!</p>
        <p class="text-sm mt-1">Me pondré en contacto dentro de las próximas 24 horas.</p>
      </div>
    </div>
  </div>

  <!-- ── Área de Feedback: Error ──────────────────────────────── -->
  <div
    x-show="submitStatus === 'error'"
    x-transition:enter="transition ease-out duration-300"
    x-transition:leave="transition ease-in duration-200"
    class="mb-6 p-4 rounded-card bg-red-50 border border-red-300 text-red-800"
    role="alert"
    aria-live="polite"
  >
    <div class="flex items-start gap-3">
      <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0-6a4 4 0 110 8 4 4 0 010-8z"/>
      </svg>
      <div>
        <p class="font-semibold">Error al enviar el mensaje</p>
        <p class="text-sm mt-1" x-text="errorMessage || 'Por favor, intenta nuevamente.'"></p>
      </div>
    </div>
  </div>

  <!-- ── Botón de Envío ───────────────────────────────────────── -->
  <button
    type="submit"
    :disabled="isSubmitting || !isFormValid"
    :class="isSubmitting && 'opacity-75 cursor-not-allowed'"
    class="form-submit-btn w-full font-semibold transition-opacity duration-200"
    aria-label="Enviar formulario de contacto"
  >
    <!-- Spinner durante envío -->
    <svg
      x-show="isSubmitting"
      class="animate-spin h-5 w-5 inline mr-2"
      fill="none"
      viewBox="0 0 24 24"
      aria-hidden="true"
    >
      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
      <path
        class="opacity-75"
        fill="currentColor"
        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
      ></path>
    </svg>

    <!-- Texto del botón dinámico -->
    <span x-text="isSubmitting ? 'Enviando...' : 'Enviar mensaje'">Enviar mensaje</span>
  </button>

  <!-- ── Texto informativo de privacidad ───────────────────────── -->
  <p class="text-center text-xs text-muted mt-6">
    Tus datos se envían de forma segura a través de Formspree.
    <a href="https://formspree.io/privacy/" target="_blank" rel="noopener noreferrer" class="text-violet hover:underline">
      Ver política de privacidad
    </a>
  </p>

</form>
