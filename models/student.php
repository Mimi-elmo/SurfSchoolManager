<?php

require_once __DIR__ . '/../config/database.php';

class Student
{
    // ----------------------------------------------------------------
    //  Propriétés — toutes private (encapsulation stricte)
    // ----------------------------------------------------------------
    private int     $id;
    private ?int    $user_id;
    private string  $name;
    private string  $country;
    private ?string $phone;
    private string  $level;

    // ----------------------------------------------------------------
    //  Constructeur — hydrate l'objet depuis un tableau PDO
    // ----------------------------------------------------------------
    public function __construct(array $data)
    {
        $this->id      = (int)    $data['id'];
        $this->user_id = isset($data['user_id']) ? (int) $data['user_id'] : null;
        $this->name    =          $data['name'];
        $this->country =          $data['country'];
        $this->phone   =          $data['phone']   ?? null;
        $this->level   =          $data['level'];
    }

    // ----------------------------------------------------------------
    //  Getters — seule façon d'accéder aux propriétés depuis l'extérieur
    // ----------------------------------------------------------------
    public function getId(): int        { return $this->id; }
    public function getUserId(): ?int   { return $this->user_id; }
    public function getName(): string   { return $this->name; }
    public function getCountry(): string{ return $this->country; }
    public function getPhone(): ?string { return $this->phone; }
    public function getLevel(): string  { return $this->level; }

    // ----------------------------------------------------------------
    //  findAll() — retourne tous les élèves triés par nom
    // ----------------------------------------------------------------
    public static function findAll(): array
    {
        $pdo  = Database::getConnection();
        $stmt = $pdo->query('SELECT * FROM student ORDER BY name ASC');
        return array_map(fn($row) => new self($row), $stmt->fetchAll());
    }

    // ----------------------------------------------------------------
    //  findById() — retourne un élève par son id
    // ----------------------------------------------------------------
    public static function findById(int $id): ?self
    {
        $pdo  = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM student WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $row  = $stmt->fetch();
        return $row ? new self($row) : null;
    }

    // ----------------------------------------------------------------
    //  findByUserId() — retourne le profil lié à un user connecté
    //  Utilisé dans StudentController après lecture de $_SESSION['user_id']
    // ----------------------------------------------------------------
    public static function findByUserId(int $userId): ?self
    {
        $pdo  = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM student WHERE user_id = ? LIMIT 1');
        $stmt->execute([$userId]);
        $row  = $stmt->fetch();
        return $row ? new self($row) : null;
    }

    // ----------------------------------------------------------------
    //  create() — insère un nouvel élève en base
    //  Appelé depuis AuthController::register() juste après User::create()
    // ----------------------------------------------------------------
    public static function create(
        int     $userId,
        string  $name,
        string  $country,
        string  $level,
        ?string $phone = null
    ): int {
        $pdo  = Database::getConnection();
        $stmt = $pdo->prepare(
            'INSERT INTO student (user_id, name, country, level, phone)
             VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->execute([$userId, $name, $country, $level, $phone]);
        return (int) $pdo->lastInsertId();
    }

    // ----------------------------------------------------------------
    //  update() — met à jour le profil complet (admin)
    // ----------------------------------------------------------------
    public function update(
        string  $name,
        string  $country,
        string  $level,
        ?string $phone
    ): bool {
        $pdo  = Database::getConnection();
        $stmt = $pdo->prepare(
            'UPDATE student SET name = ?, country = ?, level = ?, phone = ? WHERE id = ?'
        );
        $ok = $stmt->execute([$name, $country, $level, $phone, $this->id]);
        if ($ok) {
            $this->name    = $name;
            $this->country = $country;
            $this->level   = $level;
            $this->phone   = $phone;
        }
        return $ok;
    }

    // ----------------------------------------------------------------
    //  updateLevel() — met à jour uniquement le niveau (admin)
    // ----------------------------------------------------------------
    public function updateLevel(string $newLevel): bool
    {
        $allowed = ['Beginner', 'Intermediate', 'Advanced'];
        if (!in_array($newLevel, $allowed, true)) {
            return false;
        }
        $pdo  = Database::getConnection();
        $stmt = $pdo->prepare('UPDATE student SET level = ? WHERE id = ?');
        $ok   = $stmt->execute([$newLevel, $this->id]);
        if ($ok) {
            $this->level = $newLevel;
        }
        return $ok;
    }

    // ----------------------------------------------------------------
    //  count() — retourne le nombre total d'élèves (pour dashboard)
    // ----------------------------------------------------------------
    public static function count(): int
    {
        $pdo = Database::getConnection();
        return (int) $pdo->query('SELECT COUNT(*) FROM student')->fetchColumn();
    }
}