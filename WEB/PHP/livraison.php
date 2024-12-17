<?php
    $pageTitle = "Votre compte";
    require_once "./include/head.php";
?>
<body>
    <!-- Header avec ligne de suivi -->
    <header class="header bg-white border-bottom">
        <div class="container text-center py-3">
            <div class="d-flex justify-content-center align-items-center">
                <div class="btn-group" role="group">
                    <a href="consultPanier.php" class="btn btn-outline-primary">1. Panier</a>
                    <div class="border-bottom border-primary mx-2" style="width: 50px; height: 2px;"></div>
                    <a href="livraison.php" class="btn btn-outline-primary active">2. Livraison</a>
                    <div class="border-bottom border-primary mx-2" style="width: 50px; height: 2px;"></div>
                    <a href="#" class="btn btn-outline-secondary btn-lg disabled" aria-disabled="true">3. Paiement</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Contenu principal -->
    <main role="main" class="container my-5">
        <?php
        require_once "./include/connect.inc.php";

            $reqUser = $conn->prepare("SELECT * FROM UTILISATEUR WHERE idUtilisateur = ?");
            $reqUser->execute([$_SESSION['user']]);
            $user = $reqUser->fetch();
            $adresse = htmlspecialchars($user['ADRESSE'] ?? "Adresse non renseignée");

            $reqPanier = $conn->prepare("SELECT p.IDPRODUIT, p.NOMPRODUIT, p.PRIX, dp.QUANTITEPANIER 
                        FROM DETAILPANIER dp
                        JOIN PRODUIT p ON dp.IDPRODUIT = p.IDPRODUIT
                        WHERE dp.IDUTILISATEUR = ?");
            $reqPanier->execute([$_SESSION['user']]);
            $panier = $reqPanier->fetchAll(PDO::FETCH_ASSOC);

            // Fonction pour calculer le total panier
            function calculerTotalPanier($panier) {
                $total = 0;
                foreach ($panier as $produit) {
                    $total += $produit['PRIX'] * $produit['QUANTITEPANIER'];
                }
                return $total;
            }

            // Gestion livraison
            $totalPanier = calculerTotalPanier($panier);
            $livraisonDomicile = ceil($totalPanier / 1000);
            $livraisonPointRelais = 0; // Gratuit
        ?>
        <div class="row gx-3 gx-lg-5">
            <!-- Choix de la livraison -->
            <div class="col-lg-8">
                <h2><?= htmlspecialchars($user['PRENOM']) ?>, choisissez votre livraison</h2>
                <div class="form-check mt-4">
                    <!-- Bouton radio domicile -->
                    <input class="form-check-input" type="radio" name="livraison" id="livraisonDomicile" value="domicile" checked>
                    <label class="form-check-label" for="livraisonDomicile">
                        Livraison à domicile (<?= $livraisonDomicile ?> €)
                    </label>
                    <div class="ms-4 mt-2">
                        <p><strong>Adresse :</strong> <?= $adresse ?></p>
                        <a href="#" class="btn btn-sm btn-outline-secondary">Modifier</a>
                    </div>
                </div>
                <div class="form-check mt-4">
                    <!-- Bouton radio point relais -->
                    <input class="form-check-input" type="radio" name="livraison" id="livraisonRelais" value="relais">
                    <label class="form-check-label" for="livraisonRelais">
                        Livraison en point relais (Gratuit)
                    </label>
                    <div class="ms-4 mt-2">
                        <ul class="list-group">
                            <li class="list-group-item">Point Relais 1 - 12 Rue Exemple, Paris</li>
                            <li class="list-group-item">Point Relais 2 - 34 Avenue Exemple, Lyon</li>
                            <li class="list-group-item">Point Relais 3 - 56 Boulevard Exemple, Marseille</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Récapitulatif -->
            <div class="col-lg-4">
                <h2>Récapitulatif</h2>
                <div class="card mb-4">
                    <div class="card-body">
                        <!-- Liste des produits -->
                        <h6 class="card-title"><strong>Articles</strong></h6>
                        <?php foreach ($panier as $produit): ?>
                            <p class="card-text">
                                <?php 
                                    if ($produit['QUANTITEPANIER'] > 1) {
                                        echo "x".$produit['QUANTITEPANIER'];
                                    }
                                    echo htmlspecialchars($produit['NOMPRODUIT'])." - ".number_format($produit['PRIX'] * $produit['QUANTITEPANIER'], 2)."€";
                                ?>
                            </p>
                        <?php endforeach; ?>

                        <!-- Livraison -->
                        <h6 class="card-title mt-2"><strong>Livraison</strong></h6>
                        <p class="card-text">
                            <span id="livraisonPrix"><?= number_format($livraisonDomicile, 2) ?> €</span>
                        </p>

                        <!-- Total commande -->
                        <h5 class="card-title mt-2"><strong>Total commande</strong></h5>
                        <p class="card-text">
                            <span id="totalPrix"><?= number_format($totalPanier + $livraisonDomicile, 2) ?> €</span>
                        </p>
                    </div>
                </div>
                <a href="paiement.php" class="btn btn-primary rounded-pill w-100">Continuer</a>
            </div>
        </div>
    </main>

    <?php require_once "./include/footer.php"; ?>
</body>
</html>
