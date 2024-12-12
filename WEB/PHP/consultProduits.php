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

        // Requête pour récupérer les produits en fonction des filtres
        $queryStr = "SELECT * FROM PRODUIT WHERE 1=1";
        $params = [];

        if ($idCateg) {
            $queryStr .= " AND IDCATEGORIE = ?";
            $params[] = $idCateg;
        }
        if ($energie) {
            $queryStr .= " AND ENERGIE = ?";
            $params[] = $energie;
        }
        if ($taille) {
            $tailleMin = (int)$taille - 5;
            $tailleMax = (int)$taille + 5;
            $queryStr .= " AND CAST(REPLACE(TAILLE, 'm', '') AS UNSIGNED) BETWEEN ? AND ?";
            $params[] = $tailleMin;
            $params[] = $tailleMax;
        }
        if ($prixMin) {
            $prixMinTol = $prixMin * 0.8;
            $queryStr .= " AND PRIX >= ?";
            $params[] = $prixMinTol;
        }
        if ($prixMax) {
            $prixMaxTol = $prixMax * 1.15;
            $queryStr .= " AND PRIX <= ?";
            $params[] = $prixMaxTol;
        }

        $query = $conn->prepare($queryStr);
        $query->execute($params);
        $produits = $query->fetchAll(PDO::FETCH_ASSOC);

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
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Filtrer</button>
                </div>
            </form>
        </div>
        <div class="row">
            <?php foreach ($produits as $produit): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 product-card">
                        <!-- L'image redirige vers la page détail produit -->
                        <a href="detailProduit.php?id=<?php echo $produit['IDPRODUIT']; ?>">
                            <img src="https://static.wikia.nocookie.net/lego/images/7/73/70618_-_2.jpg/revision/latest?cb=20170727200641&path-prefix=fr" 
                                 class="card-img-top" 
                                 alt="<?php echo htmlspecialchars($produit['NOMPRODUIT']); ?>">
                        </a>
                        <div class="card-body">
                            <!-- Le titre du produit redirige également -->
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
