<?php
/**
 * includes/sections/projects.php — Proyectos Destacados
 *
 * Datos extraídos del CV de Roberto Vázquez.
 * Para añadir proyectos: añadir elementos al array $proyectos.
 *
 * Campos del proyecto:
 *   nombre        string    Título del proyecto
 *   descripcion   string    Descripción breve (2-3 líneas)
 *   tags          string[]  Tecnologías usadas
 *   estado        string    'En producción' | 'En desarrollo' | 'Finalizado' | 'Archivado'
 *   url           ?string   URL pública del proyecto (null si no tiene)
 *   github        ?string   URL del repositorio GitHub (null si es privado)
 *   github_label  ?string   Texto del enlace GitHub. Por defecto: 'Ver en GitHub'
 *   insignia      ?string   Badge de origen/naturaleza del proyecto (ej: 'Sistema institucional').
 *                           Distingue proyectos propios de integraciones o kits externos.
 */
$proyectos = [
    [
        'nombre'      => 'DevLink — Marketplace de Desarrolladores',
        'descripcion' => 'Plataforma web tipo marketplace que conecta desarrolladores con clientes. Diseñada con foco en escalabilidad y experiencia de usuario, con desarrollo asistido por IA para optimizar flujos y acelerar tiempos de entrega.',
        'tags'        => ['PHP', 'JavaScript', 'MySQL', 'Tailwind CSS'],
        'estado'      => 'En producción',
        'url'         => 'https://devlink.nygaccesorios.com/',
        'github'      => null,
    ],

    [
        'nombre'      => 'Sistema de Gestión de Trámites',
        'descripcion' => 'Aplicación web integral para la automatización de flujos operativos internos, optimizando la gestión y trazabilidad de trámites institucionales.',
        'tags'        => ['PHP', 'MySQL', 'Tailwind CSS', 'JavaScript'],
        'estado'      => 'Finalizado',
        'url'         => null,
        'github'      => 'https://github.com/rcvazquezz/sistema_cne',
        'github_label' => 'Ver repositorio en GitHub',
        'insignia'    => 'Sistema institucional',
    ],

    /* ── AÑADE AQUÍ MÁS PROYECTOS ────────────────────────────────
    [
        'nombre'      => 'Nombre del proyecto',
        'descripcion' => 'Descripción clara y concisa del proyecto.',
        'tags'        => ['Tecnología 1', 'Tecnología 2'],
        'estado'      => 'En desarrollo',
        'url'         => null,
        'github'      => 'https://github.com/usuario/repo',
    ],
    ─────────────────────────────────────────────────────────── */
];

/* Colores por estado — mapeados a clases Tailwind */
$estado_clases = [
    'En producción' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'dot' => 'bg-emerald-500'],
    'En desarrollo' => ['bg' => 'bg-amber-50',   'text' => 'text-amber-700',   'dot' => 'bg-amber-500'],
    'Finalizado'    => ['bg' => 'bg-indigo-50',  'text' => 'text-indigo-700',  'dot' => 'bg-indigo-500'],
    'Archivado'     => ['bg' => 'bg-zinc-100',   'text' => 'text-zinc-600',    'dot' => 'bg-zinc-400'],
];
?>

<section
  id="proyectos"
  class="section-wrapper"
  aria-label="Proyectos destacados"
>
  <div class="section-container">

    <!-- ── Encabezado ──────────────────────────────────────────── -->
    <div class="reveal mb-16">
      <span class="section-label">Mi trabajo</span>
      <h2 class="section-heading">Proyectos Destacados</h2>
      <div class="section-divider"></div>
      <p class="section-subheading">
        Aplicaciones reales en producción, construidas con foco en escalabilidad,
        rendimiento y experiencia de usuario.
      </p>
    </div>

    <!-- ── Grid de proyectos ──────────────────────────────────── -->
    <div class="grid sm:grid-cols-2 gap-6">

      <?php foreach ($proyectos as $i => $proyecto) :
        $clases = $estado_clases[$proyecto['estado']] ?? $estado_clases['Archivado'];
      ?>

        <article
          class="project-card reveal"
          data-delay="<?= $i * 100 ?>"
          aria-label="Proyecto: <?= htmlspecialchars($proyecto['nombre']) ?>"
        >

          <!-- Nombre + badge de estado -->
          <div class="flex items-start justify-between gap-4">
            <h3
              class="font-display font-bold text-noir leading-snug"
              style="font-size: 1rem; letter-spacing: -0.02em;"
            >
              <?= htmlspecialchars($proyecto['nombre']) ?>
            </h3>

            <?php if (!empty($proyecto['estado'])) : ?>
              <span class="badge flex-shrink-0 rounded-full px-2.5 py-1 <?= $clases['bg'] ?> <?= $clases['text'] ?>">
                <span class="w-1.5 h-1.5 rounded-full inline-block mr-1 <?= $clases['dot'] ?>" aria-hidden="true"></span>
                <?= htmlspecialchars($proyecto['estado']) ?>
              </span>
            <?php endif; ?>
          </div>

          <!-- Insignia de origen (solo si el proyecto la tiene) -->
          <?php if (!empty($proyecto['insignia'])) : ?>
            <span class="badge-dark self-start">
              <?= htmlspecialchars($proyecto['insignia']) ?>
            </span>
          <?php endif; ?>

          <!-- Descripción -->
          <p class="text-muted text-sm leading-relaxed flex-1">
            <?= htmlspecialchars($proyecto['descripcion']) ?>
          </p>

          <!-- Tags de tecnología -->
          <div class="card-tag-bar">
            <?php foreach ($proyecto['tags'] as $tag) : ?>
              <span class="badge-violet"><?= htmlspecialchars($tag) ?></span>
            <?php endforeach; ?>
          </div>

          <!-- Links de acción -->
          <div class="flex items-center gap-5 pt-4 border-t border-ui-border">

            <?php if (!empty($proyecto['url'])) : ?>
              <a
                href="<?= htmlspecialchars($proyecto['url']) ?>"
                target="_blank"
                rel="noopener noreferrer"
                class="link-external"
                aria-label="Ver proyecto <?= htmlspecialchars($proyecto['nombre']) ?> en producción"
              >
                Ver proyecto
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
              </a>
            <?php endif; ?>

            <?php if (!empty($proyecto['github'])) : ?>
              <a
                href="<?= htmlspecialchars($proyecto['github']) ?>"
                target="_blank"
                rel="noopener noreferrer"
                class="link-external"
                aria-label="Ver código de <?= htmlspecialchars($proyecto['nombre']) ?> en GitHub"
              >
                <?= htmlspecialchars($proyecto['github_label'] ?? 'Ver en GitHub') ?>
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                </svg>
              </a>
            <?php endif; ?>

          </div>
        </article>

      <?php endforeach; ?>

    </div>
  </div>
</section>
