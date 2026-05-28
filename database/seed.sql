-- ============================================================
-- database/seed.sql — Datos iniciales del portafolio
-- ============================================================
-- Ejecuta este archivo en HeidiSQL (o cualquier cliente MySQL)
-- DESPUÉS de haber aplicado las migraciones en migration.sql.
--
-- Contiene el contenido real del portafolio de Roberto Vázquez:
--   · 3 proyectos destacados
--   · 1 experiencia laboral
--   · 1 entrada de formación académica
--
-- ORDEN REQUERIDO: primero migration.sql, luego este archivo.
-- ============================================================

USE `portfolio_cms`;

-- ── Proyectos ──────────────────────────────────────────────────────────────
-- status almacena la etiqueta de visualización directa (no 'published').
-- Valores válidos: 'En producción' | 'En desarrollo' | 'Finalizado' | 'Archivado' | 'draft'
-- tags: array JSON de strings con las tecnologías del proyecto.
-- insignia: badge opcional de contexto (ej. "Sistema institucional").

INSERT INTO `projects`
  (`name`, `description`, `tags`, `status`, `url`, `github_url`, `insignia`, `sort_order`)
VALUES
  (
    'DevLink — Marketplace de Desarrolladores',
    'Plataforma web tipo marketplace que conecta desarrolladores con clientes. Diseñada con foco en escalabilidad y experiencia de usuario, con desarrollo asistido por IA para optimizar flujos y acelerar tiempos de entrega.',
    '["PHP","JavaScript","MySQL","Tailwind CSS"]',
    'En producción',
    'https://devlink.nygaccesorios.com/',
    NULL,
    NULL,
    0
  ),
  (
    'Puyo Code',
    'Plataforma web para mi agencia de desarrollo, enfocada en la creación de soluciones digitales a medida, optimización de rendimiento y estrategias de presencia online.',
    '["PHP","JavaScript","MySQL","Tailwind CSS"]',
    'En producción',
    NULL,
    'https://github.com/rcvazquezz/puyo-code',
    'Agencia de Desarrollo',
    1
  ),
  (
    'Sistema de Gestión de Trámites',
    'Aplicación web integral para la automatización de flujos operativos internos, optimizando la gestión y trazabilidad de trámites institucionales.',
    '["PHP","MySQL","Tailwind CSS","JavaScript"]',
    'Finalizado',
    NULL,
    'https://github.com/rcvazquezz/sistema_cne',
    'Sistema institucional',
    2
  );


-- ── Experiencia laboral ─────────────────────────────────────────────────────
-- description: cada línea de texto = un bullet en el timeline del portafolio.
-- start_date / end_date: formato DATE (YYYY-MM-01).
-- is_current: 0 = finalizado, 1 = posición activa.

INSERT INTO `experiences`
  (`company`, `location`, `role`, `description`, `start_date`, `end_date`, `is_current`, `sort_order`)
VALUES
  (
    'Consejo Nacional Electoral',
    'Guanare, Portuguesa',
    'Analista y Desarrollador de Sistemas · Soporte Técnico',
    'Diseñé y desarrollé una aplicación web Full Stack (PHP, MySQL, Tailwind) para la automatización de flujos de trabajo institucionales. Esta solución redujo significativamente los tiempos de procesamiento administrativo y mejoró la precisión en el manejo de expedientes.
Lideré auditorías técnicas de sistemas críticos y validación de infraestructura de hardware. Implementé protocolos de mejora continua que garantizaron la disponibilidad operativa y la integridad de los datos en entornos de alta exigencia.
Arquitecto de bases de datos relacionales, optimizando consultas y aplicando políticas de seguridad e integridad referencial, logrando una mayor eficiencia en la recuperación y protección de información sensible.
Gestioné el soporte técnico integral (Nivel 1 y 2) para más de 50 equipos, estableciendo procesos de resolución de incidencias que minimizaron los tiempos de inactividad operativa.',
    '2025-12-01',
    '2026-05-01',
    0,
    0
  );


-- ── Formación académica ─────────────────────────────────────────────────────
-- degree + field: se combinan en el portafolio como "degree en field".
-- skills: formato "Categoría: habilidad1, habilidad2" (una línea por categoría).
-- gancho: nota técnica que aparece al pie de la card con flecha →.

INSERT INTO `education`
  (`institution`, `location`, `degree`, `field`,
   `start_year`, `end_year`, `is_current`,
   `description`, `skills`, `gancho`, `sort_order`)
VALUES
  (
    'IUTEPI — Instituto Universitario de Tecnología para la Informática',
    'Guanare, Portuguesa',
    'Técnico Superior Universitario',
    'Análisis de Sistemas | Mención Desarrollo Web',
    2023,
    2026,
    1,
    'Formación orientada a la ingeniería de software, arquitectura de sistemas y despliegue de aplicaciones web escalables.',
    'Arquitectura & Software: Ciclo de vida del software, Análisis de Sistemas, Ingeniería de Requisitos
Data & Lógica: Bases de Datos Relacionales, Lógica de Programación, Algoritmia
Despliegue: Sistemas de Gestión Institucional, Integración de Servicios',
    'Enfoque técnico: Del levantamiento de requerimientos a la puesta en producción.',
    0
  );
