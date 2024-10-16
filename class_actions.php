<?php
include 'include/Config.php';

// Traitement de l'ajout d'une nouvelle classe
if (isset($_POST['add_class'])) {
    $className = $_POST['class_name'];

    // Préparer et exécuter la requête pour ajouter la nouvelle classe
    $stmt = $pdo->prepare("INSERT INTO Class (ClassName) VALUES (:className)");
    $stmt->bindParam(':className', $className);
    
    // Exécuter la requête
    $stmt->execute();

    // Redirection après l'ajout pour éviter la soumission multiple
    header('Location: manage_classes.php');
    exit();
}

// Traitement de la suppression d'une classe
if (isset($_POST['delete_class'])) {
    $classId = $_POST['class_id'];

    // Préparer et exécuter la requête pour supprimer la classe
    $stmt = $pdo->prepare("DELETE FROM Class WHERE idClasse = :classId");
    $stmt->bindParam(':classId', $classId);

    // Exécuter la requête
    $stmt->execute();

    // Redirection après la suppression pour actualiser la page
    header('Location: manage_classes.php');
    exit();
}
?>
