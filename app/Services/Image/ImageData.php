<?php

namespace App\Services\Image;

class ImageData
{
    private $content;
    private $mimeType;
    private $size;

    public function __construct($content, $mimeType)
    {
        $this->content = $content;
        $this->mimeType = $mimeType;
        $this->size = strlen($content);
    }

    public function getSize()
    {
        return $this->size;
    }

    public function getMimeType()
    {
        return $this->mimeType;
    }

  

    public function getContent()
    {
        return $this->content;
    }
    

    public function getPathname()
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'processed_image_');
        file_put_contents($tempFile, $this->content);
        return $tempFile;
    }

    public function __destruct()
    {
        $tempFile = $this->getPathname();
        if (file_exists($tempFile)) {
            unlink($tempFile);
        }
    }
}