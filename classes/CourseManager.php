<?php
class CourseManager {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Récupère les cours en fonction de la recherche
    public function searchCourses($search) {
        $query = "SELECT sc.idCourse as CourseID, 
                         s.SubName, 
                         CONCAT(u.FirstName, ' ', u.LastName) as TeacherName, 
                         c.ClassName, 
                         sc.StartDateTime, 
                         sc.EndDateTime 
                  FROM subject s
                  JOIN course sc ON s.idSubject = sc.SubjectID
                  JOIN user u ON sc.TeacherID = u.idUser
                  JOIN class c ON sc.ClassID = c.idClasse
                  WHERE (s.SubName LIKE :search 
                         OR CONCAT(u.FirstName, ' ', u.LastName) LIKE :search 
                         OR c.ClassName LIKE :search)
                         AND sc.EndDateTime > NOW()
                  ORDER BY sc.StartDateTime ASC";

        $stmt = $this->pdo->prepare($query);
        $searchParam = '%' . $search . '%';
        $stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Supprime un cours
    public function deleteCourse($courseId) {
        $deleteStmt = $this->pdo->prepare("DELETE FROM course WHERE idCourse = :courseId");
        $deleteStmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
        return $deleteStmt->execute();
    }
}
