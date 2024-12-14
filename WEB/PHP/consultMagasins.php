<?php
$pageTitle = "Magasins";
require_once "./include/head.php";
require_once "./include/connect.inc.php"; // Connexion PDO

// Préparer et exécuter la requête PDO pour récupérer les points relais
$reqAdresses = $conn->prepare("SELECT * FROM POINTRELAIS");
$reqAdresses->execute();
$result = $reqAdresses->fetchAll(PDO::FETCH_ASSOC);
?>

<body>
    <?php
    require_once "./include/header.php";
    require_once "./include/menu.php";
    ?>

    <!-- Contenu principal -->
    <main role="main">
        
        <!-- Carte OpenStreetMap -->
        <div id="map" style="height: 100vh; width: 100%;"></div>

        <!-- Intégration de Leaflet (OpenStreetMap) -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

        <script>
            // Créez la carte
            var map = L.map('map').setView([46.603354, 1.888334], 6); // Vue initiale sur la France

            // Définir les limites maximales de la carte (bornes de la carte)
            var maxBounds = L.latLngBounds(
                L.latLng(41.0, -5.0),  // Latitude minimale, longitude minimale (coin sud-ouest)
                L.latLng(51.0, 9.5)    // Latitude maximale, longitude maximale (coin nord-est)
            );

            // Appliquer ces bornes à la carte pour empêcher le déplacement au-delà
            map.setMaxBounds(maxBounds);

            // Désactivez le zoom par molette par défaut
            map.scrollWheelZoom.disable();

            // Autoriser le zoom/dézoom uniquement avec Ctrl + Molette
            map.on('keydown', function (e) {
                if (e.originalEvent.key === "Control") {
                    map.scrollWheelZoom.enable(); // Activer le zoom par molette
                }
            });
            map.on('keyup', function (e) {
                if (e.originalEvent.key === "Control") {
                    map.scrollWheelZoom.disable(); // Désactiver le zoom par molette
                }
            });
            
            map.doubleClickZoom.disable();

            // Ajoutez une couche de tuiles
            L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}.png', {
                maxZoom: 19,    // Zoom maximum
                minZoom: 3,     // Zoom minimum (ajoutez cette ligne)
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
            

            // Ajoutez des boutons "+" et "-" pour zoomer/dézoomer
            L.control.zoom({
                position: 'topright'
            }).addTo(map);

            // Récupérer les points relais depuis PHP et les placer sur la carte
            <?php
            foreach ($result as $pointRelais) {
                $adresse = addslashes($pointRelais['ADRESSEPOINTRELAIS']); // Sécuriser l'adresse
            ?>
                (function(adresse) {
                    var geocodeUrl = 'https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(adresse);

                    // Récupérer les coordonnées via Nominatim
                    fetch(geocodeUrl)
                        .then(response => response.json())
                        .then(data => {
                            if (data && data[0]) {
                                var lat = parseFloat(data[0].lat);
                                var lon = parseFloat(data[0].lon);
                                // Ajouter un marqueur sur la carte avec les coordonnées
                                L.marker([lat, lon]).addTo(map)
                                    .bindPopup('<b>' + adresse + '</b>'); // Affiche l'adresse correcte
                            } else {
                                console.log("Aucune donnée trouvée pour l'adresse: " + adresse);
                            }
                        })
                        .catch(error => {
                            console.error('Erreur de géocodage:', error);
                        });
                })('<?php echo $adresse; ?>'); // IIFE (Immediately Invoked Function Expression) pour chaque itération
            <?php
            }
            ?>
        </script>
    </main>

    <!-- Pied de page -->
    <?php require_once "./include/footer.php"; ?>
</body>
</html>
