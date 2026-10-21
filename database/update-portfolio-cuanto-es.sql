-- Actualiza proyectos, experiencias y formación según el CV de octubre de 2026
-- (CV_Roberto_Vazquez_ATS.pdf): añade Cuánto Es como proyecto principal y
-- deja la experiencia del CNE solo con hechos.
--
-- Ejecutar en phpMyAdmin con la base de datos del portafolio seleccionada
-- (en producción: u123661417_portfolio). Se puede ejecutar más de una vez.
-- Los proyectos se buscan por nombre porque los id pueden variar entre entornos.

SET NAMES utf8mb4;
START TRANSACTION;

-- ══════════════════════════════════════════════════════════════════════
-- PROYECTOS
-- Primera línea de description = párrafo; cada línea siguiente = bullet.
-- ══════════════════════════════════════════════════════════════════════

-- 1) Cuánto Es — proyecto principal
INSERT INTO `projects` (`name`, `description`, `tags`, `status`, `url`, `github_url`, `sort_order`, `insignia`)
SELECT
  'Cuánto Es — App de gestión para bodegas y pequeños comercios de Venezuela',
  'App de gestión para bodegas y pequeños comercios de Venezuela que he diseñado, desarrollado y desplegado yo solo, de principio a fin. Está en producción y los primeros negocios ya la usan.
Precios en dólares, bolívares y pesos colombianos con la tasa oficial del BCV actualizada cada día.
Ventas con cobros mixtos y vuelto, fiados, inventario, cierre de caja, proveedores y reportes de ganancia.
Funciona sin internet (PWA) y sincroniza las ventas sola al recuperar la conexión, sin duplicarlas.
Apps para Android y Windows, importación desde Excel, lector de código de barras e impresión de tickets por Bluetooth.
Automatizaciones con n8n y WhatsApp Cloud API: verificación de cuentas, recordatorios de cobro y resumen diario de ventas.',
  JSON_ARRAY('PHP', 'CodeIgniter 4', 'MariaDB', 'Vue 3', 'Vite', 'PWA', 'n8n', 'WhatsApp Cloud API'),
  'En producción',
  'https://micuantoes.com',
  NULL,
  0,
  'Proyecto propio'
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `projects` WHERE `name` LIKE 'Cuánto Es%');

-- Si ya existía (p. ej. creado desde el panel), normalizar título y orden.
-- Título sin punto final y con raya, como el resto de proyectos.
UPDATE `projects`
SET    `name`       = 'Cuánto Es — App de gestión para bodegas y pequeños comercios de Venezuela',
       `url`        = 'https://micuantoes.com',
       `status`     = 'En producción',
       `insignia`   = 'Proyecto propio',
       `sort_order` = 0
WHERE  `name` LIKE 'Cuánto Es%';

-- 2) Zamurove — corregir el tag de base de datos
UPDATE `projects`
SET    `tags` = JSON_ARRAY('TypeScript', 'Next.js', 'PostgreSQL (Prisma)', 'NextAuth', 'Tailwind CSS'),
       `sort_order` = 1
WHERE  `name` LIKE 'Zamurove%';

-- 3) DevLink — descripción del CV
UPDATE `projects`
SET    `description` = 'Marketplace que conecta desarrolladores con clientes, con perfiles, gestión de proyectos, roles y búsqueda con filtros.',
       `sort_order` = 2
WHERE  `name` LIKE 'DevLink%';

-- 4) Sistema de Gestión de Trámites (CNE) — sin enlace al repositorio
UPDATE `projects`
SET    `description` = 'Aplicación web interna que desarrollé para el CNE para gestionar trámites: solicitudes, expedientes, notificaciones y reportes, con 5 roles de usuario, registro de auditoría y copias de seguridad automáticas por correo.',
       `github_url` = NULL,
       `sort_order` = 3
WHERE  `name` = 'Sistema de Gestión de Trámites';

-- 5) Puyo Code — sin web pública, así que sin estado "En producción"
UPDATE `projects`
SET    `status` = '',
       `sort_order` = 4
