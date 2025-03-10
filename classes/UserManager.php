<?php
class UserManager {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getUserByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM user WHERE Email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUsers($search = '') {
        $stmt = $this->pdo->prepare("SELECT * FROM user WHERE LastName LIKE :search OR FirstName LIKE :search OR Email LIKE :search");
        $searchParam = '%' . $search . '%';
        $stmt->bindParam(':search', $searchParam);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createUser($lastName, $firstName, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO user (LastName, FirstName, Email, Password) VALUES (:lastName, :firstName, :email, :password)");
        $stmt->bindParam(':lastName', $lastName);
        $stmt->bindParam(':firstName', $firstName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        return $stmt->execute();
    }

    public function updateUser($userId, $lastName, $firstName, $email, $status) {
        $stmt = $this->pdo->prepare("UPDATE user SET LastName = :lastName, FirstName = :firstName, Email = :email, Status = :status WHERE idUser = :userId");
        $stmt->bindParam(':lastName', $lastName);
        $stmt->bindParam(':firstName', $firstName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':userId', $userId);
        return $stmt->execute();
    }

    public function deleteUser($userId) {
        $stmt = $this->pdo->prepare("DELETE FROM user WHERE idUser = :userId");
        $stmt->bindParam(':userId', $userId);
        return $stmt->execute();
    }
    public function getUserById($userId) {
        $stmt = $this->pdo->prepare("SELECT * FROM user WHERE idUser = :userId");
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllClasses() {
        $stmt = $this->pdo->query("SELECT * FROM class");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserClasses($userId) {
        $stmt = $this->pdo->prepare("
            SELECT class.idClasse, class.ClassName 
            FROM user_has_class 
            JOIN class ON user_has_class.Class_idClasse = class.idClasse
            WHERE user_has_class.User_idUser = :userId
        ");
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        var_dump($result);
        return $result;
    }

    public function addUserClass($userId, $classId) {
        // Vérifier si la classe est déjà associée à l'utilisateur
        $checkStmt = $this->pdo->prepare("
            SELECT * FROM user_has_class WHERE User_idUser = :userId AND Class_idClasse = :classId
        ");
        $checkStmt->bindParam(':userId', $userId);
        $checkStmt->bindParam(':classId', $classId);
        $checkStmt->execute();

        if ($checkStmt->rowCount() == 0) {
            // Associer la classe à l'utilisateur
            $insertStmt = $this->pdo->prepare("
                INSERT INTO user_has_class (User_idUser, Class_idClasse) VALUES (:userId, :classId)
            ");
            $insertStmt->bindParam(':userId', $userId);
            $insertStmt->bindParam(':classId', $classId);
            return $insertStmt->execute();
        }

        return false;
    }

    public function deleteUserClass($userId, $classId) {
        $stmt = $this->pdo->prepare("
            DELETE FROM user_has_class WHERE User_idUser = :userId AND Class_idClasse = :classId
        ");
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':classId', $classId);
        return $stmt->execute();
    }

    public function getStudentsByClass($classId) {
        $stmt = $this->pdo->prepare("SELECT u.FirstName, u.LastName, u.Email 
                                     FROM user u
                                     JOIN user_has_class uc ON u.idUser = uc.User_idUser
                                     WHERE uc.Class_idClasse = :classId AND u.Status = 'Student'");
        $stmt->bindParam(':classId', $classId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function loginUser($email, $password) {
        $query = "SELECT idUser, FirstName, LastName, Password, Status FROM user WHERE Email = :email";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['Password'])) {
            return $user; // Retourne l'utilisateur si le mot de passe est bon
        }
        return false; // Mauvais identifiants
    }
}

