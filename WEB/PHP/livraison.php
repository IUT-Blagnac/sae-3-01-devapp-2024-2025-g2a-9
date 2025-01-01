<?php
    $pageTitle = "Votre compte";
    require_once "./include/head.php";
?>
<body>
    <?php require_once "./include/header.php"; ?>
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
            $livraisonDomicile = ceil($totalPanier / 1000);
            $livraisonPointRelais = 0; // Gratuit
        ?>
        <div class="row gx-3 gx-lg-5">
            <div class="col-lg-8">
                <!-- Choix de la livraison -->
                <h2><?= htmlspecialchars($user['PRENOM']) ?>, choisissez votre livraison</h2>
                <div class="form-check mt-4">
                    <!-- Bouton radio domicile -->
                    <input class="form-check-input" type="radio" name="livraison" id="livraisonDomicile" value="domicile" checked>
                    <label class="form-check-label" for="livraisonDomicile">
                        Livraison à domicile (<?= $livraisonDomicile ?> €)
                    </label>
                    <div class="ms-4 mt-2">
                        <?php
                            if ($adresse) {
                                list($numRue, $libelleVoie, $codePostal, $ville) = explode(", ", $adresse);
                                echo "
                                    <li class=\"list-group-item d-flex justify-content-between align-items-center\">
                                        <span><strong>Adresse :</strong> $numRue $libelleVoie, $codePostal $ville</span>
                                        <a href=\"consultCompte.php\" class=\"btn btn-sm btn-outline-secondary rounded-pill\">Modifier</a>
                                    </li>";
                            } else {
                                echo "
                                    <li class=\"list-group-item d-flex justify-content-between align-items-center text-muted\">
                                        <span>Adresse non renseignée</span>
                                        <a href=\"consultCompte.php\" class=\"btn btn-sm btn-outline-secondary rounded-pill\">Modifier</a>
                                    </li>";
                            }
                        ?>
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
                            <?php
                                $reqPointRelais = $conn->prepare("SELECT * FROM POINTRELAIS;");
                                $reqPointRelais->execute();

                                foreach ($reqPointRelais as $pointRelais): 
                                    $idPointRelais = htmlspecialchars($pointRelais['IDPOINTRELAIS']);
                                    $adressePointRelais = htmlspecialchars($pointRelais['ADRESSEPOINTRELAIS']);
                            ?>
                                <li class="list-group-item d-flex align-items-center">
                                    <div class="ms-4">
                                        <!-- Bouton radio pour chaque point relais (désactivé par défaut) -->
                                        <input class="form-check-input" type="radio" name="pointRelais" id="<?= $idPointRelais ?>" value="<?= $idPointRelais ?>">
                                        <label class="form-check-label" for="<?= $idPointRelais ?>">
                                            <?= $adressePointRelais ?>
                                        </label>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>


                <!-- Choix du paiement -->
                <h2><?= htmlspecialchars($user['PRENOM']) ?>, choisissez votre mode de paiement</h2>
                <div class="form-check mt-4">
                    <!-- Bouton radio cb -->
                    <input class="form-check-input" type="radio" name="paiement" id="paiementCB" value="carteBancaire" checked>
                    <label class="form-check-label" for="paiementCB">Carte bancaire</label>
                    <div class="ms-4 mt-2">

                    </div>
                </div>
                <div class="form-check mt-4">
                    <!-- Bouton radio paypal -->
                    <input class="form-check-input" type="radio" name="paiement" id="paiementPaypal" value="paypal">
                    <label class="form-check-label" for="paiementPaypal">Paypal</label>
                    <div class="ms-4 mt-2">
                        
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
                    </div>
                </div>
                <a href="paiement.php" class="btn btn-primary rounded-pill w-100">Passez votre comande</a>
            </div>
        </div>
    </main>

    <?php require_once "./include/footer.php"; ?>

    <script>
        // Sélectionne les éléments nécessaires
        const livraisonRelais = document.getElementById('livraisonRelais');
        const livraisonDomicile = document.getElementById('livraisonDomicile');
        const pointRelaisRadios = document.querySelectorAll('input[name="pointRelais"]');
        const livraisonPrix = document.getElementById('livraisonPrix');
        const totalPrix = document.getElementById('totalPrix');

        // Prix fixes
        const prixLivraisonDomicile = <?= $livraisonDomicile ?>;
        const prixLivraisonRelais = <?= $livraisonPointRelais ?>;
        const totalPanier = <?= $totalPanier ?>;

        // Fonction pour mettre à jour les états
        function updateState() {
            if (livraisonRelais.checked) {
                // Activer les points relais
                pointRelaisRadios.forEach(radio => {
                    radio.disabled = false; // Activer
                });
                // Mettre à jour les prix
                livraisonPrix.textContent = prixLivraisonRelais.toFixed(2) + " €";
                totalPrix.textContent = (totalPanier + prixLivraisonRelais).toFixed(2) + " €";
            } else {
                // Désactiver les points relais
                pointRelaisRadios.forEach(radio => {
                    radio.disabled = true; // Désactiver
                    radio.checked = false; // Désélectionner
                });
                // Mettre à jour les prix
                livraisonPrix.textContent = prixLivraisonDomicile.toFixed(2) + " €";
                totalPrix.textContent = (totalPanier + prixLivraisonDomicile).toFixed(2) + " €";
            }
        }

        // Ajoute des écouteurs d'événements pour détecter les changements
        livraisonRelais.addEventListener('change', updateState);
        livraisonDomicile.addEventListener('change', updateState);

        // Mise à jour initiale
        updateState();
    </script>
</body>
</html>
