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
}