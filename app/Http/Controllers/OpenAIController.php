<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class OpenAIController extends Controller
{
    public function askQuestion(Request $request)
    {
        // Votre clé API OpenAI
        $apiKey = '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';

        // Votre question
        $question = $request->input('question');

        // Création du client Guzzle
        $client = new Client([
            'verify' => false, // Désactive la vérification du certificat SSL pour le développement
        ]);

        // Appel de l'API OpenAI avec le paramètre 'model' ajouté
        $response = $client->request('POST', 'https://api.openai.com/v1/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'prompt' => $question,
                'max_tokens' => 1000, // Nombre maximum de jetons pour la réponse
                'model' => 'GPT-3.5 Turbo', // Exemple de modèle, remplacer par le modèle souhaité
            ],
        ]);

        // Récupération de la réponse de l'API
        $data = json_decode($response->getBody()->getContents(), true);
        $answer = $data['choices'][0]['text'];

        return response()->json(['answer' => $answer]);
    }
}
