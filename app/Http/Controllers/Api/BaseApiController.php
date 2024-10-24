<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class BaseApiController extends Controller
{

    /**
     * Send success response
     * 
     * @param mixed $data Data to be returned
     * @param mixed $message Success message
     * @param int $code HTTP status code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendResponse($data, $message = '', $code = 200)
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * Send error response
     * 
     * @param string $message Error message
     * @param array $errors Validation errors
     * @param int $code HTTP status code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendError($message, $errors = [], $code = 400)
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'errors' => $errors
        ], $code);
    }
}
