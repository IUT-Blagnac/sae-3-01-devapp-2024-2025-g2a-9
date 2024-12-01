<header class="mb-4 fixed-top bg-light">
    <div class="p-3 text-center">
        <div class="container-fluid">
            <div class="row">
                <!-- Left elements -->
                <div class="col-md-5 d-flex justify-content-center justify-content-md-start align-items-center d-none d-lg-flex">
                    <button id="menu-btn">
                        <img src="image/menu.png" alt="Menu" />
                    </button>
                    <?php include 'menu.php'; ?>
                    <div class="overlay"></div>
                    <form class="d-flex input-group w-50 mt-1 mb-3 mb-md-0">
                        <input type="search" class="search-bar form-control rounded" placeholder="Search" />
                    </form>
                </div>
                <!-- Center elements -->
                <div class="col-md-2 d-none d-lg-block">
                    <a href="#!" class="ms-md-2">
                        <img src="image/logo_nautic.png" alt="Logo de l'entreprise" class="img-fluid" />
                    </a>
                </div>
                <!-- Right elements -->
                <div class="col-lg-5 d-flex justify-content-center justify-content-md-end align-items-center">
                    <a href="#" class="nav-link">Magasin</a>
                    <a href="#" class="nav-link">Compte</a>
                    <!-- Bouton de langue -->
                    <button class="btn nav-link">
                        <img src="image/globe.png" alt="Langue" id="globe-icon" />
                    </button>
                    <!-- Bouton Paramètres -->
                    <button class="btn nav-link">
                        <img src="image/panier.png" alt="Panier" id="panier-icon" />
                    </button>
                </div>
            </div>
        </div>
    </div>
</header>