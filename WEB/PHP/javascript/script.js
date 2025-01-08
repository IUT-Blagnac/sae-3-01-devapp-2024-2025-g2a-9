/************
COMMANDER.PHP
**************/
// Sélectionne les éléments nécessaires
const livraisonRelais = document.getElementById('livraisonRelais');
const livraisonDomicile = document.getElementById('livraisonDomicile');
const pointRelaisRadios = document.querySelectorAll('input[name="pointRelais"]');
const livraisonPrix = document.getElementById('livraisonPrix');
const totalPrix = document.getElementById('totalPrix');
const adresseNonRenseignee = document.querySelector('.adresse-non-renseignee'); // élément qui affiche "Adresse non renseignée"

// Fonction pour mettre à jour les états
function updateState() {
    if (livraisonRelais.checked) {
        pointRelaisRadios.forEach(radio => {
            radio.disabled = false;
        });
        livraisonPrix.textContent = prixLivraisonRelais.toFixed(2) + " €";
        totalPrix.textContent = (totalPanier + prixLivraisonRelais).toFixed(2) + " €";
    } else {
        pointRelaisRadios.forEach(radio => {
            radio.disabled = true;
            radio.checked = false;
        });
        livraisonPrix.textContent = prixLivraisonDomicile.toFixed(2) + " €";
        totalPrix.textContent = (totalPanier + prixLivraisonDomicile).toFixed(2) + " €";
    }
}

// Gestion des événements pour la livraison
livraisonRelais.addEventListener('change', updateState);
livraisonDomicile.addEventListener('change', updateState);
updateState(); // Initialisation

// Gestion des paiements
const paiementCB = document.getElementById('paiementCB');
const paiementPaypal = document.getElementById('paiementPaypal');
const formCarteBancaire = document.getElementById('formCarteBancaire');
const formPaypal = document.getElementById('formPaypal');
// Champs des formulaires
const numCarte = document.getElementById('numCarte');
const dateExpiration = document.getElementById('dateExpiration');
const cryptogramme = document.getElementById('cryptogramme');
const emailPaypal = document.getElementById('emailPaypal');
// Gestion de l'affichage dynamique
function togglePaymentForms() {
    if (paiementCB.checked) {
        formCarteBancaire.closest('.card-body').classList.remove('d-none'); // Affiche tout le bloc
        formPaypal.closest('.card-body').classList.add('d-none'); // Masque tout le bloc PayPal
    } else if (paiementPaypal.checked) {
        formCarteBancaire.closest('.card-body').classList.add('d-none'); // Masque tout le bloc carte bancaire
        formPaypal.closest('.card-body').classList.remove('d-none'); // Affiche tout le bloc PayPal
    }
}
paiementCB.addEventListener('change', togglePaymentForms);
paiementPaypal.addEventListener('change', togglePaymentForms);

// Validation des champs
function validerFormulaire() {
    let valide = true;

    // Réinitialiser les erreurs
    [numCarte, dateExpiration, cryptogramme, emailPaypal].forEach(input => input.classList.remove('is-invalid'));

    // Vérifie que l'adresse est renseignée si la livraison à domicile est sélectionnée
    if (livraisonDomicile.checked && adresseNonRenseignee) {
        alert('Veuillez renseigner votre adresse de livraison à domicile.');
        valide = false;
    }

    // Validation des informations de paiement
    if (paiementCB.checked) {
        // Vérifie le numéro de carte (16 chiffres)
        if (!/^\d{16}$/.test(numCarte.value)) {
            numCarte.classList.add('is-invalid');
            valide = false;
        }
    
        // Vérifie le format de la date (MM/YY)
        if (!/^\d{2}\/\d{2}$/.test(dateExpiration.value)) {
            dateExpiration.classList.add('is-invalid');
            valide = false;
        } else {
            // Vérifie si la date n'est pas expirée
            const dateParts = dateExpiration.value.split('/');
            const month = parseInt(dateParts[0], 10); // Mois
            const year = parseInt(dateParts[1], 10) + 2000; // Année (convertit YY en YYYY)
    
            const currentDate = new Date(); // Date actuelle
            const currentMonth = currentDate.getMonth() + 1; // Mois actuel (de 0 à 11, donc +1)
            const currentYear = currentDate.getFullYear(); // Année actuelle
    
            if (
                month < 1 || month > 12 || // Mois invalide
                year < currentYear || // Année expirée
                (year === currentYear && month < currentMonth) // Mois expiré pour l'année actuelle
            ) {
                dateExpiration.classList.add('is-invalid'); // Ajoute la classe d'erreur
                document.getElementById('dateExpirationError').style.display = 'block';
                valide = false; // Date invalide
            } else {
                dateExpiration.classList.remove('is-invalid'); // Supprime la classe si valide
                document.getElementById('dateExpirationError').style.display = 'none';
            }
        }
    
        // Vérifie le cryptogramme (3 chiffres)
        if (!/^\d{3}$/.test(cryptogramme.value)) {
            cryptogramme.classList.add('is-invalid');
            valide = false;
        }
    } else if (paiementPaypal.checked) {
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailPaypal.value)) {
            emailPaypal.classList.add('is-invalid');
            valide = false;
        }
    }

    return valide;
}

// Validation au clic sur "Passer votre commande"
document.querySelector('button.btn-primary').addEventListener('click', function (e) {
    if (!validerFormulaire()) {
        e.preventDefault();
        alert('Veuillez corriger les erreurs dans le formulaire de paiement.');
    }

    // Vérifie qu'un point relais est sélectionné si l'option est activée
    if (livraisonRelais.checked && ![...pointRelaisRadios].some(r => r.checked)) {
        e.preventDefault();
        alert('Veuillez sélectionner un point relais.');
    }
});

// Initialisation de l'affichage des formulaires de paiement
togglePaymentForms();