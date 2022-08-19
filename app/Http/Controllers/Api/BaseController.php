<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    public function notFound($message = 'Not found')
    {
        return response()->json($message, 404);
    }

    public function validationError($message = 'Validation failed')
    {
        return response()->json($message, 422);
    }

    public function success($data) {
        return response()->json($data);
    }
}
