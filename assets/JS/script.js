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

// Efface le contenu du canvas
document.getElementById('clearButton').addEventListener('click', () => {
    clearCanvas();
});

// Fonction pour récupérer la signature du canvas et l'envoyer
// Fonction pour récupérer la signature du canvas et l'envoyer
// Quand l'utilisateur appuie sur le bouton "Envoyer"
document.getElementById("saveButton").addEventListener("click", function(e) {
    e.preventDefault(); // Empêche la soumission du formulaire immédiatement

    // Récupérer les données du canvas en base64
    var signatureData = document.getElementById("signatureCanvas").toDataURL("image/png");

    // Vérifier si la signature est vide
    if (signatureData === "") {
        alert("Veuillez dessiner une signature.");
        return;
    }

    // Remplir le champ caché avec la signature
    document.getElementById("signatureData").value = signatureData;

    // Afficher la modale de succès
    var successModal = new bootstrap.Modal(document.getElementById('successModal'));
    successModal.show();  // Afficher la modale

    // Lorsqu'on ferme la modale, rediriger ou soumettre le formulaire
    document.getElementById('closeModal').addEventListener('click', function() {
        // Après la fermeture de la modale, soumettre le formulaire
        document.getElementById("signatureForm").submit();
    });
});





