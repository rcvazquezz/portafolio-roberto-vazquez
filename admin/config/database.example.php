<?php
/**
 * admin/config/database.example.php — Plantilla de credenciales MySQL
 *
 * Copia este archivo como database.php y completa los valores de tu entorno.
 * database.php está en .gitignore y NUNCA debe subirse al repositorio.
 *
 * Laragon (desarrollo local):
 *   host     → 127.0.0.1
 *   port     → 3306
 *   username → root
 *   password → (vacío)
 *
 * Producción: usa variables de entorno en lugar de valores hardcodeados.
 */

return [
    'host'     => '127.0.0.1',
    'port'     => '3306',
    'dbname'   => 'portfolio_cms',
    'charset'  => 'utf8mb4',
    'username' => 'root',
    'password' => '',
];
