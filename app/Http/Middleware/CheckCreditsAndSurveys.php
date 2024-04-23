<?php

namespace App\Http\Middleware;

use App\Models\GarmentDesign;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckCreditsAndSurveys
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if($user->credits <= 0) {
            return response()->json(['message' => 'Lo siento, parece que no tienes suficientes créditos disponibles para continuar generando prendas en este momento.'], 403);
        }

        $surveyPending = GarmentDesign::where('user_id', $user->id)
        ->whereDoesntHave('qualityIndicators')
        ->get();

        if($surveyPending->count() > 0) {
            return response()->json(['message' => 'Parece que tienes una encuesta pendiente. Por favor, completa la encuesta antes de continuar.'], 403);
        }

        return $next($request);
    }
}
