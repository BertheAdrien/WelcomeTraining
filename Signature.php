<?php include('partials/header.php');?>



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
