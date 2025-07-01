<?php

require_once '../includes/Auth.php';

$auth = new Auth();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $firstname = $_POST['firstname'] ?? '';
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    try {
        if ($password !== $confirm_password) {
            throw new Exception("Les mots de passe ne correspondent pas");
        }

        if ($auth->register($name, $firstname, $username, $email, $password)) {
            $success = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<div class="auth-container">
    <h2>Inscription</h2>
    
    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label for="name">Nom :</label>
            <input type="text" id="name" name="name" required>
        </div>

        <div class="form-group">
            <label for="firstname">Prénom :</label>
            <input type="text" id="firstname" name="firstname" required>
        </div>

        <div class="form-group">
            <label for="username">Pseudo :</label>
            <input type="text" id="username" name="username" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required>
        </div>
        
        <div class="form-group">
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <div class="form-group">
            <label for="confirm_password">Confirmer le mot de passe :</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        
        <div class="form-group">
            <input type="submit" value="S'inscrire">
        </div>
    </form>
    
    <p>Déjà un compte ? <a href="login.php">Se connecter</a></p>
</div>

<?php require_once '../includes/footer.php'; ?>