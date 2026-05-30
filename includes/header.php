<?php
/**
 * includes/header.php — Cabecera global del sitio
 *
 * Contiene:
 *   - Meta tags (SEO + Open Graph)
 *   - Carga de Google Fonts: Syne (display) + Inter (body)
 *   - Hoja de estilos Tailwind compilada
 *   - Navegación principal responsiva (Alpine.js)
 *
 * Dependencias: Alpine.js se carga en footer.php con defer.
 */

/* ── Configuración global de la página ──────────────────────── */
$config = [
    'nombre'      => 'Roberto Carlos Vázquez Antelo',
    'rol'         => 'Desarrollador Full Stack',
    /*
     * Meta description: entre 140-160 caracteres, incluye keywords clave
     * y diferenciadores (disponibilidad, tecnologías, especialidad).
     */
    'descripcion' => 'Desarrollador Full Stack (PHP, MySQL, JavaScript) con experiencia '
                   . 'en aplicaciones web escalables y automatización de flujos. '
                   . 'Disponible para incorporación inmediata.',
    'url'         => 'https://rcvazquezz.com/',
    /*
     * og:image: imagen de 1200×630 px para preview en redes sociales.
     * Crear el archivo en public/og-image.png antes de publicar.
     */
    'og_image'    => 'https://rcvazquezz.com/public/og-image.png',
    'color_tema'  => '#0C0C0F',
    'google_analytics_id' => 'G-S29LYE3SYS',
];

/* ── Ítems del menú: etiqueta visible => ancla de sección ───── */
$nav_items = [
    'Inicio'      => '#inicio',
    'Sobre mí'    => '#sobre-mi',
    'Experiencia' => '#experiencia',
    'Proyectos'   => '#proyectos',
    'Formación'   => '#educacion',
    'Toolkit'     => '#toolkit',
    'Contacto'    => '#contacto',
];
?>
<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- ── SEO básico ───────────────────────────────────────────── -->
  <title><?= htmlspecialchars($config['nombre']) ?> — <?= htmlspecialchars($config['rol']) ?></title>
  <meta name="description" content="<?= htmlspecialchars($config['descripcion']) ?>">
  <meta name="author"      content="<?= htmlspecialchars($config['nombre']) ?>">
  <meta name="theme-color" content="<?= $config['color_tema'] ?>">

  <!-- Canonical: evita que Google indexe URLs duplicadas con parámetros -->
  <link rel="canonical" href="<?= htmlspecialchars($config['url']) ?>">

  <!-- ── Open Graph (Facebook, LinkedIn, WhatsApp, Discord…) ─── -->
  <meta property="og:type"        content="website">
  <meta property="og:locale"      content="es_ES">
  <meta property="og:site_name"   content="<?= htmlspecialchars($config['nombre']) ?>">
  <meta property="og:url"         content="<?= htmlspecialchars($config['url']) ?>">
  <meta property="og:title"       content="<?= htmlspecialchars($config['nombre']) ?> — <?= htmlspecialchars($config['rol']) ?>">
  <meta property="og:description" content="<?= htmlspecialchars($config['descripcion']) ?>">
  <meta property="og:image"       content="<?= htmlspecialchars($config['og_image']) ?>">
  <meta property="og:image:width"  content="1200">
  <meta property="og:image:height" content="630">
  <meta property="og:image:alt"   content="Portafolio de <?= htmlspecialchars($config['nombre']) ?>">

  <!-- ── Twitter / X Card ─────────────────────────────────────── -->
  <meta name="twitter:card"        content="summary_large_image">
  <meta name="twitter:title"       content="<?= htmlspecialchars($config['nombre']) ?> — <?= htmlspecialchars($config['rol']) ?>">
  <meta name="twitter:description" content="<?= htmlspecialchars($config['descripcion']) ?>">
  <meta name="twitter:image"       content="<?= htmlspecialchars($config['og_image']) ?>">
  <meta name="twitter:image:alt"   content="Portafolio de <?= htmlspecialchars($config['nombre']) ?>">

  <!-- ── JSON-LD: schema.org Person ───────────────────────────────
       Permite a Google mostrar un Knowledge Panel con tu perfil
       directamente en los resultados de búsqueda.
  ─────────────────────────────────────────────────────────────── -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "Person",
    "name": "<?= $config['nombre'] ?>",
    "jobTitle": "<?= $config['rol'] ?>",
    "description": "<?= $config['descripcion'] ?>",
    "url": "<?= $config['url'] ?>",
    "email": "mailto:rcvazquezantelo2006@gmail.com",
    "knowsAbout": ["PHP", "JavaScript", "MySQL", "Tailwind CSS", "REST APIs", "Git", "Análisis de Sistemas"],
    "sameAs": [
      "https://github.com/rcvazquezz",
      "https://www.linkedin.com/in/rcvazquezantelo/"
    ]
  }
  </script>

  <!-- ── Google Fonts: precarga para evitar FOUT ──────────────── -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap"
    rel="stylesheet"
  >

  <!-- ── Favicon ─────────────────────────────────────────────── -->
  <link rel="icon" href="/favicon.svg" type="image/svg+xml">
  <link rel="icon" href="/favicon.svg" sizes="any">
  <link rel="apple-touch-icon" href="/favicon.svg">

  <!-- ── Tailwind CSS compilado ───────────────────────────────── -->
  <link rel="stylesheet" href="src/css/output.css?v=4">
