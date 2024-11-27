<?php
include_once 'include/Config.php'; 
include_once('include/pdo.php');

if (isset($_POST['class_id'])) {
    $classId = $_POST['class_id'];

    // Requête pour obtenir les élèves associés à la classe
    $stmt = $pdo->prepare("
        SELECT User.FirstName, User.LastName, User.Email 
        FROM User_has_Class 
        INNER JOIN User ON User.idUser = User_has_Class.User_idUser
        WHERE User_has_Class.Class_idClasse = :classId
    ");
    $stmt->bindParam(':classId', $classId, PDO::PARAM_INT);
    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Vérifier si des élèves sont associés à la classe
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