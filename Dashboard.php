<?php
include('partials/header.php');
include('include/Config.php');

// Récupérer le classID de l'élève depuis la session
$classID = $_SESSION['class_id'];
$currentDate = date('Y-m-d'); // Obtenir la date actuelle pour filtrer les cours de la journée
$currentDateTime = date('Y-m-d H:i:s'); // Obtenir la date et l'heure actuelles pour comparer aux cours

// Requête pour récupérer les cours de la journée pour l'élève connecté, triés par heure de début
$query = "SELECT sc.idCourse, s.SubName, sc.StartDateTime, sc.EndDateTime
          FROM Subject s
          JOIN course sc ON s.idSubject = sc.SubjectID
          WHERE sc.classID = :classID 
          AND DATE(sc.StartDateTime) = :currentDate
          AND sc.EndDateTime > :currentDateTime  -- Exclure les cours dont l'heure de fin est dépassée
          ORDER BY sc.StartDateTime ASC";  // Tri par StartDateTime en ordre croissant

$stmt = $pdo->prepare($query);
$stmt->bindParam(':classID', $classID, PDO::PARAM_INT);
$stmt->bindParam(':currentDate', $currentDate, PDO::PARAM_STR);
$stmt->bindParam(':currentDateTime', $currentDateTime, PDO::PARAM_STR);
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

                <?php if (($presence['Presence'] === 'Present') && ($isWithinTimeFrame)) : ?>
                    <!-- Élève déjà présent -->
                    <p class="text-success">Vous êtes marqué comme présent.</p>
                <?php elseif ($isWithinTimeFrame): ?>
                    <!-- Affichez le bouton de signature uniquement si dans l'horaire -->
                    <a href="Signature.php?idCourse=<?php echo $course['idCourse']; ?>" class="btn btn-primary mt-3 w-100">Signer</a>
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
