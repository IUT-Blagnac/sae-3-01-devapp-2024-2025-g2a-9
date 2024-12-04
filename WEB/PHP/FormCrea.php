<?php
    $pageTitle = "Création de Compte";
    require_once "./include/head.php";
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
        <main role="main" class="container my-5" style='margin-top: 20px;'>
            <center>
                <?php
                if(isset($_GET['msgErreur'])){
                    echo '<div class="alert alert-danger w-25" role="alert">';
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
                <form>
                    <div class="row mb-4 w-50">
                        <div class="col">
                            <div data-mdb-input-init class="form-outline">
                                <label class="form-label" for="prenom">Prénom</label>
                                <input type="text"  id="prenom" class="form-control" />                   
                            </div>
                        </div>
                        <div class="col">
                            <div data-mdb-input-init class="form-outline">
                                <label class="form-label" for="nom">Nom</label>
                                <input type="text" id="nom" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <div data-mdb-input-init class="form-outline mb-4 w-50">
                        <label class="form-label" for="form6Example5">Email</label>
                        <input type="email" id="form6Example5" class="form-control" /> 
                    </div>
                </form>
            </center>
        </main>
        <!-- Pied de page -->
        <?php require_once "./include/footer.php"; ?>
    </body>
</html>