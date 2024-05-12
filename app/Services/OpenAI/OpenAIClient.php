<?php

namespace App\Services\OpenAI;

use GuzzleHttp\Client;

class OpenAIClient extends Client
{
    public function __construct()
    {
        parent::__construct([
            'base_uri' => 'https://api.openai.com/v1/',
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
            ]
        ]);
    }
}
