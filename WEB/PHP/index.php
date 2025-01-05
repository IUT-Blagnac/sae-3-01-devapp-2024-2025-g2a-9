<?php
$pageTitle = "Page d'accueil";
require_once "./include/head.php";

?>
    
<body>
    <?php
    require_once "./include/header.php";
    require_once "./include/menu.php";
    ?>
    <!-- Contenu principal -->
    <main role="main">
    <center>
        <img src="image/m3-banner.jpeg" class="d-block w-100">
        <br>
        <br>
        <h2>OFFRE DU MOMENT</h2>
        <p>Découvrez et profitez de nos meilleures offres sur l’ensemble de la gamme de Nautic Horizon</p>
        <div id="carouselExampleDark" class="carousel carousel-dark slide bg-secondary" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner w-100 ">
                <div class="carousel-item active" data-bs-interval="10000">
                    <img src="image/produit/test20.jpg" class="d-block w-100">
                    <div class="carousel-caption d-none d-md-block text-white">
                        <h5>Yacht</h5>
                        <a class="btn btn-primary" href="detailProduit.php?id=1" role="button">Voir plus</a>
                    </div>
                </div>
                <div class="carousel-item" data-bs-interval="2000">
                    <img src="image/produit/test21.jpg" class="d-block w-100">
                    <div class="carousel-caption d-none d-md-block text-white">
                        <h5>Yacht</h5>
                        <a class="btn btn-primary" href="detailProduit.php?id=2" role="button">Voir plus</a>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="image/produit/test22.jpg" class="d-block w-100">
                    <div class="carousel-caption d-none d-md-block text-white">
                        <h5>Jetski</h5>
                        <a class="btn btn-primary" href="detailProduit.php?id=3" role="button">Voir plus</a>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
        <br>
        <br>
        <h2>Découvrez aussi...</h2>
        <p>Notre gamme professionnel</p>
        <br>
        <br>
        <div id="pro" class="carousel carousel-dark slide bg-secondary" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#pro" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#pro" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#pro" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner w-100 ">
                <div class="carousel-item active" data-bs-interval="10000">
                    <img src="image/produit/test30.jpg" class="d-block w-100">
                    <div class="carousel-caption d-none d-md-block text-white">
                        <h5>Tanker</h5>
                        <a class="btn btn-primary" href="detailProduit.php?id=5" role="button">Voir plus</a>
                    </div>
                </div>
                <div class="carousel-item" data-bs-interval="2000">
                    <img src="image/produit/test31.png" class="d-block w-100">
                    <div class="carousel-caption d-none d-md-block text-white">
                        <h5>Porte Conteneur</h5>
                        <a class="btn btn-primary" href="detailProduit.php?id=6" role="button">Voir plus</a>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="image/produit/test32.jpg" class="d-block w-100">
                    <div class="carousel-caption d-none d-md-block text-white">
                        <h5>Ferie</h5>
                        <a class="btn btn-primary" href="detailProduit.php?id=7" role="button">Voir plus</a>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#pro" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#pro" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
        <br>
        <br>
        <p>Notre gamme ecologique</p>
        <br>
        <br>
        <div id="eco" class="carousel carousel-dark slide bg-secondary" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#eco" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#eco" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#eco" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner w-100 ">
                <div class="carousel-item active" data-bs-interval="10000">
                    <img src="image/produit/test40.jpg" class="d-block w-100">
                    <div class="carousel-caption d-none d-md-block text-white">
                        <h5>Voilier</h5>
                        <a class="btn btn-primary" href="detailProduit.php?id=9" role="button">Voir plus</a>
                    </div>
                </div>
                <div class="carousel-item" data-bs-interval="2000">
                    <img src="image/produit/test41.jpg" class="d-block w-100">
                    <div class="carousel-caption d-none d-md-block text-white">
                        <h5>Catamaran</h5>
                        <a class="btn btn-primary" href="detailProduit.php?id=10" role="button">Voir plus</a>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="image/produit/test42.jpg" class="d-block w-100">
                    <div class="carousel-caption d-none d-md-block text-white">
                        <h5>Yacht electrique</h5>
                        <a class="btn btn-primary" href="detailProduit.php?id=11" role="button">Voir plus</a>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#eco" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#eco" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </center>
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