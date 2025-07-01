<?php
require_once __DIR__ . '/../includes/Session.php';

// Démarrer la session si ce n'est pas déjà fait
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vider le tableau de session
$_SESSION = [];

// Détruire le cookie de session
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Détruire la session
session_destroy();

// Redirection sécurisée
header("Location: login.php");
exit();
?>