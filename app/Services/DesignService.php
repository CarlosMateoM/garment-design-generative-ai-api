<?php

namespace App\Services;

use App\Jobs\ProcessGarmentDesing;
use App\Models\GarmentDesign;
use App\Models\Keyword;
use App\Models\KeywordsCategory;
use App\Models\User;
use App\Services\OpenAI\ChatCompletionService;
use App\Services\OpenAI\DalleService;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;

class DesignService
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

    public function findDesignById(int $id): GarmentDesign
    {
        return GarmentDesign::find($id);
    }

    public function attacthImage(int $id, int $imageId): void
    {
        $garmentDesign = GarmentDesign::find($id);

        $garmentDesign->images()->attach($imageId);
    }

    public function getAllDesigns(Request $request)
    {
        $query = QueryBuilder::for(GarmentDesign::class)
            ->allowedFilters([
                'user_id',
                'prompt',
                'revised_prompt'
            ])
            ->allowedIncludes([
                'images',
                'images.keywords',
                'qualityIndicators'
            ])
            ->allowedSorts([
                'created_at'
            ])
            ->paginate($request->get('per_page', 10))
            ->appends($request->query());

        return $query;
    }

    public function createDesign(User $user, String $prompt)
    {

        $this->chatCompletionService->validatePrompt($prompt);

        $image = $this->dalleService->imageGeneration($prompt);

        $garmentDesign = new GarmentDesign();

        $garmentDesign->user_id = $user->id;
        $garmentDesign->prompt = $prompt;
        $garmentDesign->revised_prompt = $image['revised_prompt'];

        $garmentDesign->save();

        $user->decreaseCredits(1);

        ProcessGarmentDesing::dispatch($user, $garmentDesign->id, $image['url']);
    }

    public function classifyDesign(int $id)
    {
        $garmentDesign = GarmentDesign::find($id);

        $image = $garmentDesign->images()->first();

        $response = $this->chatCompletionService->classifyImage($image->url);

        $keywordsCategories = KeywordsCategory::all();

        try {


            foreach ($keywordsCategories as $keywordsCategory) {

                $categoryName = $keywordsCategory->name;


                foreach ($response->$categoryName as $keyword) {


                    $savedKeyword = Keyword::firstOrCreate([
                        'keyword' => $keyword
                    ]);

                    $savedKeyword->keywordCategory()->attach($keywordsCategory);

                    //$image->keywords()->attach($savedKeyword->id);
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al clasificar la imagen',
                'error' => $e->getMessage(),
                'response' => $response
            ], 500);
        }

        return $response;
    }
}
