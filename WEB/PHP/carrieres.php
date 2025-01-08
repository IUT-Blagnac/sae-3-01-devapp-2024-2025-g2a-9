<?php
$pageTitle = "Recrutement";
require_once "./include/head.php";
?>
<body>
<?php
    require_once "./include/header.php";
    require_once "./include/menu.php";
    require_once './include/connect.inc.php';
?>

<!-- Contenu principal -->
<main role="main">
    <section style="background-color: #ECF0FB;">
        <section class="container mb-5" style="position: relative; width: 100%; max-width: 1500px; margin: 0 auto; padding-top: 30px; padding-bottom: 30px;">
            <img src="image/careers.jpg" alt="Image Nautic Horizon" style="width: 100%; height: 600px; display: block; border-radius: 10px;">
            <div style="
                position: absolute; 
                top: 150px; 
                left: 40px; 
                color:rgb(0, 52, 107); 
                padding: 20px; 
                width: calc(100% - 40px); 
                max-width: 600px; 
                border-radius: 10px; 
                font-family: 'Arial', sans-serif;
            ">
                <h2 style="font-size: 1.8rem; margin-bottom: 10px; font-weight: bold;">LE RECRUTEMENT DANS LE GROUPE.</h2>
                <p style="font-size: 1.3rem; line-height: 1.6; margin: 0;">
                    Comment rejoindre le Groupe Nautic Horizon ?
                </p>
            </div>
        </section>
    </section>

    <section>
        <section class="container" style="max-width: 1200px; margin: 30px auto; font-family: 'Arial', sans-serif;">
            <h2 style="font-size: 2rem; margin-bottom: 20px; color: rgb(0, 52, 107);">Offres d'emploi</h2>
            <div style="display: flex; flex-direction: column; gap: 20px;">

                <?php
                // Exemple de données pour les offres d'emploi
                $jobs = [
                    [
                        "title" => "Ingénieur Naval",
                        "location" => "La Rochelle, France",
                        "start" => "01 Mars 2025",
                        "description" => "Conception et développement de nouvelles générations de bateaux, en intégrant les dernières innovations technologiques.",
                    ],
                    [
                        "title" => "Responsable Commercial",
                        "location" => "Marseille, France",
                        "start" => "15 Février 2025",
                        "description" => "Gestion des relations clients, négociation des ventes et développement des partenariats commerciaux dans le secteur maritime.",
                    ],
                    [
                        "title" => "Technicien de Maintenance Nautique",
                        "location" => "Bordeaux, France",
                        "start" => "10 Avril 2025",
                        "description" => "Entretien, réparation et vérification technique des bateaux avant et après livraison.",
                    ],
                    [
                        "title" => "Designer Naval",
                        "location" => "Vannes, France",
                        "start" => "05 Mai 2025",
                        "description" => "Création de designs innovants et ergonomiques pour des bateaux de plaisance et professionnels.",
                    ],
                    [
                        "title" => "Chef de Production Nautique",
                        "location" => "Nice, France",
                        "start" => "20 Février 2025",
                        "description" => "Supervision de la production des bateaux, gestion des équipes de fabrication et coordination des étapes de production pour garantir la qualité et les délais.",
                    ],
                    [
                        "title" => "Responsable Marketing Nautique",
                        "location" => "Nantes, France",
                        "start" => "01 Avril 2025",
                        "description" => "Création de stratégies marketing pour promouvoir nos modèles de bateaux à travers différents canaux, gestion des campagnes publicitaires et des relations publiques.",
                    ],
                    [
                        "title" => "Vendeur / Conseiller Nautique",
                        "location" => "Cannes, France",
                        "start" => "01 Mars 2025",
                        "description" => "Conseiller les clients dans le choix de leur bateau en fonction de leurs besoins et préférences, tout en offrant un service personnalisé de haute qualité.",
                    ],
                    [
                        "title" => "Technicien de Production en Composites",
                        "location" => "Saint-Malo, France",
                        "start" => "15 Mars 2025",
                        "description" => "Fabrication et assemblage de pièces en composites pour la construction de coques de bateaux. Travail avec des matériaux de haute technologie pour la performance des produits.",
                    ],
                ];
                

                // Affichage dynamique des offres d'emploi
                foreach ($jobs as $job): ?>
                    <div style="display: flex; align-items: flex-start; background-color: #ffffff; padding: 20px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                        <img src="image/logoNautic.png" alt="Logo de l'entreprise" style="width: 80px; height: 80px; border-radius: 50%; margin-right: 20px; object-fit: cover;">
                        <div style="flex: 2;">
                            <h3 style="margin: 0; font-size: 1.5rem; color: rgb(0, 52, 107);"><?php echo $job['title']; ?></h3>
                            <p style="margin: 5px 0; font-size: 1rem; color: rgb(100, 100, 100);">
                                <strong>Lieu :</strong> <?php echo $job['location']; ?><br>
                                <strong>Début :</strong> <?php echo $job['start']; ?>
                            </p>
                        </div>
                        <div style="flex: 3; padding-left: 20px; font-size: 1rem; color: rgb(80, 80, 80);">
                            <strong>Description :</strong> <?php echo $job['description']; ?>
                        </div>
                        <div style="margin-left: 20px;">
                            <a href="contact.php" style="
                                display: inline-block; 
                                background-color: rgb(0, 52, 107); 
                                color: #ffffff; 
                                padding: 10px 20px; 
                                border-radius: 5px; 
                                text-decoration: none; 
                                font-size: 1rem;
                            ">Demander plus de details</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </section>
    
</main>

<!-- Pied de page -->
<?php require_once "./include/footer.php"; ?>
</body>
</html>
