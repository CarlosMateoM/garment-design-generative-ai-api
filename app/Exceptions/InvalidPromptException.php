<?php

namespace App\Exceptions;

use Exception;

class InvalidPromptException extends Exception
{

    protected $statusCode;

    protected $alternativeDescription;

    public function __construct($message, $alternativeDescription = '',  $statusCode = 400)
    {
        parent::__construct($message);
        $this->statusCode = $statusCode;
        $this->alternativeDescription = $alternativeDescription;
    }
    
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getAlternativeDescription()
    {
        return $this->alternativeDescription;
    }


}