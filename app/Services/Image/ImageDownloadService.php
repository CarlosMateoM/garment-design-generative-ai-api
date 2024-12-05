<?php

namespace App\Services\Image;

use GuzzleHttp\Client;


class ImageDownloadService
{

    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }


    public function downloadImage(String $url): ImageData
    {
        try {

            $response = $this->client->get($url);

            $image = $response->getBody()->getContents();

            $format = $response->getHeader('Content-Type')[0];

            return new ImageData($image, $format);

        } catch (\Exception $e) {
            throw new \Exception("Error downloading image");
        }
    }
}
