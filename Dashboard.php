<?php
include_once('partials/header.php');
include_once('include/Config.php');
include_once('include/pdo.php');
include_once('classes/Course.php');

$classID = $_SESSION['class_id'];
$currentDate = date('Y-m-d');
$currentDateTime = date('Y-m-d H:i:s');

// Instanciation de la classe Course
$courseManager = new Course($pdo);

// Récupération des cours de la journée
$courses = $courseManager->getCoursesForClass($classID, $currentDate, $currentDateTime);
?>

<!DOCTYPE html>
<html lang="en">
<body class="bg-light">

<div class="container py-4">
    <div class="row mb-4">
        <div>
            <h4>Bonjour <?php echo htmlspecialchars($_SESSION['first_name']) . ' ' . htmlspecialchars($_SESSION['last_name']); ?></h4>
            <h5><?php echo htmlspecialchars($_SESSION['class_name']) ?></h5>
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

    <!-- Ligne des blocs de cours en colonne -->
    <div class="row g-4">
<?php
foreach ($courses as $course) {
    $startTime = strtotime($course['StartDateTime']);
    $endTime = strtotime($course['EndDateTime']);
    $now = strtotime($currentDateTime);

    // Vérifiez si l'élève est déjà marqué comme présent pour ce cours
    $queryPresence = "SELECT Presence FROM user WHERE idUser = :idUser";
    $stmtPresence = $pdo->prepare($queryPresence);
    $stmtPresence->bindParam(':idUser', $_SESSION['idUser'], PDO::PARAM_INT);
    $stmtPresence->execute();
    $presence = $stmtPresence->fetch(PDO::FETCH_ASSOC);

    // Vérifiez si l'heure actuelle est dans l'intervalle du cours
    $isWithinTimeFrame = $now >= $startTime && $now <= $endTime;
    ?>
    <div class="col-12 d-flex justify-content-center">
        <div class="card shadow-sm cours-bloc" style="max-width: 400px; width: 100%;">
            <div class="card-body">
                <h2 class="card-title"><?php echo htmlspecialchars($course['SubName']); ?></h2>
                <h4 class="card-text">Heure début : <?php echo date('H:i', $startTime); ?></h4>
                <h4 class="card-text">Heure fin : <?php echo date('H:i', $endTime); ?></h4>
                <h4 class="card-text">ID : <?php echo htmlspecialchars($course['idCourse']); ?></h4>

                <?php if (($presence['Presence'] === 'Present') && ($isWithinTimeFrame)) : ?>
                    <!-- Élève déjà présent -->
                    <p class="text-success">Vous êtes marqué comme présent.</p>
                <?php elseif ($isWithinTimeFrame): ?>
                    <!-- Affichez le bouton de signature uniquement si dans l'horaire -->
                    <form method="POST" action="drawSignature.php">
                        <input type="hidden" name="idCourse" value="<?php echo $course['idCourse']; ?>">
                        <button type="submit" class="btn btn-primary mt-3 w-100">Signer</button>
                    </form>
                <?php else: ?>
                    <!-- Hors de l'horaire -->
                    <p class="text-warning"></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php
}
?>
</div>
</div>



<!-- Bootstrap JS (optionnel) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
