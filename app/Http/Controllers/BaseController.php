<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseController extends Controller
{
    public function sendResponse($result , $message ,$status=200)
    {
        $data = [
            'success' => true,
            'message' => $message,
            'data' => $result,
            'errors' => null ,
            'status' =>$status

        ];
        return response()->json($data);
    }

    public function sendError($erros, $status=401, $message="validation faild")
    {
        $data = [
            'success' => false,
            'message' => $message,
            'data' => null,
            'erros' => $erros ,
            'status' =>$status
        ];
        return response()->json($data);

    }
}
