<?php
/**
 * includes/sections/education.php — Formación Académica
 *
 * $formacion viene de index.php → getEducation() en portfolio-data.php.
 * Administra la formación desde el panel CMS: /admin/education
 *
 * Formato esperado de cada elemento:
 *   titulo       string    Título completo (degree + ' en ' + field)
 *   institucion  string    Nombre de la institución
 *   ubicacion    string    Ciudad (vacío si no se registró en la BD)
 *   periodo      string    'Año – Año' o 'Año – Presente'
 *   descripcion  ?string   Descripción opcional
 *   categorias   array     Grupos de materias ([] si no aplica)
 *   gancho       ?string   Nota técnica al pie de la card (null si no aplica)
 *
 * Certificaciones: gestionadas también desde /admin/education en el futuro.
 * Por ahora se renderiza solo si el array no está vacío.
 */

/* La sección de certificaciones se ocultará si no hay datos */
$certificaciones = [];
?>

<section
  id="educacion"
  class="section-wrapper bg-muted-bg"
  aria-label="Formación académica"
>
  <div class="section-container">

    <!-- ── Encabezado ──────────────────────────────────────────── -->
    <div class="reveal mb-16">
      <span class="section-label">Base académica</span>
      <h2 class="section-heading">Formación</h2>
      <div class="section-divider"></div>
    </div>

    <!-- ── Cards de formación ─────────────────────────────────── -->
    <div class="max-w-2xl space-y-6">
      <?php foreach ($formacion as $edu) : ?>

        <article
          class="card p-6 reveal"
          aria-label="<?= htmlspecialchars($edu['titulo']) ?>"
        >

          <!-- Título + periodo -->
          <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-3">
            <h3
              class="font-display font-bold text-noir"
              style="font-size: 1rem; letter-spacing: -0.02em; line-height: 1.35;"
            >
              <?= htmlspecialchars($edu['titulo']) ?>
            </h3>
            <!-- badge-dark (graphite + violet) contrasta sobre el fondo blanco de la card -->
            <span class="badge-dark flex-shrink-0 self-start">
              <?= htmlspecialchars($edu['periodo']) ?>
            </span>
          </div>

          <!-- Institución y ubicación (ubicación solo si está disponible) -->
          <p class="text-muted text-sm font-medium mb-3">
            <?= htmlspecialchars($edu['institucion']) ?>
            <?php if (!empty($edu['ubicacion'])): ?>
              <span class="text-ui-border mx-2" aria-hidden="true">·</span>
              <?= htmlspecialchars($edu['ubicacion']) ?>
            <?php endif; ?>
          </p>

          <!-- Descripción de valor -->
          <?php if (!empty($edu['descripcion'])) : ?>
            <p class="text-muted text-sm leading-relaxed mb-5">
              <?= htmlspecialchars($edu['descripcion']) ?>
            </p>
          <?php endif; ?>

          <!-- Áreas de conocimiento agrupadas por categoría -->
          <?php if (!empty($edu['categorias'])) : ?>
            <div class="space-y-3 mb-2" aria-label="Áreas de conocimiento">
              <?php foreach ($edu['categorias'] as $categoria) : ?>
                <div>
                  <p class="text-xs font-semibold tracking-widest uppercase text-muted mb-2">
                    <?= htmlspecialchars($categoria['nombre']) ?>
                  </p>
                  <div class="flex flex-wrap gap-2">
                    <?php foreach ($categoria['items'] as $item) : ?>
                      <span class="badge-neutral"><?= htmlspecialchars($item) ?></span>
                    <?php endforeach; ?>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <!-- Gancho técnico al pie de la card -->
          <?php if (!empty($edu['gancho'])) : ?>
            <div class="mt-5 pt-4 border-t border-ui-border">
              <p class="text-xs text-muted">
                <span class="text-violet font-semibold" aria-hidden="true">→</span>
                <?= htmlspecialchars($edu['gancho']) ?>
              </p>
            </div>
          <?php endif; ?>

        </article>

      <?php endforeach; ?>
    </div>

    <!-- ── Certificaciones (se renderiza solo si hay datos) ──── -->
    <?php if (!empty($certificaciones)) : ?>
      <div class="mt-16 max-w-2xl">
        <h3 class="font-display font-semibold text-noir text-lg mb-8 reveal">
          Certificaciones
        </h3>
        <div class="grid sm:grid-cols-2 gap-4">
          <?php foreach ($certificaciones as $cert) : ?>
            <div class="card p-5 reveal">
              <p class="font-semibold text-noir text-sm">
                <?= htmlspecialchars($cert['nombre']) ?>
              </p>
              <p class="text-muted text-xs mt-1">
                <?= htmlspecialchars($cert['emisor']) ?> · <?= htmlspecialchars($cert['periodo']) ?>
              </p>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>

  </div>
</section>
