<?php
session_start(); 

$title = 'Agenda';
include_once('../partials/header.php');     
include_once('../include/Config.php');
include_once('../include/pdo.php');

$isAdmin = $_SESSION['user_status'] === 'Admin';
    $course = "SELECT * FROM course";
    $courseStmt = $pdo->prepare($course);
    $courseStmt->execute();
    $events = $courseStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<html>
<body class="bg-light">
    <div id="calendar-container" class="container py-4">
        <h1 class="text-center mb-4">Agenda des Cours</h1>

        <?php if ($isAdmin): ?>
            <!-- Afficher la barre de sélection de classe uniquement pour l'admin -->
            <div class="mb-4">
                <select id="classSelect" class="form-select">
                    <option value="">Sélectionner une classe</option>
                    <?php
                    // Récupérer la liste des classes
                    $classQuery = "SELECT idClasse, ClassName FROM class";
                    $classStmt = $pdo->prepare($classQuery);
                    $classStmt->execute();
                    $classes = $classStmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    foreach ($classes as $class) {
                        echo "<option value=\"{$class['idClasse']}\">{$class['ClassName']}</option>";
                    }
                    ?>
                </select>
            </div>
        <?php endif; ?>
        
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
    <script src="../assets/JS/calendarAdmin.js"></script>
</body>
</html>
