<?php
// root index.php front controller for SurfSchool Manager
session_start();

// Stocke la route demandée (ex: student/agenda)
$route = isset($_GET['route']) ? trim($_GET['route']) : 'student/agenda';

// Contrôleurs
require_once __DIR__ . '/controllers/StudentController.php';
require_once __DIR__ . '/controllers/AuthController.php';

switch ($route) {
    case 'student/agenda':
        (new StudentController())->agenda();
        break;

    case 'student/level':
        (new StudentController())->level();
        break;

    case 'logout':
        session_unset();
        session_destroy();
        header('Location: index.php?route=login');
        exit;

    case 'login':
        $auth = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth->login();
        } else {
            $auth->showLogin();
        }
        break;

    case 'register':
        $auth = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth->register();
        } else {
            $auth->showRegister();
        }
        break;

    default:
        http_response_code(404);
        echo '<h1>404 Not Found</h1><p>Route introuvable : ' . htmlspecialchars($route) . '</p>';
        break;
}
