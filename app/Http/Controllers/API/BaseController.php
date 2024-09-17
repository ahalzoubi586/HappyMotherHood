<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BaseController extends Controller
{
    public function sendResponse($result)
    {
        $response = [
            'status' => 'success',
            'result' => $result,
        ];
        Log::info($response);
        return response()->json($response, 200);
    }
    public function sendError($result, $code = 200)
    {
        $response = [
            'status' => 'error',
            'result' => $result,
        ];
        Log::info($response);
        return response()->json($response, $code);
    }
}
