<?php

namespace App\Service;

use OpenAI;

class SnippetAI
{
    public static function explain(?array $snippet) {
        // On va initialiser la clé API
        $yourApiKey = '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';

        // On initialise le client
        $client = OpenAI::client($yourApiKey);

        // On initialise le préprompt
        $prePrompt = 'Tu agis comme un assistant d\'aide à la programmation pour une application appelée CodeXpress. Les utilisateurs disposent d\'une fonction permettant de comprendre le code d\'une page. Réponds en commençant toujours par "Le code suivant signifie :". Voici le code à expliquer : '; 
        
        // On va créer une requête et récupérer le résultat
        $result = $client->chat()->create([
            'model' => 'gpt-3.5-turbo-16k',
            'messages' => [
                // On concatène le préprompt et le snippet qui est passé en paramètre
                ['role' => 'user', 'content' => $prePrompt . $snippet['code']],
            ],
        ]);

        // On va retourner le résultat
        return $result['choices'][0]['message']['content'];
    }
}

