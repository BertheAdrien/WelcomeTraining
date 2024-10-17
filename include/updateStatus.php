<?php


// Traitement de la mise à jour du statut de l'utilisateur
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_user'])) {
    $userId = $_POST['user_id'];
    $newStatus = $_POST['status'];

    // Mettre à jour le statut de l'utilisateur
    $updateStmt = $pdo->prepare("UPDATE User SET Status = :status WHERE idUser = :idUser");
    $updateStmt->bindParam(':status', $newStatus);
    $updateStmt->bindParam(':idUser', $userId);
    $updateStmt->execute();

    header('Location: manage_users.php');
    exit();
}

?>