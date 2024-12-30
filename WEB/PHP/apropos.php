<?php
$pageTitle = "À propos de Nautic Horizon";
require_once "./include/head.php";
?>
<body>
<?php
    require_once "./include/header.php";
    require_once "./include/menu.php";
    require_once './include/connect.inc.php';
?>

<!-- Contenu principal -->
<main role="main" class="container my-5">
    <section class="mb-5">
        <h2>À propos de Nautic Horizon</h2>
        <p>
            Fondée sur l'héritage du célèbre architecte naval William Leboeuf, Nautic Horizon combine tradition et innovation pour offrir des solutions nautiques adaptées à tous.
            Basée à Singapour, l'entreprise est un leader dans la vente de bateaux et d’équipements maritimes, avec une présence forte en France, en Suisse et au Luxembourg.
        </p>
    </section>

    <section class="mb-5">
        <h2>Pourquoi choisir Nautic Horizon ?</h2>
        <ul>
            <li>Expertise historique : Un héritage maritime depuis le XVIIIe siècle.</li>
            <li>Catalogue diversifié : Des yachts de luxe aux équipements maritimes spécialisés.</li>
            <li>Présence internationale : Des magasins stratégiquement situés et un siège social à Singapour.</li>
            <li>Engagement envers l’innovation : Intégration des dernières technologies dans chaque produit.</li>
        </ul>
    </section>

    <section class="mb-5">
        <h2>Ce que disent nos clients</h2>
        <blockquote class="blockquote">
            <p>"Grâce à Nautic Horizon, j'ai pu réaliser mon rêve d'acquérir un yacht de luxe. Le service client est impeccable."</p>
            <footer class="blockquote-footer">Robert, amateur de nautisme</footer>
        </blockquote>
        <blockquote class="blockquote">
            <p>"Une expérience fluide et des produits de grande qualité, parfaits pour mes besoins professionnels."</p>
            <footer class="blockquote-footer">Maria, chef d'entreprise</footer>
        </blockquote>
    </section>

    <section class="mb-5">
        <h2>Notre Histoire</h2>
        <p>
            L’entreprise tire ses racines de William Leboeuf, pionnier de l’architecture navale. À travers les siècles, ses descendants ont perpétué cet héritage,
            créant Nautic Horizon pour offrir des solutions modernes tout en respectant des traditions maritimes fortes.
        </p>
    </section>

    <section class="mb-5">
        <h2>Notre Équipe</h2>
        <ul class="team-list">
            <li>Nolhan Biblocque - Directeur Général</li>
            <li>Mathys Laguilliez - Co-Directeur</li>
            <li>Victor Jockin - Responsable Design</li>
            <li>Mucahit Lekesiz - Responsable Technique</li>
            <li>Léo Guinvarc’h - Responsable Marketing</li>
        </ul>
    </section>

    <section class="mb-5">
        <h2>Nos chiffres clés</h2>
        <ul>
            <li>Chiffre d’affaires : 399 millions d'euros en 2023</li>
            <li>Capitalisation boursière : 942 millions d'euros</li>
            <li>Présence : 3 pays (France, Suisse, Luxembourg)</li>
        </ul>
    </section>
</main>

<!-- Pied de page -->
<?php require_once "./include/footer.php"; ?>
</body>
</html>
