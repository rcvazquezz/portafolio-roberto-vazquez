<?php
/**
 * includes/sections/experience.php — Sección de Experiencia Laboral
 *
 * Datos extraídos del CV de Roberto Vázquez.
 * Para añadir nueva experiencia: añadir un elemento al array $experiencias.
 * El timeline se genera automáticamente.
 */

/**
 * Array de experiencias laborales.
 *
 * @var array{
 *   rol: string,
 *   empresa: string,
 *   ubicacion: string,
 *   periodo: string,
 *   bullets: string[]
 * }[]
 */
$experiencias = [
    [
        'rol'       => 'Analista y Desarrollador de Sistemas · Soporte Técnico',
        'empresa'   => 'Consejo Nacional Electoral',
        'ubicacion' => 'Guanare, Portuguesa',
        'periodo'   => 'Dic 2025 – May 2026',
        'bullets'   => [
            'Desarrollé una aplicación web full stack (PHP, MySQL, Tailwind CSS) para automatizar procesos internos institucionales, reduciendo tiempos operativos.',
            'Realicé auditorías de sistemas críticos y validación de infraestructura hardware del organismo.',
            'Administré bases de datos relacionales con foco en integridad referencial y seguridad de datos.',
            'Gestioné soporte técnico de primer y segundo nivel para el parque informático institucional.',
        ],
    ],

    /* ── AÑADE AQUÍ FUTURAS EXPERIENCIAS ────────────────────────
    [
        'rol'       => 'Nombre del rol',
        'empresa'   => 'Nombre de la empresa',
        'ubicacion' => 'Ciudad, País',
        'periodo'   => 'Mes Año – Mes Año',
        'bullets'   => [
            'Logro o responsabilidad 1.',
            'Logro o responsabilidad 2.',
        ],
    ],
    ─────────────────────────────────────────────────────────── */
];
?>

<section
  id="experiencia"
  class="section-wrapper bg-muted-bg"
  aria-label="Experiencia laboral"
>
  <div class="section-container">

    <!-- ── Encabezado ────────────────────────────────────────── -->
    <div class="reveal mb-16">
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

          <!-- Empresa y ubicación -->
          <p class="text-muted text-sm font-medium mb-5">
            <?= htmlspecialchars($exp['empresa']) ?>
            <span class="text-ui-border mx-2" aria-hidden="true">·</span>
            <?= htmlspecialchars($exp['ubicacion']) ?>
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
