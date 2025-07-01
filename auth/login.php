<?php
require_once '../includes/header.php';
require_once '../includes/Auth.php';

$auth = new Auth();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    try {
        if ($auth->login($email, $password)) {
            header("Location: ../index.php");
            exit();
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<div class="auth-container">
    <h2>Connexion</h2>
    
    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required>
        </div>
        
        <div class="form-group">
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <div class="form-group">
            <input type="submit" value="Se connecter">
        </div>
    </form>
    
    <p>Pas encore de compte ? <a href="register.php">S'inscrire</a></p>
</div>

<?php require_once '../includes/footer.php'; ?>