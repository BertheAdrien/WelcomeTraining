// script.js
const canvas = document.getElementById('signatureCanvas');
const ctx = canvas.getContext('2d');
let drawing = false;

// Configuration du canvas
const scale = window.devicePixelRatio || 1;
const displayedWidth = canvas.offsetWidth;
const displayedHeight = canvas.offsetHeight;

canvas.width = displayedWidth * scale;
canvas.height = displayedHeight * scale;
canvas.style.width = `${displayedWidth}px`;
canvas.style.height = `${displayedHeight}px`;
ctx.scale(scale, scale);

// Paramètres du trait
ctx.strokeStyle = 'black';
ctx.lineWidth = 2;
ctx.lineCap = 'round';
ctx.lineJoin = 'round';

// Fond blanc
function fillCanvasBackground() {
    ctx.fillStyle = 'white';
    ctx.fillRect(0, 0, canvas.width / scale, canvas.height / scale);
}

fillCanvasBackground();

// Effacer le canvas
function clearCanvas() {
    fillCanvasBackground();
    ctx.clearRect(0, 0, canvas.width / scale, canvas.height / scale);
}

// Événements de dessin
canvas.addEventListener('mousedown', (e) => {
    drawing = true;
    ctx.beginPath();
    ctx.moveTo(
        (e.offsetX || (e.clientX - canvas.getBoundingClientRect().left)),
        (e.offsetY || (e.clientY - canvas.getBoundingClientRect().top))
    );
});

canvas.addEventListener('mousemove', (e) => {
    if (drawing) {
        ctx.lineTo(
            (e.offsetX || (e.clientX - canvas.getBoundingClientRect().left)),
            (e.offsetY || (e.clientY - canvas.getBoundingClientRect().top))
        );
        ctx.stroke();
    }
});

canvas.addEventListener('mouseup', () => {
    drawing = false;
    ctx.closePath();
});

// Bouton d'effacement
document.getElementById('clearButton').addEventListener('click', clearCanvas);

// Vérifier si le canvas est vide
function isCanvasBlank() {
    const context = canvas.getContext('2d');
    const pixelBuffer = new Uint32Array(
        context.getImageData(0, 0, canvas.width, canvas.height).data.buffer
    );
    return !pixelBuffer.some(color => color !== 0);
}

// Gestion de la soumission
document.getElementById("saveButton").addEventListener("click", async function(e) {
    e.preventDefault();

    if (isCanvasBlank()) {
        alert("Veuillez dessiner une signature.");
        return;
    }

    // Récupérer la signature
    const signatureData = canvas.toDataURL("image/png");
    document.getElementById("signatureData").value = signatureData;

    // Message de confirmation
    if (confirm("Voulez-vous enregistrer votre signature ?")) {
        try {
            // Soumission du formulaire
            const form = document.getElementById("signatureForm");
            if (form) {
                form.submit();
            }
        } catch (error) {
            console.error("Erreur lors de la soumission :", error);
            alert("Une erreur est survenue lors de l'enregistrement de la signature.");
        }
    }
});