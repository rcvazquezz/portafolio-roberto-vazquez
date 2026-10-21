<?php
/**
 * includes/components/cookie-banner.php — Aviso de cookies + carga de Analytics
 *
 * Google Analytics solo se carga si el visitante pulsa "Aceptar".
 * La decisión se guarda en localStorage ('cookiesAnalitica' = 'aceptadas' |
 * 'rechazadas'). El enlace "Cookies" del footer vuelve a mostrar el aviso.
 *
 * Expone para otros componentes:
 *   window.cookiesPendientes() → true si aún no hay decisión
 *   evento 'cookies:decididas'  → se dispara al aceptar o rechazar
 *
 * Usa $config['google_analytics_id'] definido en header.php.
 */
$ga_id = $config['google_analytics_id'] ?? '';
?>

<div
  id="cookie-banner"
  class="fixed bottom-6 left-6 right-6 sm:right-auto sm:max-w-md z-50"
  role="dialog"
  aria-live="polite"
  aria-label="Aviso de cookies"
  hidden
>
  <div class="backdrop-blur-md bg-noir/90 border border-violet/20 rounded-lg p-4 shadow-lg">
    <p class="text-sm text-cream leading-relaxed">
      Uso Google Analytics para saber cuántas personas visitan el portfolio.
      Solo se activa si lo aceptas.
    </p>
    <div class="flex items-center gap-4 mt-4">
      <button type="button" data-cookies="aceptadas" class="btn-primary text-xs py-2 px-4">
        Aceptar
      </button>
      <button type="button" data-cookies="rechazadas"
              class="text-xs font-semibold text-muted hover:text-cream transition-colors duration-150">
        Rechazar
      </button>
    </div>
  </div>
</div>

<script>
  (function () {
    var CLAVE  = 'cookiesAnalitica';
    var GA_ID  = <?= json_encode($ga_id) ?>;
    var banner = document.getElementById('cookie-banner');

    /* localStorage puede lanzar excepción (modo privado, cookies bloqueadas) */
    function leer()           { try { return localStorage.getItem(CLAVE); } catch (e) { return null; } }
    function guardar(valor)   { try { localStorage.setItem(CLAVE, valor); } catch (e) {} }

    function cargarAnalytics() {
      if (!GA_ID || GA_ID === 'G-PLACEHOLDER' || window.gtag) return;

      var s = document.createElement('script');
      s.async = true;
      s.src = 'https://www.googletagmanager.com/gtag/js?id=' + encodeURIComponent(GA_ID);
      document.head.appendChild(s);

      window.dataLayer = window.dataLayer || [];
      window.gtag = function () { dataLayer.push(arguments); };
      gtag('js', new Date());
      gtag('config', GA_ID, {
        anonymize_ip: true,
        allow_google_signals: false,
        allow_ad_personalization_signals: false,
      });
    }

    window.cookiesPendientes = function () { return leer() === null; };

    /* Llamado desde el enlace "Cookies" del footer */
    window.mostrarAvisoCookies = function () { banner.hidden = false; };

    banner.addEventListener('click', function (e) {
      var boton = e.target.closest('[data-cookies]');
      if (!boton) return;

      var valor = boton.getAttribute('data-cookies');
      guardar(valor);
      banner.hidden = true;
      if (valor === 'aceptadas') cargarAnalytics();
      window.dispatchEvent(new Event('cookies:decididas'));
    });

    var decision = leer();
    if (decision === 'aceptadas') cargarAnalytics();
    if (decision === null) banner.hidden = false;
  })();
</script>
