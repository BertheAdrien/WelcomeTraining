<?php
include 'include/Config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['class_id'])) {
    $classId = $_POST['class_id'];

    // Requête pour obtenir les élèves associés à la classe
    $stmt = $pdo->prepare("
        SELECT User.FirstName, User.LastName, User.Email 
        FROM User_has_Class 
        INNER JOIN User ON User.idUser = User_has_Class.User_idUser
        WHERE User_has_Class.Class_idClasse = :classId
    ");
    $stmt->bindParam(':classId', $classId);
    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($students) {
        foreach ($students as $student) {
            echo '<li class="list-group-item">' . htmlspecialchars($student['FirstName']) . ' ' . htmlspecialchars($student['LastName']) . ' (' . htmlspecialchars($student['Email']) . ')</li>';
        }
    } else {
        echo '<li class="list-group-item">Aucun élève dans cette classe.</li>';
    }
}
?>
