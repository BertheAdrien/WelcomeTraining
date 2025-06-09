<?php
include_once '../include/pdo.php';
include_once '../classes/UserManager.php';
include_once '../controllers/UserController.php';

session_start();

$userManager = new UserManager($pdo, $_SESSION['email']);
$userController = new UserController($userManager);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_user'])) {
        $userController->updateUser($_POST['user_id'], $_POST['last_name'], $_POST['first_name'], $_POST['email'], $_POST['status']);
    }

    if (isset($_POST['delete_user'])) {
        $userController->deleteUser($_POST['user_id']);
    }

    if (isset($_POST['create_user'])) {
        $userController->createUser($_POST['last_name'], $_POST['first_name'], $_POST['email'], $_POST['status']);
    }

    header('Location: ../pages/admin_users.php');
    exit();
}
