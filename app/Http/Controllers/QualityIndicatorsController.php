<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQualityIndicatorsRequest;
use App\Http\Requests\UpdateQualityIndicatorsRequest;
use App\Models\QualityIndicators;

class QualityIndicatorsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreQualityIndicatorsRequest $request)
    {
        $qualityIndicators = new QualityIndicators();

        $qualityIndicators->garment_design_id = $request->garmentDesignId;
        $qualityIndicators->creativity = $request->creativity;
        $qualityIndicators->originality = $request->originality;
        $qualityIndicators->texture = $request->texture;
        $qualityIndicators->stylistics = $request->stylistics;
        $qualityIndicators->functionality = $request->functionality;
        $qualityIndicators->feasibility = $request->feasibility;
        $qualityIndicators->feedback = $request->feedback;

        $qualityIndicators->save();

        return response()->json($qualityIndicators, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(QualityIndicators $qualityIndicators)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateQualityIndicatorsRequest $request, QualityIndicators $qualityIndicators)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(QualityIndicators $qualityIndicators)
    {
        //
    }
}
