<?php

class UserController {
    private $user;
    private $classManager;

    public function __construct($user, $classManager) {
        $this->user = $user;
        $this->classManager = $classManager;
    }

    public function handleSearch($search) {
        return $this->user->getUsers($search);
    }

    public function handleUpdateStatus($userId, $lastName, $firstName, $email, $status) {
        $this->user->updateUser($userId, $lastName, $firstName, $email, $status);
    }

    public function handleDeleteClass($userId, $classId) {
        $this->user->deleteClass($userId, $classId);
    }

    public function getClasses() {
        return $this->classManager->getClasses();
    }
    
    public function handleDeleteUser($userId) {
        $this->user->deleteUser($userId);
    }
}
?>
