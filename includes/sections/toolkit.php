<?php
/**
 * includes/sections/toolkit.php — Sección Toolkit & Labs
 *
 * Presenta las soluciones técnicas seleccionadas, integradas y
 * configuradas por Roberto Vázquez para flujos de trabajo de alto
 * rendimiento. El enfoque es de Integrador / Arquitecto de Soluciones:
 * cada kit combina las mejores herramientas disponibles con una
 * arquitectura de flujo diseñada para el problema que resuelve.
 * La autoría de las herramientas base se atribuye en la nota al pie.
 *
 * ─────────────────────────────────────────────────────────────────
 * CÓMO FUNCIONA EL RENDERIZADO DINÁMICO
 * ─────────────────────────────────────────────────────────────────
 * Cada kit es un array asociativo en $kits con campos tipados.
 * El template itera $kits y renderiza cada card sin repetir HTML.
 * Los estilos de badge e ícono se resuelven contra $tipo_config:
 * un mapa tipo → clases CSS que centraliza la paleta de colores
 * por categoría. Añadir un kit nuevo es solo añadir un elemento
 * al array $kits — el HTML se genera solo.
 *
 * Íconos: Lucide Icons (CDN UMD en footer.php).
 * El atributo data-lucide="nombre" es reemplazado por el SVG
 * correspondiente al llamar lucide.createIcons() en el DOM ready.
 * ─────────────────────────────────────────────────────────────────
 *
 * Para añadir un kit:
 *   1. Añadir elemento a $kits con los campos requeridos.
 *   2. Si el tipo es nuevo, añadir su config en $tipo_config.
 *   3. Nada más — el HTML se renderiza automáticamente.
 */


/* ═══════════════════════════════════════════════════════════════
   MAPA DE ESTILOS POR TIPO DE KIT
   Centraliza la paleta de colores de cada categoría.
   Clave: valor de 'tipo' en $kits.
   Clases de badge e ícono siguen el sistema de tokens del proyecto.
═══════════════════════════════════════════════════════════════ */
$tipo_config = [
    // Proceso repetitivo eliminado con lógica de flujo (n8n)
    'Automatización' => [
        'badge_clase' => 'bg-blue-50 text-blue-700 border border-blue-100',
        'icono_clase' => 'bg-blue-50 text-blue-600',
    ],
    // Herramienta nativa del navegador generada a medida (Chrome)
    'Extensión' => [
        'badge_clase' => 'bg-violet-light text-violet border border-violet/20',
        'icono_clase' => 'bg-violet-light text-violet',
    ],
    // Web profesional generativa lista para desplegar (web-scrolling)
    'Starter Kit' => [
        'badge_clase' => 'bg-amber-50 text-amber-700 border border-amber-100',
        'icono_clase' => 'bg-amber-50 text-amber-600',
    ],
    // Identificación y cualificación automatizada de leads (kit-prospeccion)
    // Teal: asociado a crecimiento, ventas y generación de negocio.
    'Prospección' => [
        'badge_clase' => 'bg-teal-50 text-teal-700 border border-teal-100',
        'icono_clase' => 'bg-teal-50 text-teal-600',
    ],
];


