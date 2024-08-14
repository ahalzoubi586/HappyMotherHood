<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    public function sendResponse($result)
    {
        $response = [
            'status' => 'success',
            'result' => $result,
        ];
        return response()->json($response, 200);
    }
    public function sendError($result, $code = 404)
    {
        $response = [
            'status' => 'error',
            'result' => $result,
        ];
        return response()->json($response, $code);
    }
}
