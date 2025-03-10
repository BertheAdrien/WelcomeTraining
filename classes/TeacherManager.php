<?php
class TeacherManager {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getTodayTeacherCourses($teacherId) {
        $currentDate = date('Y-m-d');

        $query = "
            SELECT sc.idCourse, s.SubName, sc.StartDateTime, sc.EndDateTime, c.ClassName
            FROM subject s
            JOIN course sc ON s.idSubject = sc.SubjectID
            JOIN class c ON sc.classID = c.idClasse
            WHERE sc.teacherID = :teacherId
            AND DATE(sc.StartDateTime) = :currentDate
            -- AND sc.EndDateTime > CURRENT_TIMESTAMP
            ORDER BY sc.StartDateTime ASC";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':teacherId', $teacherId, PDO::PARAM_INT);
        $stmt->bindParam(':currentDate', $currentDate, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        var_dump($result);
        return $result;
    }
}
