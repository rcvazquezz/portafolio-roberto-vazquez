<?php
/**
 * includes/sections/hero.php — Sección Hero
 *
 * Primera impresión del visitante. Contiene:
 *   - Badge de disponibilidad con dot pulsante
 *   - Nombre en Syne 800 con gradiente violeta
 *   - Rol y descripción breve (del CV)
 *   - Stack tecnológico como skill-tags
 *   - CTAs: "Ver proyectos" (primario) y email (outline)
 *   - Decoración: blob de gradiente + grid punteado de fondo
 *
 * Animaciones: fade-up escalonado con animation-delay (CSS puro,
 * no requiere JS — funciona aunque el observer aún no haya cargado).
 */

/* Stack principal para mostrar en el hero (extraído del CV) */
$stack_hero = ['PHP', 'JavaScript', 'MySQL', 'Tailwind CSS', 'REST APIs', 'Git'];
?>

<section
  id="inicio"
  class="relative min-h-[calc(100vh-4rem)] flex items-center overflow-hidden bg-hero-gradient"
  aria-label="Presentación de Roberto Vázquez"
>

  <!-- ── Decoración de fondo: blob de gradiente violeta ─────── -->
  <div
    aria-hidden="true"
    class="pointer-events-none absolute -top-40 -right-40 w-[700px] h-[700px] rounded-full"
    style="
      background: radial-gradient(circle, rgba(124,101,246,0.13) 0%, transparent 65%);
      filter: blur(72px);
    "
  ></div>

  <!-- ── Decoración de fondo: grid punteado sutil ──────────── -->
  <div
    aria-hidden="true"
    class="pointer-events-none absolute inset-0 opacity-40"
    style="
      background-image: radial-gradient(circle, #E4E3DF 1px, transparent 1px);
      background-size: 32px 32px;
    "
  ></div>

  <!-- ── Contenido principal ───────────────────────────────── -->
  <div class="section-container w-full py-20 lg:py-32 relative z-10">
    <div class="max-w-3xl">

      <!-- Badge de disponibilidad (dot pulsante) -->
      <div class="animate-fade-up animation-delay-100">
        <span class="availability-badge">
          <span class="dot" aria-hidden="true"></span>
          Disponible para incorporación inmediata
        </span>
      </div>

      <!-- Nombre principal: Syne 800, escala fluida con clamp -->
      <h1
        class="font-display font-extrabold text-noir mt-8 animate-fade-up animation-delay-200"
        style="font-size: clamp(2.75rem, 7vw, 5.5rem); line-height: 1.05; letter-spacing: -0.04em;"
      >
        Roberto Carlos<br>
        <span class="text-gradient">Vázquez Antelo</span>
      </h1>

      <!-- Rol / Subtítulo -->
      <p
        class="font-display font-semibold text-muted mt-5 animate-fade-up animation-delay-300"
        style="font-size: clamp(1.1rem, 2.5vw, 1.4rem); letter-spacing: -0.01em;"
      >
        Desarrollador Full Stack
        <span class="text-ui-border mx-3" aria-hidden="true">·</span>
        Análisis de Sistemas
      </p>

      <!-- Descripción (del CV) -->
      <p
        class="text-muted font-body mt-6 max-w-xl leading-relaxed animate-fade-up animation-delay-400"
        style="font-size: 1.0625rem;"
      >
        Técnico Superior en Análisis de Sistemas con experiencia en desarrollo web full stack
        usando PHP, JavaScript y MySQL. Especializado en construir aplicaciones escalables y
        optimizar flujos de trabajo internos.
      </p>

      <!-- Stack tecnológico -->
      <div
        class="flex flex-wrap gap-2 mt-8 animate-fade-up animation-delay-500"
        role="list"
        aria-label="Tecnologías principales"
      >
        <?php foreach ($stack_hero as $tech) : ?>
          <span class="skill-tag" role="listitem"><?= htmlspecialchars($tech) ?></span>
        <?php endforeach; ?>
      </div>

      <!-- CTAs -->
      <div class="flex flex-col sm:flex-row items-start gap-4 mt-10 animate-fade-up animation-delay-600">
        <a href="#proyectos" class="btn-primary group">
          Ver proyectos
          <svg
            class="w-4 h-4 transition-transform duration-200 group-hover:translate-x-1"
            fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"
          >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
          </svg>
        </a>
        <a href="mailto:rcvazquezantelo@gmail.com" class="btn-outline">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
          </svg>
          rcvazquezantelo@gmail.com
        </a>
      </div>

      <!-- Indicador de scroll hacia abajo -->
      <div
        class="mt-16 hidden md:flex items-center gap-3 animate-fade-in animation-delay-700"
        aria-hidden="true"
      >
        <div class="flex flex-col gap-1">
          <div class="w-px h-8 bg-ui-border mx-auto"></div>
          <div class="w-1 h-1 rounded-full bg-violet mx-auto animate-float"></div>
        </div>
        <span class="text-xs font-semibold text-muted tracking-widest uppercase">Scroll</span>
      </div>

    </div>
  </div>

</section>
