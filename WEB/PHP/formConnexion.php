<?php 
    $pageTitle = "Connexion";
    require_once "./include/head.php" 
?>

    <!-- En-tête -->
    <header class="header bg-white border-bottom">
        <div class="container-fluid d-flex align-items-center flex-wrap">
            <!-- Logo et nom de l'entreprise -->
            <div class="header-brand ms-3">
                <a href="index.php"><img src="image/logoNautic.png" alt="Logo" class="header-logo"></a>
                <a href="index.php" class="d-none d-md-inline text-decoration-none fw-bold">Nautic Horizon</a>
            </div>
        </div>
    </header>

    <body>
        <!-- Contenu principal -->
        <main role="main" class="container mt-10 my-5">
            <center>
                <?php
                if(isset($_GET['msgErreur'])){
                    echo '<div class="alert alert-danger w-50" role="alert">';
                    echo '<strong>Un problème est survenu</strong><br>';
                    echo htmlentities($_GET['msgErreur']);
                    echo '</div>';
                }
                if (!empty($_COOKIE['user_email'])) {
                    $cookie = $_COOKIE['user_email'];
                }
                else {
                    $cookie = "";
                }
                ?>
                <form method="post" action="traitConnexion.php">
                    <!-- Mail -->
                    <label for="inputMail" class="form-label">Adresse E-mail</label>
                    <input type="email" class="form-control w-50" id="inputMail" name="email" value="<?php echo htmlentities($cookie); ?>" maxlength="50" require /><br>
                    <!-- Mot de passe -->
                    <label for="inputPwd" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control w-50" id="inputPwd" name="pwd" maxlength="30" require/><br>
                    <!-- Checkbox -->
                    <div class="col d-flex justify-content-evenly">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="rememberMe" name="cookie"/>
                            <label class="form-check-label" for="rememberMe">Se souvenir de moi </label>
                        </div>
                        <!-- Fonctionnalité could -->
                        <a href="resetMDP.php">Mot de passe oublié ?</a>
                    </div><br>
                    <!-- Bouton confirm -->
                    <button type="submit" name="submit" class="btn btn-primary">Se connecter</button><br><br>
                    <!-- Inscription -->
                    <div>
                        <p>Pas de compte ? <a href="formCrea.php">S'inscrire</a></p>
                    </div>
                </form>
            </center>
        </main>
    </body>
    <?php require_once "./include/footer.php" ?>
    <script src="javascript/script.js"></script>
</html>