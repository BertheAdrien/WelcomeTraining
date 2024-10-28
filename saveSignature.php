<?php
session_start();
include('include/Config.php'); // Assurez-vous d'inclure votre fichier de connexion à la base de données

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['idUser'])) {
    echo json_encode(['status' => 'error', 'message' => 'Utilisateur non connecté']);
    exit();
}

$idUser = $_SESSION['idUser']; // Récupérer l'ID de l'utilisateur de la session

// Récupérer les données JSON envoyées
$data = json_decode(file_get_contents('php://input'), true);
$imageData = $data['imageData'];

// Supprimer l'en-tête de l'image (data:image/jpeg;base64,)
$imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
$imageData = str_replace(' ', '+', $imageData);

// Convertir l'image en binaire
$imageData = base64_decode($imageData);

// Préparer la requête d'insertion
$stmt = $conn->prepare("INSERT INTO signature (idUser, imageData, created_at) VALUES (?, ?, ?)");
$createdAt = date('Y-m-d H:i:s'); // Timestamp actuel
$stmt->bind_param('iss', $idUser, $imageData, $createdAt);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Signature sauvegardée avec succès']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Erreur lors de la sauvegarde de la signature']);
}

// Fermer la connexion
$stmt->close();
$conn->close();
?>
