<?php
/**
 * includes/data/db.php — Conexión PDO del portafolio público
 *
 * Reutiliza las credenciales de admin/config/database.php para que
 * portafolio y panel CMS apunten a la misma base de datos sin duplicar
 * la configuración.
 *
 * Patrón: Singleton vía variable estática.
 *   · Primera llamada → crea la conexión PDO.
 *   · Llamadas sucesivas → devuelve la instancia existente.
 *   · Fallo de conexión → loguea internamente y devuelve null.
 *     Las secciones que usan esta función devuelven [] en ese caso,
 *     por lo que la página sigue funcionando con secciones vacías.
 *
 * @return PDO|null Instancia PDO o null si la BD no está disponible.
 */
function portfolio_db(): ?PDO
{
    /* false = sin inicializar; null = error de conexión (no reintentar) */
    static $pdo = false;

    if ($pdo !== false) {
        return $pdo;
    }

    $cfg = require APP_ROOT . '/admin/config/database.php';

    $dsn = sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=%s',
        $cfg['host'],
        $cfg['port'],
        $cfg['dbname'],
        $cfg['charset']
    );

    try {
        $pdo = new PDO($dsn, $cfg['username'], $cfg['password'], [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    } catch (PDOException $e) {
        /*
         * No exponer credenciales ni detalles técnicos al visitante.
         * El log interno es suficiente para que el desarrollador diagnostique.
         */
        error_log('[Portfolio] DB connection error: ' . $e->getMessage());
        $pdo = null;
    }

    return $pdo;
}
