<?php
$pageTitle = "Nautic Challenge";
require_once "./include/head.php";
?>
<body>
<?php
    require_once "./include/header.php";
    require_once "./include/menu.php";
?>

<!-- Contenu principal -->
<main role="main" class="container mt-4 text-center">
    <h1 class="text-center">Nautic Challenge</h1>
    <p class="text-center">Pourriez-vous relever le Nautic Challenge du premier coup ?!</p>

    <div id="loading-indicator" style="display: block;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Chargement...</span>
        </div>
        <p>Chargement de la question...</p>
    </div>

    <div id="quiz-container" class="quiz-container mt-4" style="display: none;">
        <div id="question-block" class="question-block card p-5 shadow border rounded" style="max-width: 1000px; margin: 0 auto;">
            <!-- La question sera ajoutée ici dynamiquement par JS -->
        </div>
        <div id="feedback" class="mt-4"></div>
    </div>
</main>

<script>
    // Fonction pour afficher la question une fois récupérée
    function displayQuestion(questionData) {
        const questionContainer = document.getElementById('question-block');
        const loadingIndicator = document.getElementById('loading-indicator');
        const quizContainer = document.getElementById('quiz-container');
        
        // Cacher le spinner de chargement et afficher le quiz
        loadingIndicator.style.display = 'none';
        quizContainer.style.display = 'block';

        // Vérifier si la question est disponible
        if (questionData && questionData.question && questionData.options) {
            // Si la question et les options sont disponibles, afficher la question avec les options
            let html = `
                <h4 class="mb-4">${questionData.question}</h4>
                <div class="row justify-content-center">
            `;

            // Affichage des options
            Object.keys(questionData.options).forEach(key => {
                html += `
                    <div class="col-6 mb-3">
                        <button 
                            class="btn btn-secondary w-100 py-2 option-button" 
                            data-option="${key}" 
                            id="option${key}" 
                            style="font-size: 1.2rem;">
                            ${questionData.options[key]}
                        </button>
                    </div>
                `;
            });

            // Ajouter le bouton "Suivant"
            html += `
                <div class="col-12 mt-4 d-flex justify-content-center">
                    <button id="next-button" class="btn btn-primary w-50" style="display: none;">Suivant</button>
                </div>
            </div>`;

            questionContainer.innerHTML = html;

            // Ajouter un gestionnaire d'événement pour chaque bouton d'option
            const optionButtons = document.querySelectorAll('.option-button');
            optionButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const selectedOption = button.getAttribute('data-option');

                    // Vérifier la réponse et appliquer la couleur correspondante
                    if (selectedOption === questionData.answer) {
                        button.classList.remove('btn-secondary');
                        button.classList.add('btn-success');
                    } else {
                        button.classList.remove('btn-secondary');
                        button.classList.add('btn-danger');
                    }

                    // Désactiver tous les boutons pour éviter plusieurs sélections
                    optionButtons.forEach(btn => btn.disabled = true);

                    // Afficher le bouton Suivant
                    document.getElementById('next-button').style.display = "block";
                });
            });

        } else {
            // Si la question n'est pas disponible, afficher seulement le message et le bouton "Suivant"
            questionContainer.innerHTML = `
                <p class="text-danger">Question non disponible. Veuillez réessayer plus tard.</p>
                <div class="col-12 mt-4 d-flex justify-content-center">
                    <button id="next-button" class="btn btn-primary w-50">Suivant</button>
                </div>
            `;

            // Ne pas afficher de boutons d'option, et afficher directement le bouton "Suivant"
            document.getElementById('next-button').style.display = "block";
        }
    }

    // Gère l'événement du bouton Suivant
    document.getElementById('quiz-container').addEventListener('click', function (event) {
        if (event.target && event.target.id === 'next-button') {
            // Réinitialiser l'état pour afficher une nouvelle question
            const feedback = document.getElementById('feedback');
            feedback.innerHTML = "";  // Réinitialiser le feedback

            // Appeler l'API pour générer une nouvelle question
            getNewQuestion();
        }
    });

    // Fonction pour récupérer une nouvelle question depuis l'API
    function getNewQuestion() {
        fetch('generate_question.php')
            .then(response => response.json())
            .then(data => {
                // Afficher la nouvelle question
                displayQuestion(data);
            })
            .catch(error => {
                console.error('Erreur lors de la récupération de la question:', error);
                // Si une erreur survient, afficher un message et le bouton Suivant
                displayQuestion(null);
            });
    }

    // Charger la première question après un délai pour afficher le spinner
    setTimeout(() => {
        getNewQuestion();
    }, 5000);  // Attendre 5 secondes avant d'afficher la question
</script>

<!-- Pied de page -->
<?php require_once "./include/footer.php"; ?>
</body>
</html>
