<?php
$pageTitle = "Nautic Challenge";
require_once "./include/head.php";
?>
<body>
<?php
    require_once "./include/header.php";
    require_once "./include/menu.php";

    // Liste des questions
    $questions = [
        [
            "question" => "Quel est le terme pour désigner un bateau à deux coques ?",
            "options" => ["Trimaran", "Catamaran", "Monocoque", "Jonque"],
            "answer" => "Catamaran"
        ],
        [
            "question" => "Quelle partie d’un bateau est utilisée pour le diriger ?",
            "options" => ["La quille", "Le gouvernail", "La proue", "La voile"],
            "answer" => "Le gouvernail"
        ],
        [
            "question" => "Quelle est la vitesse mesurée en nœuds ?",
            "options" => ["La profondeur", "La vitesse du vent", "La vitesse du bateau", "La distance parcourue"],
            "answer" => "La vitesse du bateau"
        ],
        [
            "question" => "Quel événement nautique célèbre traverse l'Atlantique ?",
            "options" => ["La Route du Rhum", "Vendée Globe", "America's Cup", "Transat Jacques Vabre"],
            "answer" => "La Route du Rhum"
        ],
        [
            "question" => "Comment appelle-t-on la partie avant d'un bateau ?",
            "options" => ["La proue", "La poupe", "Le pont", "La coque"],
            "answer" => "La proue"
        ]
    ];

    // Mélange aléatoire des questions
    shuffle($questions);
?>

<!-- Contenu principal -->
<main role="main" class="container mt-4 text-center">
    <h1 class="text-center">Nautic Challenge</h1>
    <p class="text-center">Pourriez-vous relever le Nautic Challenge du premier coup ?!</p>

    <div id="quiz-container" class="quiz-container mt-4">
        <div id="question-block" class="question-block card p-5 shadow border rounded" style="max-width: 1000px; margin: 0 auto;">
            <!-- La première question sera affichée ici via JavaScript -->
        </div>
        <div id="feedback" class="mt-4"></div>
    </div>
</main>

<script>
    // Liste des questions en JavaScript (transmise depuis PHP)
    const questions = <?php echo json_encode($questions); ?>;

    let currentQuestionIndex = 0;

    // Affiche une question
    function displayQuestion() {
        const questionContainer = document.getElementById('question-block');
        const questionData = questions[currentQuestionIndex];
        const feedback = document.getElementById('feedback');

        // Réinitialiser le feedback et le bouton Suivant
        feedback.innerHTML = "";

        // Construction du HTML pour la question et les choix
        let html = `
            <h4 class="mb-4">${questionData.question}</h4>
            <div class="row justify-content-center">
        `;

        questionData.options.forEach((option, index) => {
            html += `
                <div class="col-6 mb-3">
                    <button 
                        class="btn btn-secondary w-100 py-2 option-button" 
                        data-option="${option}" 
                        id="option${index}" 
                        style="font-size: 1.2rem;">
                        ${option}
                    </button>
                </div>
            `;
        });

        // Ajouter le bouton "Suivant" dans la même boîte
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
    }

    // Gère l'événement du bouton Suivant
    document.getElementById('quiz-container').addEventListener('click', function (event) {
        if (event.target && event.target.id === 'next-button') {
            currentQuestionIndex++;
            if (currentQuestionIndex >= questions.length) {
                currentQuestionIndex = 0; // Revenir au début
            }
            displayQuestion();
        }
    });

    // Charger la première question
    displayQuestion();
</script>

<!-- Pied de page -->
<?php require_once "./include/footer.php"; ?>
</body>
</html>
