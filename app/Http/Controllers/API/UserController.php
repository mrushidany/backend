<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
        $user = $this->create($request->all());
        $user->attachRole($request->role);
        event(new Registered($user));

        Auth::login($user);

        if($user){
            return response()->json([
                'status' => 'success',
                'user' => $user,
                'message' => 'registration successful'
            ], 200);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Could not complete the registration'
            ]);
        }


    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required','string','max:255'],
            'email' => ['required','string','email','max:255','unique:users'],
            'password' => ['required', 'string', 'min:4'],
        ]);
    }

    protected function create(array $data)
    {
        return $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);

    }

    protected function guard()
    {
        return Auth::guard();
    }

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

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Succesfull Log Out'
        ], 200);
    }
}
