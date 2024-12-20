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
    <main role="main" style="display: flex; flex-direction: column; height: 100%;">

        <!-- Fenêtre de détails flottante -->
        <div id="details-box" style="display: none;">
            <button id="close-details" style="float: right;">&times;</button>
            <h2>Détails du point relais</h2>
            <p id="details">Double-cliquez sur un point relais pour voir les détails ici.</p>
        </div>
        
        <!-- Carte OpenStreetMap -->
        <div id="map" style="flex: 1; width: 100%;"></div>

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
            
            map.zoomControl.remove();

            // Ajoutez des boutons "+" et "-" pour zoomer/dézoomer
            L.control.zoom({
                position: 'topright'
            }).addTo(map);

            // Récupérer et afficher la boîte de détails
            function showDetails(details) {
                const detailsBox = document.getElementById("details-box");
                const detailsContent = document.getElementById("details");

                detailsBox.style.display = "block";
                detailsContent.innerHTML = details;
            }

            // Fermer la boîte de détails
            document.getElementById("close-details").addEventListener("click", function () {
                const detailsBox = document.getElementById("details-box");
                detailsBox.style.display = "none";
            });

            // Remplacez par votre clé API OpenCage
            const apiKey = '433d051140a4440681e75e610ea8565d'; // Remplacez avec votre clé API

            // Récupérer les points relais depuis PHP et les placer sur la carte
            <?php
            foreach ($result as $pointRelais) {
                $adresse = addslashes($pointRelais['ADRESSEPOINTRELAIS']); // Sécuriser l'adresse
            ?>
                (function(adresse) {
                    var geocodeUrl = 'https://api.opencagedata.com/geocode/v1/json?q=' + encodeURIComponent(adresse) + '&key=' + apiKey;

                    // Récupérer les coordonnées via OpenCage Geocoder
                    fetch(geocodeUrl)
                        .then(response => response.json())
                        .then(data => {
                            if (data.results && data.results[0]) {
                                var lat = data.results[0].geometry.lat;
                                var lon = data.results[0].geometry.lng;
                                // Ajouter un marqueur sur la carte avec les coordonnées
                                var marker = L.marker([lat, lon]).addTo(map)
                                    .bindPopup('<b>' + adresse + '</b>'); // Affiche l'adresse correcte

                                marker.on('dblclick', function () {
                                    const details = `
                                        <h3>${adresse}</h3>
                                        <p>Horaires: 9h - 18h</p>
                                        <p>Contact: 01 23 45 67 89</p>
                                    `;
                                    showDetails(details);
                                });
                            } else {
                                console.log("Aucune donnée trouvée pour l'adresse: " + adresse);
                            }
                        })
                        .catch(error => {
                            console.error('Erreur de géocodage:', error);
                        });
                })('<?php echo $adresse; ?>');
            <?php
            }
            ?>

            // Rendre la fenêtre de détails déplaçable
            const detailsBox = document.getElementById("details-box");
            let isDragging = false;
            let offsetX, offsetY;

            detailsBox.addEventListener('mousedown', function (e) {
                // Initialisation des valeurs de déplacement
                isDragging = true;
                offsetX = e.clientX - detailsBox.getBoundingClientRect().left;
                offsetY = e.clientY - detailsBox.getBoundingClientRect().top;
                
                // Empêcher le texte de se sélectionner pendant le déplacement
                e.preventDefault();
            });

            document.addEventListener('mousemove', function (e) {
                if (isDragging) {
                    // Calcul du nouveau positionnement de la fenêtre
                    const newX = e.clientX - offsetX;
                    const newY = e.clientY - offsetY;

                    // Appliquer la nouvelle position à la fenêtre
                    detailsBox.style.left = `${newX}px`;
                    detailsBox.style.top = `${newY}px`;
                }
            });

            document.addEventListener('mouseup', function () {
                isDragging = false; // Arrêter le déplacement lorsqu'on relâche la souris
            });
        </script>
    </main>
</body>
</html>
