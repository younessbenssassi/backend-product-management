<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller as Controller;
use function response;
use Illuminate\Http\JsonResponse;

class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @param $result
     * @param $message
     * @return JsonResponse
     */
    public function sendResponse($result, $message) : JsonResponse

    {
    	$response = array_merge([
                'status' => true,
                'message' => $message,
            ],
            $result
        );

        return response()->json($response, 200);
    }


    /**
     * return error response.
     *
     * @param $error
     * @param int $code
     * @return JsonResponse
     */
    public function sendError($error, int $code = 404):JsonResponse
    {
        return response()->json([
            'status' => false,
            'message' => $error,
        ], $code);
    }
}
