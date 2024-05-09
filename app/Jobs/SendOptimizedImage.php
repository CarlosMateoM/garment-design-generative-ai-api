<?php

namespace App\Jobs;

use App\Events\ImageProcessedEvent;
use App\Models\GarmentDesign;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendOptimizedImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private GarmentDesign $garmentDesign;

    /**
     * Create a new job instance.
     */
    public function __construct(GarmentDesign $garmentDesign)
    {
        $this->garmentDesign = $garmentDesign;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        
        ImageProcessedEvent::dispatch(
            $this->garmentDesign
        );
    }
}
