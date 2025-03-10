<?php 
$title = 'Gestion des classes';
include_once '../partials/header.php';
include_once '../include/Config.php';
include_once '../include/pdo.php';
include_once '../classes/ClassManager.php';

// Instancier le gestionnaire de classes
$classManager = new ClassManager($pdo);

// Récupérer toutes les classes
$classes = $classManager->getAllClasses();
?>

<body class="bg-light">
    <div class="container py-4">
        <h1 class="text-center mb-4">Gestion des classes</h1>

        <!-- Formulaire pour ajouter une classe -->
        <!-- <h2 class="mt-4">Ajouter une nouvelle classe</h2>
        <form method="POST" action="../actions/class_actions.php" class="d-flex mb-4">
            <input type="text" name="class_name" class="form-control me-2" placeholder="Nom de la nouvelle classe" required>
            <button type="submit" name="add_class" class="btn btn-primary">Ajouter</button>
        </form> -->

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
                        <td>
                            <a href="#" class="class-link" data-class-id="<?php echo $class['idClasse']; ?>">
                                <?php echo htmlspecialchars($class['ClassName']); ?>
                            </a>
                        </td>
                        <td>
                            <!-- <form method="POST" action="../actions/class_actions.php" style="display:inline-block;">
                                <input type="hidden" name="class_id" value="<?php echo $class['idClasse']; ?>">
                                <button type="submit" name="delete_class" class="btn btn-danger">✖</button>
                            </form> -->
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
        <!-- Modal pour afficher les élèves -->
    <div class="modal fade" id="studentModal" tabindex="-1" aria-labelledby="studentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="studentModalLabel">Liste des élèves</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div id="studentList">
            <!-- Les élèves seront chargés ici par AJAX -->
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
        </div>
        </div>
    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../assets/JS/listClass.js"></script>
</body>
</html>
