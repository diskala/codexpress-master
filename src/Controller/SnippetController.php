<?php

namespace App\Controller;

use App\Entity\Snippet;
use App\Service\SnippetAI;
use App\Form\SnippetAIType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Georgehadjisavva\ElevenLabsClient\Facades\TextToSpeach;

use Georgehadjisavva\ElevenLabsClient\ElevenLabsClient; 
 

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SnippetController extends AbstractController
{
    #[Route('/snippet/{id}', name: 'show_code')]
    public function index(
        Snippet $snippet, 
        Request $request,
        
        // TextToSpeach $tts,
    ): Response
    {
        //  $Apikey=$this->getParameter('ELEVEN_API_KEY');
        // $eleven= new ElevenLabsClient($Apikey);
        // dd($eleven);
        //  dd($eleven->voices()->getVoice('CYw3kZ02Hs0563khs1Fj'));
        $form = $this->createForm(SnippetAIType::class);
        // On récupère les données du formulaire
        $form->handleRequest($request);
        // On vérifie que le formulaire est soumis et valide
        if($form->isSubmitted() && $form->isValid()) {
            // On le code pour l'envoyer à l'IA
            $data = $form->getData('code');
            // On envoie les données à l'IA et elle renvoie une explication
            $explication = SnippetAI::explain($data);

            // si GPT3.5 a répondu alors on fait le texxt to speach avec ElevenLabs
            if($explication)
          {
                 
            // https://api.elevenlabs.io/v1/text-to-speech/CYw3kZ02Hs0563khs1Fj/stream
//             
          // On va initialiser l'url de l'API
$api = "https://api.elevenlabs.io/v1/text-to-speech/CYw3kZ02Hs0563khs1Fj/stream?optimize_streaming_latency=0&output_format=mp3_44100_128";
// On va initialiser la clé API
$key = $this->getParameter('ELEVEN_API_KEY');
// On récupère le texte généré par GPT3.5
$text = $explication;
// On initialise les headers
$headers = [
'accept: */*',
'xi-api-key: ' . $key,
'Content-Type: application/json'
];
// On initialise cURL
$curl = curl_init();
// On configure cURL
curl_setopt($curl, CURLOPT_URL, $api);
// Option pour la récupération du résultat
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
// ondesactive la verification certificat ssl
curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,false);
curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
// Option pour la méthode POST
curl_setopt($curl, CURLOPT_POST, true);
// Option pour les headers
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
// Option pour le body
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode([
'text' => $text,
'model_id' => 'eleven_multilingual_v1',
'voice_settings' => [
'stability' => 0,
'similarity_boost' => 0,
'style' => 0,
'use_speaker_boost' => true
]
]));
// On exécute cURL
curl_exec($curl);
//catch des erreurs
if(curl_error($curl)){
    return new Response(curl_error($curl));
}
// On ferme cURL
curl_close($curl);


//  dd($curl);
// dd($explication);
            // '{
            //     "text": $text,
            //     "model_id": "eleven_monolingual_v1",
            //      "voice_settings": {
            //       "stability": 0,
            //       "similarity_boost": 0,
            //        "style": 0,
            //       "use_speaker_boost": true
            //     }
            //    }'

        
          
//   -H 'accept: */*' \
    //  -H 'xi-api-key': $key,
//   -H 'Content-Type: application/json' \
//     '{
//   "text": "string",
//   "model_id": "eleven_monolingual_v1",
//    "voice_settings": {
//     "stability": 0,
//     "similarity_boost": 0,
//      "style": 0,
//     "use_speaker_boost": true
//   }
//  }'
              
            
            // On affiche le résultat dans le template twig
            return $this->render('snippet/snippet.html.twig', [
                'snippet' => $snippet,
                'SnippetAI' => $form,
                'Explication' => $explication, // Cette variable contient la réponse de l'IA
                // 'audio'=>$audio // Contient fichier audio
            ]);
          }
        }

        return $this->render('snippet/snippet.html.twig', [
            'snippet' => $snippet,
            'SnippetAI' => $form,
            'Explication' => '',
            'audio'=>'',
        ]);
    }
}
