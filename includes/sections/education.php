<?php
/**
 * includes/sections/education.php — Formación Académica
 *
 * Datos extraídos del CV de Roberto Vázquez.
 * Para añadir formación o certificaciones: editar los arrays correspondientes.
 */

/**
 * Formación académica formal.
 *
 * @var array{
 *   titulo: string,
 *   institucion: string,
 *   ubicacion: string,
 *   periodo: string,
 *   materias: string[]
 * }[]
 */
$formacion = [
    [
        'titulo'      => 'Técnico Superior Universitario en Análisis de Sistemas',
        'institucion' => 'IUTEPI — Instituto Universitario de Tecnología para la Informática',
        'ubicacion'   => 'Guanare, Portuguesa',
        'periodo'     => '2023 – 2026',
        'materias'    => [
            'Ciclo de vida del software',
            'Bases de datos relacionales',
            'Lógica de programación',
            'Sistemas de gestión institucional',
        ],
    ],

    /* ── AÑADE AQUÍ MÁS FORMACIÓN ────────────────────────────────
    [
        'titulo'      => 'Nombre del título',
        'institucion' => 'Nombre de la institución',
        'ubicacion'   => 'Ciudad, País',
        'periodo'     => 'Año – Año',
        'materias'    => ['Materia 1', 'Materia 2'],
    ],
    ─────────────────────────────────────────────────────────── */
];

/**
 * Certificaciones y cursos complementarios.
 *
 * @var array{nombre: string, emisor: string, periodo: string}[]
 */
$certificaciones = [
    /* ── AÑADE AQUÍ TUS CERTIFICACIONES ─────────────────────────
    [
        'nombre'  => 'Nombre del certificado',
        'emisor'  => 'Plataforma / Institución',
        'periodo' => '2024',
    ],
    ─────────────────────────────────────────────────────────── */
];
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
          <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-4">
            <h3
              class="font-display font-bold text-noir"
              style="font-size: 1rem; letter-spacing: -0.02em; line-height: 1.35;"
            >
              <?= htmlspecialchars($edu['titulo']) ?>
            </h3>
            <span class="badge-violet flex-shrink-0 self-start">
              <?= htmlspecialchars($edu['periodo']) ?>
            </span>
          </div>

          <!-- Institución y ubicación -->
          <p class="text-muted text-sm font-medium mb-5">
            <?= htmlspecialchars($edu['institucion']) ?>
            <span class="text-ui-border mx-2" aria-hidden="true">·</span>
            <?= htmlspecialchars($edu['ubicacion']) ?>
          </p>

          <!-- Materias relevantes -->
          <?php if (!empty($edu['materias'])) : ?>
            <div class="flex flex-wrap gap-2" role="list" aria-label="Materias relevantes">
              <?php foreach ($edu['materias'] as $materia) : ?>
                <span class="badge-neutral" role="listitem">
                  <?= htmlspecialchars($materia) ?>
                </span>
              <?php endforeach; ?>
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
