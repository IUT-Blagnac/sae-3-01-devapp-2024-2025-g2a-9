<?php
    require_once "./include/connect.inc.php";

    if (isset($_POST['submit'])) {
        // Récupère données form
        $mail = htmlentities($_POST['mail']);
        $mdp = htmlentities($_POST['mdp']);
        $prenom = htmlentities($_POST['prenom']);
        $nom = htmlentities($_POST['nom']);
        $dateN = htmlentities($_POST['dateN']);
        $civilite = htmlentities($_POST['civilite']);
        $Numtel = htmlentities($_POST['Numtel']);
        $verif = htmlentities($_POST['verif']);

        if($mdp!=$verif){
            header("location: FormCrea.php?msgErreur=Mot de passe saisie différent lors de la vérification");
            exit();
        }

        $req = $conn->prepare("INSERT INTO Client (nom,prenom,civilite,dateN,mail,telephone,password)
                               VALUES (?,?,?,?,?,?,?)") ;
        $req->execute([$nom,$prenom,$civilite,$dateN,$mail,$Numtel,$mdp]);
    }