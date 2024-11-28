<?php
// Inclure les fichiers nécessaires
include_once('partials/header.php');

// Vérifiez si idCourse a été envoyé via POST
$idCourse = isset($_POST['idCourse']) ? $_POST['idCourse'] : null;

if ($idCourse === null) {
    echo "Erreur : Aucun cours sélectionné.";
    exit();
}
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
                    <!-- Retour au formulaire des cours -->
                    <button id="returnButton" class="btn btn-secondary" onclick="window.history.back();">Retour</button>
                    <button id="clearButton" class="btn btn-warning">Effacer</button>

                    <!-- Formulaire pour envoyer la signature -->
                    <form id="signatureForm" method="POST" action="Dashboard.php">
                        <input type="hidden" name="idCourse" value="<?php echo $idCourse; ?>">
                        <input type="hidden" name="signatureData" id="signatureData" value="">
                        <button type="submit" id="saveButton" class="btn btn-primary">Envoyer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal de succès -->
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="closeModal">Fermer</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/JS/script.js"></script>
    
</body>
</html>
