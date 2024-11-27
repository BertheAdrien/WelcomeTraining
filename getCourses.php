<?php
include_once('include/Config.php'); 
include_once('include/pdo.php');

$classID = isset($_GET['classID']) ? intval($_GET['classID']) : 0;

$query = "SELECT s.SubName, CONCAT(u.FirstName, ' ', u.LastName) as TeacherName, c.ClassName, sc.StartDateTime, sc.EndDateTime 
          FROM Subject s
          JOIN course sc ON s.idSubject = sc.SubjectID
          JOIN user u ON sc.teacherID = u.idUser
          JOIN Class c ON sc.classID = c.idClasse";

if ($classID > 0) {
    $query .= " WHERE sc.classID = :classID";
}

$stmt = $pdo->prepare($query);

if ($classID > 0) {
    $stmt->bindParam(':classID', $classID, PDO::PARAM_INT);
}

$stmt->execute();
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

$events = [];
foreach ($courses as $course) {
    $events[] = [
        'title' => "{$course['SubName']} \n{$course['TeacherName']} \n({$course['ClassName']})",
        'start' => $course['StartDateTime'],
        'end' => $course['EndDateTime'],
    ];
}

