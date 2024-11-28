<header class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid">
        
        <button id="menu-btn">
            <img src="image/menu.png" alt="Menu" />
        </button>

        <!-- Inclure le menu -->
        <?php include 'menu.php'; ?>

        <div class="overlay"></div>
        
        <!-- Recherche -->
        <form class="d-flex search-form w-50">
            <input class="form-control" type="search" placeholder="Recherche" aria-label="Search">
        </form>

        <!-- Logo -->
        <a href="" class="navbar-brand">
            <img src="image/logo_nautic.png" alt="Logo de l'entreprise" class="logo">
        </a>
        <div class="navbar-text ms-auto">
            NAUTIC HORIZON
        </div>

        <!-- Liens -->
        <div class="d-flex">
            <a href="#" class="header-item header-link">Magasin</a>
            <a href="#" class="header-item header-link">Compte</a>

            <!-- Bouton de langue (avec globe simpliste) -->
            <button class="btn btn-link header-item">
                <img src="image/globe.png" alt="Langue" id="globe-icon">
            </button>

            <!-- Bouton Paramètres -->
            <button class="btn btn-link header-item no-border">
                <img src="image/parametre.png" alt="Paramètres" id="parametre-icon">
            </button>
        </div>
    </div>
</header>