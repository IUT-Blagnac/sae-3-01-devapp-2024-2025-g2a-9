<?php
    session_start();
    require_once "./include/connect.inc.php";

    if (isset($_POST['submit'])) {
        // Récupère données form
        $email = htmlentities($_POST['email']);
        $pwd = htmlentities($_POST['pwd']);
        if (isset($_POST['cookie'])) {
            $cookie = htmlentities($_POST['cookie']);
        } else {
            $cookie = null;
        }

        // Vérifie si les crédentials existents
        $req = $conn->prepare("SELECT nom, prenom, password FROM CLIENT
                                    WHERE mail = ?") ;
        $req->execute([$email]);

        if ($client = $req->fetch()) {
            if ($pwd === $client['password']) {
                $nom = $client['nom'];
                $prenom = $client['prenom'];
                $_SESSION['user'] = $email;

                if($cookie) {
                setcookie('user_email', $email, time()+60*10);
                }
                // Renvoi sur la page en cours (could)
                // if (isset($_SESSION['url'])) {
                //     $url = $_SESSION['url'];
                //     unset($_SESSION['url']);
                //     header("location:index.php");
                //     exit();
                // }
                header("location:index.php");
                exit();
            }
            else {
                header("location: formConnexion.php?msgErreur=Le mot de passe est incorrect.");
                exit();
            }
        }
        else {
            header("location: formConnexion.php?msgErreur=L'adresse e-mail est incorrect.");
            exit();
        }
    }
?>