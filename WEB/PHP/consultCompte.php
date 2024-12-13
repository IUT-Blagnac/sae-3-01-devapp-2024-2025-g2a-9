<?php
    $pageTitle = "Votre compte";
    require_once "./include/head.php";
?>
<body>
    <?php
    require_once "./include/header.php";
    require_once "./include/menu.php";
    ?>
    <!-- Contenu principal -->
    <main role="main" class="container my-5">
        <?php
            $reqUser = $conn->prepare("SELECT * FROM UTILISATEUR WHERE idUtilisateur = ?;") ;
            $reqUser->execute([$_SESSION['user']]);
            if ($user = $reqUser->fetch()) {
                $nom = htmlspecialchars($user['NOM']);
                $prenom = htmlspecialchars($user['PRENOM']);
                $pays = htmlspecialchars($user['PAYS']);
                $dateN = htmlspecialchars($user['DATEN']);
                $civilite = htmlspecialchars($user['CIVILITE']);
                $email = htmlspecialchars($user['MAIL']);
                $telephone = htmlspecialchars($user['TELEPHONE']);
            }
        ?>
        <div class="row gx-3 gx-lg-5">
            <div class="col-3 me-3 me-lg-5">
                <!-- Card Utilisateur -->
                <div class="card mb-4">
                    <div class="d-flex flex-column align-items-center">
                        <i class="bi bi-person-circle fs-1 w-100 text-center"></i>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Bonjour, <strong><?php echo "$prenom";?></strong></h5>
                        <p class="card-text">Bienvenue sur votre espace personnel.</p>
                    </div>
                </div>
                <!-- Boutons navigation (tab) -->
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <button class="nav-link d-flex align-items-center mb-2 active" id="infoPersoTab" data-bs-toggle="tab" data-bs-target="#infoPersoPane" type="button" role="tab" aria-controls="infoPersoPane" aria-selected="true"><i class="bi bi-file-person me-3"></i>Informations personnelles</button>
                    <button class="nav-link d-flex align-items-center mb-2" id="commandesTab" data-bs-toggle="tab" data-bs-target="#commandesPane" type="button" role="tab" aria-controls="commandesPane" aria-selected="false"><i class="bi bi-cart-check me-3"></i>Mes commandes</button>
                    <button class="nav-link d-flex align-items-center mb-2" id="favorisTab" data-bs-toggle="tab" data-bs-target="#favorisPane" type="button" role="tab" aria-controls="favorisPane" aria-selected="false"><i class="bi bi-heart me-3"></i>Mes produits favoris</button>
                </div>
            </div>
            <div class="col-8">
                <!-- Contenu des tabs -->
                <div class="tab-content" id="tab-content" aria-orientation="vertical">
                    <!-- Tab infos personnelles -->
                    <div class="tab-pane fade show active" id="infoPersoPane" role="tabpanel" aria-labelledby="infoPersoTab">
                        <h2 class="mb-4">Voici vos informations personnelles :</h2>
                        <!-- Les infos générales -->
                        <div class="card mb-2 w-75">
                            <div class="card-body">
                                <h5 class="card-title">Informations générales</h5>
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><strong>Nom : </strong><?php echo "$prenom $nom"; ?></li>
                                <?php 
                                    if (isset($dateN)) {
                                        echo "<li class=\"list-group-item\"><strong>Date de naissance : </strong>".date('d/m/Y', strtotime($dateN))."</li>";
                                    }else {
                                        echo "<li class=\"list-group-item\"><strong>Date de naissance : </strong><p class=\"text-muted\" Aucune date donnée /p></li>";
                                    }
                                ?>
                                <li class="list-group-item"><strong>Civilité : </strong><?php echo "$civilite"; ?></li>
                            </ul>
                        </div>
                        <!-- Les coordonnées -->
                        <div class="card mb-2 w-75">
                            <div class="card-body">
                                <h5 class="card-title">Coordonnées</h5>
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><strong>E-mail : </strong><?php echo "$email"; ?></li>
                                <?php 
                                    if (isset($telephone)) {
                                        echo "<li class=\"list-group-item\"><strong>Numéro de téléphone : </strong>$telephone</li>";
                                    }else {
                                        echo "<li class=\"list-group-item\"><strong>Numéro de téléphone : </strong><p class=\"text-muted\" Aucun numméro attribué /p></li>";
                                    }
                                ?>
                                <?php 
                                    if (isset($pays)) {
                                        echo "<li class=\"list-group-item\"><strong>Pays : </strong>$pays</li>";
                                    }else {
                                        echo "<li class=\"list-group-item\"><strong>Pays : </strong><p class=\"text-muted\" Aucun pays/p></li>";
                                    }
                                ?>
                            </ul>
                        </div>
                        <!-- Les adresses de livraison si elles existent -->
                        <div class="card mb-4 w-75">
                            <div class="card-body">
                                <h5 class="card-title">Adresses de vos livraisons</h5>
                            </div>
                            <ul class="list-group list-group-flush">
                                <?php
                                    $reqAdresses = $conn->prepare("SELECT ADRESSELIVRAISON FROM COMMANDE WHERE IDUTILISATEUR = ? AND ADRESSELIVRAISON IS NOT NULL ORDER BY DATECOMMANDE;");
                                    $reqAdresses->execute([$_SESSION['user']]);

                                    $commandes = $reqAdresses->fetchAll();

                                    if (!empty($commandes)) {
                                        foreach ($commandes as $index => $commande) {
                                            echo "<li class='list-group-item'><strong>Adresse ".($index + 1)." : </strong>" . htmlspecialchars($commande['ADRESSELIVRAISON']) . "</li>";
                                        }
                                    } else {
                                        echo "<li class='list-group-item text-muted'>Aucune adresse disponible.</li>";
                                    }                                    
                                    $reqAdresses->closeCursor();
                                ?>
                            </ul>
                        </div>
                        <a class="btn btn-primary" href="modifierCompte.php" role="button">Changer mes infomations personnelles</a>
                    </div>
                    <!-- Tab commandes -->
                    <div class="tab-pane fade" id="commandesPane" role="tabpanel" aria-labelledby="commandesTab">
                        <h2 class="mb-4">Vos commandes :</h2> 
                        <!-- Selection de la commande -->
                        <div class="col d-flex justify-content-between align-items-center mb-3">
                            <p class="card-text">Choisissez votre commande :</p>
                            <form method="GET" id="commandeForm">
                                <select name="commande" class="form-select w-100" aria-label="Liste des commandes" onchange="document.getElementById('commandeForm').submit();">
                                    <?php
                                    $reqCommandes = $conn->prepare("SELECT * FROM COMMANDE WHERE IDUTILISATEUR = ? ORDER BY DATECOMMANDE DESC;");
                                    $reqCommandes->execute([$_SESSION['user']]);
                                    $selectedCommande = $_GET['commande'] ?? null;

                                    foreach ($reqCommandes as $index => $commande) {
                                        // Construire la valeur concaténé contenant les données de la commande
                                        $commandeData = implode('|', [
                                            $commande['IDCOMMANDE'],
                                            $commande['IDPAIEMENT'],
                                            $commande['DATECOMMANDE'],
                                            $commande['MODELIVRAISON'],
                                            $commande['ADRESSELIVRAISON'],
                                            $commande['IDPOINTRELAIS']
                                        ]);
                                        // Marquer la commande sélectionnée par défaut
                                        $selected = ($selectedCommande == $commandeData || (!$selectedCommande && $index == 0)) ? 'selected' : '';
                                        if (!$selectedCommande && $index == 0) {
                                            $selectedCommande = $commandeData;
                                        }
                                        echo "<option value='$commandeData' $selected>Commande du " . date('d/m/Y', strtotime($commande['DATECOMMANDE'])) . "</option>";
                                    }
                                    $reqCommandes->closeCursor();
                                    ?>
                                </select>
                            </form>
                        </div>
                        <!-- Affichage de la commande selectionnée -->
                        <?php if (!empty($selectedCommande)) : ?>
                            <?php
                            // Extraire les données de la commande sélectionnée
                            [$idCommande, $idPaiement, $dateCommande, $modeLivraison, $adresseLivraison, $idPointRelais] = explode('|', $selectedCommande);

                            // Requete pour le total commande et le mode de paiement
                            $reqPaiement = $conn->prepare("
                            SELECT PRIXCOMMANDE, MODEPAIEMENT FROM PAIEMENT WHERE IDPAIEMENT = ?;");
                            $reqPaiement->execute([$idPaiement]);
                            if ($paiement = $reqPaiement->fetch()) {
                                $prixCommande = $paiement['PRIXCOMMANDE'];
                                $modePaiement = $paiement['MODEPAIEMENT'];
                            }
                            
                            // Charger les détails de la commande
                            $reqDetail = $conn->prepare("
                                SELECT P.IDPRODUIT, P.NOMPRODUIT, P.PRIX, DC.QUANTITECOMMANDEE 
                                FROM DETAILCOMMANDE DC 
                                INNER JOIN PRODUIT P ON DC.IDPRODUIT = P.IDPRODUIT
                                WHERE DC.IDCOMMANDE = ?;");
                            $reqDetail->execute([$idCommande]);

                            // Requete pour connaitre l'adresse de livraison (celle du point relais ou du client)
                            if ($modeLivraison !== "Domicile") {
                                $reqPointRelais = $conn->prepare("SELECT ADRESSEPOINTRELAIS FROM POINTRELAIS WHERE IDPOINTRELAIS = ?;");
                                $reqPointRelais->execute([$idPointRelais]);
                                if ($relais = $reqPointRelais->fetch()) {
                                    $adresseLivraison = $relais['ADRESSEPOINTRELAIS'];
                                }
                            }
                            ?>
                            <!-- Le détail de la commande -->
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Détails de la commande</h5>
                                    <p class="card-text">
                                        Commande passée le <?php echo date('d/m/Y', strtotime($dateCommande)); ?><br>
                                        <?php echo number_format($prixCommande, 2, ',', ' '); ?>€ payé via <?php echo htmlspecialchars($modePaiement); ?>.
                                    </p>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <strong>Livraison
                                        <?php if ($modeLivraison === "Domicile"): ?>
                                            à domicile : </strong><?php echo htmlspecialchars($adresseLivraison); ?>.
                                        <?php else: ?>
                                            en point relais : </strong><?php echo htmlspecialchars($adresseLivraison); ?>.
                                        <?php endif; ?>
                                    </li>
                                    <?php foreach ($reqDetail as $produit) : ?>
                                        <li class="list-group-item">
                                            <div class="d-flex justify-content-start">
                                                <div class="me-4">
                                                    <a href="detailProduit.php?id=<?php echo $produit['IDPRODUIT']; ?>"><img src="./image/produit/test<?= htmlspecialchars($produit['IDPRODUIT']); ?>.png" style="max-width: 100px; height:auto;"/></a>
                                                </div>
                                                <div>
                                                    <strong><a href="detailProduit.php?id=<?php echo $produit['IDPRODUIT']; ?>" style="color:black; text-decoration: none;"><?php echo htmlspecialchars($produit['NOMPRODUIT']); ?></a></strong><br>
                                                    <!-- Peut etre ajouté des étoiles pour les avis --><a href="#">Donnez votre avis</a><br>
                                                    <?php echo number_format($produit['PRIX'] * $produit['QUANTITECOMMANDEE'], 2, ',', ' '); ?> €<br>
                                                    Quantité : <?php echo $produit['QUANTITECOMMANDEE']; ?><br>
                                                </div>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <?php
                            $reqDetail->closeCursor();
                            if (isset($reqPointRelais)) $reqPointRelais->closeCursor();
                            ?>
                        <?php else : ?>
                            <p class="text-muted">Aucune commande disponible pour l'instant.</p>
                        <?php endif; ?>
                    </div>
                    <!-- Tab favoris -->
                    <div class="tab-pane fade" id="favorisPane" role="tabpanel" aria-labelledby="favorisTab">
                        <h2 class="mb-4">Vos articles favoris :</h2>
                        <?php
                            $reqFavoris = $conn->prepare("SELECT * FROM FAVORI WHERE idUtilisateur = ?;") ;
                            $reqFavoris->execute([$_SESSION['user']]);
                            $favoris = $reqFavoris->fetchAll();
                        ?>
                        <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Favoris</h5>
                                    <p class="card-text">
                                        Voici les produits que vous avez définis favoris.<br> 
                                        Vous pouvez supprimer un produit de vos favoris directement depuis sa page.
                                    </p>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <?php if (!empty($favoris)): ?>
                                        <?php foreach ($favoris as $fav): ?>
                                            <li class="list-group-item">
                                            <?php 
                                            $reqProduits = $conn->prepare("SELECT IDPRODUIT, NOMPRODUIT, PRIX FROM PRODUIT WHERE IDPRODUIT = ?;") ;
                                            $reqProduits->execute([$fav['IDPRODUIT']]); 
                                            ?>
                                            <?php foreach ($reqProduits as $produit): ?>
                                                <div class="d-flex justify-content-start">
                                                    <div class="me-4">
                                                        <a href="detailProduit.php?id=<?php echo $produit['IDPRODUIT']; ?>"><img src="./image/produit/test<?= htmlspecialchars($produit['IDPRODUIT']); ?>.png" style="max-width: 100px; height:auto;"/></a>
                                                    </div>
                                                    <div>
                                                        <strong><a href="detailProduit.php?id=<?php echo $produit['IDPRODUIT']; ?>" style="color:black; text-decoration: none;"><?php echo htmlspecialchars($produit['NOMPRODUIT']); ?></a></strong><br><br>
                                                        <!-- Peut etre ajouté des étoiles pour les avis -->
                                                        <?php echo number_format($produit['PRIX'], 2, ',', ' '); ?> €<br>
                                                    </div>
                                                </div>
                                            </li>
                                            <?php endforeach; ?>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <li class="list-group-item text-muted">Aucun produits favoris.</li>";
                                    <?php endif; ?>
                                            

                                            
                                </ul>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Pied de page -->
    <?php require_once "./include/footer.php"; ?>
</body>
</html>