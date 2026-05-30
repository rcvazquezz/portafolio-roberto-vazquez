<?php
/**
 * includes/sections/experience.php — Sección de Experiencia Laboral
 *
 * $experiencias viene de index.php → getExperiences() en portfolio-data.php.
 * Administra las experiencias desde el panel CMS: /admin/experiences
 *
 * Formato esperado de cada elemento:
 *   rol       string    Nombre del cargo
 *   empresa   string    Nombre de la empresa
 *   ubicacion string    Ciudad, País (vacío si no se registró en la BD)
 *   periodo   string    Período formateado ('Dic 2025 – Presente')
 *   bullets   string[]  Logros/responsabilidades (cada línea del campo descripción)
 */
?>

<section
  id="experiencia"
  class="section-wrapper bg-muted-bg"
  aria-label="Experiencia laboral"
>
  <div class="section-container">

    <!-- ── Encabezado ────────────────────────────────────────── -->
    <div class="reveal mb-8 md:mb-16">
      <span class="section-label">Trayectoria</span>
      <h2 class="section-heading">Experiencia</h2>
      <div class="section-divider"></div>
    </div>

    <!-- ── Timeline ──────────────────────────────────────────── -->
    <div class="max-w-2xl">
      <?php foreach ($experiencias as $exp) : ?>

        <article
          class="timeline-item reveal mb-14 last:mb-0"
          aria-label="Experiencia: <?= htmlspecialchars($exp['rol']) ?>"
        >

          <!-- Cabecera: Rol + periodo -->
          <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2 mb-3">
            <h3
              class="font-display font-bold text-noir"
              style="font-size: 1.0625rem; letter-spacing: -0.02em; line-height: 1.3;"
            >
              <?= htmlspecialchars($exp['rol']) ?>
            </h3>
            <span class="badge-violet flex-shrink-0 self-start">
              <?= htmlspecialchars($exp['periodo']) ?>
            </span>
          </div>

          <!-- Empresa y ubicación (ubicación solo si está disponible) -->
          <p class="text-muted text-sm font-medium mb-5">
            <?= htmlspecialchars($exp['empresa']) ?>
            <?php if (!empty($exp['ubicacion'])): ?>
              <span class="text-ui-border mx-2" aria-hidden="true">·</span>
              <?= htmlspecialchars($exp['ubicacion']) ?>
            <?php endif; ?>
          </p>

          <!-- Logros y responsabilidades -->
          <ul class="space-y-3" role="list">
            <?php foreach ($exp['bullets'] as $bullet) : ?>
              <li class="flex gap-3 text-sm text-muted leading-relaxed">
                <span class="text-violet flex-shrink-0 font-semibold mt-px" aria-hidden="true">→</span>
                <?= htmlspecialchars($bullet) ?>
              </li>
            <?php endforeach; ?>
          </ul>

        </article>

      <?php endforeach; ?>
    </div>

  </div>
</section>
