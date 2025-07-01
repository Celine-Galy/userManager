<?php
require_once 'includes/header.php';
require_once 'includes/User.php';
require_once 'includes/UserManager.php';
require_once 'includes/Session.php';
//Session::redirectIfNotLoggedIn();

$userManager = new UserManager();
$users = $userManager->readAll();
?>

<h2>Liste des Utilisateurs</h2>

<?php if (isset($_GET['message'])): ?>
    <div class="message"><?= htmlspecialchars($_GET['message']) ?></div>
<?php endif; ?>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?= htmlspecialchars($user->getId()) ?></td>
            <td><?= htmlspecialchars($user->getName()) ?></td>
            <td><?= htmlspecialchars($user->getFirstname()) ?></td>
            <td><?= htmlspecialchars($user->getEmail()) ?></td>
            <td>
                <a href="edit.php?id=<?= $user->getId() ?>" class="btn btn-edit">Modifier</a>
                <a href="delete.php?id=<?= $user->getId() ?>" class="btn btn-delete" 
                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once 'includes/footer.php'; ?>