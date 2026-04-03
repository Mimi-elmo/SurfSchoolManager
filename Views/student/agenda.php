<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Agenda — SurfSchool Manager</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>

<?php
if (!isset($student) || ! $student) {
    header('Location: index.php?route=login');
    exit;
}
?>

<!-- ============================================================
     NAVBAR
     ============================================================ -->
<nav class="navbar">
    <div class="wrapper">
        <div class="navbar__brand">🏄 Surf<span>School</span>
            <small style="font-size:.75rem;opacity:.5;font-weight:400;">Manager</small>
        </div>
        <ul class="navbar__links">
            <li><a href="index.php?route=student/agenda" class="active">Mon Agenda</a></li>
            <li><a href="index.php?route=student/level">Mon Niveau</a></li>
            <li><a href="index.php?route=logout" class="btn-logout">Déconnexion</a></li>
        </ul>
    </div>
</nav>

<!-- ============================================================
     PAGE HEADER
     ============================================================ -->
<header class="page-header">
    <div class="wrapper">
        <h1>Mon Agenda de Surf 🌊</h1>
        <!-- htmlspecialchars() protège contre les attaques XSS -->
        <p>Bonjour <?= htmlspecialchars($student->getName()) ?> — voici vos prochaines sessions</p>
    </div>
</header>

<!-- ============================================================
     MAIN CONTENT
     ============================================================ -->
<main class="wrapper mb-4">

    <!-- Carte profil élève -->
    <div class="card mb-3" style="display:flex; align-items:center; gap:1.5rem;">

        <!-- Avatar : première lettre du nom en majuscule -->
        <div style="
            width:56px; height:56px; border-radius:50%;
            background:var(--ocean);
            display:flex; align-items:center; justify-content:center;
            font-family:'Syne',sans-serif; font-weight:800; font-size:1.3rem;
            color:#fff; flex-shrink:0;">
            <?= strtoupper(substr($student->getName(), 0, 1)) ?>
        </div>

        <!-- Infos élève -->
        <div>
            <div style="font-weight:700; font-size:1rem;">
                <?= htmlspecialchars($student->getName()) ?>
            </div>
            <div class="text-muted">
                <?= htmlspecialchars($student->getCountry()) ?>
                <!-- Affiche le téléphone seulement s'il existe -->
                <?= $student->getPhone() ? ' · ' . htmlspecialchars($student->getPhone()) : '' ?>
            </div>
        </div>

        <!-- Badge niveau -->
        <div style="margin-left:auto;">
            <?php
            $lvl   = $student->getLevel();
            $badge = strtolower($lvl); // 'Beginner' → 'beginner' pour la classe CSS
            ?>
            <span class="badge badge--<?= $badge ?>" style="font-size:.85rem; padding:.4rem 1rem;">
                <?= $lvl ?>
            </span>
        </div>
    </div>

    <!-- --------------------------------------------------------
         LISTE DES SESSIONS À VENIR
         $enrollments = tableau d'objets Enroll passé par le contrôleur
         Chaque objet contient les infos du cours (JOIN lessons)
         -------------------------------------------------------- -->
    <h2 style="font-size:1.05rem; margin-bottom:1rem;">📅 Sessions à venir</h2>

    <!-- Cas vide : aucune session inscrite ou toutes passées -->
    <?php if (empty($enrollments)): ?>
        <div class="card" style="text-align:center; padding:3rem; color:var(--muted);">
            <div style="font-size:2.5rem; margin-bottom:.75rem;">🏖️</div>
            <div style="font-size:1rem; font-weight:600; margin-bottom:.4rem;">
                Aucune session à venir
            </div>
            <div style="font-size:.88rem;">
                Contactez l'école pour vous inscrire à un cours.
            </div>
        </div>
    <?php endif; ?>

    <!-- Boucle sur chaque inscription -->
    <?php foreach ($enrollments as $e): ?>
        <div class="agenda-item">

            <!-- Infos du cours -->
            <div class="agenda-item__info">
                <div class="agenda-item__title">
                    <!-- getLessonTitle() vient du JOIN dans Enroll::findUpcomingByStudent() -->
                    <?= htmlspecialchars($e->getLessonTitle()) ?>
                </div>
                <div class="agenda-item__meta">
                    📅 <?= $e->getFormattedDate() ?>
                    &nbsp;·&nbsp;
                    👤 <?= htmlspecialchars($e->getLessonCoach()) ?>
                    &nbsp;·&nbsp;
                    <!-- number_format : affiche 350.00 → "350,00 MAD" -->
                    💰 <?= number_format($e->getLessonPrice() ?? 0, 2, ',', ' ') ?> MAD
                </div>
            </div>

            <!-- Badge statut paiement -->
            <div style="flex-shrink:0;">
                <?php if ($e->isPaid()): ?>
                    <!-- isPaid() retourne true si pay_status === 'paid' -->
                    <span class="badge badge--paid">✅ Payé</span>
                <?php else: ?>
                    <span class="badge badge--pending">⏳ En attente</span>
                <?php endif; ?>
            </div>

        </div>
    <?php endforeach; ?>

    <!-- Compteur en bas -->
    <?php if (!empty($enrollments)): ?>
        <div class="text-muted mt-2" style="font-size:.82rem; text-align:right;">
            <?= count($enrollments) ?> session(s) à venir
        </div>
    <?php endif; ?>

</main>

</body>
</html>