<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TestAuthController extends Controller
{


    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|email|string|max:255|unique:users,email',
            'password' => 'required|string|min:4'
        ]);
        if($validator->fails()){
            return response()->json([
                'status' => 'failed',
                'message' => 'Credentials validation error.'
            ]);
        }


        // if($registration){
        //     return response()->json([
        //         'status' => 'success',
        //         'data' => 'Successful registered the user'
        //     ], 200);
        // }else{
        //     return response()->json([
        //         'status' => 'error',
        //         'data' => 'Registration process failed'
        //     ]);
        // }
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required','string', 'min:4']
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    protected function guard()
    {
        return Auth::guard();
    }

    public function login(Request $request)
    {
        $email = $request->post('email');
        $password = $request->post('password');
        //User checking if available
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            //Setting the logging response
            $success['name'] = $user->name;
            return response()->json([
                'status' => 'success',
                'data' => $success
            ]);
        }else {
            return response()->json([
                'status' => 'error',
                'data' => 'Unauthorized Access'.' Requests:  '.$email
            ]);
        }
    }
}
