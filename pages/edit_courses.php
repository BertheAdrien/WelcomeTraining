<?php 
$title = 'Gestion des cours';
include_once('../partials/header.php');     
include_once('../include/pdo.php');
include_once('../classes/CourseManager.php');
include_once('../classes/CourseController.php');

$courseManager = new CourseManager($pdo);
$courseController = new CourseController($courseManager);

$search = isset($_POST['search']) ? $_POST['search'] : '';
$courses = $courseController->searchCourses($search);

// Suppression d'un cours
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_course'])) {
    $courseId = $_POST['course_id'];
    $courseController->deleteCourse($courseId);
    header('Location: edit_courses.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<body class="bg-light">
    <a href="manage_subjects.php" class="btn btn-secondary">Retour</a>
    <div class="container py-4">
        <h1 class="text-center mb-4">Gérer les cours existants</h1>

        <form method="POST" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Rechercher par matière, professeur ou classe" value="<?php echo htmlspecialchars($search); ?>">
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
                <?php foreach ($courses as $course) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($course['ClassName']); ?></td>
                        <td><?php echo htmlspecialchars($course['SubName']); ?></td>
                        <td><?php echo htmlspecialchars($course['TeacherName']); ?></td>
                        <td><?php echo htmlspecialchars($course['StartDateTime']); ?></td>
                        <td><?php echo htmlspecialchars($course['EndDateTime']); ?></td>
                        <td>
                            <form method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce cours ?');">
                                <input type="hidden" name="course_id" value="<?php echo htmlspecialchars($course['CourseID']); ?>">
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
