<?php

// Inclure l'en-tête (header.php) qui contient le <head> et les éléments communs
include('partials/header.php');


?>

<!DOCTYPE html>
<html lang="en">

<body class="bg-light">
    <!-- Inclusion de la navbar -->

    <div class="container py-4">
        <div class="row mb-4">
            <div>
                <h6>Prénom de l'élève connecté: <?php echo htmlspecialchars($_SESSION['FirstName']); ?></h6>
            </div>
            <div>
                <h6>Nom de l'élève connecté: <?php echo htmlspecialchars($_SESSION['LastName']); ?></h6>
            </div>
            <div>
                <h6><?php echo date('d-m-Y'); ?></h6>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col text-center">
                <h1>Cours de la journée</h1>
            </div>
        </div>

        <!-- Ligne des blocs de cours en colonne -->
        <div class="row g-4">
            <!-- Cours Mathématiques -->
            <div class="col-12 d-flex justify-content-center">
                <div class="card shadow-sm cours-bloc" style="max-width: 400px; width: 100%;">
                    <div class="card-body">
                        <h2 class="card-title">Mathématiques</h2>
                        <h4 class="card-text">Heure début : </h4>
                        <h4 class="card-text">Heure fin : </h4>
                        <a href="signature.php" class="btn btn-primary mt-3 w-100">Signer</a>
                    </div>
                </div>
            </div>

            <!-- Cours Anglais -->
            <div class="col-12 d-flex justify-content-center">
                <div class="card shadow-sm cours-bloc" style="max-width: 400px; width: 100%;">
                    <div class="card-body">
                        <h2 class="card-title">Anglais</h2>
                        <h4 class="card-text">Heure début : </h4>
                        <h4 class="card-text">Heure fin : </h4>
                        <a href="signature.php" class="btn btn-primary mt-3 w-100">Signer</a>
                    </div>
                </div>
            </div>

            <!-- Cours Gilles -->
            <div class="col-12 d-flex justify-content-center">
                <div class="card shadow-sm cours-bloc" style="max-width: 400px; width: 100%;">
                    <div class="card-body">
                        <h2 class="card-title">Gilles</h2>
                        <h4 class="card-text">Heure début : </h4>
                        <h4 class="card-text">Heure fin : </h4>
                        <a href="signature.php" class="btn btn-primary mt-3 w-100">Signer</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (optionnel) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="assets/JS/dashboard.js"></script>
</body>
</html>
