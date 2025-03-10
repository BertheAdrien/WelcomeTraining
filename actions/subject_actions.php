<?php
include_once '../include/Config.php';
include_once('../include/pdo.php');

// Fonction pour rediriger vers la page de gestion des matières avec un message de succès
function redirectWithMessage($message, $type = 'success') {
    $_SESSION['message'] = $message;
    $_SESSION['message_type'] = $type;
    header('Location: ../pages/manage_subjects.php');
    exit();
}

// Ajouter une nouvelle matière
if (isset($_POST['add_subject'])) {
    addSubject($_POST['subject_name']);
}

// Supprimer une matière
if (isset($_POST['delete_subject'])) {
    deleteSubject($_POST['subject_id']);
}

// Affecter un cours à une matière
if (isset($_POST['assign_course'])) {
    assignCourse(
        $_POST['subject_id'],
        $_POST['class_id'],
        $_POST['teacher_id'],
        $_POST['start_datetime'],
        $_POST['end_datetime']
    );
}

// Fonction pour ajouter une matière
function addSubject($subjectName) {
    global $pdo;

    try {
        $stmt = $pdo->prepare("INSERT INTO subject (SubName) VALUES (:subject_name)");
        $stmt->bindParam(':subject_name', $subjectName);
        $stmt->execute();
        redirectWithMessage('La matière a été ajoutée avec succès.');
    } catch (Exception $e) {
        redirectWithMessage('Erreur lors de l\'ajout de la matière.', 'error');
    }
}

// Fonction pour supprimer une matière
function deleteSubject($subjectId) {
    global $pdo;

    try {
        $stmt = $pdo->prepare("DELETE FROM subject WHERE idSubject = :subject_id");
        $stmt->bindParam(':subject_id', $subjectId);
        $stmt->execute();
        redirectWithMessage('La matière a été supprimée avec succès.');
    } catch (Exception $e) {
        redirectWithMessage('Erreur lors de la suppression de la matière.', 'error');
    }
}

// Fonction pour affecter un cours à une matière
function assignCourse($subjectId, $classId, $teacherId, $startDateTime, $endDateTime) {
    global $pdo;

    try {
        $stmt = $pdo->prepare(
            "INSERT INTO course (SubjectID, ClassID, TeacherID, StartDateTime, EndDateTime) 
            VALUES (:subject_id, :class_id, :teacher_id, :start_datetime, :end_datetime)"
        );
        $stmt->bindParam(':subject_id', $subjectId);
        $stmt->bindParam(':class_id', $classId);
        $stmt->bindParam(':teacher_id', $teacherId);
        $stmt->bindParam(':start_datetime', $startDateTime);
        $stmt->bindParam(':end_datetime', $endDateTime);
        
        $stmt->execute();
        redirectWithMessage('Le cours a été affecté avec succès.');
    } catch (Exception $e) {
        redirectWithMessage('Erreur lors de l\'affectation du cours.', 'error');
    }
}
?>
