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
}
