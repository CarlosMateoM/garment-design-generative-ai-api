<?php

namespace App\Services\OpenAI;

use App\Exceptions\InvalidPromptException;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Log;

class DalleService
{

    private OpenAIClient $openAIClient;

    public function __construct(OpenAIClient $openAIClient)
    {
        $this->openAIClient = $openAIClient;
    }

    public function imageGeneration(String $description)
    {

        try {

            $prompt = "Genera una representación visual realista de un 
            maniquí con un ángulo centrado que permita apreciar la prenda 
            en su totalidad y resaltando los detalles. " . $description;

            $response = $this->openAIClient->post('images/generations', [
                'json' => [
                    "n" => 1,
                    "size" => "1024x1024",
                    "model" => "dall-e-3",
                    'prompt' => $prompt,
                ],
            ]);

            $responseJson = json_decode($response->getBody()->getContents(), true);

            $data = $responseJson['data'][0];

            return [
                'url' => $data['url'],
                'revised_prompt' => $data['revised_prompt']
            ];

        } catch (ClientException $e) {

            $response = $e->getResponse();

            Log::error("error al enviar la peticion a dalle: " . $response->getBody()->getContents());

            if ($response->getStatusCode() === 400) {
                
                throw new InvalidPromptException(
                    $response->getReasonPhrase(), 
                    $response->getStatusCode()
                );

            }

        }
    }
}
