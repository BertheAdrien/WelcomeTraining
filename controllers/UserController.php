<?php
class UserController {
    private $userManager;

    public function __construct($userManager) {
        $this->userManager = $userManager;
    }

    public function searchUsers($search) {
        return $this->userManager->getUsers($search);
    }

    public function updateUser($userId, $lastName, $firstName, $email, $status, $newEmail) {
        return $this->userManager->updateUser($userId, $lastName, $firstName, $email, $status, $newEmail);
    }

    public function deleteUser($userId) {
        return $this->userManager->deleteUser($userId);
    }
    public function getUserById($userId) {
        return $this->userManager->getUserById($userId);
    }

    public function getAllClasses() {
        return $this->userManager->getAllClasses();
    }

    public function getUserClasses($userId) {
        return $this->userManager->getUserClasses($userId);
    }

    public function addUserClass($userId, $classId) {
        return $this->userManager->addUserClass($userId, $classId);
    }

    public function deleteUserClass($userId, $classId) {
        return $this->userManager->deleteUserClass($userId, $classId);
    }
    // Récupérer les étudiants d'une classe
    public function getStudentsByClass($classId) {
        return $this->userManager->getStudentsByClass($classId);
    }
}
