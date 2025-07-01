<?php
require_once 'includes/header.php';
require_once 'includes/User.php';
require_once 'includes/UserManager.php';
require_once 'includes/Session.php';
//Session::redirectIfNotLoggedIn();

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$userManager = new UserManager();
$user = $userManager->read($_GET['id']);

if (!$user) {
    header("Location: index.php?message=Utilisateur non trouvé");
    exit();
}

$message = '';
$name = $user->getName();
$firstname = $user->getFirstname();
$username = $user->getUsername();
$email = $user->getEmail();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $firstname = trim($_POST['firstname']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    
    if (!empty($name) && !empty($email)) {
        try {
            $user->setName($name);
            $user->setFirstname($firstname);
            $user->setUsername($username);
            $user->setEmail($email);
            $userManager->update($user);
            
            header("Location: index.php?message=Utilisateur mis à jour avec succès");
            exit();
        } catch (Exception $e) {
            $message = "Erreur : " . $e->getMessage();
        }
    } else {
        $message = "Veuillez remplir tous les champs";
    }
}
?>

<h2>Modifier l'Utilisateur</h2>

<?php if ($message): ?>
    <div class="message"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<form method="POST">
    <input name="id" type="hidden" value="<?= $user->getId() ?>">
    
    <div class="form-group">
        <label for="name">Nom :</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($name) ?>" required>
    </div>

    <div class="form-group">
        <label for="firstname">Prénom :</label>
        <input type="text" id="firstname" name="firstname" value="<?= htmlspecialchars($firstname) ?>" required>
    </div>

    <div class="form-group">
        <label for="username">Pseudo :</label>
        <input type="text" id="username" name="username" value="<?= htmlspecialchars($username) ?>" required>
    </div>
    
    <div class="form-group">
        <label for="email">Email :</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
    </div>
    
    <div class="form-group">
        <input type="submit" value="Mettre à jour">
    </div>
</form>

<?php require_once 'includes/footer.php'; ?>