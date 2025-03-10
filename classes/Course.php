<?php

class Course
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getCoursesForClass($classId, $currentDate, $currentDateTime)
    {
        $query = "SELECT sc.idCourse, s.SubName, sc.StartDateTime, sc.EndDateTime
                  FROM Subject s
                  JOIN course sc ON s.idSubject = sc.SubjectID
                  WHERE sc.classID = :classID 
                  AND DATE(sc.StartDateTime) = :currentDate
                  AND sc.EndDateTime > :currentDateTime
                  ORDER BY sc.StartDateTime ASC";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':classID', $classId, PDO::PARAM_INT);
        $stmt->bindParam(':currentDate', $currentDate, PDO::PARAM_STR);
        $stmt->bindParam(':currentDateTime', $currentDateTime, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCourseById($idCourse)
    {
        $query = "SELECT * FROM course WHERE idCourse = :idCourse";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':idCourse', $idCourse, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getStudentsByCourse(int $courseId): array {
        $query = "
            SELECT u.idUser, u.FirstName, u.LastName, 
                   ca.can_sign, ca.signature_path
            FROM user u
            JOIN user_has_class uhc ON u.idUser = uhc.User_idUser
            JOIN class c ON uhc.Class_idClasse = c.idClasse
            JOIN course sc ON c.idClasse = sc.classID
            LEFT JOIN course_attendance ca ON u.idUser = ca.student_id AND ca.course_id = :courseId
            WHERE sc.idCourse = :courseId 
            AND u.status = 'Student'
            ORDER BY u.LastName, u.FirstName;
        ";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':courseId' => $courseId]);
        return $stmt->fetchAll();
    }
}
