<?php
$host = 'localhost'; // ou l'adresse de ton serveur MySQL
$dbname = 'WelcomeTraining';
$username = 'root'; // ton nom d'utilisateur MySQL
$password = ''; // ton mot de passe MySQL

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
?>
