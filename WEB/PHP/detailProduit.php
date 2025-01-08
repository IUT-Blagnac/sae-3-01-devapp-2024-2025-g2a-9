<?php
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
    $isInFav = false;
    
    // Utilisateur connection verif
    if (isset($_SESSION['user'])) {
        $userId = $_SESSION['user'];

        // Vérifier panier dans la base de données pour un utilisateur connecté
        $reqPanier = $conn->prepare("SELECT 1 FROM DETAILPANIER WHERE IDUTILISATEUR = :userId AND IDPRODUIT = :productId");
        $reqPanier->execute(['userId' => $userId, 'productId' => $produit['IDPRODUIT']]);
        $isInCart = $reqPanier->fetch() ? true : false;

        // Vérifier favori dans la base de données pour un utilisateur connecté
        $reqFav = $conn->prepare("SELECT 1 FROM FAVORI WHERE IDUTILISATEUR = :userId AND IDPRODUIT = :productId");
        $reqFav->execute(['userId' => $userId, 'productId' => $produit['IDPRODUIT']]);
        $isInFav = $reqFav->fetch() ? true : false;

        // Vérification si l'utilisateur a déjà commandé ce produit
        $queryCommande = $conn->prepare("
            SELECT 1
            FROM DETAILCOMMANDE DC
            JOIN COMMANDE C ON DC.IDCOMMANDE = C.IDCOMMANDE
            WHERE C.IDUTILISATEUR = :userId
            AND DC.IDPRODUIT = :productId
        ");
        $queryCommande->execute(['userId' => $userId, 'productId' => $productId]);
        $hasPurchased = $queryCommande->fetch() ? true : false;

        $queryAvisExist = $conn->prepare("
            SELECT 1
            FROM AVIS
            WHERE IDUTILISATEUR = :userId
            AND IDPRODUIT = :productId
        ");
        $queryAvisExist->execute(['userId' => $userId, 'productId' => $productId]);
        $hasReviewed = $queryAvisExist->fetch() ? true : false;
    } else {
        // Vérifier dans la session pour un utilisateur non connecté
        $isInCart = isset($_SESSION['panier'][$produit['IDPRODUIT']]);

        $hasPurchased = false;

        $hasReviewed = false;
    }

    // Traitement Ajouter au panier
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'ajouter_au_panier') {    
        if (isset($userId)) {
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

    // Traitement Ajouter / Enlever favori
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'ajouter_favori') {    
        if (isset($userId)) {
            if (!$isInFav) {
                // Ajouter le produit aux favoris
                $dateFavori = date('Y/m/d');
                $query = $conn->prepare("INSERT INTO FAVORI (IDUTILISATEUR, IDPRODUIT, DATEFAVORI) VALUES (:userId, :productId, :dateFavori)");
                $query->execute(['userId' => $userId, 'productId' => $productId, 'dateFavori' => $dateFavori]);
                $isInFav = true;
            }
        }
    } else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'enlever_favori'){
        if (isset($userId)) {
            if ($isInFav) {
                // Enlever le produit des favoris
                $query = $conn->prepare("DELETE FROM FAVORI WHERE IDUTILISATEUR = :userId AND IDPRODUIT = :productId");
                $query->execute(['userId' => $userId, 'productId' => $productId]);
                $isInFav = false;
            }
        }
    }

    // Traitement du formulaire d'avis
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitReview'])) {
        $note = isset($_POST['note']) ? intval($_POST['note']) : 0;
        $commentaire = isset($_POST['commentaire']) ? trim($_POST['commentaire']) : '';
        $idProduit = isset($_POST['idProduit']) ? intval($_POST['idProduit']) : 0;

        // Validation des données
        if ($note >= 1 && $note <= 5 && !empty($commentaire) && $idProduit === $productId) {
            // Insertion dans la table AVIS
            $dateAvis = date('Y-m-d');
            $queryAvis = $conn->prepare("
                INSERT INTO AVIS (IDUTILISATEUR, IDPRODUIT, NOTE, COMMENTAIRE, DATEAVIS)
                VALUES (:userId, :productId, :note, :commentaire, :dateAvis)
            ");
            $queryAvis->execute([
                'userId' => $userId,
                'productId' => $idProduit,
                'note' => $note,
                'commentaire' => $commentaire,
                'dateAvis' => $dateAvis
            ]);
            
        }
    }

    // Traitement de la suppression de l'avis
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'supprimer_avis') {
        if (isset($userId) && isset($_POST['idAvis'])) {
            $idAvis = intval($_POST['idAvis']);

            // Vérification que l'avis appartient à l'utilisateur
            $query = $conn->prepare("SELECT IDUTILISATEUR FROM AVIS WHERE IDAVIS = :idAvis");
            $query->execute(['idAvis' => $idAvis]);
            $avis = $query->fetch();

            if ($avis && $avis['IDUTILISATEUR'] == $userId) {
                // Si l'avis appartient à l'utilisateur connecté, supprimer l'avis
                $deleteQuery = $conn->prepare("DELETE FROM AVIS WHERE IDAVIS = :idAvis");
                $deleteQuery->execute(['idAvis' => $idAvis]);
            }
        }
    }

    // Vérification de l'état des favoris (après ajout ou retrait)
    if (isset($userId)) {
        $reqFav = $conn->prepare("SELECT 1 FROM FAVORI WHERE IDUTILISATEUR = :userId AND IDPRODUIT = :productId");
        $reqFav->execute(['userId' => $userId, 'productId' => $productId]);
        $isInFav = $reqFav->fetch() ? true : false;
    }

    // Requête pour récupérer les avis du produit
    $queryAvis = $conn->prepare("
        SELECT 
            A.IDAVIS,
            A.COMMENTAIRE, 
            A.NOTE, 
            A.DATEAVIS,
            A.IDUTILISATEUR, 
            U.NOM
        FROM AVIS A, UTILISATEUR U
        WHERE A.IDUTILISATEUR = U.IDUTILISATEUR
        AND A.IDPRODUIT = :productId
        ORDER BY A.DATEAVIS DESC
    ");
    $queryAvis->execute(['productId' => $productId]);
    $avisList = $queryAvis->fetchAll();
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
                <img src="./image/produit/prod<?= htmlspecialchars($produit['IDPRODUIT']); ?>.png" 
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

            <div class="d-flex align-items-center">
                <!-- Bouton Ajouter au panier -->
                <?php if ($isInCart): ?>
                    <button class="btn btn-secondary btn-lg mt-4" disabled>Article déjà dans le panier</button>
                <?php else: ?>
                    <form action="" method="POST" class="mt-4">
                        <input type="hidden" name="action" value="ajouter_au_panier">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($produit['IDPRODUIT']); ?>">
                        <button type="submit" class="btn btn-primary btn-lg">Ajouter au panier</button>
                    </form>
                <?php endif; ?>

                <!-- Bouton Favori -->
                <?php if (isset($userId)): ?>
                    <?php if (!$isInFav): ?>
                        <form action="" method="POST" class="mt-4">
                            <input type="hidden" name="action" value="ajouter_favori">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($produit['IDPRODUIT']); ?>">
                            <button type="submit" class="btn btn-outline-secondary btn-lg bouton-favori">
                                <i class="far fa-heart"></i>
                            </button>
                        </form>
                    <?php else: ?>
                        <form action="" method="POST" class="mt-4">
                            <input type="hidden" name="action" value="enlever_favori">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($produit['IDPRODUIT']); ?>">
                            <button type="submit" class="btn btn-outline-secondary btn-lg bouton-favori">
                                <i class="fas fa-heart"></i>
                            </button>
                        </form>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Section affichage des avis -->
    <div class="product-reviews mt-5">
        <h2>Avis des utilisateurs</h2>

        <?php if (empty($avisList)): ?>
            <p class="text-muted">Aucun avis pour ce produit pour le moment.</p>
        <?php else: ?>
            <?php foreach ($avisList as $avis): ?>
                <div class="review mb-4">
                    <strong><?= htmlspecialchars($avis['NOM']); ?></strong>
                    <span class="text-muted">(<?= date('d/m/Y', strtotime($avis['DATEAVIS'])); ?>)</span>
                    <p class="rating">Note : <?= str_repeat('★', intval($avis['NOTE'])); ?><?= str_repeat('☆', 5 - intval($avis['NOTE'])); ?></p>
                    <p class="comment"><?= htmlspecialchars($avis['COMMENTAIRE']); ?></p>

                    <?php if (isset($userId) && $userId == $avis['IDUTILISATEUR']): ?>
                        <!-- Bouton de suppression visible uniquement pour l'utilisateur qui a laissé l'avis -->
                        <form action="" method="POST" class="d-inline-block">
                            <input type="hidden" name="action" value="supprimer_avis">
                            <input type="hidden" name="idAvis" value="<?= htmlspecialchars($avis['IDAVIS']); ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Section pour laisser un avis, visible uniquement si l'utilisateur a commandé ce produit -->
    <?php if (isset($userId) && $hasPurchased && !$hasReviewed): ?>
        <div class="leave-review mt-5">
            <h3>Laissez un avis sur ce produit</h3>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="note">Note :</label>
                    <select name="note" id="note" class="form-control" required>
                        <option value="1">1 - Très mauvais</option>
                        <option value="2">2 - Mauvais</option>
                        <option value="3">3 - Moyen</option>
                        <option value="4">4 - Bon</option>
                        <option value="5">5 - Excellent</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="commentaire">Commentaire :</label>
                    <textarea name="commentaire" id="commentaire" class="form-control" rows="5" required></textarea>
                </div>
                <input type="hidden" name="idProduit" value="<?= htmlspecialchars($productId); ?>">
                <button type="submit" class="btn btn-primary mt-3" name="submitReview">Soumettre l'avis</button>
            </form>
        </div>
    <?php endif; ?>

</main>

<!-- Pied de page -->
<?php require_once "./include/footer.php"; ?>
</body>
</html>
