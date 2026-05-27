<?php
/**
 * config/app.php — Configuración global del Panel CMS
 *
 * Define constantes que usa toda la aplicación.
 * ADMIN_ROOT se define en admin/index.php antes de cargar este archivo.
 *
 * ¡IMPORTANTE! Cambia APP_URL al dominio real en producción.
 */

/* ── Entorno ───────────────────────────────────────────────────────────────
 * 'development' → muestra errores PHP en pantalla.
 * 'production'  → oculta errores, solo loguea internamente.
 * -------------------------------------------------------------------------- */
define('APP_ENV',  'development');
define('APP_NAME', 'Portfolio CMS');

/*
 * APP_URL: URL base del panel SIN barra final.
 * Laragon con carpeta:   http://localhost/portafolio-roberto-vazquez/admin
 * Laragon con vhost:     http://portafolio.test/admin
 * Producción:            https://rcvazquez.com/admin
 */
define('APP_URL', 'http://localhost/portafolio-roberto-vazquez/admin');

/* ── Rutas del filesystem ──────────────────────────────────────────────────
 * Usadas en autoloader, render() y Database::getInstance().
 * -------------------------------------------------------------------------- */
define('CONFIG_PATH', ADMIN_ROOT . '/config');
define('SRC_PATH',    ADMIN_ROOT . '/src');
define('VIEWS_PATH',  ADMIN_ROOT . '/views');

/* ── Seguridad ─────────────────────────────────────────────────────────────
 * SESSION_NAME: nombre de la cookie de sesión (evita colisiones con el sitio público).
 * CSRF_TOKEN_NAME: clave usada en $_SESSION y en el campo hidden del formulario.
 * -------------------------------------------------------------------------- */
define('SESSION_NAME',    'portfolio_cms_session');
define('CSRF_TOKEN_NAME', '_csrf_token');

/* ── Visibilidad de errores PHP ────────────────────────────────────────────
 * En desarrollo: todos los errores visibles.
 * En producción: silenciados en pantalla, el servidor los loguea internamente.
 * -------------------------------------------------------------------------- */
if (APP_ENV === 'production') {
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
    error_reporting(0);
} else {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
}
