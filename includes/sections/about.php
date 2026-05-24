<?php
/**
 * includes/sections/about.php — Sección "Sobre mí"
 *
 * Datos extraídos íntegramente del CV de Roberto Vázquez.
 * Layout: dos columnas (texto + habilidades) en desktop, apiladas en móvil.
 */

/* Tecnologías del CV (sidebar "Tecnologías") */
$tecnologias = [
    'PHP', 'JavaScript', 'MySQL', 'HTML5', 'CSS3',
    'Tailwind CSS', 'GitHub', 'Git', 'REST APIs',
    'Análisis de Sistemas', 'BD Relacionales',
];

/* Idiomas del CV */
$idiomas = [
    ['nombre' => 'Español', 'nivel' => 'Nativo'],
    ['nombre' => 'Inglés',  'nivel' => 'Básico'],
];
?>

<section
  id="sobre-mi"
  class="section-wrapper"
  aria-label="Sobre mí"
>
  <div class="section-container">

    <!-- ── Encabezado ──────────────────────────────────────────── -->
    <div class="reveal mb-16">
      <span class="section-label">Conóceme</span>
      <h2 class="section-heading">Sobre mí</h2>
      <div class="section-divider"></div>
    </div>

    <!-- ── Grid principal ───────────────────────────────────────── -->
    <div class="grid md:grid-cols-2 gap-12 lg:gap-20 items-start">

      <!-- Columna izquierda: texto + datos personales -->
      <div class="space-y-6 reveal">

        <p class="text-muted leading-relaxed" style="font-size: 1.0625rem;">
          Técnico Superior Universitario en Análisis de Sistemas, con experiencia real en
          desarrollo web full stack y soporte técnico institucional. Me especializo en
          construir aplicaciones escalables con PHP, MySQL y JavaScript, con foco en la
          optimización de flujos de trabajo internos.
        </p>

        <p class="text-muted leading-relaxed" style="font-size: 1rem;">
          Tengo alta capacidad de aprendizaje, orientación al trabajo en equipo y experiencia
          en entornos institucionales exigentes. Actualmente disponible para incorporación
          inmediata, presencial o remota.
        </p>

        <!-- Separador -->
        <div class="w-full h-px bg-ui-border !my-8" aria-hidden="true"></div>

        <!-- Información de contacto rápido -->
        <ul class="space-y-4" role="list" aria-label="Datos de contacto">

          <li class="flex items-center gap-3 text-sm text-muted">
            <svg class="w-4 h-4 text-violet flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span>Caldebarcos, A Coruña, Galicia</span>
          </li>

          <li class="flex items-center gap-3 text-sm text-muted">
            <svg class="w-4 h-4 text-violet flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.948V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
            </svg>
            <span>+34 625 01 87 07</span>
          </li>

          <li class="flex items-center gap-3 text-sm text-muted">
            <svg class="w-4 h-4 text-violet flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9"/>
            </svg>
            <a
              href="https://devlink.nygaccesorios.com/"
              target="_blank"
              rel="noopener noreferrer"
              class="hover:text-violet transition-colors duration-150"
            >
              devlink.nygaccesorios.com
            </a>
          </li>

        </ul>

        <!-- Idiomas -->
        <div class="pt-4">
          <p class="text-xs font-semibold tracking-widest uppercase text-muted mb-4">Idiomas</p>
          <div class="flex gap-3">
            <?php foreach ($idiomas as $idioma) : ?>
              <div class="card px-5 py-4 text-center flex-1" style="box-shadow:none;">
                <p class="font-semibold text-noir text-sm"><?= htmlspecialchars($idioma['nombre']) ?></p>
                <p class="text-xs text-muted mt-1"><?= htmlspecialchars($idioma['nivel']) ?></p>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

      </div>

      <!-- Columna derecha: stack + disponibilidad -->
      <div class="reveal animation-delay-200">

        <p class="text-xs font-semibold tracking-widest uppercase text-muted mb-6">
          Stack & Tecnologías
        </p>

        <div class="flex flex-wrap gap-3" role="list" aria-label="Stack tecnológico">
          <?php foreach ($tecnologias as $tech) : ?>
            <span class="skill-tag" role="listitem"><?= htmlspecialchars($tech) ?></span>
          <?php endforeach; ?>
        </div>

        <!-- Card de disponibilidad -->
        <div class="card mt-10 p-6" aria-label="Estado de disponibilidad">
          <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-badge bg-violet-light flex items-center justify-center flex-shrink-0">
              <svg class="w-5 h-5 text-violet" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
              </svg>
            </div>
            <div>
              <p class="font-semibold text-noir text-sm">Disponible ahora</p>
              <p class="text-muted text-sm mt-1 leading-relaxed">
                Incorporación inmediata. Abierto a reubicación en cualquier localización.
                Modalidad presencial o remota.
              </p>
            </div>
          </div>
        </div>

      </div>

    </div>
  </div>
</section>
