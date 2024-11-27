<?php 

$title = 'Gestion des utilisateurs';
include_once('partials/header.php');     
include_once('include/updateStatus.php');
include_once('include/pdo.php');

$search = isset($_POST['search']) ? $_POST['search'] : '';
$query = "SELECT s.SubName, 
                 CONCAT(u.FirstName, ' ', u.LastName) as TeacherName, 
                 c.ClassName, 
                 sc.StartDateTime, 
                 sc.EndDateTime 
          FROM Subject s
          JOIN course sc ON s.idSubject = sc.SubjectID
          JOIN user u ON sc.teacherID = u.idUser
          JOIN Class c ON sc.classID = c.idClasse
          WHERE (s.SubName LIKE :search 
                 OR CONCAT(u.FirstName, ' ', u.LastName) LIKE :search 
                 OR c.ClassName LIKE :search)
            AND sc.StartDateTime > NOW()  
          ORDER BY sc.StartDateTime ASC";

$stmt = $pdo->prepare($query);
$searchParam = '%' . $search . '%';
$stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer la liste des classes pour les assignations
$classStmt = $pdo->prepare("SELECT * FROM Class");
$classStmt->execute();
$classes = $classStmt->fetchAll(PDO::FETCH_ASSOC);

// Suppression d'un cours

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_course'])) {
    // Récupère les informations du cours à supprimer
    $className = $_POST['class_name'];
    $subName = $_POST['sub_name'];
    $startDateTime = $_POST['start_date_time'];
    $endDateTime = $_POST['end_date_time'];

    // Requête SQL pour supprimer le cours
    $deleteStmt = $pdo->prepare("DELETE FROM course 
                                 WHERE classID = (SELECT idClasse FROM Class WHERE ClassName = :className)
                                 AND SubjectID = (SELECT idSubject FROM Subject WHERE SubName = :subName)
                                 AND StartDateTime = :startDateTime
                                 AND EndDateTime = :endDateTime");

    // Lier les paramètres à la requête
    $deleteStmt->bindParam(':className', $className, PDO::PARAM_STR);
    $deleteStmt->bindParam(':subName', $subName, PDO::PARAM_STR);
    $deleteStmt->bindParam(':startDateTime', $startDateTime, PDO::PARAM_STR);
    $deleteStmt->bindParam(':endDateTime', $endDateTime, PDO::PARAM_STR);

    // Exécute la requête de suppression
    $deleteStmt->execute();

    // Redirection après la suppression
    header('Location: edit_courses.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<!DOCTYPE html>
<html lang="en">

<body class="bg-light">
    <a href="manage_subjects.php" class="btn btn-secondary" >
        Retour
    </a>
    <div class="container py-4">
        <h1 class="text-center mb-4">Gérer les cours existants</h1>

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
                    <th>Classe</th>
                    <th>Matière</th>
                    <th>Professeur</th>
                    <th>Heure début</th>
                    <th>Heure fin</th>
                    <th>Action</th> <!-- Ajout d'une colonne pour l'action -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['ClassName']); ?></td>
                        <td><?php echo htmlspecialchars($user['SubName']); ?></td>
                        <td><?php echo htmlspecialchars($user['TeacherName']); ?></td>
                        <td><?php echo htmlspecialchars($user['StartDateTime']); ?></td>
                        <td><?php echo htmlspecialchars($user['EndDateTime']); ?></td>
                        <td>
                            <!-- Formulaire de suppression -->
                            <form method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce cours ?');">
                                <button type="submit" name="delete_course" class="btn btn-danger">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

