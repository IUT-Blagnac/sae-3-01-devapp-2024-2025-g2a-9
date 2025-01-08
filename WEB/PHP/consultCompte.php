<?php
    $pageTitle = "Votre compte";
    require_once "./include/head.php";
?>
<style>
    .is-invalid {
    border: 1px solid red;
    background-color: #fdd;
}

.is-valid {
    border: 1px solid green;
    background-color: #dfd;
}
</style>
<body>
    <?php
        require_once "./include/header.php";
        require_once "./include/isLogin.php";
        require_once "./include/menu.php";
    ?>
    <!-- Contenu principal -->
    <main role="main" class="container my-5">
        <?php
            require_once "./include/connect.inc.php";
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
                $adresse = htmlspecialchars($user['ADRESSE']);
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
                    <button class="nav-link d-flex align-items-center mb-2 <?php echo (isset($_POST['form_name']) && $_POST['form_name'] === 'commandeForm') ? '' : 'active'; ?>" id="infoPersoTab" data-bs-toggle="tab" data-bs-target="#infoPersoPane" type="button" role="tab" aria-controls="infoPersoPane" aria-selected="true"><i class="bi bi-file-person me-3"></i>Informations personnelles</button>
                    <button class="nav-link d-flex align-items-center mb-2 <?php echo (isset($_POST['form_name']) && $_POST['form_name'] === 'commandeForm') ? 'active' : ''; ?>" id="commandesTab" data-bs-toggle="tab" data-bs-target="#commandesPane" type="button" role="tab" aria-controls="commandesPane" aria-selected="false"><i class="bi bi-cart-check me-3"></i>Mes commandes</button>
                    <button class="nav-link d-flex align-items-center mb-2" id="favorisTab" data-bs-toggle="tab" data-bs-target="#favorisPane" type="button" role="tab" aria-controls="favorisPane" aria-selected="false"><i class="bi bi-heart me-3"></i>Mes produits favoris</button>
                </div>
            </div>
            <div class="col-8">
                <!-- Contenu des tabs -->
                <div class="tab-content" id="tab-content" aria-orientation="vertical">
                    <!-- Tab infos personnelles -->
                    <div class="tab-pane fade <?php echo (isset($_POST['form_name']) && $_POST['form_name'] === 'commandeForm') ? '' : 'show active'; ?>" id="infoPersoPane" role="tabpanel" aria-labelledby="infoPersoTab">
                        <h2 class="mb-4">Voici vos informations personnelles :</h2>
                            <?php
                                if(isset($_GET['msgErreur'])){
                                    echo '<div class="alert alert-danger w-50" role="alert">';
                                        echo '<strong>Un problème est survenu</strong><br>';
                                        echo htmlentities($_GET['msgErreur']);
                                    echo '</div>';
                                }
                                if(isset($_GET['msgSucces'])){
                                    echo '<div class="alert alert-success w-50" role="alert">';
                                        echo htmlentities($_GET['msgSucces']);
                                    echo '</div>';
                                }
                            ?>
                            <form method="post" action="modifierCompte.php">
                                <input type="hidden" name="action" value="updateInfo">
                                <!-- Les infos générales -->
                                <div class="card mb-2 w-75">
                                    <div class="card-body">
                                        <h5 class="card-title">Informations générales</h5>
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <!-- Nom et prénom -->
                                        <li class="list-group-item d-flex align-items-center">
                                            <strong class="me-2">Nom :</strong> 
                                            <input type="text" name="nom" class="form-control w-50" value="<?php echo htmlspecialchars($nom); ?>" maxlength="50">
                                        </li>
                                        <li class="list-group-item d-flex align-items-center">
                                            <strong class="me-2">Prénom :</strong> 
                                            <input type="text" name="prenom" class="form-control w-50" value="<?php echo htmlspecialchars($prenom); ?>" maxlength="50">
                                        </li>
                                        <!-- Date de naissance -->
                                        <li class="list-group-item d-flex align-items-center">
                                            <strong class="me-2">Date de naissance :</strong>
                                            <input type="date" name="dateN" class="form-control w-50" value="<?php echo isset($dateN) ? htmlspecialchars($dateN) : ''; ?>">
                                        </li>
                                        <!-- Civilité -->
                                        <li class="list-group-item d-flex align-items-center">
                                            <strong class="me-2">Civilité :</strong>
                                            <select name="civilite" class="form-select w-50">
                                                <option value="MR" <?= $civilite === 'MR' ? 'selected' : '' ?>>Mr</option>
                                                <option value="MME" <?= $civilite === 'MME' ? 'selected' : '' ?>>Mme</option>
                                            </select>
                                        </li>
                                    </ul>
                                </div>
                                <!-- Les coordonnées -->
                                <div class="card mb-2 w-75">
                                    <div class="card-body">
                                        <h5 class="card-title">Coordonnées</h5>
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <!-- E-mail -->
                                        <li class="list-group-item d-flex align-items-center">
                                            <strong class="me-2">E-mail :</strong>
                                            <input type="email" name="email" class="form-control w-50" value="<?php echo htmlspecialchars($email); ?>" maxlength="50" required>
                                        </li>
                                        <!-- Numéro de téléphone -->
                                        <li class="list-group-item d-flex align-items-center">
                                            <strong class="me-2">Numéro de téléphone :</strong>
                                            <input type="tel" name="telephone" class="form-control w-50" value="<?php echo isset($telephone) ? htmlspecialchars($telephone) : ''; ?>" maxlength="15">
                                        </li>
                                        <!-- Pays -->
                                        <li class="list-group-item d-flex align-items-center">
                                            <strong class="me-2">Pays :</strong>
                                            <input type="text" name="pays" class="form-control w-50" value="<?php echo isset($pays) ? htmlspecialchars($pays) : ''; ?>" maxlength="30">
                                        </li>
                                    </ul>
                                </div>
                                <!-- Adresse -->
                                <div class="card mb-2 w-75">
                                    <div class="card-body">
                                        <h5 class="card-title">Adresse</h5>
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <?php
                                            if ($adresse) { 
                                                // Décomposer l'adresse en champs individuels
                                                list($numRue, $libelleVoie, $codePostal, $ville) = explode(", ", $adresse);

                                                echo "<li class=\"list-group-item\"><strong>Adresse : </strong>$numRue $libelleVoie, $codePostal $ville</li>";
                                            ?>
                                                <li class="list-group-item d-flex align-items-center">
                                                    <div>
                                                        <strong>Numéro de rue :</strong>
                                                        <input type="text" name="numRue" class="form-control w-25" value="<?php echo htmlspecialchars($numRue) ?>" maxlength="3">
                                                    </div>
                                                    <div>
                                                        <strong>Libellé de voie :</strong>
                                                        <input type="text" name="libelleVoie" class="form-control w-100" value="<?php echo htmlspecialchars($libelleVoie) ?>" maxlength="20">
                                                    </div>  
                                                </li>
                                                <li class="list-group-item d-flex align-items-center">
                                                    <div>
                                                        <strong>Code postal :</strong>
                                                        <input type="text" name="codePostal" class="form-control w-50" value="<?php echo htmlspecialchars($codePostal) ?>" maxlength="5">
                                                    </div>
                                                    <div>
                                                        <strong>Ville :</strong>
                                                        <input type="text" name="ville" class="form-control w-100" value="<?php echo htmlspecialchars($ville) ?>" maxlength="18">
                                                    </div>  
                                                </li>
                                            <?php } else { ?>
                                                <li class="list-group-item d-flex align-items-center">
                                                    <div>
                                                        <strong>Numéro de rue :</strong>
                                                        <input type="text" name="numRue" class="form-control w-25" value="" maxlength="3">
                                                    </div>
                                                    <div>
                                                        <strong>Libellé de voie :</strong>
                                                        <input type="text" name="libelleVoie" class="form-control w-100" value="" maxlength="20">
                                                    </div>  
                                                </li>
                                                <li class="list-group-item d-flex align-items-center">
                                                    <div>
                                                        <strong>Code postal :</strong>
                                                        <input type="text" name="codePostal" class="form-control w-50" value="" maxlength="5">
                                                    </div>
                                                    <div>
                                                        <strong>Ville :</strong>
                                                        <input type="text" name="ville" class="form-control w-100" value="" maxlength="18">
                                                    </div>  
                                                </li>
                                            <?php } ?>
                                    </ul>
                                </div>
                                <div class="d-flex justify-content-start mt-4">
                                    <button type="submit" name="submit" class="btn btn-primary rounded-pill w-35 me-5">Modifier mes informations</button>
                                    <a href="modifierCompte.php" class="btn btn-primary rounded-pill w-35 ms-5">Modifier le mot de passe</a>
                                </div>
                            </form>
                    </div>
                    <!-- Tab commandes -->
                    <div class="tab-pane fade <?php echo (isset($_POST['form_name']) && $_POST['form_name'] === 'commandeForm') ? 'show active' : ''; ?>" id="commandesPane" role="tabpanel" aria-labelledby="commandesTab">
                        <h2 class="mb-4">Vos commandes :</h2> 
                        <!-- Selection de la commande -->
                        <div class="col d-flex justify-content-between align-items-center mb-3">
                            <p class="card-text">Choisissez votre commande :</p>
                            <form method="GET" id="commandeForm">
                                <input type="hidden" name="tab" value="commandes">
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
                                                    <a href="detailProduit.php?id=<?php echo $produit['IDPRODUIT']; ?>"><img src="./image/produit/prod<?= htmlspecialchars($produit['IDPRODUIT']); ?>.png" style="max-width: 100px; height:auto;"/></a>
                                                </div>
                                                <div>
                                                    <strong><a href="detailProduit.php?id=<?php echo $produit['IDPRODUIT']; ?>" style="color:black; text-decoration: none;"><?php echo htmlspecialchars($produit['NOMPRODUIT']); ?></a></strong><br>
                                                    <!-- Peut etre ajouté des étoiles pour les avis --><a href="detailProduit.php?id=<?php echo $produit['IDPRODUIT']; ?>">Donnez votre avis</a><br>
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
                                                    <a href="detailProduit.php?id=<?php echo $produit['IDPRODUIT']; ?>"><img src="./image/produit/prod<?= htmlspecialchars($produit['IDPRODUIT']); ?>.png" style="max-width: 100px; height:auto;"/></a>
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Récupérer les paramètres URL
            const urlParams = new URLSearchParams(window.location.search);
            const tab = urlParams.get('tab');

            // Activer l'onglet correspondant
            if (tab) {
                const activeTab = document.querySelector(`[data-bs-target="#${tab}Pane"]`);
                const activePane = document.getElementById(`${tab}Pane`);

                if (activeTab && activePane) {
                    // Désactiver l'onglet actif par défaut
                    document.querySelector('.nav-link.active').classList.remove('active');
                    document.querySelector('.tab-pane.active').classList.remove('show', 'active');

                    // Activer l'onglet spécifié
                    activeTab.classList.add('active');
                    activePane.classList.add('show', 'active');
                }
            }
        });
        document.addEventListener('DOMContentLoaded', function () {
            // Validation en temps réel
            const form = document.querySelector('form');
            const inputs = form.querySelectorAll('input, select');

            // Fonction pour vérifier un champ
            function validateField(field) {
                let isValid = true;
                const value = field.value.trim();

                // Validation spécifique pour chaque champ
                switch (field.name) {
                    case 'nom':
                    case 'prenom':
                    case 'ville':
                    case 'libelleVoie':
                        isValid = /^[a-zA-ZÀ-ÿ\s-]+$/.test(value); // Lettres uniquement
                        break;

                    case 'numRue':
                        isValid = /^[0-9]{1,3}$/.test(value); // 1 à 3 chiffres uniquement
                        break;

                    case 'codePostal':
                        isValid = /^[0-9]{5}$/.test(value); // 5 chiffres
                        break;

                    case 'email':
                        isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value); // Email valide
                        break;

                    case 'telephone':
                        isValid = /^[0-9]{10}$/.test(value); // 10 chiffres
                        break;

                    case 'pays':
                        isValid = /^[a-zA-ZÀ-ÿ\s-]{30}$/.test(value); // Lettres uniquement, 1 à 30 caractères
                        break;

                    default:
                        break;
                }

                // Affichage des erreurs
                if (!isValid) {
                    field.classList.add('is-invalid');
                    field.classList.remove('is-valid');
                } else {
                    field.classList.remove('is-invalid');
                    field.classList.add('is-valid');
                }

                return isValid;
            }

            // Vérification sur chaque champ
            inputs.forEach(input => {
                input.addEventListener('input', () => validateField(input));
            });

            // Validation globale avant envoi du formulaire
            form.addEventListener('submit', function (event) {
                let isFormValid = true;

                inputs.forEach(input => {
                    if (!validateField(input)) {
                        isFormValid = false;
                    }
                });

                if (!isFormValid) {
                    event.preventDefault(); // Empêche l'envoi si le formulaire est invalide
                    alert('Veuillez corriger les champs invalides avant de soumettre le formulaire.');
                }
            });
        });
    </script>
</body>
</html>