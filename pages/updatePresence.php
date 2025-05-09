<?php
include_once('../include/Config.php');
include_once('../include/pdo.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['idUser']) || $_SESSION['user_status'] !== 'Prof') {
    header('Location: ../pages/Login.php');
    exit();
}

$courseId = $_POST['courseId'];
$presentStudents = $_POST['present'] ?? [];

try {
    $pdo->beginTransaction();

    // Réinitialiser les autorisations de signature pour ce cours
    $stmt = $pdo->prepare("
        UPDATE course_attendance 
        SET can_sign = 0 
        WHERE course_id = ? AND signature_path IS NULL
    ");
    $stmt->execute([$courseId]);

    // Mettre à jour les présences
    if (!empty($presentStudents)) {
        foreach ($presentStudents as $studentId) {
            // Vérifier si une entrée existe
            $stmt = $pdo->prepare("
                SELECT idCourseAttendance FROM course_attendance 
                WHERE course_id = ? AND student_id = ?
            ");
            $stmt->execute([$courseId, $studentId]);
            
            if ($stmt->rowCount() === 0) {
                // Créer une nouvelle entrée
                $stmt = $pdo->prepare("
                    INSERT INTO course_attendance (course_id, student_id, can_sign)
                    VALUES (?, ?, 1)
                ");
                $stmt->execute([$courseId, $studentId]);
            } else {
                // Mettre à jour l'entrée existante
                $stmt = $pdo->prepare("
                    UPDATE course_attendance 
                    SET can_sign = 1 
                    WHERE course_id = ? AND student_id = ? AND signature_path IS NULL
                ");
                $stmt->execute([$courseId, $studentId]);
            }
        }
    }

    $pdo->commit();
    header('Location: gestion_presence.php?courseId=' . $courseId . '&success=1');
} catch (Exception $e) {
    $pdo->rollBack();
    $errorMessage = urlencode($e->getMessage());
    header('Location: gestion_presence.php?courseId=' . $courseId . '&error=1&message=' . $errorMessage);
    exit();
}
?>