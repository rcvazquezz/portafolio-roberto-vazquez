-- Actualiza la tabla `experiences` para que coincida con el CV actualizado
-- (Roberto_Vazquez_Antelo_CV_FullStack_Developer.pdf).
-- Ejecutar en phpMyAdmin / tu cliente MySQL contra la base de datos del portafolio.

USE `u123661417_portfolio`; -- Cambia el nombre de la BD si es distinto en tu entorno

DELETE FROM `experiences`;
ALTER TABLE `experiences` AUTO_INCREMENT = 1;

INSERT INTO `experiences`
  (`company`, `location`, `role`, `description`, `start_date`, `end_date`, `is_current`, `sort_order`)
VALUES

-- 1) Consejo Nacional Electoral (CNE) — Dic 2025 – May 2026
(
  'Consejo Nacional Electoral (CNE)',
  'Guanare, Portuguesa, Venezuela',
  'Analista y Desarrollador de Sistemas Full Stack',
  'Lideré el diseño y desarrollo de una aplicación web Full Stack (PHP, MySQL, Tailwind CSS, JavaScript) que automatizó los flujos de trabajo administrativos del CNE, reduciendo el tiempo de procesamiento de trámites en un 60% y eliminando cuellos de botella operativos críticos.
Arquitecté el modelo de base de datos relacional en MySQL: normalización hasta 3FN, índices compuestos, vistas y políticas de integridad referencial, mejorando la eficiencia de consultas críticas en un 40% y reforzando la seguridad de datos sensibles.
Implementé control de acceso basado en roles (RBAC) y módulo de trazabilidad con seguimiento en tiempo real, elevando la auditabilidad del sistema y reduciendo incidencias críticas en un 35%.
Ejecuté auditorías técnicas de sistemas de alta disponibilidad e impulsé protocolos de mejora continua mediante integración de REST APIs y automatización de reportes, garantizando disponibilidad operativa del 99%.
Gestioné el ciclo completo del software: levantamiento de requisitos, arquitectura, desarrollo, pruebas, despliegue y mantenimiento correctivo y preventivo de infraestructura de hardware y software.',
  '2025-12-01', '2026-05-01', 0, 0
),

-- 2) Freelancer — Desarrollo de Sistema Deportivo — Jun 2025 – Oct 2025
(
  'Freelance',
  NULL,
  'Freelancer — Desarrollo de Sistema Deportivo',
  'Desarrollo de requerimientos funcionales asignados por el jefe de proyecto.
Stack: PHP · JavaScript · CodeIgniter',
  '2025-06-01', '2025-10-01', 0, 1
),

-- 3) Freelancer Front-End Developer — Najo Consultores — Feb 2024 – May 2025
(
  'Najo Consultores',
  'Remoto',
  'Freelancer Front-End Developer',
  'Maquetación de interfaces de usuario (UI) con enfoque en experiencia de usuario.
Programación de funcionalidades frontend con JavaScript y Vue.js.
Consumo de APIs REST para conectar el sistema con servicios backend.
Stack: JavaScript · Vue.js · Vuetify',
  '2024-02-01', '2025-05-01', 0, 2
);
