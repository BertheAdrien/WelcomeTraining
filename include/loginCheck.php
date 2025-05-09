<?php
session_start();
include_once '../include/Config.php';
include_once '../include/pdo.php';
include_once '../classes/UserManager.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['Email'];
    $password = $_POST['motdepasse'];

    $userManager = new UserManager($pdo, $email);
    $user = $userManager->loginUser($password);

    if ($user) {
        $_SESSION['idUser'] = $user['idUser'];
        $_SESSION['user_status'] = $user['Status'];
        $_SESSION['first_name'] = $user['FirstName'];
        $_SESSION['last_name'] = $user['LastName'];

        // Récupérer la classe associée
        $class = $userManager->getUserClasses($user['idUser']);
        $_SESSION['class_name'] = $class['ClassName'];
        $_SESSION['class_id'] = $class['idClasse'];

        // Redirection selon le statut
        switch ($_SESSION['user_status']) {
            case 'Admin':
                header('Location: ../pages/admin_users.php');
                break;
            case 'Prof':
                header('Location: ../pages/dashboard_teacher.php');
                break;
            default:
                header('Location: ../pages/dashboard_student.php');
                break;
        }
        exit();
    } else {
        $error = "Email ou mot de passe incorrect";
    }
}
?>