<?php

namespace App\Traits;

use Illuminate\Http\Response;

trait ApiResponse
{
    protected function successResponse($data, $message = 'Success', $code = Response::HTTP_OK, $meta = [])
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
            'meta' => $meta,
        ], $code);
    }

    protected function errorResponse($message = 'Error', $code = Response::HTTP_INTERNAL_SERVER_ERROR, $errors = [])
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }
}