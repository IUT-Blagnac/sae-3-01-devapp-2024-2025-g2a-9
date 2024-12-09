<header class="header bg-white border-bottom">
    <div class="container-fluid d-flex align-items-center flex-wrap">
        <!-- Bouton menu -->
        <button class="menu-btn me-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="sidebar">
            <i class="bi bi-list"></i>
        </button>

        <!-- Barre de recherche -->
        <form class="search-form flex-grow-1">
            <input class="search-input form-control" type="search" placeholder="Rechercher" aria-label="Search">
        </form>

        <!-- Logo et nom de l'entreprise -->
        <div class="header-brand ms-3">
            <a href="index.php"><img src="image/logoNautic.png" alt="Logo" class="header-logo"></a>
            <a href="index.php" class="d-none d-md-inline text-decoration-none fw-bold">Nautic Horizon</a>
        </div>

        <!-- Liens à droite -->
        <ul class="header-links list-unstyled d-flex align-items-center ms-auto">
            <li><a class="nav-link" href="#">Magasins</a></li>
            <?php
                session_start();
                $_SESSION['url'] = basename($_SERVER['PHP_SELF']);// Enregistre le fichier php actuel
                if (!isset($_SESSION['user'])) { 
                    echo "<li><a class=\"nav-link\" href=\"formConnexion.php\">Compte</a></li>";
                } else {
                    if ($_SESSION['url'] == "compte.php") {
                        echo '<li><a class="nav-link" href="deconnexion.php">Déconnexion</a></li>';
                    } else {
                        echo '<li><a class="nav-link" href="compte.php">Compte</a></li>';
                    }
                }
            ?>
            <li><a class="nav-link" href="#"><i class="bi bi-translate"></i></a></li>
            <li><a class="nav-link" href="panier.php"><i class="bi bi-cart"></i></a></li>
        </ul>
    </div>
</header>

