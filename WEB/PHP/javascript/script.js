// Le bouton qui ouvre/ferme le menu
const menuBtn = document.getElementById('menu-btn');
const sidebar = document.querySelector('.sidebar');

// Ajout d'un événement sur le bouton pour ouvrir/fermer le menu
menuBtn.addEventListener('click', function() {
    sidebar.classList.toggle('active'); // Active ou désactive le menu en ajoutant/enlevant la classe 'active'
});