// ═══════════════════════════════════════════════════════════════
// DATOS DE LOS KITS
// Fuente: análisis de /kits/{kit}/INSTRUCCIONES.md y CLAUDE.md
// Cada 'descripcion' y 'caracteristicas' están redactados con foco
// en el VALOR que aporta al usuario, no en la tecnología en sí.
// 'icono' → nombre de ícono de Lucide (data-lucide="...").
// ═══════════════════════════════════════════════════════════════
$kits = [

    /* ── Kit 1: Automatizaciones n8n ──────────────────────────── */
    [
        'titulo'      => 'Automatizaciones n8n',
        // Framing: integrador que configura n8n, no desarrollador del motor.
        // El valor está en la arquitectura del flujo y la selección de nodos.
        'descripcion' => 'Integración de n8n configurada para diseñar y desplegar '
                       . 'workflows sin código. La arquitectura del flujo conecta '
                       . 'herramientas de negocio — formularios, hojas de cálculo, '
                       . 'email, mensajería — en automatizaciones listas para activar.',
        'tipo'        => 'Automatización',
        'icono'       => 'workflow',
        'caracteristicas' => [
            'Conecta más de 1.300 nodos: Sheets, Slack, email, CRMs y formularios',
            'Se despliega en tu instancia de n8n o exporta como JSON importable',
            'Flujo guiado: de la descripción del proceso al workflow activo',
        ],
        'url'         => null,  // Añade la URL cuando publiques el kit
    ],

    /* ── Kit 2: Extensiones Chrome ────────────────────────────── */
    [
        'titulo'      => 'Extensiones Chrome',
        // Framing: entorno preconfigurado que guía el proceso de creación,
        // no una librería de extensiones escrita desde cero.
        'descripcion' => 'Entorno preconfigurado para crear extensiones de Chrome '
                       . 'con Manifest V3. El flujo está estructurado para ir del '
                       . 'requisito funcional al artefacto instalable en el menor '
                       . 'tiempo posible, sin conocimientos previos de la API de Chrome.',
        'tipo'        => 'Extensión',
        'icono'       => 'puzzle',
        'caracteristicas' => [
            'Del requisito al archivo instalable en Chrome en menos de 2 minutos',
            'Genera popup, content scripts y manifest.json correctamente configurados',
            'Casos de uso curados: productividad, extracción de datos, personalización web',
        ],
        'url'         => null,
    ],

    /* ── Kit 3: Web Premium con Scroll ────────────────────────── */
    [
        'titulo'      => 'Web Premium con Scroll',
        // Framing: pipeline de generación configurado para producir webs
        // de producción, no un constructor de páginas genérico.
        'descripcion' => 'Pipeline de generación de webs de negocio con animaciones '
                       . 'de scroll, configurado para producir resultados listos para '
                       . 'producción a partir de datos reales. Integra diseño adaptativo, '
                       . 'rendimiento y exportación en un único flujo guiado.',
        'tipo'        => 'Starter Kit',
        'icono'       => 'scroll',
        'caracteristicas' => [
            'Flujo guiado: datos del negocio → web completa lista para publicar',
            'Integra efecto de scroll progresivo sobre vídeo (referencia Apple)',
            'Exportable como HTML único: desplegable en Netlify sin servidor',
        ],
        'url'         => null,
    ],

    /* ── Kit 4: Prospección de Clientes ──────────────────────── */
    [
        'titulo'      => 'Prospección de Clientes',
        // Framing: pipeline B2B configurado con criterios de scoring propios,
        // no un scraper genérico. El valor está en la lógica de cualificación.
        'descripcion' => 'Pipeline de prospección B2B que integra búsqueda web, '
                       . 'auditoría de presencia digital y scoring de oportunidades '
                       . 'en un flujo automatizado. Entrega prospectos cualificados '
                       . 'con fichas de análisis y mensajes de contacto personalizados '
                       . 'listos para el CRM — sin investigación manual.',
        'tipo'        => 'Prospección',
        'icono'       => 'crosshair',
        'caracteristicas' => [
            'Puntúa cada prospecto de 0–100 según déficit digital y servicio ofrecido',
            'Genera mensajes de contacto en frío con problemas reales detectados en cada negocio',
            'Exporta el pipeline a JSON: compatible con cualquier CRM o Google Sheets',
        ],
        'url'         => null,
    ],

    /* ── AÑADE AQUÍ MÁS KITS ────────────────────────────────────
    [
        'titulo'      => 'Nombre del kit',
        'descripcion' => 'Descripción enfocada en el valor y el problema que resuelve.',
        'tipo'        => 'Automatización',   // Debe existir en $tipo_config
        'icono'       => 'nombre-lucide',    // Nombre de icono Lucide válido
        'caracteristicas' => [
            'Característica o beneficio 1',
            'Característica o beneficio 2',
            'Característica o beneficio 3',
        ],
        'url' => null,
    ],
    ──────────────────────────────────────────────────────────── */
];
?>

<section
  id="toolkit"
  class="section-wrapper bg-muted-bg"
  aria-label="Toolkit y Labs de Roberto Vázquez"
