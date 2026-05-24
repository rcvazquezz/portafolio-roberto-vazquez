<?php
/**
 * index.php — Punto de entrada principal del portafolio
 *
 * Ensambla el sitio completo mediante require_once de cada módulo.
 * Este archivo solo orquesta; la lógica y el HTML viven en includes/.
 *
 * Orden de secciones:
 *   1. Hero         — Primera impresión, nombre y CTAs
 *   2. Sobre mí     — Perfil, habilidades y disponibilidad
 *   3. Experiencia  — Historial laboral en formato timeline
 *   4. Proyectos    — Proyectos destacados en producción
 *   5. Educación    — Formación académica
 *   6. Toolkit      — Kits y automatizaciones propias
 *   7. Contacto     — Canales de comunicación (fondo oscuro)
 */
define('APP_ROOT', __DIR__);
?>

<?php require_once APP_ROOT . '/includes/header.php'; ?>

<main id="main-content" role="main">
  <?php require_once APP_ROOT . '/includes/sections/hero.php'; ?>
  <?php require_once APP_ROOT . '/includes/sections/about.php'; ?>
  <?php require_once APP_ROOT . '/includes/sections/experience.php'; ?>
  <?php require_once APP_ROOT . '/includes/sections/projects.php'; ?>
  <?php require_once APP_ROOT . '/includes/sections/education.php'; ?>
  <?php require_once APP_ROOT . '/includes/sections/toolkit.php'; ?>
  <?php require_once APP_ROOT . '/includes/sections/contact.php'; ?>
</main>

<?php require_once APP_ROOT . '/includes/footer.php'; ?>
