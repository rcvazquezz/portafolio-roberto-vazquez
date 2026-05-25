<?php
/**
 * includes/components/contact-toast.php — Smart Contact Toast
 *
 * Toast inteligente que aparece:
 *   1. Tras 8 segundos de carga inicial
 *   2. Al hacer scroll al 80% de la página
 *
 * Usa Alpine.js para el estado y transiciones CSS fluidas.
 * No se muestra hasta que se cumple una de las condiciones.
 */
?>

<div
  x-data="contactToast()"
  x-show="isVisible"
  x-transition:enter="transition ease-out duration-300"
  x-transition:enter-start="opacity-0 translate-y-4"
  x-transition:enter-end="opacity-100 translate-y-0"
  x-transition:leave="transition ease-in duration-200"
  x-transition:leave-start="opacity-100 translate-y-0"
  x-transition:leave-end="opacity-0 translate-y-4"
  class="fixed bottom-6 right-6 max-w-sm z-50"
  role="complementary"
  aria-label="Widget de contacto por WhatsApp"
>
  <!-- Card con glassmorphism -->
  <div class="backdrop-blur-md bg-noir/90 border border-violet/20 rounded-lg p-4 shadow-lg">

    <!-- Header: icono + texto -->
    <div class="flex items-start gap-3 mb-3">
      <div class="flex-shrink-0 w-5 h-5 text-violet mt-0.5 toast-icon-pulse">
        <i data-lucide="message-circle" class="w-5 h-5" aria-hidden="true"></i>
      </div>
      <div class="flex-1">
        <p class="text-sm text-cream font-medium leading-snug">
          ¿Tienes un proyecto en mente?
        </p>
        <p class="text-xs text-muted mt-1">
          Hablemos por WhatsApp
        </p>
      </div>

      <!-- Botón de cierre -->
      <button
        @click="isVisible = false"
        class="flex-shrink-0 w-5 h-5 flex items-center justify-center text-muted hover:text-cream transition-colors duration-200 rounded hover:bg-violet/10"
        aria-label="Cerrar widget"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>

    <!-- CTA Button (WhatsApp) -->
    <a
      href="https://wa.me/34625018707?text=<?= urlencode('Hola Roberto, vi tu portafolio y me interesa conversar sobre un proyecto.') ?>"
      target="_blank"
      rel="noopener noreferrer"
      class="block w-full bg-green-600 hover:bg-green-700 text-white text-sm font-semibold py-2.5 px-4 rounded transition-colors duration-200 text-center"
      aria-label="Abrir WhatsApp"
    >
      Conversar en WhatsApp
    </a>

  </div>
</div>

<script>
  function contactToast() {
    return {
      isVisible: false,
      scrollThreshold: 0.8,
      timeoutDuration: 8000,

      init() {
        // Condición 1: Mostrar tras 8 segundos
        setTimeout(() => {
          this.isVisible = true;
        }, this.timeoutDuration);

        // Condición 2: Mostrar al scroll al 80% de la página
        this.setupScrollDetection();
      },

      setupScrollDetection() {
        window.addEventListener('scroll', () => {
          const docHeight = document.documentElement.scrollHeight - window.innerHeight;
          const scrollPercent = docHeight > 0 ? window.scrollY / docHeight : 0;

          if (scrollPercent >= this.scrollThreshold && !this.isVisible) {
            this.isVisible = true;
          }
        });
      }
    }
  }
</script>
