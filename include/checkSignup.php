<?php
include_once '../include/Config.php'; // Vérifiez que $pdo est correctement configuré ici
include_once '../classes/User.php';
include_once '../include/pdo.php';
include_once '../classes/UserManager.php';

// Vérification si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validation des données du formulaire
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $motdepasse = trim($_POST['motdepasse']);

    if (empty($nom) || empty($prenom) || empty($email) || empty($motdepasse)) {
        echo "<div class='alert alert-danger'>Tous les champs sont obligatoires.</div>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<div class='alert alert-danger'>L'adresse e-mail est invalide.</div>";
    } else {
        // Instance de la classe User
        $userManager = new UserManager($pdo, $email);

        // Création de l'utilisateur
        if ($userManager->createUser($nom, $prenom, $motdepasse)) {
            // Si réussi, redirection vers la page de connexion
            header("Location: ../pages/Login.php?success=1");
            exit();
        } else {
            // En cas d'erreur
            echo "<div class='alert alert-danger'>Erreur lors de l'inscription. Veuillez réessayer.</div>";
        }
    }
}
?>
