<?php
$pageTitle = "Panier";
require_once "./include/head.php";
?>
<body>
    <?php
    require_once "./include/header.php";
    require_once "./include/menu.php";
    // Inclure la connexion à la base de données
    require_once './include/connect.inc.php';


        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            echo "Vous devez être connecté pour voir votre panier.";
            exit;
        } else {
            $user_id = $_SESSION['user'];
        }
        

        // Gérer les actions + et -
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer l'action et l'ID du produit
            $action = $_POST['action'];
            $product_id = $_POST['product_id'];

            // Vérifier l'action
            if ($action == 'increase') {
                // Augmenter la quantité de 1
                $query = "UPDATE DETAILPANIER SET QUANTITEPANIER = QUANTITEPANIER + 1 WHERE IDUTILISATEUR = :user_id AND IDPRODUIT = :product_id";
            } elseif ($action == 'decrease') {
                // Diminuer la quantité de 1 si elle est plus grande que 1
                $query = "UPDATE DETAILPANIER SET QUANTITEPANIER = QUANTITEPANIER - 1 WHERE IDUTILISATEUR = :user_id AND IDPRODUIT = :product_id AND QUANTITEPANIER > 1";
            }

            // Préparer et exécuter la requête
            if (isset($query)) {
                $reqQuantite = $conn->prepare($query);
                $reqQuantite->execute(['user_id' => $user_id, 'product_id' => $product_id]);
            }
        }

        // Récupérer les produits du panier de l'utilisateur à partir de la base de données
        $query = "SELECT p.IDPRODUIT, p.NOMPRODUIT, p.PRIX, dp.QUANTITEPANIER 
        FROM DETAILPANIER dp, PRODUIT p
        WHERE dp.IDPRODUIT = p.IDPRODUIT
        AND dp.IDUTILISATEUR = :user_id";

        $reqPanier = $conn->prepare($query);
        $reqPanier->execute(['user_id' => $user_id]);
        $panier = $reqPanier->fetchAll(PDO::FETCH_ASSOC);

        
        // // Appeler la procédure pour calculer le total du panier
        // $query_total = "CALL calculer_total_panier(:user_id)";
        // $stmt_total = $pdo->prepare($query_total);
        // $stmt_total->execute(['user_id' => $user_id]);
        // $total = $stmt_total->fetchColumn(); // Récupérer le résultat du total
    ?>

    
    <!-- Contenu principal -->
    <main role="main" class="container my-5">
        <h2>Votre panier</h2>

        <?php
        
        ?>

        <?php if (empty($panier)): ?>
            <p>Votre panier est vide.</p>
        <?php else: ?>
            <div class="panier-items">
                <?php foreach ($panier as $produit): ?>
                    <div class="panier-item">
                        <!-- Image du produit à gauche -->
                        <img src="./image/produit/test<?= htmlspecialchars($produit['IDPRODUIT']); ?>.png" alt="<?= htmlspecialchars($produit['NOMPRODUIT']); ?>" class="panier-item-image">
                        
                        <!-- Détails du produit au centre -->
                        <div class="panier-item-details">
                            <h2><?= htmlspecialchars($produit['NOMPRODUIT']); ?></h2>
                        </div>
                        
                        <!-- Prix et Quantité à droite -->
                        <div class="panier-item-price-quantity">
                            <p>Prix : <?= number_format($produit['PRIX'], 2, ',', ' '); ?>€</p>
                            
                            <!-- Quantité avec boutons + et - -->
                            <div class="quantity-buttons">
                                <!-- Formulaire bouton - -->
                                <form action="panier.php" method="POST" class="quantity-form">
                                    <input type="hidden" name="action" value="decrease">
                                    <input type="hidden" name="product_id" value="<?= $produit['IDPRODUIT']; ?>">
                                    <button type="submit" class="btn btn-outline-secondary">-</button>
                                </form>

                                <!-- Affichage de la quantité -->
                                <span class="quantity"><?= $produit['QUANTITEPANIER']; ?></span>

                                <!-- Formulaire bouton + -->
                                <form action="panier.php" method="POST" class="quantity-form">
                                    <input type="hidden" name="action" value="increase">
                                    <input type="hidden" name="product_id" value="<?= $produit['IDPRODUIT']; ?>">
                                    <button type="submit" class="btn btn-outline-secondary">+</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </main>

    <!-- Pied de page -->
    <?php require_once "./include/footer.php"; ?>
</body>
</html>