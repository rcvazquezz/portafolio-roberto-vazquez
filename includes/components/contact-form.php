<?php
/**
 * includes/components/contact-form.php — Formulario de Contacto Profesional
 *
 * ═══════════════════════════════════════════════════════════════════════════
 * COMPONENTE SENIOR: Formulario de contacto escalable, accesible, documentado
 * ═══════════════════════════════════════════════════════════════════════════
 *
 * RESPONSABILIDADES:
 *   1. Capturar datos de contacto (nombre, email, mensaje)
 *   2. Validación client-side robusta (HTML5 + Alpine.js)
 *   3. Envío a backend (actualmente: Formspree)
 *   4. Feedback visual completo (loading, success, error)
 *   5. Accesibilidad WCAG AA (aria-*, role, aria-describedby)
 *
 * FLUJO DE USUARIO:
 *   1. Usuario carga página → Formulario visible con inputs en blanco
 *   2. Usuario rellena datos → Validación en tiempo real con @blur
 *   3. Si hay errores: mostrar mensajes de error rojos
 *   4. Si todo válido: botón "Enviar mensaje" habilitado
 *   5. Usuario clickea botón → @submit → handleSubmit()
 *   6. Spinner visible, botón disabled (previene envío múltiple)
 *   7. POST a Formspree en segundo plano
 *   8a. Éxito: Mensaje verde, form se limpia, auto-reset en 3s
 *   8b. Error: Mensaje rojo, botón re-habilitado, usuario puede reintentar
 *
 * CAMPOS CAPTURADOS:
 *   - name: string, 3-100 caracteres, requerido
 *   - email: string, formato email válido, requerido
 *   - message: string, 10-1000 caracteres, requerido
 *
 * ESCALABILIDAD:
 *   📋 Agregar campo nuevo:
 *      1. Copiar bloque <div class="form-group">...</div>
 *      2. Actualizar id, name, validación
 *      3. Agregar validación en Alpine.js (app.js)
 *
 *   🔧 Cambiar backend:
 *      1. Editar submitToBackend() en src/js/app.js
 *      2. Cambiar URL/método (POST, fetch body, headers)
 *      3. Ajustar parsing de respuesta
 *      4. El resto del componente sigue igual
 *
 *   🎨 Cambiar estilos:
 *      1. Actualizar clases Tailwind en form-container, form-input, form-submit-btn
 *      2. O editar variables CSS en src/css/input.css
 *      3. npm run build para compilar
 *
 * COMPATIBILIDAD:
 *   ✓ HTML5 validation: required, type="email", minlength, maxlength
 *   ✓ Fallback sin JavaScript: action="formspree-endpoint"
 *   ✓ Alpine.js v3.x: x-data, x-model, @submit, x-show, x-text
 *   ✓ Accesibilidad: aria-label, aria-required, aria-describedby, role="alert"
 *   ✓ Navegadores modernos: Chrome, Firefox, Safari, Edge (2022+)
 *
 * ESTADO DE ARTE (Senior Code):
 *   • Separación de responsabilidades (validación, envío, UI)
 *   • Documentación JSDoc completa
 *   • Sin código "mágico" o redundancias
 *   • Fácil de mantener y extender
 *   • Comentarios explican el "por qué", no solo el "qué"
 *
 * REFERENCIAS:
 *   • Formspree API: https://formspree.io/docs
 *   • Alpine.js: https://alpinejs.dev/
 *   • WCAG Accessibility: https://www.w3.org/WAI/WCAG21/quickref/
 *   • JavaScript Validation: src/js/app.js (contactForm component)
 */
?>

<form
  id="contact-form"
  x-data="contactForm()"
  @submit="handleSubmit($event)"
  action="https://formspree.io/f/mkoepwqb"
  method="POST"
  class="flex flex-col gap-4 p-6 rounded-card border border-graphite max-w-2xl mx-auto transition-all duration-250"
  novalidate
  aria-label="Formulario de contacto"
>

  <!-- ──────────────────────────────────────────────────────────
       SECCIÓN 1: CAMPOS DE FORMULARIO
       Estructura: .form-group agrupa label + input + error message
       Validación: @blur dispara validateField(), mostrar error si inválido
       Accesibilidad: aria-* para screen readers, role="alert" para errores
       ────────────────────────────────────────────────────────────── -->

  <!-- ── Campo: Nombre ────────────────────────────────────────── -->
  <div class="form-group">
    <label for="contact-name" class="form-label text-white">
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
  <div class="form-group">
    <label for="contact-email" class="form-label text-white">
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
  <div class="form-group">
    <label for="contact-message" class="form-label text-white">
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

  <!-- ──────────────────────────────────────────────────────────
       SECCIÓN 2: FEEDBACK VISUAL
       Éxito: Mostrado cuando submitStatus === 'success'
               - Transición suave (ease-out 300ms)
               - Auto-desaparece tras 3 segundos (controlado en Alpine)
       Error: Mostrado cuando submitStatus === 'error'
              - Mensaje dinámico desde servidor o fallback
              - Permite reintentos
       ────────────────────────────────────────────────────────────── -->

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

  <!--
    ── Botón de Envío ────────────────────────────────────────────────────
    Estilos: 100% clases Tailwind (sin dependencia de btn-* en input.css).

    Para cambiar el estilo del botón en el futuro, edita únicamente las
    clases del elemento <button> y <span> que están aquí abajo:

      · Color de fondo  → bg-violet (DEFAULT) / hover:bg-violet-dark (ver tailwind.config.js)
      · Color de texto  → text-white
      · Tamaño          → px-6 py-3 / text-sm / text-base
      · Forma           → rounded-lg / rounded-full / rounded-none
      · Ancho           → w-full / w-auto / max-w-xs

    Estado disabled: opacity-50 cursor-not-allowed (Alpine lo aplica
    automáticamente vía :disabled cuando isSubmitting || !isFormValid).
    ──────────────────────────────────────────────────────────────────── -->
  <button
    type="submit"
    :disabled="isSubmitting || !isFormValid"
    class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg font-semibold text-sm text-white bg-violet hover:bg-violet-dark transition-all duration-300"
    :class="{'opacity-50 cursor-not-allowed': isSubmitting || !isFormValid}"
    aria-label="Enviar formulario de contacto"
  >
    <!-- Spinner: visible solo durante isSubmitting -->
    <svg
      x-show="isSubmitting"
      class="animate-spin h-4 w-4 flex-shrink-0"
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

    <!-- Texto siempre visible: Alpine lo alterna entre los dos estados -->
    <span class="block" x-text="isSubmitting ? 'Enviando...' : 'Enviar mensaje'">Enviar mensaje</span>
  </button>

  <!-- ── Texto informativo de privacidad ───────────────────────── -->
  <p class="text-center text-xs text-muted mt-6">
    Tus datos se envían de forma segura a través de Formspree.
    <a href="https://formspree.io/privacy/" target="_blank" rel="noopener noreferrer" class="text-violet hover:underline">
      Ver política de privacidad
    </a>
  </p>

</form>
