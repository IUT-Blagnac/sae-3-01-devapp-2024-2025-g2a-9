<?php
    $pageTitle = "Gestion des clients";
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
        // ...vous récupérez les champs du formulaire...
        try {
            $updateClientQuery = $conn->prepare("
                UPDATE UTILISATEUR
                SET nom = :nom,
                    prenom = :prenom,
                    mail = :email
                WHERE IDUTILISATEUR = :IDUTILISATEUR
            ");
            $updateClientQuery->execute([
                ':nom' => $_POST['nom'],
                ':prenom' => $_POST['prenom'],
                ':email' => $_POST['email'],
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
        // ...vous récupérez l'ID du client...
        try {
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
                <!-- Contenu des tabs -->
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
                    <div class="tab-pane fade <?php echo (isset($_GET['IDUTILISATEUR']) || (isset($_POST['form_name']) && $_POST['form_name'] === 'clientForm')) ? 'show active' : ''; ?>" id="modifierClientPane" role="tabpanel" aria-labelledby="modifierClientTab">
                        <h2 class="mb-4">Modifier un client :</h2>

                        <!-- Tableau listant tous les clients -->
                        <?php
                        $reqClients = $conn->prepare("SELECT * FROM UTILISATEUR ORDER BY NOM ASC;");
                        $reqClients->execute();
                        ?>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nom</th>
                                    <th>Prénom</th>
                                    <th>Email</th>
                                    <th>Téléphone</th>
                                    <th>Date de Naissance</th>
                                    <th>Pays</th>
                                    <th>Adresse</th>
                                    <th>Civilité</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($reqClients as $client): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($client['IDUTILISATEUR']); ?></td>
                                    <td><?php echo htmlspecialchars($client['NOM']); ?></td>
                                    <td><?php echo htmlspecialchars($client['PRENOM']); ?></td>
                                    <td><?php echo htmlspecialchars($client['MAIL']); ?></td>
                                    <td><?php echo htmlspecialchars($client['TELEPHONE']); ?></td>
                                    <td><?php echo htmlspecialchars($client['DATEN']); ?></td>
                                    <td><?php echo htmlspecialchars($client['PAYS']); ?></td>
                                    <td><?php echo htmlspecialchars($client['ADRESSE']); ?></td>
                                    <td><?php echo htmlspecialchars($client['CIVILITE']); ?></td>
                                    <td>
                                        <a href="?IDUTILISATEUR=<?php echo $client['IDUTILISATEUR']; ?>#modifierClientPane" class="btn btn-sm btn-primary">
                                            Modifier
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php $reqClients->closeCursor(); ?>

                        <!-- Formulaire pré-rempli si GET[IDUTILISATEUR] est défini -->
                        <?php if (isset($_GET['IDUTILISATEUR'])): ?>
                            <?php
                                $IDUTILISATEURSel = (int) $_GET['IDUTILISATEUR'];
                                $fetchClientQuery = $conn->prepare("SELECT * FROM UTILISATEUR WHERE IDUTILISATEUR = ?");
                                $fetchClientQuery->execute([$IDUTILISATEURSel]);
                                $clientData = $fetchClientQuery->fetch(PDO::FETCH_ASSOC);
                            ?>
                            <?php if ($clientData): ?>
                                <form method="POST" class="mt-4">
                                    <input type="hidden" name="form_name" value="modificationClient">
                                    <input type="hidden" name="IDUTILISATEUR" value="<?php echo htmlspecialchars($clientData['IDUTILISATEUR']); ?>">

                                    <div class="mb-3">
                                        <label for="civilite" class="form-label">Civilité :</label>
                                        <select name="civilite" id="civilite" class="form-select" required>
                                            <option value="MR" <?php echo ($clientData['CIVILITE'] == 'MR') ? 'selected' : ''; ?>>Monsieur</option>
                                            <option value="MME" <?php echo ($clientData['CIVILITE'] == 'MME') ? 'selected' : ''; ?>>Madame</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="nom" class="form-label">Nom :</label>
                                        <input type="text" name="nom" id="nom" class="form-control"
                                               value="<?php echo htmlspecialchars($clientData['NOM']); ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="prenom" class="form-label">Prénom :</label>
                                        <input type="text" name="prenom" id="prenom" class="form-control"
                                               value="<?php echo htmlspecialchars($clientData['PRENOM']); ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email :</label>
                                        <input type="email" name="email" id="email" class="form-control"
                                               value="<?php echo htmlspecialchars($clientData['MAIL']); ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="pays" class="form-label">Pays :</label>
                                        <input type="text" name="pays" id="pays" class="form-control"
                                               value="<?php echo htmlspecialchars($clientData['PAYS']); ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="adresse" class="form-label">Adresse :</label>
                                        <input type="text" name="adresse" id="adresse" class="form-control"
                                               value="<?php echo htmlspecialchars($clientData['ADRESSE']); ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="dateN" class="form-label">Date de Naissance :</label>
                                        <input type="date" name="dateN" id="dateN" class="form-control"
                                               value="<?php echo htmlspecialchars($clientData['DATEN']); ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="telephone" class="form-label">Téléphone :</label>
                                        <input type="text" name="telephone" id="telephone" class="form-control"
                                               value="<?php echo htmlspecialchars($clientData['TELEPHONE']); ?>" required>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                                </form>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>

                    
                    <!-- Tab suppression client -->
                    <div class="tab-pane fade" id="supprimerClientPane" role="tabpanel" aria-labelledby="supprimerClientTab">
                        <h2 class="mb-4">Supprimer un client :</h2>
                        <!-- Selection du client -->
                        <div class="col d-flex justify-content-between align-items-center mb-3">
                            <p class="card-text">Choisissez votre client :</p>
                            <form method="POST" id="clientForm">
                                <input type="hidden" name="form_name" value="clientForm">
                                <select name="client" class="form-select w-100" aria-label="Liste des clients" onchange="this.form.submit();">
                                    <?php
                                    $reqClients = $conn->prepare("SELECT * FROM UTILISATEUR ORDER BY nom ASC;");
                                    $reqClients->execute();
                                    $selectedClient = $_POST['client'] ?? null;

                                    foreach ($reqClients as $client) {
                                        // Construire la valeur concaténée contenant les données du client
                                        $clientData = implode('|', [
                                            $client['IDUTILISATEUR'],
                                            $client['nom'],
                                            $client['prenom'],
                                            $client['mail']
                                        ]);
                                        // Marquer le client sélectionné par défaut
                                        $selected = ($selectedClient == $clientData || (!$selectedClient && $index == 0)) ? 'selected' : '';
                                        if (!$selectedClient && $index == 0) {
                                            $selectedClient = $clientData;
                                        }

                                        echo "<option value='$clientData' $selected>" . $client['IDUTILISATEUR'] . " - " . htmlspecialchars($client['nom']) . "</option>";
                                    }
                                    $reqClients->closeCursor();
                                    ?>
                                </select>
                            </form>
                        </div>
                        <!-- Affichage du client selectionné -->
                        <?php if (!empty($selectedClient)) : ?>
                            <?php
                                // Extraire les données du client selectionné
                                [$IDUTILISATEUR, $nom, $prenom, $email] = explode('|', $selectedClient);
                            ?>

                            <!-- Formulaire pour supprimer le client sélectionné -->
                            <form method="POST" id="suppressionClient">
                                <input type="hidden" name="form_name" value="suppressionClient">

                                <div class="mb-3">
                                    <label for="IDUTILISATEUR" class="form-label">ID Client :</label>
                                    <input type="text" name="IDUTILISATEUR" id="IDUTILISATEUR" class="form-control" value="<?= htmlspecialchars($IDUTILISATEUR) ?>" readonly>
                                </div>

                                <div class="mb-3">
                                    <label for="nom" class="form-label">Nom :</label>
                                    <input type="text" name="nom" id="nom" class="form-control" value="<?= htmlspecialchars($nom ?? '') ?>" readonly>
                                </div>

                                <div class="mb-3">
                                    <label for="prenom" class="form-label">Prénom :</label>
                                    <input type="text" name="prenom" id="prenom" class="form-control" value="<?= htmlspecialchars($prenom ?? '') ?>" readonly>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email :</label>
                                    <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($email ?? '') ?>" readonly>
                                </div>

                                <button type="submit" class="btn btn-primary">Supprimer ce client</button>
                            </form>
                            <?php if (isset($message)) : ?>
                                <p class="mt-3"><?= htmlspecialchars($message) ?></p>
                            <?php endif; ?>
                            
                        <?php else : ?>
                            <p class="text-muted">Aucun client disponible pour l'instant.</p>
                        <?php endif; ?>
                        
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Pied de page -->
    <?php require_once "./include/footer.php"; ?>
</body>

</html>