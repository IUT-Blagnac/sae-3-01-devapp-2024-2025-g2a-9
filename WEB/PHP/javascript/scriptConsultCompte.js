/************
CONSULTCOMPTE.PHP
**************/
document.addEventListener('DOMContentLoaded', function () {
    // Récupérer les paramètres URL
    const urlParams = new URLSearchParams(window.location.search);
    const tab = urlParams.get('tab');

    // Activer l'onglet correspondant
    if (tab) {
        const activeTab = document.querySelector(`[data-bs-target="#${tab}Pane"]`);
        const activePane = document.getElementById(`${tab}Pane`);

        if (activeTab && activePane) {
            // Désactiver l'onglet actif par défaut
            document.querySelector('.nav-link.active').classList.remove('active');
            document.querySelector('.tab-pane.active').classList.remove('show', 'active');

            // Activer l'onglet spécifié
            activeTab.classList.add('active');
            activePane.classList.add('show', 'active');
        }
    }

    // Validation en temps réel
    const form = document.querySelector('#modifierCompte');
    const inputs = form.querySelectorAll('input');

    // Fonction pour vérifier un champ
    function validateField(field) {
        let isValid = true;
        const value = field.value.trim();

        // Validation spécifique pour chaque champ
        switch (field.name) {
            case 'nom':
            case 'prenom':
                isValid = /^[a-zA-ZÀ-ÿ\s]+$/.test(value); // Lettres uniquement
                break;
            case 'numRue':
                isValid = /^\d{0}$|^\d{1,3}$/.test(value); // 1 à 3 chiffres uniquement ou 0
                break;
            case 'codePostal':
                isValid = /^\d{0}$|^\d{5}$/.test(value); // 5 chiffres ou 0
                break;
            case 'email':
                isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value); // Email valide
                break;
            case 'telephone':
                isValid = /^\d{0}$|^\d{10}$/.test(value);
                break;
                case 'ville':
            case 'libelleVoie':
            case 'pays':
                isValid = /^[a-zA-ZÀ-ÿ\s]*$/.test(value); // Lettres uniquement ou vide
                break;
            default:
                break;
        }

        // Affichage des erreurs
        if (!isValid) {
            field.classList.add('is-invalid');
        } else {
            field.classList.remove('is-invalid');
        }

        return isValid;
    }

    // Vérification sur chaque champ
    inputs.forEach(input => {
        input.addEventListener('input', () => validateField(input));
    });

    // Validation globale avant envoi du formulaire
    form.addEventListener('submit', function (event) {
        let isFormValid = true;

        inputs.forEach(input => {
            if (!validateField(input)) {
                isFormValid = false;
            }
        });

        if (!isFormValid) {
            event.preventDefault(); // Empêche l'envoi si le formulaire est invalide
            alert('Veuillez corriger les champs invalides avant de soumettre le formulaire.');
        }
    });
});