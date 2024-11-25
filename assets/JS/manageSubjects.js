$(document).ready(function () {
    // Ouvrir le modal lors du clic sur un lien ou un bouton
    $('.subject-link').on('click', function (e) {
        e.preventDefault(); // Évite tout comportement par défaut
        const subjectId = $(this).data('subject-id');
        $('#modalSubjectId').val(subjectId); // Remplit le champ caché avec l'ID de la matière
        $('#assignmentModal').modal('show'); // Affiche le modal
    });
});

