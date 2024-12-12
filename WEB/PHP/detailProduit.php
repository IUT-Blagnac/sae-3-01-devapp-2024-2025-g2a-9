<?php
ob_start();

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

    $isInCart = false;

    if (isset($_SESSION['user'])) {
        // Vérifier dans la base de données pour un utilisateur connecté
        $userId = $_SESSION['user'];
        $query = $conn->prepare("SELECT 1 FROM DETAILPANIER WHERE IDUTILISATEUR = :userId AND IDPRODUIT = :productId");
        $query->execute(['userId' => $userId, 'productId' => $produit['IDPRODUIT']]);
        $isInCart = $query->fetch() ? true : false;
    } else {
        // Vérifier dans la session pour un utilisateur non connecté
        $isInCart = isset($_SESSION['panier'][$produit['IDPRODUIT']]);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'ajouter_au_panier') {
        $productId = intval($_POST['id']);
    
        if (isset($_SESSION['user'])) {
            // Utilisateur connecté : ajout à la base de données
            $userId = $_SESSION['user'];
    
            if (!$isInCart) {
                // Ajouter le produit au panier
                $query = $conn->prepare("INSERT INTO DETAILPANIER (IDUTILISATEUR, IDPRODUIT, QUANTITEPANIER) VALUES (:userId, :productId, 1)");
                $query->execute(['userId' => $userId, 'productId' => $productId]);
                $isInCart = true;
            }
        } else {
            // Utilisateur non connecté : ajout à la session
            if (!$isInCart) {
                $_SESSION['panier'][$productId] = 1;
                $isInCart = true;
            }
        }
    }
?>
<!-- Contenu principal -->
<main role="main" class="container my-5">
    <a class="back-button_detailprod" href="javascript:history.back()">
        <i class="fa-solid fa-chevron-left"></i> Retour à la page précédente
    </a>
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
            
            <?php if ($isInCart): ?>
                <button class="btn btn-secondary btn-lg mt-2" disabled>Article déjà dans le panier</button>
            <?php else: ?>
                <form action="" method="POST" class="mt-4">
                    <input type="hidden" name="action" value="ajouter_au_panier">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($produit['IDPRODUIT']); ?>">
                    <button type="submit" class="btn btn-primary btn-lg">Ajouter au panier</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</main>

<!-- Pied de page -->
<?php require_once "./include/footer.php"; ?>
</body>
</html>
<?php
ob_end_flush();
?>
