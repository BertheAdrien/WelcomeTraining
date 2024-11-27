<?php

include_once('include/Config.php');
include_once('partials/header.php');
include_once('include/pdo.php');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['idUser'])) {
    echo json_encode(['status' => 'error', 'message' => 'Utilisateur non connecté']);
    exit();
}

// Récupérer l'ID du cours depuis l'URL
if (!isset($_GET['idCourse']) || empty($_GET['idCourse'])) {
    echo json_encode(['status' => 'error', 'message' => 'ID du cours manquant']);
    exit();
}

$idCourse = intval($_GET['idCourse']); // Assurez-vous de convertir en entier pour des raisons de sécurité

// Maintenant, vous pouvez utiliser $idCourse dans votre logique pour insérer la signature ou effectuer des opérations liées au cours
?>

<!DOCTYPE html>
<html lang="fr">
<body class="bg-light">
    <div class="container mt-5 signature-container">
        <div class="card shadow">
            <div class="card-body">
                <h2 class="text-center mb-4">Signature en ligne</h2>
                <canvas id="signatureCanvas"></canvas>
                <img id="savedSignature" class="img-fluid" alt="Signature sauvegardée" />
                
                <div class="button-container mt-3">
                    <button href="Dashboard.php" id="returnButton" class="btn btn-secondary">Retour</button>
                    <button id="clearButton" class="btn btn-warning">Effacer</button>
                    <button id="saveButton" class="btn btn-primary">Envoyer</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Signature soumise</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Votre signature a été soumise avec succès !
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>


    <!-- Bootstrap JS -->
    <script src="assets/JS/script.js"></script>
</body>
</html>
