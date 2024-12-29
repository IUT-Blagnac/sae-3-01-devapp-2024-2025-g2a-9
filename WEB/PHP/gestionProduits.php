<?php
    $pageTitle = "Gestion des produits";
    require_once "./include/head.php";
?>
<body>
    <?php        
    require_once "./include/header.php";

        // On vérifie ses droits
        if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
            header("Location: index.php");
            exit();
        }

    require_once "./include/menu.php";

    // Ajout produit
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_name']) && $_POST['form_name'] === 'ajoutProduit') {
        $idCategorie = $_POST['idCategorie'];
        $nomProduit = $_POST['nomProduit'];
        $prix = $_POST['prix'];
        $description = $_POST['description'];
        $taille = $_POST['taille'];
        $energie = $_POST['energie'];
        $stockDisponible = $_POST['stockDisponible'];
        $stockLimite = $_POST['stockLimite'];

        try {
            $insertQuery = $conn->prepare("
                INSERT INTO PRODUIT (IDCATEGORIE, NOMPRODUIT, PRIX, DESCRIPTION, TAILLE, ENERGIE, STOCKDISPONIBLE, STOCKLIMITE)
                VALUES (:idCategorie, :nomProduit, :prix, :description, :taille, :energie, :stockDisponible, :stockLimite)
            ");

            $insertQuery->execute([
                ':idCategorie' => $idCategorie,
                ':nomProduit' => $nomProduit,
                ':prix' => $prix,
                ':description' => $description,
                ':taille' => $taille,
                ':energie' => $energie,
                ':stockDisponible' => $stockDisponible,
                ':stockLimite' => $stockLimite
            ]);

            $message = "Le produit a été ajouté avec succès.";
        } catch (PDOException $e) {
            $message = "Erreur lors de l'ajout du produit : " . $e->getMessage();
        }
    }

    // modification produit
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idProduit']) && isset($_POST['form_name']) && $_POST['form_name'] === 'modificationProduit') {
        $idProduit = $_POST['idProduit'];
        $idCategorie = $_POST['idCategorie'];
        $nomProduit = $_POST['nomProduit'];
        $prix = $_POST['prix'];
        $description = $_POST['description'];
        $taille = $_POST['taille'];
        $energie = $_POST['energie'];
        $stockDisponible = $_POST['stockDisponible'];
        $stockLimite = $_POST['stockLimite'];

        try {
            $updateQuery = $conn->prepare("
                UPDATE PRODUIT
                SET IDCATEGORIE = :idCategorie,
                    NOMPRODUIT = :nomProduit,
                    PRIX = :prix,
                    DESCRIPTION = :description,
                    TAILLE = :taille,
                    ENERGIE = :energie,
                    STOCKDISPONIBLE = :stockDisponible,
                    STOCKLIMITE = :stockLimite
                WHERE IDPRODUIT = :idProduit
            ");

            $updateQuery->execute([
                ':idCategorie' => $idCategorie,
                ':nomProduit' => $nomProduit,
                ':prix' => $prix,
                ':description' => $description,
                ':taille' => $taille,
                ':energie' => $energie,
                ':stockDisponible' => $stockDisponible,
                ':stockLimite' => $stockLimite,
                ':idProduit' => $idProduit
            ]);

            $message = "Le produit a été modifié avec succès.";
        } catch (PDOException $e) {
            $message = "Erreur lors de la modification du produit : " . $e->getMessage();
        }
    }

    // Suppression produit
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idProduit']) && isset($_POST['form_name']) && $_POST['form_name'] === 'suppressionProduit') {
        $idProduit = (int)$_POST['idProduit']; // Conversion en entier pour éviter des problèmes de sécurité

        try {
            $deleteQuery = $conn->prepare("
                DELETE FROM PRODUIT
                WHERE IDPRODUIT = :idProduit
            ");

            $deleteQuery->execute([':idProduit' => $idProduit]);

            $message = "Le produit a été supprimé avec succès.";
        } catch (PDOException $e) {
            $message = "Erreur lors de la suppression du produit : " . htmlspecialchars($e->getMessage());
        }
    }

    ?>


    <!-- Contenu principal -->
    <main role="main" class="container my-5">
        <?php if (isset($message)) : ?>
            <?php 
                // Extraire le premier mot du message
                $firstWord = strtolower(explode(' ', $message)[0]);
                
                // Vérifier si le premier mot est 'erreur'
                if ($firstWord === 'Erreur') {
                    $alertClass = 'alert-danger'; // Rouge pour une erreur
                } else {
                    $alertClass = 'alert-success'; // Vert pour un message réussi
                }
            ?>
            <center>
                <strong>
                    <p class="alert <?= $alertClass ?> mb-4"><?= htmlspecialchars($message) ?></p>
                </strong>
            </center>
        <?php endif; ?>
        <div class="row gx-3 gx-lg-5">
            <div class="col-3 me-3 me-lg-5">
                <!-- Card Utilisateur -->
                <div class="card mb-4">
                    <div class="d-flex flex-column align-items-center">
                        <i class="bi bi-person-circle fs-1 w-100 text-center"></i>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Bonjour,</strong></h5>
                        <p class="card-text">Bienvenue sur votre espace administrateur.</p>
                    </div>
                </div>
                <!-- Boutons navigation (tab) -->
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <button class="nav-link d-flex align-items-center mb-2 <?php echo (isset($_POST['form_name']) && $_POST['form_name'] === 'produitForm') ? '' : 'active'; ?>" id="ajouterProdTab" data-bs-toggle="tab" data-bs-target="#ajouterProdPane" type="button" role="tab" aria-controls="ajouterProdPane" aria-selected="true"><i class="bi bi-plus-circle me-3"></i>Ajouter un produit</button>
                    <button class="nav-link d-flex align-items-center mb-2 <?php echo (isset($_POST['form_name']) && $_POST['form_name'] === 'produitForm') ? 'active' : ''; ?>" id="modifierProdTab" data-bs-toggle="tab" data-bs-target="#modifierProdPane" type="button" role="tab" aria-controls="modifierProdPane" aria-selected="false"><i class="bi bi-pencil-square me-3"></i>Modifier un produit</button>
                    <button class="nav-link d-flex align-items-center mb-2" id="supprimerProdTab" data-bs-toggle="tab" data-bs-target="#supprimerProdPane" type="button" role="tab" aria-controls="supprimerProdPane" aria-selected="false"><i class="bi bi-trash me-3"></i>Supprimer un produit</button>
                </div>
            </div>
            <div class="col-8">
                <!-- Contenu des tabs -->
                <div class="tab-content" id="tab-content" aria-orientation="vertical">

                    <!-- Tab ajout produit -->
                    <div class="tab-pane fade <?php echo (isset($_POST['form_name']) && $_POST['form_name'] === 'produitForm') ? '' : 'show active'; ?>" id="ajouterProdPane" role="tabpanel" aria-labelledby="ajouterProdTab">
                        <h2 class="mb-4">Ajouter un produit :</h2>
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
                            
                            <!-- form pour ajouter un produit -->
                            <form method="POST" id="ajoutProduit">
                                <input type="hidden" name="form_name" value="ajoutProduit">

                                <div class="mb-3">
                                    <label for="nomProduit" class="form-label">Nom du produit :</label>
                                    <input type="text" name="nomProduit" id="nomProduit" class="form-control" required maxlength="30">
                                </div>

                                <div class="mb-3">
                                    <label for="categorie" class="form-label">Catégorie :</label>
                                    <select name="idCategorie" id="categorie" class="form-select" required>
                                        <?php
                                        $reqCategories = $conn->prepare("SELECT IDCATEGORIE, NOMCATEGORIE FROM CATEGORIE;");
                                        $reqCategories->execute();
                                        foreach ($reqCategories as $categorie) {
                                            $selected = ($categorie['IDCATEGORIE'] == $idCategorie) ? 'selected' : '';
                                            echo "<option value=\"{$categorie['IDCATEGORIE']}\" $selected>{$categorie['NOMCATEGORIE']}</option>";
                                        }
                                        $reqCategories->closeCursor();
                                        ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="prix" class="form-label">Prix (€) :</label>
                                    <input type="number" name="prix" id="prix" class="form-control" step="0.01" min="0" required>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description :</label>
                                    <textarea name="description" id="description" class="form-control" maxlength="200"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="taille" class="form-label">Taille :</label>
                                    <input type="text" name="taille" id="taille" class="form-control" maxlength="30">
                                </div>

                                <div class="mb-3">
                                    <label for="energie" class="form-label">Énergie :</label>
                                    <select name="energie" id="energie" class="form-select" required>
                                        <?php
                                        $energies = ['Electrique', 'Diesel', 'Essence', 'Manuel'];
                                        foreach ($energies as $energieOption) {
                                            $selected = ($energieOption == isset($energie)) ? 'selected' : '';
                                            echo "<option value=\"$energieOption\" $selected>$energieOption</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="stockDisponible" class="form-label">Stock disponible :</label>
                                    <input type="number" name="stockDisponible" id="stockDisponible" class="form-control" min="1" required>
                                </div>

                                <div class="mb-3">
                                    <label for="stockLimite" class="form-label">Stock limite :</label>
                                    <input type="number" name="stockLimite" id="stockLimite" class="form-control" min="1" required>
                                </div>

                                <button type="submit" class="btn btn-primary">Ajouter le produit</button>
                            </form>
                    </div>

                    <!-- Tab modification produit -->
                    <div class="tab-pane fade <?php echo (isset($_POST['form_name']) && $_POST['form_name'] === 'produitForm') ? 'show active' : ''; ?>" id="modifierProdPane" role="tabpanel" aria-labelledby="modifierProdTab">
                        <h2 class="mb-4">Modifier un produit :</h2> 
                        <!-- Selection du produit -->
                        <div class="col d-flex justify-content-between align-items-center mb-3">
                            <p class="card-text">Choisissez votre produit :</p>
                            <form method="POST" id="produitForm">
                                <input type="hidden" name="form_name" value="produitForm">
                                <select name="produit" class="form-select w-100" aria-label="Liste des produits" onchange="this.form.submit();">
                                    <?php
                                    $reqProduits = $conn->prepare("SELECT * FROM PRODUIT ORDER BY NOMPRODUIT ASC;");
                                    $reqProduits->execute();
                                    $selectedProduit = $_POST['produit'] ?? null;

                                    foreach ($reqProduits as $produit) {
                                        // Construire la valeur concaténée contenant les données du produit
                                        $produitData = implode('|', [
                                            $produit['IDPRODUIT'],
                                            $produit['IDCATEGORIE'],
                                            $produit['NOMPRODUIT'],
                                            $produit['PRIX'],
                                            $produit['DESCRIPTION'],
                                            $produit['TAILLE'],
                                            $produit['ENERGIE'],
                                            $produit['STOCKDISPONIBLE'],
                                            $produit['STOCKLIMITE']
                                        ]);
                                        // Marquer le produit sélectionné par défaut
                                        $selected = ($selectedProduit == $produitData || (!$selectedProduit && $index == 0)) ? 'selected' : '';
                                        if (!$selectedProduit && $index == 0) {
                                            $selectedProduit = $produitData;
                                        }

                                        echo "<option value='$produitData' $selected>" . $produit['IDPRODUIT'] . " - " . htmlspecialchars($produit['NOMPRODUIT']) . "</option>";
                                    }
                                    $reqProduits->closeCursor();
                                    ?>
                                </select>
                            </form>
                        </div>
                        <!-- Affichage du produit selectionné -->
                        <?php if (!empty($selectedProduit)) : ?>
                            <?php
                                // Extraire les données du produit selectionné
                                [$idProduit, $idCategorie, $nomProduit, $prix, $description, $taille, $energie, $stockDisponible, $stockLimite] = explode('|', $selectedProduit);
                            ?>

                            <!-- Formulaire pour modifier le produit sélectionné -->
                            <form method="POST" id="modificationProduit">
                                <input type="hidden" name="form_name" value="modificationProduit">

                                <div class="mb-3">
                                    <label for="idProduit" class="form-label">ID Produit :</label>
                                    <input type="text" name="idProduit" id="idProduit" class="form-control" value="<?= htmlspecialchars($idProduit) ?>" readonly>
                                </div>

                                <div class="mb-3">
                                    <label for="nomProduit" class="form-label">Nom du produit :</label>
                                    <input type="text" name="nomProduit" id="nomProduit" class="form-control" value="<?= htmlspecialchars($nomProduit) ?>" required maxlength="30">
                                </div>

                                <div class="mb-3">
                                    <label for="categorie" class="form-label">Catégorie :</label>
                                    <select name="idCategorie" id="categorie" class="form-select" required>
                                        <?php
                                        $reqCategories = $conn->prepare("SELECT IDCATEGORIE, NOMCATEGORIE FROM CATEGORIE;");
                                        $reqCategories->execute();
                                        foreach ($reqCategories as $categorie) {
                                            $selected = ($categorie['IDCATEGORIE'] == $idCategorie) ? 'selected' : '';
                                            echo "<option value=\"{$categorie['IDCATEGORIE']}\" $selected>{$categorie['NOMCATEGORIE']}</option>";
                                        }
                                        $reqCategories->closeCursor();
                                        ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="prix" class="form-label">Prix (€) :</label>
                                    <input type="number" name="prix" id="prix" class="form-control" step="0.01" min="0" value="<?= htmlspecialchars($prix) ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description :</label>
                                    <textarea name="description" id="description" class="form-control" maxlength="200"><?= htmlspecialchars($description) ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="taille" class="form-label">Taille :</label>
                                    <input type="text" name="taille" id="taille" class="form-control" value="<?= htmlspecialchars($taille) ?>" maxlength="30">
                                </div>

                                <div class="mb-3">
                                    <label for="energie" class="form-label">Énergie :</label>
                                    <select name="energie" id="energie" class="form-select" required>
                                        <?php
                                        $energies = ['Electrique', 'Diesel', 'Essence', 'Manuel'];
                                        foreach ($energies as $energieOption) {
                                            $selected = ($energieOption == $energie) ? 'selected' : '';
                                            echo "<option value=\"$energieOption\" $selected>$energieOption</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="stockDisponible" class="form-label">Stock disponible :</label>
                                    <input type="number" name="stockDisponible" id="stockDisponible" class="form-control" min="1" value="<?= htmlspecialchars($stockDisponible) ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="stockLimite" class="form-label">Stock limite :</label>
                                    <input type="number" name="stockLimite" id="stockLimite" class="form-control" min="1" value="<?= htmlspecialchars($stockLimite) ?>" required>
                                </div>

                                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                            </form>
                            <?php if (isset($message)) : ?>
                                <p class="mt-3"><?= htmlspecialchars($message) ?></p>
                            <?php endif; ?>
                            
                        <?php else : ?>
                            <p class="text-muted">Aucun produit disponible pour l'instant.</p>
                        <?php endif; ?>
                    </div>

                    <!-- Tab suppression produit -->
                    <div class="tab-pane fade" id="supprimerProdPane" role="tabpanel" aria-labelledby="supprimerProdTab">
                        <h2 class="mb-4">Supprimer un produit :</h2>
                        <!-- Selection du produit -->
                        <div class="col d-flex justify-content-between align-items-center mb-3">
                            
                            <?php if (!empty($selectedProduit)) : ?>
                                <!-- Formulaire pour supprimer le produit sélectionné -->
                                <form method="POST" id="suppressionProduit" class="w-100">
                                    <input type="hidden" name="form_name" value="suppressionProduit">
                                    
                                    <label for="rechercheProduitInput" class="card-text">Recherchez un produit :</label>
                                    <input 
                                        list="produitList" 
                                        id="rechercheProduitInput" 
                                        name="idProduit" 
                                        class="form-control form-control-lg w-100 mb-3" 
                                        placeholder="Tapez pour rechercher..."
                                        required
                                    >

                                    <datalist id="produitList">
                                        <?php
                                        $reqProduits = $conn->prepare("SELECT IDPRODUIT, NOMPRODUIT FROM PRODUIT ORDER BY NOMPRODUIT ASC;");
                                        $reqProduits->execute();

                                        foreach ($reqProduits as $produit) {
                                            echo "<option value='" . htmlspecialchars($produit['IDPRODUIT']) . "'>" 
                                                . htmlspecialchars($produit['NOMPRODUIT']) . 
                                                "</option>";
                                        }
                                        $reqProduits->closeCursor();
                                        ?>
                                    </datalist>

                                    <button type="submit" class="btn btn-primary btn-lg mt-3">Supprimer ce produit</button>
                                </form>

                                <script>
                                    document.getElementById('suppressionProduit').addEventListener('submit', function(event) {
                                        const inputProduit = document.getElementById('rechercheProduitInput');
                                        const produitList = document.getElementById('produitList');
                                        const productId = inputProduit.value.trim();
                                        
                                        let validProduct = false;

                                        // Parcourir toutes les options du datalist et vérifier si l'ID correspond
                                        for (let option of produitList.options) {
                                            if (option.value === productId) {
                                                validProduct = true;
                                                break;
                                            }
                                        }

                                        if (!validProduct) {
                                            alert("Ce produit n'existe pas dans la liste. Veuillez choisir un produit valide.");
                                            event.preventDefault(); // Empêche l'envoi du formulaire si l'ID n'est pas valide
                                        } else {
                                            // Demander confirmation avant de soumettre le formulaire
                                            const confirmation = confirm("Êtes-vous sûr de vouloir supprimer ce produit ?");
                                            if (!confirmation) {
                                                event.preventDefault(); // Empêche la suppression si l'utilisateur annule
                                            }
                                        }
                                    });
                                </script>
                                
                            <?php else : ?>
                                <p class="text-muted">Aucun produit disponible pour l'instant.</p>
                            <?php endif; ?>
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