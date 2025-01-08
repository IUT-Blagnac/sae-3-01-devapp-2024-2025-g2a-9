<?php
    $pageTitle = "Gestion des clients";
    require_once "./include/head.php";
?>
<body>
    <?php        
    require_once "./include/header.php";

    //  verif droits
    if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
        header("Location: index.php");
        exit();
    }

    require_once "./include/menu.php";

    if (isset($_SESSION['message'])) {
        $messageType = $_SESSION['message_type'] ?? 'info';
        echo '<div class="alert alert-' . htmlspecialchars($messageType) . ' w-50" role="alert">';
        echo htmlspecialchars($_SESSION['message']);
        echo '</div>';
        // Supprimer le message après l'affichage
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    }


    

    // Ajout client
    if ($_SERVER['REQUEST_METHOD'] === 'POST'
        && isset($_POST['form_name'])
        && $_POST['form_name'] === 'ajoutClient') {
        // Récupère données form
        $mail = htmlentities($_POST['mail']);
        $mdp = htmlentities($_POST['mdp'] ?? 'password');
        $prenom = htmlentities($_POST['prenom']);
        $nom = htmlentities($_POST['nom']);
        $dateN = htmlentities($_POST['dateN']);
        $civilite = htmlentities($_POST['civilite']);
        $Numtel = htmlentities($_POST['telephone'] ?? '');
        $verif = htmlentities($_POST['verif'] ?? 'password');
        $pays = htmlentities($_POST['pays']);
        $adresse = htmlentities($_POST['adresse']);

        if($mdp != $verif){
            $message = "Mot de passe saisie différent lors de la vérification";
        } else {
            $reqmail = $conn->prepare("SELECT * FROM UTILISATEUR WHERE mail = ?") ;
            $reqmail->execute([$mail]);

            if ($reqmail->rowCount() > 0) {
                $message = "Compte déjà existant pour cette adresse";
            } else {
                $password = password_hash($mdp, PASSWORD_DEFAULT);
                $req = $conn->prepare("INSERT INTO UTILISATEUR (nom, prenom, civilite, dateN, mail, telephone, password, pays, adresse, droit)
                                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'CLIENT')") ;
                $req->execute([$nom, $prenom, $civilite, $dateN, $mail, $Numtel, $password, $pays, $adresse]);
                $message = "Le client a été ajouté avec succès.";
                $_SESSION['message'] = "Le client a été ajouté avec succès.";
                $_SESSION['message_type'] = 'success';
            }
        }
    }





    // Modification client
    if ($_SERVER['REQUEST_METHOD'] === 'POST'
        && isset($_POST['form_name'])
        && $_POST['form_name'] === 'modificationClient') {
        // recup champs formulaire
        try {
            $updateClientQuery = $conn->prepare("
                UPDATE UTILISATEUR
                SET nom = :nom,
                    prenom = :prenom,
                    mail = :email,
                    telephone = :telephone,
                    dateN = :dateN,
                    pays = :pays,
                    adresse = :adresse,
                    civilite = :civilite
                WHERE IDUTILISATEUR = :IDUTILISATEUR
            ");
            
            $updateClientQuery->execute([
                ':nom' => $_POST['nom'],
                ':prenom' => $_POST['prenom'],
                ':email' => $_POST['email'],
                ':telephone' => $_POST['telephone'],
                ':dateN' => $_POST['dateN'],
                ':pays' => $_POST['pays'],
                ':adresse' => $_POST['adresse'],
                ':civilite' => $_POST['civilite'],
                ':IDUTILISATEUR' => $_POST['IDUTILISATEUR']
            ]);
            $message = "Le client a été modifié avec succès.";
        } catch (PDOException $e) {
            $message = "Erreur lors de la modification du client : " . $e->getMessage();
        }
    }





    // Suppression client
    if ($_SERVER['REQUEST_METHOD'] === 'POST'
        && isset($_POST['form_name'])
        && $_POST['form_name'] === 'suppressionClient') {
        // recup id client
        try {
            // Supprimer enregistrements dans DETAILPANIER
            $deleteDetailPanierQuery = $conn->prepare("
                DELETE FROM DETAILPANIER
                WHERE IDUTILISATEUR = :IDUTILISATEUR
            ");
            $deleteDetailPanierQuery->execute([
                ':IDUTILISATEUR' => $_POST['IDUTILISATEUR']
            ]);

            // Supprimer enregistrements dans FAVORI
            $deleteFavoriQuery = $conn->prepare("
                DELETE FROM FAVORI
                WHERE IDUTILISATEUR = :IDUTILISATEUR
            ");
            $deleteFavoriQuery->execute([
                ':IDUTILISATEUR' => $_POST['IDUTILISATEUR']
            ]);

            // Supprimer utilisateur
            $deleteClientQuery = $conn->prepare("
                DELETE FROM UTILISATEUR
                WHERE IDUTILISATEUR = :IDUTILISATEUR
            ");
            $deleteClientQuery->execute([
                ':IDUTILISATEUR' => $_POST['IDUTILISATEUR']
            ]);
            $message = "Le client a été supprimé avec succès.";
        } catch (PDOException $e) {
            $message = "Erreur lors de la suppression du client : " . $e->getMessage();
        }
    }

    ?>




    <!-- Contenu principal -->
    <main role="main" class="container my-5">
        
        <div class="row gx-3 gx-lg-5">
            <div class="col-3 me-3 me-lg-5">
                <!-- message Utilisateur -->
                <div class="card mb-4">
                    <div class="d-flex flex-column align-items-center">
                        <i class="bi bi-person-circle fs-1 w-100 text-center"></i>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Bonjour,</strong></h5>
                        <p class="card-text">Bienvenue sur votre espace administrateur.</p>
                    </div>
                </div>
                <!-- Boutons navigation -->
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <button class="nav-link d-flex align-items-center mb-2 <?php echo (!isset($_GET['IDUTILISATEUR']) && (!isset($_POST['form_name']) || $_POST['form_name'] !== 'clientForm')) ? 'active' : ''; ?>" id="ajouterClientTab" data-bs-toggle="tab" data-bs-target="#ajouterClientPane" type="button" role="tab" aria-controls="ajouterClientPane" aria-selected="true">
                        <i class="bi bi-plus-circle me-3"></i>Ajouter un client
                    </button>
                    <button class="nav-link d-flex align-items-center mb-2 <?php echo (isset($_GET['IDUTILISATEUR']) || (isset($_POST['form_name']) && $_POST['form_name'] === 'clientForm')) ? 'active' : ''; ?>" id="modifierClientTab" data-bs-toggle="tab" data-bs-target="#modifierClientPane" type="button" role="tab" aria-controls="modifierClientPane" aria-selected="false">
                        <i class="bi bi-pencil-square me-3"></i>Modifier un client
                    </button>
                    <button class="nav-link d-flex align-items-center mb-2" id="supprimerClientTab" data-bs-toggle="tab" data-bs-target="#supprimerClientPane" type="button" role="tab" aria-controls="supprimerClientPane" aria-selected="false"><i class="bi bi-trash me-3"></i>Supprimer un client</button>
                </div>
            </div>
            <div class="col-8">
                
                <div class="tab-content" id="tab-content" aria-orientation="vertical">

                    <!-- Tab ajout client -->
                    <div class="tab-pane fade <?php echo (!isset($_GET['IDUTILISATEUR']) && (!isset($_POST['form_name']) || $_POST['form_name'] !== 'clientForm')) ? 'show active' : ''; ?>" id="ajouterClientPane" role="tabpanel" aria-labelledby="ajouterClientTab">
                        <h2 class="mb-4">Ajouter un client :</h2>
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
                        




                        <!-- form pour ajouter un client -->
                        <form method="POST" id="ajoutClient">
                            <input type="hidden" name="form_name" value="ajoutClient">

                            <div class="mb-3">
                                <label for="civilite" class="form-label">Civilité :</label>
                                <select name="civilite" id="civilite" class="form-select" required>
                                    <option value="MR">Monsieur</option>
                                    <option value="MME">Madame</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="nom" class="form-label">Nom :</label>
                                <input type="text" name="nom" id="nom" class="form-control" required maxlength="30">
                            </div>

                            <div class="mb-3">
                                <label for="prenom" class="form-label">Prénom :</label>
                                <input type="text" name="prenom" id="prenom" class="form-control" required maxlength="30">
                            </div>

                            <div class="mb-3">
                                <label for="pays" class="form-label">Pays :</label>
                                <input type="text" name="pays" id="pays" class="form-control" required maxlength="30">
                            </div>

                            <div class="mb-3">
                                <label for="ville" class="form-label">Ville :</label>
                                <input type="text" name="ville" id="ville" class="form-control" required maxlength="30">
                            </div>

                            <div class="mb-3">
                                <label for="codePostal" class="form-label">Code Postal :</label>
                                <input type="text" name="codePostal" id="codePostal" class="form-control" required maxlength="10">
                            </div>

                            <div class="mb-3">
                                <label for="adresse" class="form-label">Adresse :</label>
                                <input type="text" name="adresse" id="adresse" class="form-control" required maxlength="100">
                            </div>

                            <div class="mb-3">
                                <label for="dateN" class="form-label">Date de Naissance :</label>
                                <input type="date" name="dateN" id="dateN" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email :</label>
                                <input type="email" name="mail" id="mail" class="form-control" required maxlength="50">
                            </div>

                            <div class="mb-3">
                                <label for="telephone" class="form-label">Téléphone :</label>
                                <input type="text" name="telephone" id="telephone" class="form-control" required maxlength="15">
                            </div>

                            <button type="submit" class="btn btn-primary">Ajouter le client</button>
                        </form>

                        <?php if (isset($message)) : ?>
                            <div class="alert alert-success mt-3" role="alert">
                                <?= htmlspecialchars($message) ?>
                            </div>
                        <?php endif; ?>

                    </div>




                    <!-- Tab modification client -->
                    <div class="tab-pane fade <?php echo (
                        isset($_POST['form_name']) 
                        && in_array($_POST['form_name'], ['clientSelectForm','modificationClient'])
                    ) ? 'show active' : ''; ?>" id="modifierClientPane" role="tabpanel" aria-labelledby="modifierClientTab">
                        <h2 class="mb-4">Modifier un client :</h2>
                        <div class="col d-flex justify-content-between align-items-center mb-3">
                            <form method="POST" id="clientSelectForm">
                                <input type="hidden" name="form_name" value="clientSelectForm">
                                <select name="clientData" class="form-select w-100" onchange="this.form.submit();">
                                    <?php
                                    $reqClients = $conn->prepare("SELECT * FROM UTILISATEUR ORDER BY NOM ASC;");
                                    $reqClients->execute();
                                    $selectedClient = $_POST['clientData'] ?? null;

                                    foreach ($reqClients as $client) {
                                        $data = implode('|', [
                                            $client['IDUTILISATEUR'],
                                            $client['NOM'],
                                            $client['PRENOM'],
                                            $client['MAIL'],
                                            $client['TELEPHONE'],
                                            $client['DATEN'],
                                            $client['PAYS'],
                                            $client['ADRESSE'],
                                            $client['CIVILITE']
                                        ]);
                                        $selected = ($selectedClient == $data) ? 'selected' : '';
                                        echo "<option value='$data' $selected>" . $client['IDUTILISATEUR'] . " - " . htmlspecialchars($client['NOM']) . ' ' . htmlspecialchars($client['PRENOM']) . "</option>";
                                    }
                                    $reqClients->closeCursor();
                                    ?>
                                </select>
                            </form>
                        </div>
                        <?php if (!empty($selectedClient)): ?>
                            <?php
                            list($id, $nom, $prenom, $mail, $tel, $dateN, $pays, $adresse, $civilite) = explode('|', $selectedClient);
                            ?>
                            <form method="POST">
                                <input type="hidden" name="form_name" value="modificationClient">
                                <input type="hidden" name="IDUTILISATEUR" value="<?php echo htmlspecialchars($id); ?>">

                                <div class="mb-3">
                                    <label for="civilite" class="form-label">Civilité :</label>
                                    <select name="civilite" id="civilite" class="form-select">
                                        <option value="MR" <?php echo ($civilite === 'MR') ? 'selected' : ''; ?>>Monsieur</option>
                                        <option value="MME" <?php echo ($civilite === 'MME') ? 'selected' : ''; ?>>Madame</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="nom" class="form-label">Nom :</label>
                                    <input type="text" name="nom" value="<?php echo htmlspecialchars($nom); ?>" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="prenom" class="form-label">Prénom :</label>
                                    <input type="text" name="prenom" value="<?php echo htmlspecialchars($prenom); ?>" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email :</label>
                                    <input type="email" name="email" value="<?php echo htmlspecialchars($mail); ?>" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="telephone" class="form-label">Téléphone :</label>
                                    <input type="text" name="telephone" value="<?php echo htmlspecialchars($tel); ?>" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="dateN" class="form-label">Date de Naissance :</label>
                                    <input type="date" name="dateN" value="<?php echo htmlspecialchars($dateN); ?>" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="pays" class="form-label">Pays :</label>
                                    <input type="text" name="pays" value="<?php echo htmlspecialchars($pays); ?>" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="adresse" class="form-label">Adresse :</label>
                                    <input type="text" name="adresse" value="<?php echo htmlspecialchars($adresse); ?>" class="form-control">
                                </div>
                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                            </form>
                        <?php endif; ?>
                    </div>

                    



                    <!-- Tab suppression client -->
                    <div class="tab-pane fade" id="supprimerClientPane" role="tabpanel" aria-labelledby="supprimerClientTab">
                        <h2 class="mb-4">Supprimer un client :</h2>
                        <form method="POST" action="">
                            <input type="hidden" name="form_name" value="suppressionClient">
                            <label for="rechercheClientInput" class="form-label">Recherchez un client :</label>
                            <input
                                list="clientList"
                                id="rechercheClientInput"
                                name="IDUTILISATEUR"
                                class="form-control"
                                placeholder="Tapez pour rechercher..."
                                required
                            >
                            <datalist id="clientList">
                                <?php
                                $reqClients = $conn->prepare("SELECT IDUTILISATEUR, NOM, PRENOM FROM UTILISATEUR ORDER BY NOM ASC;");
                                $reqClients->execute();
                                foreach ($reqClients as $client) {
                                    echo "<option value='" . htmlspecialchars($client['IDUTILISATEUR']) . "'>" 
                                        . htmlspecialchars($client['NOM']) . ' ' 
                                        . htmlspecialchars($client['PRENOM']) . "</option>";
                                }
                                $reqClients->closeCursor();
                                ?>
                            </datalist>
                            <button type="submit" class="btn btn-danger mt-3">Supprimer le client</button>
                        </form>
                        <script>
                        document.querySelector('form[action=""]').addEventListener('submit', function(e) {
                            const input = document.getElementById('rechercheClientInput');
                            const list = document.getElementById('clientList');
                            const val = input.value.trim();
                            let found = false;
                            for (let option of list.options) {
                                if (option.value === val) {
                                    found = true;
                                    break;
                                }
                            }
                            if (!found) {
                                alert("Ce client n'existe pas dans la liste.");
                                e.preventDefault();
                            } else if (!confirm("Êtes-vous sûr de vouloir supprimer ce client ?")) {
                                e.preventDefault();
                            }
                        });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </main>


    <!-- Pied de page -->
    <?php require_once "./include/footer.php"; ?>
</body>

</html>