<?php
/**
 * includes/sections/contact.php — Sección de Contacto
 *
 * CTA final del portafolio. Fondo oscuro (bg-noir) para crear contraste
 * visual con las secciones anteriores y destacar el cierre del sitio.
 *
 * Canales de contacto extraídos del CV de Roberto Vázquez.
 * Para añadir un canal: añadir un elemento al array $canales.
 */

/**
 * @var array{
 *   icono: 'email'|'phone'|'globe',
 *   label: string,
 *   valor: string,
 *   url: string,
 *   cta: string
 * }[]
 */
$canales = [
    [
        'icono' => 'email',
        'label' => 'Email',
        'valor' => 'rcvazquezantelo@gmail.com',
        'url'   => 'mailto:rcvazquezantelo@gmail.com',
        'cta'   => 'Enviar email →',
    ],
    [
        'icono' => 'phone',
        'label' => 'Teléfono',
        'valor' => '+34 625 018 707',
        'url'   => 'tel:+34625018707',
        'cta'   => 'Llamar →',
    ],
    [
        'icono' => 'globe',
        'label' => 'DevLink',
        'valor' => 'devlink.nygaccesorios.com',
        'url'   => 'https://devlink.nygaccesorios.com/',
        'cta'   => 'Ver plataforma →',
    ],
];

/** Devuelve el SVG del ícono solicitado (inline para evitar peticiones HTTP) */
function icono_contacto(string $tipo): string {
    return match ($tipo) {
        'email' => '<svg class="w-5 h-5 text-violet" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>',
        'phone' => '<svg class="w-5 h-5 text-violet" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.948V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>',
        default => '<svg class="w-5 h-5 text-violet" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9"/>
                    </svg>',
    };
}
?>

<section
  id="contacto"
  class="bg-noir py-24 md:py-32"
  aria-label="Contacto"
>
  <div class="section-container">

    <!-- ── Encabezado sobre fondo oscuro ──────────────────────── -->
    <div class="reveal text-center mb-16">
      <span class="inline-flex items-center gap-2 text-xs font-semibold tracking-widest uppercase text-violet mb-4">
        <span class="block w-6 h-px bg-violet" aria-hidden="true"></span>
        Trabajemos juntos
        <span class="block w-6 h-px bg-violet" aria-hidden="true"></span>
      </span>
      <h2
        class="font-display font-bold text-white mt-4"
        style="font-size: clamp(2rem, 4vw, 2.75rem); letter-spacing: -0.03em;"
      >
        ¿Tienes un proyecto en mente?
      </h2>
      <p class="text-muted mt-4 max-w-lg mx-auto" style="font-size: 1.0625rem; line-height: 1.75;">
        Estoy disponible para incorporación inmediata. Si buscas un desarrollador
        Full Stack comprometido con la calidad, hablemos.
      </p>
    </div>

    <!-- ── Cards de canales de contacto ──────────────────────── -->
    <div class="grid sm:grid-cols-3 gap-4 max-w-3xl mx-auto">
      <?php foreach ($canales as $i => $canal) :
        $es_externo = str_starts_with($canal['url'], 'http');
      ?>

        <a
          href="<?= htmlspecialchars($canal['url']) ?>"
          <?= $es_externo ? 'target="_blank" rel="noopener noreferrer"' : '' ?>
          class="group flex flex-col gap-4 p-6 rounded-card border border-graphite reveal
                 transition-all duration-250 ease-smooth
                 hover:border-violet hover:bg-graphite"
          data-delay="<?= $i * 100 ?>"
          aria-label="Contactar por <?= htmlspecialchars($canal['label']) ?>"
        >

          <!-- Ícono en contenedor circular oscuro -->
          <div class="w-10 h-10 rounded-badge bg-graphite flex items-center justify-center
                      transition-colors duration-250 group-hover:bg-violet-dark flex-shrink-0">
            <?= icono_contacto($canal['icono']) ?>
          </div>

          <div class="flex-1">
            <p class="text-xs font-semibold tracking-widest uppercase text-muted mb-1">
              <?= htmlspecialchars($canal['label']) ?>
            </p>
            <p class="text-white text-sm font-medium break-all">
              <?= htmlspecialchars($canal['valor']) ?>
            </p>
          </div>

          <span class="text-violet text-xs font-semibold
                       transition-transform duration-200 group-hover:translate-x-1 inline-block">
            <?= htmlspecialchars($canal['cta']) ?>
          </span>

        </a>

      <?php endforeach; ?>
    </div>

  </div>
</section>
