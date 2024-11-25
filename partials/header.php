<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['idUser'])) {
    header('Location: Login.php');
    exit();
}

function getUserClasses($userId, $pdo) {
    $classStmt = $pdo->prepare("
        SELECT Class.ClassName, Class.idClasse
        FROM User_has_Class
        INNER JOIN Class ON User_has_Class.Class_idClasse = Class.idClasse
        WHERE User_has_Class.User_idUser = :userId
    ");
    $classStmt->bindParam(':userId', $userId);
    $classStmt->execute();
    return $classStmt->fetchAll(PDO::FETCH_ASSOC);
}

// Inclure la configuration de la base de données si nécessaire
include 'include/Config.php';
$title = isset($title) ? $title : 'Welcome training';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?></title>
    <link href="assets/css/calendar.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="assets/CSS/dashboard.css">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/calendar.css" rel="stylesheet">
    


</head>
<body>

<?php
// Si l'utilisateur n'est pas admin, afficher la navbar générale utilisateur
if ($_SESSION['user_status'] !== 'Admin') {
?>
<!-- Navbar générale pour tous les utilisateurs -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">Welcome Training</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="Dashboard.php">Mes cours</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="view_shedule_student.php">Calendrier</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Logout.php">Déconnexion</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<?php
}
?>

<?php
// Si l'utilisateur est Admin, afficher une barre de navigation supplémentaire avec bouton de déconnexion
if ($_SESSION['user_status'] === 'Admin') {
?>
<!-- Navbar spéciale pour les administrateurs -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand" href="#">Admin Panel</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav" aria-controls="adminNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="adminNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="manage_users.php">Gérer les utilisateurs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="manage_classes.php">Gérer les classes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="manage_subjects.php">Gérer les cours</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="view_shedule_admin.php">Voir les cours</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Déconnexion</a> <!-- Bouton de déconnexion ajouté -->
                </li>
            </ul>
        </div>
    </div>
</nav>
<?php
}
?>

<!-- Contenu de la page ici -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
