<header class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        
        <button id="menu-btn">
            <img src="image/menu.png" alt="Menu" />
        </button>

        <!-- Inclure le menu -->
        <?php include 'menu.php'; ?>
        
        <!-- Recherche -->
        <form class="d-flex search-form w-50">
            <input class="form-control" type="search" placeholder="Recherche" aria-label="Search">
        </form>

        <!-- Logo -->
        <a href="" class="navbar-brand">
            <img src="logo.png" alt="Logo de l'entreprise" class="logo">
        </a>

        <!-- Liens -->
        <div class="d-flex">
            <a href="#" class="header-item">Magasin</a>
            <a href="#" class="header-item">Compte</a>

            <!-- Bouton de langue (avec globe simpliste) -->
            <button class="btn btn-link header-item no-border" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="globe-icon">&#127758;</span> <!-- Globe simpliste en HTML (Unicode) -->
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="?lang=fr">Français</a>
                <a class="dropdown-item" href="?lang=en">English</a>
            </div>

            <!-- Bouton Paramètres -->
            <button class="btn btn-link header-item no-border">
                <i class="bi bi-gear"></i> <!-- Icône des paramètres -->
            </button>
        </div>
    </div>
</header>