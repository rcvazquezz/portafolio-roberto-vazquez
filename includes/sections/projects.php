<?php
/**
 * includes/sections/projects.php — Proyectos Destacados
 *
 * $proyectos viene de index.php → getPublishedProjects() en portfolio-data.php.
 * Administra los proyectos desde el panel CMS: /admin/projects
 *
 * Formato esperado de cada elemento:
 *   nombre        string    Título del proyecto
 *   descripcion   string    Descripción breve
 *   tags          string[]  Tecnologías usadas
 *   estado        string    'En producción' | 'En desarrollo' | 'Finalizado' | 'Archivado'
 *   url           ?string   URL pública (null si no aplica)
 *   github        ?string   URL del repositorio (null si es privado)
 *   github_label  ?string   Etiqueta del enlace GitHub
 *   insignia      ?string   Badge de origen/contexto del proyecto
 *
 * El primer proyecto (sort_order más bajo) es el principal: ocupa las dos
 * columnas, lleva el borde violeta y el botón "Ver la app". En la descripción,
 * la primera línea es el párrafo y cada línea siguiente se muestra como un
 * bullet "→".
 *
 * Capturas: se muestran solo si existe /public/img/<slug>.webp, donde <slug>
 * es el nombre antes de " — " / " - ", sin tildes, espacios ni símbolos
 * (ej. "Cuánto Es — App…" → cuantoes.webp, "Puyo Code" → puyocode.webp).
 */

/* Colores por estado — mapeados a clases Tailwind */
$estado_clases = [
    'En producción' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'dot' => 'bg-emerald-500'],
    'En desarrollo' => ['bg' => 'bg-amber-50',   'text' => 'text-amber-700',   'dot' => 'bg-amber-500'],
    'Finalizado'    => ['bg' => 'bg-indigo-50',  'text' => 'text-indigo-700',  'dot' => 'bg-indigo-500'],
    'Archivado'     => ['bg' => 'bg-zinc-100',   'text' => 'text-zinc-600',    'dot' => 'bg-zinc-400'],
];

/**
 * Devuelve src, ancho y alto de la captura del proyecto, o null si no existe.
 *
 * @return array{src: string, width: int, height: int}|null
 */
function imagen_proyecto(string $nombre): ?array
{
    $base = preg_split('/\s+[—-]\s+/u', $nombre)[0];
    $slug = strtr(mb_strtolower($base), ['á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ü' => 'u', 'ñ' => 'n']);
    $slug = preg_replace('/[^a-z0-9]/', '', $slug);

    $ruta = APP_ROOT . "/public/img/{$slug}.webp";
    if ($slug === '' || !is_file($ruta)) return null;

    [$ancho, $alto] = getimagesize($ruta) ?: [1200, 750];

    return ['src' => "/public/img/{$slug}.webp", 'width' => $ancho, 'height' => $alto];
}
?>

<section
  id="proyectos"
  class="section-wrapper"
  aria-label="Proyectos destacados"
>
  <div class="section-container">

    <!-- ── Encabezado ──────────────────────────────────────────── -->
    <div class="reveal mb-8 md:mb-16">
      <span class="section-label">Mi trabajo</span>
      <h2 class="section-heading">Proyectos Destacados</h2>
      <div class="section-divider"></div>
      <p class="section-subheading">
        Aplicaciones reales que he diseñado y desarrollado, desde el backend hasta la
        interfaz.
      </p>
    </div>

    <!-- ── Grid de proyectos ──────────────────────────────────── -->
    <div class="grid sm:grid-cols-2 gap-6">

      <?php foreach ($proyectos as $i => $proyecto) :
        $clases     = $estado_clases[$proyecto['estado']] ?? $estado_clases['Archivado'];
        $principal  = $i === 0;
        $imagen     = imagen_proyecto($proyecto['nombre']);
        $lineas     = array_values(array_filter(
            array_map('trim', explode("\n", $proyecto['descripcion'])),
            fn(string $linea) => $linea !== ''
        ));
        $resumen    = array_shift($lineas) ?? '';
      ?>

        <article
          class="project-card reveal<?= $principal ? ' sm:col-span-2 border-violet' : '' ?>"
          data-delay="<?= $i * 100 ?>"
          aria-label="Proyecto: <?= htmlspecialchars($proyecto['nombre']) ?>"
        >
        <div class="<?= $principal && $imagen ? 'grid lg:grid-cols-2 gap-8 items-center' : 'contents' ?>">

          <!-- Captura (solo si existe el archivo) — arriba en las tarjetas normales -->
          <?php if ($imagen && !$principal) : ?>
            <img
              src="<?= htmlspecialchars($imagen['src']) ?>"
              width="<?= $imagen['width'] ?>"
              height="<?= $imagen['height'] ?>"
              loading="lazy"
              decoding="async"
              alt="Captura de <?= htmlspecialchars($proyecto['nombre']) ?>"
              class="w-full h-auto rounded-lg border border-ui-border"
            >
          <?php endif; ?>

          <div class="flex flex-col gap-4 flex-1">

          <!-- Nombre + badge de estado -->
          <div class="flex items-start justify-between gap-4">
            <h3
              class="font-display font-bold text-noir leading-snug"
              style="font-size: <?= $principal ? '1.25rem' : '1rem' ?>; letter-spacing: -0.02em;"
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
          <p class="text-muted text-sm leading-relaxed<?= $lineas ? '' : ' flex-1' ?>">
            <?= htmlspecialchars($resumen) ?>
          </p>

          <!-- Funcionalidades (líneas 2+ de la descripción) -->
          <?php if ($lineas) : ?>
            <ul class="space-y-2 flex-1" role="list">
              <?php foreach ($lineas as $linea) : ?>
                <li class="flex gap-3 text-sm text-muted leading-relaxed">
                  <span class="text-violet flex-shrink-0 font-semibold mt-px" aria-hidden="true">→</span>
                  <?= htmlspecialchars($linea) ?>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>

          <!-- Tags de tecnología -->
          <div class="card-tag-bar">
            <?php foreach ($proyecto['tags'] as $tag) : ?>
              <span class="badge-violet"><?= htmlspecialchars($tag) ?></span>
            <?php endforeach; ?>
          </div>

          <!-- Links de acción -->
          <?php if (!empty($proyecto['url']) || !empty($proyecto['github'])) : ?>
          <div class="flex items-center gap-5 pt-4 border-t border-ui-border">

            <?php if (!empty($proyecto['url']) && $principal) : ?>
              <a
                href="<?= htmlspecialchars($proyecto['url']) ?>"
                target="_blank"
                rel="noopener noreferrer"
                class="btn-primary"
                aria-label="Ver la app <?= htmlspecialchars($proyecto['nombre']) ?>"
              >
                Ver la app
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
              </a>
            <?php elseif (!empty($proyecto['url'])) : ?>
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
          <?php endif; ?>

          </div>

          <!-- Captura del proyecto principal — a la derecha en escritorio -->
          <?php if ($imagen && $principal) : ?>
            <img
              src="<?= htmlspecialchars($imagen['src']) ?>"
              width="<?= $imagen['width'] ?>"
              height="<?= $imagen['height'] ?>"
              loading="lazy"
              decoding="async"
              alt="Captura de <?= htmlspecialchars($proyecto['nombre']) ?>"
              class="w-full h-auto rounded-lg border border-ui-border"
            >
          <?php endif; ?>

        </div>
        </article>

      <?php endforeach; ?>

    </div>
  </div>
</section>
