<?php
/**
 * src/Core/Router.php — Router basado en patrones de URL
 *
 * Registra rutas con get() y post(), luego dispatch() las compara
 * contra la URL actual usando expresiones regulares.
 *
 * Parámetros dinámicos en el patrón:
 *   {id}   → captura dígitos,     ej. 'projects/edit/{id}' → $id = '5'
 *   {slug} → captura texto simple, ej. 'posts/{slug}'      → $slug = 'mi-post'
 *
 * Ejemplo de uso en index.php:
 *   $router->get('projects',           [ProjectController::class, 'index']);
 *   $router->get('projects/edit/{id}', [ProjectController::class, 'edit']);
 *   $router->post('projects/edit/{id}',[ProjectController::class, 'update']);
 */

namespace Core;

use RuntimeException;

class Router
{
    /**
     * Rutas registradas, agrupadas por método HTTP.
     * Estructura: ['GET' => ['pattern' => $handler], 'POST' => [...]]
     *
     * @var array<string, array<string, callable|array>>
     */
    private array $routes = [
        'GET'  => [],
        'POST' => [],
    ];

    /**
     * Registra una ruta GET.
     *
     * @param string         $pattern Patrón de URL (ej. 'projects/edit/{id}')
     * @param callable|array $handler [ControllerClass::class, 'method'] o closure
     */
    public function get(string $pattern, callable|array $handler): void
    {
        $this->routes['GET'][$pattern] = $handler;
    }

    /**
     * Registra una ruta POST.
     */
    public function post(string $pattern, callable|array $handler): void
    {
        $this->routes['POST'][$pattern] = $handler;
    }

    /**
     * Compara la URL actual con las rutas registradas y ejecuta el handler.
     * Si no hay coincidencia devuelve una respuesta 404.
     *
     * @param string $url    URL sin el prefijo base (ej. 'projects/edit/3')
     * @param string $method Método HTTP ('GET' o 'POST')
     */
    public function dispatch(string $url, string $method): void
    {
        $url    = trim($url, '/');
        $method = strtoupper($method);

        foreach ($this->routes[$method] ?? [] as $pattern => $handler) {
            $regex = $this->patternToRegex($pattern);

            if (preg_match($regex, $url, $matches)) {
                /*
                 * Filtrar solo las capturas con nombre (los parámetros dinámicos).
                 * preg_match incluye índices numéricos además de los nombrados;
                 * array_filter con ARRAY_FILTER_USE_KEY elimina los numéricos.
                 */
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                $this->callHandler($handler, array_values($params));
                return;
            }
        }

        http_response_code(404);
        echo '<h1 style="font-family:sans-serif;padding:2rem">404 — Página no encontrada</h1>';
    }

    /**
     * Convierte un patrón de ruta en una expresión regular.
     *
     * Transformaciones:
     *   {id}    → (?P<id>\d+)         solo dígitos
     *   {slug}  → (?P<slug>[^/]+)     cualquier carácter salvo /
     *   (resto) → cualquier otro {word} → (?P<word>[^/]+)
     */
    private function patternToRegex(string $pattern): string
    {
        /* {id} específicamente captura solo números */
        $pattern = preg_replace('/\{id\}/', '(?P<id>\d+)', $pattern);

        /* El resto de parámetros capturan cualquier segmento de URL */
        $pattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $pattern);

        return '#^' . $pattern . '$#';
    }

    /**
     * Invoca el handler del controlador con los parámetros de la URL.
     * Soporta arrays [Clase::class, 'método'] y closures.
     *
     * @param callable|array $handler
     * @param array          $params  Parámetros dinámicos capturados de la URL
     */
    private function callHandler(callable|array $handler, array $params): void
    {
        if (is_array($handler)) {
            [$class, $method] = $handler;
            $controller = new $class();
            $controller->$method(...$params);
        } else {
            $handler(...$params);
        }
    }
}
