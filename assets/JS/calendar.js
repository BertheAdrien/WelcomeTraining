document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek', // Vue initiale
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        locales: ['fr'],
        locale: 'fr',
        events: calendarEvents,
        selectable: true,
        hiddenDays: [0], // Masquer le dimanche
        firstDay: 1, // Commencer la semaine par lundi
        slotLabelFormat: { // Format des heures au niveau global
            hour: '2-digit',
            minute: '2-digit',
            hour12: false // Désactiver le format AM/PM
        },
    });

    calendar.render();

    // Écouteur d'événement pour changer la classe
    document.getElementById('classSelect').addEventListener('change', function() {
        const selectedClass = this.value;
        let url = `getCoursesJSON.php?classID=${selectedClass}`;

        fetch(url)
            .then(response => response.json())
            .then(events => {
                calendar.removeAllEvents(); // Supprimer tous les événements existants
                calendar.addEventSource(events); // Ajouter les nouveaux événements
            })
            .catch(error => console.error('Erreur lors du chargement des cours:', error));
    });
});
