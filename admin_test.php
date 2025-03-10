<?php
session_start();
$_SESSION['user_status'] = 'Admin'; // Simuler un admin connecté
include_once 'partials/header.php'; // Votre fichier header
?>
<div class="container mt-4">
    <h1>Test Page</h1>
    <p>Ceci est un test</p>
</div>
</body>
</html>