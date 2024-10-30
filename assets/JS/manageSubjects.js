$(document).ready(function() {
    $('.subject-link').on('click', function() {
        const subjectId = $(this).data('subject-id');
        $('#modalSubjectId').val(subjectId);
        $('#assignmentModal').modal('show');
    });
});
