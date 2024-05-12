<?php

namespace App\Services\OpenAI;

use App\Exceptions\InvalidPromptException;
use Exception;

class ChatCompletionService
{
    private OpenAIClient $openAIClient;

    private $validatePromptInstructions =

    'Como asistente AI, tu tarea es validar que los textos proporcionados describan prendas de vestir.

    Si el texto es válido, la respuesta debe seguir el siguiente formato JSON:
      
    {
        "description_valid": true
    }    
    
    Si el texto ingresado por el usuario no corresponde a una prenda de vestir,
    por favor genera una descripción de al menos 500 caracteres de una prenda 
    que se ajuste al contexto proporcionado en la información ingresada por el usuario.
    
    En tal caso, la respuesta debe seguir el siguiente formato JSON:
    
    {
        "description_valid": false,
        "description_generated": "Descripción generada"
    }
    ';


    public function __construct(OpenAIClient $openAIClient)
    {
        $this->openAIClient = $openAIClient;
    }

    public function validatePrompt(String $prompt)
    {
        $response = $this->openAIClient->post('chat/completions', [
            'json' => [
                'model' => 'gpt-3.5-turbo',
                'response_format' => ['type' => 'json_object'],
                'messages' => [
                    [
                        'role' =>  'system',
                        'content' => $this->validatePromptInstructions
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ]
            ]
        ]);

        $responseData = json_decode($response->getBody(), true);

        // Verificar si la respuesta tiene el formato esperado
        if (!isset($responseData['choices'][0]['message']['content'])) {
            throw new Exception('La estructura de la respuesta no es la esperada.');
        }

        $content = $responseData['choices'][0]['message']['content'];

        // Decodificar el contenido JSON dentro del string
        $messageContent = json_decode($content);


        // Verificar si la propiedad 'description_valid' existe en el objeto decodificado
        if (!isset($messageContent->description_valid)) {
            throw new Exception('La propiedad description_valid no está presente en la respuesta.');
        }

        // Acceder al valor de 'description_valid'
        $descriptionValid = $messageContent->description_valid;
        
        // Utilizar el valor de 'description_valid' según sea necesario
        if (!$descriptionValid) {
            
            $descriptionGenerated = $messageContent->description_generated;

            throw new InvalidPromptException($descriptionGenerated);
        }
    }
}
