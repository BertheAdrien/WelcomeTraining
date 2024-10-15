<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: Login.php');
    exit();
}

// Inclure la configuration de la base de données si nécessaire
include 'include/Config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Training - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/CSS/dashboard.css">

</head>
<body>

<?php
// Si l'utilisateur n'est pas admin, afficher la navbar générale utilisateur
if ($_SESSION['user_status'] !== 'Admin') {
?>
<!-- Navbar générale pour tous les utilisateurs -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand" href="#">Welcome Training</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="#">Mes cours</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Profil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Login.php">Déconnexion</a>
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
                    <a class="nav-link" href="manage_subjects.php">Gérer les matières</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Déconnexion</a> <!-- Bouton de déconnexion ajouté -->
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
