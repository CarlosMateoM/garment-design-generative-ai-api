<?php

namespace App\Http\Controllers;

use App\Models\GarmentDesign;

class UserSurveyStatusController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $garmentDesigns = GarmentDesign::where('user_id', $user->id)
                            ->whereDoesntHave('qualityIndicators')
                            ->get();

        return response()->json($garmentDesigns);
    }
}
