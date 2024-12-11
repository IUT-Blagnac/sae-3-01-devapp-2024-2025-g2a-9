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

        <div class="row gx-3 gx-lg-5">
            <div class="col-3 me-3 me-lg-5">
                <ul class="nav nav-tabs nav-tabs-vertical" role="tablist">
                    <li class="nav-item mb-2" role="presentation">
                        <a class="nav-link active" id="infoPersoTab" data-bs-toggle="tab" href="#infoPersoPane" role="tab" aria-controls="infoPersoPane" aria-selected="true">Mes informations personnelles</a>
                    </li>
                    <li class="nav-item mb-2" role="presentation">
                        <a class="nav-link" id="commandesTab" data-bs-toggle="tab" href="#commandesPane" role="tab" aria-controls="commandesPane" aria-selected="false">Mes commandes</a>
                    </li>
                    <li class="nav-item mb-2" role="presentation">
                        <a class="nav-link" id="favorisTab" data-bs-toggle="tab" href="#favorisPane" role="tab" aria-controls="favorisPane" aria-selected="false">Mes produits favoris</a>
                    </li>
                </ul>
            </div>
            <div class="col-8">
                <div class="tab-content" id="tab-content" aria-orientation="vertical">
                    <div class="tab-pane active" id="infoPersoPane" role="tabpanel" aria-labelledby="infoPersoTab">
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
                        <p class="card-text mb-5">Voici vos informations personnelles :</p>
                        <div class="card mb-2 w-75">
                            <div class="card-body">
                                <h5 class="card-title">Informations générales</h5>
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">Nom : <?php echo "$prenom $nom"; ?></li>
                                <li class="list-group-item">Date de naissance : <?php echo "$dateN"; ?></li>
                                <li class="list-group-item">Civilité : <?php echo "$civilite"; ?></li>
                            </ul>
                        </div>
                        <div class="card mb-2 w-75">
                            <div class="card-body">
                                <h5 class="card-title">Coordonnées</h5>
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">E-mail : <?php echo "$email"; ?></li>
                                <li class="list-group-item">Numéro de téléphone : <?php echo "$telephone"; ?></li>
                            </ul>
                        </div>
                        <div class="card mb-2 w-75">
                            <div class="card-body">
                                <h5 class="card-title">Adresses</h5>
                            </div>
                            <ul class="list-group list-group-flush">
                                <?php
                                    $reqAdresses = $conn->prepare("SELECT adresseLivraison FROM COMMANDE WHERE idUtilisateur = ? AND adresseLivraison IS NOT NULL ORDER BY dateCommande;");
                                    $reqAdresses->execute([$_SESSION['user']]);

                                    $hasAddresses = false;
                                    foreach ($reqAdresses as $commande) {
                                        $hasAddresses = true;
                                        $adresse = htmlspecialchars($commande['ADRESSELIVRAISON']);
                                        echo "<li class='list-group-item'>Adresse : $adresse</li>";
                                    }

                                    if (!$hasAddresses) { // Si il n'y a pas d'adresse
                                        echo "<li class='list-group-item text-muted'>Aucune adresse disponible.</li>";
                                    }

                                    $reqAdresses->closeCursor();
                                ?>
                            </ul>
                        </div>   
                    </div>
                    <div class="tab-pane" id="commandesPane" role="tabpanel" aria-labelledby="commandesTab">
                        <div class="col d-flex justify-content-between mb-5">
                            <p class="card-text">Choisissez votre commande :</p>
                            <form method="GET" id="commandeForm">
                                <select name="commande" class="form-select w-25" aria-label="Liste des commandes" onchange="document.getElementById('commandeForm').submit();"> <!-- Pour avoir la commande sélectionné par défaut affiché -->
                                    <?php
                                    $reqCommandes = $conn->prepare("SELECT * FROM COMMANDE WHERE idUtilisateur = ? ORDER BY dateCommande DESC;");
                                    $reqCommandes->execute([$_SESSION['user']]);
                                    
                                    $hasCommandes = false;
                                    $selectedCommande = $_GET['commande'] ?? null; // Commande sélectionnée via le GET sinon null
                                    foreach ($reqCommandes as $index => $commande) {
                                        $hasCommandes = true;
                                        // Définir la commande sélectionnée (dernière par défaut si aucune sélectionnée)
                                        $selected = ($selectedCommande == $commande['IDCOMMANDE'] || (!$selectedCommande && $index == 0)) ? 'selected' : '';
                                        if (!$selectedCommande && $index == 0) {
                                            $selectedCommande = $commande['IDCOMMANDE']; // Mettre à jour pour afficher la commande par défaut
                                        }
                                        echo "<option value='".$commande['IDCOMMANDE']."' $selected>Commande du ".date('d/m/Y', strtotime($commande['DATECOMMANDE']))."</option>";
                                    }
                                    $reqCommandes->closeCursor();
                                    ?>
                                </select>
                            </form>
                        </div>
                        <!-- Le détail de la commande (ou non si aucune commande) -->
                        <?php
                            if ($hasCommandes) {
                            // Charger les détails de la commande sélectionnée
                            $reqDetail = $conn->prepare("
                                SELECT P.nomProduit, P.prix, P.description, DC.quantiteCommandee 
                                FROM DETAILCOMMANDE DC 
                                INNER JOIN PRODUIT P ON DC.idProduit = P.idProduit
                                WHERE DC.idCommande = ?;
                            ");
                            $reqDetail->execute([$selectedCommande]);
                        
                            
                            echo "<div class=\"card mt-3\">";
                                echo "<div class=\"card-body\">";
                                    echo "<h5 class=\"card-title\">Détails de la commande</h5>";
                                    echo "<p class=\"card-text\">Voici les articles de votre commande :</p>";
                                echo "</div>";
                                echo "<ul class=\"list-group list-group-flush\">";
                                foreach ($reqDetail as $produit) {
                                    echo "<li class=\"list-group-item\">";
                                        echo "<strong>".htmlspecialchars($produit['NOMPRODUIT'])."</strong><br>";
                                        echo "Prix : ".number_format($produit['PRIX'], 2, ',', ' ')." €<br>";
                                        echo "Quantité : ".$produit['QUANTITECOMMANDEE']."<br>";
                                        echo "Description : ".htmlspecialchars($produit['description']);
                                    echo "</li>";
                                }
                                $reqDetail->closeCursor();
                                echo "</ul>";
                            echo "</div>";
                            } else {
                                echo "<p>Aucune commande disponible pour l'instant.</p>";
                            }
                        ?>
                    </div>
                    <div class="tab-pane" id="favorisPane" role="tabpanel" aria-labelledby="favorisTab">
                        <p class="card-text mb-5">Vos articles favoris :</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Pied de page -->
    <?php require_once "./include/footer.php"; ?>
</body>
</html>