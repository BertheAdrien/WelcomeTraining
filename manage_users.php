<?php 

$title = 'Gestion des utilisateurs';
include('partials/header.php');     
include('include/updateStatus.php');

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
                                    <option value="Student" <?php if ($user['Status'] == 'Student') echo 'selected'; ?>>Student</option>
                                    <option value="Admin" <?php if ($user['Status'] == 'Admin') echo 'selected'; ?>>Admin</option>
                                    <option value="Prof" <?php if ($user['Status'] == 'Prof') echo 'selected'; ?>>Prof</option>
                                </select>
                            </td>
                            <td>
                            <!-- Lien pour gérer les classes d'un utilisateur dans une nouvelle page -->
                                <a href="manage_user_classes.php?user_id=<?php echo $user['idUser']; ?>" class="btn btn-primary">
                                    Gérer les classes
                                </a>
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
