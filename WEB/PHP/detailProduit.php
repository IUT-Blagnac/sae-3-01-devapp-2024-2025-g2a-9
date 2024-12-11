<?php
$pageTitle = "Detail produit";
require_once "./include/head.php";
?>
<body>
<?php
    require_once "./include/header.php";
    require_once "./include/menu.php";
    require_once './include/connect.inc.php';

    // // Simuler des produits dans le panier pour un utilisateur non connecté
    // if (!isset($_SESSION['user'])) { // Si l'utilisateur n'est pas connecté
    //     // Ajouter des produits fictifs si le panier est vide
    //     if (empty($_SESSION['panier'])) {
    //         $_SESSION['panier'] = [
    //             1 => 2, // ID du produit => Quantité
    //             2 => 1,
    //             3 => 5
    //         ];
    //     }
    // }

    // $password = password_hash("pass1234",PASSWORD_DEFAULT);
    //         $req = $conn->prepare("INSERT INTO UTILISATEUR (nom, prenom, civilite, mail, password, droit)
    //                                VALUES (?,?,?,?,?,'CLIENT')") ;
    //         $req->execute(["test", "test", "MR", "test@mail.com", $password]);
?>
    <!-- Contenu principal -->
    <main role="main" class="container my-5">
        <h2>Votre panier</h2>

    </main>

    <!-- Pied de page -->
    <?php require_once "./include/footer.php"; ?>
</body>
</html>