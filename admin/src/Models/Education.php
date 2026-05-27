<?php
/**
 * src/Models/Education.php — Modelo para la tabla `education`
 *
 * Gestiona la formación académica y certificaciones del portafolio.
 */

namespace Models;

use Core\Database;
use PDO;

class Education
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    /**
     * Devuelve todos los registros de educación ordenados por sort_order.
     *
     * @return array<int, array>
     */
    public function all(): array
    {
        $stmt = $this->pdo->query(
            'SELECT * FROM education ORDER BY sort_order ASC, id ASC'
        );

        return $stmt->fetchAll();
    }

    /**
     * Busca un registro de educación por ID. Devuelve null si no existe.
     */
    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM education WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        return $row ?: null;
    }

    /**
     * Inserta un nuevo registro de educación.
     *
     * @param array $data Claves: institution, degree, field, start_year,
     *                    end_year (null si is_current), is_current, sort_order
     * @return int ID del nuevo registro
     */
    public function create(array $data): int
    {
        $isCurrent = (int) !empty($data['is_current']);

        $stmt = $this->pdo->prepare(
            'INSERT INTO education (institution, degree, field, start_year, end_year, is_current, sort_order)
             VALUES (:institution, :degree, :field, :start_year, :end_year, :is_current, :sort_order)'
        );

        $stmt->execute([
            ':institution' => trim($data['institution']),
            ':degree'      => trim($data['degree']),
            ':field'       => $data['field'] ? trim($data['field']) : null,
            ':start_year'  => (int) $data['start_year'],
            ':end_year'    => $isCurrent ? null : ($data['end_year'] ? (int) $data['end_year'] : null),
            ':is_current'  => $isCurrent,
            ':sort_order'  => (int) ($data['sort_order'] ?? 0),
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Actualiza un registro de educación existente.
     */
    public function update(int $id, array $data): void
    {
        $isCurrent = (int) !empty($data['is_current']);

        $stmt = $this->pdo->prepare(
            'UPDATE education
             SET institution = :institution,
                 degree      = :degree,
                 field       = :field,
                 start_year  = :start_year,
                 end_year    = :end_year,
                 is_current  = :is_current,
                 sort_order  = :sort_order
             WHERE id = :id'
        );

        $stmt->execute([
            ':institution' => trim($data['institution']),
            ':degree'      => trim($data['degree']),
            ':field'       => $data['field'] ? trim($data['field']) : null,
            ':start_year'  => (int) $data['start_year'],
            ':end_year'    => $isCurrent ? null : ($data['end_year'] ? (int) $data['end_year'] : null),
            ':is_current'  => $isCurrent,
            ':sort_order'  => (int) ($data['sort_order'] ?? 0),
            ':id'          => $id,
        ]);
    }

    /**
     * Elimina un registro de educación por ID.
     */
    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM education WHERE id = ?');
        $stmt->execute([$id]);
    }
}
