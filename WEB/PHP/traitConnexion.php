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

        // Vérifie si les crédentials existent
        $req = $conn->prepare("SELECT idutilisateur, droit, password FROM UTILISATEUR
                                    WHERE mail = ?") ;
        $req->execute([$email]);

        if ($user = $req->fetch()) {
            if (password_verify($pwd, $user['password'])) { // Si le mdp correspond
                $_SESSION['user'] = $user['idutilisateur']; // On garde l'id
                $user_id = $user['idutilisateur'];
                if ($user['droit'] == "ADMIN") { // On vérifie ses droits
                    $_SESSION['admin'] = true;
                }

                if($cookie) {
                setcookie('user_email', $email, time()+60*10);
                }

                // Synchronisation du panier de session avec la base de données
                if (!empty($_SESSION['panier'])) {
                    foreach ($_SESSION['panier'] as $product_id => $quantity) {
                        // Vérifier si le produit est déjà dans le panier de la base de données
                        $reqCheck = $conn->prepare("SELECT QUANTITEPANIER FROM DETAILPANIER WHERE IDUTILISATEUR = :user_id AND IDPRODUIT = :product_id");
                        $reqCheck->execute(['user_id' => $user_id, 'product_id' => $product_id]);
                        $existing = $reqCheck->fetch(PDO::FETCH_ASSOC);

                        if ($existing) {
                            // Si le produit existe, mettre à jour la quantité
                            $new_quantity = $existing['QUANTITEPANIER'] + $quantity;
                            $reqUpdate = $conn->prepare("UPDATE DETAILPANIER SET QUANTITEPANIER = :quantity WHERE IDUTILISATEUR = :user_id AND IDPRODUIT = :product_id");
                            $reqUpdate->execute(['quantity' => $new_quantity, 'user_id' => $user_id, 'product_id' => $product_id]);
                        } else {
                            // Si le produit n'existe pas, l'ajouter
                            $reqInsert = $conn->prepare("INSERT INTO DETAILPANIER (IDUTILISATEUR, IDPRODUIT, QUANTITEPANIER) VALUES (:user_id, :product_id, :quantity)");
                            $reqInsert->execute(['user_id' => $user_id, 'product_id' => $product_id, 'quantity' => $quantity]);
                        }
                    }
                    // Vider le panier de session après la synchronisation
                    unset($_SESSION['panier']);
                }
                
                // Renvoi sur la page en cours (could)
                if (isset($_SESSION['url'])) {
                    $url = $_SESSION['url'];
                    unset($_SESSION['url']);
                    header("location:$url");
                    exit();
                }
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