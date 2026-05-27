<?php
/**
 * src/Core/Controller.php — Clase base para todos los Controllers
 *
 * Provee los métodos que todos los controllers necesitan:
 *   render()     → carga una vista dentro del layout admin
 *   redirect()   → redirige a una ruta del panel
 *   csrfToken()  → genera/recupera el token CSRF de la sesión
 *   verifyCsrf() → valida el token CSRF en cada POST
 *   abort()      → respuesta de error HTTP y termina la ejecución
 *   flash()      → almacena un mensaje temporal en sesión
 *   getFlash()   → recupera y borra el mensaje de sesión
 */

namespace Core;

abstract class Controller
{
    /**
     * Renderiza una vista dentro del layout del panel.
     *
     * Proceso:
     *   1. Extrae $data como variables locales disponibles en la vista.
     *   2. Captura el output de la vista en un buffer ($content).
     *   3. Incluye el layout, que utiliza $content para insertar el HTML.
     *
     * @param string $view       Ruta relativa a views/ sin extensión (ej. 'projects/index')
     * @param array  $data       Variables disponibles en la vista
     * @param bool   $withLayout false para vistas sin sidebar (ej. login)
     */
    protected function render(string $view, array $data = [], bool $withLayout = true): void
    {
        $viewPath = VIEWS_PATH . '/' . $view . '.php';

        if (!file_exists($viewPath)) {
            throw new \RuntimeException("Vista no encontrada: {$view}");
        }

        /* Convierte las claves del array en variables locales de la vista */
        extract($data, EXTR_SKIP);

        if ($withLayout) {
            /* Captura el HTML de la vista en una variable */
            ob_start();
            require $viewPath;
            $content = ob_get_clean();

            /* El layout usa $content para colocar el HTML en el área principal */
            require VIEWS_PATH . '/layouts/admin.php';
        } else {
            /* Vistas standalone como login, sin sidebar ni topbar */
            require $viewPath;
        }
    }

    /**
     * Redirige a una ruta relativa dentro del panel.
     * Siempre hace exit para detener el flujo del controlador.
     *
     * @param string $path Ruta relativa al panel (ej. 'projects', 'projects/edit/1')
     */
    protected function redirect(string $path = ''): never
    {
        $base = rtrim(APP_URL, '/');
        $path = ltrim($path, '/');
        header("Location: {$base}/{$path}");
        exit;
    }

    /**
     * Genera un token CSRF y lo almacena en la sesión.
     * Si ya existe un token en la sesión, lo reutiliza.
     *
     * Uso en la vista:
     *   <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $this->csrfToken() ?>">
     */
    protected function csrfToken(): string
    {
        if (empty($_SESSION[CSRF_TOKEN_NAME])) {
            $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
        }

        return $_SESSION[CSRF_TOKEN_NAME];
    }

    /**
     * Verifica que el token CSRF del POST coincida con el de la sesión.
     * Usa hash_equals() para comparación en tiempo constante (evita timing attacks).
     * Rota el token después de cada verificación exitosa.
     *
     * Termina con 403 si el token es inválido o no existe.
     */
    protected function verifyCsrf(): void
    {
        $submitted = $_POST[CSRF_TOKEN_NAME] ?? '';
        $stored    = $_SESSION[CSRF_TOKEN_NAME] ?? '';

        if (empty($submitted) || empty($stored) || !hash_equals($stored, $submitted)) {
            http_response_code(403);
            exit('403 Forbidden — Token de seguridad inválido. Vuelve atrás e intenta de nuevo.');
        }

        /* Rotar el token: el mismo token no puede usarse dos veces */
        unset($_SESSION[CSRF_TOKEN_NAME]);
    }

    /**
     * Almacena un mensaje flash en la sesión para mostrarlo en la siguiente petición.
     *
     * @param string $type    Tipo de alerta: 'success' | 'error' | 'warning'
     * @param string $message Texto del mensaje
     */
    protected function flash(string $type, string $message): void
    {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }

    /**
     * Recupera y elimina el mensaje flash de la sesión.
     * Devuelve null si no hay ninguno.
     *
     * @return array{type: string, message: string}|null
     */
    protected function getFlash(): ?array
    {
        if (!isset($_SESSION['flash'])) {
            return null;
        }

        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);

        return $flash;
    }

    /**
     * Devuelve una respuesta de error HTTP y detiene la ejecución.
     *
     * @param int    $code    Código HTTP (403, 404, 500, etc.)
     * @param string $message Mensaje opcional
     */
    protected function abort(int $code, string $message = ''): never
    {
        http_response_code($code);
        exit($message ?: "Error {$code}");
    }
}
