<?php
include 'include/Config.php';

// Ajouter une nouvelle matière
if (isset($_POST['add_subject'])) {
    $subjectName = $_POST['subject_name'];
    $stmt = $pdo->prepare("INSERT INTO Subject (SubName) VALUES (:subject_name)");
    $stmt->bindParam(':subject_name', $subjectName);
    $stmt->execute();
    header('Location: manage_subjects.php');
    exit();
}

// Supprimer une matière
if (isset($_POST['delete_subject'])) {
    $subjectId = $_POST['subject_id'];
    $stmt = $pdo->prepare("DELETE FROM Subject WHERE idSubject = :subject_id");
    $stmt->bindParam(':subject_id', $subjectId);
    $stmt->execute();
    header('Location: manage_subjects.php');
    exit();
}

// Affecter un cours à une matière
if (isset($_POST['assign_course'])) {
    $subjectId = $_POST['subject_id'];
    $classId = $_POST['class_id'];
    $teacherId = $_POST['teacher_id'];
    $startDateTime = $_POST['start_datetime'];
    $endDateTime = $_POST['end_datetime'];

    $stmt = $pdo->prepare("INSERT INTO Course (SubjectID, ClassID, TeacherID, StartDateTime, EndDateTime) 
                           VALUES (:subject_id, :class_id, :teacher_id, :start_datetime, :end_datetime)");
    $stmt->bindParam(':subject_id', $subjectId);
    $stmt->bindParam(':class_id', $classId);
    $stmt->bindParam(':teacher_id', $teacherId);
    $stmt->bindParam(':start_datetime', $startDateTime);
    $stmt->bindParam(':end_datetime', $endDateTime);

    if ($stmt->execute()) {
        echo "Le cours a été ajouté avec succès.";
    } else {
        echo "Erreur lors de l'ajout du cours.";
    }
    header('Location: manage_subjects.php');
    exit();
}
?>
