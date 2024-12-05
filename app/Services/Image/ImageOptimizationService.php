<?php

namespace App\Services\Image;

use Intervention\Image\EncodedImage;
use Intervention\Image\ImageManager;
use Psr\Http\Message\StreamInterface;


class ImageOptimizationService
{
    public function __construct(
        private ImageManager $imageManager
    ) {}

    public function isImage($file): bool
    {
        return strpos($file->getMimeType(), 'image/') === 0;
    }

    public function optimizeImageToWebP(ImageData $image, int $quality = 85): ImageData
    {
        try {

            $image = $this->imageManager->read($image->getContent());

            $image = $image->toWebp($quality);

            return new ImageData($image, 'image/webp');
          
        } catch (\Exception $e) {

            throw new \Exception("Error optimizing image: " . $e->getMessage() . " " . $e->getLine() . " " . $e->getFile());
        }
    }
}
