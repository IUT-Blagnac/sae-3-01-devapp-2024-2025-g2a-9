<?php
$pageTitle = "Produits";
require_once "./include/head.php";
?>

<body>
    <?php
        require_once "./include/header.php";
        require_once "./include/menu.php";
        require_once './include/connect.inc.php';

        // Requête pour récupérer tous les produits
        $query = $conn->prepare("SELECT * FROM PRODUIT");
        $query->execute();
        $produits = $query->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <div class="container mt-4">
        <h1 class="text-center">Nos Produits</h1>
        <div class="row">
            <?php foreach ($produits as $produit): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="https://static.wikia.nocookie.net/lego/images/7/73/70618_-_2.jpg/revision/latest?cb=20170727200641&path-prefix=fr" class="card-img-top" alt="<?php echo htmlspecialchars($produit['NOMPRODUIT']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($produit['NOMPRODUIT']); ?></h5>
                            <p class="card-text">Prix : <?php echo number_format($produit['PRIX'], 2, ',', ' '); ?> €</p>
                        </div>
                        <div class="card-footer">
                            <a href="ajouter_panier.php?id=<?php echo $produit['IDPRODUIT']; ?>" class="btn btn-primary">Ajouter au panier</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php
        require_once "./include/footer.php";
    ?>
</body>
</html>