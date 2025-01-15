<?php
include_once('partials/header.php');
include_once('include/Config.php');
include_once('include/pdo.php');

// Récupérer l'idUser de l'élève depuis la session
$idUser = $_SESSION['idUser'];
$currentDate = date('Y-m-d');

// Requête pour récupérer les cours de la journée
$query = "
    SELECT sc.idCourse, s.SubName, sc.StartDateTime, sc.EndDateTime, c.ClassName
    FROM Subject s
    JOIN course sc ON s.idSubject = sc.SubjectID
    JOIN Class c ON sc.classID = c.idClasse
    WHERE sc.teacherID = :idUser
    AND DATE(sc.StartDateTime) = :currentDate
    AND sc.EndDateTime > CURRENT_TIMESTAMP
    ORDER BY sc.StartDateTime ASC";

$stmt = $pdo->prepare($query);
$stmt->bindParam(':idUser', $idUser, PDO::PARAM_INT);
$stmt->bindParam(':currentDate', $currentDate, PDO::PARAM_STR);
$stmt->execute();
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<body class="bg-light">

<div class="container py-4">
    <div class="row mb-4">
        <div>
            <h4>Bonjour <?php echo htmlspecialchars($_SESSION['first_name']) . ' ' . htmlspecialchars($_SESSION['last_name']); ?></h4>
        </div>
        <div>
            <h6><?php echo date('d-m-Y'); ?></h6>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col text-center">
            <h1>Cours de la journée</h1>
        </div>
    </div>

    <div class="row g-4">
    <?php foreach ($courses as $course): 
        $startTime = strtotime($course['StartDateTime']);
        $endTime = strtotime($course['EndDateTime']);
        $now = time();
        $isCurrentCourse = ($now >= $startTime && $now <= $endTime);
        ?>
        <div class="col-12 d-flex justify-content-center">
            <div class="card shadow-sm cours-bloc" style="max-width: 400px; width: 100%;">
                <div class="card-body">
                    <h2 class="card-title"><?php echo htmlspecialchars($course['SubName']); ?></h2>
                    <h4 class="card-text">Heure début : <?php echo date('H:i', $startTime); ?></h4>
                    <h4 class="card-text">Heure fin : <?php echo date('H:i', $endTime); ?></h4>
                    <h5 class="card-text">Classe : <?php echo htmlspecialchars($course['ClassName']); ?></h5>
                    
                    <?php if ($isCurrentCourse): ?>
                        <!-- Bouton pour gérer les présences -->
                        <a href="gestionPresence.php?courseId=<?php echo $course['idCourse']; ?>" 
                           class="btn btn-primary w-100 mt-3">
                            Gérer les présences
                        </a>
                    <?php else: ?>
                        <button class="btn btn-secondary w-100 mt-3" disabled>
                            Hors des horaires du cours
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>