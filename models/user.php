<?php
class User {
    private int $id;
    private string $name;
    private string $email;
    private string $password_hash;
    private string $role;

    public function __construct(int $id, string $name, string $email, string $role, string $password_hash = '') {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->role = $role;
        $this->password_hash = $password_hash;
    }

    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getEmail(): string { return $this->email; }
    public function getPassword(): string { return $this->password_hash; }
    public function getRole(): string { return $this->role; }

    public static function create(string $name, string $email, string $password, string $role = 'student'): int {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, ?)");
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt->execute([$email, $hash, $role]);
        return (int) $pdo->lastInsertId();
    }

    public static function findById(int $id): ?User {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $data = $stmt->fetch();
        if (!$data) {
            return null;
        }
        $name = $data['name'] ?? '';
        return new User($data['id'], $name, $data['email'], $data['role'], $data['password']);
    }

    public static function findByEmail(string $email): ?User {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $data = $stmt->fetch();
        if (!$data) {
            return null;
        }
        $name = $data['name'] ?? '';
        return new User($data['id'], $name, $data['email'], $data['role'], $data['password']);
    }
}
