<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller as Controller;

class BaseController extends Controller
{
    public function handleResponse($data = [], $message = '', $code = 200, $success = true): JsonResponse
    {
        $res['success'] = true;

        if ($data) {
            $res['data'] = $data;
        }

        if ($message) {
            $res['message'] = $message;
        }

        return response()->json($res, $code);
    }

    public function handleError($data = [], $message = '', $code = 200)
    {
        $this->handleResponse($data, $message, $code, false);
    }
}