>
  <div class="section-container">

    <!-- ── Encabezado ──────────────────────────────────────────── -->
    <div class="reveal mb-6">
      <span class="section-label">Soluciones integradas</span>
      <h2 class="section-heading">Toolkit & Labs</h2>
      <div class="section-divider"></div>
    </div>

    <!--
      Intro orientada al reclutador.
      Posiciona a Roberto como Integrador / Arquitecto de Soluciones:
      alguien que selecciona, combina y configura las herramientas
      correctas para cada problema, en lugar de reinventar la rueda.
      Esta es una competencia senior de alto valor en el mercado.
    -->
    <p class="section-subheading reveal mb-8 md:mb-16">
      Soluciones técnicas seleccionadas, integradas y configuradas para
      flujos de trabajo de alto rendimiento. Cada kit combina las herramientas
      más adecuadas para un problema concreto con una arquitectura de flujo
      pensada para maximizar la eficiencia operativa.
    </p>

    <!-- ════════════════════════════════════════════════════════
         GRID DE KITS
         2 columnas en desktop, 1 en móvil.
         Cada card es un <article> semántico con el icono Lucide,
         badge de tipo, descripción y lista de características.
    ════════════════════════════════════════════════════════════ -->
    <div class="grid sm:grid-cols-2 gap-6">

      <?php foreach ($kits as $i => $kit) :

        /*
         * Resolvemos los estilos de badge e ícono desde el mapa $tipo_config.
         * Si el tipo no existe en el mapa (error de datos), usamos un fallback neutral
         * para no romper el layout.
         */
        $config = $tipo_config[$kit['tipo']] ?? [
            'badge_clase' => 'bg-muted-bg text-muted border border-ui-border',
            'icono_clase' => 'bg-muted-bg text-muted',
        ];

      ?>

        <article
          class="project-card reveal"
          data-delay="<?= $i * 100 ?>"
          aria-label="Kit: <?= htmlspecialchars($kit['titulo']) ?>"
        >

          <!-- ── Cabecera: ícono + badge de tipo ─────────────── -->
          <div class="flex items-start justify-between gap-4">

            <!-- Contenedor del ícono Lucide -->
            <!--
              El elemento <i data-lucide="nombre"> es un placeholder.
              Al cargarse lucide.createIcons() en footer.php, este
              elemento es reemplazado por el SVG correspondiente.
              Las clases de tamaño (w-5 h-5) se aplican al SVG resultante
              porque Lucide copia los atributos class del <i> al <svg>.
            -->
            <div class="w-11 h-11 rounded-card flex items-center justify-center flex-shrink-0
                        <?= $config['icono_clase'] ?>"
                 aria-hidden="true">
              <i data-lucide="<?= htmlspecialchars($kit['icono']) ?>" class="w-5 h-5"></i>
            </div>

            <!-- Badge de tipo (categoría del kit) -->
            <span class="badge rounded-full text-xs font-semibold <?= $config['badge_clase'] ?>">
              <?= htmlspecialchars($kit['tipo']) ?>
            </span>

          </div>

          <!-- ── Título ───────────────────────────────────────── -->
          <h3
            class="font-display font-bold text-noir"
            style="font-size: 1.0625rem; letter-spacing: -0.025em; line-height: 1.3;"
          >
            <?= htmlspecialchars($kit['titulo']) ?>
          </h3>

          <!-- ── Descripción enfocada en el valor ─────────────── -->
          <p class="text-muted text-sm leading-relaxed">
            <?= htmlspecialchars($kit['descripcion']) ?>
          </p>

          <!-- Separador visual antes de las características -->
          <div class="w-full h-px bg-ui-border" aria-hidden="true"></div>

          <!-- ── Lista de características / beneficios ─────────── -->
          <!--
            Estas características están redactadas como beneficios
            concretos (qué puede hacer el usuario) no como features
            técnicas. El objetivo es que un reclutador no técnico
            entienda el valor en 5 segundos.
          -->
          <ul class="space-y-2.5 flex-1" role="list" aria-label="Características del kit">
            <?php foreach ($kit['caracteristicas'] as $caracteristica) : ?>
              <li class="flex items-start gap-2.5 text-xs text-muted leading-relaxed">
                <span class="text-violet flex-shrink-0 font-bold mt-px" aria-hidden="true">→</span>
                <?= htmlspecialchars($caracteristica) ?>
              </li>
            <?php endforeach; ?>
          </ul>

          <!-- ── CTA / Estado del kit ─────────────────────────── -->
          <div class="pt-2 border-t border-ui-border">
            <?php if (!empty($kit['url'])) : ?>

              <!-- Kit publicado: enlace externo -->
              <a
                href="<?= htmlspecialchars($kit['url']) ?>"
                target="_blank"
                rel="noopener noreferrer"
                class="link-external"
                aria-label="Ver kit <?= htmlspecialchars($kit['titulo']) ?>"
              >
                Ver kit
                <i data-lucide="external-link" class="w-3.5 h-3.5" aria-hidden="true"></i>
              </a>

            <?php else : ?>

              <!-- Kit pendiente de publicación -->
              <span class="inline-flex items-center gap-1.5 text-xs text-muted font-medium">
                <i data-lucide="clock" class="w-3.5 h-3.5" aria-hidden="true"></i>
                Documentación próximamente
              </span>

            <?php endif; ?>
          </div>

        </article>

      <?php endforeach; ?>

    </div><!-- fin grid -->

    <!--
      Nota de atribución — Transparencia sobre el rol de integrador.
      Posicionada debajo del grid para no interrumpir el escaneo visual
      del reclutador, pero visible para quien quiera más contexto.
      El tono es informativo, no defensivo.
    -->
    <p class="mt-6 md:mt-12 text-xs text-muted text-center reveal">
      Integración, arquitectura de flujo y configuración:
      <span class="font-medium text-ink">Roberto Carlos Vázquez Antelo</span>
      &nbsp;·&nbsp;
      Las herramientas base son de código abierto o plataformas de terceros.
    </p>

  </div>
</section>
