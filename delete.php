<?php
require_once 'includes/UserManager.php';
require_once 'includes/Session.php';
//Session::redirectIfNotLoggedIn();

if (isset($_GET['id'])) {
    $userManager = new UserManager();
    $success = $userManager->delete($_GET['id']);
    
    if ($success) {
        header("Location: index.php?message=Utilisateur supprimé avec succès");
    } else {
        header("Location: index.php?message=Erreur lors de la suppression");
    }
} else {
    header("Location: index.php");
}
exit();
?>