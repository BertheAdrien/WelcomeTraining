<?php
include_once('../include/Config.php');
include_once('../include/pdo.php');

// Récupérer l'ID de classe passé en paramètre
$classID = isset($_GET['classID']) ? intval($_GET['classID']) : null;

// Préparer la requête en fonction de la présence ou non d'un ID de classe
if ($classID) {
    // Si un ID de classe est fourni, filtrer les cours pour cette classe
    $query = "SELECT c.*, s.SubName, cl.ClassName 
              FROM course c
              JOIN subject s ON c.SubjectID = s.idSubject
              JOIN class cl ON c.ClassID = cl.idClasse
              WHERE c.ClassID = :classID";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['classID' => $classID]);
} else {
    // Sinon, récupérer tous les cours
    $query = "SELECT c.*, s.SubName, cl.ClassName 
              FROM course c
              JOIN subject s ON c.SubjectID = s.idSubject
              JOIN class cl ON c.ClassID = cl.idClasse";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
}

$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Formater les données pour FullCalendar
$events = [];
foreach ($courses as $course) {
    $events[] = [
        'id' => $course['idCourse'],
        'title' => $course['SubName'] . ' - ' . $course['ClassName'],
        'start' => $course['StartDateTime'],
        'end' => $course['EndDateTime'],
        'classId' => $course['ClassID'],
        'subjectId' => $course['SubjectID'],
    ];
}

// Définir l'en-tête pour indiquer que nous renvoyons du JSON
header('Content-Type: application/json');
echo json_encode($events);
exit;
?>