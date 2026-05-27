-- ============================================================
-- database/seed.sql — Datos iniciales del Panel CMS
-- ------------------------------------------------------------
-- Ejecuta este archivo UNA SOLA VEZ después de schema.sql.
-- Crea el usuario admin con contraseña "admin123".
--
-- IMPORTANTE: Cambia la contraseña desde el panel en producción.
--
-- Hash generado con:
--   php -r "echo password_hash('admin123', PASSWORD_BCRYPT);"
-- ============================================================

USE `portfolio_cms`;

-- ── Usuario administrador inicial ──────────────────────────────────────────
INSERT INTO `admin_users` (`name`, `email`, `password_hash`) VALUES (
  'Roberto Vázquez',
  'rcvazquezantelo2006@gmail.com',
  '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
  -- Contraseña: admin123  ← cámbiala después del primer login
);
