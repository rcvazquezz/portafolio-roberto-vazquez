<?php
/**
 * src/Models/Project.php — Modelo para la tabla `projects`
 *
 * Todos los métodos usan PDO con prepared statements para prevenir SQL injection.
 * Los tags se almacenan como JSON en la DB y se convierten a/desde array PHP aquí.
 */

namespace Models;

use Core\Database;
use PDO;

class Project
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    /**
     * Devuelve todos los proyectos ordenados por sort_order ascendente.
     *
     * @return array<int, array>
     */
    public function all(): array
    {
        $stmt = $this->pdo->query(
            'SELECT * FROM projects ORDER BY sort_order ASC, id ASC'
        );
        $rows = $stmt->fetchAll();

        /* Decodificar el JSON de tags en cada fila */
        foreach ($rows as &$row) {
            $row['tags'] = json_decode($row['tags'] ?? '[]', true);
        }

        return $rows;
    }

    /**
     * Devuelve solo los proyectos publicados (para el portafolio público).
     *
     * @return array<int, array>
     */
    public function published(): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM projects WHERE status = 'published' ORDER BY sort_order ASC"
        );
        $stmt->execute();
        $rows = $stmt->fetchAll();

        foreach ($rows as &$row) {
            $row['tags'] = json_decode($row['tags'] ?? '[]', true);
        }

        return $rows;
    }

    /**
     * Busca un proyecto por su ID.
     * Devuelve null si no existe.
     */
    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM projects WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        $row['tags'] = json_decode($row['tags'] ?? '[]', true);

        return $row;
    }

    /**
     * Inserta un nuevo proyecto en la base de datos.
     * Los tags deben pasarse como array PHP; se convierten a JSON aquí.
     *
     * @param array $data Claves: name, description, tags[], status, url, github_url, sort_order
     * @return int ID del nuevo registro
     */
    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO projects (name, description, tags, status, url, github_url, sort_order)
             VALUES (:name, :description, :tags, :status, :url, :github_url, :sort_order)'
        );

        $stmt->execute([
            ':name'        => trim($data['name']),
            ':description' => trim($data['description']),
            ':tags'        => json_encode($this->normalizeTags($data['tags'] ?? [])),
            ':status'      => $data['status'] ?? 'published',
            ':url'         => $data['url']        ? trim($data['url'])        : null,
            ':github_url'  => $data['github_url'] ? trim($data['github_url']) : null,
            ':sort_order'  => (int) ($data['sort_order'] ?? 0),
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Actualiza un proyecto existente.
     *
     * @param int   $id   ID del proyecto a actualizar
     * @param array $data Mismas claves que create()
     */
    public function update(int $id, array $data): void
    {
        $stmt = $this->pdo->prepare(
            'UPDATE projects
             SET name        = :name,
                 description = :description,
                 tags        = :tags,
                 status      = :status,
                 url         = :url,
                 github_url  = :github_url,
                 sort_order  = :sort_order
             WHERE id = :id'
        );

        $stmt->execute([
            ':name'        => trim($data['name']),
            ':description' => trim($data['description']),
            ':tags'        => json_encode($this->normalizeTags($data['tags'] ?? [])),
            ':status'      => $data['status'] ?? 'published',
            ':url'         => $data['url']        ? trim($data['url'])        : null,
            ':github_url'  => $data['github_url'] ? trim($data['github_url']) : null,
            ':sort_order'  => (int) ($data['sort_order'] ?? 0),
            ':id'          => $id,
        ]);
    }

    /**
     * Elimina un proyecto por su ID.
     */
    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM projects WHERE id = ?');
        $stmt->execute([$id]);
    }

    /**
     * Normaliza la entrada de tags.
     * Acepta tanto un array como un string separado por comas.
     * Filtra vacíos y aplica trim a cada elemento.
     *
     * @param array|string $tags
     * @return array<int, string>
     */
    private function normalizeTags(array|string $tags): array
    {
        if (is_string($tags)) {
            /* "PHP, MySQL, Tailwind CSS" → ['PHP', 'MySQL', 'Tailwind CSS'] */
            $tags = explode(',', $tags);
        }

        return array_values(
            array_filter(
                array_map('trim', $tags),
                fn(string $t) => $t !== ''
            )
        );
    }
}
