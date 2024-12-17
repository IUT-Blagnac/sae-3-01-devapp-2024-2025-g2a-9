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
                        <h5>Yacht</h5>
                        <a class="btn btn-primary" href="detailProduit.php?id=1" role="button">Voir plus</a>
                    </div>
                </div>
                <div class="carousel-item" data-bs-interval="2000">
                    <img src="image/produit/test21.jpg" class="d-block w-100">
                    <div class="carousel-caption d-none d-md-block text-white">
                        <h5>Yacht</h5>
                        <a class="btn btn-primary" href="detailProduit.php?id=2" role="button">Voir plus</a>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="image/produit/test22.jpg" class="d-block w-100">
                    <div class="carousel-caption d-none d-md-block text-white">
                        <h5>Jetski</h5>
                        <a class="btn btn-primary" href="detailProduit.php?id=3" role="button">Voir plus</a>
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
        <br>
        <br>
        <h2>Découvrez aussi...</h2>
        <p>Notre gamme professionnel</p>
        <br>
        <br>
        <div id="pro" class="carousel carousel-dark slide bg-secondary" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#pro" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#pro" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#pro" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner w-100 ">
                <div class="carousel-item active" data-bs-interval="10000">
                    <img src="image/produit/test30.jpg" class="d-block w-100">
                    <div class="carousel-caption d-none d-md-block text-white">
                        <h5>Tanker</h5>
                        <a class="btn btn-primary" href="detailProduit.php?id=5" role="button">Voir plus</a>
                    </div>
                </div>
                <div class="carousel-item" data-bs-interval="2000">
                    <img src="image/produit/test31.png" class="d-block w-100">
                    <div class="carousel-caption d-none d-md-block text-white">
                        <h5>Porte Conteneur</h5>
                        <a class="btn btn-primary" href="detailProduit.php?id=6" role="button">Voir plus</a>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="image/produit/test32.jpg" class="d-block w-100">
                    <div class="carousel-caption d-none d-md-block text-white">
                        <h5>Ferie</h5>
                        <a class="btn btn-primary" href="detailProduit.php?id=7" role="button">Voir plus</a>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#pro" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#pro" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
        <br>
        <br>
        <p>Notre gamme ecologique</p>
        <br>
        <br>
        <div id="eco" class="carousel carousel-dark slide bg-secondary" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#eco" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#eco" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#eco" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner w-100 ">
                <div class="carousel-item active" data-bs-interval="10000">
                    <img src="image/produit/test40.jpg" class="d-block w-100">
                    <div class="carousel-caption d-none d-md-block text-white">
                        <h5>Voilier</h5>
                        <a class="btn btn-primary" href="detailProduit.php?id=9" role="button">Voir plus</a>
                    </div>
                </div>
                <div class="carousel-item" data-bs-interval="2000">
                    <img src="image/produit/test41.jpg" class="d-block w-100">
                    <div class="carousel-caption d-none d-md-block text-white">
                        <h5>Catamaran</h5>
                        <a class="btn btn-primary" href="detailProduit.php?id=10" role="button">Voir plus</a>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="image/produit/test42.jpg" class="d-block w-100">
                    <div class="carousel-caption d-none d-md-block text-white">
                        <h5>Yacht electrique</h5>
                        <a class="btn btn-primary" href="detailProduit.php?id=11" role="button">Voir plus</a>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#eco" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#eco" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </center>
    <!-- Pied de page -->
    <?php require_once "./include/footer.php"; ?>
</body>
</html>