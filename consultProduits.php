<!-- Page pour consulter les produits 
 on recupere aussi les articles faisant 5m de plus ou de moins que la taille demandée
 et les articles dont le prix est 15% plus bas ou plus haut que le prix demandé
-->

<?php
$pageTitle = "Produits";
require_once "./include/head.php";
?>

<head>
    <style>
        .product-card {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.5s ease, transform 0.5s ease;
        }
        .product-card.show {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>

<body>
    <?php
        require_once "./include/header.php";
        require_once "./include/menu.php";
        require_once './include/connect.inc.php';

        // Récupérer l'ID de la catégorie depuis l'URL
        $idCateg = isset($_GET['idCateg']) ? $_GET['idCateg'] : null;

        // Récupérer les filtres depuis l'URL
        $energie = isset($_GET['energie']) ? $_GET['energie'] : null;
        $taille = isset($_GET['taille']) ? $_GET['taille'] : null;
        $prixMin = isset($_GET['prixMin']) ? $_GET['prixMin'] : null;
        $prixMax = isset($_GET['prixMax']) ? $_GET['prixMax'] : null;
        $sort = isset($_GET['sort']) ? $_GET['sort'] : null;
        $recherche = isset($_GET['recherche']) ? $_GET['recherche'] : null; // Nouveau paramètre de recherche

        // Récupérer les catégories filles si idCateg est défini
        $categories = [];
        if ($idCateg) {
            $categories[] = $idCateg;
            $queryCateg = $conn->prepare("SELECT IDCATEGORIE FROM CATEGORIE WHERE IDCATEGPERE = ?");
            $queryCateg->execute([$idCateg]);
            $childCategories = $queryCateg->fetchAll(PDO::FETCH_COLUMN);
            if (!empty($childCategories)) {
                $categories = array_merge($categories, $childCategories);
            }
        }
        // Convertir les catégories en une chaîne pour passer en paramètre
        $categoriesStr = !empty($categories) ? implode(',', $categories) : null;

        // Convertir les paramètres en valeurs valides pour la procédure
        $taille = is_numeric($taille) ? (int)$taille : null;
        $prixMin = is_numeric($prixMin) ? (float)$prixMin : null;
        $prixMax = is_numeric($prixMax) ? (float)$prixMax : null;
        $categoriesStr = !empty($categories) ? implode(',', $categories) : null;

        // Appeler la procédure stockée
        $query = $conn->prepare("CALL ObtenirProduitsFiltres(?, ?, ?, ?, ?, ?, ?)"); // Ajout d'un argument supplémentaire
        $query->execute([
            $categoriesStr,
            $energie ?: null, // Remplace une chaîne vide par NULL
            $taille,
            $prixMin,
            $prixMax,
            $sort ?: null, // Remplace une chaîne vide par NULL
            $recherche ?: null // Paramètre de recherche
        ]);
        $produits = $query->fetchAll(PDO::FETCH_ASSOC);
        $query->closeCursor();


        // Récupérer le prix minimum et maximum des produits
        $query = $conn->prepare("SELECT MIN(PRIX) as minPrix, MAX(PRIX) as maxPrix FROM PRODUIT");
        $query->execute();
        $prixRange = $query->fetch(PDO::FETCH_ASSOC);
    ?>

    <div class="container mt-4">
        <h1 class="text-center">Nos Produits</h1>
        <div class="text-center mb-4">
            <form method="GET" class="row g-3">
                <input type="hidden" name="idCateg" value="<?php echo $idCateg; ?>">
                <div class="col-md-3">
                    <label for="energie" class="form-label">Énergie</label>
                    <select id="energie" name="energie" class="form-select">
                        <option value="">Toutes</option>
                        <option value="Diesel" <?php echo $energie === 'Diesel' ? 'selected' : ''; ?>>Diesel</option>
                        <option value="Essence" <?php echo $energie === 'Essence' ? 'selected' : ''; ?>>Essence</option>
                        <option value="Electrique" <?php echo $energie === 'Electrique' ? 'selected' : ''; ?>>Électrique</option>
                        <option value="Manuel" <?php echo $energie === 'Manuel' ? 'selected' : ''; ?>>Manuel</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="taille" class="form-label">Taille (en mètres)</label>
                    <input type="number" id="taille" name="taille" class="form-control" value="<?php echo htmlspecialchars($taille); ?>">
                </div>
                <div class="col-md-3">
                    <label for="prixMin" class="form-label">Prix Minimum</label>
                    <input type="number" id="prixMin" name="prixMin" class="form-control" min="<?php echo $prixRange['minPrix']; ?>" max="<?php echo $prixRange['maxPrix']; ?>" value="<?php echo htmlspecialchars($prixMin); ?>">
                </div>
                <div class="col-md-3">
                    <label for="prixMax" class="form-label">Prix Maximum</label>
                    <input type="number" id="prixMax" name="prixMax" class="form-control" min="<?php echo $prixRange['minPrix']; ?>" max="<?php echo $prixRange['maxPrix']; ?>" value="<?php echo htmlspecialchars($prixMax); ?>">
                </div>
                <div class="col-md-3">
                    <label for="sort" class="form-label">Trier par prix</label>
                    <select id="sort" name="sort" class="form-select">
                        <option value="">Par défaut</option>
                        <option value="asc" <?php if ($sort === 'asc') echo 'selected'; ?>>Croissant</option>
                        <option value="desc" <?php if ($sort === 'desc') echo 'selected'; ?>>Décroissant</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="recherche" class="form-label">Recherche</label>
                    <input type="text" id="recherche" name="recherche" class="form-control" value="<?php echo htmlspecialchars($recherche); ?>">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Filtrer</button>
                </div>
            </form>
        </div>

        <?php if (count($produits) == 0): ?>
            <p class="text-center">Aucun bateau correspondant aux filtres n'a été trouvé.</p>
        <?php else: ?>
            <div class="row">
                <?php foreach ($produits as $produit): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 product-card">
                            <a href="detailProduit.php?id=<?php echo $produit['IDPRODUIT']; ?>">
                                <img src="https://static.wikia.nocookie.net/lego/images/7/73/70618_-_2.jpg/revision/latest?cb=20170727200641&path-prefix=fr" 
                                     class="card-img-top" 
                                     alt="<?php echo htmlspecialchars($produit['NOMPRODUIT']); ?>">
                            </a>
                            <div class="card-body">
                                <a href="detailProduit.php?id=<?php echo $produit['IDPRODUIT']; ?>" style="text-decoration: none; color: inherit;">
                                    <h5 class="card-title"><?php echo htmlspecialchars($produit['NOMPRODUIT']); ?></h5>
                                </a>
                                <p class="card-text">Prix : <?php echo number_format($produit['PRIX'], 2, ',', ' '); ?> €</p>
                            </div>
                            <div class="card-footer">
                                <a href="detailProduit.php?id=<?php echo $produit['IDPRODUIT']; ?>" class="btn btn-primary">Voir Détails</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const productCards = document.querySelectorAll('.product-card');
            productCards.forEach((card, index) => {
                setTimeout(() => {
                    card.classList.add('show');
                }, index * 100);
            });
        });
    </script>

    <?php
        require_once "./include/footer.php";
    ?>
</body>
</html>
