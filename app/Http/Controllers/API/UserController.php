<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    public function search(Request $request)
    {
        $query = $request->input('query');
        $users = User::where('email', 'LIKE', "%{$query}%")
                     ->orWhere('username', 'LIKE', "%{$query}%")
                     ->orWhere('phone_number', 'LIKE', "%{$query}%")
                     ->get();

        return response()->json($users);
    }
}
