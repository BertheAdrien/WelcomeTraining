document.addEventListener('DOMContentLoaded', function() {
    // Configuration du calendrier
    const calendarConfig = {
        initialView: 'timeGridWeek',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        locale: 'fr',
        events: calendarEvents,
        selectable: true,
        hiddenDays: [0], // Masquer le dimanche
        firstDay: 1,     // Commencer la semaine par lundi
        slotLabelFormat: {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        },
        // Options d'affichage supplémentaires
        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        },
        allDaySlot: false,
        height: 'auto',
        // Personnalisation des événements
        eventDidMount: function(info) {
            // Vous pouvez personnaliser l'apparence des événements ici
            // Par exemple, ajouter un tooltip avec des informations supplémentaires
            if (info.event.extendedProps.description) {
                info.el.title = info.event.extendedProps.description;
            }
        }
    };

    // Initialisation du calendrier
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, calendarConfig);
    calendar.render();

    // Gestion du sélecteur de classe (si présent)
    const classSelect = document.getElementById('classSelect');
    if (classSelect) {
        classSelect.addEventListener('change', function() {
            const selectedClass = this.value;
            
            if (!selectedClass) {
                // Afficher tous les cours
                calendar.removeAllEvents();
                calendar.addEventSource(calendarEvents);
                return;
            }

            // Charger les cours pour la classe sélectionnée
            loadClassCourses(selectedClass, calendar);
        });
    }
});

/**
 * Charge les cours pour une classe spécifique
 * @param {number} classId - L'ID de la classe
 * @param {FullCalendar.Calendar} calendar - L'instance du calendrier
 */
function loadClassCourses(classId, calendar) {
    const url = `../actions/getCoursesJSON.php?classID=${classId}`;
    
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Erreur HTTP: ${response.status}`);
            }
            return response.json();
        })
        .then(events => {
            calendar.removeAllEvents();
            calendar.addEventSource(events);
        })
        .catch(error => {
            console.error('Erreur lors du chargement des cours:', error);
            // Afficher un message d'erreur à l'utilisateur
            alert('Impossible de charger les cours. Veuillez réessayer plus tard.');
        });
}