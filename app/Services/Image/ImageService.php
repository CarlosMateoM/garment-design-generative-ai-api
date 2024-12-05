<?php

namespace App\Services\Image;

use App\Models\Image;
use Psr\Http\Message\StreamInterface;

class ImageService
{
    public function __construct(
        private ImageStorageService $imageStorageService
    ) {}

    public function createImage(ImageData $img, String $format): Image
    {
        $url = $this->imageStorageService->storeImage($img, $format);

        $image = new Image();

        $image->url = $url;
        $image->format = $format;
        $image->alt_text = "alternative text";

        $image->save();

        return $image;
    }
}
