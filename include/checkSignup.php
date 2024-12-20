<?php
include_once 'Config.php'; // Vérifiez que $pdo est correctement configuré ici
include_once 'classes/user.php';
include_once('include/pdo.php');


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
        $user = new User($pdo);

        // Création de l'utilisateur
        if ($user->createUser($nom, $prenom, $email, $motdepasse)) {
            // Si réussi, redirection vers la page de connexion
            header("Location: Login.php?success=1");
            exit();
        } else {
            // En cas d'erreur
            echo "<div class='alert alert-danger'>Erreur lors de l'inscription. Veuillez réessayer.</div>";
        }
    }
}
?>
