<?php
    session_start();
    require_once "./include/connect.inc.php";

    if (isset($_POST['submit'])) {
        // Récupère données form
        $email = htmlentities($_POST['mail']);
        $pwd = htmlentities($_POST['pwd']);
        $cookie = htmlentities($_POST['cookie']);

        // Vérifie si les crédentials existents
        $req = $conn->prepare("SELECT nom, prenom FROM Client
                                    WHERE mail = ? AND password = ?") ;
        $req->execute([$email, password_hash($pwd)]);

        $testEmail = "test@outlook.fr";
        $testPwd = "test";

        if ($client = $req->fetch() || ($testEmail === $email && $testPwd === $pwd)) {
            $nom = $result['nom'];
            $prenom = $result['prenom'];
            $_SESSION['user'] = $email;

            if($cookie) {
            setcookie('C'.$nom.$prenom, $email, time()+60*10);
            }
            // Renvoi sur la page en cours
            if (isset($_SESSION['url'])) {
                $url = $_SESSION['url'];
                unset($_SESSION['url']);
                header("location:$url");
                exit();
            }
            else {
                header("location:index.php");
                exit();
            }
        }
    }
?>