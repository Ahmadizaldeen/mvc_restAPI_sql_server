<?php
declare(strict_types=1);

class User
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connectDB();
    }

    // Neuen User anlegen — gibt neue ID zurück # auth/register
    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO users (name, lastname, email, password)
             VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([
            $data['name'],

            $data['lastname'],

            $data['email'],
            password_hash($data['password'], PASSWORD_DEFAULT)
        ]);
        return (int) $this->db->lastInsertId();
    }

    // User per E-Mail suchen — für auth/login
    public function findByEmail(string $email): array|false
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM users WHERE email = ? AND deleted_at IS NULL'
        );
        $stmt->execute([$email]);
        return $stmt->fetch();
    }
}