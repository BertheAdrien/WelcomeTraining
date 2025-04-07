<?php

class Database {
    private $pdo;

    public function __construct() {
        $host = 'localhost';
        $dbname = 'welcometraining';
        $username = 'root';
        $password = 'Pokemon72380';

        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }

    public function getPDO() {
        return $this->pdo;
    }
}

