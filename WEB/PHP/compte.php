<?php
    $pageTitle = "Votre compte";
    require_once "./include/head.php";
?>
<body>
    <?php
    require_once "./include/header.php";
    require_once "./include/menu.php";
    ?>
    <!-- Contenu principal -->
    <main role="main" class="container my-5">

        <div class="row gx-3 gx-lg-5">
            <div class="col-3 me-3 me-lg-5">
                <ul class="nav nav-tabs nav-tabs-vertical" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="infoPersoTab" data-bs-toggle="tab" href="#infoPersoPane" role="tab" aria-controls="infoPersoPane" aria-selected="true">Mes informations personnelles</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="commandesTab" data-bs-toggle="tab" href="#commandesPane" role="tab" aria-controls="commandesPane" aria-selected="false">Mes commandes</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="favorisTab" data-bs-toggle="tab" href="#favorisPane" role="tab" aria-controls="favorisPane" aria-selected="false">Mes produits favoris</a>
                    </li>
                </ul>
            </div>
            <div class="col-8">
                <div class="tab-content" id="tab-content" aria-orientation="vertical">
                    <div class="tab-pane active" id="infoPersoPane" role="tabpanel" aria-labelledby="infoPersoTab">
                        Voici vos informations personnelles :
                            <div class="card" style="width: 18rem;">
                                <div class="card-body">
                                    <h5 class="card-title">Informations générales</h5>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">Nom</li>
                                    <li class="list-group-item">Date de naissance</li>
                                    <li class="list-group-item">Civilité</li>
                                </ul>
                            </div>
                            <div class="card" style="width: 18rem;">
                                <div class="card-body">
                                    <h5 class="card-title">Coordonnées</h5>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">E-mail</li>
                                    <li class="list-group-item">Numéro de téléphone</li>
                                </ul>
                            </div>
                            <div class="card" style="width: 18rem;">
                                <div class="card-body">
                                    <h5 class="card-title">Adresses</h5>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <!-- For each adresses de livraison rattacher au client -->
                                </ul>
                            </div>   
                    </div>
                    <div class="tab-pane" id="commandesPane" role="tabpanel" aria-labelledby="commandesTab">
                        <div class="col d-flex justify-content-evenly">
                            <p class="card-text">Choisissez votre commande : </p>
                            <select name="commande" class="form-select w-25" aria-label="Default select example">
                                <?php
                                    $reqCommandes = $conn->prepare("SELECT * FROM COMMANDE WHERE idUtilisateur = ?;") ;
                                    $reqCommandes->execute();
                                    foreach($reqCommandes as $index => $commande) {
                                        if ($index == 0) {
                                            $selected = 'selected'; // Marque la commande comme sélectionnée
                                        } else {
                                            $selected = ''; // Pour les autres commandes, pas de sélection
                                        }
                                        echo "<option value='".$commande["idCommande"]."' $selected>".$commande["dateCommande"]."</option>";
                                    }
                                    $reqCommandes->closeCursor();
                                ?>
                            </select>
                        </div>
                        
                        Detail de la commande
                    </div>
                    <div class="tab-pane" id="favorisPane" role="tabpanel" aria-labelledby="favorisTab">
                        Vos articles favoris :
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Pied de page -->
    <?php require_once "./include/footer.php"; ?>
</body>
</html>