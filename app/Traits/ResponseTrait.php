<?php

namespace App\Traits;

trait ResponseTrait
{
    public function sendResponse(int $statusCode, string $message, $data = null)
    {
        $response = [
            'message' => $message,
            'status_code' => $statusCode
        ];

        if ($data) {
            $response['data'] = $data;
        }

        return response()->json($response, $statusCode);
    }
}

