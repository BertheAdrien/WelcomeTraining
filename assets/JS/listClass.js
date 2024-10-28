$(document).ready(function() {
    // Quand on clique sur le nom d'une classe
    $('.class-link').click(function(e) {
        e.preventDefault();
        
        // Récupérer l'ID de la classe à partir de l'attribut data
        var classId = $(this).data('class-id');

        // Appel AJAX pour récupérer la liste des élèves
        $.ajax({
            url: 'get_students.php', // Le fichier PHP qui va traiter la requête
            type: 'POST',            // La méthode de la requête
            data: { class_id: classId }, // Données envoyées via POST
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


