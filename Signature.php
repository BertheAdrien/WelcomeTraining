<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signature en ligne + calendar</title>
    <link rel="stylesheet" href="assets/CSS/style.css">

</head>

<body>
    <!-- SIGNATURE -->
    <div class="signature-container">
        <h2>Signature en ligne</h2>
        <canvas id="signatureCanvas" width="500" height="200"></canvas>
        <img id="savedSignature" alt="Signature sauvegardée" style="margin-top: 20px; display: none;"/>
        <br>
        <div class="button-container">
            <button id="returnButton">Retour</button>
            <button id="clearButton">Effacer</button>
            <button id="saveButton">Envoyer</button>
        </div>
  
        <br>
        
        
    </div>
    <!-- FIN SIGNATURE -->
<script src="assets/JS/script.js"></script>
</body>
</html>