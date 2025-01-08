<?php
    $pageTitle = "Gestion des catégories";
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

    // Ajout categorie
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_name']) && $_POST['form_name'] === 'ajoutCategorie') {
        $nomCategorie = $_POST['nomCategorie'];
        try {
            if ($_POST['idCategPere'] === 'null') {
                $insertQuery = $conn->prepare("
                    INSERT INTO CATEGORIE (NOMCATEGORIE)
                    VALUES (:nomCategorie)
                ;");

                $insertQuery->execute([
                    ':nomCategorie' => $nomCategorie
                ]);
            } else {
                $idCategPere = $_POST['idCategPere'];
                $insertQuery = $conn->prepare("
                    INSERT INTO CATEGORIE (IDCATEGPERE, NOMCATEGORIE)
                    VALUES (:idCategPere, :nomCategorie)
                ;");
                $insertQuery->execute([
                    ':idCategPere' => $idCategPere,
                    ':nomCategorie' => $nomCategorie
                ]);
            }

            $message = "La catégorie a été ajoutée avec succès.";
        } catch (PDOException $e) {
            $message = "Erreur lors de l'ajout de la  catégorie : " . $e->getMessage();
        }
    }

    // modification categorie
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idCategorie']) && isset($_POST['form_name']) && $_POST['form_name'] === 'modificationCategorie') {
        $idCategorie = $_POST['idCategorie'];
        $nomCategorie = $_POST['nomCategorie'];
        try {
            if ($_POST['idCategPere'] === 'null') {
                $updateQuery = $conn->prepare("
                    UPDATE CATEGORIE
                    SET NOMCATEGORIE = :nomCategorie
                    WHERE IDCATEGORIE = :idCategorie
                ;");

                $updateQuery->execute([
                    ':nomCategorie' => $nomCategorie,
                    ':idCategorie' => $idCategorie
                ]);
            } else {
                $idCategPere = $_POST['idCategPere'];
                $updateQuery = $conn->prepare("
                    UPDATE CATEGORIE
                    SET IDCATEGPERE = :idCategPere,
                        NOMCATEGORIE = :nomCategorie
                    WHERE IDCATEGORIE = :idCategorie
                ;");
                $updateQuery->execute([
                    ':idCategPere' => $idCategPere,
                    ':nomCategorie' => $nomCategorie,
                    ':idCategorie' => $idCategorie
                ]);
            }

            $message = "La catégorie a été modifiée avec succès.";
        } catch (PDOException $e) {
            $message = "Erreur lors de la modification de la catégorie : " . $e->getMessage();
        }
    }

    // Suppression categorie
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idCategorie']) && isset($_POST['form_name']) && $_POST['form_name'] === 'suppressionCategorie') {
        $idCategorie = (int)$_POST['idCategorie']; // Conversion en entier pour éviter des problèmes de sécurité

        try {
            $deleteQuery = $conn->prepare("
                DELETE FROM CATEGORIE
                WHERE IDCATEGORIE = :idCategorie
            ;");

            $deleteQuery->execute([':idCategorie' => $idCategorie]);

            $message = "Le categorie a été supprimée avec succès.";
        } catch (PDOException $e) {
            $message = "Erreur lors de la suppression de la catégorie : " . htmlspecialchars($e->getMessage());
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
                    <button class="nav-link d-flex align-items-center mb-2 <?php echo (isset($_POST['form_name']) && $_POST['form_name'] === 'categorieForm') ? '' : 'active'; ?>" id="ajouterCategTab" data-bs-toggle="tab" data-bs-target="#ajouterCategPane" type="button" role="tab" aria-controls="ajouterCategPane" aria-selected="true"><i class="bi bi-plus-circle me-3"></i>Ajouter une catégorie</button>
                    <button class="nav-link d-flex align-items-center mb-2 <?php echo (isset($_POST['form_name']) && $_POST['form_name'] === 'categorieForm') ? 'active' : ''; ?>" id="modifierCategTab" data-bs-toggle="tab" data-bs-target="#modifierCategPane" type="button" role="tab" aria-controls="modifierCategPane" aria-selected="false"><i class="bi bi-pencil-square me-3"></i>Modifier une catégorie</button>
                    <button class="nav-link d-flex align-items-center mb-2" id="supprimerCategTab" data-bs-toggle="tab" data-bs-target="#supprimerCategPane" type="button" role="tab" aria-controls="supprimerCategPane" aria-selected="false"><i class="bi bi-trash me-3"></i>Supprimer une catégorie</button>
                </div>
            </div>
            <div class="col-8">
                <!-- Contenu des tabs -->
                <div class="tab-content" id="tab-content" aria-orientation="vertical">

                    <!-- Tab ajout categorie -->
                    <div class="tab-pane fade <?php echo (isset($_POST['form_name']) && $_POST['form_name'] === 'categorieForm') ? '' : 'show active'; ?>" id="ajouterCategPane" role="tabpanel" aria-labelledby="ajouterCategTab">
                        <h2 class="mb-4">Ajouter une catégorie :</h2>
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
                            
                            <!-- form pour ajouter une categorie -->
                            <form method="POST" id="ajoutCategorie">
                                <input type="hidden" name="form_name" value="ajoutCategorie">

                                <div class="mb-3">
                                    <label for="nomCategorie" class="form-label">Nom de la categorie :</label>
                                    <input type="text" name="nomCategorie" id="nomCategorie" class="form-control" required maxlength="30">
                                </div>

                                <div class="mb-3">
                                    <label for="categPere" class="form-label">Catégorie père :</label>
                                    <select name="idCategPere" id="categPere" class="form-select" required>
                                        <option value="null">Aucune</option>
                                        <?php
                                        $reqCategories = $conn->prepare("SELECT IDCATEGORIE, NOMCATEGORIE FROM CATEGORIE;");
                                        $reqCategories->execute();
                                        foreach ($reqCategories as $categorie) {
                                            $selected = ($categorie['IDCATEGORIE'] == $idCategPere) ? 'selected' : '';
                                            echo "<option value=\"{$categorie['IDCATEGORIE']}\" $selected>{$categorie['NOMCATEGORIE']}</option>";
                                        }
                                        $reqCategories->closeCursor();
                                        ?>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary">Ajouter la catégorie</button>
                            </form>
                    </div>

                    <!-- Tab modification categorie -->
                    <div class="tab-pane fade <?php echo (isset($_POST['form_name']) && $_POST['form_name'] === 'categorieForm') ? 'show active' : ''; ?>" id="modifierCategPane" role="tabpanel" aria-labelledby="modifierProdTab">
                        <h2 class="mb-4">Modifier une catégorie :</h2> 
                        <!-- Selection de la categorie -->
                        <div class="col d-flex justify-content-between align-items-center mb-3">
                            <p class="card-text">Choisissez votre catégorie :</p>
                            <form method="POST" id="categorieForm">
                                <input type="hidden" name="form_name" value="categorieForm">
                                <select name="categorie" class="form-select w-100" aria-label="Liste des catégories" onchange="this.form.submit();">
                                    <?php
                                    $reqCategories = $conn->prepare("SELECT * FROM CATEGORIE ORDER BY NOMCATEGORIE ASC;");
                                    $reqCategories->execute();
                                    $selectedCategorie = $_POST['categorie'] ?? null;

                                    foreach ($reqCategories as $categorie) {
                                        // Construire la valeur concaténée contenant les données du categorie
                                        $categorieData = implode('|', [
                                            $categorie['IDCATEGORIE'],
                                            $categorie['IDCATEGPERE'],
                                            $categorie['NOMCATEGORIE']
                                        ]);
                                        // Marquer le categorie sélectionné par défaut
                                        $selected = ($selectedCategorie == $categorieData || (!$selectedCategorie && $index == 0)) ? 'selected' : '';
                                        if (!$selectedCategorie && $index == 0) {
                                            $selectedCategorie = $categorieData;
                                        }

                                        echo "<option value='$categorieData' $selected>" . $categorie['IDCATEGORIE'] . " - " . htmlspecialchars($categorie['NOMCATEGORIE']) . "</option>";
                                    }
                                    $reqCategories->closeCursor();
                                    ?>
                                </select>
                            </form>
                        </div>
                        <!-- Affichage de la categorie selectionnée -->
                        <?php if (!empty($selectedCategorie)) : ?>
                            <?php
                                // Extraire les données de la categorie selectionnée
                                [$idCategorie, $idCategPere, $nomCategorie] = explode('|', $selectedCategorie);
                            ?>

                            <!-- Formulaire pour modifier la categorie sélectionnée -->
                            <form method="POST" id="modificationCategorie">
                                <input type="hidden" name="form_name" value="modificationCategorie">

                                <div class="mb-3">
                                    <label for="idCategorie" class="form-label">ID Catégorie :</label>
                                    <input type="text" name="idCategorie" id="idCategorie" class="form-control" value="<?= htmlspecialchars($idCategorie) ?>" readonly>
                                </div>

                                <div class="mb-3">
                                    <label for="nomCategorie" class="form-label">Nom de la catégorie :</label>
                                    <input type="text" name="nomCategorie" id="nomCategorie" class="form-control" value="<?= htmlspecialchars($nomCategorie) ?>" required maxlength="30">
                                </div>

                                <div class="mb-3">
                                    <label for="categPere" class="form-label">Catégorie Père :</label>
                                    <select name="idCategPere" id="categPere" class="form-select" required>
                                        <option value="null">Aucune</option>
                                        <?php
                                        $reqCategories = $conn->prepare("SELECT IDCATEGORIE, NOMCATEGORIE FROM CATEGORIE WHERE IDCATEGPERE IS NOT NULL;");
                                        $reqCategories->execute();
                                        foreach ($reqCategories as $categorie) {
                                            $selected = ($categorie['IDCATEGORIE'] == $idCategPere) ? 'selected' : '';
                                            echo "<option value=\"{$categorie['IDCATEGORIE']}\" $selected>{$categorie['NOMCATEGORIE']}</option>";
                                        }
                                        $reqCategories->closeCursor();
                                        ?>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                            </form>
                            
                        <?php else : ?>
                            <p class="text-muted">Aucune catégorie disponible pour l'instant.</p>
                        <?php endif; ?>
                    </div>

                    <!-- Tab suppression categorie -->
                    <div class="tab-pane fade" id="supprimerCategPane" role="tabpanel" aria-labelledby="supprimerCategTab">
                        <h2 class="mb-4">Supprimer une catégorie :</h2>
                        <!-- Selection du categorie -->
                        <div class="col d-flex justify-content-between align-items-center mb-3">
                            
                            <?php if (!empty($selectedCategorie)) : ?>
                                <!-- Formulaire pour supprimer la categorie sélectionnée -->
                                <form method="POST" id="suppressionCategorie" class="w-100">
                                    <input type="hidden" name="form_name" value="suppressionCategorie">
                                    
                                    <label for="rechercheCategorieInput" class="card-text">Recherchez une catégorie :</label>
                                    <input 
                                        list="categorieList" 
                                        id="rechercheCategorieInput" 
                                        name="idCategorie" 
                                        class="form-control form-control-lg w-100 mb-3" 
                                        placeholder="Tapez pour rechercher..."
                                        required
                                    >

                                    <datalist id="categorieList">
                                        <?php
                                        $reqCategoriesList = $conn->prepare("SELECT IDCATEGORIE, NOMCATEGORIE FROM CATEGORIE ORDER BY NOMCATEGORIE ASC;");
                                        $reqCategoriesList->execute();

                                        foreach ($reqCategoriesList as $categorie) {
                                            echo "<option value='" . htmlspecialchars($categorie['IDCATEGORIE']) . "'>" 
                                                . htmlspecialchars($categorie['NOMCATEGORIE']) . 
                                                "</option>";
                                        }
                                        $reqCategoriesList->closeCursor();
                                        ?>
                                    </datalist>

                                    <button type="submit" class="btn btn-primary btn-lg mt-3">Supprimer cette catégorie</button>
                                </form>

                                <script>
                                    document.getElementById('suppressionCategorie').addEventListener('submit', function(event) {
                                        const inputCategorie = document.getElementById('rechercheCategorieInput');
                                        const categorieList = document.getElementById('categorieList');
                                        const categId = inputCategorie.value.trim();
                                        
                                        let validCateg = false;

                                        // Parcourir toutes les options du datalist et vérifier si l'ID correspond
                                        for (let option of categorieList.options) {
                                            if (option.value === categId) {
                                                validCateg = true;
                                                break;
                                            }
                                        }

                                        if (!validCateg) {
                                            alert("Cette catégorie n'existe pas dans la liste. Veuillez choisir une catégorie valide.");
                                            event.preventDefault(); // Empêche l'envoi du formulaire si l'ID n'est pas valide
                                        } else {
                                            // Demander confirmation avant de soumettre le formulaire
                                            const confirmation = confirm("Êtes-vous sûr de vouloir supprimer cette catégorie ?");
                                            if (!confirmation) {
                                                event.preventDefault(); // Empêche la suppression si l'utilisateur annule
                                            }
                                        }
                                    });
                                </script>
                                
                            <?php else : ?>
                                <p class="text-muted">Aucune catégorie disponible pour l'instant.</p>
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