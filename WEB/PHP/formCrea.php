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
            <div>
                <a class="btn btn-primary" href="formConnexion.php" role="button">Retour</a>
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
                ?>
                <form method="post" action="traitCrea.php">
                    <div class="row mb-4 w-50">
                        <div class="col">
                            <div class="form-outline">
                                <label class="form-label" for="Inputprenom">Prénom</label>
                                <input type="text"  id="Inputprenom" name="prenom" class="form-control" required/>                   
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-outline">
                                <label class="form-label" for="Inputnom">Nom</label>
                                <input type="text" id="Inputnom" name="nom" class="form-control" required/>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4 w-50">
                        <div class="col">
                            <div class="form-outline">
                                <label class="form-label" for="Inputcivilite">Civilité</label>
                                <select data-mdb-select-init class="select w-100" name="civilite" required>
                                    <option value="MR">Mr</option>
                                    <option value="MME">Mme</option>
                                </select>                  
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-outline">
                                <label class="form-label" for="InputdateN">Date Naissance</label>
                                <input type="date" id="InputdateN" name="dateN" class="form-control w-100" required/>
                            </div>
                        </div>
                    </div>
                    <div class="form-outline mb-4 w-50">
                        <label class="form-label" for="Inputpays">Pays</label>
                        <input type="text" id="Inputpays" name="pays" class="form-control" required/>
                    </div>
                    <div class="form-outline mb-4 w-50">
                        <label class="form-label" for="Inputmail">Adresse E-mail</label>
                        <input type="email" id="Inputmail" name="mail" class="form-control" required/>
                    </div>
                    <div class="form-outline mb-4 w-50">
                        <label class="form-label" for="InputNumTel">Numéro de Téléphone</label>
                        <input type="number" id="InputNumTel" name="Numtel" class="form-control" />
                    </div>
                    <div class="form-outline mb-4 w-50">
                        <label class="form-label" for="Inputmdp">Mot de passe</label>
                        <input type="password" id="Inputmdp" name="mdp" class="form-control" required/>
                    </div>
                    <div  class="form-outline mb-4 w-50">
                        <label class="form-label" for="Inputverif">Vérification du mot de passe</label>
                        <input type="password" id="Inputverif" name="verif" class="form-control" required/>
                    </div>
                    <button  type="submit" name="submit" class="btn btn-primary w-50">Créer Compte</button>
                </form>
            </center>
        </main>
        <!-- Pied de page -->
        <?php require_once "./include/footer.php"; ?>
    </body>
</html>