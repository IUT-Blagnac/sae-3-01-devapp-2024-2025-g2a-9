<?php
$pageTitle = "Page d'accueil";
require_once "./include/head.php";

?>
    
<body>
    <?php
    require_once "./include/header.php";
    require_once "./include/menu.php";
    require_once './include/connect.inc.php';

    $query = $conn->prepare("SELECT * FROM PRODUIT ORDER BY IDPRODUIT ASC");
    $query->execute();
    $produits = $query->fetchAll(PDO::FETCH_ASSOC);
    $query->closeCursor();
    ?>
    
    <!-- Contenu principal -->
    <main role="main">

        <!-- Première section: Image en plein écran avec texte -->
            <section class="first-section" style="position: relative; height: 75vh; background-image: url('image/bateau.jpg'); background-size: cover; background-position: center;">
                <!-- Overlay sombre uniquement sur l'image -->
                <div class="overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 1;"></div>
                <!-- Texte au-dessus de l'image -->
                <div class="container-fluid h-100 d-flex justify-content-center align-items-center text-center text-white" style="position: relative; z-index: 2;">
                    <div>
                        <h3 class="display-6">Éloignez-vous de la vie urbaine</h3>
                        <h1 class="display-4" style="font-weight: bold; font-size: 4rem;">Détendez votre esprit au large</h1>
                    </div>
                </div>
            </section>

        <!-- Deuxième section: Choisissez le bateau -->
        <style>
            /* Augmenter la taille des icônes de navigation */
            .carousel-control-prev-icon, .carousel-control-next-icon {
                width: 4rem;  /* Augmente la taille de l'icône */
                height: 4rem; /* Augmente la hauteur de l'icône */
                background-size: 100%, 100%; /* Assure que l'icône conserve sa forme */
            }
            
            .carousel-control-prev,
            .carousel-control-prev > span {
                margin-right: 5rem !important;
            }

            .carousel-control-next,
            .carousel-control-next > span {
                margin-left: 5rem !important;
            }

            .carousel-item .card {
                margin: 0 15px; /* Ajoute une marge de 15px à gauche et à droite */
            }

            .card {
                overflow: hidden; /* Pour éviter que l'image dépasse les limites de la carte */
                transition: transform 0.3s ease; /* Animation fluide */
            }

            .card:hover {
                transform: scale(1.05); /* Augmente légèrement la taille de la carte */
            }

            .carousel-item {
                transition: transform 0.3s ease, opacity 0.3s ease;
            }

            @media (max-width: 767px) {
                .carousel-inner .carousel-item > div {
                    display: none;
                }
                .carousel-inner .carousel-item > div:first-child {
                    display: block;
                }
            }

            .carousel-inner .carousel-item.active,
            .carousel-inner .carousel-item-next,
            .carousel-inner .carousel-item-prev {
                display: flex;
            }

            /* medium and up screens */
            @media (min-width: 768px) {
                
                .carousel-inner .carousel-item-end.active,
                .carousel-inner .carousel-item-next {
                transform: translateX(25%);
                }
                
                .carousel-inner .carousel-item-start.active, 
                .carousel-inner .carousel-item-prev {
                transform: translateX(-25%);
                }
            }

            .carousel-inner .carousel-item-end,
            .carousel-inner .carousel-item-start { 
            transform: translateX(0);
            }
        </style>
        <section class="second-section py-5">
            <div class="container text-center my-3">
                <div class="row mx-auto my-auto justify-content-center">
                    <h2 class="my-5 display-6 font-weight-bold">Choisissez le bateau qui vous inspire</h2>
                    <div id="recipeCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner" role="listbox">
                            <?php
                            $limiteProduits = array_intersect_key($produits, array_flip([8, 12, 15, 17, 19, 21, 22, 23]));
                            $firstItem = true; // Variable pour identifier le premier produit
                            foreach ($limiteProduits as $produit) :
                            ?>
                             <div class="carousel-item <?php if ($firstItem) { echo 'active'; $firstItem = false; } ?>">
                                <!-- Envelopper toute la carte dans un lien cliquable -->
                                <a href="detailProduit.php?id=<?= htmlspecialchars($produit['IDPRODUIT']); ?>" style="text-decoration: none; color: inherit;">
                                    <div class="card" style="border: none; background-color: #F8F9FA;">
                                        <!-- Affichage de l'image du produit -->
                                        <img src="./image/produit/prod<?= htmlspecialchars($produit['IDPRODUIT']); ?>.png" 
                                            class="card-img-top" 
                                            style="width: 100%; height: 250px; object-fit: cover;"
                                            alt="<?= htmlspecialchars($produit['NOMPRODUIT']); ?>">
                                        <div class="card-body text-center">
                                            <!-- Nom du produit -->
                                            <h4 class="card-title" style="font-size: 1.6rem;"><?= htmlspecialchars($produit['NOMPRODUIT']); ?></h4>
                                            <!-- Prix du produit -->
                                            <p class="card-text">Prix : <?= number_format($produit['PRIX'], 2, ',', ' '); ?> €</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <a class="carousel-control-prev bg-transparent w-auto" href="#recipeCarousel" role="button" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        </a>
                        <a class="carousel-control-next bg-transparent w-auto" href="#recipeCarousel" role="button" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        </a>
                    </div>
                </div>
            </div>
        </section>
        <script>
            let items = document.querySelectorAll('.carousel .carousel-item')
            items.forEach((el) => {
                const minPerSlide = 4
                let next = el.nextElementSibling
                for (var i=1; i<minPerSlide; i++) {
                    if (!next) {
                        // wrap carousel by using first child
                        next = items[0]
                    }
                    let cloneChild = next.cloneNode(true)
                    el.appendChild(cloneChild.children[0])
                    next = next.nextElementSibling
                }
            })
        </script>


        <!-- Troisième section: Nos types de bateau proposés -->
        <section class="third-section py-5" style="position: relative; background-image: url('image/bateau-interieur.jpg'); background-size: cover; background-position: center;">
            <!-- Overlay sombre -->
            <div class="overlay" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0, 0, 0, 0.5); z-index: 1;"></div>

            <div class="container text-center text-white" style="position: relative; z-index: 2;">
                <h2 class="display-4">Nos types de bateau proposés</h2>
                <div class="row mt-5">
                    <!-- Type de bateau 1 -->
                    <div class="col-md-4 mb-4">
                        <div class="card" style="background-color: rgba(0, 0, 0, 0.6); color: white; display: flex; flex-direction: column; justify-content: space-between;">
                            <div class="card-body text-center" style="flex-grow: 1;">
                                <h5 class="card-title font-weight-bold" style="font-size: 1.6rem;">Bateaux de transport</h5>
                                <p class="card-text">Les bateaux de transport sont conçus pour transporter des passagers ou des marchandises sur de longues distances. Ils sont généralement équipés de moteurs puissants et sont conçus pour être robustes et spacieux.</p>
                            </div>
                        </div>
                    </div>
                    <!-- Type de bateau 2 -->
                    <div class="col-md-4 mb-4">
                        <div class="card" style="background-color: rgba(0, 0, 0, 0.6); color: white; display: flex; flex-direction: column; justify-content: space-between;">
                            <div class="card-body text-center" style="flex-grow: 1;">
                                <h5 class="card-title font-weight-bold" style="font-size: 1.6rem;">Bateaux de plaisance</h5>
                                <p class="card-text">Les bateaux de plaisance sont Confortables et élégants, conçus pour offrir une expérience agréable en mer ou sur des plans d'eau tranquilles, permettant ainsi de profiter pleinement de moments de détente en toute sérénité.</p>
                            </div>
                        </div>
                    </div>
                    <!-- Type de bateau 3 -->
                    <div class="col-md-4 mb-4">
                        <div class="card" style="background-color: rgba(0, 0, 0, 0.6); color: white; display: flex; flex-direction: column; justify-content: space-between;">
                            <div class="card-body text-center" style="flex-grow: 1;">
                                <h5 class="card-title font-weight-bold" style="font-size: 1.6rem;">Bateaux de loisirs</h5>
                                <p class="card-text">Les bateaux de loisirs sont parfaits pour des activités comme la pêche, la baignade ou même la navigation tranquille. Polyvalents, ils offrent un excellent rapport qualité-prix et sont parfaits pour ceux qui recherchent à la fois confort et fonctionnalité.</p>
                            </div>
                        </div>
                    </div>
                    <!-- Type de bateau 4 -->
                    <div class="col-md-4 mb-4">
                        <div class="card" style="background-color: rgba(0, 0, 0, 0.6); color: white; display: flex; flex-direction: column; justify-content: space-between;">
                            <div class="card-body text-center" style="flex-grow: 1;">
                                <h5 class="card-title font-weight-bold" style="font-size: 1.6rem;">Bateaux de pêche</h5>
                                <p class="card-text">Les bateaux de pêche sont spécialement conçus pour les passionnés de pêche. Avec des caractéristiques comme des espaces de rangement et des plateformes de pêche, ils assurent confort et efficacité lors de vos sorties en mer.</p>
                            </div>
                        </div>
                    </div>
                    <!-- Type de bateau 5 -->
                    <div class="col-md-4 mb-4">
                        <div class="card" style="background-color: rgba(0, 0, 0, 0.6); color: white; display: flex; flex-direction: column; justify-content: space-between;">
                            <div class="card-body text-center" style="flex-grow: 1;">
                                <h5 class="card-title font-weight-bold" style="font-size: 1.6rem;">Bateaux non motorisés</h5>
                                <p class="card-text">Les bateaux non motorisés sont parfaits pour ceux qui souhaitent une expérience calme et apaisante sur l'eau. Ces bateaux comprennent des canoës, kayaks et petites embarcations à pagaie, idéaux pour la détente et l'exploration.</p>
                            </div>
                        </div>
                    </div>
                    <!-- Type de bateau 6 -->
                    <div class="col-md-4 mb-4">
                        <div class="card" style="background-color: rgba(0, 0, 0, 0.6); color: white; display: flex; flex-direction: column; justify-content: space-between;">
                            <div class="card-body text-center" style="flex-grow: 1;">
                                <h5 class="card-title font-weight-bold" style="font-size: 1.6rem;">Bateaux spécialisés</h5>
                                <p class="card-text">Les bateaux spécialisés sont conçus pour des utilisations spécifiques comme les excursions en mer ou les expéditions en eaux peu profondes. Chaque modèle est adapté à des besoins particuliers, offrant une performance optimale pour sa vocation.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Nouvelle section avec texte à gauche et image à droite -->
        <section class="fourth-section py-5 my-5" style="background-color: #f8f9fa;">
            <div class="container">
                <div class="row align-items-center">
                    <!-- Texte et bouton à gauche -->
                    <div class="col-md-6">
                        <h2 class="display-6 font-weight-bold">Apprenez en plus sur nous</h2>
                        <a href="apropos.php" class="btn btn-primary btn-lg mt-4">En savoir plus</a>
                    </div>
                    <!-- Image à droite -->
                    <div class="col-md-6">
                        <img src="image/logo_nautic.png" alt="Bateau en mer" class="img-fluid rounded">
                    </div>
                </div>
            </div>
        </section>

    </main>

    <script>
        (function () {
            document.head.insertAdjacentHTML('beforeend', '<link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.16/tailwind.min.css" rel="stylesheet">');

            const style = document.createElement('style');
            style.innerHTML = `
        .hidden {
            display: none;
        }
        #chat-widget-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            flex-direction: column;
            z-index: 1000;
        }
        
        #chat-popup {
            height: 50vh;
            max-height: 50vh;
            transition: all 0.3s;
            overflow: hidden;
            position:relative;
            z-index: 1000;
        }

        .content-loader {
            display: none;
            padding: 12px 20px;
            position: absolute;
            z-index: 1;
            right: 50px;
            bottom: 100px;
        }
        
        .typing-loader::after {
            content: "Gemini est en train d'écrire.....";
            animation: typing 1s steps(1) infinite, blink .75s step-end infinite;
            font-size:10px;
        }
        
        @keyframes typing {
            from,to { width: 0; }
            50% { width: 15px; }
        }
        
        @keyframes blink {
            50% { color: transparent; }
        }
        @media (max-width: 768px) {
            #chat-popup {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100%;
            max-height: 100%;
            border-radius: 0;
            }
        }

        `;

            document.head.appendChild(style);

            // Create container for chat widget
            const chatWidgetContainer = document.createElement('div');
            chatWidgetContainer.id = 'chat-widget-container';
            document.body.appendChild(chatWidgetContainer);

            chatWidgetContainer.innerHTML = `
            <div id="chat-bubble" class="text-white fa fa-comments w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center cursor-pointer text-3xl">
            </div>
            <div id="chat-popup" class="hidden absolute bottom-20 right-0 w-96 bg-white rounded-md shadow-md flex flex-col transition-all text-sm">
                <div id="chat-header" class="flex justify-between items-center p-4 bg-blue-500 text-white">
                    <h3 class="m-0 text-lg">Assistant IA Nautic Horizon</h3>
                    <button id="close-popup" class="bg-transparent border-none text-white cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    </button>
                </div>
                <div class="content-loader">
                <div class="typing-loader"></div>
            </div>
            <div id="chat-messages" class="flex-1 p-4 overflow-y-auto"></div>
            <div id="chat-input-container" class="p-4 border-t border-blue-200">
                <div class="flex space-x-4 items-center">
                <input type="text" id="chat-input" class="flex-1 border border-blue-300 rounded-md px-4 py-2 outline-none w-3/4" placeholder="Type your message...">
                <button id="chat-submit" class="bg-blue-800 text-white rounded-md px-4 py-2 cursor-pointer">Send</button>
                </div>
            </div>
            </div>
        `;

            // Add event listeners
            const chatInput = document.getElementById('chat-input');
            const chatSubmit = document.getElementById('chat-submit');
            const chatBubble = document.getElementById('chat-bubble');
            const chatPopup = document.getElementById('chat-popup');
            const chatMessages = document.getElementById('chat-messages');
            const loader = document.querySelector('.content-loader');
            const closePopup = document.getElementById('close-popup');

            chatSubmit.addEventListener('click', function () {

                const message = chatInput.value.trim();
                if (!message) return;

                chatMessages.scrollTop = chatMessages.scrollHeight;

                chatInput.value = '';

                onUserRequest(message);

            });

            chatInput.addEventListener('keyup', function (event) {
                if (event.key === 'Enter') {
                    chatSubmit.click();
                }
            });

            chatBubble.addEventListener('click', function () {
                console.log('Chat bubble clicked, current style:', chatBubble.style);
                togglePopup();
            });

            closePopup.addEventListener('click', function () {
                togglePopup();
            });

            function togglePopup() {
                const chatPopup = document.getElementById('chat-popup');
                chatPopup.classList.toggle('hidden');
                if (!chatPopup.classList.contains('hidden')) {
                    document.getElementById('chat-input').focus();
                }
            }

            function highlightContactDetails(text) {
                // Email regex
                const emailRegex = /\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z|a-z]{2,}\b/g;
                // Phone number regex
                const phoneRegex = /(\b\+?1\s)?\(?\d{3}\)?[-.\s]?\d{3}[-.\s]?\d{4}\b/g;
                // Simples URL regex
                const urlRegex = /\b((http|https):\/\/)?[a-z0-9\.-]+\.[a-z]{2,}[^\s]*\b/g;

                // Replace and add mark tag for highlighting
                text = text.replace(emailRegex, '<mark>$&</mark>');
                text = text.replace(phoneRegex, '<mark>$&</mark>');
                text = text.replace(urlRegex, '<mark>$&</mark>');

                return text;
            }

            function onUserRequest(message) {
                // Afficher le message de l'utilisateur
                const messageElement = document.createElement('div');
                messageElement.className = 'flex justify-end mb-3';
                messageElement.innerHTML = `
                    <div class="bg-blue-800 text-white rounded-lg py-2 px-4 max-w-[70%]">
                        ${message}
                    </div>
                `;
                chatMessages.appendChild(messageElement);
                chatMessages.scrollTop = chatMessages.scrollHeight;

                chatInput.value = '';

                const systemPrompt = "Vous êtes un agent de support client chez Nautic Horizon, une entreprise spécialisée dans le transport maritime et les loisirs, incluant des bateaux, yachts, voiliers et équipements associés. Votre rôle est d'aider les clients à naviguer sur le site, répondre à leurs questions concernant les produits, les services, les commandes, la location ou l'achat de bateaux, et d'autres informations liées aux activités nautiques. Vous devez répondre de manière concise et précise, en restant focalisé sur les besoins du client. Ne répondez pas aux questions hors de ce contexte.\n\nPersonne : Qui êtes-vous ?\n Je suis un agent de support client chez Nautic Horizon, spécialisé dans les loisirs nautiques et le transport maritime.\n\nPersonne : Où puis-je acheter un bateau ?\n Vous pouvez acheter nos bateaux directement sur notre site web, selon vos besoins.\n\nPersonne : Pouvez-vous me donner plus de détails sur vos yachts ou voiliers ?\n Nous proposons une large gamme de yachts et voiliers adaptés à différents besoins, allant des modèles de loisirs aux modèles plus professionnels. Que recherchez-vous exactement ?, finalement seulement quand la personne mentionne la franchise et SEULEMENT quand la personne mentionne la franchise : repond lui un fait aléatoire sur les bateaux mais ne parle pas de franchise, et tant qu'elle a pas ecrit le mot franchise repond normalement";

                // Configuration de l'URL et des données pour l'API Gemini
                let url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=AIzaSyACKCs7NRVr7TeviDZoBRNmDaXRoCJyR28"; // Remplacez par votre clé API Gemini
                let headers = {
                    "Content-Type": "application/json"
                };

                let body = {
                    "contents": [{
                        "parts": [{
                            "text": systemPrompt + "\nPersonne : " + message
                        }]
                    }]
                };

                // Affichage du loader
                loader.style.display = 'inline-block';

                // Appel à l'API Gemini
                fetch(url, {
                    method: 'POST',
                    headers: headers,
                    body: JSON.stringify(body)
                })
                .then(response => response.json())
                .then(data => {
                    loader.style.display = 'none';

                    // Extraire la réponse de Gemini et l'afficher
                    const replyMessage = data.candidates[0].content.parts[0].text;
                    reply(replyMessage);
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    loader.style.display = 'none';
                });
            }

            function reply(message) {
                const chatMessages = document.getElementById('chat-messages');
                const replyElement = document.createElement('div');
                replyElement.className = 'flex mb-3';
                replyElement.innerHTML = `
                    <div class="bg-blue-200 text-black rounded-lg py-2 px-4 max-w-[70%]">
                        ${highlightContactDetails(message)}
                    </div>
                `;
                chatMessages.appendChild(replyElement);
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }

        })();
    </script>
    <?php require_once "./include/footer.php"; ?>
</body>
</html>