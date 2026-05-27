<?php
/**
 * src/Middleware/AuthMiddleware.php — Protección de rutas autenticadas
 *
 * Se ejecuta en index.php ANTES de despachar cualquier ruta.
 * Si el usuario no tiene sesión activa y la ruta no es pública,
 * redirige al formulario de login.
 *
 * Rutas públicas (no requieren autenticación):
 *   - login   → formulario de acceso
 *   - logout  → cierre de sesión (maneja sesión vacía sin error)
 */

namespace Middleware;

use Core\Auth;

class AuthMiddleware
{
    /**
     * Verifica si la petición actual requiere autenticación.
     *
     * @param string   $currentPath URL actual limpia (ej. 'projects/edit/1')
     * @param string[] $publicPaths Rutas que no requieren login
     */
    public static function handle(string $currentPath, array $publicPaths = []): void
    {
        $path = trim($currentPath, '/');

        /* Las rutas públicas no necesitan verificación */
        if (in_array($path, $publicPaths, true)) {
            return;
        }

        /* Si no hay sesión activa, redirigir al login */
        if (!Auth::check()) {
            $base = rtrim(APP_URL, '/');
            header("Location: {$base}/login");
            exit;
        }
    }
}
