<?php
include_once '../include/Config.php';
include_once '../include/pdo.php';
include_once '../classes/ClassManager.php';

// Instancier le gestionnaire de classes
// $classManager = new ClassManager($pdo);

// 🔹 Ajout d'une classe
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_class'])) {
    $className = $_POST['class_name'];
    if (!empty($className)) {
        $classManager->addClass($className);
    }
    header('Location: ../pages/manage_classes.php');
    exit();
}

// 🔹 Suppression d'une classe
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_class'])) {
    $classId = (int) $_POST['class_id'];
    $classManager->deleteClass($classId);
    header('Location: ../pages/manage_classes.php');
    exit();
}
?>
