<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — SurfSchool Manager</title>
</head>
<body>
    <h1>Connexion à SurfSchool Manager</h1>
    <p>Formulaire de connexion de base (implémentation requise dans AuthController).</p>
    <form method="post" action="index.php?route=login">
        <div>
            <label>Email</label>
            <input type="email" name="email" required>
        </div>
        <div>
            <label>Mot de passe</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit">Se connecter</button>
    </form>
    <p>Pour tester rapidement, créez un compte via <a href="index.php?route=register">inscription</a>.</p>
</body>
</html>
