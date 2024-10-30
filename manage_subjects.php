<?php 
$title = 'Gestion des matières';
include('partials/header.php');
include 'include/Config.php';

// Récupérer toutes les matières depuis la base de données
$query = "SELECT * FROM Subject";
$stmt = $pdo->prepare($query);
$stmt->execute();
$subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<body class="bg-light">
    <div class="container py-4">
        <h1 class="text-center mb-4">Gestion des matières</h1>
        <!-- Formulaire pour ajouter une nouvelle matière -->
        <h2 class="mt-4">Ajouter une nouvelle matière</h2>
        <form method="POST" action="subject_actions.php" class="d-flex mb-4">
            <input type="text" name="subject_name" class="form-control me-2" placeholder="Nom de la nouvelle matière" required>
            <button type="submit" name="add_subject" class="btn btn-primary">Ajouter</button>
        </form>
        
        <!-- Liste des matières -->
        <h2>Matières existantes</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nom de la matière</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($subjects as $subject) : ?>
                    <tr>
                        <!-- Nom de la matière cliquable pour affecter un cours -->
                        <td>
                            <a href="#" class="subject-link" data-subject-id="<?php echo $subject['idSubject']; ?>">
                                <?php echo htmlspecialchars($subject['SubName']); ?>
                            </a>
                        </td>
                        <td>
                            <!-- Formulaire pour supprimer une matière -->
                            <form method="POST" action="subject_actions.php" style="display:inline-block;">
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
    <script src="assets/JS/manageSubjects.js"></script> <!-- Fichier JS externe -->

    <!-- Modal pour affecter un professeur, une classe et un horaire -->
    <div class="modal fade" id="assignmentModal" tabindex="-1" aria-labelledby="assignmentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="assignmentModalLabel">Affecter un cours</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="subject_actions.php">
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
