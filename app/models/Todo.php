<?php
declare(strict_types=1);

class Todo
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connectDB();
    }

    public function all(int $userId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM todos WHERE user_id = ? AND deleted_at IS NULL');
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function find(int $id, int $userId): array|false
    {
        $stmt = $this->db->prepare('SELECT * FROM todos WHERE id = ? AND user_id =? AND deleted_at IS NULL');
        $stmt->execute([$id, $userId]);
        return $stmt->fetch();
    }

    public function create(array $data, int $userId): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO todos (title, description, status, user_id) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$data['title'], $data['description'] ?? '', $data['status'] ?? 'open', $userId]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data, int $userId): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE todos SET title = ?, description = ?, status = ? WHERE id = ? AND user_id =?'
        );
        return $stmt->execute([$data['title'], $data['description'], $data['status'], $id, $userId]);
    }

    public function delete(int $id, int $userId): bool
    {
        // Soft Delete — Datensatz bleibt in der DB
        $stmt = $this->db->prepare('UPDATE todos SET deleted_at = NOW() WHERE id = ? AND user_id =?');
        return $stmt->execute([$id, $userId]);
    }
}