// Récupérer les éléments nécessaires
const menuBtn = document.getElementById('menu-btn');
const sidebar = document.querySelector('.sidebar');
const sidebar2 = document.querySelector('.sidebar2');
const overlay = document.querySelector('.overlay');
const btnsMenu = document.querySelectorAll('.btns-menu'); // Sélectionner tous les liens du menu

// Fonction pour ouvrir/fermer le menu principal
menuBtn.addEventListener('click', function () {
    sidebar.classList.toggle('active'); // Toggle pour la première sidebar
    if (sidebar2.classList.contains('active')) {
        sidebar2.classList.remove('active'); // Ferme la deuxième sidebar si elle est ouverte
    }
    overlay.classList.toggle('active'); // Active ou désactive l'overlay
});

// Fermer le menu et l'overlay en cliquant sur l'overlay
overlay.addEventListener('click', function () {
    sidebar.classList.remove('active'); // Ferme la première sidebar
    sidebar2.classList.remove('active'); // Ferme la deuxième sidebar
    overlay.classList.remove('active'); // Ferme l'overlay
});

// Afficher la deuxième sidebar lorsque l'une des options de la première sidebar est cliquée
btnsMenu.forEach(btn => {
    btn.addEventListener('click', function () {
        // Positionner la deuxième sidebar par rapport au bouton cliqué
        const buttonRect = btn.getBoundingClientRect(); // Récupérer la position du bouton dans la première sidebar
        sidebar2.style.top = `${buttonRect.top + window.scrollY}px`; // Ajuster la position de la sidebar
        
        // Ouvrir la deuxième sidebar
        sidebar2.classList.add('active');
    });
});

