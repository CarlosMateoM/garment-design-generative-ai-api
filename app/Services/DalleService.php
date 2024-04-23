<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class DalleService
{

    private Client $openAIClient;

    public function __construct()
    {
        $this->openAIClient = new Client([
            'base_uri' => 'https://api.openai.com/v1/',
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'), 
            ]
        ]);
    }

    public function imageGeneration(String $prompt)
    {
        try{
            
            $response = $this->openAIClient->post('images/generations', [
                'json' => [
                    "n" => 1,
                    "size" => "1024x1024",
                    "model" => "dall-e-3",
                    'prompt' => $prompt,
                ],
            ]);

            return json_decode($response->getBody()->getContents(), true);

        } catch (Exception $e) {
            Log::error('Error al generar imagen desde DALL·E: ' . $e->getMessage());
            return null;
        }
    }
}
