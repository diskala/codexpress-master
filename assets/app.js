import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

const option={
    method: 'POST',
    Headers: {
        'accept': '*/*',
        'xi-api-key': '95d60a27c74231a4f733fcfb8a2e0aeb',
        'Content-Type': 'application/json'
    }
};
// Initialisation du body pour la requête
let body= {
    
        "text": $explication,
        "model_id": "eleven_multilingual_v1",
        "voice_settings": {
          "stability": 0,
          "similarity_boost": 0,
          "style": 0,
          "use_speaker_boost": true
        
      }
     
}
body:JSON.stringify(body);
//fetch la requête vers l'API Eleventlabs en POST
let url="https://api.elevenlabs.io/v1/text-to-speech/CYw3kZ02Hs0563khs1Fj/stream?optimize_streaming_latency=0&output_format=mp3_44100_128";
fetch(url, option)
.then(Responses=>{
    if(Responsesesponses.ok)
    {
    return Responsesesponses.json();
}
})
