<?php
$host = 'localhost'; // ou l'adresse de ton serveur MySQL
$dbname = 'WelcomeTraining';
$username = 'root'; // ton nom d'utilisateur MySQL
$password = ''; // ton mot de passe MySQL

$conn = new mysqli($host, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Erreur de connexion: " . $conn->connect_error);
}


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
?>
