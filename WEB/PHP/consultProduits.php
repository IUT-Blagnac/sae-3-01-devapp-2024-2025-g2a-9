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
        
        // Récupérer les filtres depuis l'URL
        $idCategorie = isset($_GET['idCateg']) ? $_GET['idCateg'] : null;
        $filtreEnergie = isset($_GET['energie']) ? $_GET['energie'] : null;
        $filtreTaille = isset($_GET['taille']) ? $_GET['taille'] : null;
        $filtrePrixMin = isset($_GET['prixMin']) && $_GET['prixMin'] !== '' ? $_GET['prixMin'] : null;
        $filtrePrixMax = isset($_GET['prixMax']) && $_GET['prixMax'] !== '' ? $_GET['prixMax'] : null;
        $ordreTri = isset($_GET['sort']) ? $_GET['sort'] : null;
        
        if ($idCategorie !== null) {
            // Appeler la procédure pour obtenir les produits filtrés
            $stmt = $conn->prepare("CALL ObtenirProduitsFiltres(:idCategorie, :filtreEnergie, :filtreTaille, :filtrePrixMin, :filtrePrixMax, :ordreTri)");
            $stmt->bindValue(':idCategorie', $idCategorie, PDO::PARAM_INT);
            $stmt->bindValue(':filtreEnergie', $filtreEnergie !== '' ? $filtreEnergie : null, PDO::PARAM_STR);
            $stmt->bindValue(':filtreTaille', $filtreTaille !== '' ? $filtreTaille : null, PDO::PARAM_INT);
            $stmt->bindValue(':filtrePrixMin', $filtrePrixMin, $filtrePrixMin !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
            $stmt->bindValue(':filtrePrixMax', $filtrePrixMax, $filtrePrixMax !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
            $stmt->bindValue(':ordreTri', $ordreTri !== '' ? $ordreTri : null, PDO::PARAM_STR);
            $stmt->execute();
            $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
        } else {
            $produits = [];
        }
        
        // Récupérer le prix minimum et maximum des produits
        $query = $conn->prepare("SELECT MIN(PRIX) as minPrix, MAX(PRIX) as maxPrix FROM PRODUIT");
        $query->execute();
        $prixRange = $query->fetch(PDO::FETCH_ASSOC);
    ?>

    <div class="container mt-4">
        <h1 class="text-center">Nos Produits</h1>
        <div class="text-center mb-4">
            <form method="GET" class="row g-3">
                <input type="hidden" name="idCateg" value="<?php echo $idCategorie; ?>">
                <div class="col-md-3">
                    <label for="energie" class="form-label">Énergie</label>
                    <select id="energie" name="energie" class="form-select">
                        <option value="">Toutes</option>
                        <option value="Diesel" <?php echo $filtreEnergie === 'Diesel' ? 'selected' : ''; ?>>Diesel</option>
                        <option value="Essence" <?php echo $filtreEnergie === 'Essence' ? 'selected' : ''; ?>>Essence</option>
                        <option value="Electrique" <?php echo $filtreEnergie === 'Electrique' ? 'selected' : ''; ?>>Électrique</option>
                        <option value="Manuel" <?php echo $filtreEnergie === 'Manuel' ? 'selected' : ''; ?>>Manuel</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="taille" class="form-label">Taille (en mètres)</label>
                    <input type="number" id="taille" name="taille" class="form-control" value="<?php echo htmlspecialchars($filtreTaille); ?>">
                </div>
                <div class="col-md-3">
                    <label for="prixMin" class="form-label">Prix Minimum</label>
                    <input type="number" id="prixMin" name="prixMin" class="form-control" min="<?php echo $prixRange['minPrix']; ?>" max="<?php echo $prixRange['maxPrix']; ?>" value="<?php echo htmlspecialchars($filtrePrixMin); ?>">
                </div>
                <div class="col-md-3">
                    <label for="prixMax" class="form-label">Prix Maximum</label>
                    <input type="number" id="prixMax" name="prixMax" class="form-control" min="<?php echo $prixRange['minPrix']; ?>" max="<?php echo $prixRange['maxPrix']; ?>" value="<?php echo htmlspecialchars($filtrePrixMax); ?>">
                </div>
                <div class="col-md-3">
                    <label for="sort" class="form-label">Trier par prix</label>
                    <select id="sort" name="sort" class="form-select">
                        <option value="">Par défaut</option>
                        <option value="asc" <?php if ($ordreTri === 'asc') echo 'selected'; ?>>Croissant</option>
                        <option value="desc" <?php if ($ordreTri === 'desc') echo 'selected'; ?>>Décroissant</option>
                    </select>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Appliquer</button>
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