WHERE  `name` = 'Puyo Code';


-- ══════════════════════════════════════════════════════════════════════
-- EXPERIENCIA (orden: más reciente primero)
-- Una línea de description = un bullet del timeline.
-- Las posiciones activas (is_current = 1) se muestran como "Oct 2026 – Actualidad".
-- ══════════════════════════════════════════════════════════════════════

DELETE FROM `experiences`;
ALTER TABLE `experiences` AUTO_INCREMENT = 1;

INSERT INTO `experiences`
  (`company`, `location`, `role`, `description`, `start_date`, `end_date`, `is_current`, `sort_order`)
VALUES

-- 1) Cuánto Es — Oct 2026 – Actualidad
(
  'Cuánto Es (proyecto propio)',
  'Remoto',
  'Fundador y Desarrollador Full Stack',
  'Desarrollé desde cero una aplicación SaaS de gestión para bodegas y pequeños comercios, con más de 20 módulos: ventas, cobros mixtos con vuelto, fiados, inventario, cierre de caja, proveedores y reportes de ganancia.
Construí el backend con PHP y CodeIgniter 4 (arquitectura MVC, API REST, MariaDB) y un control de acceso por roles para dueños y cajeros con permisos limitados.
Implementé una PWA con Vue 3 y Vite que funciona sin conexión y sincroniza las ventas al recuperar la señal sin duplicados, usando un identificador único (UUID) por operación.
Integré la tasa oficial del BCV con actualización diaria y precios en 3 monedas (USD, VES y COP), eliminando el cálculo manual de precios en bolívares.
Automaticé procesos con n8n y WhatsApp Cloud API: verificación de cuentas por código, recordatorios de cobro y resumen diario de ventas.
Publiqué la app en web, Android y Windows; gestiono el despliegue en servidor Linux, la monitorización de errores y el soporte a usuarios.',
  '2026-10-01', NULL, 1, 0
),

-- 2) Consejo Nacional Electoral (CNE) — Dic 2025 – May 2026
(
  'Consejo Nacional Electoral (CNE)',
  'Guanare, Venezuela',
  'Analista y Desarrollador de Sistemas',
  'Desarrollé una aplicación web interna de gestión de trámites con PHP, MySQL, JavaScript y Tailwind CSS.
Diseñé la base de datos relacional, con más de 10 tablas normalizadas.
Implementé control de acceso con 5 roles: atención al ciudadano, funcionario, coordinador, director y administrador.
Añadí un registro de auditoría de cada operación.
Automaticé las copias de seguridad de la base de datos con envío programado por correo electrónico.
Di soporte técnico de nivel 1 y 2 a los usuarios de la institución.',
  '2025-12-01', '2026-05-01', 0, 1
),

-- 3) Sistema deportivo — Jun 2025 – Oct 2025
(
  'Cliente en Quito, Ecuador',
  'Remoto',
  'Desarrollador PHP Freelance — Sistema deportivo',
  'Desarrollé los módulos de equipos, deportistas y resultados con PHP y CodeIgniter, y la visualización de estadísticas con JavaScript.',
  '2025-06-01', '2025-10-01', 0, 2
),

-- 4) Najo Consultores — Feb 2024 – May 2025
(
  'Najo Consultores',
  'Remoto',
  'Desarrollador Front-End Freelance',
  'Maqueté interfaces de usuario responsive con Vue.js y Vuetify y las conecté a servicios backend mediante API REST.',
  '2024-02-01', '2025-05-01', 0, 3
);


-- ══════════════════════════════════════════════════════════════════════
-- FORMACIÓN
-- ══════════════════════════════════════════════════════════════════════

UPDATE `education`
SET    `field`      = 'Análisis de Sistemas, mención Desarrollo Web',
       `location`   = 'Guanare, Venezuela',
       `start_year` = 2023,
       `end_year`   = 2026,
       `is_current` = 0
WHERE  `institution` LIKE 'IUTEPI%';

COMMIT;
