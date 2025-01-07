<?php
    $pageTitle = "Informations de commande";
    require_once "./include/head.php";
?>
<body>
    <?php 
    require_once "./include/header.php"; 
    require_once "./include/isLogin.php";
    ?>
    
    <!-- Contenu principal -->
    <main role="main" class="container my-5">
        <?php
            require_once "./include/connect.inc.php";
            $reqUser = $conn->prepare("SELECT * FROM UTILISATEUR WHERE idUtilisateur = ?");
            $reqUser->execute([$_SESSION['user']]);
            $user = $reqUser->fetch();
            $adresse = htmlspecialchars($user['ADRESSE']);

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
            $livraisonDomicile = ceil($totalPanier / 1000); // Arrondi sup du total par 1000
            $livraisonPointRelais = 0; // Gratuit
        ?>
        <div class="row gx-3 gx-lg-5">
            <div class="col-lg-8">
                <!-- Choix de la livraison -->
                <h2><?= htmlspecialchars($user['PRENOM']) ?>, choisissez votre livraison</h2>

                <!-- Bouton radio domicile -->
                <div class="card mt-4">
                    <div class="card-header">
                        <div class="ms-4">
                            <input class="form-check-input" type="radio" name="livraison" id="livraisonDomicile" value="domicile" checked>
                            <label class="form-check-label fw-bold" for="livraisonDomicile">
                                Livraison à domicile (<?= $livraisonDomicile ?> €)
                            </label>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="ms-2 mt-2">
                            <?php
                                if ($adresse) {
                                    list($numRue, $libelleVoie, $codePostal, $ville) = explode(", ", $adresse);
                                    echo "
                                        <div class=\"d-flex justify-content-between align-items-center\">
                                            <span><strong>Adresse :</strong> $numRue $libelleVoie, $codePostal $ville</span>
                                            <a href=\"consultCompte.php\" class=\"btn btn-sm btn-outline-secondary rounded-pill\">Modifier</a>
                                        </div>";
                                } else {
                                    echo "
                                        <div class=\"d-flex justify-content-between align-items-center text-muted\">
                                            <span>Adresse non renseignée</span>
                                            <a href=\"consultCompte.php\" class=\"btn btn-sm btn-outline-secondary rounded-pill\">Modifier</a>
                                        </div>";
                                }
                            ?>
                        </div>
                    </div>
                </div>
                <!-- Bouton radio point relais -->
                <div class="card mt-4">
                    <div class="card-header">
                        <div class="ms-4">
                            <input class="form-check-input" type="radio" name="livraison" id="livraisonRelais" value="relais">
                            <label class="form-check-label fw-bold" for="livraisonRelais">
                                Livraison en point relais (Gratuit)
                            </label>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php
                            $reqPointRelais = $conn->prepare("SELECT * FROM POINTRELAIS;");
                            $reqPointRelais->execute();

                            foreach ($reqPointRelais as $pointRelais): 
                                $idPointRelais = htmlspecialchars($pointRelais['IDPOINTRELAIS']);
                                $adressePointRelais = htmlspecialchars($pointRelais['ADRESSEPOINTRELAIS']);
                        ?>
                            <div class="d-flex align-items-center ms-3">
                                <!-- Bouton radio pour chaque point relais (désactivé par défaut) -->
                                <input class="form-check-input" type="radio" name="pointRelais" id="<?= $idPointRelais ?>" value="<?= $idPointRelais ?>">
                                <label class="form-check-label" for="<?= $idPointRelais ?>">
                                    <?= $adressePointRelais ?>
                                </label>
                            </div>
                            <div class="w-100" style="border-top: 1px solid #ccc; margin: 20px 0;"></div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="w-100" style="border-top: 1px solid #ccc; margin: 20px 0;"></div>

                <!-- Choix du paiement -->
                <h2 class="mt-5"><?= htmlspecialchars($user['PRENOM']) ?>, choisissez votre mode de paiement</h2>
                <!-- Carte bancaire -->
                <div class="card mt-4">
                    <div class="card-header">
                        <div class="ms-4">
                            <input class="form-check-input me-2" type="radio" name="paiement" id="paiementCB" value="carteBancaire" checked>
                            <label class="form-check-label fw-bold" for="paiementCB">Carte bancaire</label>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="formCarteBancaire">
                            <input type="text" id="numCarte" class="form-control mb-2 w-75" placeholder="Numéro de carte (16 chiffres)">
                            <div class="d-flex gap-2 w-50">
                                <input type="text" id="dateExpiration" class="form-control mb-2 rounded-pill" placeholder="MM/AA">
                                <input type="text" id="cryptogramme" class="form-control mb-2" placeholder="Cryptogramme">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PayPal -->
                <div class="card mt-4">
                    <div class="card-header">
                        <div class="ms-4">
                            <input class="form-check-input me-2" type="radio" name="paiement" id="paiementPaypal" value="paypal">
                            <label class="form-check-label fw-bold" for="paiementPaypal">PayPal</label>
                        </div>
                    </div>
                    <div class="card-body d-none" id="formPaypal">
                        <input type="email" id="emailPaypal" class="form-control" placeholder="Adresse e-mail PayPal">
                    </div>
                </div>
            </div>

            <!-- Récapitulatif -->
            <div class="col-lg-4">
                <h2>Récapitulatif</h2>
                <div class="card mb-4">
                    <div class="card-body">
                        <!-- Liste des produits -->
                        <h5 class="card-title"><strong>Articles</strong></h5>
                        <?php foreach ($panier as $produit): ?>
                            <p class="card-text my-0">
                                <?php 
                                    if ($produit['QUANTITEPANIER'] > 1) {
                                        echo "x".$produit['QUANTITEPANIER']." ";
                                    }
                                    echo "<strong>".htmlspecialchars($produit['NOMPRODUIT'])."</strong> - ".number_format($produit['PRIX'] * $produit['QUANTITEPANIER'], 2)."€";
                                ?>
                            </p>
                        <?php endforeach; ?>

                        <!-- Livraison -->
                        <h5 class="card-title mt-4"><strong>Livraison</strong></h5>
                        <p class="card-text my-0">
                            <span id="livraisonPrix"><?= number_format($livraisonDomicile, 2) ?> €</span>
                        </p>

                        <!-- Total commande -->
                        <h4 class="card-title mt-4"><strong>Total commande</strong></h4>
                        <p class="card-text my-0">
                            <span id="totalPrix"><?= number_format($totalPanier + $livraisonDomicile, 2) ?> €</span>
                        </p>
                        <script>
                            const prixLivraisonDomicile = <?= $livraisonDomicile ?>;
                            const prixLivraisonRelais = <?= $livraisonPointRelais ?>;
                            const totalPanier = <?= $totalPanier ?>;
                        </script>
                    </div>
                </div>
                <button type="submit" name="submit" class="btn btn-primary rounded-pill w-100">Passez votre comande</button>
            </div>
        </div>
        <?php
            if (isset($_POST['submit'])) {
                // Récupération des données
                $modeLivraison = htmlentities($_POST['livraison']);
                $modePaiement = htmlentities($_POST['paiement']);
                $idUtilisateur = $_SESSION['user'];
                $adresseLivraison = null;
                $idPointRelais = null;

                // Gestion de l'adresse ou du point relais en fonction du mode de livraison
                if ($modeLivraison === 'domicile') {
                    $adresseLivraison = "$numRue $libelleVoie, $codePostal $ville"; // Utiliser l'adresse utilisateur existante
                } else if ($modeLivraison === 'relais') {
                    $idPointRelais = intval($_POST['pointRelais']); // ID du point relais sélectionné
                }
                if ($modePaiement === 'carteBancaire') {
                    $modePaiement = 'Carte Bancaire';
                } else if ($modePaiement === 'paypal') {
                    $modePaiement = 'Paypal';
                }

                // Préparation et exécution de la procédure
                $reqInsertCommande = $conn->prepare("CALL CreerCommande(
                    :idUtilisateur, :modeLivraison, :adresseLivraison, :idPointRelais, :modePaiement
                )");
                $reqInsertCommande->execute([
                    ':idUtilisateur' => $idUtilisateur,
                    ':modeLivraison' => $modeLivraison,
                    ':adresseLivraison' => $adresseLivraison,
                    ':idPointRelais' => $idPointRelais,
                    ':modePaiement' => $modePaiement,
                ]);
                header("location:consultCompte.php?tab=commandes");
                exit;
            }
        ?>
    </main>

    <?php require_once "./include/footer.php"; ?>
    <script src="javascript/script.js"></script>
</body>
</html>
