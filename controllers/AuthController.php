<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Student.php';

class AuthController
{
    public function showLogin(): void
    {
        require_once __DIR__ . '/../Views/auth/login.php';
    }

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->showLogin();
            return;
        }

        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'] ?? '';

        if (!$email || !$password) {
            echo '<p style="color:red;">Email et mot de passe obligatoires.</p>';
            $this->showLogin();
            return;
        }

        $user = User::findByEmail($email);

        if (!$user || !password_verify($password, $user->getPassword())) {
            echo '<p style="color:red;">Identifiants invalides.</p>';
            $this->showLogin();
            return;
        }

        $_SESSION['user_id'] = $user->getId();
        $_SESSION['role'] = $user->getRole();

        header('Location: index.php?route=student/agenda');
        exit;
    }

    public function showRegister(): void
    {
        require_once __DIR__ . '/../Views/auth/register.php';
    }

    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->showRegister();
            return;
        }

        $name = trim($_POST['name'] ?? '');
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'] ?? '';
        $country = trim($_POST['country'] ?? 'France');
        $level = in_array($_POST['level'] ?? 'Beginner', ['Beginner', 'Intermediate', 'Advanced'], true)
            ? $_POST['level'] : 'Beginner';

        if (!$name || !$email || !$password || !$country) {
            echo '<p style="color:red;">Tous les champs sont obligatoires.</p>';
            $this->showRegister();
            return;
        }

        if (User::findByEmail($email)) {
            echo '<p style="color:red;">Cet email est déjà utilisé.</p>';
            $this->showRegister();
            return;
        }

        $userId = User::create($name, $email, $password, 'student');
        Student::create($userId, $name, $country, $level);

        $_SESSION['user_id'] = $userId;
        $_SESSION['role'] = 'student';

        header('Location: index.php?route=student/agenda');
        exit;
    }
}
