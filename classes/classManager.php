<?php

class ClassManager {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // 🔹 Récupérer toutes les classes
    public function getAllClasses(): array {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM class");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Erreur dans getAllClasses: ' . $e->getMessage());
            return [];
        }
    }

    // 🔹 Ajouter une nouvelle classe
    public function addClass(string $className): bool {
        $stmt = $this->pdo->prepare("INSERT INTO class (ClassName) VALUES (:className)");
        return $stmt->execute(['className' => $className]);
    }

    // 🔹 Supprimer une classe
    public function deleteClass(int $classId): bool {
        $stmt = $this->pdo->prepare("DELETE FROM class WHERE idClasse = :classId");
        return $stmt->execute(['classId' => $classId]);
    }
}
