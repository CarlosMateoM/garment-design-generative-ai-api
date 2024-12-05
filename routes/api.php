<?php

use App\Http\Controllers\DesignController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ImageDescriptionController;
use App\Http\Controllers\QualityIndicatorsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserSurveyStatusController;
use App\Http\Middleware\CheckCreditsAndSurveys;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function () {

    Route::get('garment-designs', [DesignController::class, 'index']);
    
    Route::post('images', [ImageController::class, 'store']);

    Route::post('images/description', ImageDescriptionController::class);

    

    Route::middleware(['auth:sanctum'])->group(function () {

        Route::get('user', UserController::class);

        Route::get('user-survey-status', [UserSurveyStatusController::class, 'index']);
        
        Route::get('designs/{design}', [DesignController::class, 'show']);


        Route::post('garment-designs', [DesignController::class, 'store']);
                                        //->middleware(['verified', CheckCreditsAndSurveys::class]);

        Route::apiResource('quality-indicators', QualityIndicatorsController::class);
    });
});

