<?php
require_once 'includes/header.php';
require_once 'includes/User.php';
require_once 'includes/UserManager.php';
require_once 'includes/Session.php';
//Session::redirectIfNotLoggedIn();

$message = '';
$name = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    
    if (!empty($name) && !empty($email)) {
        try {
            $userManager = new UserManager();
            $newUser = new User(null, $name, $email);
            $userId = $userManager->create($newUser);
            
            header("Location: index.php?message=Utilisateur ajouté avec succès");
            exit();
        } catch (Exception $e) {
            $message = "Erreur : " . $e->getMessage();
        }
    } else {
        $message = "Veuillez remplir tous les champs";
    }
}
?>

<h2>Ajouter un Utilisateur</h2>

<?php if ($message): ?>
    <div class="message"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<form method="POST">
    <div class="form-group">
        <label for="name">Nom :</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($name) ?>" required>
    </div>
    
    <div class="form-group">
        <label for="email">Email :</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
    </div>
    
    <div class="form-group">
        <input type="submit" value="Ajouter">
    </div>
</form>

<?php require_once 'includes/footer.php'; ?>