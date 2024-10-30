<?php
$title = 'Agenda';
include('partials/header.php');     
include('include/Config.php');
include('getCourses.php')
?>

<html>
<body class="bg-light">
    <div id="calendar-container" class="container py-4">
        <h1 class="text-center mb-4">Agenda des Cours</h1>
        <div class="mb-4">
            <label for="classSelect" class="form-label">Sélectionner une classe :</label>
            <select id="classSelect" class="form-select">
            <option value="">Tous les cours</option>
            <?php
            // Récupérer la liste des classes
            $classQuery = "SELECT idClasse, ClassName FROM Class";
            $classStmt = $pdo->prepare($classQuery);
            $classStmt->execute();
            $classes = $classStmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($classes as $class) {
                echo "<option value=\"{$class['idClasse']}\">{$class['ClassName']}</option>";
            }
            ?>
            </select>
        </div>
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
    <script src="assets/js/calendar.js"></script>
</body>
</html>
