<?php

namespace App\Exceptions;

use Exception;

class InvalidPromptException extends Exception
{

    protected $generatedDescription;

    public function __construct(
        $generatedDescription, 
        $message = 'La descripción proporcionada no es válida para una prenda de vestir. Hemos generado una descripción alternativa basada en la información ingresada.')
    {

        $this->generatedDescription = $generatedDescription;

        parent::__construct($message);
    }

    public function getGeneratedDescription()
    {
        return $this->generatedDescription;
    }

    public function toJsonResponse()
    {
        return response()->json([
            'message' => $this->getMessage(),
            'generatedDescription' => $this->getGeneratedDescription()
        ], 400);
    }

}
