<?php
if (!isset($_SESSION['user'])) {
    header('Location: formConnexion.php?msgErreur=Vous devez être connecté pour accéder à cette page !'); // Rediriger vers la page de connexion
    exit();
}
?>