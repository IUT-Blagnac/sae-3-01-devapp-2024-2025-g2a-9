<?php require_once "./include/head.php" ?>

    <!-- En-tête -->
    <header class="header bg-white border-bottom">
        <div class="container-fluid d-flex align-items-center flex-wrap">
            <!-- Logo et nom de l'entreprise -->
            <div class="header-brand ms-3">
                <a href="#"><img src="image/logoNautic.png" alt="Logo" class="header-logo"></a>
                <a href="#" class="d-none d-md-inline text-decoration-none fw-bold">Nautic Horizon</a>
            </div>
        </div>
    </header>

    <!-- Contenu principal -->
    <main role="main" class="container my-5" style='margin-top: 20px;'>
        <?php
        if(isset($_GET['msgErreur'])){
            echo '<h2>'.htmlentities($_GET['msgErreur']).'</h2>';
        }
        if (!empty($_COOKIE[])) {
            $cookie = $_COOKIE[];
        }
        else {
            $cookie = "";
        }
        ?>
        <form method="post" action="traitConnexion.php">
            <!-- Mail -->
            <input type="email" name="email" value="<?php echo htmlentities($cookie); ?>" require/>
            <label class="form-label" for="email">Adresse Email</label><br><br>
            <!-- Mot de passe -->
            <input type="password" name="pwd" require/>
            <label class="form-label" for="pwd">Password</label><br><br>
            <!-- Checkbox -->
            <div class="col d-flex justify-content-center">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="rememberMe" name="cookie"/>
                    <label class="form-check-label" for="rememberMe">Se souvenir de moi </label><br/><br/>
                </div>
            </div>
            <!-- Oublie mot de passe peut etre plus tard :
             
            -->
            <input type="submit" name="Se connecter" value="Submit"/>
        </form> 
    </main>