<?php
/**
 * src/Models/Experience.php — Modelo para la tabla `experiences`
 *
 * Gestiona el historial laboral del timeline del portafolio.
 * start_date y end_date se guardan como DATE (YYYY-MM-01).
 */

namespace Models;

use Core\Database;
use PDO;

class Experience
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    /**
     * Devuelve todas las experiencias ordenadas por sort_order.
     *
     * @return array<int, array>
     */
    public function all(): array
    {
        $stmt = $this->pdo->query(
            'SELECT * FROM experiences ORDER BY sort_order ASC, id ASC'
        );

        return $stmt->fetchAll();
    }

    /**
     * Busca una experiencia por ID. Devuelve null si no existe.
     */
    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM experiences WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        return $row ?: null;
    }

    /**
     * Inserta una nueva experiencia.
     *
     * @param array $data Claves: company, role, description, start_date,
     *                    end_date (null si is_current), is_current, sort_order
     * @return int ID del nuevo registro
     */
    public function create(array $data): int
    {
        $isCurrent = (int) !empty($data['is_current']);

        $stmt = $this->pdo->prepare(
            'INSERT INTO experiences (company, location, role, description, start_date, end_date, is_current, sort_order)
             VALUES (:company, :location, :role, :description, :start_date, :end_date, :is_current, :sort_order)'
        );

        $stmt->execute([
            ':company'     => trim($data['company']),
            ':location'    => !empty($data['location']) ? trim($data['location']) : null,
            ':role'        => trim($data['role']),
            ':description' => trim($data['description'] ?? ''),
            ':start_date'  => $data['start_date'],
            ':end_date'    => $isCurrent ? null : ($data['end_date'] ?: null),
            ':is_current'  => $isCurrent,
            ':sort_order'  => (int) ($data['sort_order'] ?? 0),
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Actualiza una experiencia existente.
     */
    public function update(int $id, array $data): void
    {
        $isCurrent = (int) !empty($data['is_current']);

        $stmt = $this->pdo->prepare(
            'UPDATE experiences
             SET company     = :company,
                 location    = :location,
                 role        = :role,
                 description = :description,
                 start_date  = :start_date,
                 end_date    = :end_date,
                 is_current  = :is_current,
                 sort_order  = :sort_order
             WHERE id = :id'
        );

        $stmt->execute([
            ':company'     => trim($data['company']),
            ':location'    => !empty($data['location']) ? trim($data['location']) : null,
            ':role'        => trim($data['role']),
            ':description' => trim($data['description'] ?? ''),
            ':start_date'  => $data['start_date'],
            ':end_date'    => $isCurrent ? null : ($data['end_date'] ?: null),
            ':is_current'  => $isCurrent,
            ':sort_order'  => (int) ($data['sort_order'] ?? 0),
            ':id'          => $id,
        ]);
    }

    /**
     * Elimina una experiencia por ID.
     */
    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM experiences WHERE id = ?');
        $stmt->execute([$id]);
    }
}
