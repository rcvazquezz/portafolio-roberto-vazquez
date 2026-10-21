<?php
/**
 * includes/components/contact-toast.php — Aviso flotante de WhatsApp
 *
 * Aparece como mucho una vez por visita (sessionStorage), cuando ocurre
 * lo primero de:
 *   1. 8 segundos tras la carga
 *   2. Scroll al 80% de la página
 *
 * No aparece mientras el aviso de cookies esté pendiente (espera al evento
 * `cookies:decididas`), y una vez cerrado no vuelve a mostrarse.
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
  style="display:none;"
  role="complementary"
  aria-label="Contacto por WhatsApp"
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
          ¿Buscas desarrollador?
        </p>
        <p class="text-xs text-muted mt-1">
          Escríbeme por WhatsApp
        </p>
      </div>

      <!-- Botón de cierre -->
      <button
        @click="close()"
        class="flex-shrink-0 w-5 h-5 flex items-center justify-center text-muted hover:text-cream transition-colors duration-200 rounded hover:bg-violet/10"
        aria-label="Cerrar aviso de WhatsApp"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>

    <!-- CTA Button (WhatsApp) -->
    <a
      href="https://wa.me/34625018707?text=<?= rawurlencode('Hola Roberto, vi tu portfolio y me gustaría hablar sobre una vacante') ?>"
      target="_blank"
      rel="noopener noreferrer"
      @click="close()"
      class="block w-full bg-green-600 hover:bg-green-700 text-white text-sm font-semibold py-2.5 px-4 rounded transition-colors duration-200 text-center"
    >
      Escribir por WhatsApp
    </a>

  </div>
</div>

<script>
  function contactToast() {
    const CLAVE = 'toastWhatsappMostrado';

    /* sessionStorage puede lanzar excepción (modo privado, cookies bloqueadas) */
    const yaMostrado = () => { try { return sessionStorage.getItem(CLAVE) === '1'; } catch (e) { return false; } };
    const marcar     = () => { try { sessionStorage.setItem(CLAVE, '1'); } catch (e) {} };

    return {
      isVisible: false,
      scrollThreshold: 0.8,
      timeoutDuration: 8000,

      init() {
        if (yaMostrado()) return;

        /* Esperar a que se responda el aviso de cookies para no tapar uno con otro */
        if (window.cookiesPendientes && window.cookiesPendientes()) {
          window.addEventListener('cookies:decididas', () => this.programar(), { once: true });
        } else {
          this.programar();
        }
      },

      programar() {
        const timer = setTimeout(() => this.show(), this.timeoutDuration);

        const onScroll = () => {
          const docHeight = document.documentElement.scrollHeight - window.innerHeight;
          const scrollPercent = docHeight > 0 ? window.scrollY / docHeight : 0;
          if (scrollPercent >= this.scrollThreshold) this.show();
        };
        window.addEventListener('scroll', onScroll, { passive: true });

        this.limpiar = () => {
          clearTimeout(timer);
          window.removeEventListener('scroll', onScroll);
        };
      },

      /* Solo una vez por visita: al mostrarse deja de escuchar timer y scroll */
      show() {
        if (yaMostrado()) return;
        marcar();
        this.limpiar?.();
        this.isVisible = true;
      },

      close() {
        this.isVisible = false;
      },
    }
  }
</script>
