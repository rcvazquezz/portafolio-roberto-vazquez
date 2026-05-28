<?php
/**
 * includes/data/portfolio-data.php — Capa de datos del portafolio público
 *
 * Expone tres funciones que leen de la BD y devuelven arrays en el formato
 * exacto que espera cada sección del portafolio:
 *
 *   getPublishedProjects() → includes/sections/projects.php
 *   getExperiences()       → includes/sections/experience.php
 *   getEducation()         → includes/sections/education.php
 *
 * Si la BD no está disponible, cada función devuelve [] sin romper la página.
 *
 * Funciones con prefijo _ son internas (helpers de mapeo), no deben llamarse
 * desde fuera de este archivo.
 */

require_once APP_ROOT . '/includes/data/db.php';


/* ══════════════════════════════════════════════════════════════════════
   PROYECTOS
   status almacena la etiqueta de visualización directamente:
     'En producción' | 'En desarrollo' | 'Finalizado' | 'Archivado'
   'draft' es el único valor que excluye el proyecto del portafolio.
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
        "SELECT name, description, tags, status, url, github_url, insignia
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
 * Campos de la BD → campos del portafolio:
 *   name        → nombre
 *   description → descripcion
 *   tags (JSON) → tags (array PHP)
 *   status      → estado  (ya es la etiqueta española)
 *   url         → url
 *   github_url  → github
 *   insignia    → insignia (badge de contexto, opcional)
 *
 * @internal Usado solo por getPublishedProjects() como callback de array_map.
 */
function _mapProjectRow(array $row): array
{
    return [
        'nombre'       => $row['name'],
        'descripcion'  => $row['description'],
        'tags'         => json_decode($row['tags'] ?? '[]', true) ?: [],
        'estado'       => $row['status'],
        'url'          => $row['url']        ?: null,
        'github'       => $row['github_url'] ?: null,
        'github_label' => 'Ver en GitHub',
        'insignia'     => $row['insignia']   ?: null,
    ];
}


/* ══════════════════════════════════════════════════════════════════════
   EXPERIENCIA
   description se guarda como texto multilínea en la BD;
   cada línea no vacía se convierte en un bullet del timeline.
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
        'SELECT company, location, role, description, start_date, end_date, is_current
         FROM   experiences
         ORDER  BY sort_order ASC, id ASC'
    );

    return array_map('_mapExperienceRow', $stmt->fetchAll());
}

/**
 * Mapea una fila de `experiences` al formato que espera experience.php.
 *
 * Campos de la BD → campos del portafolio:
 *   company     → empresa
 *   location    → ubicacion
 *   role        → rol
 *   description → bullets[] (una línea = un bullet)
 *   start_date + end_date + is_current → periodo (formateado)
 *
 * @internal Usado solo por getExperiences() como callback de array_map.
 */
function _mapExperienceRow(array $row): array
{
    /*
     * Convertir el campo description (texto multilínea) en array de bullets.
     * El admin ingresa cada logro/responsabilidad en una línea separada.
     * Se filtran líneas vacías y se aplica trim para limpiar espacios sobrantes.
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
        'ubicacion' => $row['location'] ?? '',
        'periodo'   => _formatPeriod(
            $row['start_date'],
            $row['end_date'],
            (bool) $row['is_current']
        ),
        'bullets'   => $bullets,
    ];
}

/**
 * Formatea un par de fechas en texto legible para el período del timeline.
 *
 * Ejemplos:
 *   ('2025-12-01', '2026-05-01', false) → 'Dic 2025 – May 2026'
 *   ('2025-12-01', null,         true)  → 'Dic 2025 – Presente'
 *
 * @param string      $start     Fecha de inicio en formato YYYY-MM-DD
 * @param string|null $end       Fecha de fin en formato YYYY-MM-DD, o null
 * @param bool        $isCurrent true si la posición sigue activa
 * @return string Período con mes abreviado en español y año
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
   skills se guarda como texto con formato "Categoría: habilidad1, hab2"
   (una categoría por línea). Se parsea a la estructura que espera
   education.php: array de {nombre, items[]}.
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
        'SELECT institution, location, degree, field,
                start_year, end_year, is_current,
                description, skills, gancho
         FROM   education
         ORDER  BY sort_order ASC, id ASC'
    );

    return array_map('_mapEducationRow', $stmt->fetchAll());
}

/**
 * Mapea una fila de `education` al formato que espera education.php.
 *
 * Campos de la BD → campos del portafolio:
 *   degree + field  → titulo  ("Técnico Superior en Análisis de Sistemas")
 *   institution     → institucion
 *   location        → ubicacion
 *   start_year + end_year + is_current → periodo
 *   description     → descripcion
 *   skills (texto)  → categorias (array parseado por _parseSkills)
 *   gancho          → gancho
 *
 * @internal Usado solo por getEducation() como callback de array_map.
 */
function _mapEducationRow(array $row): array
{
    $endLabel = $row['is_current'] ? 'Presente' : ($row['end_year'] ?? 'Presente');

    /* Construir título completo: "Técnico Superior en Análisis de Sistemas" */
    $titulo = $row['degree'];
    if (!empty($row['field'])) {
        $titulo .= ' en ' . $row['field'];
    }

    return [
        'titulo'      => $titulo,
        'institucion' => $row['institution'],
        'ubicacion'   => $row['location'] ?? '',
        'periodo'     => $row['start_year'] . ' – ' . $endLabel,
        'descripcion' => $row['description'] ?: null,
        'categorias'  => _parseSkills($row['skills'] ?? ''),
        'gancho'      => $row['gancho']      ?: null,
    ];
}

/**
 * Convierte el texto de skills en el array de categorías que espera education.php.
 *
 * Formato de entrada (una categoría por línea):
 *   "Arquitectura & Software: Ciclo de vida del software, Análisis de Sistemas"
 *   "Data & Lógica: Bases de Datos Relacionales, Lógica de Programación"
 *
 * Formato de salida:
 *   [
 *     ['nombre' => 'Arquitectura & Software', 'items' => ['Ciclo de vida del software', ...]],
 *     ['nombre' => 'Data & Lógica',           'items' => ['Bases de Datos Relacionales', ...]],
 *   ]
 *
 * Líneas vacías o sin separador ":" se ignoran silenciosamente.
 *
 * @param string $text Texto del campo `skills` de la BD
 * @return array<int, array{nombre: string, items: string[]}> Categorías parseadas
 */
function _parseSkills(string $text): array
{
    if (trim($text) === '') return [];

    $categories = [];

    foreach (explode("\n", $text) as $line) {
        $line = trim($line);

        /* Ignorar líneas vacías o sin separador ":" */
        if ($line === '' || !str_contains($line, ':')) {
            continue;
        }

        /* Separar en nombre de categoría e items (limitamos a 2 partes por si
           los items contienen ":" en su nombre, aunque es poco probable) */
        [$nombre, $itemsStr] = explode(':', $line, 2);

        $items = array_values(
            array_filter(
                array_map('trim', explode(',', $itemsStr)),
                fn(string $item) => $item !== ''
            )
        );

        if (!empty($items)) {
            $categories[] = [
                'nombre' => trim($nombre),
                'items'  => $items,
            ];
        }
    }

    return $categories;
}
