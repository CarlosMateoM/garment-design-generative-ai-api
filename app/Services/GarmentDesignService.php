<?php

namespace App\Services;

use App\Jobs\ProcessGarmentDesing;
use App\Models\GarmentDesign;
use App\Models\User;
use App\Services\OpenAI\ChatCompletionService;
use App\Services\OpenAI\DalleService;
use Exception;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GarmentDesignService
{

    private DalleService $dalleService;
    private ChatCompletionService $chatCompletionService;

    public function __construct(
        DalleService $dalleService,
        ChatCompletionService $chatCompletionService
    ) {
        $this->dalleService = $dalleService;
        $this->chatCompletionService = $chatCompletionService;
    }

    public function storeGarmentDesign(User $user, String $prompt)
    {

        $this->chatCompletionService->validatePrompt($prompt);

        DB::beginTransaction();

        try {

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
