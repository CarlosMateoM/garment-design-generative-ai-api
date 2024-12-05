<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageDescriptionRequest;
use App\Services\DesignService;
use App\Services\OpenAI\ChatCompletionService;
use Illuminate\Http\Request;

class ImageDescriptionController extends Controller
{

    public function __construct(
        private DesignService  $designService,
        private ChatCompletionService $chatCompletionService
    )
    {}


    /**
     * Handle the incoming request.
     */
    public function __invoke(ImageDescriptionRequest $request)
    {

        $response = $this->chatCompletionService->describeImageDesign($request->url);

        return response()->json($response, 201);
    }
}
