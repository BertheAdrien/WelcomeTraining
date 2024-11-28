<?php

// Inclure les fichiers nécessaires
include_once('include/Config.php');
include_once('include/pdo.php');
include_once('classes/Signature.php');
session_start();

// Vérifiez si les données ont été envoyées via POST
if (isset($_POST['idCourse']) && isset($_POST['signatureData'])) {
    // Récupérer l'id du cours et les données de la signature
    $idCourse = $_POST['idCourse'];
    $signatureData = $_POST['signatureData'];

    // Vérifier que les données de signature sont valides
    if (empty($signatureData)) {
        echo json_encode(['status' => 'error', 'message' => 'Aucune signature reçue.']);
        exit;
    }

    // Décoder les données de l'image base64
    $imageData = base64_decode(preg_replace('#^data:image/png;base64,#i', '', $signatureData));

    // Vérifier que l'image a bien été décodée
    if ($imageData === false) {
        echo json_encode(['status' => 'error', 'message' => 'Erreur lors de la décodification de l\'image.']);
        exit;
    }

    // Définir le chemin du fichier où l'image sera sauvegardée
    $timestamp = time();
    $fileName = "signatures/signature_" . $timestamp . "_signature.png";    // Sauvegarder l'image sur le serveur
    
    // file_put_contents($fileName, $imageData);

    // Créer une instance de la classe Signature
    
        $signatureManager = new Signature($pdo);

        // Sauvegarder la signature dans la base de données
        $filePath = $signatureManager->saveSignature($idCourse, $fileName);

        // Répondre avec un message de succès
        echo json_encode([
            'status' => 'success',
            'message' => 'Signature sauvegardée avec succès.',
            'filePath' => $filePath
        ]);
        
        // // Rediriger vers la page dashboard après un délai
        // //  header("Location: dashboard.php");
        //  exit();
} else {
    // Si l'idCourse ou la signature sont manquants, afficher une erreur
    echo json_encode(['status' => 'error', 'message' => 'Les données de signature ou l\'ID du cours sont manquants.']);
    exit;
}
?>
