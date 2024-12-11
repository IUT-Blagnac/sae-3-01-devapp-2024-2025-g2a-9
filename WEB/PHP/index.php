<?php
$pageTitle = "Page d'accueil";
require_once "./include/head.php";
?>
<body>
    <?php
    require_once "./include/header.php";
    require_once "./include/menu.php";
    ?>
    <!-- Contenu principal -->

    <center>
        <img src="image/m3-banner.jpeg" class="d-block w-100">
        <br>
        <br>
        <h2>OFFRE DU MOMENT</h2>
        <p>Découvrez et profitez de nos meilleures offres sur l’ensemble de la gamme de Nautic Horizon</p>
        <div id="carouselExampleDark" class="carousel carousel-dark slide bg-secondary" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner w-100 ">
                <div class="carousel-item active" data-bs-interval="10000">
                    <img src="image/produit/test20.jpg" class="d-block w-100">
                    <div class="carousel-caption d-none d-md-block text-white">
                        <h5>First slide label</h5>
                        <p>Some representative placeholder content for the first slide.</p>
                    </div>
                </div>
                <div class="carousel-item" data-bs-interval="2000">
                    <img src="image/produit/test21.jpg" class="d-block w-100">
                    <div class="carousel-caption d-none d-md-block text-white">
                        <h5>Second slide label</h5>
                        <p>Some representative placeholder content for the second slide.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="image/produit/test22.jpg" class="d-block w-100">
                    <div class="carousel-caption d-none d-md-block text-white">
                        <h5>Third slide label</h5>
                        <p>Some representative placeholder content for the third slide.</p>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </center>
    <!-- Pied de page -->
    <?php require_once "./include/footer.php"; ?>
</body>
</html>