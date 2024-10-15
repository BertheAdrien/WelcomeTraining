<?php
include 'Config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $motdepasse = $_POST['motdepasse'];

    // Hachage du mot de passe
    $hashedPassword = password_hash($motdepasse, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO User (LastName, FirstName, Email, Password) VALUES (:nom, :prenom, :email, :motdepasse)");
    
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':prenom', $prenom);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':motdepasse', $hashedPassword);


    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Inscription réussie !</div>";
        header("Location: Login.php");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Erreur lors de l'inscription.</div>";
    }
}
?>
