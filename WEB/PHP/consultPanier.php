<?php
$pageTitle = "Panier";
require_once "./include/head.php";
?>
<body>
<?php
    require_once "./include/header.php";
    require_once "./include/menu.php";
    require_once './include/connect.inc.php';

    // // Simuler des produits dans le panier pour un utilisateur non connecté
    // if (!isset($_SESSION['user'])) { // Si l'utilisateur n'est pas connecté
    //     // Ajouter des produits fictifs si le panier est vide
    //     if (empty($_SESSION['panier'])) {
    //         $_SESSION['panier'] = [
    //             1 => 2, // ID du produit => Quantité
    //             2 => 1,
    //             3 => 5
    //         ];
    //     }
    // }

    // $password = password_hash("pass1234",PASSWORD_DEFAULT);
    //         $req = $conn->prepare("INSERT INTO UTILISATEUR (nom, prenom, civilite, mail, password, droit)
    //                                VALUES (?,?,?,?,?,'CLIENT')") ;
    //         $req->execute(["test", "test", "MR", "test@mail.com", $password]);

    $isUserLoggedIn = isset($_SESSION['user']); // Vérifie si l'utilisateur est connecté
    $user_id = $isUserLoggedIn ? $_SESSION['user'] : null;

    // Gérer les actions + et -
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'];
        $product_id = $_POST['product_id'];

        if ($isUserLoggedIn) {
            if ($action === 'increase') {
                // Récupérer le stock disponible pour le produit
                $queryStock = "SELECT STOCKDISPONIBLE FROM PRODUIT WHERE IDPRODUIT = :product_id";
                $reqStock = $conn->prepare($queryStock);
                $reqStock->execute(['product_id' => $product_id]);
                $stock = $reqStock->fetchColumn();
    
                // Vérifier la quantité actuelle dans le panier
                $queryPanier = "SELECT QUANTITEPANIER 
                                FROM DETAILPANIER 
                                WHERE IDUTILISATEUR = :user_id AND IDPRODUIT = :product_id";
                $reqPanier = $conn->prepare($queryPanier);
                $reqPanier->execute(['user_id' => $user_id, 'product_id' => $product_id]);
                $quantityInCart = $reqPanier->fetchColumn();
    
                if ($quantityInCart < $stock) {
                    // Augmenter la quantité si le stock le permet
                    $queryUpdate = "UPDATE DETAILPANIER 
                                    SET QUANTITEPANIER = QUANTITEPANIER + 1 
                                    WHERE IDUTILISATEUR = :user_id AND IDPRODUIT = :product_id";
                    $reqUpdate = $conn->prepare($queryUpdate);
                    $reqUpdate->execute(['user_id' => $user_id, 'product_id' => $product_id]);
                } else {
                    // Si le stock est insuffisant, afficher un message (ou autre traitement)
                    $_SESSION['message'] = "Stock insuffisant pour ce produit.";
                }
            } elseif ($action === 'decrease') {
                // Vérifier la quantité actuelle
                $queryCheck = "SELECT QUANTITEPANIER 
                               FROM DETAILPANIER 
                               WHERE IDUTILISATEUR = :user_id AND IDPRODUIT = :product_id";
                $reqCheck = $conn->prepare($queryCheck);
                $reqCheck->execute(['user_id' => $user_id, 'product_id' => $product_id]);
                $result = $reqCheck->fetch(PDO::FETCH_ASSOC);
        
                if ($result && $result['QUANTITEPANIER'] > 1) {
                    // Réduire la quantité si elle est supérieure à 1
                    $query = "UPDATE DETAILPANIER 
                              SET QUANTITEPANIER = QUANTITEPANIER - 1 
                              WHERE IDUTILISATEUR = :user_id AND IDPRODUIT = :product_id";
                    $reqUpdate = $conn->prepare($query);
                    $reqUpdate->execute(['user_id' => $user_id, 'product_id' => $product_id]);
                } else {
                    // Supprimer l'article si la quantité est 1 ou moins
                    $query = "DELETE FROM DETAILPANIER 
                              WHERE IDUTILISATEUR = :user_id AND IDPRODUIT = :product_id";
                    $reqDelete = $conn->prepare($query);
                    $reqDelete->execute(['user_id' => $user_id, 'product_id' => $product_id]);
                }
            }
        } else {
            // Utilisateur non connecté : mise à jour dans la session
            if (!isset($_SESSION['panier'][$product_id])) {
                $_SESSION['panier'][$product_id] = 0;
            }
            if ($action === 'increase') {
                // Vérifier le stock disponible
                $queryStock = "SELECT STOCKDISPONIBLE FROM PRODUIT WHERE IDPRODUIT = :product_id";
                $reqStock = $conn->prepare($queryStock);
                $reqStock->execute(['product_id' => $product_id]);
                $stock = $reqStock->fetchColumn();

                if ($_SESSION['panier'][$product_id] < $stock) {
                    $_SESSION['panier'][$product_id]++;
                } else {
                    $_SESSION['message'] = "Stock insuffisant pour ce produit.";
                }
            } elseif ($action === 'decrease') {
                if ($_SESSION['panier'][$product_id] > 1) {
                    $_SESSION['panier'][$product_id]--;
                } else {
                    unset($_SESSION['panier'][$product_id]); // Supprime l'article si quantité = 1
                }
            }
        }
    }

    // Récupérer les produits du panier
    $panier = [];
    if ($isUserLoggedIn) {
        // Récupérer depuis la base de données pour un utilisateur connecté
        $query = "SELECT p.IDPRODUIT, p.NOMPRODUIT, p.PRIX, dp.QUANTITEPANIER 
                  FROM DETAILPANIER dp
                  JOIN PRODUIT p ON dp.IDPRODUIT = p.IDPRODUIT
                  WHERE dp.IDUTILISATEUR = :user_id";
        $stmt = $conn->prepare($query);
        $stmt->execute(['user_id' => $user_id]);
        $panier = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        // Récupérer depuis la session pour un utilisateur non connecté
        foreach ($_SESSION['panier'] as $product_id => $quantity) {
            $query = "SELECT IDPRODUIT, NOMPRODUIT, PRIX FROM PRODUIT WHERE IDPRODUIT = :product_id";
            $stmt = $conn->prepare($query);
            $stmt->execute(['product_id' => $product_id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($product) {
                $product['QUANTITEPANIER'] = $quantity;
                $panier[] = $product;
            }
        }
    }
    ?>

    <!-- Contenu principal -->
    <main role="main" class="container my-5">
        <h2>Votre panier</h2>

        <?php if (empty($panier)): ?>
            <p>Votre panier est vide.</p>
        <?php else: ?>
            <div class="panier-items">
                <?php if (isset($_SESSION['message'])): ?>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            // Afficher le message dans une boîte popup
                            alert("<?= htmlspecialchars($_SESSION['message']); ?>");
                        });
                    </script>
                    <?php unset($_SESSION['message']); // Supprimer le message après affichage ?>
                <?php endif; ?>

                <?php foreach ($panier as $produit): ?>
                    <div class="panier-item">
                        <img src="./image/produit/prod<?= htmlspecialchars($produit['IDPRODUIT']); ?>.png" 
                        alt="<?= htmlspecialchars($produit['NOMPRODUIT']); ?>" 
                        class="panier-item-image"
                        onclick="window.location.href='detailProduit.php?id=<?= htmlspecialchars($produit['IDPRODUIT']); ?>'">

                        <div class="panier-item-details">
                            <h2 onclick="window.location.href='detailProduit.php?id=<?= htmlspecialchars($produit['IDPRODUIT']); ?>'"><?= htmlspecialchars($produit['NOMPRODUIT']); ?></h2>
                        </div>

                        <div class="panier-item-price-quantity">
                            <p>Prix : <?= number_format($produit['PRIX'], 2, ',', ' '); ?>€</p>
                            <div class="quantity-buttons">
                                <form action="consultPanier.php" method="POST" class="quantity-form">
                                    <input type="hidden" name="action" value="decrease">
                                    <input type="hidden" name="product_id" value="<?= $produit['IDPRODUIT']; ?>">
                                    <button type="submit" class="btn btn-outline-secondary">-</button>
                                </form>
                                <span class="quantity"><?= $produit['QUANTITEPANIER']; ?></span>
                                <form action="consultPanier.php" method="POST" class="quantity-form">
                                    <input type="hidden" name="action" value="increase">
                                    <input type="hidden" name="product_id" value="<?= $produit['IDPRODUIT']; ?>">
                                    <button type="submit" class="btn btn-outline-secondary">+</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <div class="panier-total my-4">
                    <?php
                    $total = 0;
                    $reqTotal = $conn->prepare("CALL CalculerTotalPanier(:idUtilisateur, @total)");
                    $reqTotal->execute([':idUtilisateur' => $_SESSION['user']]);
                    $reqTotalResult = $conn->prepare("SELECT @total AS total");
                    $reqTotalResult->execute([]);
                    $result = $reqTotalResult->fetch(PDO::FETCH_ASSOC);
                    $total = $result['total'];
                    ?>
                    <h3>Total : <?= number_format($total, 2, ',', ' '); ?>€</h3>
                </div>
                <?php 
                    if (!$isUserLoggedIn) {
                        echo '<a href="formConnexion.php" class="btn btn-primary rounded-pill">Connectez-vous pour passer votre commande</a>';
                    } else {
                        echo '<a href="commander.php" class="btn btn-primary rounded-pill">Passer votre commande</a>';
                    }
                ?>
            </div>
        <?php endif; ?>
    </main>

    <!-- Pied de page -->
    <?php require_once "./include/footer.php"; ?>
</body>
</html>
