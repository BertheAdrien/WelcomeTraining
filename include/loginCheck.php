<?php
session_start();
include_once 'Config.php';
include_once 'classes/user.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['Email'];
    $password = $_POST['motdepasse'];

    $database = new Database();
    $pdo = $database->getPDO();

    // Initialiser la classe User
    $userModel = new User($pdo);

    // Vérifier les informations d'identification
    $user = $userModel->loginUser($email, $password);

    if ($user) {
        // Initialiser les variables de session
        $_SESSION['idUser'] = $user['idUser'];
        $_SESSION['user_status'] = $user['Status'];
        $_SESSION['first_name'] = $user['FirstName'];
        $_SESSION['last_name'] = $user['LastName'];

        // Récupérer la classe associée
        $class = $userModel->getUserClass($user['idUser']);
        if ($class) {
            $_SESSION['class_name'] = $class['ClassName'];
            $_SESSION['class_id'] = $class['idClasse'];
        } else {
            $_SESSION['class_name'] = null;
            $_SESSION['class_id'] = null;
        }

        // Redirection en fonction du statut de l'utilisateur
        switch ($_SESSION['user_status']) {
            case 'Admin':
                header('Location: manage_users.php');
                break;
            case 'Prof':
                header('Location: dashboardProf.php');
                break;
            default:
                header('Location: Dashboard.php');
                break;
        }
        exit();
    } else {
        $error = "Email ou mot de passe incorrect.";
    }
}
?>
