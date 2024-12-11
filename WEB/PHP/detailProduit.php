<?php
ob_start(); // Active le tampon de sortie pour éviter l'envoi prématuré des données.

$pageTitle = "Détail du Produit";
require_once "./include/head.php";
?>
<body>
<?php
    require_once "./include/header.php";
    require_once "./include/menu.php";
    require_once './include/connect.inc.php';

    // Exemple : récupérer l'ID du produit depuis l'URL
    $productId = isset($_GET['id']) ? intval($_GET['id']) : 0;

    // Requête SQL pour récupérer les détails du produit
    $query = $conn->prepare("SELECT * FROM PRODUIT WHERE IDPRODUIT = :id");
    $query->execute(['id' => $productId]);
    $produit = $query->fetch();

    // Si le produit est introuvable, rediriger vers la page 404
    if (!$produit) {
        header("Location: 404error.php");
        exit;
    }
?>
<!-- Contenu principal -->
<main role="main" class="container my-5">
    <div class="row">
        <!-- Section image du produit -->
        <div class="col-md-6">
            <div class="product-gallery">
                <img src="./image/produit/test<?= htmlspecialchars($produit['IDPRODUIT']); ?>.png" 
                     alt="<?= htmlspecialchars($produit['NOMPRODUIT']); ?>" 
                     class="img-fluid main-image">
            </div>
        </div>

        <!-- Section détails du produit -->
        <div class="col-md-6">
            <h1><?= htmlspecialchars($produit['NOMPRODUIT']); ?></h1>
            <h3 class="price"><?= number_format($produit['PRIX'], 2, ',', ' '); ?> €</h3>
            <p class="product-description mt-4"><?= htmlspecialchars($produit['DESCRIPTION']); ?></p>
            <p class="product-description mt-4">Taille : <?= htmlspecialchars($produit['TAILLE']); ?></p>
            <p class="product-description mt-4">Type d'énergie : <?= htmlspecialchars($produit['ENERGIE']); ?></p>
            <p class="product-description mt-4">Quantité disponible en stock : <?= htmlspecialchars($produit['STOCKDISPONIBLE']); ?></p>
            <form action="panier.php" method="POST" class="mt-4">
                <input type="hidden" name="product_id" value="<?= $produit['IDPRODUIT']; ?>">
                <button type="submit" class="btn btn-primary btn-lg">Ajouter au panier</button>
            </form>
        </div>
    </div>
</main>

<!-- Pied de page -->
<?php require_once "./include/footer.php"; ?>
</body>
</html>
<?php
ob_end_flush(); // Envoie le contenu du tampon et désactive la mise en tampon.
?>
