
<?php include_once('../include/loginCheck.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/CSS/dashboard.css">

    
</head>

<body class="bg-light">
    <div class="container py-4">
        <div class="row justify-content-center mb-5">
            <div class="col-md-6 col-lg-4">
                <h1 class="text-center mb-4">Connexion</h1>
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label for="Email" class="form-label">Adresse Mail</label>
                                <input type="text" class="form-control" id="Email" name="Email" required>
                            </div>
                            <div class="mb-3">
                                <label for="motdepasse" class="form-label">Mot de passe</label>
                                <input type="password" class="form-control" id="motdepasse" name="motdepasse" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                        </form>
                        <?php
                        if (isset($error)) {
                            echo "<div class='alert alert-danger mt-3'>$error</div>";
                        }
                        ?>
                    </div>
                </div>
                <div class="mt-3 text-center">
                    <a href="../pages/Signup.php">Créer un compte</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
