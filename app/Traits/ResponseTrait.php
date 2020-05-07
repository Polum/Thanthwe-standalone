<?php

namespace App\Traits;

trait ResponseTrait {

    public function sendResponse($status, $data = null, $message, $code = 404){
        return response()->json(['status'=>$status, 'data'=>$data, 'message'=>$message], $code);
    }

    public function respondAccessError(){
        return response()->json(['status'=>'error', 'data'=>null, 'message'=>'Un-authorized access'], 401);
    }

    public function respondActionComplete($action, $data=null){
        return response()->json(['status'=>'success', 'data'=>$data, 'message'=>$action], 200);
    }

    public function respondSuccess($message){
        return response()->json(['message'=>$message], 200);
    }

    public function respondData($data=null){
        return response()->json($data);
    }

    public function respondNoData($error){
        return response()->json(['error'=>$error], 404);
    }

    public function respondError($error){
        return response()->json(['error'=>$error], 404);
    }

    public function respondIncompleteAction($action, $data = null){
        return response()->json(['status'=>'error', 'data'=>$data, 'error'=>$action], 404);
    }
}
