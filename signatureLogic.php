<?php
// saveSignature.php

session_start();
require_once 'include/Config.php';
require_once 'include/pdo.php';

// Vérifier si l'utilisateur est connecté et est un élève
if (!isset($_SESSION['idUser']) || $_SESSION['user_status'] !== 'Student') {
    http_response_code(403);
    echo json_encode(['error' => 'Accès non autorisé']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentId = $_SESSION['idUser'];
    $courseId = $_POST['idCourse'];
    $signatureData = $_POST['signatureData'];

    try {
        // Vérifier si l'élève a le droit de signer
        $stmt = $pdo->prepare("
            SELECT can_sign 
            FROM course_attendance 
            WHERE student_id = ? AND course_id = ? AND can_sign = 1
        ");
        $stmt->execute([$studentId, $courseId]);
        
        if ($stmt->rowCount() === 0) {
            http_response_code(403);
            echo json_encode(['error' => 'Signature non autorisée']);
            exit;
        }

        // Decoder l'image base64
        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $signatureData));
        
        // Générer un nom de fichier unique
        $filename = 'signature_' . $studentId . '_' . $courseId . '_' . time() . '.png';
        $filepath = 'signatures/' . $filename;
        
        // Sauvegarder l'image
        file_put_contents($filepath, $imageData);

        // Mettre à jour la base de données
        $stmt = $pdo->prepare("
            UPDATE course_attendance 
            SET signature_path = ?, 
                can_sign = 0, 
                signed_at = NOW() 
            WHERE student_id = ? AND course_id = ?
        ");
        $stmt->execute([$filepath, $studentId, $courseId]);

        // Redirection vers le tableau de bord
        header('Location: dashboard.php');
        exit;

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur lors de l\'enregistrement de la signature']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Méthode non autorisée']);
}