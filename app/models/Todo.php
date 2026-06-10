<?php
#declare(strict_types=1);

class Todo
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::ConnectDB();
    }

    public function all(): array
    {
        $stmt = $this->db->query('SELECT * FROM todos WHERE deleted_at IS NULL');
        return $stmt->fetchAll();
    }

    public function find(int $id): array|false
    {
        $stmt = $this->db->prepare('SELECT * FROM todos WHERE id = ? AND deleted_at IS NULL');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO todos (title, description, status) VALUES (?, ?, ?)'
        );
        $stmt->execute([$data['title'], $data['description'] ?? '', $data['status'] ?? 'open']);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE todos SET title = ?, description = ?, status = ? WHERE id = ?'
        );
        return $stmt->execute([$data['title'], $data['description'], $data['status'], $id]);
    }

    public function delete(int $id): bool
    {
        // Soft Delete — Datensatz bleibt in der DB
        $stmt = $this->db->prepare('UPDATE todos SET deleted_at = NOW() WHERE id = ?');
        return $stmt->execute([$id]);
    }
}