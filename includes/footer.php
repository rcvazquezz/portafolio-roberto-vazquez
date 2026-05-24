<?php
/**
 * includes/footer.php — Pie de página global
 *
 * Contiene:
 *   - Footer con links sociales y copyright
 *   - Alpine.js CDN (defer — debe ir antes de app.js)
 *   - Script app.js local
 *
 * ORDEN DE SCRIPTS IMPORTA:
 *   1. Alpine.js (defer) → registra el runtime
 *   2. app.js (defer)    → registra componentes con Alpine.data()
 *   Alpine procesa los x-data del DOM DESPUÉS de que ambos scripts
 *   hayan cargado, gracias al evento 'alpine:init'.
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
              href="https://github.com/"
              target="_blank"
              rel="noopener noreferrer"
              class="text-muted text-sm transition-colors duration-150 hover:text-white"
            >
              GitHub
            </a>
          </li>
          <li>
            <a
              href="https://devlink.nygaccesorios.com/"
              target="_blank"
              rel="noopener noreferrer"
              class="text-muted text-sm transition-colors duration-150 hover:text-white"
            >
              DevLink
            </a>
          </li>
          <li>
            <a
              href="mailto:rcvazquezantelo@gmail.com"
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
     SCRIPTS

     Orden de carga (importa):
       1. Lucide Icons  — síncrono, al final del body.
                          El DOM ya está parseado en este punto,
                          por lo que createIcons() encuentra todos
                          los elementos data-lucide y los reemplaza
                          por SVGs antes de que el usuario los vea.
       2. Alpine.js     — defer, registra el runtime reactivo.
       3. app.js        — defer, registra componentes Alpine +
                          IntersectionObserver para animaciones.
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

<!-- Alpine.js v3 — interactividad reactiva sin framework -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>

<!-- app.js — componentes Alpine + IntersectionObserver -->
<script defer src="src/js/app.js"></script>

</body>
</html>
