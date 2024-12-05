<?php

namespace App\Services\OpenAI;

use App\Exceptions\InvalidPromptException;
use Exception;
use Symfony\Component\Yaml\Yaml;

class ChatCompletionService
{
    private OpenAIClient $openAIClient;

    private array $instructions;

    public function __construct(OpenAIClient $openAIClient)
    {
        $this->instructions = $this->loadInstructions();
        $this->openAIClient = $openAIClient;
    }

    private function loadInstructions(): array
    {
        $path = storage_path('app/openai_instructions.yaml');
        return Yaml::parseFile($path);
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
                        'content' => $this->instructions['prompt_validator']
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ]
            ]
        ]);

        $responseData = json_decode($response->getBody(), true);

        if (!isset($responseData['choices'][0]['message']['content'])) {
            throw new Exception('La estructura de la respuesta no es la esperada.');
        }

        $content = $responseData['choices'][0]['message']['content'];

        $messageContent = json_decode($content);

        if (!isset($messageContent->description_valid)) {
            throw new Exception('La propiedad description_valid no está presente en la respuesta.');
        }

        $descriptionValid = $messageContent->description_valid;

        if (!$descriptionValid) {


            throw new InvalidPromptException(
                $messageContent->message,
                $messageContent->description_generated,
                422
            );
        }
    }

    public function describeImageDesign(String $url)
    {
        $response = $this->openAIClient->post('chat/completions', [
            'json' => [
                'model' => 'gpt-4o-mini',
                'response_format' => ['type' => 'json_object'],
                'messages' => [
                    [
                        'role' =>  'system',
                        'content' => $this->instructions['image_describer']
                    ],
                    [
                        'role' => 'user',
                        'content' => [
                            [
                                'type' => 'image_url',
                                'image_url' => [
                                    'url' => $url
                                ]
                            ]
                        ]
                    ]
                ],
            ]
        ]);

        $responseData = json_decode($response->getBody(), true);

        if (!isset($responseData['choices'][0]['message']['content'])) {
            throw new Exception('La estructura de la respuesta no es la esperada.');
        }

        $content = json_decode($responseData['choices'][0]['message']['content']);

        if(!$content->image_valid){
            throw new InvalidPromptException(
                $content->message,
                $content->suggested_description,
                422
            );
        }

        return json_decode($content);
    }

    public function classifyImage(String $url)
    {
        $response = $this->openAIClient->post('chat/completions', [
            'json' => [
                'model' => 'gpt-4o-mini',
                'response_format' => ['type' => 'json_object'],
                'messages' => [
                    [
                        'role' =>  'system',
                        'content' => $this->instructions['image_classifier']
                    ],
                    [
                        'role' => 'user',
                        'content' => [
                            [
                                'type' => 'image_url',
                                'image_url' => [
                                    'url' => $url
                                ]
                            ]
                        ]
                    ]
                ],
            ]
        ]);

        $responseData = json_decode($response->getBody(), true);

        if (!isset($responseData['choices'][0]['message']['content'])) {
            throw new Exception('La estructura de la respuesta no es la esperada.');
        }

        $content = json_decode($responseData['choices'][0]['message']['content']);

       /*  if(!$content->image_valid){
            throw new InvalidPromptException(
                $content->message,
                $content->suggested_description,
                422
            );
        } */

        return $content;
    }
}
