<?php
class User {
    private int $id;
    private string $name;
    private string $email;
    private string $password_hash;
    private string $role;

   public function __construct(int $id, string $name, string $email, string $role) {
    $this->id = $id;
    $this->name = $name;
    $this->email = $email;
    $this->role = $role;
   }
    

    public function getId(): int { 
    return $this->id;
}

public function getName(): string {
    return $this->name;
}

public function getEmail(): string {
    return $this->email;
}

public function getRole(): string {
    return $this->role;
}
    public static function findByEmail(string $email): ?User {

    $pdo = Database::getConnection();

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);

    $data = $stmt->fetch();

    if (!$data) {
        return null;
    }

    return new User(
        $data['id'],
        $data['name'],
        $data['email'],
        $data['role']
    );
}