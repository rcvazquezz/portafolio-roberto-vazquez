<?php
/**
 * admin/index.php — Front Controller del Panel CMS
 *
 * Punto de entrada único para TODAS las peticiones del panel.
 * El archivo .htaccess redirige cada petición aquí vía ?url=...
 *
 * Responsabilidades (en orden):
 *   1. Definir ADMIN_ROOT (constante del filesystem)
 *   2. Cargar la configuración global
 *   3. Registrar el autoloader PSR-4 manual
 *   4. Iniciar la sesión PHP
 *   5. Ejecutar el middleware de autenticación
 *   6. Registrar todas las rutas
 *   7. Despachar la petición al controller correspondiente
 */

declare(strict_types=1);

/* ── 1. Raíz del directorio admin ──────────────────────────────────────── */
define('ADMIN_ROOT', __DIR__);

/* ── 2. Configuración global (constantes APP_*, SESSION_NAME, etc.) ────── */
require_once ADMIN_ROOT . '/config/app.php';

/* ── 3. Autoloader PSR-4 manual ────────────────────────────────────────── *
 * Mapea cada namespace a su directorio correspondiente en src/.
 * Esto evita usar Composer solo para un CMS liviano.
 *
 * Namespace        → Directorio
 * Core\Database    → src/Core/Database.php
 * Controllers\Auth → src/Controllers/AuthController.php
 * Models\Project   → src/Models/Project.php
 * Middleware\Auth  → src/Middleware/AuthMiddleware.php
 * -------------------------------------------------------------------------- */
spl_autoload_register(function (string $class): void {
    $namespaceMap = [
        'Core\\'        => ADMIN_ROOT . '/src/Core/',
        'Controllers\\' => ADMIN_ROOT . '/src/Controllers/',
        'Models\\'      => ADMIN_ROOT . '/src/Models/',
        'Middleware\\'  => ADMIN_ROOT . '/src/Middleware/',
    ];

    foreach ($namespaceMap as $namespace => $directory) {
        if (str_starts_with($class, $namespace)) {
            $relativePath = substr($class, strlen($namespace));
            $filePath     = $directory . str_replace('\\', '/', $relativePath) . '.php';

            if (file_exists($filePath)) {
                require_once $filePath;
            }
            return;
        }
    }
});

/* ── 4. Sesión PHP ─────────────────────────────────────────────────────── */
session_name(SESSION_NAME);
session_start();

/* ── 5. URL y método HTTP actuales ────────────────────────────────────── */
$currentUrl    = $_GET['url'] ?? '';
$currentMethod = $_SERVER['REQUEST_METHOD'];

/* ── 6. Middleware de autenticación ────────────────────────────────────── */
\Middleware\AuthMiddleware::handle($currentUrl, publicPaths: ['login', 'logout']);

/* ── 7. Registro de rutas ──────────────────────────────────────────────── */
$router = new \Core\Router();

/* ── Auth ── */
$router->get('login',  [\Controllers\AuthController::class, 'showLogin']);
$router->post('login', [\Controllers\AuthController::class, 'login']);
$router->get('logout', [\Controllers\AuthController::class, 'logout']);

/* ── Dashboard ── */
$router->get('',          [\Controllers\DashboardController::class, 'index']);
$router->get('dashboard', [\Controllers\DashboardController::class, 'index']);

/* ── Projects ── */
$router->get( 'projects',              [\Controllers\ProjectController::class, 'index']);
$router->get( 'projects/create',       [\Controllers\ProjectController::class, 'create']);
$router->post('projects/create',       [\Controllers\ProjectController::class, 'store']);
$router->get( 'projects/edit/{id}',    [\Controllers\ProjectController::class, 'edit']);
$router->post('projects/edit/{id}',    [\Controllers\ProjectController::class, 'update']);
$router->post('projects/delete/{id}',  [\Controllers\ProjectController::class, 'destroy']);

/* ── Experiences ── */
$router->get( 'experiences',             [\Controllers\ExperienceController::class, 'index']);
$router->get( 'experiences/create',      [\Controllers\ExperienceController::class, 'create']);
$router->post('experiences/create',      [\Controllers\ExperienceController::class, 'store']);
$router->get( 'experiences/edit/{id}',   [\Controllers\ExperienceController::class, 'edit']);
$router->post('experiences/edit/{id}',   [\Controllers\ExperienceController::class, 'update']);
$router->post('experiences/delete/{id}', [\Controllers\ExperienceController::class, 'destroy']);

/* ── Education ── */
$router->get( 'education',             [\Controllers\EducationController::class, 'index']);
$router->get( 'education/create',      [\Controllers\EducationController::class, 'create']);
$router->post('education/create',      [\Controllers\EducationController::class, 'store']);
$router->get( 'education/edit/{id}',   [\Controllers\EducationController::class, 'edit']);
$router->post('education/edit/{id}',   [\Controllers\EducationController::class, 'update']);
$router->post('education/delete/{id}', [\Controllers\EducationController::class, 'destroy']);

/* ── Contacts ── */
$router->get( 'contacts',              [\Controllers\ContactController::class, 'index']);
$router->post('contacts/read/{id}',    [\Controllers\ContactController::class, 'markAsRead']);
$router->post('contacts/delete/{id}',  [\Controllers\ContactController::class, 'destroy']);

/* ── 8. Despachar petición ─────────────────────────────────────────────── */
$router->dispatch($currentUrl, $currentMethod);
