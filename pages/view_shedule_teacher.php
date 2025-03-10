<?php
session_start(); 
$title = 'Agenda';
include_once('../partials/header.php');     
include_once('../include/Config.php');
include_once('../include/pdo.php');
// Vérification si l'utilisateur est un professeur
if ($_SESSION['user_status'] !== 'Prof') {
    die('Accès non autorisé'); // Optionnel : Empêcher l'accès aux non-professeurs
}

$userId = $_SESSION['idUser'];  // Identifiant de l'utilisateur connecté

// Requête pour récupérer les cours associés à l'enseignant
$query = "SELECT s.SubName, CONCAT(u.FirstName, ' ', u.LastName) as TeacherName, c.ClassName, sc.StartDateTime, sc.EndDateTime 
          FROM subject s
          JOIN course sc ON s.idSubject = sc.SubjectID
          JOIN user u ON sc.teacherID = u.idUser
          JOIN class c ON sc.classID = c.idClasse
          WHERE sc.teacherID = :userId";

$stmt = $pdo->prepare($query);
$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
$stmt->execute();
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Préparer les événements pour le calendrier
$events = [];
foreach ($courses as $course) {
    $events[] = [
        'title' => "{$course['SubName']} \n{$course['TeacherName']} \n({$course['ClassName']})",
        'start' => $course['StartDateTime'],
        'end' => $course['EndDateTime'],
    ];
}
?>

<html>
<body class="bg-light">
    <div id="calendar-container" class="container py-4">
        <h1 class="text-center mb-4">Agenda des Cours</h1>

        <div id="calendar"></div>
    </div>
    
    <!-- JS de FullCalendar, Bootstrap, et notre fichier JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Injecter les événements JSON dans la variable JavaScript pour le fichier JS -->
    <script>
    const calendarEvents = <?php echo json_encode($events); ?>;
    </script>
    
    <!-- Script personnalisé pour initialiser le calendrier -->
    <script src="../assets/JS/calendar.js"></script>
</body>
</html>
