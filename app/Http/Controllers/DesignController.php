<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGarmentDesignRequest;
use App\Models\GarmentDesign;
use App\Services\DesignService;
use Illuminate\Http\Request;

class DesignController extends Controller
{

    private DesignService $designService;

    public function __construct(DesignService $designService)
    {
        $this->designService = $designService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $designs = $this->designService->getAllDesigns($request);

        return response()->json($designs);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGarmentDesignRequest $request)
    {
        $this->designService->createDesign(
            $request->user(),
            $request->prompt
        );

        return response()->json([], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(GarmentDesign $design)
    {
        $design->load('images', 'qualityIndicators');

        return response()->json($design);
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
