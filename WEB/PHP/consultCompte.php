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
                                <li class="list-group-item"><strong>Date de naissance : </strong><?php echo date('d/m/Y', strtotime($dateN)); ?></li>
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
                            </ul>
                        </div>
                        <!-- Les adresses de livraison si elles existent -->
                        <div class="card mb-2 w-75">
                            <div class="card-body">
                                <h5 class="card-title">Adresses</h5>
                            </div>
                            <ul class="list-group list-group-flush">
                                <?php
                                    $reqAdresses = $conn->prepare("SELECT ADRESSELIVRAISON FROM COMMANDE WHERE IDUTILISATEUR = ? AND ADRESSELIVRAISON IS NOT NULL ORDER BY DATECOMMANDE;");
                                    $reqAdresses->execute([$_SESSION['user']]);

                                    $hasAddresses = false;
                                    $iAdresse = 1;
                                    foreach ($reqAdresses as $commande) {
                                        $hasAddresses = true;
                                        $adresse = htmlspecialchars($commande['ADRESSELIVRAISON']);
                                        echo "<li class='list-group-item'><strong>Adresse $iAdresse : </strong>$adresse</li>";
                                        $iAdresse++;
                                    }

                                    if (!$hasAddresses) { // Si il n'y a pas d'adresse
                                        echo "<li class='list-group-item text-muted'>Aucune adresse disponible.</li>";
                                    }

                                    $reqAdresses->closeCursor();
                                ?>
                            </ul>
                        </div>   
                    </div>
                    <div class="tab-pane fade" id="commandesPane" role="tabpanel" aria-labelledby="commandesTab">
                        <h2 class="mb-4">Vos commandes :</h2> 
                        <div class="col d-flex justify-content-between align-items-center mb-2">
                            <p class="card-text">Choisissez votre commande :</p>
                            <form method="GET" id="commandeForm">
                                <select name="commande" class="form-select w-100" aria-label="Liste des commandes" onchange="document.getElementById('commandeForm').submit();"> <!-- Pour avoir la commande sélectionné par défaut affiché -->
                                    <?php
                                    $reqCommandes = $conn->prepare("SELECT * FROM COMMANDE WHERE IDUTILISATEUR = ? ORDER BY DATECOMMANDE DESC;");
                                    $reqCommandes->execute([$_SESSION['user']]);
                                    $hasCommande = false;
                                    $selectedCommande = $_GET['commande'] ?? null; // Commande sélectionnée via le GET sinon null
                                    foreach ($reqCommandes as $index => $commande) {
                                        $hasCommande = true;
                                        // Définir la commande sélectionnée (dernière par défaut si aucune sélectionnée)
                                        $selected = ($selectedCommande == $commande['IDCOMMANDE'] || (!$selectedCommande && $index == 0)) ? 'selected' : '';
                                        if (!$selectedCommande && $index == 0) {
                                            $selectedCommande = $commande['IDCOMMANDE']; // Mettre à jour pour afficher la commande par défaut
                                        }
                                        echo "<option value='" . $commande['IDCOMMANDE'] . "' $selected>Commande du " . date('d/m/Y', strtotime($commande['DATECOMMANDE'])) . "</option>";
                                    }
                                    $reqCommandes->closeCursor();
                                    ?>
                                </select>
                            </form>
                        </div>
                        <!-- Si il y a une commande -->
                        <?php if ($hasCommande) : ?>
                            <?php
                            // Charger les détails de la commande sélectionnée
                            $reqDetail = $conn->prepare("
                                SELECT P.NOMPRODUIT, P.PRIX, P.DESCRIPTION, DC.QUANTITECOMMANDEE 
                                FROM DETAILCOMMANDE DC 
                                INNER JOIN PRODUIT P ON DC.IDPRODUIT = P.IDPRODUIT
                                WHERE DC.IDCOMMANDE = ?;
                            ");
                            $reqDetail->execute([$selectedCommande]);
                            ?>
                            
                            <div class="card mt-3">
                                <div class="card-body">
                                    <h5 class="card-title">Détails de la commande</h5>
                                    <p class="card-text">Voici les articles de votre commande :</p>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <?php foreach ($reqDetail as $produit) : ?>
                                        <li class="list-group-item">
                                            <a href="detailProduit?id=<?php echo $produit['IDPRODUIT']; ?>"><?php echo htmlspecialchars($produit['NOMPRODUIT']); ?></a><br>
                                            Prix du produit : <?php echo number_format($produit['PRIX'], 2, ',', ' '); ?> €<br>
                                            Quantité : <?php echo $produit['QUANTITECOMMANDEE']; ?><br>
                                            Description : <?php echo htmlspecialchars($produit['DESCRIPTION']); ?><br>
                                            Total pour ce produit : <?php echo number_format($produit['PRIX'], 2, ',', ' ') * $produit['QUANTITECOMMANDEE']; ?>
                                        </li>
                                    <?php 
                                        endforeach; 
                                        $reqDetail->closeCursor(); 
                                    ?>
                                </ul>
                            </div>
                        <?php else : ?>
                            <p>Aucune commande disponible pour l'instant.</p>
                        <?php endif; ?>
                    <div class="tab-pane fade" id="favorisPane" role="tabpanel" aria-labelledby="favorisTab">
                        <h2 class="mb-4">Vos articles favoris :</h2>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Pied de page -->
    <?php require_once "./include/footer.php"; ?>
</body>
</html>