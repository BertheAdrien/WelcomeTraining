<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['idUser'])) {
    header('Location: ../pages/login.php');
    exit();
}

function getUserClasses($userId, $pdo) {
    $classStmt = $pdo->prepare("
        SELECT class.ClassName, class.idClasse
        FROM user_has_class
        INNER JOIN class ON user_has_class.class_idClasse = class.idClasse
        WHERE user_has_class.user_idUser = :userId
    ");
    $classStmt->bindParam(':userId', $userId);
    $classStmt->execute();
    return $classStmt->fetchAll(PDO::FETCH_ASSOC);
}

// Inclure la configuration de la base de données si nécessaire
// include_once 'include/Config.php';
$title = isset($title) ? $title : 'Welcome training';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?></title>
    <link rel="stylesheet" href="../assets/CSS/dashboard.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../assets/CSS/dashboard.css">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/CSS/calendar.css" rel="stylesheet">
</head>
<body>

<?php
$current_page = basename($_SERVER['PHP_SELF']); // Récupère le nom du fichier courant
// Si l'utilisateur n'est pas admin, afficher la navbar générale utilisateur
if ($_SESSION['user_status'] === 'Student') {
?>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">Welcome Training</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page === 'dashboard_student.php' ? 'active font-weight-bold' : ''; ?>" href="../pages/dashboard_student.php">Mes cours</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page === 'view_shedule_student.php' ? 'active font-weight-bold' : ''; ?>" href="../pages/view_shedule_student.php">Calendrier</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page === 'Logout.php' ? 'active font-weight-bold' : ''; ?>" href="../pages/logout.php">Déconnexion</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<?php
}
?>

<?php

if ($_SESSION['user_status'] === 'Prof') {
?>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">Welcome Training</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page === 'dashboard_teacher.php' ? 'active font-weight-bold' : ''; ?>" href="../pages/dashboard_teacher.php">Mes cours</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page === 'view_shedule_teacher.php' ? 'active font-weight-bold' : ''; ?>" href="../pages/view_shedule_teacher.php">Calendrier</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page === 'Logout.php' ? 'active font-weight-bold' : ''; ?>" href="../pages/logout.php">Déconnexion</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<?php
}
?>

<?php
if ($_SESSION['user_status'] === 'Admin') {
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand" href="#">Admin Panel</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav" aria-controls="adminNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="adminNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page === 'admin_users.php' ? 'active font-weight-bold' : ''; ?>" href="../pages/admin_users.php">Gérer les utilisateurs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page === 'manage_classes.php' ? 'active font-weight-bold' : ''; ?>" href="../pages/manage_classes.php">Gérer les classes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page === 'manage_subjects.php' ? 'active font-weight-bold' : ''; ?>" href="../pages/manage_subjects.php">Gérer les cours</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page === 'view_shedule_admin.php' ? 'active font-weight-bold' : ''; ?>" href="../pages/view_shedule_admin.php">Voir les cours</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page === 'logout.php' ? 'active font-weight-bold' : ''; ?>" href="../pages/logout.php">Déconnexion</a>
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
