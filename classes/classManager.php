<?php

class ClassManager 
{
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // 🔹 Récupérer toutes les classes
    public function getAllClasses(): array {
        $stmt = $this->pdo->prepare("SELECT * FROM class");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🔹 Ajouter une nouvelle classe
    public function addClass(string $className): bool {
        $stmt = $this->pdo->prepare("INSERT INTO class (ClassName) VALUES (:className)");
        return $stmt->execute(['ClassName' => $className]);
    }

    // 🔹 Supprimer une classe
    public function deleteClass(int $classId): bool {
        $stmt = $this->pdo->prepare("DELETE FROM class WHERE idClasse = :classId");
        return $stmt->execute(['idClasse' => $classId]);
    }
}
