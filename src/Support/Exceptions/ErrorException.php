<?php

namespace Support\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ErrorException extends Exception
{
    protected $statusCode;
    protected $errors;

    public function __construct(string $message = "", int $statusCode = 400, array $errors = [])
    {
        parent::__construct($message, $statusCode);

        $this->statusCode = $statusCode;
        $this->errors = $errors;
    }

    public function render(Request $request): JsonResponse
    {
        return response()->json([
            'message' => $this->getMessage(),
            'errors' => $this->errors,
        ], $this->statusCode);
    }
}
