<?php
$pageTitle = "Modification du compte";
require_once "./include/head.php";
?>
<body>
    <?php
    require_once "./include/header.php";
    require_once "./include/connect.inc.php";

    // Vérifiez si le formulaire a été soumis
    if (isset($_POST['submit'])) {
        // Identifier l'action
        $action = $_POST['action'] ?? null;

        // Récupération des données générales
        $prenom = trim($_POST['prenom']) ?? null;
        $nom = trim($_POST['nom']) ?? null;
        $civilite = trim($_POST['civilite']) ?? null;
        $email = trim($_POST['email']) ?? null;
        $pays = trim($_POST['pays']) ?? null;
        $dateN = trim($_POST['dateN']) ?? null;
        $telephone = trim($_POST['telephone']) ?? null;

        $numRue = trim($_POST['numRue']) ?? null;
        $libelleVoie = trim($_POST['libelleVoie']) ?? null;
        $codePostal = trim($_POST['codePostal']) ?? null;
        $ville = trim($_POST['ville']) ?? null;

        $adresseComplete = !empty($numRue) && !empty($libelleVoie) && !empty($codePostal) && !empty($ville) ?
            "$numRue, $libelleVoie, $codePostal, $ville" :
            null;
        
        if (($numRue && !$libelleVoie) || (!$numRue && $libelleVoie) || ($codePostal && !$ville) || (!$codePostal && $ville)) {
            header("location:consultCompte.php?msgErreur=Veuillez remplir tous les champs de l'adresse.");
            exit();
        }

        // Vérifiez les champs obligatoires
        if ($action === "updateInfo" && !empty($prenom) && !empty($nom) && !empty($email)) {
            try {
                // Appeler la procédure stockée pour mettre à jour les informations utilisateur
                $reqUpdate = $conn->prepare("CALL ModifierUtilisateur(
                    :idUtilisateur, :civilite, :nom, :prenom, :pays, :dateN, :email, :telephone, :adresse, NULL, FALSE
                )");
                $reqUpdate->execute([
                    ':idUtilisateur' => $_SESSION['user'],
                    ':civilite' => $civilite,
                    ':nom' => $nom,
                    ':prenom' => $prenom,
                    ':pays' => $pays,
                    ':dateN' => $dateN,
                    ':email' => $email,
                    ':telephone' => $telephone,
                    ':adresse' => $adresseComplete,
                ]);

                header("location:consultCompte.php?msgSucces=Modifications enregistrées.");
                exit();
            } catch (Exception $e) {
                header("location:consultCompte.php?msgErreur=Erreur lors de la modification : " . $e->getMessage());
                exit();
            }
        } else {
            header("location:consultCompte.php?msgErreur=Veuillez saisir les champs obligatoires.");
            exit();
        }

        // Gestion du changement de mot de passe
        if ($action === "updatePassword") {
            $oldPwd = trim($_POST['old_password']) ?? null;
            $newPwd = trim($_POST['new_password']) ?? null;
            $checkPwd = trim($_POST['check_password']) ?? null;

            if (!empty($oldPwd) && !empty($newPwd) && $newPwd === $checkPwd) {
                try {
                    // Vérifiez l'ancien mot de passe
                    $reqPwd = $conn->prepare("SELECT PASSWORD FROM UTILISATEUR WHERE IDUTILISATEUR = ?");
                    $reqPwd->execute([$_SESSION['user']]);
                    $userPwd = $reqPwd->fetch();

                    if ($userPwd && password_verify($oldPwd, $userPwd['PASSWORD'])) {
                        $hashedPwd = password_hash($newPwd, PASSWORD_DEFAULT);
                        $reqUpdatePwd = $conn->prepare("CALL ModifierUtilisateur(
                            :idUtilisateur, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, :password, TRUE
                        )");
                        $reqUpdatePwd->execute([
                            ':idUtilisateur' => $_SESSION['user'],
                            ':password' => $hashedPwd,
                        ]);

                        header("location:consultCompte.php?msgSucces=Mot de passe modifié.");
                        exit();
                    } else {
                        header("location:modifierCompte.php?msgErreur=L'ancien mot de passe est incorrect.");
                        exit();
                    }
                } catch (Exception $e) {
                    header("location:modifierCompte.php?msgErreur=Erreur lors de la modification : " . $e->getMessage());
                    exit();
                }
            } else {
                header("location:modifierCompte.php?msgErreur=Veuillez saisir les champs obligatoires et confirmer votre mot de passe.");
                exit();
            }
        }
    }
    ?>
    <main role="main" class="container my-5">
        <a class="back-button_detailprod" href="consultCompte.php">
            <i class="fa-solid fa-chevron-left"></i> Retour à la page précédente
        </a>
        <center>
            <?php
            // Affichez un message d'erreur si nécessaire
            if(isset($_GET['msgErreur'])){
                echo '<div class="alert alert-danger w-50" role="alert">';
                    echo '<strong>Un problème est survenu</strong><br>';
                    echo htmlentities($_GET['msgErreur']);
                echo '</div>';
            }
            ?>
            <form method="post">
                <input type="hidden" name="action" value="updatePassword">
                <!-- Formulaire de modification du mot de passe -->
                
                <div class="form-outline my-4 w-50">
                    <label class="form-label" for="inputOldPwd">Ancien mot de passe</label>
                    <input type="password" id="inputOldPwd" name="old_password" class="form-control" required />
                </div>
                <div class="form-outline mb-4 w-50">
                    <label class="form-label" for="inputNewPwd">Nouveau mot de passe</label>
                    <input type="password" id="inputNewPwd" name="new_password" class="form-control" required />
                </div>
                <div class="form-outline mb-4 w-50">
                    <label class="form-label" for="inputPwdCheck">Confirmer le nouveau mot de passe</label>
                    <input type="password" id="inputPwdCheck" name="check_password" class="form-control" required />
                </div>
                <button type="submit" name="submit" class="btn btn-primary w-25">Changer le mot de passe</button>
            </form>
        </center>
    </main>
    <?php require_once "./include/footer.php"; ?>
</body>
</html>
