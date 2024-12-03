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
        $req = $conn->prepare("SELECT nom, prenom FROM CLIENT
                                    WHERE mail = ? AND password = ?") ;
        $req->execute([$email, password_hash($pwd, PASSWORD_DEFAULT)]);

        if ($client = $req->fetch()) {
            $nom = $result['nom'];
            $prenom = $result['prenom'];
            $_SESSION['user'] = $email;

            if($cookie) {
            setcookie('user_email', $email, time()+60*10);
            }
            // Renvoi sur la page en cours (could)
            if (isset($_SESSION['url'])) {
                $url = $_SESSION['url'];
                unset($_SESSION['url']);
                header("location:index.php");
                exit();
            }
            else {
                header("location:index.php");
                exit();
            }
        }
        else {
            header("location: formConnexion.php?msgErreur=L'adresse E-mail ou mot de passe est incorrect.");
            exit();
        }
    }
?>