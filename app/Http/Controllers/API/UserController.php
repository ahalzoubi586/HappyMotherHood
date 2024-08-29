<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends BaseController
{
    public function getAllUsers(Request $request)
    {
        try{
            $users = User::where("user_type","0")->where("id","<>",$request->user()->id)->get();
            Log::info($users);
            return $this->sendResponse($users);
        }
        catch(Exception $e){
            return $this->sendError($e->getMessage());
        }
        
    }
}
