<?php
/**
 * src/Core/Auth.php — Autenticación basada en sesiones PHP
 *
 * Maneja login, logout y verificación de sesión.
 * Compara contraseñas contra hashes bcrypt almacenados en la tabla admin_users.
 *
 * Seguridad implementada:
 *   - password_verify()         → compara contra bcrypt, nunca texto plano
 *   - session_regenerate_id()   → previene session fixation attack en cada login
 *   - Destrucción completa      → logout limpia cookie, variables y destruye sesión
 */

namespace Core;

use PDO;

class Auth
{
    /** Clave bajo la que se guarda el usuario en $_SESSION */
    private static string $sessionKey = 'admin_user';

    /**
     * Intenta autenticar con email y contraseña.
     *
     * Proceso:
     *   1. Busca el usuario por email en la DB.
     *   2. Verifica la contraseña con password_verify() (bcrypt).
     *   3. Si es válida, regenera el ID de sesión y guarda el usuario.
     *
     * @return bool true si las credenciales son correctas, false si no.
     */
    public static function attempt(string $email, string $password): bool
    {
        $pdo  = Database::getInstance();
        $stmt = $pdo->prepare(
            'SELECT id, name, email, password_hash
             FROM   admin_users
             WHERE  email = ?
             LIMIT  1'
        );
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            return false;
        }

        /*
         * Regenerar el ID de sesión ANTES de escribir datos sensibles.
         * true = destruye también la sesión vieja en el servidor.
         */
        session_regenerate_id(true);

        /* Guardamos los datos del usuario sin el hash de contraseña */
        $_SESSION[self::$sessionKey] = [
            'id'    => $user['id'],
            'name'  => $user['name'],
            'email' => $user['email'],
        ];

        return true;
    }

    /**
     * Verifica si hay una sesión de admin activa.
     */
    public static function check(): bool
    {
        return isset($_SESSION[self::$sessionKey]);
    }

    /**
     * Devuelve los datos del admin autenticado, o null si no hay sesión.
     *
     * @return array{id: int, name: string, email: string}|null
     */
    public static function user(): ?array
    {
        return $_SESSION[self::$sessionKey] ?? null;
    }

    /**
     * Destruye la sesión por completo:
     *   1. Vacía las variables de sesión.
     *   2. Elimina la cookie de sesión del navegador.
     *   3. Destruye el archivo/registro de sesión en el servidor.
     */
    public static function logout(): void
    {
        $_SESSION = [];

        /* Expirar la cookie de sesión en el navegador del usuario */
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
    }
}
