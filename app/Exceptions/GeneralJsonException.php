<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class GeneralJsonException extends Exception
{
    protected $code = 422;
    /**
     * Report the exception.
     */
    // public function report(): void
    // {
    //     // dump('abcde');
    // }

    /**
     * Render the exception into an HTTP response.
     * 
     * @param \Illuminate\Http\Request $request
     */
    public function render($request): JsonResponse
    {
        return new JsonResponse([
            'errors' => [
                'message' => $this->getMessage(),
            ]
        ], $this->code);
    }
}
