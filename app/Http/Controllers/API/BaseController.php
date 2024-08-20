<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    public function sendResponse($result,$messages)
    {
        $response = [
            'status'    =>  true,
            'data'      => $result,
            'message'   => $messages
        ];
        return response()->json($response,200);
    }
    public function sendError($error, $errorMessage = [], $code = 404)
    {
        $response = [
            'status'    => false,
            'error'     => $error
        ];
        if(!empty($errorMessage)){
            $response['data'] = $errorMessage;
        }
        return response()->json($response,$code);
    }
}
