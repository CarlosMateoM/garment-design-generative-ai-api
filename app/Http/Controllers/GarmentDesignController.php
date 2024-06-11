<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidPromptException;
use App\Http\Requests\StoreGarmentDesignRequest;
use App\Models\GarmentDesign;
use App\Services\GarmentDesignService;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class GarmentDesignController extends Controller
{

    private GarmentDesignService $garmentDesignService;

    public function __construct(GarmentDesignService $garmentDesignService)
    {
        $this->garmentDesignService = $garmentDesignService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = QueryBuilder::for(GarmentDesign::class)
            ->allowedFilters(['user_id', 'prompt', 'revised_prompt'])
            ->allowedSorts('created_at')
            ->paginate()
            ->appends(request()->query());

        return response()->json($query);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGarmentDesignRequest $request)
    {
        try {

            $this->garmentDesignService->storeGarmentDesign(
                $request->user(),
                $request->prompt
            );
            
        } catch (InvalidPromptException $e) {

            return $e->toJsonResponse();
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
