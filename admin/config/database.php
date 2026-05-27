<?php
/**
 * config/database.php — Credenciales de conexión a MySQL
 *
 * ¡ESTE ARCHIVO CONTIENE CREDENCIALES SENSIBLES!
 * Asegúrate de que esté en .gitignore antes de hacer cualquier commit.
 * En producción, usa variables de entorno en lugar de valores hardcodeados.
 *
 * Laragon por defecto:
 *   host     → 127.0.0.1
 *   port     → 3306
 *   username → root
 *   password → (vacío)
 */

return [
    'host'     => '127.0.0.1',
    'port'     => '3306',
    'dbname'   => 'portfolio_cms',
    'charset'  => 'utf8mb4',
    'username' => 'root',
    'password' => '',
];