</head>

<body class="bg-cream text-ink font-body antialiased">

<!-- ════════════════════════════════════════════════════════════
     NAVEGACIÓN PRINCIPAL
     Alpine.js (navController en app.js) gestiona:
       · open        → estado del menú móvil
       · scrolled    → clase nav-scrolled al hacer scroll
       · activeSection → link activo del menú
════════════════════════════════════════════════════════════════ -->
<nav
  x-data="navController()"
  x-init="init()"
  :class="scrolled ? 'nav-scrolled' : 'bg-transparent'"
  class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
  role="navigation"
  aria-label="Navegación principal"
>
  <div class="section-container">
    <div class="flex items-center justify-between h-16">

      <!-- ── Logotipo: monograma RV + nombre ──────────────────── -->
      <a href="#inicio" class="flex items-center gap-3 group" aria-label="Inicio — Roberto Vázquez">
        <div
          class="w-9 h-9 rounded-lg bg-noir flex items-center justify-center flex-shrink-0
                 transition-transform duration-250 ease-spring group-hover:scale-105"
        >
          <span class="font-display font-bold text-sm text-white tracking-tight" aria-hidden="true">RV</span>
        </div>
        <span class="hidden sm:block font-display font-semibold text-noir text-sm
                     transition-opacity duration-200 group-hover:opacity-60">
          Roberto Vázquez
        </span>
      </a>

      <!-- ── Links de escritorio ──────────────────────────────── -->
      <ul class="hidden md:flex items-center gap-1" role="list">
        <?php foreach ($nav_items as $label => $anchor) : ?>
          <li>
            <a
              href="<?= $anchor ?>"
              :class="activeSection === '<?= ltrim($anchor, '#') ?>' ? 'active' : ''"
              class="nav-link px-3 py-2 block"
            >
              <?= htmlspecialchars($label) ?>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>

      <!-- ── CTA de escritorio ─────────────────────────────────── -->
      <div class="hidden md:flex items-center">
        <a href="/public/cv-roberto-vazquez.pdf" download="CV_Roberto_Vazquez.pdf" class="btn-outline text-xs py-2 px-4">
          <i data-lucide="download" class="w-3 h-3" aria-hidden="true"></i>
          Descargar CV
        </a>
      </div>

      <!-- ── Botón hamburguesa (móvil) ────────────────────────── -->
      <button
        @click="open = !open"
        :aria-expanded="open.toString()"
        class="md:hidden p-2 rounded-button text-ink
               transition-colors duration-150 hover:bg-muted-bg"
        aria-label="Abrir menú de navegación"
        aria-controls="mobile-menu"
      >
        <!-- Ícono hamburguesa -->
        <svg x-show="!open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
        <!-- Ícono de cierre (X) -->
        <svg x-show="open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true" style="display:none;">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>

    </div>
  </div><!-- fin section-container -->

  <!-- ── Menú desplegable móvil (Alpine transition) ────────────── -->
  <div
    id="mobile-menu"
    x-show="open"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 -translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-2"
    class="md:hidden bg-surface border-b border-ui-border shadow-card"
    style="display:none;"
    role="menu"
    aria-label="Menú móvil"
  >
    <ul class="section-container py-4 flex flex-col gap-1" role="list">
      <?php foreach ($nav_items as $label => $anchor) : ?>
        <li role="none">
          <a
            href="<?= $anchor ?>"
            @click="open = false"
            class="block px-4 py-3 text-sm font-medium text-ink rounded-button
                   transition-colors duration-150 hover:bg-muted-bg hover:text-violet"
            role="menuitem"
          >
            <?= htmlspecialchars($label) ?>
          </a>
        </li>
      <?php endforeach; ?>
      <li class="pt-3 mt-2 border-t border-ui-border" role="none">
        <a href="/public/cv-roberto-vazquez.pdf" download="CV_Roberto_Vazquez.pdf" class="btn-primary w-full justify-center">
          <i data-lucide="download" class="w-4 h-4" aria-hidden="true"></i>
          Descargar CV
        </a>
      </li>
    </ul>
  </div>

</nav>

<!-- Espacio para compensar la nav fija -->
<div class="h-16" aria-hidden="true"></div>
