<?php


// Traitement de la mise à jour du statut de l'utilisateur
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_user'])) {
    $userId = $_POST['user_id'];
    $newStatus = $_POST['status'];
    $newClassId = $_POST['class_id'];

    // Mettre à jour le statut de l'utilisateur
    $updateStmt = $pdo->prepare("UPDATE User SET Status = :status WHERE idUser = :idUser");
    $updateStmt->bindParam(':status', $newStatus);
    $updateStmt->bindParam(':idUser', $userId);
    $updateStmt->execute();

    // Ajouter l'utilisateur à une nouvelle classe si sélectionnée
    if ($newClassId != 'none') {
        // Vérifier si l'utilisateur est déjà assigné à cette classe
        $checkClassStmt = $pdo->prepare("
            SELECT * FROM User_has_Class WHERE User_idUser = :userId AND Class_idClasse = :classId
        ");
        $checkClassStmt->bindParam(':userId', $userId);
        $checkClassStmt->bindParam(':classId', $newClassId);
        $checkClassStmt->execute();

        // Si l'utilisateur n'est pas encore dans cette classe, l'ajouter
        if ($checkClassStmt->rowCount() === 0) {
            $classAssignStmt = $pdo->prepare("
                INSERT INTO User_has_Class (User_idUser, Class_idClasse) VALUES (:userId, :classId)
            ");
            $classAssignStmt->bindParam(':userId', $userId);
            $classAssignStmt->bindParam(':classId', $newClassId);
            $classAssignStmt->execute();
        }
    }

    header('Location: manage_users.php');
    exit();
}

?>