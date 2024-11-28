<?php 
$title = 'Gestion des utilisateurs';
include_once('partials/header.php');     
include_once('include/pdo.php');

$search = isset($_POST['search']) ? $_POST['search'] : '';
$query = "SELECT sc.idCourse as CourseID, 
                 s.SubName, 
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

// Suppression d'un cours
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_course'])) {
    $courseId = $_POST['course_id'];

    $deleteStmt = $pdo->prepare("DELETE FROM course WHERE idCourse = :courseId");
    $deleteStmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
    $deleteStmt->execute();

    header('Location: edit_courses.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<body class="bg-light">
    <a href="manage_subjects.php" class="btn btn-secondary">Retour</a>
    <div class="container py-4">
        <h1 class="text-center mb-4">Gérer les cours existants</h1>

        <form method="POST" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Rechercher par nom, prénom ou email" value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-primary">Rechercher</button>
            </div>
        </form>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Classe</th>
                    <th>Matière</th>
                    <th>Professeur</th>
                    <th>Heure début</th>
                    <th>Heure fin</th>
                    <th>Action</th>
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
                            <form method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce cours ?');">
                                <input type="hidden" name="course_id" value="<?php echo htmlspecialchars($user['CourseID']); ?>">
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
