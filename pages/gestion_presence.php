<?php
include_once('../partials/header.php');
include_once('../include/Config.php');
include_once('../include/pdo.php');
include_once('../classes/Course.php');

// Vérifier si l'utilisateur est un professeur
if (!isset($_SESSION['idUser']) || $_SESSION['user_status'] !== 'Prof') {
    header('Location: ../pages/Login.php');
    exit();
}

$courseId = isset($_GET['courseId']) ? (int)$_GET['courseId'] : 0;

$courseManager = new Course($pdo);
$course = $courseManager->getCourseById($courseId);
$students = $courseManager->getStudentsByCourse($courseId);


// Récupérer les informations du cours
$queryCourse = "
    SELECT s.SubName, sc.StartDateTime, sc.EndDateTime, c.ClassName
    FROM course sc
    JOIN subject s ON s.idSubject = sc.SubjectID
    JOIN class c ON sc.classID = c.idClasse
    WHERE sc.idCourse = :courseId AND sc.teacherID = :teacherId";

$stmtCourse = $pdo->prepare($queryCourse);
$stmtCourse->execute([
    ':courseId' => $courseId,
    ':teacherId' => $_SESSION['idUser']
]);
$course = $stmtCourse->fetch(PDO::FETCH_ASSOC);

if (!$course) {
    die("Cours non trouvé ou non autorisé");
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des présences</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .signature-preview {
            max-width: 150px;
            max-height: 50px;
            object-fit: contain;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-header">
                <h2><?php echo htmlspecialchars($course['SubName']); ?></h2>
                <p>Cours du <?php echo date('d/m/Y H:i', strtotime($course['StartDateTime'])); ?></p>
                <h4><?php echo htmlspecialchars($course['ClassName']); ?></h4>
            </div>
            <div class="card-body">
                <form id="presenceForm" method="POST" action="updatePresence.php">
                    <input type="hidden" name="courseId" value="<?php echo $courseId; ?>">
                    
                    <div class="mb-3">
                        <button type="button" class="btn btn-secondary me-2" id="checkAll">Tout cocher</button>
                        <button type="button" class="btn btn-secondary" id="uncheckAll">Tout décocher</button>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Présent</th>
                                    <th>Nom</th>
                                    <th>Prénom</th>
                                    <th>Statut</th>
                                    <th>Signature</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($students as $student): ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" 
                                               name="present[]" 
                                               value="<?php echo $student['idUser']; ?>"
                                               <?php echo ($student['can_sign'] ? 'checked' : ''); ?>
                                               <?php echo ($student['signature_path'] ? 'disabled' : ''); ?>>
                                    </td>
                                    <td><?php echo htmlspecialchars($student['LastName']); ?></td>
                                    <td><?php echo htmlspecialchars($student['FirstName']); ?></td>
                                    <td>
                                        <?php if ($student['signature_path']): ?>
                                            <span class="badge bg-success">Signé</span>
                                        <?php elseif ($student['can_sign']): ?>
                                            <span class="badge bg-warning">En attente de signature</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Non signé</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($student['signature_path']): ?>
                                            <img src="<?php echo htmlspecialchars($student['signature_path']); ?>" 
                                                 alt="Signature" 
                                                 class="signature-preview">
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Enregistrer les présences</button>
                        <a href="dashboard_teacher.php" class="btn btn-secondary">Retour</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Gestion des boutons tout cocher/décocher
        document.getElementById('checkAll').addEventListener('click', function() {
            document.querySelectorAll('input[name="present[]"]:not(:disabled)').forEach(checkbox => checkbox.checked = true);
        });

        document.getElementById('uncheckAll').addEventListener('click', function() {
            document.querySelectorAll('input[name="present[]"]:not(:disabled)').forEach(checkbox => checkbox.checked = false);
        });
    </script>
</body>
</html>