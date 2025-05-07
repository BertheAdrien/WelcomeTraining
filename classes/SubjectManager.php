<?php

class SubjectManager {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Fonction pour ajouter une matière
    public function addSubject($subjectName) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO subject (SubName) VALUES (:subject_name)");
            $stmt->bindParam(':subject_name', $subjectName);
            $stmt->execute();
            return 'La matière a été ajoutée avec succès.';
        } catch (Exception $e) {
            return 'Erreur lors de l\'ajout de la matière.';
        }
    }

    // Fonction pour supprimer une matière
    public function deleteSubject($subjectId) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM subject WHERE idSubject = :subject_id");
            $stmt->bindParam(':subject_id', $subjectId);
            $stmt->execute();
            return 'La matière a été supprimée avec succès.';
        } catch (Exception $e) {
            return 'Erreur lors de la suppression de la matière.';
        }
    }

    // Fonction pour affecter un cours à une matière, classe et prof
    public function assignCourse($subjectId, $classId, $teacherId, $startDateTime, $endDateTime) {
        try {
            // Préparer la requête d'insertion
            $stmt = $this->pdo->prepare(
                "INSERT INTO course (SubjectID, ClassID, TeacherID, StartDateTime, EndDateTime) 
                VALUES (:subject_id, :class_id, :teacher_id, :start_datetime, :end_datetime)"
            );
    
            // Lier les paramètres
            $stmt->bindParam(':subject_id', $subjectId);
            $stmt->bindParam(':class_id', $classId);
            $stmt->bindParam(':teacher_id', $teacherId);
            $stmt->bindParam(':start_datetime', $startDateTime);
            $stmt->bindParam(':end_datetime', $endDateTime);
    
            // Exécuter la requête
            $stmt->execute();
            return 'Cours affecté avec succès.';
        } catch (PDOException $e) {
            // Afficher l'erreur PDO pour mieux comprendre
            return 'Erreur lors de l\'affectation du cours : ' . $e->getMessage();
        } catch (Exception $e) {
            return 'Erreur générale : ' . $e->getMessage();
        }
    }
}
?>
