<?php
/**
 * includes/data/portfolio-data.php — Capa de datos del portafolio público
 *
 * Expone tres funciones públicas que leen de la BD y devuelven arrays en el
 * formato exacto que espera cada sección del portafolio:
 *
 *   getPublishedProjects() → para includes/sections/projects.php
 *   getExperiences()       → para includes/sections/experience.php
 *   getEducation()         → para includes/sections/education.php
 *
 * Si la BD no está disponible, cada función devuelve [] de forma silenciosa:
 * la página sigue cargando pero las secciones aparecen vacías.
 *
 * Las funciones de mapeo (map*Row) son privadas al archivo; solo se usan
 * internamente como callbacks de array_map para mantener la lógica limpia.
 */

require_once APP_ROOT . '/includes/data/db.php';


/* ══════════════════════════════════════════════════════════════════════
   PROYECTOS
   La BD guarda status como etiqueta de visualización directa:
     'En producción' | 'En desarrollo' | 'Finalizado' | 'Archivado' | 'draft'
   Solo 'draft' se excluye del portafolio (borrador no publicado).
══════════════════════════════════════════════════════════════════════ */

/**
 * Devuelve todos los proyectos no-borrador, ordenados por sort_order.
 *
 * @return array<int, array> Proyectos en el formato de projects.php
 */
function getPublishedProjects(): array
{
    $pdo = portfolio_db();
    if ($pdo === null) return [];

    $stmt = $pdo->prepare(
        "SELECT name, description, tags, status, url, github_url
         FROM   projects
         WHERE  status != 'draft'
         ORDER  BY sort_order ASC, id ASC"
    );
    $stmt->execute();

    return array_map('_mapProjectRow', $stmt->fetchAll());
}

/**
 * Mapea una fila de `projects` al formato que espera projects.php.
 *
 * @internal Solo para uso de getPublishedProjects() vía array_map.
 */
function _mapProjectRow(array $row): array
{
    return [
        'nombre'       => $row['name'],
        'descripcion'  => $row['description'],
        'tags'         => json_decode($row['tags'] ?? '[]', true) ?: [],
        /* status ya es la etiqueta española ('En producción', etc.) */
        'estado'       => $row['status'],
        'url'          => $row['url']        ?: null,
        'github'       => $row['github_url'] ?: null,
        'github_label' => 'Ver en GitHub',
        'insignia'     => null,
    ];
}


/* ══════════════════════════════════════════════════════════════════════
   EXPERIENCIA
   La BD guarda description como texto libre multilínea.
   Cada línea se convierte en un bullet del timeline.
══════════════════════════════════════════════════════════════════════ */

/**
 * Devuelve todas las experiencias laborales ordenadas por sort_order.
 *
 * @return array<int, array> Experiencias en el formato de experience.php
 */
function getExperiences(): array
{
    $pdo = portfolio_db();
    if ($pdo === null) return [];

    $stmt = $pdo->query(
        'SELECT company, role, description, start_date, end_date, is_current
         FROM   experiences
         ORDER  BY sort_order ASC, id ASC'
    );

    return array_map('_mapExperienceRow', $stmt->fetchAll());
}

/**
 * Mapea una fila de `experiences` al formato que espera experience.php.
 *
 * Cada línea no vacía de `description` se convierte en un bullet.
 * El período se formatea a partir de las fechas guardadas en la BD.
 *
 * @internal Solo para uso de getExperiences() vía array_map.
 */
function _mapExperienceRow(array $row): array
{
    /*
     * Convertir texto multilínea en array de bullets.
     * El admin debe ingresar cada logro/responsabilidad en una línea separada.
     * Las líneas vacías (espacios en blanco) se filtran.
     */
    $bullets = array_values(
        array_filter(
            array_map('trim', explode("\n", $row['description'] ?? '')),
            fn(string $line) => $line !== ''
        )
    );

    return [
        'rol'       => $row['role'],
        'empresa'   => $row['company'],
        /*
         * `ubicacion` no existe en el esquema actual de la BD.
         * La sección experience.php lo muestra de forma condicional
         * (no renderiza el separador · si está vacío).
         */
        'ubicacion' => '',
        'periodo'   => _formatPeriod(
            $row['start_date'],
            $row['end_date'],
            (bool) $row['is_current']
        ),
        'bullets'   => $bullets,
    ];
}

/**
 * Formatea un rango de fechas en texto legible para el período del timeline.
 *
 * Ejemplos:
 *   ('2025-12-01', '2026-05-01', false) → 'Dic 2025 – May 2026'
 *   ('2025-12-01', null,         true)  → 'Dic 2025 – Presente'
 *
 * @param string      $start     Fecha de inicio (YYYY-MM-DD)
 * @param string|null $end       Fecha de fin (YYYY-MM-DD) o null
 * @param bool        $isCurrent true si la posición está activa
 * @return string Período formateado
 */
function _formatPeriod(string $start, ?string $end, bool $isCurrent): string
{
    $meses = [
        '01' => 'Ene', '02' => 'Feb', '03' => 'Mar', '04' => 'Abr',
        '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Ago',
        '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dic',
    ];

    [$startYear, $startMonth] = array_pad(explode('-', $start), 3, '01');
    $startLabel = ($meses[$startMonth] ?? '') . ' ' . $startYear;

    if ($isCurrent || $end === null) {
        return "{$startLabel} – Presente";
    }

    [$endYear, $endMonth] = array_pad(explode('-', $end), 3, '01');
    $endLabel = ($meses[$endMonth] ?? '') . ' ' . $endYear;

    return "{$startLabel} – {$endLabel}";
}


/* ══════════════════════════════════════════════════════════════════════
   EDUCACIÓN
   La BD guarda los datos básicos (institución, título, campo, años).
   Los campos categorias/gancho del portafolio antiguo no existen en la BD;
   se devuelven como arrays/null vacíos para que el HTML los omita.
══════════════════════════════════════════════════════════════════════ */

/**
 * Devuelve toda la formación académica ordenada por sort_order.
 *
 * @return array<int, array> Educación en el formato de education.php
 */
function getEducation(): array
{
    $pdo = portfolio_db();
    if ($pdo === null) return [];

    $stmt = $pdo->query(
        'SELECT institution, degree, field, start_year, end_year, is_current
         FROM   education
         ORDER  BY sort_order ASC, id ASC'
    );

    return array_map('_mapEducationRow', $stmt->fetchAll());
}

/**
 * Mapea una fila de `education` al formato que espera education.php.
 *
 * @internal Solo para uso de getEducation() vía array_map.
 */
function _mapEducationRow(array $row): array
{
    $endLabel = $row['is_current'] ? 'Presente' : ($row['end_year'] ?? 'Presente');

    /* Título: "Técnico Superior en Análisis de Sistemas" */
    $titulo = $row['degree'];
    if (!empty($row['field'])) {
        $titulo .= ' en ' . $row['field'];
    }

    return [
        'titulo'      => $titulo,
        'institucion' => $row['institution'],
        'ubicacion'   => '',    /* No existe en el esquema actual */
        'periodo'     => $row['start_year'] . ' – ' . $endLabel,
        'descripcion' => null,  /* No existe en el esquema actual */
        'categorias'  => [],    /* No existe en el esquema actual */
        'gancho'      => null,  /* No existe en el esquema actual */
    ];
}
