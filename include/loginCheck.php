<?php
session_start();
// Inclure le fichier de connexion
include 'Config.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Email = $_POST['Email'];
    $motdepasse = $_POST['motdepasse'];

    // Préparer une requête SQL pour rechercher l'utilisateur
    $stmt = $pdo->prepare("SELECT * FROM User WHERE Email = :Email");
    $stmt->bindParam(':Email', $Email);
    $stmt->execute();

    // Vérifier si un utilisateur a été trouvé
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifier si le mot de passe est correct
        if (password_verify($motdepasse, $user['Password'])) {
            
            $_SESSION['idUser'] = $user['idUser']; 
            $_SESSION['user_status'] = $user['Status']; 
            $_SESSION['first_name'] = $user['FirstName'];
            $_SESSION['last_name'] = $user['LastName'];

            // Requête pour récupérer la classe de l'élève
            $query = "
                SELECT c.ClassName, c.idClasse
                FROM Class c
                JOIN user_has_class uc ON c.idClasse = uc.Class_idClasse
                WHERE uc.user_idUser = :userID";

            $classStmt = $pdo->prepare($query);
            $classStmt->bindParam(':userID', $user['idUser'], PDO::PARAM_INT);
            $classStmt->execute();

            $class = $classStmt->fetch(PDO::FETCH_ASSOC);

            // Vérifier si une classe a été trouvée et la stocker dans la session
            if ($class) {
                $_SESSION['class_name'] = $class['ClassName'];
                $_SESSION['class_id'] = $class['idClasse'];
            } else {
                $_SESSION['class_name'] = null; // Optionnel : Si aucune classe n'est trouvée
                $_SESSION['class_id'] = null;
            }

            // Redirection en fonction du statut de l'utilisateur
            if ($_SESSION['user_status'] === 'Admin') {
                header('Location: manage_users.php'); 
            } else if ($_SESSION['user_status'] === 'Prof') {
                header('Location: Prof.php'); 
            } else {
                header('Location: Dashboard.php');
            }
            exit();
        } else {
            $error = "Mot de passe incorrect.";
        }
    } else {
        $error = "Email de compte non trouvé.";
    }
}
?>
