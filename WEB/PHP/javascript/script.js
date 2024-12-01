// Récupérer les éléments nécessaires
const menuBtn = document.getElementById('menu-btn');
const sidebar = document.querySelector('.sidebar');
const overlay = document.querySelector('.overlay');

// Fonction pour ouvrir/fermer le menu
menuBtn.addEventListener('click', function () {
    sidebar.classList.toggle('active'); // Active ou désactive le menu
    overlay.classList.toggle('active'); // Active ou désactive l'overlay
});

// Fermer le menu et l'overlay en cliquant sur l'overlay
overlay.addEventListener('click', function () {
    sidebar.classList.remove('active'); // Ferme le menu
    overlay.classList.remove('active'); // Ferme l'overlay
});
