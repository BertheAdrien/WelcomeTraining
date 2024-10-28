const canvas = document.getElementById('signatureCanvas');
const ctx = canvas.getContext('2d');
let drawing = false;

// Résolution souhaitée (pour un affichage Retina)
const scale = window.devicePixelRatio || 1;

// Taille physique du canvas (ce que l'utilisateur voit)
const displayedWidth = canvas.offsetWidth;
const displayedHeight = canvas.offsetHeight;

// Ajustement de la résolution virtuelle (canvas.width et canvas.height)
canvas.width = displayedWidth * scale;
canvas.height = displayedHeight * scale;

// Ajustement de la taille visible (style.width et style.height) pour correspondre à l'interface utilisateur
canvas.style.width = `${displayedWidth}px`;
canvas.style.height = `${displayedHeight}px`;

// Adapter le contexte au nouveau ratio de pixels
ctx.scale(scale, scale);

// Paramètres personnalisés pour un trait lisse
ctx.strokeStyle = 'black';  // Couleur noire
ctx.lineWidth = 2;  // Épaisseur de 2px
ctx.lineCap = 'round';  // Extrémités arrondies
ctx.lineJoin = 'round';  // Jointures arrondies

function fillCanvasBackground() {
    ctx.fillStyle = 'white';  // Couleur de fond
    ctx.fillRect(0, 0, canvas.width / scale, canvas.height / scale);  // Remplir le rectangle
}

// Appeler cette fonction lors de l'initialisation
fillCanvasBackground();  // Remplir le fond du canvas au début

// Effacer le canvas avec un fond blanc
function clearCanvas() {
    fillCanvasBackground();  // Remplir avec un fond blanc à chaque effacement
    ctx.clearRect(0, 0, canvas.width / scale, canvas.height / scale);  // Effacer avec la bonne échelle
}

// Effacer le canvas
function clearCanvas() {
    ctx.clearRect(0, 0, canvas.width / scale, canvas.height / scale);  // Effacer avec la bonne échelle
}

// Commence à dessiner
canvas.addEventListener('mousedown', (e) => {
    drawing = true;
    ctx.beginPath();
    ctx.moveTo((e.offsetX || (e.clientX - canvas.getBoundingClientRect().left)), 
                (e.offsetY || (e.clientY - canvas.getBoundingClientRect().top)));
});

// Dessine pendant que la souris se déplace
canvas.addEventListener('mousemove', (e) => {
    if (drawing) {
        ctx.lineTo((e.offsetX || (e.clientX - canvas.getBoundingClientRect().left)), 
                    (e.offsetY || (e.clientY - canvas.getBoundingClientRect().top)));
        ctx.stroke(); // le trait
    }
});

// Arrête de dessiner quand on relâche la souris
canvas.addEventListener('mouseup', () => {
    drawing = false;
    ctx.closePath();
});

document.getElementById('returnButton').addEventListener('click', () => {
    window.location.href = 'dashboard.php'; // Redirige vers dashboard.php
});

// Efface le contenu du canvas
document.getElementById('clearButton').addEventListener('click', () => {
    clearCanvas();
});



// Fonction pour sauvegarder la signature
document.getElementById('saveButton').addEventListener('click', () => {
    // Convertir le canvas en image JPG
    const imageData = canvas.toDataURL('image/jpeg');

    // Préparer les données à envoyer
    const signatureData = {
        imageData: imageData
    };

    // Envoyer les données à un script PHP
    fetch('saveSignature.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(signatureData),
    })
    .then(response => {
        // Vérifie si la réponse est au format JSON
        return response.text(); // Utilise text() au lieu de json() pour voir la réponse brute
    })
    .then(data => {console
        try {
            const jsonData = JSON.parse(data); // Essaye de parser la réponse
            
            // Vérifie si la réponse contient une confirmation de succès
            if (jsonData.status === 'success') {
                // Afficher le modal de succès
                const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                successModal.show();

                // Redirection après la fermeture du modal
                document.getElementById('successModal').addEventListener('hidden.bs.modal', function () {
                    window.location.href = 'dashboard.php'; // Redirige vers dashboard.php
                });
            } else {
                alert('Erreur : ' + jsonData.message); // Affiche un message d'erreur
            }
        } catch (e) {
            alert('Erreur lors de la soumission de la signature.'); // Pop-up d'erreur
        }
    })
    .catch((error) => {
        alert('Erreur de connexion. Veuillez réessayer.'); // Pop-up d'erreur
    });
});

