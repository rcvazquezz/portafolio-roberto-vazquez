-- ============================================================
-- database/migration.sql — Columnas nuevas del portafolio CMS
-- ============================================================
-- Ejecuta este archivo en HeidiSQL ANTES de seed.sql.
-- Agrega los campos que el portafolio necesita para mostrar
-- el contenido exacto del panel de administración.
--
-- Cada ALTER TABLE usa IF NOT EXISTS implícito mediante el patrón
-- de ignorar errores (ejecutar con "Continuar en caso de error"
-- activado en HeidiSQL, o copiar bloque por bloque).
-- ============================================================

USE `portfolio_cms`;

-- ── projects: badge de contexto opcional ────────────────────
-- Aparece como badge oscuro bajo el título del proyecto.
-- Ejemplo: "Sistema institucional", "Agencia de Desarrollo".
ALTER TABLE `projects`
  ADD COLUMN `insignia` VARCHAR(150) NULL DEFAULT NULL
  AFTER `sort_order`;


-- ── experiences: ubicación geográfica ───────────────────────
-- Se muestra junto al nombre de la empresa en el timeline.
-- Ejemplo: "Guanare, Portuguesa".
ALTER TABLE `experiences`
  ADD COLUMN `location` VARCHAR(150) NULL DEFAULT NULL
  AFTER `company`;


-- ── education: campos enriquecidos ──────────────────────────

-- Ciudad/región de la institución.
ALTER TABLE `education`
  ADD COLUMN `location` VARCHAR(150) NULL DEFAULT NULL
  AFTER `institution`;

-- Párrafo descriptivo de la formación (texto libre).
ALTER TABLE `education`
  ADD COLUMN `description` TEXT NULL DEFAULT NULL
  AFTER `field`;

-- Áreas de conocimiento agrupadas por categoría.
-- Formato almacenado: "Categoría: habilidad1, habilidad2" (una por línea).
-- El portafolio parsea este texto y lo renderiza como badges agrupados.
ALTER TABLE `education`
  ADD COLUMN `skills` TEXT NULL DEFAULT NULL
  AFTER `description`;

-- Nota técnica al pie de la card (se muestra con →).
-- Ejemplo: "Enfoque técnico: Del levantamiento de requerimientos...".
ALTER TABLE `education`
  ADD COLUMN `gancho` VARCHAR(500) NULL DEFAULT NULL
  AFTER `skills`;
