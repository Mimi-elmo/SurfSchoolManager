<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription — SurfSchool Manager</title>
</head>
<body>
    <h1>Inscription</h1>
    <p>Page d'inscription de base (implémentation requise dans AuthController).</p>
    <form method="post" action="index.php?route=register">
        <div>
            <label>Nom</label>
            <input type="text" name="name" required>
        </div>
        <div>
            <label>Email</label>
            <input type="email" name="email" required>
        </div>
        <div>
            <label>Mot de passe</label>
            <input type="password" name="password" required>
        </div>
        <div>
            <label>Pays</label>
            <input type="text" name="country" required>
        </div>
        <div>
            <label>Niveau</label>
            <select name="level" required>
                <option value="Beginner">Beginner</option>
                <option value="Intermediate">Intermediate</option>
                <option value="Advanced">Advanced</option>
            </select>
        </div>
        <button type="submit">S'inscrire</button>
    </form>
    <p>Déjà inscrit ? <a href="index.php?route=login">Connexion</a>.</p>
</body>
</html>
