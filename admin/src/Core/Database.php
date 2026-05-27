<?php
/**
 * src/Core/Database.php — Singleton de conexión PDO
 *
 * Garantiza una sola instancia de PDO por ciclo de petición (Singleton).
 * Todos los Models acceden a la base de datos a través de este archivo.
 *
 * PDO se configura con:
 *   ERRMODE_EXCEPTION  → los errores SQL lanzan excepciones (fácil de depurar)
 *   FETCH_ASSOC        → los resultados son arrays asociativos por defecto
 *   EMULATE_PREPARES   → false, usa prepared statements reales del driver MySQL
 *                        (mejor seguridad y performance)
 */

namespace Core;

use PDO;
use PDOException;
use RuntimeException;

class Database
{
    /** @var PDO|null Instancia única compartida entre todos los Models */
    private static ?PDO $instance = null;

    /* Constructor y clone privados: impiden crear instancias directamente */
    private function __construct() {}
    private function __clone() {}

    /**
     * Devuelve la instancia única de PDO.
     * La primera llamada crea la conexión; las siguientes la reutilizan.
     *
     * @throws RuntimeException si las credenciales son incorrectas o MySQL no está disponible
     */
    public static function getInstance(): PDO
    {
        if (self::$instance !== null) {
            return self::$instance;
        }

        $cfg = require CONFIG_PATH . '/database.php';

        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            $cfg['host'],
            $cfg['port'],
            $cfg['dbname'],
            $cfg['charset']
        );

        try {
            self::$instance = new PDO($dsn, $cfg['username'], $cfg['password'], [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            /*
             * En producción nunca expongas el mensaje original de PDOException:
             * puede revelar host, usuario o estructura de la base de datos.
             * Loguea internamente y muestra un mensaje genérico.
             */
            if (APP_ENV === 'production') {
                error_log('[CMS] DB connection error: ' . $e->getMessage());
                throw new RuntimeException('No se pudo conectar a la base de datos.');
            }

            throw new RuntimeException('Database error: ' . $e->getMessage());
        }

        return self::$instance;
    }
}
