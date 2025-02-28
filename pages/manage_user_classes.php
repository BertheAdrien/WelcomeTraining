<?php 
$title = 'Gestion des classes de l\'utilisateur';
include_once('../partials/header.php');
include_once('../include/pdo.php');
include_once('../classes/UserManager.php');
include_once('../classes/UserController.php');

$userManager = new UserManager($pdo);
$userController = new UserController($userManager);

// Récupérer l'ID de l'utilisateur depuis l'URL
$userId = isset($_GET['user_id']) ? $_GET['user_id'] : null;

if (!$userId) {
    header('Location: admin_users.php');
    exit();
}

$user = $userController->getUserById($userId);
$classes = $userController->getAllClasses();
$userClasses = $userController->getUserClasses($userId);

// Ajouter une classe
if (isset($_POST['add_class'])) {
    $classId = $_POST['class_id'];
    $userController->addUserClass($userId, $classId);
    header("Location: manage_user_classes.php?user_id=$userId");
    exit();
}

// Supprimer une classe
if (isset($_POST['delete_class'])) {
    $classId = $_POST['class_id'];
    $userController->deleteUserClass($userId, $classId);
    header("Location: manage_user_classes.php?user_id=$userId");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<body class="bg-light">
    <div class="container py-4">
        <h1 class="text-center mb-4">Gestion des classes de <?php echo htmlspecialchars($user['FirstName'] . ' ' . $user['LastName']); ?></h1>

        <!-- Liste des classes assignées -->
        <h2>Classes actuelles</h2>
        <ul class="list-group mb-4">
            <?php if (!empty($userClasses)) : ?>
                <?php foreach ($userClasses as $class) : ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?php echo htmlspecialchars($class['ClassName']); ?>
                        <form method="POST" class="d-inline-block">
                            <input type="hidden" name="class_id" value="<?php echo $class['idClasse']; ?>">
                            <button type="submit" name="delete_class" class="btn btn-danger btn-sm">Supprimer</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            <?php else : ?>
                <li class="list-group-item">Aucune classe assignée.</li>
            <?php endif; ?>
        </ul>

        <!-- Ajouter une classe -->
        <h2>Ajouter une classe</h2>
        <form method="POST" class="mb-4">
            <div class="input-group">
                <select name="class_id" class="form-select" required>
                    <option value="">Choisir une classe</option>
                    <?php foreach ($classes as $class) : ?>
                        <option value="<?php echo $class['idClasse']; ?>"><?php echo htmlspecialchars($class['ClassName']); ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" name="add_class" class="btn btn-primary">Ajouter</button>
            </div>
        </form>

        <a href="admin_users.php" class="btn btn-secondary">Retour</a>
    </div>
</body>
</html>
