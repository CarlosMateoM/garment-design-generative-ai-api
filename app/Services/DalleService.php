<?php

namespace App\Services;

use GuzzleHttp\Client;

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
    }
}
