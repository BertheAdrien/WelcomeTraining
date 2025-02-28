<?php
include_once('../include/Config.php');
include_once('../include/pdo.php');
include_once('../classes/UserManager.php');
include_once('../controllers/UserController.php');

// Vérifier si l'ID de la classe a été passer
if (isset($_POST['class_id'])) {
    $classId = $_POST['class_id'];

    // Initialiser les classes UserManager et UserController
    $userManager = new UserManager($pdo);
    $userController = new UserController($userManager);

    // Récupérer les étudiants associés à la classe via le contrôleur
    $students = $userController->getStudentsByClass($classId);

    // Vérifier si des étudiants ont été récupérés
    if ($students) {
        foreach ($students as $student) {
            echo '<li class="list-group-item">'
                . htmlspecialchars($student['FirstName']) . ' '
                . htmlspecialchars($student['LastName']) . ' (' 
                . htmlspecialchars($student['Email']) . ')'
                . '</li>';
        }
    } else {
        echo '<li class="list-group-item">Aucun élève dans cette classe.</li>';
    }
} else {
    echo '<li class="list-group-item">Erreur: Classe non spécifiée.</li>';
}
?>
