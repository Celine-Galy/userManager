<?php
require_once 'includes/header.php';
require_once 'includes/User.php';
require_once 'includes/UserManager.php';
require_once 'includes/Session.php';
require_once 'includes/Auth.php';
//Session::redirectIfNotLoggedIn();
// Initialisation
Session::start();
$auth = new Auth(); // Initialisation correcte

// Protection admin
if (!Session::get('user_id')) {
    header('Location: auth/login.php');
    exit();
}

$error = '';
$success = '';
$username = '';
$name = '';
$firstname = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $firstname = trim($_POST['firstname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    try {
        // Validation
        if (empty($username) || empty($email) || empty($password)) {
            throw new Exception("Tous les champs sont obligatoires");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Format d'email invalide");
        }

        if ($password !== $confirm_password) {
            throw new Exception("Les mots de passe ne correspondent pas");
        }

        if (strlen($password) < 8) {
            throw new Exception("Le mot de passe doit contenir au moins 8 caractères");
        }

        // Enregistrement
        if ($auth->register($username, $name, $firstname, $email, $password)) {
            $success = "Utilisateur créé avec succès";
            // Réinitialisation après succès
            $username = $email = $name = $firstname = '';
        }

    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<div class="form-container">
    <h2>Ajouter un utilisateur</h2>
    
    <?php if ($error): ?>
        <div class="alert error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="alert success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label for="username">Pseudo:</label>
            <input type="text" id="username" name="username" 
                   value="<?= htmlspecialchars($username) ?>" required>
        </div>

        <div class="form-group">
            <label for="name">Nom:</label>
            <input type="text" id="name" name="name" 
                   value="<?= htmlspecialchars($name) ?>" required>
        </div>

        <div class="form-group">
            <label for="firstname">Prénom:</label>
            <input type="text" id="firstname" name="firstname" 
                   value="<?= htmlspecialchars($firstname) ?>" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" 
                   value="<?= htmlspecialchars($email) ?>" required>
        </div>
        
        <div class="form-group">
            <label for="password">Mot de passe:</label>
            <input type="password" id="password" name="password" required minlength="8">
        </div>
        
        <div class="form-group">
            <label for="confirm_password">Confirmer le mot de passe:</label>
            <input type="password" id="confirm_password" name="confirm_password" required minlength="8">
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn">Enregistrer</button>
            <a href="index.php" class="btn cancel">Annuler</a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>