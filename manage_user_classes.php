<?php 
$title = 'Gestion des classes de l\'utilisateur';
include('partials/header.php');
include 'include/Config.php';

// Récupérer l'ID de l'utilisateur depuis l'URL
$userId = isset($_GET['user_id']) ? $_GET['user_id'] : null;

// Si l'ID de l'utilisateur n'est pas défini, rediriger vers la page de gestion des utilisateurs
if (!$userId) {
    header('Location: manage_users.php');
    exit();
}

// Récupérer les informations de l'utilisateur
$userStmt = $pdo->prepare("SELECT * FROM User WHERE idUser = :userId");
$userStmt->bindParam(':userId', $userId);
$userStmt->execute();
$user = $userStmt->fetch(PDO::FETCH_ASSOC);

// Récupérer toutes les classes
$classStmt = $pdo->prepare("SELECT * FROM Class");
$classStmt->execute();
$classes = $classStmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les classes déjà associées à l'utilisateur
$userClasses = getUserClasses($userId, $pdo);

// Ajouter une classe à l'utilisateur
if (isset($_POST['add_class'])) {
    $classId = $_POST['class_id'];

    // Vérifier que la classe n'est pas déjà associée à l'utilisateur
    $checkStmt = $pdo->prepare("SELECT * FROM User_has_Class WHERE User_idUser = :userId AND Class_idClasse = :classId");
    $checkStmt->bindParam(':userId', $userId);
    $checkStmt->bindParam(':classId', $classId);
    $checkStmt->execute();

    if ($checkStmt->rowCount() == 0) {
        // Associer la classe à l'utilisateur
        $insertStmt = $pdo->prepare("INSERT INTO User_has_Class (User_idUser, Class_idClasse) VALUES (:userId, :classId)");
        $insertStmt->bindParam(':userId', $userId);
        $insertStmt->bindParam(':classId', $classId);
        $insertStmt->execute();
    }

    header("Location: manage_user_classes.php?user_id=$userId");
    exit();
}

// Supprimer une classe de l'utilisateur
if (isset($_POST['delete_class'])) {
    $classId = $_POST['class_id'];

    // Supprimer l'association de classe pour cet utilisateur
    $deleteStmt = $pdo->prepare("DELETE FROM User_has_Class WHERE User_idUser = :userId AND Class_idClasse = :classId");
    $deleteStmt->bindParam(':userId', $userId);
    $deleteStmt->bindParam(':classId', $classId);
    $deleteStmt->execute();

    header("Location: manage_user_classes.php?user_id=$userId");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<body class="bg-light">
    <div class="container py-4">
        <h1 class="text-center mb-4">Gestion des classes de <?php echo htmlspecialchars($user['FirstName'] . ' ' . $user['LastName']); ?></h1>

        <!-- Liste des classes assignées à l'utilisateur -->
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

        <!-- Formulaire pour ajouter une nouvelle classe à l'utilisateur -->
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

        <!-- Bouton pour revenir à la gestion des utilisateurs -->
        <a href="manage_users.php" class="btn btn-secondary">Retour à la gestion des utilisateurs</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
