document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        locales: ['fr'],
        locale: 'fr',
        events: calendarEvents, // Chargement initial des événements
        selectable: true,
        hiddenDays: [0], // Masquer le dimanche
        firstDay: 1, // Commencer la semaine par lundi
        slotLabelFormat: {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false // Désactiver le format AM/PM
        },
    });

    // Initialisation du calendrier avec les événements
    calendar.render();
    
    // Écouteur d'événement pour le changement de classe
    document.getElementById('classSelect').addEventListener('change', function() {
        const selectedClass = this.value;
        
        // Si aucune classe n'est sélectionnée, remettre les événements par défaut
        if (!selectedClass) {
            calendar.removeAllEvents();          // Supprime tous les événements existants
            calendar.addEventSource(calendarEvents); // Recharge les événements initiaux
            return;
        }

        // Sinon, charger les événements pour la classe sélectionnée
        const url = `../actions/getCoursesJSON.php?classID=${selectedClass}`;
        
        fetch(url)
            .then(response => response.json())
            .then(events => {
                calendar.removeAllEvents();  // Supprime les événements actuels
                calendar.addEventSource(events); // Ajoute les nouveaux événements
            })
            .catch(error => console.error('Erreur lors du chargement des cours:', error));
    });
});
