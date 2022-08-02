<?php

namespace App\Trait;

/**
 * Trait HandleResponse
 */
trait HandleResponse
{
    public function response($data, string $message = 'Operation successful', int $statusCode = 200)
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }


    public function successResponse($data, string $message = 'Operation successful', int $statusCode = 200)
    {
        return $this->response($data, $message, $statusCode);
    }

    public function errorResponse($data, $message = 'Operation failed', int $statusCode = 400)
    {
        return $this->response($data, $message, $statusCode);
    }
}
