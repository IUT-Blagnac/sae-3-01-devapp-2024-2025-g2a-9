<?php
    $pageTitle = "Mofiication du compte";
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
                $password = htmlspecialchars($user['PASSWORD']);
            }
            if(isset($_GET['msgErreur'])){
                echo '<div class="alert alert-danger w-50" role="alert">';
                echo '<strong>Un problème est survenu</strong><br>';
                echo htmlentities($_GET['msgErreur']);
                echo '</div>';
            }
        ?>
        <form method="post">
            <!-- Informations personnelles -->
            <div class="row mb-4 w-50">
                <div class="col">
                    <div class="form-outline">
                        <label class="form-label" for="inputPrenom">Prénom *</label>
                        <input type="text" id="inputPrenom" name="prenom" class="form-control" value="<?= $prenom ?>" required />
                    </div>
                </div>
                <div class="col">
                    <div class="form-outline">
                        <label class="form-label" for="inputNom">Nom *</label>
                        <input type="text" id="inputNom" name="nom" class="form-control" value="<?= $nom ?>" required />
                    </div>
                </div>
            </div>

            <div class="row mb-4 w-50">
                <div class="col">
                    <div class="form-outline">
                        <label class="form-label" for="inputCivilite">Civilité *</label>
                        <select class="form-select w-100" id="inputCivilite" name="civilite" required>
                            <option value="MR" <?= $civilite === "MR" ? "selected" : "" ?>>Mr</option>
                            <option value="MME" <?= $civilite === "MME" ? "selected" : "" ?>>Mme</option>
                        </select>
                    </div>
                </div>
                <div class="col">
                    <div class="form-outline">
                        <label class="form-label" for="inputDateN">Date de Naissance</label>
                        <input type="date" id="inputDateN" name="dateN" class="form-control" value="<?= $dateN ?>" />
                    </div>
                </div>
            </div>

            <div class="form-outline mb-4 w-50">
                <label class="form-label" for="inputPays">Pays</label>
                <input type="text" id="inputPays" name="pays" class="form-control" value="<?= $pays ?>" />
            </div>

            <div class="form-outline mb-4 w-50">
                <label class="form-label" for="inputMail">Adresse E-mail *</label>
                <input type="email" id="inputMail" name="mail" class="form-control" value="<?= $email ?>" required />
            </div>

            <div class="form-outline mb-4 w-50">
                <label class="form-label" for="inputTel">Numéro de Téléphone</label>
                <input type="tel" id="inputTel" name="telephone" class="form-control" value="<?= $telephone ?>" />
            </div>

            <!-- Changement de mot de passe -->
            <h5 class="mb-3">Changer de mot de passe</h5>
            <div class="form-outline mb-4 w-50">
                <label class="form-label" for="inputOldPwd">Ancien mot de passe</label>
                <input type="password" id="inputOldPwd" name="old_password" class="form-control"/>
            </div>
            <div class="form-outline mb-4 w-50">
                <label class="form-label" for="inputNewPwd">Nouveau mot de passe</label>
                <input type="password" id="inputNewPwd" name="new_password" class="form-control"/>
            </div>
            <div class="form-outline mb-4 w-50">
                <label class="form-label" for="inputPwdCheck">Confirmer le nouveau mot de passe</label>
                <input type="password" id="inputPwdCheck" name="check_password" class="form-control"/>
            </div>

            <!-- Bouton de validation -->
            <button type="submit" name="submit" class="btn btn-primary w-50">Modifier vos informations</button>
        </form>

        <?php
        if (isset($_POST['submit'])) {
            if (isset($prenom) && isset($nom) && isset($civilite) && isset($email)) {
                // Champs obligatoires
                $prenom = trim($_POST['prenom']);
                $nom = trim($_POST['nom']);
                $civilite = trim($_POST['civilite']);
                $email = trim($_POST['mail']);

                // Champs pas obligatoire
                $pays = trim($_POST['pays']) ?? null;
                $dateN = trim($_POST['dateN']) ?? null;
                $telephone = trim($_POST['telephone']) ?? null;
                // Vérification des mots de passe
                $oldPwd = trim($_POST['old_password']) ?? null;
                $newPwd = trim($_POST['new_password']) ?? null;
                $checkPwd = trim($_POST['check_password']) ?? null;

                if (!empty($oldPwd) && !empty($newPwd) && !empty($checkPwd) && $newPwd === $checkPwd) {
                    if (password_verify($oldPwd, $password)) {
                        $args = [$civilite, $nom, $prenom, $pays, $dateN, $email, $telephone, password_hash($oldPwd, PASSWORD_DEFAULT)];
                        $reqUpdatePwd = $conn->prepare("UPDATE UTILISATEUR UPDATE UTILISATEUR
                                                        SET CIVILITE = ?,
                                                            NOM = ?,
                                                            PRENOM = ?,
                                                            PAYS = ?,
                                                            DATEN = ?,
                                                            MAIL = ?,
                                                            TELEPHONE = ?,
                                                            PASSWORD = ?
                                                        WHERE IDUTILISATEUR = ?;") ;
                        $reqUpdatePwd->execute([$args, $_SESSION['user']]);

                        header("location: consultCompte.php");
                        exit();
                    } else {
                        header("location: modifierCompte.php?msgErreur=L'ancien mot de passe est incorrect.");
                        exit();
                    }
                }
                // Voir pour que tout les champs mdp soit rempli au besoin, sinon ne pas modifier mot de passe
                $args = [$civilite, $nom, $prenom, $pays, $dateN, $email, $telephone];
                $reqUpdate = $conn->prepare("UPDATE UTILISATEUR UPDATE UTILISATEUR
                                                SET CIVILITE = ?,
                                                    NOM = ?,
                                                    PRENOM = ?,
                                                    PAYS = ?,
                                                    DATEN = ?,
                                                    MAIL = ?,
                                                    TELEPHONE = ?,
                                                WHERE IDUTILISATEUR = ?;") ;
                $reqUpdate->execute([$args, $_SESSION['user']]);
                header("location: consultCompte.php");
                exit();
            } else {
                header("location: modifierCompte.php?msgErreur=Veuillez saisir les champs obligatoires *.");
                exit();
            }   
        }
        ?>

    </main>

    <!-- Pied de page -->
    <?php require_once "./include/footer.php"; ?>
</body>
</html>