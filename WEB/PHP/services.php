<?php
$pageTitle = "Services Nautic Horizon";
require_once "./include/head.php";
?>
<body>
<?php
    require_once "./include/header.php";
    require_once "./include/menu.php";
?>

<main role="main">
    <section style="background-color: #ECF0FB;">
        <section class="container mb-5" style="position: relative; width: 100%; max-width: 1500px; margin: 0 auto; padding: 30px;">
            <h2 style="font-size: 2.5rem; font-weight: bold; color: #0056b3; margin-bottom: 2rem;">Nos Services</h2>
            <p style="font-size: 1.2rem; line-height: 1.8;">
                Découvrez les services offerts par Nautic Horizon pour répondre à tous vos besoins en navigation :
            </p>
            <ul style="list-style: none; padding: 0; margin: 2rem 0; font-size: 1.2rem; line-height: 1.8;">
                <li style="margin-bottom: 1rem;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <img src="image/services/reparation.jpg" alt="Reparation" style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px;">
                        <strong>Maintenance et Réparation :</strong> Entretien complet de vos bateaux ou jet skis pour naviguer en toute sécurité.
                    </div>
                </li>
                <li style="margin-bottom: 1rem;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <img src="image/services/location.jpg" alt="Location" style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px;">
                        <strong>Location et Mise à Disposition :</strong> Choisissez parmi un large choix de voiliers, yachts et autres embarcations pour vos sorties.
                    </div>
                </li>
                <li style="margin-bottom: 1rem;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <img src="image/services/formation.jpg" alt="Formation" style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px;">
                        <strong>Formation Nautique :</strong> Cours de navigation pour débutants et pilotes expérimentés, incluant préparation aux permis.
                    </div>
                </li>
                <li style="margin-bottom: 1rem;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <img src="image/services/personnalisation.jpg" alt="Personnalisation" style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px;">
                        <strong>Conception et Personnalisation :</strong> Accompagnement sur mesure pour la construction ou la customisation de bateaux.
                    </div>
                </li>
                <li style="margin-bottom: 1rem;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <img src="image/services/transport.jpg" alt="Transport" style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px;">
                        <strong>Transport et Logistique :</strong> Services d’expédition et de convoyage pour vos bateaux, du petit navire au porte-conteneurs.
                    </div>
                </li>
            </ul>
        </section>
    </section>
    <section style="background-color: #f8f9fa;">
        <section class="container mb-5" style="position: relative; width: 100%; max-width: 1500px; margin: 0 auto; padding: 30px;">
            <h3 style="font-size: 2rem; color: #0056b3; margin-bottom: 2rem;">Détails de nos Services</h3>
            <ul style="list-style: none; padding: 0; margin: 2rem 0; font-size: 1.2rem; line-height: 1.8;">
                <li style="margin-bottom: 1rem;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <img src="image/services/reparation.jpg" alt="Maintenance" style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px;">
                        <strong>Maintenance et Réparation :</strong>
                        Nous assurons un diagnostic technique complet et utilisons des pièces de rechange garanties.
                    </div>
                </li>
                <li style="margin-bottom: 1rem;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <img src="image/services/location.jpg" alt="Location" style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px;">
                        <strong>Location et Mise à Disposition :</strong>
                        Bénéficiez d’offres spéciales pour les périodes de haute saison et location longue durée.
                    </div>
                </li>
                <li style="margin-bottom: 1rem;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <img src="image/services/formation.jpg" alt="Formation" style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px;">
                        <strong>Formation Nautique :</strong>
                        Accédez à des supports pédagogiques exclusifs et des tuteurs expérimentés.
                    </div>
                </li>
                <li style="margin-bottom: 1rem;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <img src="image/services/personnalisation.jpg" alt="Conception" style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px;">
                        <strong>Conception et Personnalisation :</strong>
                        Collaborez avec nos ingénieurs pour créer un bateau unique, adapté à votre pratique.
                    </div>
                </li>
                <li style="margin-bottom: 1rem;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <img src="image/services/transport.jpg" alt="Transport" style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px;">
                        <strong>Transport et Logistique :</strong>
                        Simplifiez vos démarches grâce à nos partenaires en assurance et transit international.
                    </div>
                </li>
            </ul>
            <div style="text-align: center; margin-top: 2rem;">
                <a href="contact.php" style="background-color: #0056b3; color: #fff; padding: 1rem 2rem; font-size: 1.2rem; text-decoration: none; border-radius: 5px;">
                    Demander un devis
                </a>
            </div>
        </section>
    </section>
</main>

<?php
    require_once "./include/footer.php";
?>
</body>
</html>
