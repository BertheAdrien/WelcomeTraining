<?php
class User {
    private $pdo;
    private $sqlRequestInsertNewUser = "INSERT INTO User (LastName, FirstName, Email, Password) VALUES (:lastName, :firstName, :email, :password)";

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }



    public function loginUser($email, $password){
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM User WHERE Email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                if (password_verify($password, $user['Password'])) {
                    return $user;
                }
            }
            return null;
        } catch (PDOException $e) {
            error_log("Erreur lors de la connexion : " . $e->getMessage());
            return null;
        }
    }

    public function getUserClass($userId) {
        try {
            $query = "
                SELECT c.ClassName, c.idClasse
                FROM Class c
                JOIN user_has_class uc ON c.idClasse = uc.Class_idClasse
                WHERE uc.user_idUser = :userId";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération de la classe : " . $e->getMessage());
            return null;
        }
    }

    public function createUser($lastName, $firstName, $email, $password) {     
        try {
            // Hashage du mot de passe
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // Préparation et exécution de la requête
            $stmt = $this->pdo->prepare($this->sqlRequestInsertNewUser);
            $stmt->bindParam(':lastName', $lastName);
            $stmt->bindParam(':firstName', $firstName);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);

            return $stmt->execute();
        } catch (PDOException $e) {
            // Log ou gestion des erreurs
            error_log("Erreur lors de la création d'utilisateur : " . $e->getMessage());
            return false;
        }
    }
    public function getUsers($search = '') {
        $query = "SELECT * FROM User WHERE LastName LIKE :search OR FirstName LIKE :search OR Email LIKE :search";
        $stmt = $this->pdo->prepare($query);
        $searchParam = '%' . $search . '%';
        $stmt->bindParam(':search', $searchParam);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
        // Mettre à jour le statut d'un utilisateur
    public function updateStatus($userId, $status) {
        $query = "UPDATE User SET Status = :status WHERE idUser = :userId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':status', $status);
        $stmt->execute();
    }

    // Supprimer une classe pour un utilisateur
    public function deleteClass($userId, $classId) {
        $query = "DELETE FROM User_has_Class WHERE User_idUser = :userId AND Class_idClasse = :classId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':classId', $classId);
        $stmt->execute();
    }

    public function updateUser($userId, $lastName, $firstName, $email, $status) {
        $query = "UPDATE User 
                    SET LastName = :lastName, 
                        FirstName = :firstName, 
                        Email = :email, 
                        Status = :status 
                    WHERE idUser = :userId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':lastName', $lastName);
        $stmt->bindParam(':firstName', $firstName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':userId', $userId);        
        $stmt->execute();
    }
        
}