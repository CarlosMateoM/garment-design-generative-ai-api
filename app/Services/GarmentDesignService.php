<?php

namespace App\Services;

use App\Jobs\ProcessGarmentDesing;
use App\Models\GarmentDesign;
use App\Models\User;
use Exception;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GarmentDesignService
{

    private DalleService $dalleService;

    public function __construct(DalleService $dalleService)
    {
        $this->dalleService = $dalleService;
    }

    public function storeGarmentDesign(User $user, String $prompt)
    {

        try {

            DB::beginTransaction();

            $image = $this->dalleService->imageGeneration($prompt);

            $garmentDesign = new GarmentDesign();

            $garmentDesign->user_id = $user->id;
            $garmentDesign->prompt = $prompt;
            $garmentDesign->revised_prompt = $image['revised_prompt'];

            $garmentDesign->save();

            $user->decreaseCredits(1);

            ProcessGarmentDesing::dispatch($user, $garmentDesign->id, $image['url']);

            DB::commit();

        } catch (RequestException $e) {
            
            DB::rollBack();

            Log::error($e->getMessage());

            throw new Exception("Ha ocurrido un error, por favor intente nuevamente.");
        }
    }
}
