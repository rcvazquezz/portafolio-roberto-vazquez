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
 *   descripcion: ?string,
 *   categorias: array{nombre: string, items: string[]}[],
 *   gancho: ?string
 * }[]
 */
$formacion = [
    [
        'titulo'      => 'Técnico Superior Universitario en Análisis de Sistemas | Mención Desarrollo Web',
        'institucion' => 'IUTEPI — Instituto Universitario de Tecnología para la Informática',
        'ubicacion'   => 'Guanare, Portuguesa',
        'periodo'     => '2023 – 2026',
        'descripcion' => 'Formación orientada a la ingeniería de software, arquitectura de sistemas y despliegue de aplicaciones web escalables.',
        'categorias'  => [
            [
                'nombre' => 'Arquitectura & Software',
                'items'  => ['Ciclo de vida del software', 'Análisis de Sistemas', 'Ingeniería de Requisitos'],
            ],
            [
                'nombre' => 'Data & Lógica',
                'items'  => ['Bases de Datos Relacionales', 'Lógica de Programación', 'Algoritmia'],
            ],
            [
                'nombre' => 'Despliegue',
                'items'  => ['Sistemas de Gestión Institucional', 'Integración de Servicios'],
            ],
        ],
        'gancho'      => 'Enfoque técnico: Del levantamiento de requerimientos a la puesta en producción.',
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

          <!-- Institución y ubicación -->
          <p class="text-muted text-sm font-medium mb-3">
            <?= htmlspecialchars($edu['institucion']) ?>
            <span class="text-ui-border mx-2" aria-hidden="true">·</span>
            <?= htmlspecialchars($edu['ubicacion']) ?>
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
