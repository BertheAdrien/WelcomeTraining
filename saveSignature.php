<?php
session_start();
include_once('include/Config.php'); // Inclure la configuration de la base de données
include_once('include/pdo.php');
header('Content-Type: application/json'); // Définir le type de contenu comme JSON

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['idUser'])) {
    echo json_encode(['status' => 'error', 'message' => 'Utilisateur non connecté']);
    exit();
}

$idUser = $_SESSION['idUser']; // ID utilisateur récupéré depuis la session
$idCourse = isset($_GET['idCourse']) ? intval($_GET['idCourse']) : null; // Récupérer et valider l'ID du cours


// Récupérer les données JSON envoyées
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['imageData'])) {
    echo json_encode(['status' => 'error', 'message' => 'Données de signature manquantes']);
    exit();
}

$imageData = $data['imageData'];

// Supprimer l'en-tête de l'image (data:image/jpeg;base64,) et convertir en binaire
$imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
$imageData = str_replace(' ', '+', $imageData);
$imageData = base64_decode($imageData);

if ($imageData === false) {
    echo json_encode(['status' => 'error', 'message' => 'Données de l\'image invalides']);
    exit();
}

$createdAt = date('Y-m-d H:i:s'); // Timestamp actuel

// Utiliser une transaction pour s'assurer que les deux opérations sont atomiques
$conn->begin_transaction();

try {
    // Préparer la requête d'insertion de la signature
    $stmt = $conn->prepare("INSERT INTO signature (idUser, idCourse, imageData, created_at) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('iiss', $idUser, $idCourse, $imageData, $createdAt);

    if (!$stmt->execute()) {
        throw new Exception('Erreur lors de la sauvegarde de la signature : ' . $stmt->error);
    }

    // Valider la transaction
    $conn->commit();
    echo json_encode(['status' => 'success', 'message' => 'Signature sauvegardée avec succès']);
} catch (Exception $e) {
    // Annuler la transaction en cas d'erreur
    $conn->rollback();
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
} finally {
    // Fermer la requête préparée
    if (isset($stmt) && $stmt instanceof mysqli_stmt) {
        $stmt->close();
    }

    // Fermer la connexion
    $conn->close();
}

?>
