<?php
$title = 'Gestion des matières';
include_once '../partials/header.php';
include_once '../include/Config.php';
include_once '../include/pdo.php';
include_once '../classes/SubjectManager.php'; // On inclut la classe qui gère les matières

// Initialiser le gestionnaire de matières
$subjectManager = new SubjectManager($pdo);

// Récupérer toutes les matières depuis la base de données
$query = "SELECT * FROM subject";
$stmt = $pdo->prepare($query);
$stmt->execute();
$subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Gestion des messages de succès ou d'erreur
function displayMessage() {
    if (isset($_SESSION['message'])) {
        echo '<div class="alert alert-' . $_SESSION['message_type'] . '">';
        echo $_SESSION['message'];
        echo '</div>';
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    }
}

// Gestion des actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ajouter une matière
    if (isset($_POST['add_subject'])) {
        $message = $subjectManager->addSubject($_POST['subject_name']);
        $_SESSION['message'] = $message;
        $_SESSION['message_type'] = 'success';
        header('Location: manage_subjects.php');
        exit();
    }

    // Supprimer une matière
    if (isset($_POST['delete_subject'])) {
        $message = $subjectManager->deleteSubject($_POST['subject_id']);
        $_SESSION['message'] = $message;
        $_SESSION['message_type'] = 'danger';
        header('Location: manage_subjects.php');
        exit();
    }

    // Affecter un cours
    if (isset($_POST['assign_course'])) {
        $message = $subjectManager->assignCourse(
            $_POST['subject_id'],
            $_POST['class_id'],
            $_POST['teacher_id'],
            $_POST['start_datetime'],
            $_POST['end_datetime']
        );
        $_SESSION['message'] = $message;
        $_SESSION['message_type'] = 'success';
        header('Location: manage_subjects.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<body class="bg-light">
    <div class="container py-4">
        <h1 class="text-center mb-4">Gestion des matières</h1>
        <!-- Formulaire pour ajouter une nouvelle matière -->
        <h2 class="mt-4">Ajouter une nouvelle matière</h2>
        <form method="POST" action="manage_subjects.php" class="d-flex mb-4">
            <input type="text" name="subject_name" class="form-control me-2" placeholder="Nom de la nouvelle matière" required>
            <button type="submit" name="add_subject" class="btn btn-primary">Ajouter</button>
        </form>
        
        <!-- Bouton pour modifier les cours -->
        <div class="text-center mb-4">
            <a href="edit_courses.php" class="btn btn-warning">Modifier les cours</a>
        </div>

        <!-- Affichage des messages de succès ou d'erreur -->
        <?php displayMessage(); ?>

        <!-- Liste des matières -->
        <h2>Matières existantes</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nom de la matière</th>
                    <th>Affecter un cours</th>
                    <th>Supprimer</th>
                </tr>
            </thead>
            <tbody>
    <?php foreach ($subjects as $subject) : ?>
        <tr>
            <!-- Nom de la matière cliquable pour affecter un cours -->
            <td>
                <a data-subject-id="<?php echo $subject['idSubject']; ?>">
                    <?php echo htmlspecialchars($subject['SubName']); ?>
                </a>
            </td>
            <!-- Bouton pour affecter un cours -->
            <td>
                <button type="button" class="btn btn-secondary subject-link" data-subject-id="<?php echo $subject['idSubject']; ?>" data-bs-toggle="modal" data-bs-target="#assignmentModal">
                    Affecter un cours
                </button>
            </td>
            <!-- Formulaire pour supprimer une matière -->
            <td>
                <form method="POST" action="manage_subjects.php" style="display:inline-block;">
                    <input type="hidden" name="subject_id" value="<?php echo $subject['idSubject']; ?>">
                    <button type="submit" name="delete_subject" class="btn btn-danger">✖</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../assets/JS/manageSubjects.js"></script> <!-- Fichier JS externe -->

    <!-- Modal pour affecter un professeur, une classe et un horaire -->
    <div class="modal fade" id="assignmentModal" tabindex="-1" aria-labelledby="assignmentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="assignmentModalLabel">Affecter un cours</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="manage_subjects.php">
                        <input type="hidden" name="subject_id" id="modalSubjectId">
                        
                        <!-- Sélection du professeur -->
                        <div class="mb-3">
                            <label for="teacherSelect" class="form-label">Professeur</label>
                            <select class="form-select" name="teacher_id" id="teacherSelect" required>
                                <!-- Options des professeurs seront ajoutées ici via PHP -->
                                <?php
                                $teachers = $pdo->query("SELECT idUser, Email FROM user WHERE status='Prof'")->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($teachers as $teacher) {
                                    echo "<option value='{$teacher['idUser']}'>{$teacher['Email']}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <!-- Sélection de la classe -->
                        <div class="mb-3">
                            <label for="classSelect" class="form-label">Classe</label>
                            <select class="form-select" name="class_id" id="classSelect" required>
                                <!-- Options des classes seront ajoutées ici via PHP -->
                                <?php
                                $classes = $pdo->query("SELECT idClasse, ClassName FROM Class")->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($classes as $class) {
                                    echo "<option value='{$class['idClasse']}'>{$class['ClassName']}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <!-- Date et heure de début et fin -->
                        <div class="mb-3">
                            <label for="startDateTime" class="form-label">Date et heure de début</label>
                            <input type="datetime-local" class="form-control" name="start_datetime" id="startDateTime" required>
                        </div>
                        <div class="mb-3">
                            <label for="endDateTime" class="form-label">Date et heure de fin</label>
                            <input type="datetime-local" class="form-control" name="end_datetime" id="endDateTime" required>
                        </div>

                        <button type="submit" name="assign_course" class="btn btn-success">Affecter le cours</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
