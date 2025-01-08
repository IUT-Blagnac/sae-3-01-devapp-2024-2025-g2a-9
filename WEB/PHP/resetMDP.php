<?php 
$pageTitle = "Récupérez votre mot de passe";
require_once "./include/head.php";
require_once "./include/connect.inc.php";

if (isset($_POST['reset_password'])) {
    // Récupérer les informations du formulaire
    $email = $_POST['email'];
    $dob = $_POST['dob'];
    $phone = $_POST['phone'];
    $newPwd = $_POST['new_pwd'];
    $confirmPwd = $_POST['confirm_pwd'];

    // Vérifier que les mots de passe correspondent
    if ($newPwd !== $confirmPwd) {
        header("Location: resetMDP.php?msgErreur=Les mots de passe ne correspondent pas.");
        exit();
    } else {
        try {
            // Préparer la requête pour vérifier les informations de l'utilisateur
            $stmt = $conn->prepare("SELECT * FROM UTILISATEUR WHERE MAIL = :email AND DATEN = :dob AND TELEPHONE = :phone");
            // Lier les paramètres
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':dob', $dob, PDO::PARAM_STR);
            $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);

            // Exécuter la requête
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Vérifier si l'utilisateur existe
            if ($user) {
                // L'utilisateur a été trouvé, on peut mettre à jour le mot de passe
                $hashedPwd = password_hash($newPwd, PASSWORD_DEFAULT);

                // Mettre à jour le mot de passe dans la base de données
                $updateStmt = $conn->prepare("UPDATE UTILISATEUR SET PASSWORD = :password WHERE MAIL = :email");
                // Lier les paramètres pour la mise à jour
                $updateStmt->bindParam(':password', $hashedPwd, PDO::PARAM_STR);
                $updateStmt->bindParam(':email', $email, PDO::PARAM_STR);

                // Exécuter la mise à jour
                $updateStmt->execute();

                if ($updateStmt->rowCount() > 0) {
                    header("Location: resetMDP.php?msgSuccess=Mot de passe réinitialisé avec succès.");
                } else {
                    header("Location: resetMDP.php?msgErreur=Erreur lors de la mise à jour du mot de passe.");
                }
            } else {
                header("Location: resetMDP.php?msgErreur=Les informations fournies ne correspondent pas à un utilisateur existant.");
            }
        } catch (PDOException $e) {
            header("Location: resetMDP.php?msgErreur=Erreur de connexion à la base de données.");
        }
    }
}
?>

<!-- HTML (formulaire de réinitialisation) -->
<header class="header bg-white border-bottom">
    <div class="container-fluid d-flex align-items-center flex-wrap">
        <div class="header-brand ms-3">
            <a href="index.php"><img src="image/logoNautic.png" alt="Logo" class="header-logo"></a>
            <a href="index.php" class="d-none d-md-inline text-decoration-none fw-bold">Nautic Horizon</a>
        </div>
    </div>
</header>

<body>
    <main role="main" class="container mt-10 my-5">
        <center>
            <?php
            // Afficher les messages d'erreur ou de succès
            if (isset($_GET['msgErreur'])) {
                echo '<div class="alert alert-danger w-50" role="alert">';
                echo '<strong>Erreur :</strong> ' . htmlentities($_GET['msgErreur']);
                echo '</div>';
            }
            if (isset($_GET['msgSuccess'])) {
                echo '<div class="alert alert-success w-50" role="alert">';
                echo '<strong>Succès :</strong> ' . htmlentities($_GET['msgSuccess']);
                echo '</div>';
            }
            ?>
            <form method="post" onsubmit="return validatePassword()">
                <label for="inputMail" class="form-label">Adresse E-mail</label>
                <input type="email" class="form-control w-50" id="inputMail" name="email" maxlength="50" required /><br>

                <label for="inputDob" class="form-label">Date de naissance</label>
                <input type="date" class="form-control w-50" id="inputDob" name="dob" required /><br>

                <label for="inputPhone" class="form-label">Numéro de téléphone</label>
                <input type="text" class="form-control w-50" id="inputPhone" name="phone" maxlength="15" required /><br>

                <label for="inputNewPwd" class="form-label">Nouveau mot de passe</label>
                <input type="password" class="form-control w-50" id="inputNewPwd" name="new_pwd" maxlength="30" required /><br>

                <label for="inputConfirmPwd" class="form-label">Confirmer nouveau mot de passe</label>
                <input type="password" class="form-control w-50" id="inputConfirmPwd" name="confirm_pwd" maxlength="30" required /><br>

                <button type="submit" name="reset_password" class="btn btn-primary">Réinitialiser le mot de passe</button><br><br>

                <div>
                    <p>Retour à la <a href="login.php">connexion</a></p>
                </div>
            </form>
        </center>
    </main>
</body>

<?php require_once "./include/footer.php" ?>

<script src="javascript/script.js"></script>
</html>

<!-- JavaScript pour validation des mots de passe -->
<script>
    function validatePassword() {
        var newPwd = document.getElementById('inputNewPwd').value;
        var confirmPwd = document.getElementById('inputConfirmPwd').value;

        if (newPwd !== confirmPwd) {
            alert("Les mots de passe ne correspondent pas.");
            return false; // Empêche la soumission du formulaire
        }
        return true; // Permet la soumission du formulaire
    }
</script>
