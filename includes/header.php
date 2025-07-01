<?php
// Démarrer la session si ce n'est pas déjà fait
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fonction sécurisée pour récupérer une valeur de session
function getSessionSafe($key, $default = '') {
    return isset($_SESSION[$key]) ? htmlspecialchars($_SESSION[$key]) : $default;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Gestion des Utilisateurs</h1>
        <nav>
            <a href="index.php">Liste des utilisateurs</a>
            <a href="add.php">Ajouter un utilisateur</a>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="user-section">
                    <span>Connecté en tant que : <?= getSessionSafe('user_username') ?></span>
                    <a href="auth/logout.php" class="logout-btn">Déconnexion</a>
                </div>
            <?php else: ?>
                <a href="auth/login.php" class="login-btn">Connexion</a>
            <?php endif; ?>
        </nav>