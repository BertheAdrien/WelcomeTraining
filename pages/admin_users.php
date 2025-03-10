<?php
include_once('../include/pdo.php');
include_once('../classes/UserManager.php');
include_once('../controllers/UserController.php');
include_once('../partials/header.php');


$userManager = new UserManager($pdo);
$userController = new UserController($userManager);

$search = isset($_POST['search']) ? $_POST['search'] : '';
// $users = $userController->searchUsers($search);

?>
<!DOCTYPE html>
<html>
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
                    <th>Supprimer</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user) : ?>
                    <tr>
                        <form method="POST" action="../actions/user_actions_handler.php">
                            <td>
                                <input type="text" name="last_name" class="form-control" value="<?php echo htmlspecialchars($user['LastName']); ?>">
                            </td>
                            <td>
                                <input type="text" name="first_name" class="form-control" value="<?php echo htmlspecialchars($user['FirstName']); ?>">
                            </td>
                            <td>
                                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['Email']); ?>">
                            </td>
                            <td>
                                <select name="status" class="form-select">
                                    <option value="Student" <?php if ($user['Status'] == 'Student') echo 'selected'; ?>>Student</option>
                                    <option value="Admin" <?php if ($user['Status'] == 'Admin') echo 'selected'; ?>>Admin</option>
                                    <option value="Prof" <?php if ($user['Status'] == 'Prof') echo 'selected'; ?>>Prof</option>
                                </select>
                            </td>
                            <td>
                                <a href="../pages/manage_user_classes.php?user_id=<?php echo $user['idUser']; ?>" class="btn btn-primary">
                                    Affecter une classe
                                </a>
                            </td>
                            <td>
                                <input type="hidden" name="user_id" value="<?php echo $user['idUser']; ?>">
                                <button type="submit" name="update_user" class="btn btn-success">Mettre à jour</button>
                            </td>
                            <td>
                                <button type="submit" name="delete_user" class="btn btn-danger" onclick="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?');">
                                    ✖
                                </button>
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
