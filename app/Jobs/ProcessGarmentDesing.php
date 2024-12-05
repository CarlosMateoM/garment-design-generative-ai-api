<?php

namespace App\Jobs;

use App\Services\DesignService;
use App\Services\Image\ImageDownloadService;
use App\Services\Image\ImageOptimizationService;
use App\Services\Image\ImageService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Class ProcessGarmentDesignJob
 *
 * This class represents a job responsible for downloading, optimizing,
 * and storing an image generated by the DALLE API. This job is typically
 * dispatched asynchronously to improve application performance.
 */
class ProcessGarmentDesing implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user;

    private $imageUrl;

    private $garmendDesignId;


    public function __construct($user,  $garmendDesignId,  $imageUrl)
    {  
        $this->user = $user;
        $this->garmendDesignId = $garmendDesignId;
        $this->imageUrl = $imageUrl;
    }


    public function handle(
        DesignService $designService,
        ImageService $imageService,
        ImageDownloadService $imageDownloadService,
        ImageOptimizationService $imageOptimizationService
    ): void {

 

            $originalImage = $imageDownloadService->downloadImage($this->imageUrl);
                        
            $originalImageSaved = $imageService->createImage($originalImage, 'png');
            
            $designService->attacthImage($this->garmendDesignId, $originalImageSaved->id);

            $optimizedImage = $imageOptimizationService->optimizeImageToWebP($originalImage);

            $optimizedImageSaved = $imageService->createImage($optimizedImage, 'webp');

            $designService->attacthImage($this->garmendDesignId, $optimizedImageSaved->id);

            $garmentDesign = $designService->findDesignById($this->garmendDesignId);

            SendOptimizedImage::dispatch($garmentDesign);

        
    }
}