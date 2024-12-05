<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {

        $this->renderable(function (InvalidPromptException $e, Request $request) {
            return response()->json([
                'error' => 'Invalid prompt',
                'message' => $e->getMessage(),
                'alternativeDescription' => $e->getAlternativeDescription()
            ], $e->getStatusCode());
            
        });


        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
