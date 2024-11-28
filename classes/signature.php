<?php

class Signature
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function saveSignature($courseId, $imageContent)
    {
        // Récupérer l'ID de l'utilisateur depuis la session
        if (!isset($_SESSION['idUser'])) {
            throw new Exception("L'utilisateur n'est pas connecté.");
        }
        $userId = $_SESSION['idUser'];

        // Extraire les données de l'image (base64)
        $imageContent = str_replace('data:image/jpeg;base64,', '', $imageContent);
        $imageContent = str_replace(' ', '+', $imageContent);
        $imageData = base64_decode($imageContent);

        // Vérifier que l'image a bien été décodée
        if ($imageData === false) {
            throw new Exception("Erreur lors du décodage de l'image.");
        }

        // Définir le chemin pour sauvegarder l'image
        $signaturePath = 'signatures/' . uniqid() . '.jpg';

        // Sauvegarder l'image dans le répertoire 'signatures/'
        if (file_put_contents($signaturePath, $imageData) === false) {
            throw new Exception("Erreur lors de la sauvegarde de l'image.");
        }

        // Sauvegarde dans la base de données
        $query = "INSERT INTO signature (idCourse, idUser, file_path, created_at) 
                  VALUES (:courseId, :userId, :filePath, NOW())";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':filePath', $signaturePath, PDO::PARAM_STR);

        if (!$stmt->execute()) {
            throw new Exception("Erreur lors de l'enregistrement de la signature dans la base de données.");
        }

        return $signaturePath; // Retourne le chemin de l'image enregistrée
    }
}
?>
