<?php

namespace App\Http\Controllers;

use App\Models\GarmentDesign;
use App\Services\DalleService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\QueryBuilder\QueryBuilder;
use Intervention\Image\Facades\Image;

class GarmentDesignController extends Controller
{

    private DalleService $dalleService;

    public function __construct(DalleService $dalleService)
    {
        $this->dalleService = $dalleService;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = QueryBuilder::for(GarmentDesign::class)
            ->allowedFilters(['user_id', 'prompt', 'revised_prompt'])
            ->allowedSorts('created_at')
            ->get();

        return response()->json($query);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'prompt' => 'required|string'
        ]);

        $user = $request->user();

        $response = $this->dalleService->imageGeneration($request->prompt);

        $image = $response['data'][0];
        
        $garmentDesign = new GarmentDesign();


        try {
                $date = Carbon::now();

                $originalName = $date . '.png';
                $originalFile = file_get_contents($image['url']);
                Storage::disk('public')->put('images/' . $originalName, $originalFile);


                $optimizedName = $date . '.jpg';
                $optimizedFile = Image::make($originalFile)->encode('jpg', 80);
                Storage::disk('public')->put('images/optimized/' . $optimizedName, $optimizedFile);

                $garmentDesign->user_id = $user->id;
                $garmentDesign->prompt = $request->prompt;
                $garmentDesign->revised_prompt = $image['revised_prompt'];
                $garmentDesign->url = asset('storage/images/optimized/' . $optimizedName);

                $garmentDesign->save();

                $user->decreaseCredits(1);

                return $garmentDesign;

        } catch (Exception $e) {
            Log::error('Error al obtener los diseños: ' . $e->getMessage());
            return response()->json(['message' => 'Error al obtener los diseños'. $e->getMessage() ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(GarmentDesign $garmentDesign)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GarmentDesign $garmentDesign)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GarmentDesign $garmentDesign)
    {
        //
    }
}
