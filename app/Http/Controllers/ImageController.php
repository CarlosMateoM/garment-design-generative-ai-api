<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreImageRequest;
use App\Models\Image;
use App\Services\Image\ImageData;
use App\Services\Image\ImageService;
use Illuminate\Http\Request;

class ImageController extends Controller
{

    public function __construct(
        private ImageService $imageService
    ) {}


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
    public function store(StoreImageRequest $request)
    {
        $imageData = new ImageData(
            $request->file('image')->getContent(),
            $request->file('image')->getMimeType()
        );

        $image = $this->imageService->createImage(
            $imageData, 
            $request->file('image')->getMimeType()
        );

        return response()->json($image, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Image $image)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Image $image)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Image $image)
    {
        //
    }
}
