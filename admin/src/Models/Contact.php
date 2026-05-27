<?php
/**
 * src/Models/Contact.php — Modelo para la tabla `contacts`
 *
 * Gestiona los mensajes recibidos desde el formulario de contacto.
 * Los mensajes no se editan, solo se marcan como leídos o se eliminan.
 */

namespace Models;

use Core\Database;
use PDO;

class Contact
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    /**
     * Devuelve todos los mensajes, los no leídos primero, luego por fecha descendente.
     *
     * @return array<int, array>
     */
    public function all(): array
    {
        $stmt = $this->pdo->query(
            'SELECT * FROM contacts ORDER BY is_read ASC, created_at DESC'
        );

        return $stmt->fetchAll();
    }

    /**
     * Cuenta los mensajes no leídos.
     * Usado en el sidebar del dashboard para el badge de notificación.
     */
    public function countUnread(): int
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM contacts WHERE is_read = 0");

        return (int) $stmt->fetchColumn();
    }

    /**
     * Busca un mensaje por ID. Devuelve null si no existe.
     */
    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM contacts WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        return $row ?: null;
    }

    /**
     * Guarda un nuevo mensaje de contacto recibido desde el formulario público.
     *
     * @param array $data Claves: name, email, message, ip_address
     * @return int ID del nuevo registro
     */
    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO contacts (name, email, message, ip_address)
             VALUES (:name, :email, :message, :ip_address)'
        );

        $stmt->execute([
            ':name'       => trim($data['name']),
            ':email'      => trim($data['email']),
            ':message'    => trim($data['message']),
            ':ip_address' => $data['ip_address'] ?? null,
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Marca un mensaje como leído.
     */
    public function markAsRead(int $id): void
    {
        $stmt = $this->pdo->prepare('UPDATE contacts SET is_read = 1 WHERE id = ?');
        $stmt->execute([$id]);
    }

    /**
     * Elimina un mensaje por ID.
     */
    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM contacts WHERE id = ?');
        $stmt->execute([$id]);
    }
}
