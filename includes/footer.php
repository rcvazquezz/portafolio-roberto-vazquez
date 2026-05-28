<?php
/**
 * includes/footer.php — Pie de página global
 *
 * Contiene:
 *   - Footer con links sociales y copyright
 *   - app.js local (defer, ANTES de Alpine)
 *   - Alpine.js CDN (defer, DESPUÉS de app.js)
 *
 * ORDEN DE SCRIPTS IMPORTA (Alpine v3):
 *   1. app.js (defer)    → registra listener alpine:init + Alpine.data()
 *   2. Alpine.js (defer) → dispara alpine:init → componentes ya registrados
 *   Si Alpine va primero, dispara alpine:init antes de que app.js escuche.
 */
?>

<!-- ════════════════════════════════════════════════════════════
     FOOTER
════════════════════════════════════════════════════════════════ -->
<footer class="bg-noir" role="contentinfo" aria-label="Pie de página">
  <div class="section-container py-12 md:py-16">
    <div class="flex flex-col md:flex-row items-center justify-between gap-8">

      <!-- ── Marca ─────────────────────────────────────────────── -->
      <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-graphite flex items-center justify-center flex-shrink-0">
          <span class="font-display font-bold text-xs text-white" aria-hidden="true">RV</span>
        </div>
        <p class="text-sm text-muted font-medium">Roberto Carlos Vázquez Antelo</p>
      </div>

      <!-- ── Links externos ────────────────────────────────────── -->
      <nav aria-label="Links del footer">
        <ul class="flex items-center gap-6" role="list">
          <li>
            <a
              href="https://github.com/rcvazquezz"
              target="_blank"
              rel="noopener noreferrer"
              aria-label="Perfil de GitHub de Roberto Vázquez"
              class="text-muted text-sm transition-colors duration-150 hover:text-white"
            >
              GitHub
            </a>
          </li>
          <li>
            <a
              href="https://www.linkedin.com/in/rcvazquezantelo/"
              target="_blank"
              rel="noopener noreferrer"
              aria-label="Perfil de LinkedIn de Roberto Vázquez"
              class="text-muted text-sm transition-colors duration-150 hover:text-white"
            >
              LinkedIn
            </a>
          </li>
          <li>
            <a
              href="https://devlink.nygaccesorios.com/"
              target="_blank"
              rel="noopener noreferrer"
              aria-label="DevLink — proyecto de Roberto Vázquez"
              class="text-muted text-sm transition-colors duration-150 hover:text-white"
            >
              DevLink
            </a>
          </li>
          <li>
            <a
              href="mailto:rcvazquezantelo2006@gmail.com"
              aria-label="Enviar email a Roberto Vázquez"
              class="text-muted text-sm transition-colors duration-150 hover:text-white"
            >
              Email
            </a>
          </li>
        </ul>
      </nav>

      <!-- ── Copyright ─────────────────────────────────────────── -->
      <p class="text-xs text-muted">
        &copy; <?= date('Y') ?> · PHP + Tailwind CSS
      </p>

    </div>
  </div>
</footer>

<!-- ════════════════════════════════════════════════════════════
     COMPONENTES GLOBALES
════════════════════════════════════════════════════════════════ -->
<?php require_once APP_ROOT . '/includes/components/contact-toast.php'; ?>

<!-- ════════════════════════════════════════════════════════════
     SCRIPTS

     Orden de carga (importa):
       1. Lucide Icons  — síncrono, al final del body.
                          El DOM ya está parseado en este punto,
                          por lo que createIcons() encuentra todos
                          los elementos data-lucide y los reemplaza
                          por SVGs antes de que el usuario los vea.
       2. app.js        — defer, registra listener alpine:init +
                          Alpine.data() + IntersectionObserver.
       3. Alpine.js     — defer, dispara alpine:init, procesa x-data.
════════════════════════════════════════════════════════════════ -->

<!-- Lucide Icons UMD — síncrono para evitar FOIC (Flash of Icon Content) -->
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
<script>
  // El DOM ya está disponible aquí (script al final del body).
  // createIcons() sustituye cada <i data-lucide="nombre"> por su SVG.
  if (typeof lucide !== 'undefined') {
    lucide.createIcons();
  }
</script>

<!-- ──────────────────────────────────────────────────────────────
     GOOGLE ANALYTICS 4 (gtag.js)

     Tracking automático de:
       • Pageviews: cada carga de página
       • Scroll depth: hasta qué punto scrolleó el usuario
       • Eventos personalizados: clicks en CTAs, descargas, etc.

     Configuración:
       • ID desde includes/header.php $config['google_analytics_id']
       • Privacidad: NO rastrea datos personales
       • Compatible: todos los navegadores modernos

     Documentación: https://developers.google.com/analytics/devguides/collection/gtagjs
────────────────────────────────────────────────────────────────── -->
<?php if (!empty($config['google_analytics_id']) && $config['google_analytics_id'] !== 'G-PLACEHOLDER'): ?>
  <script async src="https://www.googletagmanager.com/gtag/js?id=<?= htmlspecialchars($config['google_analytics_id']) ?>"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    // Configuración de Google Analytics 4
    gtag('config', '<?= htmlspecialchars($config['google_analytics_id']) ?>', {
      'anonymize_ip': true,  // No guardar IP completa (privacidad)
      'allow_google_signals': false,  // Respetar DNT (Do Not Track)
      'allow_ad_personalization_signals': false,  // Sin personalización ads
    });
  </script>
<?php endif; ?>

<!-- app.js — componentes Alpine + IntersectionObserver
     DEBE ir antes de Alpine para que alpine:init esté escuchando
     cuando Alpine dispare el evento al inicializar. -->
<script defer src="src/js/app.js"></script>

<!-- Alpine.js v3 — interactividad reactiva sin framework -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>

</body>
</html>
