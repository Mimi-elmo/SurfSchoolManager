<?php

require_once __DIR__ . '/../models/student.php';
require_once __DIR__ . '/../models/enroll.php';

class StudentController
{
    // ----------------------------------------------------------------
    //  Guard : accès réservé aux élèves connectés
    // ----------------------------------------------------------------
    private function requireStudent(): void
    {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
            header('Location: index.php?route=login');
            exit;
        }

        if ($_SESSION['role'] !== 'student') {
            header('Location: index.php?route=login');
            exit;
        }
    }

    // ----------------------------------------------------------------
    //  US5 — Agenda : liste des cours à venir + statut paiement
    // ----------------------------------------------------------------
    public function agenda(): void
    {
        $this->requireStudent();

        // Récupère le profil student lié à l'user connecté
        $student = Student::findByUserId((int) $_SESSION['user_id']);

        // Si l'user connecté n'a pas encore de profil student
        if (!$student) {
            header('Location: index.php?route=login');
            exit;
        }

        // Récupère tous les cours à venir de cet élève
        $enrollments = Enroll::findUpcomingByStudent($student->getId());

        // Charge la vue — $student et $enrollments sont disponibles dans la vue
        require_once __DIR__ . '/../Views/student/agenda.php';
    }

    // ----------------------------------------------------------------
    //  Niveau : affiche le niveau actuel de l'élève
    // ----------------------------------------------------------------
    public function level(): void
    {
        $this->requireStudent();

        $student = Student::findByUserId((int) $_SESSION['user_id']);

        if (!$student) {
            header('Location: index.php?route=login');
            exit;
        }

        require_once __DIR__ . '/../Views/student/level.php';
    }
}