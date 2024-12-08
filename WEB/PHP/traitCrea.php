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
        $pays = htmlentities($_POST['pays']);

        if($mdp!=$verif){
            header("location: formCrea.php?msgErreur=Mot de passe saisie différent lors de la vérification");
            exit();
        }
        $reqmail = $conn->prepare("SELECT * FROM UTILISATEUR
                                   WHERE mail = ?") ;
        $reqmail->execute([$mail]);

        if ($user = $reqmail->fetch()) {
            header("location: formCrea.php?msgErreur=Compte déja existant pour cette adresse");
            exit();
        }else{
            $password = password_hash($mdp,PASSWORD_DEFAULT);
            $req = $conn->prepare("INSERT INTO UTILISATEUR (nom, prenom, civilite, dateN, mail, telephone, password, pays, droit)
                                   VALUES (?,?,?,?,?,?,?,?,'CLIENT')") ;
            $req->execute([$nom, $prenom, $civilite, $dateN, $mail, $Numtel, $password, $pays]);
            header("location:formConnexion.php");
            exit();
        }
    }
?>