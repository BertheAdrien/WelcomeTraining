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

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signature en ligne</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #signatureCanvas {
            border: 1px solid #ccc;
            background-color: white;
            width: 100%;
            height: 200px;
            margin-bottom: 20px;
        }
        .signature-container {
            max-width: 800px;
            margin: auto;
        }
        .button-container {
            display: flex;
            gap: 10px;
            justify-content: center;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-5 signature-container">
        <div class="card shadow">
            <div class="card-body">
                <h2 class="text-center mb-4">Signature en ligne</h2>
                <canvas id="signatureCanvas"></canvas>
                
                <div class="button-container mt-3">
                    <button id="returnButton" class="btn btn-secondary" onclick="window.history.back();">Retour</button>
                    <button id="clearButton" class="btn btn-warning">Effacer</button>

                    <form id="signatureForm" method="POST" action="signatureLogic.php">
                        <input type="hidden" name="idCourse" value="<?php echo htmlspecialchars($idCourse); ?>">
                        <input type="hidden" name="signatureData" id="signatureData">
                        <button type="submit" id="saveButton" class="btn btn-primary">Envoyer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/JS/script.js"></script>
</body>

</body>
</html>
