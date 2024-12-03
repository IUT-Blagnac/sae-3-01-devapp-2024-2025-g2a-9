<?php require_once "./include/head.php" ?>

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
        <main role="main" class="container my-5" style='margin-top: 20px;'>
            <center>
                <?php
                if(isset($_GET['msgErreur'])){
                    echo '<h2>'.htmlentities($_GET['msgErreur']).'</h2>';
                }
                if (!empty($_COOKIE['mail'])) {
                    $cookie = $_COOKIE['mail'];
                }
                else {
                    $cookie = "";
                }
                ?>
                <form method="post" action="traitConnexion.php">
                    <!-- Mail -->
                    <label for="inputMail" class="form-label">Adresse E-mail</label>
                    <input type="email" class="form-control" id="inputMail" name="email" value="<?php echo htmlentities($cookie); ?>" require /><br>
                    <!-- Mot de passe -->
                    <label for="inputPwd" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" id="inputPwd" name="pwd" require/><br>
                    <!-- Checkbox -->
                    <div class="col d-flex justify-content-center">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="rememberMe" name="cookie"/>
                            <label class="form-check-label" for="rememberMe">Se souvenir de moi </label><br/><br/>
                        </div>
                    </div>
                    <!-- Oublie mot de passe peut etre plus tard :
                        
                    -->
                    <button type="submit" name="submit" class="btn btn-primary">Se connecter</button>
                </form>
            </center>
        </main>
    </body>
    <?php require_once "./include/footer.php" ?>
</html>