<?php include('partials/header.php'); ?>

<?php
// Inclure la configuration et la connexion à la base de données
include 'include/Config.php';

// Récupérer toutes les classes depuis la base de données
$query = "SELECT * FROM Class";
$stmt = $pdo->prepare($query);
$stmt->execute();
$classes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fonction pour récupérer les élèves d'une classe
function getStudentsByClass($classId, $pdo) {
    $stmt = $pdo->prepare("
        SELECT User.FirstName, User.LastName, User.Email 
        FROM User_has_Class 
        INNER JOIN User ON User.idUser = User_has_Class.User_idUser
        WHERE User_has_Class.Class_idClasse = :classId
    ");
    $stmt->bindParam(':classId', $classId);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des classes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/CSS/manage_classes.css">
</head>
<body class="bg-light">
    <div class="container py-4">
        <h1 class="text-center mb-4">Gestion des classes</h1>

        <!-- Liste des classes -->
        <h2>Classes existantes</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nom de la classe</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($classes as $class) : ?>
                    <tr>
                        <!-- Nom de la classe cliquable pour afficher les élèves -->
                        <td>
                            <a href="#" class="class-link" data-class-id="<?php echo $class['idClasse']; ?>">
                                <?php echo htmlspecialchars($class['ClassName']); ?>
                            </a>
                        </td>
                        <td>
                            <!-- Formulaire pour supprimer une classe -->
                            <form method="POST" style="display:inline-block;">
                                <input type="hidden" name="class_id" value="<?php echo $class['idClasse']; ?>">
                                <button type="submit" name="delete_class" class="btn btn-danger">✖</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Formulaire pour ajouter une nouvelle classe -->
        <h2 class="mt-4">Ajouter une nouvelle classe</h2>
        <form method="POST" class="d-flex mb-4">
            <input type="text" name="class_name" class="form-control me-2" placeholder="Nom de la nouvelle classe" required>
            <button type="submit" name="add_class" class="btn btn-primary">Ajouter</button>
        </form>
    </div>

    <!-- Modal pour afficher la liste des élèves -->
    <div class="modal fade" id="studentModal" tabindex="-1" aria-labelledby="studentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="studentModalLabel">Liste des élèves</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Contenu de la liste des élèves -->
                    <ul id="studentList" class="list-group">
                        <!-- Liste des élèves sera injectée ici via AJAX -->
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Quand on clique sur le nom d'une classe
            $('.class-link').click(function(e) {
                e.preventDefault();

                var classId = $(this).data('class-id');

                // Appel AJAX pour récupérer la liste des élèves
                $.ajax({
                    url: 'get_students.php', // Fichier PHP pour traiter la requête
                    type: 'POST',
                    data: { class_id: classId },
                    success: function(response) {
                        // Injecter la liste des élèves dans le modal
                        $('#studentList').html(response);
                        // Afficher le modal
                        $('#studentModal').modal('show');
                    },
                    error: function() {
                        alert('Erreur lors de la récupération des élèves.');
                    }
                });
            });
        });
    </script>
</body>
</html>
