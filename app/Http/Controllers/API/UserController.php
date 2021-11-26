<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $user_credentials = $request->only('email', 'password');

        if(Auth::attempt($user_credentials)) {
            // Authentication passed

            $authuser = auth()->user();
            return response()->json([
                'status' => 'success',
                'message' => 'Login successful'
            ], 200);
        }else {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid email or password'
            ], 401);
        }
    }
}
