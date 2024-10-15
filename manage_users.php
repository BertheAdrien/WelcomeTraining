<?php include('partials/header.php'); ?>

<?php
// Inclure la configuration et la connexion à la base de données
include 'include/Config.php';

// Récupérer les utilisateurs depuis la base de données
$search = isset($_POST['search']) ? $_POST['search'] : '';
$query = "SELECT * FROM User WHERE LastName LIKE :search OR FirstName LIKE :search OR Email LIKE :search";
$stmt = $pdo->prepare($query);
$searchParam = '%' . $search . '%';
$stmt->bindParam(':search', $searchParam);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer la liste des classes pour les assignations
$classStmt = $pdo->prepare("SELECT * FROM Class");
$classStmt->execute();
$classes = $classStmt->fetchAll(PDO::FETCH_ASSOC);

// Fonction pour récupérer les classes associées à un utilisateur
function getUserClasses($userId, $pdo) {
    $classStmt = $pdo->prepare("
        SELECT Class.ClassName, Class.idClasse
        FROM User_has_Class
        INNER JOIN Class ON User_has_Class.Class_idClasse = Class.idClasse
        WHERE User_has_Class.User_idUser = :userId
    ");
    $classStmt->bindParam(':userId', $userId);
    $classStmt->execute();
    return $classStmt->fetchAll(PDO::FETCH_ASSOC);
}

// Traitement de la mise à jour du statut de l'utilisateur
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_user'])) {
    $userId = $_POST['user_id'];
    $newStatus = $_POST['status'];
    $newClassId = $_POST['class_id'];

    // Mettre à jour le statut de l'utilisateur
    $updateStmt = $pdo->prepare("UPDATE User SET Status = :status WHERE idUser = :idUser");
    $updateStmt->bindParam(':status', $newStatus);
    $updateStmt->bindParam(':idUser', $userId);
    $updateStmt->execute();

    // Ajouter l'utilisateur à une nouvelle classe si sélectionnée
    if ($newClassId != 'none') {
        // Vérifier si l'utilisateur est déjà assigné à cette classe
        $checkClassStmt = $pdo->prepare("
            SELECT * FROM User_has_Class WHERE User_idUser = :userId AND Class_idClasse = :classId
        ");
        $checkClassStmt->bindParam(':userId', $userId);
        $checkClassStmt->bindParam(':classId', $newClassId);
        $checkClassStmt->execute();

        // Si l'utilisateur n'est pas encore dans cette classe, l'ajouter
        if ($checkClassStmt->rowCount() === 0) {
            $classAssignStmt = $pdo->prepare("
                INSERT INTO User_has_Class (User_idUser, Class_idClasse) VALUES (:userId, :classId)
            ");
            $classAssignStmt->bindParam(':userId', $userId);
            $classAssignStmt->bindParam(':classId', $newClassId);
            $classAssignStmt->execute();
        }
    }

    header('Location: manage_users.php');
    exit();
}

// Suppression d'une classe pour un utilisateur
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_class'])) {
    $userId = $_POST['user_id'];
    $classId = $_POST['class_id'];

    $deleteStmt = $pdo->prepare("DELETE FROM User_has_Class WHERE User_idUser = :userId AND Class_idClasse = :classId");
    $deleteStmt->bindParam(':userId', $userId);
    $deleteStmt->bindParam(':classId', $classId);
    $deleteStmt->execute();

    header('Location: manage_users.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les utilisateurs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/CSS/manage_users.css"> 
</head>
<body class="bg-light">
    <div class="container py-4">
        <h1 class="text-center mb-4">Gestion des utilisateurs</h1>

        <!-- Barre de recherche -->
        <form method="POST" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Rechercher par nom, prénom ou email" value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-primary">Rechercher</button>
            </div>
        </form>

        <!-- Liste des utilisateurs -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Statut</th>
                    <th>Classe</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user) : ?>
                    <tr>
                        <form method="POST">
                            <td><?php echo htmlspecialchars($user['LastName']); ?></td>
                            <td><?php echo htmlspecialchars($user['FirstName']); ?></td>
                            <td><?php echo htmlspecialchars($user['Email']); ?></td>
                            <td>
                                <!-- Formulaire pour la mise à jour du statut de l'utilisateur -->
                                <select name="status" class="form-select">
                                    <option value="User" <?php if ($user['Status'] == 'User') echo 'selected'; ?>>Student</option>
                                    <option value="Admin" <?php if ($user['Status'] == 'Admin') echo 'selected'; ?>>Admin</option>
                                    <option value="Prof" <?php if ($user['Status'] == 'Prof') echo 'selected'; ?>>Prof</option>
                                </select>
                            </td>
                            <td>
                                <!-- Liste des classes déjà assignées avec suppression -->
                                <div>
                                    <?php
                                    $userClasses = getUserClasses($user['idUser'], $pdo);
                                    if (!empty($userClasses)) {
                                        foreach ($userClasses as $class) {
                                            echo '<div class="d-flex justify-content-between align-items-center">';
                                            echo htmlspecialchars($class['ClassName']);
                                            echo '
                                            <form method="POST" class="d-inline-block ms-2">
                                                <input type="hidden" name="user_id" value="' . $user['idUser'] . '">
                                                <input type="hidden" name="class_id" value="' . $class['idClasse'] . '">
                                                <button type="submit" name="delete_class" class="btn btn-danger btn-sm">✖</button>
                                            </form>';
                                            echo '</div>';
                                        }
                                    } else {
                                        echo "Aucune classe";
                                    }
                                    ?>
                                </div>

                                <!-- Sélection d'une nouvelle classe -->
                                <select name="class_id" class="form-select mt-2">
                                    <option value="none">Aucune</option>
                                    <?php foreach ($classes as $class) : ?>
                                        <option value="<?php echo $class['idClasse']; ?>"><?php echo htmlspecialchars($class['ClassName']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <!-- Bouton Mettre à jour -->
                                <input type="hidden" name="user_id" value="<?php echo $user['idUser']; ?>">
                                <button type="submit" name="update_user" class="btn btn-success">Mettre à jour</button>
                            </td>
                        </form>
                    </tr>
                <?php endforeach; ?>


            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
