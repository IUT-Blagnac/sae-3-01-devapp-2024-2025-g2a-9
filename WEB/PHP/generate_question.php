<?php
// Remplacez par votre clé API Gemini
$apiKey = 'AIzaSyACKCs7NRVr7TeviDZoBRNmDaXRoCJyR28';  

// Fonction pour appeler l'API Gemini et récupérer la question
function generateQuestion() {
    global $apiKey;

    // Construire la requête pour générer une question
    $data = [
        "contents" => [
            [
                "parts" => [
                    [
                        "text" => "Génère une question originale et diversifiée pour un quiz sur le nautisme. La question doit avoir 4 choix de réponses possibles, et la bonne réponse doit être précisée. Voici le format strict attendu pour la réponse : Question : \"Quel est le terme pour un bateau à deux coques ?\" Options : A. \"Trimaran\", B. \"Catamaran\", C. \"Monocoque\", D. \"Jonque\". La bonne réponse est \"B\"."
                    ]
                ]
            ]
        ]
    ];

    // Initialiser cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=$apiKey");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json"
    ]);

    // Exécuter la requête et récupérer la réponse
    $response = curl_exec($ch);
    curl_close($ch);

    // Vérifier les erreurs
    if ($response === false) {
        echo "Erreur cURL: " . curl_error($ch);
        return null;
    } else {
        // Décoder la réponse JSON
        $responseData = json_decode($response, true);

        // Vérifier si la réponse contient la question générée
        if (isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
            $generatedText = $responseData['candidates'][0]['content']['parts'][0]['text'];

            // Extraire la question, les options et la bonne réponse
            preg_match('/Question : "([^"]+)"/', $generatedText, $questionMatches);
            preg_match_all('/([A-D])\. "([^"]+)"/', $generatedText, $optionsMatches);
            preg_match('/La bonne réponse est "([A-D])"/', $generatedText, $correctAnswerMatches);

            // Vérifier que les matches sont trouvés avant d'accéder aux indices
            if (isset($questionMatches[1])) {
                $question = $questionMatches[1];
            }

            // Vérifier que les options sont présentes
            $options = [];
            if (isset($optionsMatches[1]) && isset($optionsMatches[2])) {
                $options = array_combine($optionsMatches[1], $optionsMatches[2]);
            }

            // Vérifier que la bonne réponse est présente
            $correctAnswer = isset($correctAnswerMatches[1]) ? $correctAnswerMatches[1] : "A"; // Valeur par défaut

            // Retourner la question et les options
            return [
                'question' => $question,
                'options' => $options,
                'answer' => $correctAnswer
            ];
        }
    }

    return null;
}

// Générer une nouvelle question et retourner en JSON
header('Content-Type: application/json');
echo json_encode(generateQuestion());
?>
