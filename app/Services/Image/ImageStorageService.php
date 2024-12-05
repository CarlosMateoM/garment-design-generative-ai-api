<?php

namespace App\Services\Image;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ImageStorageService
{

    public function getPath(String $format)
    {
        $date = Carbon::now()->format('Y-m-d-H-i-s-u') . Str::random(5);
        
        $path = 'images/';

        if ($format == 'webp') {
            $path .= 'optimized/';    
        } 
        
        $path .= 'img-' . $date . '.' . $format;
        

        return $path;
    }


    public function storeImage(ImageData $image, String $format)
    {

        try {

            $path = $this->getPath($format);

            Storage::disk('public')->put($path, $image->getContent());

            $url = asset('storage/' . $path);

            return $url;
            
        } catch (\Exception $e) {
            throw new \Exception("Error storing image");
        }
    }
}
